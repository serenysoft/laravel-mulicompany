<?php

declare(strict_types=1);

namespace Sereny\MultiCompany\Resolvers;

use Illuminate\Contracts\Cache\Factory;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Model;

abstract class CompanyResolver
{
    /** @var bool */
    public static $shouldCache = false;

    /** @var int */
    public static $cacheTTL = 3600; // seconds

    /** @var string|null */
    public static $cacheStore = null; // default

    /** @var Repository */
    protected $cache;

    public function __construct(Factory $cache)
    {
        $this->cache = $cache->store(static::$cacheStore);
    }

    public function resolve(...$args): Model
    {
        if (! static::$shouldCache) {
            return $this->resolveWithoutCache(...$args);
        }

        $key = $this->getCacheKey(...$args);

        if ($tenant = $this->cache->get($key)) {
            $this->resolved($tenant, ...$args);

            return $tenant;
        }

        $tenant = $this->resolveWithoutCache(...$args);
        $this->cache->put($key, $tenant, static::$cacheTTL);

        return $tenant;
    }

    public function invalidateCache(Model $company): void
    {
        if (! static::$shouldCache) {
            return;
        }

        foreach ($this->getArgsForCompany($company) as $args) {
            $this->cache->forget($this->getCacheKey(...$args));
        }
    }

    public function getCacheKey(...$args): string
    {
        return '_company_resolver:' . static::class . ':' . json_encode($args);
    }

    public function resolved(Model $company, ...$args): void
    {
    }

    abstract public function resolveWithoutCache(...$args): Model;

    /**
     * Get all the arg combinations for resolve() that can be used to find this company.
     *
     * @param Model $company
     * @return array[]
     */
    abstract public function getArgsForCompany(Model $company): array;
}
