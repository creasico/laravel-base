<?php

namespace Creasi\Tests\Models;

use Creasi\Base\Models\Address;
use Creasi\Base\Models\Company;
use Creasi\Base\Models\File;
use Creasi\Base\Models\Enums\CompanyRelativeType;
use Creasi\Base\Models\Enums\EmploymentStatus;
use Creasi\Base\Models\Enums\EmploymentType;
use Creasi\Base\Models\Personnel;
use Creasi\Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('company')]
class CompanyTest extends TestCase
{
    #[Test]
    public function should_be_exists()
    {
        $model = Company::factory()->createOne();

        $this->assertModelExists($model);
    }

    #[Test]
    public function should_have_addresses()
    {
        $company = Company::factory()->withAddress()->createOne();

        $this->assertCount(1, $company->addresses);
        $this->assertInstanceOf(Address::class, $company->addresses->first());
    }

    #[Test]
    public function should_have_documents()
    {
        $company = Company::factory()->createOne();
        $file = File::factory()->createOne();

        $company->files()->save($file);

        $this->assertCount(1, $company->files);
        $this->assertCount(1, $file->ownedByCompanies);
    }

    #[Test]
    public function should_have_employees()
    {
        $company = Company::factory()->createOne();
        $person = Personnel::factory()->createOne();

        $company->employees()->attach($person, [
            'is_primary' => true,
            'type' => EmploymentType::Fulltime,
            'status' => EmploymentStatus::Permanent,
            'start_date' => now()->subDays(3),
        ]);

        $employee = $company->employees->first();

        $this->assertTrue($employee->employment->is_started);
        $this->assertNull($employee->employment->is_finished);
        $this->assertInstanceOf(EmploymentType::class, $employee->employment->type);
        $this->assertInstanceOf(EmploymentStatus::class, $employee->employment->status);
    }

    #[Test]
    public function should_have_other_company_as_stakeholders()
    {
        $company = Company::factory()->createOne(['name' => 'Internal Company']);
        $external = Company::factory()->createOne(['name' => 'External Company']);

        $company->addStakeholder(CompanyRelativeType::Vendor, $external);

        $this->assertCount(1, $company->companyRelatives);

        $vendor = $company->companyRelatives->first();

        $this->assertFalse($vendor->stakeholder->is_internal);
        $this->assertInstanceOf(CompanyRelativeType::class, $vendor->stakeholder->type);
    }

    #[Test]
    public function should_have_other_individual_as_stakeholders()
    {
        $company = Company::factory()->createOne(['name' => 'Some Company']);
        $personal = Personnel::factory()->withIdentity()->createOne();

        $company->addStakeholder(CompanyRelativeType::Owner, $personal, true);

        $this->assertCount(1, $company->individualRelatives);

        $owner = $company->individualRelatives->first();

        $this->assertTrue($owner->stakeholder->is_internal);
        $this->assertInstanceOf(CompanyRelativeType::class, $owner->stakeholder->type);
    }

    #[Test]
    public function should_have_access_to_mixed_stakeholders()
    {
        $company = Company::factory()->createOne(['name' => 'Internal Company']);
        $external = Company::factory()->createOne(['name' => 'External Company']);
        $personal = Personnel::factory()->withIdentity()->createOne();

        $company->addStakeholder(CompanyRelativeType::Owner, $personal);
        $company->addStakeholder(CompanyRelativeType::Supplier, $external);

        $this->assertCount(2, $company->stakeholders);
    }
}
