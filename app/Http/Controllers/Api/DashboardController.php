<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Seat;
use App\Models\Payment;
use App\Models\StudentMembership;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getGreeting(Request $request)
    {
        $hour = now()->hour;
        $greeting = 'Good Evening';
        if ($hour < 12) $greeting = 'Good Morning';
        elseif ($hour < 17) $greeting = 'Good Afternoon';

        return response()->json([
            'status' => 'success',
            'data' => [
                'greeting' => $greeting,
                'dateLine' => now()->format('l, F d, Y'),
                'welcomeLine' => 'Welcome back, ' . $request->user()->name
            ]
        ]);
    }

    public function getStats(Request $request)
    {
        $libraryId = $request->user()->library_id;

        // KPI Counts
        $totalStudents = Student::where('library_id', $libraryId)->count();
        $occupiedSeats = Seat::where('library_id', $libraryId)->where('status', 'occupied')->count();
        $vacantSeats = Seat::where('library_id', $libraryId)->where('status', 'available')->count();
        
        // Expiry calculations (expiring in next 7 days)
        $expiringSoon = StudentMembership::whereHas('student', function($q) use ($libraryId) {
            $q->where('library_id', $libraryId);
        })
        ->where('status', 'CURRENT')
        ->whereBetween('end_date', [now(), now()->addDays(7)])
        ->count();

        // Revenue calculations
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $monthlyRevenue = Payment::whereHas('student', function($q) use ($libraryId) {
            $q->where('library_id', $libraryId);
        })
        ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])
        ->sum('paid_amount');

        // Total Pending outstanding payments
        $pendingFees = Payment::whereHas('student', function($q) use ($libraryId) {
            $q->where('library_id', $libraryId);
        })
        ->where('status', 'PENDING')
        ->sum('pending_amount');

        return response()->json([
            'status' => 'success',
            'data' => [
                [
                    'id' => 'totalStudents',
                    'label' => 'Total Students',
                    'value' => $totalStudents,
                    'icon' => 'group',
                    'accent' => 'PRIMARY',
                    'screen' => 'Students'
                ],
                [
                    'id' => 'occupiedSeats',
                    'label' => 'Occupied Seats',
                    'value' => $occupiedSeats,
                    'icon' => 'event_seat',
                    'accent' => 'SECONDARY',
                    'screen' => 'Seats'
                ],
                [
                    'id' => 'monthlyRevenue',
                    'label' => 'Monthly Revenue',
                    'value' => '₹' . number_format($monthlyRevenue),
                    'icon' => 'payments',
                    'accent' => 'TERTIARY_CONTAINER',
                    'screen' => 'Fees'
                ],
                [
                    'id' => 'pendingFees',
                    'label' => 'Pending Fees',
                    'value' => '₹' . number_format($pendingFees),
                    'icon' => 'warning',
                    'accent' => 'ERROR',
                    'screen' => 'Fees'
                ],
                [
                    'id' => 'expiringSoon',
                    'label' => 'Expiring Soon',
                    'value' => $expiringSoon,
                    'icon' => 'calendar_clock',
                    'accent' => 'TERTIARY',
                    'screen' => 'Students'
                ],
                [
                    'id' => 'vacantSeats',
                    'label' => 'Vacant Seats',
                    'value' => $vacantSeats,
                    'icon' => 'door_open',
                    'accent' => 'OUTLINE',
                    'screen' => 'Seats'
                ]
            ]
        ]);
    }
}
