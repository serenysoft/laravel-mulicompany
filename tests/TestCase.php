<?php

namespace Sereny\MultiCompany\Tests;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Sereny\MultiCompany\CompanyServiceProvider;
use Sereny\MultiCompany\Manager;
use Sereny\MultiCompany\Tests\Models\Company;

abstract class TestCase extends BaseTestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        Manager::$model = Company::class;

        $this->createSchema();
    }

   /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set([
            'database.default' => 'db',
            'cache.default' => 'file',
            'database.connections.db' => [
                'driver'    => 'sqlite',
                'database'  => ':memory:'
            ]
        ]);
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return [
            CompanyServiceProvider::class,
        ];
    }

    /**
     * Get a schema builder instance.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    protected function getSchemaBuilder()
    {
        return $this->getConnection()->getSchemaBuilder();
    }

    /**
     * Setup the database schema.
     *
     * @return void
     */
    protected function createSchema()
    {
        $this->getSchemaBuilder()->create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $this->getSchemaBuilder()->create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->foreignId('company_id')->constrained();
            $table->timestamps();
        });

        $this->getSchemaBuilder()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->foreignId('company_id')->constrained();
            $table->timestamps();
        });
    }
}
