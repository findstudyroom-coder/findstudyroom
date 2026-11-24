<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\SeatController;
use App\Http\Controllers\Api\FeeController;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\PublicApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Routes

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

// Authenticated Routes
Route::get('/library-types', [PublicApiController::class, 'getLibraryTypes']);
Route::get('/states', [PublicApiController::class, 'getStates']);
Route::get('/districts', [PublicApiController::class, 'getDistricts']);

Route::middleware('auth:sanctum')->group(function () {
    
    // Auth & Profile
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::get('/auth/plans', [AuthController::class, 'getUserPlans']);
       Route::post('/auth/fcm-token', [AuthController::class, 'saveFcmToken']);
       
            Route::delete('/auth/fcm-token', [AuthController::class, 'deleteFcmToken']);
    // Dashboard Stats
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/dashboard/greeting', [DashboardController::class, 'getGreeting']);
    
    // Student Management
    Route::get('/students', [StudentController::class, 'index']);
    Route::post('/students', [StudentController::class, 'store']);
    Route::get('/students/{student}', [StudentController::class, 'show']);
    Route::post('/students/update', [StudentController::class, 'update']);
    Route::delete('/students/delete', [StudentController::class, 'destroy']);
    Route::get('/students/{student}/memberships', [StudentController::class, 'membershipHistory']);
    Route::get('/students/{student}/wallet-history', [FeeController::class, 'getWalletHistory']);
    
    // Seating Management
    Route::get('/seats/layout', [SeatController::class, 'getLayout']);
    Route::get('/seats/floors', [SeatController::class, 'getFloors']);
    Route::post('/seats/allocate', [SeatController::class, 'allocateSeat']);
    Route::post('/seats/release', [SeatController::class, 'releaseSeat']);
    
    // Fees & Transactions
    Route::get('/fees/due-students', [FeeController::class, 'getDueStudents']);
    Route::get('/fees/pending-students', [FeeController::class, 'getPendingStudents']);
    Route::get('/fees/outstanding', [FeeController::class, 'getOutstanding']);
    Route::get('/fees/stats', [FeeController::class, 'getFeeStats']);
    Route::post('/fees/pay', [FeeController::class, 'collectPayment']);
    Route::get('/fees/payments', [FeeController::class, 'paymentHistory']);
    Route::get('/fees/receipt/{payment}', [FeeController::class, 'getReceipt']);
    
    // Dynamic Configuration CRUD (Settings, Staff, Shifts, Plans)
    Route::get('/config/shifts', [ConfigController::class, 'getShifts']);
    Route::post('/config/shifts', [ConfigController::class, 'saveShift']);
    Route::get('/config/plans', [ConfigController::class, 'getPlans']);
    Route::get('/config/plans/{id}', [ConfigController::class, 'getPlan']);
    Route::post('/config/plans', [ConfigController::class, 'savePlan']);
    Route::put('/config/plans/{id}', [ConfigController::class, 'updatePlan']);
    Route::delete('/config/plans/{id}', [ConfigController::class, 'deletePlan']);

    
    // Manage Staff (Admins/Managers)
    Route::get('/staff', [ConfigController::class, 'listStaff']);
    Route::get('/staff/{user}', [ConfigController::class, 'getStaff']);
    Route::post('/staff', [ConfigController::class, 'addStaff']);
    Route::delete('/staff/{user}', [ConfigController::class, 'removeStaff']);
});
