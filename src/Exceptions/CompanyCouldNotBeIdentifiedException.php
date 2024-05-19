<?php

declare(strict_types=1);

namespace Sereny\MultiCompany\Exceptions;

use Exception;

class CompanyCouldNotBeIdentifiedException extends Exception
{

    public function __construct(string $message = null)
    {
        parent::__construct($message ?? "Company could not be identified");
    }

}
