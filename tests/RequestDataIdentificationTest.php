<?php

declare(strict_types=1);

namespace Sereny\MultiCompany\Tests;

use Illuminate\Support\Facades\Route;
use Sereny\MultiCompany\Middleware\InitializeCompanyByRequestData;
use Sereny\MultiCompany\Tests\Models\Company;

class RequestDataIdentificationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::middleware(InitializeCompanyByRequestData::class)->get('/test', function () {
            return 'Company id: ' . company()->current('id');
        });
    }

    public function tearDown(): void
    {
        InitializeCompanyByRequestData::$header = 'X-Company';
        InitializeCompanyByRequestData::$queryParameter = 'company';

        parent::tearDown();
    }

    /** @test */
    public function header_identification_works()
    {
        InitializeCompanyByRequestData::$header = 'X-Company';
        $company = Company::create(['name' => 'acme']);

        $this
            ->withoutExceptionHandling()
            ->get('test', [
                'X-Company' => $company->id,
            ])
            ->assertSee($company->id);
    }

    /** @test */
    public function query_parameter_identification_works()
    {
        InitializeCompanyByRequestData::$header = null;
        InitializeCompanyByRequestData::$queryParameter = 'company';

        $company = Company::create(['name' => 'acme']);

        $this
            ->withoutExceptionHandling()
            ->get('test?company=' . $company->id)
            ->assertSee($company->id);
    }
}
