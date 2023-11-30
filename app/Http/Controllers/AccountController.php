<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            "vat_number" => "required|numeric",
            "balance" => "required|numeric",
        ]);

        try {
            $customer = CustomerService::getByVatNumber($request->get('vat_number'));

            $account = AccountService::create($request->input(), $customer);

            return new JsonResponse($account , Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['Error' => $e->getMessage()], $e->getCode());
        }
    }

    public function get($accountNumber)
    {
        try {

            $account = AccountService::getAccountByNumber($accountNumber);

            return new JsonResponse($account , Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['Error' => $e->getMessage()], $e->getCode());
        }
    }

}
