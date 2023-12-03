<?php

namespace App\Modules\Rate\Clients;

use App\Modules\Rate\Contracts\RateClientContract;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class ExchangeRateClient implements RateClientContract
{
    public function client(): PendingRequest
    {
        return Http::baseUrl(Config::get('rates.xrt.url'))
            ->withQueryParameters(['access_key' => Config::get('rates.xrt.key')]);
    }
}