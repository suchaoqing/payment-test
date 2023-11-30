<?php

namespace App\Services;

use App\Helpers\Traits\Cache;
use App\Models\Account;
use App\Models\Customer;


class BaseService
{
    const CODE_SIZE = 8;

    protected static function generateHashCode(bool $mod11=false, bool $includeDash=false, int $each=4)
    {
        do{
            $result = true;
            $hash = bin2hex(random_bytes(8));
            if($mod11){
                $hash = self::GenMOD11(self::generateRandomHash());
            }
        }while(!$hash);

        return $includeDash? implode("-", str_split($hash, $each)) : $hash;
    }

    private static function generateRandomHash()
    {
        $min = str_pad("1", self::CODE_SIZE, "0");
        $max = str_pad("1", self::CODE_SIZE, "9");

        return mt_rand($min, $max);
    }

    /**
     * Generates a modulus 11 string
     * @param string $baseValue
     * @return string
     */
    public static function GenMOD11(string $baseValue)
    {
        $result = false;
        $weight = array(2, 3, 4, 5, 6, 7,
            2, 3, 4, 5, 6, 7,
            2, 3, 4, 5, 6, 7,
            2, 3, 4, 5, 6, 7);

        /* For convenience, reverse the string and work left to right. */
        $reversedBaseVal = strrev( $baseValue );
        for ( $i = 0, $sum = 0; $i < strlen( $reversedBaseVal ); $i++ )
        {
            /* Calculate product and accumulate. */
            $sum += substr( $reversedBaseVal, $i, 1 ) * $weight[ $i ];
        }

        /* Determine check digit, and concatenate to base value. */
        $remainder = $sum % 11;
        switch ( $remainder )
        {
            case 0:
                $result = $baseValue . 0;
                break;
            case 1:
                $result = false;
                break;
            default:
                $check_digit = 11 - $remainder;
                $result = $baseValue . $check_digit;
                break;
        }
        return $result;
    }

}
