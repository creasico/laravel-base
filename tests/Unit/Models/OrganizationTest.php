<?php

namespace Creasi\Tests\Unit\Models;

use Creasi\Base\Database\Models\Address;
use Creasi\Base\Database\Models\File;
use Creasi\Base\Database\Models\Organization;
use Creasi\Base\Database\Models\Person;
use Creasi\Base\Enums\PersonnelStatus;
use Creasi\Base\Enums\StakeholderType;
use Creasi\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('models')]
#[Group('organization')]
class OrganizationTest extends TestCase
{
    #[Test]
    public function should_have_addresses()
    {
        /** @var Organization */
        $business = Organization::factory()->withAddress()->createOne();

        $this->assertCount(1, $business->addresses);
        $this->assertInstanceOf(Address::class, $business->addresses->first());
    }

    #[Test]
    public function should_have_avatar_image()
    {
        /** @var Organization */
        $business = Organization::factory()->createOne();

        // $this->assertNull($company->avatar);

        // dump($company->toArray());

        $avatar = $business->setAvatar(
            UploadedFile::fake()->image('logo.png')
        );

        $this->assertInstanceOf(File::class, $business->avatar);
        $this->assertTrue($business->avatar->is_internal);
    }

    #[Test]
    public function should_have_employees()
    {
        /** @var Organization */
        $business = Organization::factory()->createOne();
        /** @var Person */
        $person = Person::factory()->createOne();

        $business->addEmployee($person, PersonnelStatus::Fulltime, now()->subDays(3), true);

        $employee = $business->employees->first();

        $this->assertTrue($employee->employment->is_started);
        $this->assertNull($employee->employment->is_finished);
        $this->assertInstanceOf(PersonnelStatus::class, $employee->employment->personnel_status);
    }

    #[Test]
    public function should_have_other_company_as_stakeholders()
    {
        /** @var Organization */
        $business = Organization::factory()->createOne(['name' => 'Internal Company']);
        /** @var Organization */
        $external = Organization::factory()->createOne(['name' => 'External Company']);

        $business->addStakeholder(StakeholderType::Vendor, $external);

        $this->assertCount(1, $business->companyRelatives);

        $vendor = $business->companyRelatives->first();

        $this->assertFalse($vendor->stakeholder->is_internal);
        $this->assertInstanceOf(StakeholderType::class, $vendor->stakeholder->type);
    }

    #[Test]
    public function should_have_other_individual_as_stakeholders()
    {
        $business = Organization::factory()->createOne(['name' => 'Some Company']);
        $personal = Person::factory()->createOne();

        $business->addStakeholder(StakeholderType::Owner, $personal, true);

        $this->assertCount(1, $business->individualRelatives);

        $owner = $business->individualRelatives->first();

        $this->assertTrue($owner->stakeholder->is_internal);
        $this->assertInstanceOf(StakeholderType::class, $owner->stakeholder->type);
    }

    #[Test]
    public function should_have_access_to_mixed_stakeholders()
    {
        /** @var Organization */
        $business = Organization::factory()->createOne(['name' => 'Internal Company']);
        /** @var Organization */
        $external = Organization::factory()->createOne(['name' => 'External Company']);
        /** @var Organization */
        $personal = Person::factory()->createOne();

        $business->addStakeholder(StakeholderType::Owner, $personal);
        $business->addStakeholder(StakeholderType::Supplier, $external);

        $this->assertCount(2, $business->stakeholders);
    }
}
