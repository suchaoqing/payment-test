<?php

namespace App\Services;

use App\Helpers\Traits\Cache;
use App\Models\Account;

class OperationService
{
    const CACHE_TAG = 'operations';
    const DEBIT = 'DEBIT';
    const CREDIT = 'CREDIT';


    /**
     * @param $account
     * @return mixed
     */
    public static function createNewCustomerAndAccount($data)
    {
        $customer = CustomerService::create($data['customer']);
        $account = AccountService::create($data['account'], $customer);

        $transactionData = self::buildTransactionData($account->id, self::CREDIT, $account->balance);

        $transaction = TransactionService::create($transactionData);

        return array_merge($account->toArray(), ['transaction' => $transaction]);
    }

    public static function debit($request)
    {
        try {
            $account = AccountService::getAccountByNumber($request->get('account_number'));

            $transactionData = self::buildTransactionData($account->id, self::DEBIT, $request->get('amount'), $request->get('payment_method'));
            self::validateAccountBalance($account->balance, $transactionData);

            $transaction = TransactionService::create($transactionData);

            $account = AccountService::update($account, [
                'balance' => (string)$account->balance - (string) $transaction->total_amount
            ]);

            return [
                'account_number' => $account->account_number,
                'balance' => $account->balance,
                'transaction' => $transaction,
            ];

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public static function credit($request)
    {
        $account = AccountService::getAccountByNumber($request->get('account_number'));

        $transactionData = self::buildTransactionData($account->id, self::CREDIT, $request->get('amount'), $request->get('payment_method'));

        $transaction = TransactionService::create($transactionData);

        $account = AccountService::update($account, [
            'balance' => (string)$account->balance + (string) $transaction->total_amount
        ]);

        return [
            'account_number' => $account->account_number,
            'balance' => $account->balance,
            'transaction' => $transaction,
        ];
    }

    private static function validateAccountBalance($balance, $transactionData)
    {
        if ($transactionData['total_amount'] > $balance) {
            throw new \Exception(sprintf("insufficient balance. Balance: %s, Transaction amount: %s, Tax percentage: %s, Tax amount: %s, Total amount: %s",
                $balance,
                $transactionData['amount'],
                $transactionData['tax_percentage'],
                $transactionData['tax_amount'],
                $transactionData['total_amount']
            ), 404);
        }
    }


    public static function buildTransactionData($accountId, $operationType, $amount, $paymentMethodId = null)
    {
        $transaction = [
            'account_id' => $accountId,
            'operation' => $operationType,
            'amount' => $amount,
            'total_amount' => $amount,
        ];

        switch ($operationType) {
            case self::DEBIT :

                $paymentMethod = PaymentMethodService::getMethodByMethodId($paymentMethodId);

                $taxAmount = (string) $amount * (string) $paymentMethod->tax_percentage;
                $totalAmount = (string) $amount + (string) $taxAmount;

                $transaction = array_merge($transaction, [
                    'tax_amount' => $taxAmount,
                    'tax_percentage' => $paymentMethod->tax_percentage,
                    'total_amount' => (string) $totalAmount,
                    'payment_method' => $paymentMethod->method_id,
                ]);
                break;

            case self::CREDIT :

                break;
        }


        return $transaction;

    }

}
