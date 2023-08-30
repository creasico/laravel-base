<?php

namespace Creasi\Tests\Http;

use Creasi\Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('supports')]
class SupportTest extends TestCase
{
    #[Test]
    public function should_able_to_retrieve_supports_data(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->getJson('base/supports');

        $response->assertOk();
    }
}
