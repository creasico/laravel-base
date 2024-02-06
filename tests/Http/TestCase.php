<?php

namespace Creasi\Tests\Http;

use Creasi\Tests\TestCase as BaseTestCase;
use Illuminate\Contracts\Routing\UrlRoutable;

abstract class TestCase extends BaseTestCase
{
    protected string $apiPath = '';

    protected function getRoutePath(string|UrlRoutable ...$suffixs): string
    {
        $base = config('creasi.base.routes_prefix');
        $suffixs = \array_map(
            fn ($suffix) => $suffix instanceof UrlRoutable ? $suffix->getRouteKey() : $suffix,
            $suffixs
        );

        return \implode('/', \array_filter([$base, $this->apiPath, ...$suffixs]));
    }
}
