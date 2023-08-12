<?php

namespace Creasi\Tests\Http;

use Creasi\Base\Models\Business;
use Creasi\Base\Models\Enums\BusinessRelativeType;
use Creasi\Base\Models\Personnel;
use Creasi\Tests\TestCase;
use Illuminate\Contracts\Routing\UrlRoutable;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('stakeholder')]
abstract class StakeholderTestCase extends TestCase
{
    private array $dataStructure = [
        'id',
        'avatar',
        'email',
        'phone',
        'summary',
    ];

    abstract protected function getRelativeType(): BusinessRelativeType;

    final protected function getRoutePath(string|UrlRoutable ...$suffixs): string
    {
        $route = $this->getRelativeType()->key()->plural();

        $suffixs = \array_map(
            fn ($suffix) => $suffix instanceof UrlRoutable ? $suffix->getRouteKey() : $suffix,
            $suffixs
        );

        return \implode('/', \array_filter(['base', (string) $route, ...$suffixs]));
    }

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

        $company = Business::factory()->createOne(['name' => 'External Company']);
        $personal = Personnel::factory()->createOne(['name' => 'External Personal']);

        $user->identity->company->addStakeholder($this->getRelativeType(), $company);
        $user->identity->company->addStakeholder($this->getRelativeType(), $personal);

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
        Sanctum::actingAs($this->user());

        $data = Business::factory()->raw();

        $response = $this->postJson($this->getRoutePath(), $data);

        $response->assertCreated()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_able_to_show_existing_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = Business::factory()->createOne();

        $user->identity->company->addStakeholder($this->getRelativeType(), $model);

        $response = $this->getJson($this->getRoutePath($model));

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_able_to_update_existing_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = Business::factory()->createOne();

        $user->identity->company->addStakeholder($this->getRelativeType(), $model);

        $response = $this->putJson($this->getRoutePath($model), $model->toArray());

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_able_to_delete_existing_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = Business::factory()->createOne();

        $user->identity->company->addStakeholder($this->getRelativeType(), $model);

        $response = $this->deleteJson($this->getRoutePath($model));

        $response->assertNoContent();
    }
}