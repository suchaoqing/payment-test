<?php

namespace App\Services;

use App\Helpers\Traits\Cache;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Self_;


class PaymentMethodService
{
    const CACHE_TAG = 'payment';

    /**
     * @param $paymentMethod
     * @return mixed
     */
    public static function createMethod($paymentMethod)
    {
        $paymentMethodModel = PaymentMethod::firstOrNew([
            'method_id' => $paymentMethod['method_id'],
        ]);

        $paymentMethodModel->fill($paymentMethod);

        $paymentMethodModel->save();

        Cache::setCache(self::CACHE_TAG, $paymentMethodModel->method_id, $paymentMethodModel);

        return $paymentMethodModel;
    }

    /**
     * @param $paymentMethodId
     * @return mixed
     */
    public static function getMethodByMethodId($paymentMethodId)
    {
        if ($paymentMethod = Cache::getCache(self::CACHE_TAG, $paymentMethodId)) {
            return $paymentMethod;
        }

        $paymentMethod = PaymentMethod::where('method_id', $paymentMethodId)->first();

        Cache::setCache(self::CACHE_TAG, $paymentMethodId, $paymentMethod);

        return $paymentMethod;
    }


    public static function getAllowedPaymentMethod()
    {
        $tagKey = 'ALLOWED_PAYMENT_METHOD';

        if ($allowedPaymentMethods = Cache::getCache(self::CACHE_TAG, $tagKey)) {
            return $allowedPaymentMethods;
        }

        if ($paymentMethods = PaymentMethod::selectRaw('method_id, name')->get()) {
            foreach ($paymentMethods as $paymentMethod) {
                $allowedPaymentMethods[$paymentMethod->method_id] = $paymentMethod->method_id." for ".$paymentMethod->name;
            }

            Cache::setCache(self::CACHE_TAG, $tagKey, $allowedPaymentMethods);

            return $allowedPaymentMethods;
        }

        throw new \Exception('There is no registered payment method');
    }
}
