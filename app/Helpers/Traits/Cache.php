<?php

namespace App\Helpers\Traits;

use Cache as Ca;

trait Cache
{
    public static function getCache($tag, $key)
    {
        $cache = Ca::tags($tag);

        if ($cache->has($key)) {
            return $cache->get($key);
        }

        return false;
    }

    public static function setCache($tag, $key, $data, $time = 10080)
    {
        $cache = Ca::tags($tag);

        return $cache->put($key, $data, $time);
    }

    public static function flushTag($tag)
    {
        return Ca::tags($tag)->flush();
    }

    public static function deleteByKey($key)
    {
        return Ca::pull($key);
    }
}
