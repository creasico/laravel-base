<?php

namespace Creasi\Base\Database\Models\Concerns;

use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Database\Models\OrganizationRelative;
use Creasi\Base\Enums\StakeholderType;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrganizationRelative> $owners
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrganizationRelative> $subsidiaries
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrganizationRelative> $customers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrganizationRelative> $suppliers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrganizationRelative> $vendors
 *
 * @method HasMany|OrganizationRelative owners()
 * @method HasMany|OrganizationRelative subsidiaries()
 * @method HasMany|OrganizationRelative customers()
 * @method HasMany|OrganizationRelative suppliers()
 * @method HasMany|OrganizationRelative vendors()
 *
 * @mixin Company
 */
trait AsCompany
{
    /**
     * Initialize the trait.
     */
    final protected static function bootAsCompany(): void
    {
        foreach (StakeholderType::cases() as $stakeholder) {
            static::resolveRelationUsing(
                (string) $stakeholder->key()->plural(),
                fn (Company $model) => $model->stakeholders()->where([
                    'type' => $stakeholder,
                ])
            );
        }
    }

    /**
     * Initialize the trait.
     */
    final protected function initializeAsCompany(): void
    {
        // .
    }
}
