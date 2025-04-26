<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');




Route::post('signup', [AuthController::class, 'signup']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('verify-otp', [ForgotPasswordController::class, 'verify_otp']);
Route::post('change-password', [ForgotPasswordController::class, 'change_password']);

// committee routes
Route::post('create-committee', [CommitteeController::class, 'store'])->middleware('auth:sanctum');
Route::post('join-committee', [CommitteeController::class, 'join'])->middleware('auth:sanctum');
// Route::post('create-committee', [CommitteeController::class, 'store']);

