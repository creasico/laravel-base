<?php

namespace Creasi\Laravel\Account;

use Creasi\Laravel\Account;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    private const LIB_PATH = __DIR__ . '/../..';

    public function boot()
    {
        // .
    }

    public function register()
    {
        $this->mergeConfigFrom(self::LIB_PATH . '/config/account.php', 'account');

        $this->app->bind(Account::class, function () {
            return new Account();
        });

        $this->app->alias(Account::class, 'creasi.account');

        if ($this->app->runningInConsole()) {
            $this->registerPublishables();

            $this->registerCommands();
        }
    }

    protected function registerPublishables()
    {
        $this->publishes([
            self::LIB_PATH . '/config/account.php' => \config_path('account.php')
        ], 'creasi-config');

        $timestamp = date('Y_m_d_His', time());
        $migrations = self::LIB_PATH . '/database/migrations';

        $this->publishes([
            $migrations . '/create_account_table.php' => database_path('migrations/' . $timestamp . '_create_account_table.php'),
        ], 'creasi-migrations');

        $this->loadMigrationsFrom($migrations);
    }

    protected function registerCommands()
    {
        $this->commands([]);
    }
}
