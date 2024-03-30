<?php

namespace Creasi\Tests\Feature\Http;

use Creasi\Base\Enums\StakeholderType;
use PHPUnit\Framework\Attributes\Group;

#[Group('api')]
#[Group('vendors')]
class VendorTest extends StakeholderTestCase
{
    protected function getRelativeType(): StakeholderType
    {
        return StakeholderType::Vendor;
    }
}
