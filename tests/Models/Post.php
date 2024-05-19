<?php

namespace Sereny\MultiCompany\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Sereny\MultiCompany\InteractsWithCompany;

class Post extends Model
{
    use InteractsWithCompany;

    protected static $disableCompanyScope = true;

    protected $fillable = [
        'title',
        'company_id',
    ];
}
