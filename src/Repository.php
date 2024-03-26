<?php

namespace Creasi\Base;

use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Database\Models\Contracts\Employee;
use Creasi\Base\Database\Models\Contracts\HasIdentity;
use Creasi\Base\Database\Models\Contracts\Stakeholder;
use Creasi\Base\Database\Models\Entity;
use Creasi\Base\Enums\BusinessRelativeType;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Routing\Router;

/**
 * @property \Illuminate\Foundation\Auth\User|HasIdentity $user
 */
class Repository
{
    protected array $stakeholders = [];

    public function __construct(protected Router $router)
    {
        foreach (BusinessRelativeType::cases() as $stakeholder) {
            $this->stakeholders[(string) $stakeholder->key()->plural()] = $stakeholder;
        }
    }

    /**
     * @param  Authenticatable|\Illuminate\Foundation\Auth\User|HasIdentity  $user
     */
    public function resolveEmployee(Authenticatable $user): Employee
    {
        $user->loadMissing('identity');

        $key = $this->router->input('employee');

        return $key ? $user->identity->resolveRouteBinding($key) : $user->identity;
    }

    /**
     * @param  Authenticatable|\Illuminate\Foundation\Auth\User|HasIdentity  $user
     */
    public function resolveEmployer(Authenticatable $user): Company
    {
        $user->loadMissing('identity');

        $key = $this->router->input('company');

        return $key ? $user->identity->company->resolveRouteBinding($key) : $user->identity->company;
    }

    public function resolveEntity(Company $company, Employee $employee): Entity
    {
        $entity = $this->currentRoutePrefix('companies') ? $company : $employee;
        $key = $this->router->input('entity');

        // For some reason we do need to resolve the binding ourselves
        // see: https://stackoverflow.com/a/76717314/881743
        return $entity->resolveRouteBinding($key) ?: $entity;
    }

    public function resolveStakeholder(Company $company, BusinessRelativeType $type): Stakeholder
    {
        /** @var \Creasi\Base\Database\Models\BusinessRelative */
        $relative = $company->stakeholders()->newQuery()->with('stakeholder')->where([
            'type' => $type,
            'stakeholder_id' => (int) $this->router->input('stakeholder'),
        ])->first();

        return $relative?->stakeholder ?: $company->newInstance();
    }

    public function resolveBusinessRelativeType(): BusinessRelativeType
    {
        return $this->stakeholders[$this->currentRoutePrefix()];
    }

    private function currentRoutePrefix(?string $prefix = null)
    {
        $name = \explode('.', $this->router->currentRouteName())[1];

        return $prefix ? $name === $prefix : $name;
    }
}
