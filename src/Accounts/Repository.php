<?php

namespace Creasi\Laravel\Accounts;

use Illuminate\Contracts\Container\Container;

class Repository
{
    public function __construct(
        protected Container $app
    ) {
        // .
    }
}
