<?php

namespace App\Modules\Rate\Services;

use App\Modules\Currency\Enums\Currency;
use App\Modules\Rate\Clients\ExchangeRateClient;
use App\Modules\Rate\Exceptions\RateRequestException;
use App\Modules\Rate\Factories\ExchangeRateResultFactory;
use App\Modules\Rate\Structures\RateResult;
use Carbon\Carbon;
use Illuminate\Support\Arr;

readonly class ExchangeRateService
{
    /**
     * @param Carbon $date
     * @param Currency $source
     * @param Currency[] $currencies
     * @return RateResult
     * @throws RateRequestException
     */
    public function getRates(Carbon $date, Currency $source, array $currencies): RateResult
    {
        $currencies = Arr::map($currencies, fn(Currency $currency) => $currency->value);

        $request = (new ExchangeRateClient())->withQueryParameters([
            'date' => $date->format('Y-m-d'),
            'currencies' => Arr::join($currencies, ','),
            'source' => $source->value,
        ]);

        $response = $request->get('/historical');

        $body = (object) json_decode($response->body());
        if ($response->ok() && !$body?->success ?? false) {
            throw new RateRequestException($body?->error?->info ?? 'Failed to retrieve exchange rates.');
        }

        return ExchangeRateResultFactory::create($body);
    }
}