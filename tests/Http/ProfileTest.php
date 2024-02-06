<?php

namespace Creasi\Tests\Http;

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
            'fullname',
            'nickname',
            'email',
            'phone',
            'gender' => ['value', 'label'],
            'summary',
            'nik',
            'prefix',
            'suffix',
            'birth_date',
            'birth_place' => ['name', 'code'],
            'education',
            'religion' => ['value', 'label'],
            'tax_status' => ['value', 'label'],
            'tax_id',
        ],
        'meta' => [
            'educations' => [
                ['key', 'value'],
            ],
            'tax_statuses' => [
                ['key', 'value', 'label'],
            ],
        ],
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

        $user->load('identity.profile');

        $response = $this->putJson($this->getRoutePath(), [
            'fullname' => $user->identity->name,
            'nickname' => $user->identity->alias,
            'phone' => $user->identity->phone,
            'summary' => null,
            'prefix' => null,
            'suffix' => null,
            'education' => $user->identity->profile?->education->value,
            'tax_status' => $user->identity->profile?->tax_status->value,
            'tax_id' => $user->identity->profile?->tax_id,
        ]);

        $response->assertOk()->assertJsonStructure($this->responseStructure);
    }
}
