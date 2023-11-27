<?php

namespace App\Modules\Rate\Clients;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Config;

class ExchangeRateClient extends PendingRequest
{
    public function __construct(Factory $factory = null, $middleware = [])
    {
        parent::__construct($factory, $middleware);

        $this->baseUrl(Config::get('rates.xrt.url'));
        $this->withQueryParameters(['access_key' => Config::get('rates.xrt.key')]);
    }
}