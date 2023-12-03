<?php

namespace App\Modules\Rate\Contracts;

use Illuminate\Http\Client\PendingRequest;

interface RateClientContract
{
    public function client(): PendingRequest;
}