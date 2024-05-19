<?php

declare(strict_types=1);

namespace Stancl\Tenancy\Tests;

use Illuminate\Support\Facades\DB;
use Sereny\MultiCompany\Resolvers\RequestDataCompanyResolver;
use Sereny\MultiCompany\Tests\Models\Company;
use Sereny\MultiCompany\Tests\TestCase;

class CompanyResolverTest extends TestCase
{
    public function tearDown(): void
    {
        RequestDataCompanyResolver::$shouldCache = false;

        parent::tearDown();
    }

    /** @test */
    public function companies_can_be_resolved_using_the_cached_resolver()
    {
        $company = Company::create(['name' => 'acme']);

        $this->assertTrue($company->is(app(RequestDataCompanyResolver::class)->resolve($company->id)));
        $this->assertTrue($company->is(app(RequestDataCompanyResolver::class)->resolve($company->id)));
    }

    /** @test */
    public function the_underlying_resolver_is_not_touched_when_using_the_cached_resolver()
    {
        $company = Company::create(['name' => 'acme']);

        DB::enableQueryLog();

        RequestDataCompanyResolver::$shouldCache = false;

        $this->assertTrue($company->is(app(RequestDataCompanyResolver::class)->resolve($company->id)));
        DB::flushQueryLog();
        $this->assertTrue($company->is(app(RequestDataCompanyResolver::class)->resolve($company->id)));
        $this->assertNotEmpty(DB::getQueryLog());

        RequestDataCompanyResolver::$shouldCache = true;

        $this->assertTrue($company->is(app(RequestDataCompanyResolver::class)->resolve($company->id)));
        DB::flushQueryLog();
        $this->assertTrue($company->is(app(RequestDataCompanyResolver::class)->resolve($company->id)));
        $this->assertEmpty(DB::getQueryLog());
    }
}
