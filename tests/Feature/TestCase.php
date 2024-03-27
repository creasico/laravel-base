<?php

namespace Creasi\Tests\Feature;

use Creasi\Tests\TestCase as BaseTestCase;
use Illuminate\Contracts\Routing\UrlRoutable;

abstract class TestCase extends BaseTestCase
{
    protected string $apiPath = '';

    protected ?string $apiRoutePrefix = null;

    protected function getRoutePath(string|UrlRoutable ...$suffixs): string
    {
        $base = $this->apiRoutePrefix ?: config('creasi.base.routes_prefix');
        $suffixs = \array_map(
            fn ($suffix) => $suffix instanceof UrlRoutable ? $suffix->getRouteKey() : $suffix,
            $suffixs
        );

        return \implode('/', \array_filter([$base, $this->apiPath, ...$suffixs]));
    }
}
