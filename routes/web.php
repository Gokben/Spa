<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessHourController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeScheduleController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\ReservationController;
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
    Route::apiResource('members', MemberController::class)->only(['index', 'show', 'store', 'update']);
    Route::apiResource('work-shifts', WorkShiftController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('business-hours', [BusinessHourController::class, 'index']);
    Route::put('business-hours', [BusinessHourController::class, 'update']);
    Route::apiResource('occupations', OccupationController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('work-groups', WorkGroupController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('employees', EmployeeController::class)->only(['index', 'show', 'store', 'update']);
    Route::post('employees/{employee}/photo', [EmployeeController::class, 'uploadPhoto']);
    Route::get('employee-schedules', [EmployeeScheduleController::class, 'index']);
    Route::put('employee-schedules', [EmployeeScheduleController::class, 'update']);
    Route::apiResource('stock-items', StockController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::get('stock-movements', [StockController::class, 'movements']);
    Route::post('stock-movements', [StockController::class, 'storeMovement']);
    Route::get('cash', [CashController::class, 'index']);
    Route::put('cash/opening', [CashController::class, 'saveOpening']);
    Route::post('cash/transactions', [CashController::class, 'storeTransaction']);
    Route::put('cash/transactions/{cashTransaction}', [CashController::class, 'updateTransaction']);
    Route::delete('cash/transactions/{cashTransaction}', [CashController::class, 'destroyTransaction']);
    Route::post('cash/categories', [CashController::class, 'storeCategory']);
    Route::put('cash/categories/{cashCategory}', [CashController::class, 'updateCategory']);
    Route::delete('cash/categories/{cashCategory}', [CashController::class, 'destroyCategory']);
    Route::put('cash/closing', [CashController::class, 'saveClosing']);
    Route::apiResource('reservations', ReservationController::class)->only(['index', 'store', 'update', 'destroy']);
});
