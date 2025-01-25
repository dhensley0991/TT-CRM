<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactFile extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'contact_id',
        'file_name',
        'description',
        'file',
    ];

    protected $table = 'contact_files';

}
