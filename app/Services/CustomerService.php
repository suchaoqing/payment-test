<?php

namespace App\Services;

use App\Helpers\Traits\Cache;
use App\Models\Account;
use App\Models\Customer;


class CustomerService
{
    const CACHE_TAG = 'customer';

    /**
     * @param $customerData
     * @return mixed
     */
    public static function create($customerData)
    {
        try {
            $customer = self::getByVatNumber($customerData['vat_number']);
            return $customer;
        } catch (\Exception $e) {
            $customer = Customer::create($customerData);

            Cache::setCache(self::CACHE_TAG, $customer->vat_number, $customer);

            return $customer;
        } catch (\Exception $e) {
            throw $e;
        }


    }

    /**
     * @param array $filters
     * @return mixed
     */
    private static function get(array $filters)
    {
        $account = Customer::select('*');

        Account::applyBasicFilter($account, $filters);

        return $account->get();
    }

    /**
     * @param $vatNumber
     * @return mixed
     */
    public static function getByVatNumber($vatNumber)
    {
        if ($customer = self::get(['vat_number' => $vatNumber])->first()) {
            return $customer;
        }

        throw new \Exception('Customer not exist', '404');
    }

}
