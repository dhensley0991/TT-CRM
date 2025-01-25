<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'id',
        'old_contact_id',
        'user_id',
        'contact_id',
        'description',
        'readMessage',
        'createdBy'
    ];

    protected $table = 'notifications';

}
