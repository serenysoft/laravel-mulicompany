<?php

namespace Sereny\MultiCompany\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Sereny\MultiCompany\InteractsWithCompany;

class Contact extends Model
{
    use InteractsWithCompany;

    protected $fillable = [
        'name',
        'company_id',
    ];

    /**
     * Scope a query to only include active contacts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

}
