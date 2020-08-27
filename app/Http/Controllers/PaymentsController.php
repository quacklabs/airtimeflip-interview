<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;

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
            "addon_code" => "required|alpha_num",
            "product_monthsPaidFor" => "required|numeric",
            "service_type" => "required|alpha",
            "agentId" => "required|numeric",
            // "reference" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        } else {
            $params = $request->all();
            

            // return response()->json($config['base_url']);
            // Curl::to($config['base_url'])->withData($params)->post(); // need to set headers
        }
    }

    public function payElectricity(Request $request) {
        $validator = Validator::make($request->all(), [ 
            "account_number" => "required|numeric",
            "amount" => "required|numeric",
            "phone" => "required|string",
            "service_type" => "required|string",
            // "enugu_electric_prepaid",
            // "agentId" => "30"
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        } else {
            $params = $request->all();
            $params["agentId"] = 207;
            $params["agentReference"] = uniqid();
            return response()->json($params);
        }
    }
}
