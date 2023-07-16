<?php

namespace Creasi\Tests;

use Creasi\Base\ServiceProvider;
use Creasi\Nusa\ServiceProvider as NusaServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            NusaServiceProvider::class,
        ];
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app->useEnvironmentPath(\dirname(__DIR__));

        tap($app->make('config'), function (Repository $config) {
            $config->set('app.locale', 'id');
            $config->set('app.faker_locale', 'id_ID');

            $conn = env('DB_CONNECTION', 'sqlite');

            $config->set('database.default', $conn);

            if ($conn === 'sqlite') {
                if (! file_exists($database = __DIR__.'/test.sqlite')) {
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

    private function mergeConfig(Repository $config, string $key, array $value)
    {
        $config->set($key, array_merge($config->get($key, []), $value));
    }
}
