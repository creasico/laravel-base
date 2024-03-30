<?php

namespace Creasi\Base;

use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Database\Models\Contracts\HasProfile;
use Creasi\Base\Database\Models\Contracts\Personnel;
use Creasi\Base\Database\Models\Contracts\Stakeholder;
use Creasi\Base\Database\Models\Entity;
use Creasi\Base\Enums\StakeholderType;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Routing\Router;

/**
 * @property \Illuminate\Foundation\Auth\User|HasProfile $user
 */
class Repository
{
    protected array $stakeholders = [];

    public function __construct(protected Router $router)
    {
        foreach (StakeholderType::cases() as $stakeholder) {
            $this->stakeholders[(string) $stakeholder->key()->plural()] = $stakeholder;
        }
    }

    /**
     * @param  Authenticatable|\Illuminate\Foundation\Auth\User|HasProfile  $user
     */
    public function resolvePerson(Authenticatable $user): Personnel
    {
        $user->loadMissing('profile');

        $key = $this->router->input('personnel');

        return $key ? $user->profile->resolveRouteBinding($key) : $user->profile;
    }

    /**
     * @param  Authenticatable|\Illuminate\Foundation\Auth\User|HasProfile  $user
     */
    public function resolveOrganization(Authenticatable $user): Company
    {
        $user->loadMissing('profile');

        $key = $this->router->input('company');

        return $key ? $user->profile->employer->resolveRouteBinding($key) : $user->profile->employer;
    }

    public function resolveEntity(Company $org, Personnel $person): Entity
    {
        $entity = $this->currentRoutePrefix('companies') ? $org : $person;
        $key = $this->router->input('entity');

        // For some reason we do need to resolve the binding ourselves
        // see: https://stackoverflow.com/a/76717314/881743
        return $key ? $entity->resolveRouteBinding($key) : $entity;
    }

    public function resolveStakeholder(Company $org, StakeholderType $type): Stakeholder
    {
        /** @var \Creasi\Base\Database\Models\OrganizationRelative */
        $relative = $org->stakeholders()->newQuery()->with('stakeholder')->where([
            'type' => $type,
            'stakeholder_id' => (int) $this->router->input('stakeholder'),
        ])->first();

        return $relative?->stakeholder ?: $org->newInstance();
    }

    public function resolveOrganizationRelativeType(): StakeholderType
    {
        return $this->stakeholders[$this->currentRoutePrefix()];
    }

    private function currentRoutePrefix(?string $prefix = null)
    {
        $name = \explode('.', $this->router->currentRouteName())[1];

        return $prefix ? $name === $prefix : $name;
    }
}
