<?php

namespace Creasi\Tests\Feature\Http\Business;

use Creasi\Base\Database\Models\Address;
use Creasi\Base\Database\Models\Business;
use Creasi\Base\Database\Models\File;
use Creasi\Base\Enums\FileType;
use Creasi\Base\Enums\StakeholderType;
use Creasi\Tests\Feature\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('company')]
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
        Sanctum::actingAs($this->user());

        $response = $this->getJson($this->getRoutePath());

        $response->assertNotFound();
    }

    #[Test]
    public function should_able_to_retrieve_all_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $external = Business::factory()->createOne(['name' => 'Internal Company']);

        $user->identity->employer->addStakeholder(StakeholderType::Subsidiary, $external);

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
        Sanctum::actingAs($this->user());

        $model = Business::factory()->createOne();

        $response = $this->getJson($this->getRoutePath($model));

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_receive_404_when_no_addresses_available(): void
    {
        Sanctum::actingAs($this->user());

        $model = Business::factory()->createOne();

        $response = $this->getJson($this->getRoutePath($model, 'addresses'));

        $response->assertNotFound();
    }

    #[Test]
    public function should_able_to_create_new_address(): void
    {
        Sanctum::actingAs($this->user());

        $data = Address::factory()->raw();
        $model = Business::factory()->createOne();

        $response = $this->postJson($this->getRoutePath($model, 'addresses'), $data);

        $response->assertCreated();
    }

    #[Test]
    public function should_able_to_retrieve_all_addresses(): void
    {
        Sanctum::actingAs($this->user());

        $model = Business::factory()->withAddress()->createOne();

        $response = $this->getJson($this->getRoutePath($model, 'addresses'));

        $response->assertOk();
    }

    #[Test]
    public function should_receive_404_when_no_files_available(): void
    {
        Sanctum::actingAs($this->user());

        $model = Business::factory()->createOne();

        $response = $this->getJson($this->getRoutePath($model, 'files'));

        $response->assertNotFound();
    }

    #[Test]
    public function should_able_to_upload_and_store_new_file(): void
    {
        Storage::fake();
        Sanctum::actingAs($this->user());

        $model = Business::factory()->createOne();
        $data = File::factory()->withoutFile()->raw();
        $data['upload'] = UploadedFile::fake()->create('file.pdf');
        $data['type'] = FileType::Document->value;

        $response = $this->postJson($this->getRoutePath($model, 'files'), $data);

        $response->assertCreated();
    }

    #[Test]
    public function should_able_to_retrieve_all_uploaded_files(): void
    {
        Sanctum::actingAs($this->user());

        $model = Business::factory()->withFileUpload(FileType::Document)->createOne();

        $response = $this->getJson($this->getRoutePath($model, 'files'));

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_update_existing_data(): void
    {
        Sanctum::actingAs($this->user());

        $model = Business::factory()->createOne();

        $response = $this->putJson($this->getRoutePath($model), $model->toArray());

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_able_to_delete_existing_data(): void
    {
        Sanctum::actingAs($this->user());

        $model = Business::factory()->createOne();

        $response = $this->deleteJson($this->getRoutePath($model));

        $response->assertNoContent();
    }

    #[Test]
    public function should_able_to_restore_deleted_data(): void
    {
        $this->markTestIncomplete();

        Sanctum::actingAs($user = $this->user());

        $model = Business::factory()->createOne();

        $model->delete();

        $response = $this->putJson($this->getRoutePath($model, 'restore'));

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);
    }
}
