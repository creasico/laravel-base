<?php

namespace Creasi\Base;

use Creasi\Base\Database\Models\Address;
use Creasi\Base\Database\Models\Contracts;
use Creasi\Base\Database\Models\Entity;
use Creasi\Base\Enums\StakeholderType;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Laravel\Sanctum\Sanctum;

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
            Event::listen(Login::class, Listeners\RegisterUserDevice::class);
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
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return \route('base.password.reset', [
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ]);
        });

        VerifyEmail::createUrlUsing(function ($user) {
            $expiration = \now()->addMinutes(\config('auth.verification.expire', 60));

            return URL::temporarySignedRoute('base.verification.verify', $expiration, [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]);
        });

        // Customize the way sanctum retrieves the access token.
        Sanctum::getAccessTokenFromRequestUsing(function (Request $request): ?string {
            // We'll check for the `Authorization` header first.
            if ($token = $request->bearerToken()) {
                return $token;
            }

            // If the header is not present, we'll check for the `api_token` query string.
            return $request->isMethodCacheable() ? $request->query('api_token') : null;
        });

        if (app()->routesAreCached() || config('creasi.base.routes_enable') === false) {
            return;
        }

        Route::name('base.')->group(function (): void {
            $prefix = config('creasi.base.routes_prefix', 'base');

            Route::prefix('auth')->group(self::LIB_PATH.'/routes/auth.php');

            Route::prefix($prefix)->group(self::LIB_PATH.'/routes/base.php');
        });
    }

    protected function registerBindings()
    {
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

        $this->app->bind(StakeholderType::class, function ($app) {
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
            StakeholderType::class,
            Contracts\Company::class,
            Contracts\Employee::class,
            Contracts\Stakeholder::class,
            Entity::class,
        ];
    }
}
