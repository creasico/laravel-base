<?php

use Creasi\Base\Http\Controllers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('companies', Controllers\CompanyController::class);

    Route::controller(Controllers\Account\HomeController::class)->group(function () {
        Route::get('account', 'show')->name('account.home');
        Route::put('account', 'update');
    });

    Route::controller(Controllers\Account\SettingController::class)->group(function () {
        Route::get('account/settings', 'show')->name('account.settings');
        Route::put('account/settings', 'update');
    });

    Route::get('supports', Controllers\SupportController::class)->name('supports.home');
});
