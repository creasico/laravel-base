<?php

namespace Creasi\Tests\Feature\Http;

use Creasi\Tests\Feature\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('account')]
#[Group('profile')]
class ProfileTest extends TestCase
{
    protected string $apiPath = 'profile';

    private array $responseStructure = [
        'data' => [
            'avatar',
            'name',
            'alias',
            'email',
            'phone',
            'gender' => ['value', 'label'],
            'summary',
            'prefix',
            'suffix',
            'birth_date',
            'birth_place' => ['name', 'code'],
        ],
        'meta' => [],
    ];

    #[Test]
    public function should_able_to_retrieve_profile_data(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->getJson($this->getRoutePath());

        $response->assertOk()->assertJsonStructure($this->responseStructure);
    }

    #[Test]
    public function should_able_to_update_profile_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $user->load('identity');

        $response = $this->putJson($this->getRoutePath(), [
            'name' => $user->identity->name,
            'alias' => $user->identity->alias,
            'email' => $user->identity->email,
            'phone' => $user->identity->phone,
            'summary' => null,
            'prefix' => null,
            'suffix' => null,
        ]);

        $response->assertOk()->assertJsonStructure($this->responseStructure);
    }
}
