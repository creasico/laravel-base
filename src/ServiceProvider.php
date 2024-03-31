<?php

namespace Creasi\Base;

use Creasi\Base\Database\Models;
use Creasi\Base\Database\Models\Contracts;
use Creasi\Base\Enums\StakeholderType;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * @var class-string[]
     */
    protected $providers = [
        Providers\RouteServiceProvider::class,
    ];

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

        $this->loadTranslationsFrom(self::LIB_PATH.'/resources/lang', 'creasi');

        $this->defineViews();
    }

    public function register(): void
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }

        if (! app()->configurationIsCached()) {
            config([
                'creasi.nusa' => array_merge(config('creasi.nusa', []), [
                    'addressable' => Models\Address::class,
                ]),
            ]);

            $this->mergeConfigFrom(self::LIB_PATH.'/config/creasico.php', 'creasi.base');
        }

        $this->registerBindings();
    }

    private function defineViews(): void
    {
        // View::composer('*', TranslationsComposer::class);

        Blade::componentNamespace('Creasi\\Base\\Views\\Components', 'creasi');

        $this->loadViewsFrom(\realpath(self::LIB_PATH.'/resources/views'), 'creasi');
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

    protected function registerBindings()
    {
        $this->app->bind('creasi.base.route_home', function ($app) {
            if (\class_exists($intended = 'App\Providers\RouteServiceProvider') && \defined("{$intended}::HOME")) {
                return $intended::HOME;
            }

            return '/';
        });

        $this->app->bind(Models\Entity::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveEntity']);
        });

        $this->app->bind(Contracts\Company::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveOrganization']);
        });

        $this->app->bind(Contracts\Personnel::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolvePerson']);
        });

        $this->app->bind(Contracts\Stakeholder::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveStakeholder']);
        });

        $this->app->bind(StakeholderType::class, function ($app) {
            $repo = $app->make(Repository::class);

            return $app->call([$repo, 'resolveOrganizationRelativeType']);
        });
    }

    /**
     * {@inheritdoc}
     *
     *  @codeCoverageIgnore
     */
    public function provides()
    {
        return [
            'creasi.base.user_model',
            'creasi.base.route_home',
            StakeholderType::class,
            Contracts\Company::class,
            Contracts\Personnel::class,
            Contracts\Stakeholder::class,
            Models\Entity::class,
        ];
    }
}
