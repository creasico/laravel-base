<?php

namespace Workbench\App\Providers;

use Illuminate\Config\Repository;
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

            $conn = env('DB_CONNECTION', 'sqlite');

            $config->set('database.default', $conn);

            if ($conn === 'sqlite') {
                if (! file_exists($database = \realpath(__DIR__.'/../../database').'/test.sqlite')) {
                    touch($database);
                }

                $this->mergeConfig($config, 'database.connections.sqlite', [
                    'database' => $database,
                    'foreign_key_constraints' => true,
                ]);
            } else {
                $this->mergeConfig($config, 'database.connections.'.$conn, [
                    'database' => env('DB_DATABASE', 'creasi_test'),
                    'username' => env('DB_USERNAME', 'creasico'),
                    'password' => env('DB_PASSWORD', 'secret'),
                ]);
            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    private function mergeConfig(Repository $config, string $key, array $value)
    {
        $config->set($key, array_merge($config->get($key, []), $value));
    }
}
