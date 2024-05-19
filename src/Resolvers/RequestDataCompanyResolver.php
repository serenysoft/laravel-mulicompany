<?php

declare(strict_types=1);

namespace Sereny\MultiCompany\Resolvers;

use Illuminate\Database\Eloquent\Model;
use Sereny\MultiCompany\Exceptions\CompanyCouldNotBeIdentifiedByRequestDataException;

class RequestDataCompanyResolver extends CompanyResolver
{
    /** @var bool */
    public static $shouldCache = false;

    /** @var int */
    public static $cacheTTL = 3600; // seconds

    /** @var string|null */
    public static $cacheStore = null; // default

    public function resolveWithoutCache(...$args): Model
    {
        $payload = $args[0];

        if ($payload && $company = company()->find($payload)) {
            return $company;
        }

        throw new CompanyCouldNotBeIdentifiedByRequestDataException($payload);
    }

    public function getArgsForCompany(Model $company): array
    {
        return [
            [$company->id],
        ];
    }
}
