<?php

namespace App\Modules\Rate\Factories;

use App\Modules\Currency\Enums\Currency;
use App\Modules\Rate\Contracts\RateResultFactoryContract;
use App\Modules\Rate\Structures\RateResult;
use Carbon\Carbon;
use Illuminate\Support\Arr;

readonly class ExchangeRateResultFactory implements RateResultFactoryContract
{
    public static function create(object $data): RateResult
    {
        $rates = Arr::map((array) $data->quotes, function (float $value, string $key) use ($data) {
            $currency = substr($key, 3);
            return [Currency::from($currency), $value];
        });

        return new RateResult(
            Currency::from($data->source),
            Carbon::parse($data->date),
            array_values($rates),
        );
    }
}