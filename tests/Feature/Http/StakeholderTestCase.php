<?php

namespace Creasi\Tests\Feature\Http;

use Creasi\Base\Database\Models\Organization;
use Creasi\Base\Database\Models\Person;
use Creasi\Base\Enums\StakeholderType;
use Creasi\Tests\Feature\TestCase;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('stakeholders')]
abstract class StakeholderTestCase extends TestCase
{
    private array $dataStructure = [
        'id',
        'avatar',
        'name',
        'alias',
        'email',
        'phone',
        'summary',
    ];

    abstract protected function getRelativeType(): StakeholderType;

    final protected function getRoutePath(string|UrlRoutable ...$suffixs): string
    {
        $route = $this->getRelativeType()->key()->plural();

        return parent::getRoutePath((string) $route, ...$suffixs);
    }

    #[Test]
    public function should_receive_404_when_no_data_available(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($this->user());

        $response = $this->getJson($this->getRoutePath());

        $response->assertNotFound();
    }

    #[Test]
    public function should_able_to_retrieve_all_data(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($user = $this->user());

        $company = Organization::factory()->createOne(['name' => 'External Company']);
        $personal = Person::factory()->createOne(['name' => 'External Personal']);

        $user->profile->employer->addStakeholder($this->getRelativeType(), $company);
        $user->profile->employer->addStakeholder($this->getRelativeType(), $personal);

        $response = $this->getJson($this->getRoutePath());

        $response->assertOk()->assertJsonStructure([
            'data' => [$this->dataStructure],
            'links' => [],
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_able_to_store_new_data(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($this->user());

        $data = Organization::factory()->raw();

        $response = $this->postJson($this->getRoutePath(), $data);

        $response->assertCreated()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_able_to_show_existing_data(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($user = $this->user());

        $model = Organization::factory()->createOne();

        $user->profile->employer->addStakeholder($this->getRelativeType(), $model);

        $response = $this->getJson($this->getRoutePath($model));

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_able_to_update_existing_data(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($user = $this->user());

        $model = Organization::factory()->createOne();

        $user->profile->employer->addStakeholder($this->getRelativeType(), $model);

        $response = $this->putJson($this->getRoutePath($model), $model->toArray());

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_able_to_delete_and_restore_data(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($user = $this->user());

        $model = Organization::factory()->createOne();

        $user->profile->employer->addStakeholder($this->getRelativeType(), $model);

        $response = $this->deleteJson($this->getRoutePath($model));

        $response->assertNoContent();

        // TODO: Make it happen
        // $this->assertSoftDeleted($model);

        $response = $this->putJson($this->getRoutePath($model, 'restore'));

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);

        $response = $this->deleteJson($this->getRoutePath($model), ['force' => true]);

        $response->assertNoContent();

        // TODO: Make it happen
        // $this->assertDatabaseMissing($model, [
        //     $model->getRouteKeyName() => $model->getRouteKey()
        // ]);
    }
}
