<?php

declare(strict_types=1);

namespace Sereny\MultiCompany;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Sereny\MultiCompany\Exceptions\CompanyCouldNotBeIdentifiedException;

class CompanyScope implements Scope
{
    /**
     * @inheritdoc
     */
    public function apply(Builder $builder, Model $model)
    {
        $company = company()->current();

        if ($company === null) {
            throw new CompanyCouldNotBeIdentifiedException();
        }

        $builder->whereBelongsTo($company);
    }
}
