<?php

namespace App\Http\Controllers;

use App\Services\PaymentMethodService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PaymentMethodController extends Controller
{
    public function createPaymentMethod(Request $request)
    {
        $this->validate($request, [
            "method_id" => "required|string",
            "name" => "required|string",
            "tax_percentage" => "required|numeric|between:0,1.0",
        ]);



        try {
            $paymentMethod = PaymentMethodService::createMethod($request->input());

            return new JsonResponse($paymentMethod , Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['Error' => $e->getMessage()], $e->getCode());
        }
    }

    public function get(Request $request, ServicesService $servicesService)
    {
        return $servicesService->get($request->input('id', null));
    }

    public function getAll(ServicesService $servicesService): array
    {
        return $servicesService->getAll();
    }
}
