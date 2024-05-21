<?php

declare(strict_types=1);

namespace Sereny\MultiCompany\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sereny\MultiCompany\Manager;
use Sereny\MultiCompany\Resolvers\CompanyResolver;
use Sereny\MultiCompany\Resolvers\RequestDataCompanyResolver;
use Stancl\Tenancy\Contracts\TenantResolver;
use Stancl\Tenancy\Tenancy;

class InitializeCompanyByRequestData extends IdentificationMiddleware
{
    public static ?string $header = 'X-Company';

    public static ?string $queryParameter = 'company';

    /** @var callable|null */
    public static $onFail;

    /** @var Tenancy */
    protected Manager $manager;

    /** @var TenantResolver */
    protected CompanyResolver $resolver;

    public function __construct(Manager $manager, RequestDataCompanyResolver $resolver)
    {
        $this->manager = $manager;
        $this->resolver = $resolver;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() !== 'OPTIONS') {
            return $this->initializeCompany($request, $next, $this->getPayload($request));
        }

        return $next($request);
    }

    protected function getPayload(Request $request): ?string
    {
        $company = null;
        if (static::$header && $request->hasHeader(static::$header)) {
            $company = $request->header(static::$header);
        } elseif (static::$queryParameter && $request->has(static::$queryParameter)) {
            $company = $request->get(static::$queryParameter);
        }

        return $company;
    }
}
