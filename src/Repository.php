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
    public function resolvePerson(Authenticatable $user, $default = null): Entity|Personnel
    {
        $user->loadMissing('profile');

        if (! ($key = $this->router->input('personnel', $default))) {
            return $user->profile;
        }

        /** @var \Creasi\Base\Database\Models\Person */
        $model = $user->profile()->newModelInstance();

        return $this->router->current()->allowsTrashedBindings()
            ? $model->resolveSoftDeletableRouteBinding($key)
            : $model->resolveRouteBinding($key);
    }

    /**
     * @param  Authenticatable|\Illuminate\Foundation\Auth\User|HasProfile  $user
     */
    public function resolveOrganization(Authenticatable $user, $default = null): Entity|Company
    {
        $user->loadMissing('profile');

        if (! ($key = $this->router->input('company', $default))) {
            return $user->profile->employer;
        }

        /** @var \Creasi\Base\Database\Models\Organization */
        $model = $user->profile->employers()->newModelInstance();

        return $this->router->current()->allowsTrashedBindings()
            ? $model->resolveSoftDeletableRouteBinding($key)
            : $model->resolveRouteBinding($key);
    }

    public function resolveEntity(Authenticatable $user): Entity
    {
        $key = $this->router->input('entity');

        return $this->currentRoutePrefix('companies')
            ? $this->resolveOrganization($user, $key)
            : $this->resolvePerson($user, $key);
    }

    public function resolveStakeholder(Company $company, StakeholderType $type): Stakeholder
    {
        /** @var \Creasi\Base\Database\Models\OrganizationRelative */
        $relative = $company->stakeholders()->newQuery()->with('stakeholder')->where([
            'type' => $type,
            'stakeholder_id' => (int) $this->router->input('stakeholder'),
        ])->first();

        return $relative?->stakeholder ?: $company->newInstance();
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
