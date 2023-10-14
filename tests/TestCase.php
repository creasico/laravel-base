<?php

namespace Creasi\Tests;

use Closure;
use Creasi\Base\ServiceProvider;
use Creasi\Nusa\ServiceProvider as NusaServiceProvider;
use Creasi\Tests\Fixtures\User;
use Database\Factories\PersonnelFactory;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\SanctumServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    private ?User $currentUser = null;

    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
            NusaServiceProvider::class,
            SanctumServiceProvider::class,
        ];
    }

    final protected function user(array|Closure $attrs = []): User
    {
        if (! $this->currentUser?->exists) {
            $this->currentUser = User::factory()
                ->withIdentity(fn (PersonnelFactory $p) => $p->withProfile()->withCompany(true))
                ->createOne($attrs);
        }

        return $this->currentUser;
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

            $this->mergeConfig($config, 'auth.providers.users', [
                'model' => User::class,
            ]);

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
