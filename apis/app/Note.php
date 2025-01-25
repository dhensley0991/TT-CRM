<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'contact_id',
        'description',
    ];

    protected $table = 'notes';

}
