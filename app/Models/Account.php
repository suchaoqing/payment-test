<?php
/**
 * Created by PhpStorm.
 * User: suqing
 * Date: 24/07/20
 * Time: 14:17.
 */

namespace App\Models;

class Account extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array $fillable
     */
    protected $fillable = [
        'customer_id',
        'account_number',
        'balance',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
