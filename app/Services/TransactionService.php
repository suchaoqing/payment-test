<?php

namespace App\Services;

use App\Helpers\Traits\Cache;
use App\Models\Account;
use App\Models\Transaction;


class TransactionService extends BaseService
{
    const CACHE_TAG = 'transactions';

    /**
     * @param $account
     * @return mixed
     */
    public static function create($transaction)
    {
        $transaction['transaction_id'] = self::generateHashCode();

        $transaction = Transaction::create($transaction);

        return $transaction;
    }

    /**
     * @param $paymentMethodId
     * @return mixed
     */
    public static function get(array $filters)
    {
        $account = Account::select('*');

        Account::applyBasicFilter($account, $filters);



        return $account->get()->toArray();
    }

}
