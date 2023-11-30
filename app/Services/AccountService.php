<?php

namespace App\Services;

use App\Helpers\Traits\Cache;
use App\Models\Account;
use App\Models\Customer;


class AccountService extends BaseService
{
    const CACHE_TAG = 'accounts';

    /**
     * @param $account
     * @return mixed
     */
    public static function create($account, Customer $customer)
    {
        $account['customer_id'] = $customer->id;
        $account['account_number'] = self::generateHashCode(true);
        $account = Account::create($account);

        Cache::setCache(self::CACHE_TAG, $account->account_number, $account);

        return $account;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public static function get(array $filters)
    {
        $account = Account::select('*');

        Account::applyBasicFilter($account, $filters);

        return $account->get();
    }


    public static function update(Account $account, array $data)
    {
        $account->update($data);
        Cache::setCache(self::CACHE_TAG, $account->account_number, $account);

        return $account;
    }

    public static function getAccountByNumber($number)
    {
        if ($account = Cache::getCache(self::CACHE_TAG, $number)) {
            return $account;
        }

        $account = self::get(['account_number' => $number])->first();

        if(!$account) {
            throw new \Exception('Account not exist', '404');
        }

        Cache::setCache(self::CACHE_TAG, $account->account_number, $account);

        return $account;



    }


}
