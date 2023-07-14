<?php

namespace Creasi\Base;

use Creasi\Base\Models\Address;
use Creasi\Base\View\Composers\TranslationsComposer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Laravel\Dusk\Browser;

class ServiceProvider extends IlluminateServiceProvider
{
    private const LIB_PATH = __DIR__.'/..';

    public function boot()
    {
        /**
         * While not in production, send all email tho the following address instead.
         *
         * @see https://laravel.com/docs/9.x/mail#using-a-global-to-address
         */
        if (! app()->environment('production') && $devMail = env('MAIL_DEVELOPMENT')) {
            Mail::alwaysTo($devMail);
        }

        if (app()->runningInConsole()) {
            $this->registerPublishables();

            $this->registerCommands();

            $this->loadMigrationsFrom(self::LIB_PATH.'/database/migrations');
        }

        $this->loadTranslationsFrom(self::LIB_PATH.'/resources/lang', 'creasico');

        $this->loadViewsFrom(self::LIB_PATH.'/resources/views', 'creasico');

        $this->bootViewComposers();
    }

    public function register()
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

            if (\class_exists(Browser::class)) {
                $this->registerDuskMacroForInertia();
            }
        }

    }

    protected function registerPublishables()
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

        $this->publishes([
            self::LIB_PATH.'/resources/views' => \resource_path('views/vendor/creasico'),
        ], ['creasi-views']);
    }

    protected function registerCommands()
    {
        $this->commands([
            // .
        ]);
    }

    /**
     * Register inertia.js helper for dusk testing
     *
     * @see https://github.com/protonemedia/inertiajs-events-laravel-dusk
     */
    private function registerDuskMacroForInertia(): void
    {
        Browser::macro('waitForInertia', function (?int $seconds = null): Browser {
            /** @var Browser $this */
            $driver = $this->driver;

            $currentCount = $driver->executeScript('return window.__inertiaNavigatedCount;');

            return $this->waitUsing($seconds, 100, fn () => $driver->executeScript(
                "return window.__inertiaNavigatedCount > {$currentCount};"
            ), 'Waited %s seconds for Inertia.js to increase the navigate count.');
        });
    }

    private function bootViewComposers(): void
    {
        View::composer('*', TranslationsComposer::class);
    }
}
