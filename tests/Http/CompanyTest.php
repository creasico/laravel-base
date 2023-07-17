<?php

namespace Creasi\Tests\Http;

use Creasi\Base\Models\User;
use Creasi\Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('companies')]
class CompanyTest extends TestCase
{
    #[Test]
    public function should_be_exists(): void
    {
        Sanctum::actingAs(User::factory()->createOne());

        $response = $this->getJson('base/companies');

        $response->assertOk();
    }
}
