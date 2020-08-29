<?php

namespace App\Logic;

// use Illuminate\Support\Facades\Config;
use App\Transaction;
use App\Logic\BaxiGateway;

class TransactionHandler {

    // return the transaction id if successful
    function newTransaction(array $data): ?string {
        $trans_id = uniqid();
        $record = Transaction::create([
            "trans_id" => $trans_id,
            "total_amount" => $data["total_amount"] ?? $data["amount"], // some endpointg use amount, others use total_amount. we are accounting for both to avoid throwing unneccesary errors
            "product_code" => $data["product_code"] ?? null,
            "user_id" => 1, // we are using a default user id here. should be replaced with a real user id in production
            "service_type" => $data["service_type"]
        ]);
        $record->save();
        return $trans_id;
    }

    function getTransaction(string $id) {

    }

    function updateTransaction(String $id): ?Transaction {
        $gateway = new BaxiGateway;
        $request = $gateway->verifyTransaction($id);
        $response = json_decode($request);
        if ($response->status == "success") {
            $transaction = Transaction::where("trans_id", $id)->first();
            $transaction->status = "success";
            $transaction->save();
            return $transaction;
        } else {
            $transaction = Transaction::where("trans_id", $id)->first();
            $transaction->status = $response->status;
            $transaction->save();
            return $transaction;
        }
    }
}
?>
