<?php

namespace Creasi\Laravel\Accounts;

use Creasi\Laravel\Accounts;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    private const LIB_PATH = __DIR__.'/../..';

    public function boot()
    {
        // .
    }

    public function register()
    {
        $this->mergeConfigFrom(self::LIB_PATH.'/config/accounts.php', 'accounts');

        $this->app->bind(Accounts::class, function ($app) {
            return new Accounts($app);
        });

        $this->app->alias(Accounts::class, 'creasi.accounts');

        if ($this->app->runningInConsole()) {
            $this->registerPublishables();

            $this->registerCommands();
        }
    }

    protected function registerPublishables()
    {
        $this->publishes([
            self::LIB_PATH.'/config/accounts.php' => \config_path('accounts.php'),
        ], 'creasi-config');

        $timestamp = date('Y_m_d_His', time());
        $migrations = self::LIB_PATH.'/database/migrations';

        $this->publishes([
            $migrations.'/create_accounts_tables.php' => database_path('migrations/'.$timestamp.'_create_accounts_tables.php'),
        ], 'creasi-migrations');

        $this->loadMigrationsFrom($migrations);
    }

    protected function registerCommands()
    {
        $this->commands([]);
    }
}
