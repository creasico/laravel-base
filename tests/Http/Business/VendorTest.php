<?php

namespace Creasi\Tests\Http\Business;

use Creasi\Base\Models\Enums\BusinessRelativeType;
use Creasi\Tests\Http\StakeholderTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
#[Group('vendor')]
class VendorTest extends StakeholderTestCase
{
    protected function getRelativeType(): BusinessRelativeType
    {
        return BusinessRelativeType::Vendor;
    }
}
