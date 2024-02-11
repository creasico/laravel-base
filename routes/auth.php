<?php

use Creasi\Base\Http\Controllers\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::controller(Auth\AuthenticatedSessionController::class)->group(function () {
        Route::post('login', 'store')->name('login');
    });

    Route::controller(Auth\RegisteredUserController::class)->group(function () {
        Route::post('register', 'store')->name('register');
    });

    Route::controller(Auth\ResetPasswordController::class)->group(function () {
        Route::post('forgot-password', 'store')->name('password.forgot');

        Route::get('reset-password/{token}', 'create')->name('password.reset');
        Route::put('reset-password', 'update')->name('password.update');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(Auth\AuthenticatedSessionController::class)->group(function () {
        Route::get('', 'verify')->name('verify');
        Route::delete('', 'destroy')->name('logout');
        Route::post('refresh', 'refresh')->name('refresh');
    });

    Route::controller(Auth\EmailVerificationController::class)->group(function () {
        Route::get('email/verify/{id}/{hash}', 'verify')
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-send', 'send')
            ->middleware('throttle:6,1')
            ->name('verification.send');
    });
});
