<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentMembership;
use App\Models\Seat;
use App\Models\Shift;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $libraryId = $request->user()->library_id;
        $search = $request->query('search');
        $filter = $request->query('filter'); // active, pending, expired, new
        $id = $request->query('id');

        $query = Student::where('library_id', $libraryId)->with(['activeMembership.seat', 'activeMembership.shift', 'activeMembership.plan', 'payments']);

        if ($id) {
            $id = str_replace('st_', '', $id);
            $query->where('id', $id);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        if ($filter) {
            if ($filter === 'active') {
                $query->whereHas('activeMembership', function($q) {
                    $q->where('end_date', '>=', now());
                });
            } elseif ($filter === 'pending') {
                $query->whereHas('payments', function($q) {
                    $q->where('status', 'PENDING');
                });
            } elseif ($filter === 'expired') {
                $query->whereDoesntHave('activeMembership')
                      ->orWhereHas('memberships', function($q) {
                          $q->where('end_date', '<', now());
                      });
            } elseif ($filter === 'new') {
                $query->where('created_at', '>=', now()->subDays(7));
            }
        }

        $students = $query->latest()->get();

        // Map response to match React Native app format
        $mapped = $students->map(function ($s) {
            return $this->mapStudentData($s);
        });

        if ($id) {
            $first = $mapped->first();
            return response()->json([
                'status' => 'success',
                'data' => $first ? $first : null
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $mapped
        ]);
    }

    public function show(Request $request, $id)
    {
       
        // Strip prefix if any
        $id = str_replace('st_', '', $id);
        $student = Student::where('library_id', $request->user()->library_id)
            ->with(['activeMembership.seat', 'activeMembership.shift', 'activeMembership.plan', 'memberships.seat', 'memberships.shift', 'payments'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $this->mapStudentData($student)
        ]);
    }

    public function store(Request $request)
    {
      
        $libraryId = $request->user()->library_id;

        DB::beginTransaction();
        try {
            // Upload Photo
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('students/photos', 'public');
            }

            // Upload ID Proof
            $idProofPath = null;
            if ($request->hasFile('idProof')) {
                $idProofPath = $request->file('idProof')->store('students/ids', 'public');
            }

            // 1. Create Student
            $student = Student::create([
                'library_id' => $libraryId,
             
                'name' => $request->name,
                'phone' => $request->phone,
                'parent_phone' => $request->parentPhone,
                'email' => $request->email,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'exam_prep' => $request->exam,
                'college' => $request->college,
                'course' => $request->course,
                'photo_url' => $photoPath ? Storage::url($photoPath) : null,
                'id_proof_url' => $idProofPath ? Storage::url($idProofPath) : null,
                'is_active' => true,
            ]);

            // 2. Fetch configured shift and dummy/selected seat
            $shift = Shift::where('library_id', $libraryId)->where('key_name', $request->shift)->first();
            
            if (!$shift) {
                // Auto-create standard shift if it's missing for this new library
                $shift = Shift::create([
                    'library_id' => $libraryId,
                    'name' => ucfirst($request->shift) . ' Shift',
                    'key_name' => $request->shift,
                    'start_time' => '08:00:00',
                    'end_time' => '20:00:00'
                ]);
            }
            
            // Auto allocate next available seat on floor/room if map wasn't specifically used
            $seat = null;
            if ($request->has('seat')) {
                $seat = Seat::where('library_id', $libraryId)
                    ->where('seat_number', $request->seat)
                    ->where('status', 'available')
                    ->first();
            }
            if (!$seat) {
                $seat = Seat::where('library_id', $libraryId)
                    ->where('floor', $request->floor)
                    ->where('room', $request->room)
                    ->where('status', 'available')
                    ->first();
            }
                
            if (!$seat) {
                // Failback to any seat
                $seat = Seat::where('library_id', $libraryId)->where('status', 'available')->first();
            }

            if ($seat) {
                $seat->update(['status' => 'occupied']);
            }

            // Fetch the plan_id from LibraryDetail as requested by the user
            $planId = null;
            $plan = null;
            if ($request->has('plan_id')) {
                $plan = \App\Models\LibraryPlan::find($request->plan_id);
                if ($plan) {
                    $planId = $plan->id;
                }
            }
            
            if (!$planId && $request->has('plan_name')) {
                $plan = \App\Models\LibraryPlan::where('name', $request->plan_name)->first();
                if ($plan) {
                    $planId = $plan->id;
                }
            }
            
            if (!$planId) {
                $libraryDetail = \App\Models\LibraryDetail::where('library_id', $libraryId)->first();
                $planId = $libraryDetail && $libraryDetail->plan_id ? $libraryDetail->plan_id : \App\Models\LibraryPlan::first()->id ?? 1;
                $plan = \App\Models\LibraryPlan::find($planId);
            }

            // 3. Create Student Membership
            $membershipNo = $request->membership_number ?? ('L-' . now()->year . '-' . rand(100, 999));
            $membership = StudentMembership::create([
                'student_id' => $student->id,
                'seat_id' => $seat ? $seat->id : null,
                'shift_id' => $shift->id,
                'plan_id' => $planId,
                'membership_number' => $membershipNo,
                'start_date' => $request->start_date ? \Carbon\Carbon::parse($request->start_date) : now(),
                'end_date' => $request->end_date ? \Carbon\Carbon::parse($request->end_date) : now()->addMonth(),
                'price' => $request->monthlyFee,
                'status' => 'CURRENT'
           
                ]);


            // 4. Create Initial Payments
            $registrationFeeTotal = (float)($request->registrationFeeTotal ?? ($plan ? $plan->price : 0));
            $securityDeposit = (float)($request->securityDeposit ?? 0);
            
            if ($securityDeposit > $registrationFeeTotal) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Security deposit cannot be greater than the total registration fee.'
                ], 422);
            }
            
            $totalAmount = $registrationFeeTotal;
            
            // "isme se security amount deduct hoga baki ka pending amount m jayega"
            $paidAmount = $securityDeposit;
            
            $pendingAmount = $totalAmount - $paidAmount;
            if ($pendingAmount < 0) {
                $pendingAmount = 0;
            }
            
            $receiptNumber = 'REC-' . time() . rand(10, 99);

            $payment = Payment::create([
                'student_id' => $student->id,
                'student_membership_id' => $membership->id,
                'amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'pending_amount' => $pendingAmount,
                'admission_fee' => 0,
                'security_deposit' => $securityDeposit,
                'payment_method' => 'cash', // Default startup
                'payment_date' => now(),
                'receipt_number' => $receiptNumber,
                'status' => $pendingAmount > 0 ? 'PENDING' : 'PAID'
            ]);

            // Create initial wallet transaction if anything was paid
            if ($paidAmount > 0) {
                PaymentTransaction::create([
                    'student_id' => $student->id,
                    'payment_id' => $payment->id,
                    'amount' => $paidAmount,
                    'payment_method' => 'cash',
                    'receipt_number' => $receiptNumber,
                    'transaction_id' => 'TXN-' . time(),
                    'note' => 'Initial security deposit / registration payment'
                ]);
            }

            DB::commit();

            $student->load(['activeMembership.seat', 'activeMembership.shift', 'activeMembership.plan', 'payments']);

            return response()->json([
                'status' => 'success',
                'message' => 'Student registered successfully',
                'data' => $this->mapStudentData($student)
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create student: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {


        $studentId = $request->route('student') ?? $request->input('id') ?? $request->query('id');
        $studentId = str_replace('st_', '', $studentId);

        $student = Student::findOrFail($studentId);

        $student->update($request->except(['id', '_method', '_token']));

        return response()->json([
            'status' => 'success',
            'message' => 'Student details updated successfully',
            'data' => $student
        ]);
    }



public function destroy(Request $request)
{
    $id = $request->input('id') ?? $request->query('id');

    if (!$id) {
        return response()->json([
            'status' => 'error',
            'message' => 'Student ID is required.'
        ], 422);
    }

    $id = str_replace('st_', '', $id);

    $student = Student::find($id);

    if (!$student) {
        return response()->json([
            'status' => 'error',
            'message' => 'Student not found.'
        ], 404);
    }

    // Release seat
    if ($student->activeMembership && $student->activeMembership->seat) {
        $student->activeMembership->seat->update([
            'status' => 'available'
        ]);
    }

    $student->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Student archived successfully'
    ]);
}

    public function membershipHistory(Request $request, $id)
    {
        $id = str_replace('st_', '', $id);
        $memberships = StudentMembership::where('student_id', $id)
            ->with(['seat', 'shift', 'plan'])
            ->latest()
            ->get();

        $mapped = $memberships->map(function ($m) {
            return [
                'id' => $m->id,
                'start_date' => $m->start_date ? \Carbon\Carbon::parse($m->start_date)->format('Y-m-d') : null,
                'end_date' => $m->end_date ? \Carbon\Carbon::parse($m->end_date)->format('Y-m-d') : null,
                'plan' => $m->plan ? $m->plan->name : null,
                'shift' => $m->shift ? $m->shift->name : null,
                'price' => $m->price,
                'status' => $m->status,
                'membership_number' => $m->membership_number
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $mapped
        ]);
    }

    private function mapStudentData($s)
    {
        $activeMember = $s->activeMembership;
        $hasPending = $s->payments()->where('status', 'PENDING')->sum('pending_amount');
        
        return [
            'id' => 'st_' . $s->id,
            'original_id' => $s->id,
            'student_id' => 'st_' . $s->id,
            'name' => $s->name,
            'phone' => $s->phone,
            'parent_phone' => $s->parent_phone,
            'email' => $s->email,
            'gender' => $s->gender,
            'dob' => $s->dob,
            'exam_prep' => $s->exam_prep,
            'college' => $s->college,
            'course' => $s->course,
            'floor' => $activeMember && $activeMember->seat ? $activeMember->seat->floor : null,
            'room' => $activeMember && $activeMember->seat ? $activeMember->seat->room : null,
            'shift' => $activeMember && $activeMember->shift ? $activeMember->shift->name : null,
            'membershipId' => $activeMember ? $activeMember->membership_number : 'N/A',
            'plan_id' => $activeMember ? (int)$activeMember->plan_id : null,
            'plan' => $activeMember && $activeMember->plan ? [
                'id' => $activeMember->plan->id,
                'name' => $activeMember->plan->name,
                'price' => $activeMember->plan->price,
                'duration' => $activeMember->plan->duration
            ] : null,
            'period' => $activeMember ? [
                'from' => $activeMember->start_date ? \Carbon\Carbon::parse($activeMember->start_date)->format('Y-m-d') : null,
                'to' => $activeMember->end_date ? \Carbon\Carbon::parse($activeMember->end_date)->format('Y-m-d') : null
            ] : null,
            'seat' => $activeMember && $activeMember->seat ? $activeMember->seat->seat_number : null,
            'pendingAmount' => (int)$hasPending,
            'active' => $s->is_active && $activeMember ? true : false,
            'status' => $hasPending > 0 ? 'PENDING' : ($activeMember ? 'PAID' : 'EXPIRED'),
            'photo_url' => $s->photo_url,
            'id_proof_url' => $s->id_proof_url,
            'receipt_number' => $s->payments->sortByDesc('created_at')->first()->receipt_number ?? null,
        ];
    }
}
