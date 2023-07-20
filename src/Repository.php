<?php

namespace Creasi\Base;

use Creasi\Base\Contracts\Employee;
use Creasi\Base\Contracts\Employer;
use Creasi\Base\Contracts\Stakeholder;
use Creasi\Base\Models\Business;
use Creasi\Base\Models\Entity;
use Creasi\Base\Models\Personnel;
use Illuminate\Routing\Router;

class Repository
{
    public function __construct(
        protected Router $router,
    ) {
        // .
    }

    public function resolveEntity(): Entity
    {
        /** @var Entity */
        $entity = app($this->router->is('companies.*') ? Business::class : Personnel::class);

        // For some reason we do need to resolve the binding ourselves
        // see: https://stackoverflow.com/a/76717314/881743
        return $entity->resolveRouteBinding($this->router->input('entity')) ?: $entity;
    }

    public function resolveEmployee(): Employee
    {
        return app(Personnel::class);
    }

    public function resolveEmployer(): Employer
    {
        return app(Business::class);
    }

    public function resolveStakeholder(): Stakeholder
    {
        return app(Business::class);
    }
}
