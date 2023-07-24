<?php

namespace Creasi\Tests\Http\Business;

use Creasi\Base\Models\Address;
use Creasi\Base\Models\FileUpload;
use Creasi\Base\Models\Personnel;
use Creasi\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('employee')]
class EmployeeTest extends TestCase
{
    #[Test]
    public function should_able_to_retrieve_all_data(): void
    {
        Sanctum::actingAs($this->user());
        Personnel::factory(2)->create();

        $response = $this->getJson('base/employees');

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_store_new_data(): void
    {
        Sanctum::actingAs($this->user());
        $data = Personnel::factory()->raw();

        $response = $this->postJson('base/employees', $data);

        $response->assertCreated();
    }

    #[Test]
    public function should_able_to_show_existing_data(): void
    {
        Sanctum::actingAs($this->user());

        $model = Personnel::factory()->createOne();

        $response = $this->getJson("base/employees/{$model->getRouteKey()}");

        $response->assertOk();
    }

    #[Test]
    public function should_receive_404_when_no_addresses_available(): void
    {
        Sanctum::actingAs($this->user());

        $model = Personnel::factory()->createOne();

        $response = $this->getJson("base/employees/{$model->getRouteKey()}/addresses");

        $response->assertNotFound();
    }

    #[Test]
    public function should_able_to_create_new_address(): void
    {
        Sanctum::actingAs($this->user());

        $model = Personnel::factory()->createOne();
        $data = Address::factory()->raw();

        $response = $this->postJson("base/employees/{$model->getRouteKey()}/addresses", $data);

        $response->assertCreated();
    }

    #[Test]
    public function should_able_to_retrieve_all_addresses(): void
    {
        Sanctum::actingAs($this->user());

        $model = Personnel::factory()->createOne();

        $model->addresses()->saveMany(Address::factory(2)->create());

        $response = $this->getJson("base/employees/{$model->getRouteKey()}/addresses");

        $response->assertOk();
    }

    #[Test]
    public function should_receive_404_when_no_files_available(): void
    {
        Sanctum::actingAs($this->user());

        $model = Personnel::factory()->createOne();

        $response = $this->getJson("base/employees/{$model->getRouteKey()}/files");

        $response->assertNotFound();
    }

    #[Test]
    public function should_able_to_upload_and_store_new_file(): void
    {
        Storage::fake();
        Sanctum::actingAs($this->user());

        $model = Personnel::factory()->createOne();
        $data = FileUpload::factory()->withoutFile()->raw();
        $data['upload'] = UploadedFile::fake()->create('file.pdf');

        $response = $this->postJson("base/employees/{$model->getRouteKey()}/files", $data);

        $response->assertCreated();
    }

    #[Test]
    public function should_able_to_retrieve_all_uploaded_files(): void
    {
        Sanctum::actingAs($this->user());

        $model = Personnel::factory()->createOne();

        $model->files()->saveMany(FileUpload::factory(2)->create());

        $response = $this->getJson("base/employees/{$model->getRouteKey()}/files");

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_update_existing_data(): void
    {
        Sanctum::actingAs($this->user());

        $model = Personnel::factory()->createOne();

        $response = $this->putJson("base/employees/{$model->getRouteKey()}", $model->toArray());

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_delete_existing_data(): void
    {
        Sanctum::actingAs($this->user());

        $model = Personnel::factory()->createOne();

        $response = $this->deleteJson("base/employees/{$model->getRouteKey()}");

        $response->assertNoContent();
    }
}
