<?php

namespace Creasi\Tests\Http\Business;

use Creasi\Base\Models\Enums\CompanyRelativeType;
use Creasi\Tests\Http\StakeholderTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
#[Group('supplier')]
class SupplierTest extends StakeholderTestCase
{
    protected function getRelativeType(): CompanyRelativeType
    {
        return CompanyRelativeType::Supplier;
    }
}
