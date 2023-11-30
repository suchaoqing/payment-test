<?php
/**
 * Created by PhpStorm.
 * User: suqing
 * Date: 24/07/20
 * Time: 14:17.
 */

namespace App\Models;

class Transaction extends Base
{
    protected $fillable = [
        'transaction_id',
        'account_id',
        'operation',
        'amount',
        'tax_amount',
        'tax_percentage',
        'total_amount',
        'payment_method',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'account_id',
    ];
}
