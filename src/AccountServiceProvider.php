<?php

namespace Creasi\Laravel;

use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // .
    }

    public function register()
    {
        $this->app->bind(Account::class, function () {
            return new Account();
        });

        $this->app->alias(Account::class, 'creasi.account');
    }
}
