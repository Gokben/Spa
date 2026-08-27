<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('spa');
});

Route::post('/login', [AuthController::class, 'store'])->middleware('guest')->name('login');
Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth');

Route::middleware('auth')->prefix('api')->group(function () {
    Route::apiResource('members', MemberController::class)->only(['index', 'show', 'store', 'update']);
});
