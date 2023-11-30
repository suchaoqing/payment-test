<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use App\Services\OperationService;
use App\Services\PaymentMethodService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class OperationController extends Controller
{
    public function createNewCustomerAndAccount(Request $request)
    {
        $this->validate($request, [
            "customer" => "required|array",
            "customer.name" => "required|string",
            "customer.vat_number" => "required|numeric",
            "account" => "required|array",
            "account.balance" => "required|numeric",
        ]);

        try {
            $new = OperationService::createNewCustomerAndAccount($request->input());

            return new JsonResponse($new , Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['Error' => $e->getMessage()], $e->getCode());
        }
    }

    public function operation(Request $request, $operation)
    {
        $allowedPaymentMethod = PaymentMethodService::getAllowedPaymentMethod();

        if(strtoupper($operation) == OperationService::DEBIT) {
            $this->validate($request, [
                "account_number" => "required|numeric",
                "payment_method" => "required|in:".implode(',', array_keys($allowedPaymentMethod)),
                "amount" => "required|numeric",
            ], [
                "payment_method.in" => "Allowed payment method are: ".implode(',', $allowedPaymentMethod),
            ]);
        } else {
            $this->validate($request, [
                "account_number" => "required|numeric",
                "amount" => "required|numeric",
            ]);
        }

        try {
            $response = OperationService::$operation($request);

            return new JsonResponse($response, Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return new JsonResponse(['Error' => $e->getMessage()], $e->getCode());
        }
    }

    public function getAll(ServicesService $servicesService): array
    {
        return $servicesService->getAll();
    }
}
