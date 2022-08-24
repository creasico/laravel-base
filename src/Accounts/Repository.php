<?php

namespace Creasi\Laravel\Accounts;

use Creasi\Laravel\Http\Controllers\AccountController;
use Creasi\Laravel\Http\Controllers\SettingController;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Route;

class Repository
{
    protected $types;

    public function __construct(
        protected Container $app
    ) {
        $types = $this->app['config']->get('accounts.types', Type::class);

        if (! \enum_exists($types)) {
            throw new \InvalidArgumentException(
                \sprintf('Config key "accounts.types" expected to be "enum", %s given', \gettype($types))
            );
        }

        $this->types = $types;
    }

    public function types(): array
    {
        return \array_column($this->types::cases(), 'value');
    }

    public function routes(): void
    {
        Route::group(['prefix' => 'accounts/{type}'], function () {
            Route::apiResource('accounts', AccountController::class);
            Route::apiResource('settings', SettingController::class);
        })->bind('type', function () {
            return;
        });
    }
}
