<?php

namespace Creasi\Tests\Feature\Http;

use Creasi\Tests\Feature\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('supports')]
class SupportTest extends TestCase
{
    protected string $apiPath = 'supports';

    #[Test]
    public function should_able_to_retrieve_supports_data(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->getJson($this->getRoutePath());

        $response->assertOk();
    }
}
