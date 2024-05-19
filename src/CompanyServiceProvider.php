<?php

declare(strict_types=1);

namespace Sereny\MultiCompany;

use Illuminate\Support\ServiceProvider;
use Sereny\MultiCompany\Manager;
use Sereny\MultiCompany\Resolvers\RequestDataCompanyResolver;

class CompanyServiceProvider extends ServiceProvider
{
    public static $resolvers = [
        RequestDataCompanyResolver::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Manager::class);

        foreach (static::$resolvers as $resolver) {
            $this->app->singleton($resolver);
        }

    }
}
