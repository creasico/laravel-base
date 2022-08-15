<?php

namespace Creasi\Laravel;

use Illuminate\Contracts\Container\Container;

class Accounts
{
    public function __construct(
        protected Container $app
    ) {
        // .
    }

    public function lorem(): string
    {
        return 'Lorem ipsum';
    }
}
