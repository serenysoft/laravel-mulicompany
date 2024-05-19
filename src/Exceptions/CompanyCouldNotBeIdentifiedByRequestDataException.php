<?php

declare(strict_types=1);

namespace Sereny\MultiCompany\Exceptions;

class CompanyCouldNotBeIdentifiedByRequestDataException extends CompanyCouldNotBeIdentifiedException
{
    public function __construct($tenant_id)
    {
        parent::__construct("Company could not be identified by request data with payload: $tenant_id");
    }
}
