<?php

declare(strict_types=1);

namespace Sereny\MultiCompany\Exceptions;


class CompanyCouldNotBeIdentifiedByIdException extends CompanyCouldNotBeIdentifiedException
{
    public function __construct($company_id)
    {
        parent::__construct("Company could not be identified with tenant_id: $company_id");
    }
}
