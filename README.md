# Laravel Multi Company

[![Build Status](https://github.com/serenysoft/laravel-multicompany/actions/workflows/tests.yml/badge.svg)](https://github.com/serenysoft/laravel-multicompany/actions/workflows/tests.yml)
[![Total Downloads](https://poser.pugx.org/sereny/laravel-multicompany/downloads.png)](https://packagist.org/packages/sereny/laravel-multicompany)
[![Latest Stable Version](https://poser.pugx.org/sereny/laravel-multicompany/v/stable.png)](https://packagist.org/packages/sereny/laravel-multicompany)

This Laravel library provides a flexible and secure approach to multicompany within a shared database. It allows you to filter and populate the `company_id` field of models that belong to a company, enabling you to manage data for multiple companies while maintaining data separation.

## Features

- Model Scopes: Filter models based on the currently authenticated company, ensuring data isolation.
- Middleware: Automatically set the `company_id` on incoming requests based on a configurable identifier.
- Company Detection: Easily identify the current company within your application.

Installation
------------

The preferred way to install this library is through composer.

Either run

`composer require --prefer-dist sereny/laravel-multicompany "*"`

or add

`"sereny/laravel-multicompany": "*"`

to the require section of your composer.json.

Usage
-----

1. Create table with `tenant_id` column:

```php
/**
 * Run the migrations.
 *
 * @return void
  */
public function up()
{
    Schema::create('companies', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
    });

    Schema::create('users', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->string('login')->nullable();
        $table->string('password')->nullable();
        $table->string('remember_token')->nullable();
        $table->foreignId('company_id')->constrained();
    });
}
```
2. Set `\Sereny\MultiCompany\Middleware\InitializeCompanyByRequestData::class` as middleware

3. Uses the `InteractsWithCompany` trait, it add a [Global Scope](https://laravel.com/docs/master/eloquent#global-scopes)
filtering any query by `company_id` column.

```php

<?php

use Illuminate\Database\Eloquent\Model;
use Sereny\MultiCompany\InteractsWithCompany;
use Sereny\MultiCompany\Tenant;

class User extends Model implements Tenant
{
    use InteractsWithCompany, Authenticatable;

    ...
}
```

Now when you save or execute same query the `company_id` column will be used. Example:

```php
// It's necessary will be logged in

User::where('active', 1)->get();
// select * from `users` where `active` = 1 and company_id = 1

User::create(['name' => 'Bob']);
// insert into `pet` (`name`, 'company_id') values ('Bob', 1)
```
