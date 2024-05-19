<?php

declare(strict_types=1);

namespace Sereny\MultiCompany\Middleware;

use Sereny\MultiCompany\Exceptions\CompanyCouldNotBeIdentifiedException;
use Sereny\MultiCompany\Manager;
use Sereny\MultiCompany\Resolvers\CompanyResolver;

abstract class IdentificationMiddleware
{
     /** @var callable */
     public static $onFail;

    protected Manager $manager;

    protected CompanyResolver $resolver;

    public function initializeCompany($request, $next, ...$args)
    {
        try {
            $this->manager->initialize(
                $this->resolver->resolve(...$args)
            );
        } catch (CompanyCouldNotBeIdentifiedException $e) {
            $onFail = static::$onFail ?? function ($e) {
                throw $e;
            };

            return $onFail($e, $request, $next);
        }

        return $next($request);
    }
}
