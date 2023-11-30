<?php


$router->group(['prefix' => '/v1'], function () use ($router) {


    //services
    $router->post('/payment-method', 'PaymentMethodController@createPaymentMethod');
    $router->post('/operation/new-customer', 'OperationController@createNewCustomerAndAccount');
    $router->post('/operation/{operation}', 'OperationController@operation');
    $router->get('/account/{accountNumber}', 'AccountController@get');
    $router->post('/account/create', 'AccountController@create');

});
