<?php

namespace Creasi\Base\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * {@inheritdoc}
     */
    protected function redirectTo(Request $request)
    {
        if (\property_exists($this, 'redirectToCallback')) {
            return parent::redirectTo($request);
        }

        if (! $request->expectsJson()) {
            return route('base.login');
        }
    }
}
