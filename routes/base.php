<?php

use Creasi\Base\Enums\StakeholderType;
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

Route::middleware(['web', 'auth:sanctum'])->group(function () {
    Route::get('supports', Controllers\SupportController::class)->name('supports.home');
});

Route::middleware(['api', 'auth:sanctum'])->group(function () {
    Route::apiResource('companies', Controllers\CompanyController::class);
    // Route::prefix('companies')->controller(Controllers\CompanyController::class)->group(function () {
    //     Route::put('{company}/restore', 'restore')->name('companies.restore')->withTrashed();
    // });

    Route::apiResource('personnels', Controllers\PersonnelController::class);
    // Route::prefix('personnels')->controller(Controllers\EmployeeController::class)->group(function () {
    //     Route::put('{employee}/restore', 'restore')->name('personnels.restore')->withTrashed();
    // });

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
            // 'parameters' => [$entity => 'entity'],
        ]);
    }

    foreach (StakeholderType::cases() as $stakeholder) {
        if ($stakeholder->isInternal()) {
            continue;
        }

        $route = $stakeholder->key()->plural();

        Route::apiResource($route, Controllers\StakeholderController::class)
            ->parameter((string) $route, 'stakeholder');

        // Route::prefix($route)->controller(Controllers\StakeholderController::class)->group(function () use ($route) {
        //     Route::put('{stakeholder}/restore', 'restore')->name($route.'.restore')->withTrashed();
        // });
    }
});
