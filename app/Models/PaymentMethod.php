<?php
/**
 * Created by PhpStorm.
 * User: suqing
 * Date: 24/07/20
 * Time: 14:17.
 */

namespace App\Models;

class PaymentMethod extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array $fillable
     */
    protected $fillable = [
        'method_id',
        'name',
        'tax_percentage',
        'created_at',
        'updated_at',
    ];
}
