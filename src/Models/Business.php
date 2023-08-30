<?php

namespace Creasi\Base\Models;

use Creasi\Base\Contracts\Company;
use Creasi\Base\Contracts\HasTaxInfo;
use Creasi\Base\Models\Concerns\AsCompany;
use Creasi\Base\Models\Concerns\WithTaxInfo;

/**
 * @property-read BusinessRelative $stakeholder
 *
 * @method static \Database\Factories\BusinessFactory<static> factory()
 */
class Business extends Entity implements HasTaxInfo, Company
{
    use AsCompany;
    use WithTaxInfo;

    protected $fillable = [];

    protected $casts = [];
}
