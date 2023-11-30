<?php
/**
 * Created by PhpStorm.
 * User: suqing
 * Date: 21/11/23
 * Time: 20:12
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Base extends Model
{
    /**
     * @param Builder $query
     * @param array $filters
     */
    protected static function applyBasicFilter(Builder $query, $filters = [])
    {
        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }
    }
}