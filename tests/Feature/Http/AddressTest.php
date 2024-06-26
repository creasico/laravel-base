<?php

namespace Creasi\Tests\Feature\Http;

use Creasi\Base\Database\Models\Address;
use Creasi\Tests\Feature\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('addresses')]
class AddressTest extends TestCase
{
    protected string $apiPath = 'addresses';

    private array $dataStructure = [
        'id',
        'type',
        'line',
        'rt',
        'rw',
        'village' => ['code', 'name'],
        'district' => ['code', 'name'],
        'regency' => ['code', 'name'],
        'province' => ['code', 'name'],
        'postal_code',
        'summary',
    ];

    #[Test]
    public function should_receive_404_when_no_data_available(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->getJson($this->getRoutePath());

        $response->assertNotFound();
    }

    #[Test]
    public function should_able_to_retrieve_all_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = Address::factory()->createOne();
        $user->profile->addresses()->save($model);

        $response = $this->getJson($this->getRoutePath());

        $response->assertOk()->assertJsonStructure([
            'data' => [$this->dataStructure],
            'links' => [],
            'meta' => ['types'],
        ]);
    }

    #[Test]
    public function should_able_to_store_new_data(): void
    {
        Sanctum::actingAs($this->user());

        $data = Address::factory()->raw();

        $response = $this->postJson($this->getRoutePath(), $data);

        $response->assertCreated()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => ['types'],
        ]);
    }

    #[Test]
    public function should_able_to_show_existing_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = Address::factory()->createOne();
        $user->profile->addresses()->save($model);

        $response = $this->getJson($this->getRoutePath($model));

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => ['types'],
        ]);
    }

    #[Test]
    public function should_able_to_update_existing_data(): void
    {
        Sanctum::actingAs($this->user());

        $model = Address::factory()->createOne();

        $response = $this->putJson($this->getRoutePath($model), $model->toArray());

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => ['types'],
        ]);
    }

    #[Test]
    public function should_able_to_delete_and_restore_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = Address::factory()->createOne();
        $user->profile->addresses()->save($model);

        $response = $this->deleteJson($this->getRoutePath($model));

        $response->assertNoContent();

        $this->assertSoftDeleted($model);

        $response = $this->putJson($this->getRoutePath($model, 'restore'));

        $response->assertOk();

        $response = $this->deleteJson($this->getRoutePath($model), ['force' => true]);

        $response->assertNoContent();

        $this->assertDatabaseMissing($model, [
            $model->getRouteKeyName() => $model->getRouteKey(),
        ]);
    }
}
