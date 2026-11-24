<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Library;
use App\Models\User;
use App\Models\Shift;
use App\Models\Plan;
use App\Models\Seat;
use App\Models\Student;
use App\Models\StudentMembership;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Core Library
        $library = Library::create([
            'name' => 'Central Library Branch',
            'owner_name' => 'Rahul Sharma',
            'email' => 'rahul@librarymanagerpro.com',
            'phone' => '+91 98765 43210',
            'address' => '42, Vidya Vihar Colony, Near Coaching Hub, Indore, MP',
            'capacity' => 90
        ]);

        // 2. Create Owner User Account
        User::create([
            'library_id' => $library->id,
            'name' => 'Rahul',
            'email' => 'rahul@librarymanagerpro.com',
            'phone' => '9876543210',
            'password' => Hash::make('password123'), // matching demo
            'role' => 'owner'
        ]);

        // 3. Create Standard Operating Shifts
        $shifts = [
            ['name' => 'Morning', 'key_name' => 'morning', 'start_time' => '06:00:00', 'end_time' => '12:00:00', 'icon' => 'light_mode'],
            ['name' => 'Afternoon', 'key_name' => 'afternoon', 'start_time' => '12:00:00', 'end_time' => '17:00:00', 'icon' => 'sunny'],
            ['name' => 'Evening', 'key_name' => 'evening', 'start_time' => '17:00:00', 'end_time' => '22:00:00', 'icon' => 'dark_mode'],
            ['name' => 'Full Day', 'key_name' => 'fullDay', 'start_time' => '06:00:00', 'end_time' => '22:00:00', 'icon' => 'history'],
        ];
        
        foreach ($shifts as $s) {
            Shift::create(array_merge($s, ['library_id' => $library->id]));
        }

        // 4. Create Standard Membership Plans
        $plans = [
            ['name' => 'Basic Plan', 'duration_months' => 1, 'price' => 1000.00, 'description' => 'Single Shift Study Desk Access'],
            ['name' => 'Standard Plan', 'duration_months' => 1, 'price' => 1500.00, 'description' => 'Two Shifts Study Desk Access'],
            ['name' => 'Platinum Plan', 'duration_months' => 1, 'price' => 2500.00, 'description' => 'Full Day Premium Cabin Access'],
        ];

        foreach ($plans as $p) {
            Plan::create(array_merge($p, ['library_id' => $library->id]));
        }

        // 5. Seed Seats Grid Layout
        $floors = ['Ground Floor', '1st Floor', '2nd Floor'];
        $rooms = ['Room A-1', 'Room A-2', 'Quiet Zone'];
        
        // Create 20 seats for mockup demo mapping
        $seatNumbers = [
            ['num' => 'A-01', 'zone' => 'A', 'row' => 0, 'col' => 0, 'premium' => false, 'status' => 'available'],
            ['num' => 'A-02', 'zone' => 'A', 'row' => 0, 'col' => 1, 'premium' => false, 'status' => 'available'],
            ['num' => 'A-03', 'zone' => 'A', 'row' => 0, 'col' => 2, 'premium' => false, 'status' => 'occupied'],
            ['num' => 'A-04', 'zone' => 'A', 'row' => 0, 'col' => 3, 'premium' => false, 'status' => 'available'],
            ['num' => 'A-05', 'zone' => 'A', 'row' => 1, 'col' => 0, 'premium' => false, 'status' => 'occupied'],
            ['num' => 'A-06', 'zone' => 'A', 'row' => 1, 'col' => 1, 'premium' => false, 'status' => 'available'],
            ['num' => 'A-07', 'zone' => 'A', 'row' => 1, 'col' => 2, 'premium' => false, 'status' => 'reserved'],
            ['num' => 'A-08', 'zone' => 'A', 'row' => 1, 'col' => 3, 'premium' => false, 'status' => 'available'],
            ['num' => 'A-12', 'zone' => 'A', 'row' => 2, 'col' => 3, 'premium' => false, 'status' => 'blocked'],
            ['num' => 'B-01', 'zone' => 'B', 'row' => 0, 'col' => 0, 'premium' => false, 'status' => 'available'],
            ['num' => 'B-02', 'zone' => 'B', 'row' => 0, 'col' => 1, 'premium' => false, 'status' => 'occupied'],
            ['num' => 'B-03', 'zone' => 'B', 'row' => 0, 'col' => 2, 'premium' => false, 'status' => 'available'],
            ['num' => 'W-1', 'zone' => 'C', 'row' => 0, 'col' => 0, 'premium' => true, 'status' => 'reserved'],
            ['num' => 'W-2', 'zone' => 'C', 'row' => 0, 'col' => 1, 'premium' => true, 'status' => 'available'],
            ['num' => 'W-4', 'zone' => 'C', 'row' => 0, 'col' => 3, 'premium' => true, 'status' => 'occupied'],
        ];

        foreach ($seatNumbers as $sData) {
            Seat::create([
                'library_id' => $library->id,
                'seat_number' => $sData['num'],
                'zone' => $sData['zone'],
                'row' => $sData['row'],
                'col' => $sData['col'],
                'floor' => 'Floor 1 - Main Hall',
                'room' => 'Main Area',
                'is_premium' => $sData['premium'],
                'status' => $sData['status'],
            ]);
        }

        // 6. Seed Students (Mock values matching app)
        $studentsList = [
            ['name' => 'Arjun Sharma', 'phone' => '+91 98765 43210', 'gender' => 'Male', 'prep' => 'UPSC', 'seat' => 'A-03'],
            ['name' => 'Priya Verma', 'phone' => '+91 87654 32109', 'gender' => 'Female', 'prep' => 'SSC', 'seat' => 'B-02'],
            ['name' => 'Rahul Mehta', 'phone' => '+91 76543 21098', 'gender' => 'Male', 'prep' => 'Banking', 'seat' => null],
            ['name' => 'Sneha Kapoor', 'phone' => '+91 99887 76655', 'gender' => 'Female', 'prep' => 'JEE/NEET', 'seat' => 'W-4'],
            ['name' => 'Amit Kumar', 'phone' => '+91 98712 34567', 'gender' => 'Male', 'prep' => 'Other', 'seat' => null],
        ];

        foreach ($studentsList as $index => $sData) {
            $student = Student::create([
                'library_id' => $library->id,
                'name' => $sData['name'],
                'phone' => $sData['phone'],
                'parent_phone' => '+91 99999 88888',
                'email' => strtolower(str_replace(' ', '.', $sData['name'])) . '@gmail.com',
                'gender' => $sData['gender'],
                'dob' => '2001-08-15',
                'exam_prep' => $sData['prep'],
                'college' => 'State University',
                'course' => 'Graduate Studies',
                'is_active' => true,
            ]);

            // Assign Active Membership for PAID / CURRENT status students
            $seatObj = $sData['seat'] ? Seat::where('seat_number', $sData['seat'])->first() : null;
            
            // Choose Plan based on Index
            $planId = ($index % 3) + 1; // 1, 2, 3
            $plan = Plan::find($planId);
            $shift = Shift::where('key_name', $planId == 3 ? 'fullDay' : 'morning')->first();

            $membershipNo = 'L-2024-' . rand(100, 999);
            
            // Membership ends tomorrow for Amit to show pending billing
            $endDate = ($sData['name'] == 'Amit Kumar' || $sData['name'] == 'Rahul Mehta') ? now()->subDays(2) : now()->addDays(20);

            $membership = StudentMembership::create([
                'student_id' => $student->id,
                'seat_id' => $seatObj ? $seatObj->id : null,
                'shift_id' => $shift->id,
                'plan_id' => $plan->id,
                'membership_number' => $membershipNo,
                'start_date' => now()->subDays(10),
                'end_date' => $endDate,
                'price' => $plan->price,
                'status' => $endDate->isPast() ? 'EXPIRED' : 'CURRENT'
            ]);

            // Seed Payment
            Payment::create([
                'student_id' => $student->id,
                'student_membership_id' => $membership->id,
                'amount' => $plan->price,
                'admission_fee' => 500,
                'security_deposit' => 1000,
                'payment_method' => 'upi',
                'payment_date' => now()->subDays(10),
                'receipt_number' => 'REC-' . time() . rand(10, 99),
                'status' => $endDate->isPast() ? 'PENDING' : 'PAID' // pending if membership expired
            ]);
        }
    }
}
