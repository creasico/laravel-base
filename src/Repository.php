<?php

namespace Creasi\Base;

use Creasi\Base\Contracts\Company;
use Creasi\Base\Contracts\Employee;
use Creasi\Base\Contracts\Stakeholder;
use Creasi\Base\Models\Entity;
use Creasi\Base\Models\Enums\BusinessRelativeType;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

class Repository
{
    protected ?User $user;

    protected array $stakeholders = [];

    public function __construct(
        protected Router $router,
        protected Request $request,
    ) {
        $this->user = $request->user();

        $this->user->load('identity');

        foreach (BusinessRelativeType::cases() as $stakeholder) {
            $this->stakeholders[(string) $stakeholder->key()->plural()] = $stakeholder;
        }
    }

    public function resolveEmployee(): Employee
    {
        /** @var Employee */
        $entity = $this->user->identity;
        $key = $this->router->input('employee');

        return $key ? $entity->resolveRouteBinding($key) : $entity;
    }

    public function resolveEmployer(): Company
    {
        /** @var Company */
        $entity = $this->user->identity->company;
        $key = $this->router->input('company');

        return $key ? $entity->resolveRouteBinding($key) : $entity;
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
        /** @var \Creasi\Base\Models\BusinessRelative */
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

    private function currentRoutePrefix(string $prefix = null)
    {
        $name = \explode('.', $this->router->currentRouteName())[1];

        return $prefix ? $name === $prefix : $name;
    }
}
