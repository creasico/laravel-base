<?php

namespace Creasi\Base\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests {
        resourceAbilityMap as abilityMap;
    }

    protected function resourceAbilityMap()
    {
        $abilities = [
            'restore' => 'restore',
        ];

        return \array_merge(
            $this->abilityMap(),
            \array_filter($abilities, function (string $method) {
                return \method_exists($this, $method);
            }, \ARRAY_FILTER_USE_KEY)
        );
    }
}
