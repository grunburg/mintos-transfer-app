<?php

namespace Tests\Feature\Rate\Helpers;

use Carbon\Carbon;

class ExchangeRateHttpResponseHelper
{
    public static function get(string $source, string $date, array $quotes = [], bool $success = true): array
    {
        return [
            'success' => $success,
            'terms' => 'https://currencylayer.com/terms"',
            'privacy' => 'https://currencylayer.com/privacy"',
            'historical' => true,
            'date' => $date,
            'timestamp' => Carbon::parse($date)->timestamp,
            'source' => $source,
            'quotes' => $quotes,
        ];
    }
}