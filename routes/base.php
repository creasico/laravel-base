<?php

use Creasi\Base\Http\Controllers;
use Creasi\Base\Models\Enums\BusinessRelativeType;
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
    Route::apiResource('employees', Controllers\EmployeeController::class);

    Route::apiSingleton('profile', Controllers\ProfileController::class);
    Route::apiSingleton('setting', Controllers\SettingController::class);

    Route::get('supports', Controllers\SupportController::class)->name('supports.home');

    foreach (['companies', 'employees'] as $entity) {
        Route::apiResources([
            "{$entity}.addresses" => Controllers\AddressController::class,
            "{$entity}.files" => Controllers\FileUploadController::class,
        ], [
            'parameters' => [$entity => 'entity'],
            'shallow' => true,
        ]);
    }

    foreach (BusinessRelativeType::cases() as $stakeholder) {
        if ($stakeholder->isInternal()) {
            continue;
        }

        $route = $stakeholder->key()->plural();

        Route::apiResource($route, Controllers\StakeholderController::class)
            ->parameter((string) $route, 'stakeholder');
    }
});
