<?php

namespace Creasi\Tests\Feature\Http;

use Creasi\Base\Database\Models\Address;
use Creasi\Base\Database\Models\File;
use Creasi\Base\Database\Models\Organization;
use Creasi\Base\Enums\FileType;
use Creasi\Base\Enums\StakeholderType;
use Creasi\Tests\Feature\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('companies')]
class CompanyTest extends TestCase
{
    protected string $apiPath = 'companies';

    private array $dataStructure = [
        'id',
        'avatar',
        'name',
        'alias',
        'email',
        'phone',
        'summary',
    ];

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

        $external = Organization::factory()->createOne(['name' => 'Internal Company']);

        $user->profile->employer->addStakeholder(StakeholderType::Subsidiary, $external);

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

        Sanctum::actingAs($this->user());

        $model = Organization::factory()->createOne();

        $response = $this->getJson($this->getRoutePath($model));

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_receive_404_when_no_addresses_available(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($this->user());

        $model = Organization::factory()->createOne();

        $response = $this->getJson($this->getRoutePath($model, 'addresses'));

        $response->assertNotFound();
    }

    #[Test]
    public function should_able_to_create_new_address(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($this->user());

        $data = Address::factory()->raw();
        $model = Organization::factory()->createOne();

        $response = $this->postJson($this->getRoutePath($model, 'addresses'), $data);

        $response->assertCreated();
    }

    #[Test]
    public function should_able_to_retrieve_all_addresses(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($this->user());

        $model = Organization::factory()->withAddress()->createOne();

        $response = $this->getJson($this->getRoutePath($model, 'addresses'));

        $response->assertOk();
    }

    #[Test]
    public function should_receive_404_when_no_files_available(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($this->user());

        $model = Organization::factory()->createOne();

        $response = $this->getJson($this->getRoutePath($model, 'files'));

        $response->assertNotFound();
    }

    #[Test]
    public function should_able_to_upload_and_store_new_file(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Storage::fake();
        Sanctum::actingAs($this->user());

        $model = Organization::factory()->createOne();
        $data = File::factory()->withoutFile()->raw();
        $data['upload'] = UploadedFile::fake()->create('file.pdf');
        $data['type'] = FileType::Document->value;

        $response = $this->postJson($this->getRoutePath($model, 'files'), $data);

        $response->assertCreated();
    }

    #[Test]
    public function should_able_to_retrieve_all_uploaded_files(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($this->user());

        $model = Organization::factory()->withFileUpload(FileType::Document)->createOne();

        $response = $this->getJson($this->getRoutePath($model, 'files'));

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_update_existing_data(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($this->user());

        $model = Organization::factory()->createOne();

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

        Sanctum::actingAs($this->user());

        $model = Organization::factory()->createOne();

        $response = $this->deleteJson($this->getRoutePath($model));

        $response->assertNoContent();

        $this->assertSoftDeleted($model);

        $response = $this->putJson($this->getRoutePath($model, 'restore'));

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);

        $response = $this->deleteJson($this->getRoutePath($model), ['force' => true]);

        $response->assertNoContent();

        $this->assertDatabaseMissing($model, [
            $model->getRouteKeyName() => $model->getRouteKey(),
        ]);
    }
}
