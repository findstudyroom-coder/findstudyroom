<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentMembership;
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
            ->whereIn('status', ['PENDING', 'PARTIALLY_PAID'])
            ->first();

        $membership = $student->activeMembership;
        $transactionAmount = $request->amount;
        $newPendingAmount = 0;

        if ($pendingPayment) {
            $newPendingAmount = $pendingPayment->pending_amount - $transactionAmount;
            
            // Update the main invoice record to reflect the new balances
            $pendingPayment->update([
                'paid_amount' => $pendingPayment->paid_amount + $transactionAmount,
                'pending_amount' => max($newPendingAmount, 0),
                'status' => $newPendingAmount <= 0 ? 'PAID' : 'PARTIALLY_PAID'
            ]);
            $membershipId = $pendingPayment->student_membership_id;
        } else {
            $membershipId = $membership ? $membership->id : null;
        }

        // Create a new transactional payment log
        $payment = Payment::create([
            'student_id' => $student->id,
            'student_membership_id' => $membershipId,
            'amount' => $transactionAmount,
            'paid_amount' => $transactionAmount,
            'pending_amount' => max($newPendingAmount, 0),
            'payment_method' => $request->payment_method,
            'payment_date' => now(),
            'transaction_id' => 'TXN-' . time(),
            'receipt_number' => 'REC-' . time() . rand(10, 99),
            'note' => $request->note,
            'status' => 'PAID'
        ]);

        $payment->load(['student.activeMembership.seat', 'membership']);
        $s = $payment->student;
        $activeMember = $s->activeMembership;

        return response()->json([
            'status' => 'success',
            'message' => 'Payment collected successfully',
            'data' => [
                'receipt_number' => $payment->receipt_number,
                'student_id' => 'st_' . $s->id,
                'student_name' => $s->name,
                'phone' => $s->phone,
                'seat' => $activeMember && $activeMember->seat ? $activeMember->seat->seat_number : null,
                'paid_amount' => $payment->paid_amount,
                'amount' => $payment->amount, // Added for frontend compatibility
                'paidAmount' => $payment->paid_amount, // Added for frontend compatibility
                'pendingAmount' => $payment->pending_amount, // Added for frontend compatibility
                'payment_method' => $payment->payment_method,
                'payment_date' => \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d H:i:s'),
                'membership_start' => $payment->membership ? \Carbon\Carbon::parse($payment->membership->start_date)->format('Y-m-d') : null,
                'membership_end' => $payment->membership ? \Carbon\Carbon::parse($payment->membership->end_date)->format('Y-m-d') : null,
                'membership_period' => $payment->membership ? (\Carbon\Carbon::parse($payment->membership->start_date)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($payment->membership->end_date)->format('M d, Y')) : null,
                'dueAmount' => $pendingPayment ? $newPendingAmount : 0, // This is technically old balance before this transaction
                'balance' => $payment->pending_amount,
                'lastPaidAt' => \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d H:i:s'),
                'nextDueDate' => $activeMember && $activeMember->end_date ? \Carbon\Carbon::parse($activeMember->end_date)->addDay()->format('Y-m-d') : null,
                'paymentStatus' => $payment->status
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
                'paid_amount' => $payment->paid_amount,
                'amount' => $payment->amount, // Added for frontend compatibility
                'paidAmount' => $payment->paid_amount, // Added for frontend compatibility
                'pendingAmount' => $payment->pending_amount, // Added for frontend compatibility
                'payment_method' => $payment->payment_method,
                'payment_date' => \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d H:i:s'),
                'membership_start' => $payment->membership ? \Carbon\Carbon::parse($payment->membership->start_date)->format('Y-m-d') : null,
                'membership_end' => $payment->membership ? \Carbon\Carbon::parse($payment->membership->end_date)->format('Y-m-d') : null,
                'membership_period' => $payment->membership ? (\Carbon\Carbon::parse($payment->membership->start_date)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($payment->membership->end_date)->format('M d, Y')) : null,
                'dueAmount' => $payment->pending_amount,
                'balance' => $payment->pending_amount,
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
        ->whereIn('status', ['PENDING', 'PARTIALLY_PAID'])
        ->sum(DB::raw('CASE WHEN status = "PARTIALLY_PAID" THEN pending_amount ELSE amount END'));

        $unpaidCount = Payment::whereHas('student', function($q) use ($libraryId) {
            $q->where('library_id', $libraryId);
        })
        ->whereIn('status', ['PENDING', 'PARTIALLY_PAID'])
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
}
