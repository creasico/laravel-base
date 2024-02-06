<?php

namespace Creasi\Base\Models;

use Creasi\Base\Models\Concerns\AsCompany;
use Creasi\Base\Models\Concerns\WithTaxInfo;
use Creasi\Base\Models\Contracts\Company;
use Creasi\Base\Models\Contracts\HasTaxInfo;

/**
 * @property-read BusinessRelative $stakeholder
 *
 * @method static Factories\BusinessFactory<Business> factory()
 */
class Business extends Entity implements Company, HasTaxInfo
{
    use AsCompany;
    use WithTaxInfo;

    protected $fillable = [];

    protected $casts = [];
}
