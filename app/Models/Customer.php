<?php
/**
 * Created by PhpStorm.
 * User: suqing
 * Date: 24/07/20
 * Time: 14:17.
 */

namespace App\Models;

class Customer extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array $fillable
     */
    protected $fillable = [
        'name',
        'vat_number',
        'created_at',
        'updated_at',
    ];
}
