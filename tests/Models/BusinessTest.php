<?php

namespace Creasi\Tests\Models;

use Creasi\Base\Database\Models\Address;
use Creasi\Base\Database\Models\Business;
use Creasi\Base\Database\Models\FileUpload;
use Creasi\Base\Database\Models\Personnel;
use Creasi\Base\Enums\BusinessRelativeType;
use Creasi\Base\Enums\EmploymentStatus;
use Creasi\Base\Enums\EmploymentType;
use Creasi\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('models')]
#[Group('company')]
class BusinessTest extends TestCase
{
    #[Test]
    public function should_have_addresses()
    {
        $business = Business::factory()->withAddress()->createOne();

        $this->assertCount(1, $business->addresses);
        $this->assertInstanceOf(Address::class, $business->addresses->first());
    }

    #[Test]
    public function should_have_avatar_image()
    {
        $business = Business::factory()->createOne();

        // $this->assertNull($company->avatar);

        // dump($company->toArray());

        $avatar = $business->setAvatar(
            UploadedFile::fake()->image('logo.png')
        );

        $this->assertInstanceOf(FileUpload::class, $business->avatar);
        $this->assertTrue($business->avatar->is_internal);
    }

    #[Test]
    public function should_have_employees()
    {
        $business = Business::factory()->createOne();
        $person = Personnel::factory()->createOne();

        $business->employees()->attach($person, [
            'is_primary' => true,
            'type' => EmploymentType::Fulltime,
            'status' => EmploymentStatus::Permanent,
            'start_date' => now()->subDays(3),
        ]);

        $employee = $business->employees->first();

        $this->assertTrue($employee->employment->is_started);
        $this->assertNull($employee->employment->is_finished);
        $this->assertInstanceOf(EmploymentType::class, $employee->employment->type);
        $this->assertInstanceOf(EmploymentStatus::class, $employee->employment->status);
    }

    #[Test]
    public function should_have_other_company_as_stakeholders()
    {
        $business = Business::factory()->createOne(['name' => 'Internal Company']);
        $external = Business::factory()->createOne(['name' => 'External Company']);

        $business->addStakeholder(BusinessRelativeType::Vendor, $external);

        $this->assertCount(1, $business->companyRelatives);

        $vendor = $business->companyRelatives->first();

        $this->assertFalse($vendor->stakeholder->is_internal);
        $this->assertInstanceOf(BusinessRelativeType::class, $vendor->stakeholder->type);
    }

    #[Test]
    public function should_have_other_individual_as_stakeholders()
    {
        $business = Business::factory()->createOne(['name' => 'Some Company']);
        $personal = Personnel::factory()->withProfile()->createOne();

        $business->addStakeholder(BusinessRelativeType::Owner, $personal, true);

        $this->assertCount(1, $business->individualRelatives);

        $owner = $business->individualRelatives->first();

        $this->assertTrue($owner->stakeholder->is_internal);
        $this->assertInstanceOf(BusinessRelativeType::class, $owner->stakeholder->type);
    }

    #[Test]
    public function should_have_access_to_mixed_stakeholders()
    {
        $business = Business::factory()->createOne(['name' => 'Internal Company']);
        $external = Business::factory()->createOne(['name' => 'External Company']);
        $personal = Personnel::factory()->withProfile()->createOne();

        $business->addStakeholder(BusinessRelativeType::Owner, $personal);
        $business->addStakeholder(BusinessRelativeType::Supplier, $external);

        $this->assertCount(2, $business->stakeholders);
    }
}
