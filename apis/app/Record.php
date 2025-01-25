<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'acc',
        'platform',
        'trader_id',
        'comm_deal',
        'locate_deal',
        'routing_deal',
        'program_member',
        'program_detail_cost_per_month',
        'program_detail_shares_per_month',
        'program_detail_time_limit'
    ];

    protected $table = 'records';

}
