<?php

namespace Creasi\Tests\Feature\Http\Business;

use Creasi\Base\Enums\StakeholderType;
use Creasi\Tests\Feature\Http\StakeholderTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
#[Group('vendor')]
class VendorTest extends StakeholderTestCase
{
    protected function getRelativeType(): StakeholderType
    {
        return StakeholderType::Vendor;
    }
}
