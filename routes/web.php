<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessHourController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\CurrentAccountController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeScheduleController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberPaymentController;
use App\Http\Controllers\MemberMeasurementController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ServiceGroupController;
use App\Http\Controllers\SpaPackageController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\WorkShiftController;
use App\Http\Controllers\WorkGroupController;
use App\Http\Middleware\SpaAuthenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('spa');
});

Route::post('/login', [AuthController::class, 'store'])->middleware('guest')->name('login');
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth');

Route::middleware(SpaAuthenticate::class)->prefix('api')->group(function () {
    Route::post('members/{member}/photo', [MemberController::class, 'uploadPhoto']);
    Route::get('members/{member}/photo', [MemberController::class, 'photo']);
    Route::get('members/{member}/services', [MemberController::class, 'services']);
    Route::get('members/{member}/payments', [MemberPaymentController::class, 'index']);
    Route::post('members/{member}/payments', [MemberPaymentController::class, 'store']);
    Route::delete('members/{member}/payments/{payment}', [MemberPaymentController::class, 'destroy']);
    Route::get('members/{member}/measurements', [MemberMeasurementController::class, 'index']);
    Route::post('members/{member}/measurements', [MemberMeasurementController::class, 'store']);
    Route::put('members/{member}/measurements/{measurement}', [MemberMeasurementController::class, 'update']);
    Route::delete('members/{member}/measurements/{measurement}', [MemberMeasurementController::class, 'destroy']);
    Route::apiResource('members', MemberController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::apiResource('work-shifts', WorkShiftController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('business-hours', [BusinessHourController::class, 'index']);
    Route::put('business-hours', [BusinessHourController::class, 'update']);
    Route::apiResource('occupations', OccupationController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('work-groups', WorkGroupController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('service-groups', ServiceGroupController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('employees', EmployeeController::class)->only(['index', 'show', 'store', 'update']);
    Route::post('employees/{employee}/photo', [EmployeeController::class, 'uploadPhoto']);
    Route::get('employee-schedules', [EmployeeScheduleController::class, 'index']);
    Route::put('employee-schedules', [EmployeeScheduleController::class, 'update']);
    Route::apiResource('stock-items', StockController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::get('stock-movements', [StockController::class, 'movements']);
    Route::post('stock-movements', [StockController::class, 'storeMovement']);
    Route::put('stock-movements/{stockMovement}', [StockController::class, 'updateMovement']);
    Route::delete('stock-movements/{stockMovement}', [StockController::class, 'destroyMovement']);
    Route::get('cash', [CashController::class, 'index']);
    Route::put('cash/opening', [CashController::class, 'saveOpening']);
    Route::post('cash/transactions', [CashController::class, 'storeTransaction']);
    Route::put('cash/transactions/{cashTransaction}', [CashController::class, 'updateTransaction']);
    Route::delete('cash/transactions/{cashTransaction}', [CashController::class, 'destroyTransaction']);
    Route::post('cash/categories', [CashController::class, 'storeCategory']);
    Route::put('cash/categories/{cashCategory}', [CashController::class, 'updateCategory']);
    Route::delete('cash/categories/{cashCategory}', [CashController::class, 'destroyCategory']);
    Route::put('cash/closing', [CashController::class, 'saveClosing']);
    Route::apiResource('reservations', ReservationController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::apiResource('packages', SpaPackageController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('current-accounts', CurrentAccountController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::get('sms', [SmsController::class, 'index']);
    Route::put('sms/settings', [SmsController::class, 'updateSettings']);
    Route::post('sms/send', [SmsController::class, 'send']);
});
