<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'contact_name',
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
        'mailing_address',
        'mailing_address_two',
        'mailing_address_three',
        'mail_city',
        'mail_state',
        'mail_zip',
        'mail_country',
        'mail_province',
        'referral_source',
        'total_referral',
        'acc',
        'platform',
        'trader_id',
        'comm_deal',
        'locate_deal',
        'platform_price',
        'program_member',
        'cost_per_month',
        'shares_per_month',
        'category_referral',
        'field2',
        'field3',
        'field4',
        'field5',
        'time_limit'
    ];

    protected $table = 'contacts';

}
