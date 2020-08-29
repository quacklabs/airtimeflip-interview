<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Logic\TransactionHandler;
use App\Logic\BaxiGateway;

/*
    On a normal implemetation, the methods in the class may need authentication
    since this is just a proof of concept, we'll be leaving it open.
    a more secure implementation would have laravel passport (already enabled in the project)

*/

class PaymentsController extends Controller
{
    //

    public function payCableTv(Request $request) {
        $validator = Validator::make($request->all(), [
            "total_amount" => "required|numeric",
            "smartcard_number" => "required|numeric",
            "product_code" => "required|alpha_num",
            "addon_code" => "alpha_num|nullable",
            "product_monthsPaidFor" => "required|numeric",
            "service_type" => "required|alpha",
            "agentId" => "required|numeric",
            // "reference" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $params = $request->all();
            $handler = new TransactionHandler;
            $status = $handler->newTransaction($params);

            if($status == null || $status == "") {
                return response()->json(["status" => "failed","error" => "Unable to process transaction"], 500);
            } else {
                $baxiHandler = new BaxiGateway;
                $params["agentReference"] = $status;
                $baxiRequest = $baxiHandler->payCableTV($params);
                $response = json_decode($baxiRequest);

                if($response->status == "success") {
                    $handler->updateTransaction($params["agentReference"]);
                    return response()->json([$response->data->transactionMessage]);
                } else {
                    $handler->updateTransaction($params["agentReference"]);
                    return response()->json(["status" => "failed","error" => "{$response->message}"], 500);
                }
            }
        }
    }

    public function payElectricity(Request $request) {
        $validator = Validator::make($request->all(), [
            "account_number" => "required|numeric",
            "amount" => "required|numeric",
            "phone" => "required|string",
            "service_type" => "required|string", // e.g enugu_electric_prepaid
            "agentId" => "required|alpha_num",
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {

            $params = $request->all();
            $handler = new TransactionHandler;
            $status = $handler->newTransaction($params);

            if($status == null || $status == "") {
                return response()->json(["status" => "failed","error" => "Unable to process transaction"], 500);
            } else {
                // unset($params["agentId"]); // not doing this causes an error
                $params["agentReference"] = $status;
                $baxiHandler = new BaxiGateway;
                $baxiRequest = $baxiHandler->payElectricity($params);
                $response = json_decode($baxiRequest);
                if($response->status == "success") {
                    $handler->updateTransaction($params["agentReference"]);
                    return response()->json([$response->data->transactionMessage]);
                } else {
                    $handler->updateTransaction($params["agentReference"]);
                    return response()->json(["status" => "failed","error" => var_dump($response)], 500);
                }
            }
        }
    }
}
