<?php

declare(strict_types=1);

namespace Sereny\MultiCompany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sereny\MultiCompany\Exceptions\CompanyCouldNotBeIdentifiedException;

/**
 * @property boolean $disableCompanyScope
 */
trait InteractsWithCompany
{

    public static function bootInteractsWithCompany()
    {
        if (! property_exists(get_called_class(), 'disableCompanyScope') || ! static::$disableCompanyScope) {
            static::addGlobalScope(new CompanyScope());
        }

        static::creating(function(Model $model) {
            $model->applyCompany();
        });
    }

    /**
     * The company relationship
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Manager::$model);
    }

    /**
     * Sets company_id column with current company
     *
     * @throws \Sereny\MultiCompany\Exceptions\CompanyCouldNotBeIdentifiedException
     */
    public function applyCompany()
    {
        $foreignKeyName =  $this->company()->getForeignKeyName();

        if ($this->{$foreignKeyName} === null) {
            $company = company()->current();

            if (! $company) {
                throw new CompanyCouldNotBeIdentifiedException();
            }

            $this->{$foreignKeyName} = $company->id;
        }
    }

    /**
     * Remove a registered Tenant global scope.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function withoutCompany()
    {
       return static::withoutGlobalScope(CompanyScope::class);
    }

}
