<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'account_name',
        'account_number',
        'address',
        'address_two',
        'address_three',
        'city',
        'state',
        'zip',
        'country',
        'province',
        'website',
        'phone_number',
        'fax_number',
        'email'
    ];

    protected $table = 'accounts';

}
