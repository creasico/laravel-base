<?php

namespace Creasi\Base;

use Creasi\Base\Contracts\Company;
use Creasi\Base\Contracts\Employee;
use Creasi\Base\Contracts\Stakeholder;
use Creasi\Base\Models\Address;
use Creasi\Base\Models\Entity;
use Creasi\Base\Models\Enums\BusinessRelativeType;
use Creasi\Base\View\Composers\TranslationsComposer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    private const LIB_PATH = __DIR__.'/..';

    public function boot(): void
    {
        /**
         * While not in production, send all email tho the following address instead.
         *
         * @see https://laravel.com/docs/9.x/mail#using-a-global-to-address
         */
        if (! app()->environment('production') && $devMail = env('MAIL_DEVELOPMENT')) {
            Mail::alwaysTo($devMail); // @codeCoverageIgnore
        }

        if (app()->runningInConsole()) {
            $this->registerPublishables();

            $this->registerCommands();

            $this->loadMigrationsFrom(self::LIB_PATH.'/database/migrations');
        }

        $this->loadTranslationsFrom(self::LIB_PATH.'/resources/lang', 'creasico');

        $this->bootViewComposers();

        $this->defineRoutes();
    }

    public function register(): void
    {
        config([
            'creasi.nusa' => array_merge([
                'addressable' => Address::class,
            ], config('creasi.nusa', [])),
        ]);

        if (! app()->configurationIsCached()) {
            $this->mergeConfigFrom(self::LIB_PATH.'/config/creasico.php', 'creasi.base');
        }

        if (app()->environment('testing')) {
            Factory::guessFactoryNamesUsing(function (string $modelName) {
                return Factory::$namespace.\class_basename($modelName).'Factory';
            });
        }

        $this->registerBindings();
    }

    protected function registerPublishables(): void
    {
        $this->publishes([
            self::LIB_PATH.'/config/creasico.php' => \config_path('creasi/base.php'),
        ], ['creasi-config', 'creasi-base-config']);

        $this->publishes([
            self::LIB_PATH.'/resources/assets' => \public_path('vendor/creasico'),
        ], ['creasi-assets', 'creasi-base-assets', 'laravel-assets']);

        $this->publishes([
            self::LIB_PATH.'/resources/lang' => \resource_path('lang/vendor/creasico'),
        ], ['creasi-lang']);
    }

    protected function registerCommands(): void
    {
        $this->commands([
            // .
        ]);
    }

    protected function defineRoutes(): void
    {
        if (app()->routesAreCached() || config('creasi.base.routes_enable') === false) {
            return;
        }

        $prefix = config('creasi.base.routes_prefix', 'base');

        Route::prefix($prefix)
            ->name("$prefix.")
            ->group(self::LIB_PATH.'/routes/base.php');
    }

    protected function registerBindings()
    {
        $this->app->bind('creasi.base.user_model', function ($app) {
            $provider = $app['config']["auth.guards.{$app['auth']->getDefaultDriver()}.provider"];

            return $app['config']["auth.providers.$provider.model"];
        });

        $this->app->bind(Entity::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveEntity']);
        });

        $this->app->bind(Company::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveEmployer']);
        });

        $this->app->bind(Employee::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveEmployee']);
        });

        $this->app->bind(Stakeholder::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveStakeholder']);
        });

        $this->app->bind(BusinessRelativeType::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveBusinessRelativeType']);
        });
    }

    private function bootViewComposers(): void
    {
        View::composer('*', TranslationsComposer::class);
    }
}
