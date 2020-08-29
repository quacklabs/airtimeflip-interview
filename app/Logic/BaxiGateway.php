<?php

namespace App\Logic;

use Illuminate\Support\Facades\Config;
use Ixudra\Curl\Facades\Curl;

class BaxiGateway {
    protected $headers;
    protected $base_url;

    function __construct() {
        $this->headers = [
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "x-api-key" => Config::get("baxi_params.api_key"),
            "Baxi-date" => date("c", time())
        ];
        $this->base_url = Config::get("baxi_params.base_url");
    }
    function payCableTV(array $params): string {
        $request = Curl::to($this->base_url . "services/multichoice/request")
            ->withHeaders($this->headers)
            ->withData(json_encode($params))
            ->post();

        return $request;
    }

    function payElectricity(array $params): ?string {
        $request = Curl::to($this->base_url . "services/electricity/request")
            ->withHeaders($this->headers)
            ->withData(json_encode($params))
            ->post();
        return $request;
    }

    function verifyTransaction(string $reference): ?string {
        $request = Curl::to($this->base_url . "superagent/transaction/query?agentReference=".$reference)
            ->withHeaders($this->headers)
            ->get();

        return $request;
    }
}

?>
