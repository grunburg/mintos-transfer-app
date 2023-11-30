<?php

namespace App\Modules\Rate\Exceptions;

class UnavailableRatesException extends RateException
{
    protected $message = 'Could not found any rates to retrieve from.';
}