<?php

namespace Creasi\Base;

use Creasi\Base\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    private const LIB_PATH = __DIR__.'/..';

    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->registerPublishables();

            $this->registerCommands();
        }

        $this->loadTranslationsFrom(self::LIB_PATH.'/resources/lang', 'creasico');

        $this->loadMigrationsFrom(self::LIB_PATH.'/database/migrations');
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
        }
    }

    protected function registerPublishables()
    {
        $this->publishes([
            self::LIB_PATH.'/config/creasico.php' => \config_path('creasi/base.php'),
        ], ['creasi-config', 'creasi-base-config']);

        $this->publishes([
            self::LIB_PATH.'/resources/lang' => \resource_path('vendor/creasico'),
        ], 'creasi-lang');
    }

    protected function registerCommands()
    {
        $this->commands([
            // .
        ]);
    }
}
