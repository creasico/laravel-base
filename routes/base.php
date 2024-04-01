<?php

use Creasi\Base\Enums\StakeholderType;
use Creasi\Base\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('web')->group(function () {
        Route::get('', Controllers\DashboardController::class)->name('dashboard');

        Route::resource('companies', Controllers\CompanyController::class)->only(['index', 'create', 'show']);
        Route::resource('personnels', Controllers\PersonnelController::class)->only(['index', 'create', 'show']);

        Route::get('supports', Controllers\SupportController::class)->name('supports.home');
    });

    Route::middleware('api')->group(function () {
        Route::apiResource('companies', Controllers\CompanyController::class)->except(['index', 'show']);
        Route::prefix('companies')->controller(Controllers\CompanyController::class)->group(function () {
            Route::put('{company}/restore', 'restore')->name('companies.restore')->withTrashed();
        });

        Route::apiResource('personnels', Controllers\PersonnelController::class)->except(['index', 'show']);
        Route::prefix('personnels')->controller(Controllers\PersonnelController::class)->group(function () {
            Route::put('{personnel}/restore', 'restore')->name('personnels.restore')->withTrashed();
        });

        Route::apiResource('addresses', Controllers\AddressController::class);
        Route::prefix('addresses')->controller(Controllers\AddressController::class)->group(function () {
            Route::put('{address}/restore', 'restore')->name('addresses.restore')->withTrashed();
        });

        Route::apiResource('files', Controllers\FileController::class);
        Route::prefix('files')->controller(Controllers\FileController::class)->group(function () {
            Route::put('{file}/restore', 'restore')->name('files.restore')->withTrashed();
        });

        Route::apiSingleton('profile', Controllers\ProfileController::class);
        Route::apiSingleton('setting', Controllers\SettingController::class);

        foreach (['companies', 'personnels'] as $entity) {
            Route::apiResources([
                "{$entity}.addresses" => Controllers\AddressController::class,
                "{$entity}.files" => Controllers\FileController::class,
            ], [
                'only' => ['index', 'store'],
            ]);
        }

        foreach (StakeholderType::externals() as $stakeholder) {
            $route = (string) $stakeholder->key()->plural();

            Route::apiResource($route, Controllers\StakeholderController::class)->parameter($route, 'stakeholder');
            Route::prefix($route)->controller(Controllers\StakeholderController::class)->group(function () use ($route) {
                Route::put('{stakeholder}/restore', 'restore')->name($route.'.restore')->withTrashed();
            });
        }
    });
});
