<?php

namespace Creasi\Base;

use Creasi\Base\Contracts\Employee;
use Creasi\Base\Contracts\Stakeholder;
use Creasi\Base\Models\Company;
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
        return app($this->router->is('companies.*') ? Company::class : Personnel::class);
    }

    public function resolveEmployee(): Employee
    {
        return app(Personnel::class);
    }

    public function resolveStakeholder(): Stakeholder
    {
        return app(Company::class);
    }
}
