<?php

namespace Creasi\Base;

use Creasi\Base\Models\Address;
use Creasi\Base\Models\Contracts;
use Creasi\Base\Models\Entity;
use Creasi\Base\Models\Enums\BusinessRelativeType;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
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

        $this->registerViews();

        $this->defineRoutes();
    }

    public function register(): void
    {
        if (! app()->configurationIsCached()) {
            config([
                'creasi.nusa' => array_merge([
                    'addressable' => Address::class,
                ], config('creasi.nusa', [])),
            ]);

            $this->mergeConfigFrom(self::LIB_PATH.'/config/creasico.php', 'creasi.base');
        }

        if (app()->environment('testing')) {
            Factory::guessFactoryNamesUsing(function (string $modelName) {
                return Factory::$namespace.\class_basename($modelName).'Factory';
            });
        }

        $this->registerBindings();

        $this->booting(function (): void {
            Event::listen(Authenticated::class, Listeners\RegisterUserDevice::class);
        });
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

        Route::name('base.')->group(function (): void {
            $prefix = config('creasi.base.routes_prefix', 'base');

            Route::prefix($prefix)->group(self::LIB_PATH.'/routes/base.php');
        });
    }

    protected function registerBindings()
    {
        $this->app->bind('creasi.base.user_model', function ($app) {
            $provider = $app['config']["auth.guards.{$app['auth']->getDefaultDriver()}.provider"];

            return $app['config']["auth.providers.$provider.model"];
        });

        $this->app->bind('creasi.base.route_home', function ($app) {
            if (\class_exists($intended = 'App\Providers\RouteServiceProvider') && \defined("{$intended}::HOME")) {
                return $intended::HOME;
            }

            return '/';
        });

        $this->app->bind(Entity::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveEntity']);
        });

        $this->app->bind(Contracts\Company::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveEmployer']);
        });

        $this->app->bind(Contracts\Employee::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveEmployee']);
        });

        $this->app->bind(Contracts\Stakeholder::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveStakeholder']);
        });

        $this->app->bind(BusinessRelativeType::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveBusinessRelativeType']);
        });
    }

    private function registerViews(): void
    {
        // View::composer('*', TranslationsComposer::class);

        // Blade::componentNamespace('Creasi\\Base\\Views\\Components', 'creasi');

        $this->loadViewsFrom(self::LIB_PATH.'/resources/views', 'creasi');
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            'creasi.base.user_model',
            'creasi.base.route_home',
            BusinessRelativeType::class,
            Contracts\Company::class,
            Contracts\Employee::class,
            Contracts\Stakeholder::class,
            Entity::class,
        ];
    }
}
