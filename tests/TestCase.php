<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function createPaymentMethod($newData = [])
    {
        $data = [
            'method_id' => 'D',
            'name' => 'Cartão de Débito',
            'tax_percentage' => '0.03',
        ];

        $configData = array_merge($data, $newData);

        return json_decode(self::post('/v1/payment-method', $configData)->response->getContent());
    }
}
