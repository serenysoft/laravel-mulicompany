<?php

namespace Sereny\MultiCompany\Tests;

use Sereny\MultiCompany\Exceptions\CompanyCouldNotBeIdentifiedException;
use Sereny\MultiCompany\Tests\Models\Company;
use Sereny\MultiCompany\Tests\Models\Contact;
use Sereny\MultiCompany\Tests\Models\Post;

class InteractsWithCompanyTest extends TestCase
{

    /** @test */
    public function create_models_with_company_initialized()
    {
        $acme = Company::create(['name' => 'acme']);
        $silly = Company::create(['name' => 'silly']);

        company()->initialize($acme);

        $test = Contact::create([
            'name' => 'Test',
            'active' => true
        ]);

        $forced = Contact::create([
            'name' => 'Forced',
            'active' => true,
            'company_id' => $silly->id
        ]);

        $test = Contact::find($test->id);
        $forced = Contact::find($forced->id);

        $this->assertEquals($test->company_id, $acme->id);
        $this->assertNull($forced);

        $this->assertEquals(1, Contact::count());
        $this->assertEquals(2, Contact::withoutCompany()->count());

        company()->end();
    }

    /** @test */
    public function create_model_without_company_initialized()
    {
        $this->expectException(CompanyCouldNotBeIdentifiedException::class);

        Contact::create([
            'name' => 'Test',
            'active' => true
        ]);
    }

    /** @test */
    public function disabled_company_scope()
    {
        $acme = Company::create(['name' => 'acme']);
        $silly = Company::create(['name' => 'silly']);

        Post::withoutEvents(function() use ($acme, $silly) {
            Post::create(['title' => 'Post 1', 'company_id' => $acme->id]);
            Post::create(['title' => 'Post 1', 'company_id' => $silly->id]);
        });

        company()->initialize($acme);

        $this->assertEquals(2, Post::count());

        company()->end();
    }

    /** @test */
    public function find_data_without_company_initialized()
    {
        $this->expectException(CompanyCouldNotBeIdentifiedException::class);

        Contact::all();
    }
}
