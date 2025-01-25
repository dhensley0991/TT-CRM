<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'id',
        'sortname',
        'name',
        'phonecode'
    ];

    protected $table = 'countries';

}
