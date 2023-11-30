<?php

namespace Tests\Acceptance;

use App\Models\Client;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\WithoutMiddleware;
use TestCase;

/**
 * Class OperationTest
 * @package Tests\Acceptance
 */
class OperationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    protected $data = [
        'customer' => [
            'name' => 'joão',
            'vat_number' => '1234567890123',
        ],
        'account' => [
            'balance' => '200',
        ],
    ];

    private function createNewCustomerAndAccount()
    {
        return $this->post("/v1/operation/new-customer", $this->data);
    }

    /**
     * @group testAccount
     */
    public function testCreateNewCustomerAndAccount()
    {
        $response = $this->createNewCustomerAndAccount();
        $this->seeStatusCode(201);

        $responseData = json_decode($response->response->getContent(), true);
        $this->assertEquals($this->data['account']['balance'], $responseData['balance']);
        $this->assertArrayHasKey('customer_id', $responseData);
        $this->assertArrayHasKey('account_number', $responseData);
        $this->assertArrayHasKey('transaction', $responseData);
        $this->assertArrayHasKey('transaction_id', $responseData['transaction']);
        $this->assertEquals($this->data['account']['balance'], $responseData['transaction']['total_amount']);
        $this->assertEquals($this->data['account']['balance'], $responseData['transaction']['amount']);
        $this->assertEquals('CREDIT', $responseData['transaction']['operation']);

    }

    /**
     * @group testAccount
     */
    public function testCreateNewCreditInAccount()
    {
        $response = $this->createNewCustomerAndAccount();
        $accountData = json_decode($response->response->getContent(), true);

        $payload = [
            'account_number' => $accountData['account_number'],
            'amount' => '100',
        ];

        $response = $this->post("/v1/operation/credit", $payload);

        $this->seeStatusCode(201);

        $newBalance = (string) $this->data['account']['balance'] + (string) $payload['amount'];

        $responseData = json_decode($response->response->getContent(), true);
        $this->assertEquals($newBalance, $responseData['balance']);
        $this->assertEquals($accountData['account_number'], $responseData['account_number']);
        $this->assertArrayHasKey('transaction', $responseData);
        $this->assertArrayHasKey('transaction_id', $responseData['transaction']);
        $this->assertEquals($payload['amount'], $responseData['transaction']['total_amount']);
        $this->assertEquals($payload['amount'], $responseData['transaction']['amount']);
        $this->assertEquals('CREDIT', $responseData['transaction']['operation']);

    }


    /**
     * @group testAccount
     */
    public function testCreateDebitWithDebitMethod()
    {
        $paymentMethod = [
            'method_id' => 'D',
            'name' => 'Cartão de Débito',
            'tax_percentage' => '0.03',
        ];

        $amount = 20;

        $response = $this->createNewCustomerAndAccount();
        $accountData = json_decode($response->response->getContent(), true);

        $this->createPaymentMethod($paymentMethod);

        $response = $this->post("/v1/operation/debit", [
            'account_number' => $accountData['account_number'],
            'payment_method' => $paymentMethod['method_id'],
            'amount' => $amount,
        ]);

        $responseData = json_decode($response->response->getContent(), true);

        $this->seeStatusCode(201);

        $taxAmount = (string) $amount * (string) $paymentMethod['tax_percentage'];
        $totalAmount = (string) $amount + (string) $taxAmount;

        $newBalance = (string) $this->data['account']['balance'] - (string) $totalAmount;

        $this->assertEquals($newBalance, $responseData['balance']);
        $this->assertEquals($taxAmount, $responseData['transaction']['tax_amount']);
        $this->assertEquals($totalAmount, $responseData['transaction']['total_amount']);
        $this->assertEquals($amount, $responseData['transaction']['amount']);
        $this->assertEquals('DEBIT', $responseData['transaction']['operation']);

    }

    /**
     * @group testAccount
     */
    public function testCreateDebitWithCreditMethod()
    {
        $paymentMethod = [
            'method_id' => 'C',
            'name' => 'Cartão de Crédito',
            'tax_percentage' => '0.05',
        ];

        $amount = 20;

        $response = $this->createNewCustomerAndAccount();
        $accountData = json_decode($response->response->getContent(), true);

        $this->createPaymentMethod($paymentMethod);

        $response = $this->post("/v1/operation/debit", [
            'account_number' => $accountData['account_number'],
            'payment_method' => $paymentMethod['method_id'],
            'amount' => $amount,
        ]);

        $responseData = json_decode($response->response->getContent(), true);

        $this->seeStatusCode(201);

        $taxAmount = (string) $amount * (string) $paymentMethod['tax_percentage'];
        $totalAmount = (string) $amount + (string) $taxAmount;

        $newBalance = (string) $this->data['account']['balance'] - (string) $totalAmount;

        $this->assertEquals($newBalance, $responseData['balance']);
        $this->assertEquals($taxAmount, $responseData['transaction']['tax_amount']);
        $this->assertEquals($totalAmount, $responseData['transaction']['total_amount']);
        $this->assertEquals($amount, $responseData['transaction']['amount']);
        $this->assertEquals('DEBIT', $responseData['transaction']['operation']);

    }

    /**
     * @group testAccount
     */
    public function testCreateDebitWithPixxMethod()
    {
        $paymentMethod = [
            'method_id' => 'P',
            'name' => 'Pix',
            'tax_percentage' => '0',
        ];

        $amount = 20;

        $response = $this->createNewCustomerAndAccount();
        $accountData = json_decode($response->response->getContent(), true);

        $this->createPaymentMethod($paymentMethod);

        $response = $this->post("/v1/operation/debit", [
            'account_number' => $accountData['account_number'],
            'payment_method' => $paymentMethod['method_id'],
            'amount' => $amount,
        ]);

        $responseData = json_decode($response->response->getContent(), true);

        $this->seeStatusCode(201);

        $taxAmount = (string) $amount * (string) $paymentMethod['tax_percentage'];
        $totalAmount = (string) $amount + (string) $taxAmount;

        $newBalance = (string) $this->data['account']['balance'] - (string) $totalAmount;

        $this->assertEquals($newBalance, $responseData['balance']);
        $this->assertEquals($taxAmount, $responseData['transaction']['tax_amount']);
        $this->assertEquals($totalAmount, $responseData['transaction']['total_amount']);
        $this->assertEquals($amount, $responseData['transaction']['amount']);
        $this->assertEquals('DEBIT', $responseData['transaction']['operation']);

    }

}
