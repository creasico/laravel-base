<?php

namespace Creasi\Base;

use Creasi\Base\Contracts\Company;
use Creasi\Base\Contracts\Employee;
use Creasi\Base\Contracts\Stakeholder;
use Creasi\Base\Models\Business;
use Creasi\Base\Models\BusinessRelative;
use Creasi\Base\Models\Entity;
use Creasi\Base\Models\Enums\BusinessRelativeType;
use Creasi\Base\Models\Personnel;
use Creasi\Base\Models\User;
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
        if ($personnel = $this->user->identity) {
            $key = $this->router->input('employee');

            return $key ? $personnel->newInstance()->resolveRouteBinding($key) : $personnel;
        }

        return app(Personnel::class);
    }

    public function resolveEmployer(): Company
    {
        if ($company = $this->user->identity?->company) {
            $key = $this->router->input('company');

            return $key ? $company->newInstance()->resolveRouteBinding($key) : $company;
        }

        return app(Business::class);
    }

    public function resolveBusinessRelativeType(): BusinessRelativeType
    {
        $name = \explode('.', $this->router->currentRouteName())[0];

        return $this->stakeholders[$name];
    }

    public function resolveStakeholder(Company $company, BusinessRelativeType $type): Stakeholder
    {
        $relative = BusinessRelative::with('stakeholder')->where([
            'type' => $type,
            'stakeholder_id' => (int) $this->router->input('stakeholder'),
        ]);

        return $relative->first()?->stakeholder ?: $company;
    }
}
