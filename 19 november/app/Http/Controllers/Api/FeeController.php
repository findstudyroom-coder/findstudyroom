<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentMembership;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;

class FeeController extends Controller
{
    public function getOutstanding(Request $request)
    {
        $libraryId = $request->user()->library_id;
        
        $pendingPayments = Payment::whereHas('student', function($q) use ($libraryId) {
            $q->where('library_id', $libraryId);
        })
        ->whereIn('status', ['PENDING', 'PARTIALLY_PAID'])
        ->with('student.activeMembership.seat')
        ->get();

        $mapped = $pendingPayments->map(function ($p) {
            $s = $p->student;
            $initials = collect(explode(' ', $s->name))->map(fn($n) => mb_substr($n, 0, 1))->join('');
            
            return [
                'id' => 'os' . $p->id,
                'student_id' => 'st_' . $s->id,
                'name' => $s->name,
                'seat' => $s->activeMembership && $s->activeMembership->seat ? $s->activeMembership->seat->seat_number : 'N/A',
                'amount' => (int)($p->pending_amount > 0 || $p->status == 'PARTIALLY_PAID' ? $p->pending_amount : $p->amount),
                'total_amount' => (int)$p->amount,
                'paid_amount' => (int)$p->paid_amount,
                'initials' => strtoupper(substr($initials, 0, 2)),
                'avatarColor' => '#DBE1FF'
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $mapped
        ]);
    }

    public function collectPayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string', // upi, cash, bank
            'note' => 'nullable|string'
        ]);

        $studentId = str_replace('st_', '', $request->student_id);
        $student = Student::findOrFail($studentId);

        // Find pending payment or create new transactional payment invoice
        $pendingPayment = Payment::where('student_id', $student->id)
            ->whereIn('status', ['PENDING'])
            ->first();

        if (!$pendingPayment) {
            return response()->json([
                'status' => 'error',
                'message' => 'No pending payments found for this student.'
            ], 422);
        }

        $transactionAmount = (float)$request->amount;

        if ($transactionAmount > (float)$pendingPayment->pending_amount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment amount cannot be greater than the pending amount (₹' . $pendingPayment->pending_amount . ').'
            ], 422);
        }

        $newPendingAmount = (float)$pendingPayment->pending_amount - $transactionAmount;
        
        // Update the main invoice record to reflect the new balances
        $pendingPayment->update([
            'paid_amount' => (float)$pendingPayment->paid_amount + $transactionAmount,
            'pending_amount' => $newPendingAmount,
            'status' => $newPendingAmount <= 0 ? 'PAID' : 'PENDING'
        ]);
        
        $receiptNumber = 'REC-' . time() . rand(10, 99);

        PaymentTransaction::create([
            'student_id' => $student->id,
            'payment_id' => $pendingPayment->id,
            'amount' => $transactionAmount,
            'payment_method' => $request->payment_method,
            'receipt_number' => $receiptNumber,
            'transaction_id' => 'TXN-' . time(),
            'note' => $request->note
        ]);
        
        $pendingPayment->load(['student.activeMembership.seat', 'membership']);
        $s = $pendingPayment->student;
        $activeMember = $s->activeMembership;

        return response()->json([
            'status' => 'success',
            'message' => 'Payment collected successfully',
            'data' => [
                'receipt_number' => $pendingPayment->receipt_number,
                'student_id' => 'st_' . $s->id,
                'student_name' => $s->name,
                'phone' => $s->phone,
                'seat' => $activeMember && $activeMember->seat ? $activeMember->seat->seat_number : null,
                'paid_amount' => $pendingPayment->paid_amount,
                'amount' => $pendingPayment->amount, // Added for frontend compatibility
                'paidAmount' => $pendingPayment->paid_amount, // Added for frontend compatibility
                'pendingAmount' => $pendingPayment->pending_amount, // Added for frontend compatibility
                'payment_method' => $pendingPayment->payment_method,
                'payment_date' => \Carbon\Carbon::parse($pendingPayment->payment_date)->format('Y-m-d H:i:s'),
                'membership_start' => $pendingPayment->membership ? \Carbon\Carbon::parse($pendingPayment->membership->start_date)->format('Y-m-d') : null,
                'membership_end' => $pendingPayment->membership ? \Carbon\Carbon::parse($pendingPayment->membership->end_date)->format('Y-m-d') : null,
                'membership_period' => $pendingPayment->membership ? (\Carbon\Carbon::parse($pendingPayment->membership->start_date)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($pendingPayment->membership->end_date)->format('M d, Y')) : null,
                'dueAmount' => $pendingPayment ? $newPendingAmount : 0, // This is technically old balance before this transaction
                'balance' => $pendingPayment->pending_amount,
                'lastPaidAt' => \Carbon\Carbon::parse($pendingPayment->payment_date)->format('Y-m-d H:i:s'),
                'nextDueDate' => $activeMember && $activeMember->end_date ? \Carbon\Carbon::parse($activeMember->end_date)->addDay()->format('Y-m-d') : null,
                'paymentStatus' => $pendingPayment->status
            ]
        ]);
    }

    public function getReceipt(Request $request, $id)
    {
        $payment = Payment::with(['student.activeMembership.seat', 'membership.shift', 'membership.plan'])
            ->where(function($query) use ($id) {
                if (str_starts_with($id, 'REC-')) {
                    $query->where('receipt_number', $id);
                } else {
                    $query->where('id', $id);
                }
            })->firstOrFail();

        $s = $payment->student;
        $activeMember = $s->activeMembership ?? $payment->membership;

        return response()->json([
            'status' => 'success',
            'data' => [
                'receipt_number' => $payment->receipt_number,
                'student_id' => 'st_' . $s->id,
                'student_name' => $s->name,
                'phone' => $s->phone,
                'seat' => $activeMember && $activeMember->seat ? $activeMember->seat->seat_number : null,
                'security_deposit' => (float)$payment->security_deposit,
                //'paid_amount' => (float)$payment->paid_amount,
                'amount' => $payment->amount, // Added for frontend compatibility
                'paidAmount' => (float)$payment->paid_amount, // Added for frontend compatibility
                'pendingAmount' => $payment->pending_amount, // Added for frontend compatibility
                'payment_method' => $payment->payment_method,
                'payment_date' => \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d H:i:s'),
                'membership_start' => $payment->membership ? \Carbon\Carbon::parse($payment->membership->start_date)->format('Y-m-d') : null,
                'membership_end' => $payment->membership ? \Carbon\Carbon::parse($payment->membership->end_date)->format('Y-m-d') : null,
                'membership_period' => $payment->membership ? (\Carbon\Carbon::parse($payment->membership->start_date)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($payment->membership->end_date)->format('M d, Y')) : null,
                //'dueAmount' => $payment->pending_amount,
                //'balance' => $payment->pending_amount,
                'lastPaidAt' => \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d H:i:s'),
                'nextDueDate' => $activeMember && $activeMember->end_date ? \Carbon\Carbon::parse($activeMember->end_date)->addDay()->format('Y-m-d') : null,
                'paymentStatus' => $payment->status
            ]
        ]);
    }

    public function getFeeStats(Request $request)
    {
        $libraryId = $request->user()->library_id;
        
        $pendingAmount = Payment::whereHas('student', function($q) use ($libraryId) {
            $q->where('library_id', $libraryId);
        })
        ->whereIn('status', ['PENDING'])
        ->sum('pending_amount');

        $unpaidCount = Payment::whereHas('student', function($q) use ($libraryId) {
            $q->where('library_id', $libraryId);
        })
        ->whereIn('status', ['PENDING'])
        ->select('student_id')
        ->distinct()
        ->count();

        $collectedThisMonth = Payment::whereHas('student', function($q) use ($libraryId) {
            $q->where('library_id', $libraryId);
        })
        ->whereMonth('payment_date', now()->month)
        ->whereYear('payment_date', now()->year)
        ->sum('paid_amount');

        return response()->json([
            'status' => 'success',
            'data' => [
                'collectedThisMonth' => (float)$collectedThisMonth,
                'pendingThisMonth' => (float)$pendingAmount,
                'unpaidStudents' => (int)$unpaidCount
            ]
        ]);
    }

    public function paymentHistory(Request $request)
    {
        $libraryId = $request->user()->library_id;
        
        $studentId = $request->query('student_id');

        $query = Payment::whereHas('student', function($q) use ($libraryId) {
            $q->where('library_id', $libraryId);
        });

        if ($studentId) {
            $studentId = str_replace('st_', '', $studentId);
            $query->where('student_id', $studentId);
        }

        $payments = $query->with(['student.activeMembership.seat', 'membership'])
        ->latest()
        ->get();

        $mapped = $payments->map(function ($p) {
            $s = $p->student;
            $activeMember = $s->activeMembership ?? $p->membership; // Fallback to payment's membership if active is missing
            return [
                'receipt_number' => $p->receipt_number,
                'student_id' => $s ? 'st_' . $s->id : null,
                'student_name' => $s ? $s->name : 'Unknown',
                'phone' => $s ? $s->phone : null,
                'seat' => $activeMember && $activeMember->seat ? $activeMember->seat->seat_number : null,
                'paid_amount' => $p->paid_amount,
                'amount' => $p->amount, // Added for frontend compatibility
                'paidAmount' => $p->paid_amount, // Added for frontend compatibility
                'pendingAmount' => $p->pending_amount, // Added for frontend compatibility
                'payment_method' => $p->payment_method,
                'payment_date' => $p->payment_date ? \Carbon\Carbon::parse($p->payment_date)->format('Y-m-d H:i:s') : null,
                'membership_start' => $activeMember && $activeMember->start_date ? \Carbon\Carbon::parse($activeMember->start_date)->format('Y-m-d') : null,
                'membership_end' => $activeMember && $activeMember->end_date ? \Carbon\Carbon::parse($activeMember->end_date)->format('Y-m-d') : null,
                'membership_period' => $activeMember ? (\Carbon\Carbon::parse($activeMember->start_date)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($activeMember->end_date)->format('M d, Y')) : null,
                'dueAmount' => $p->pending_amount,
                'balance' => $p->pending_amount,
                'lastPaidAt' => $p->payment_date ? \Carbon\Carbon::parse($p->payment_date)->format('Y-m-d H:i:s') : null,
                'nextDueDate' => $activeMember && $activeMember->end_date ? \Carbon\Carbon::parse($activeMember->end_date)->addDay()->format('Y-m-d') : null,
                'paymentStatus' => $p->status
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $mapped
        ]);
    }

    public function getDueStudents(Request $request)
    {
        $libraryId = $request->user()->library_id;
        
        $pendingPayments = Payment::whereHas('student', function($q) use ($libraryId) {
            $q->where('library_id', $libraryId);
        })
        ->whereIn('status', ['PENDING', 'PARTIALLY_PAID'])
        ->with(['student.activeMembership.seat', 'membership'])
        ->latest()
        ->get();

        $mapped = $pendingPayments->map(function ($p) {
            $s = $p->student;
            $activeMember = $s->activeMembership ?? $p->membership;
            return [
                'student_id' => $s ? $s->id : null,
                'student_name' => $s ? $s->name : 'Unknown',
                'phone' => $s ? $s->phone : null,
                'seat' => $activeMember && $activeMember->seat ? $activeMember->seat->seat_number : null,
                'dueAmount' => $p->pending_amount,
                'totalAmount' => $p->amount,
                'lastPaidAt' => $p->payment_date ? \Carbon\Carbon::parse($p->payment_date)->format('Y-m-d H:i:s') : null,
                'nextDueDate' => $activeMember && $activeMember->end_date ? \Carbon\Carbon::parse($activeMember->end_date)->addDay()->format('Y-m-d') : null,
                'membershipStart' => $activeMember ? $activeMember->start_date : null,
                'membershipEnd' => $activeMember ? $activeMember->end_date : null,
                'receiptNumber' => $p->receipt_number,
                'paymentStatus' => $p->status,
                'balance' => $p->pending_amount
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $mapped
        ]);
    }

    public function getPendingStudents(Request $request)
    {
        $libraryId = $request->user()->library_id;
        
        $pendingPayments = Payment::whereHas('student', function($q) use ($libraryId) {
            $q->where('library_id', $libraryId);
        })
        ->whereIn('status', ['PENDING', 'PARTIALLY_PAID'])
        ->with(['student.activeMembership.seat', 'membership'])
        ->latest()
        ->get();

        $mapped = $pendingPayments->map(function ($p) {
            $s = $p->student;
            $activeMember = $s->activeMembership ?? $p->membership;
            return [
                'dueAmount' => $p->pending_amount ?: null,
                'lastPaidAt' => $p->payment_date ? \Carbon\Carbon::parse($p->payment_date)->format('Y-m-d H:i:s') : null,
                'nextDueDate' => ($activeMember && $activeMember->end_date) ? \Carbon\Carbon::parse($activeMember->end_date)->addDay()->format('Y-m-d') : null,
                'membershipStart' => ($activeMember && $activeMember->start_date) ? $activeMember->start_date : null,
                'membershipEnd' => ($activeMember && $activeMember->end_date) ? $activeMember->end_date : null,
                'receiptNumber' => $p->receipt_number ?: null,
                'paymentStatus' => $p->status ?: null,
                'balance' => $p->pending_amount ?: null
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $mapped->isEmpty() ? null : $mapped
        ]);
    }
    public function getWalletHistory(Request $request, $studentId)
    {
        $studentId = str_replace('st_', '', $studentId);
        $student = Student::findOrFail($studentId);

        $transactions = $student->transactions()->latest()->get();

        $mapped = $transactions->map(function ($t) {
            return [
                'id' => $t->id,
                'amount' => (float)$t->amount,
                'payment_method' => $t->payment_method,
                'receipt_number' => $t->receipt_number,
                'transaction_id' => $t->transaction_id,
                'note' => $t->note,
                'date' => \Carbon\Carbon::parse($t->created_at)->format('Y-m-d H:i:s')
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $mapped
        ]);
    }
}
