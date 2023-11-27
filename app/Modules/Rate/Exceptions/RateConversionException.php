<?php

namespace App\Modules\Rate\Exceptions;

class RateConversionException extends RateException
{
    public const RATE_NOT_FOUND = 'Could not found any rates to retrieve from.';
}