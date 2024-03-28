<?php

namespace Workbench\App\Providers;

use Illuminate\Config\Repository;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Workbench\App\Models\User;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        tap($this->app->make('config'), function (Repository $config) {
            $config->set('app.locale', 'id');
            $config->set('app.faker_locale', 'id_ID');

            $this->mergeConfig($config, 'auth.providers.users', [
                'model' => User::class,
            ]);

            $this->mergeConfig($config, 'creasi.base', [
                'user_model' => User::class,
            ]);

            if (env('DB_CONNECTION', 'sqlite') === 'sqlite') {
                $this->mergeConfig($config, 'database.connections.sqlite', [
                    'database' => ':memory:',
                    'foreign_key_constraints' => true,
                ]);
            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::addLocation(__DIR__.'/../../resources/views');
    }

    private function mergeConfig(Repository $config, string $key, array $value)
    {
        $config->set($key, array_merge($config->get($key, []), $value));
    }
}
