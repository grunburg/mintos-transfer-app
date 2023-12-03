<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Feature\Rate\Services;

use App\Modules\Currency\Enums\Currency;
use App\Modules\Rate\Exceptions\RateRequestException;
use App\Modules\Rate\Services\ExchangeRateService;
use App\Modules\Rate\Structures\RateResult;
use Carbon\Carbon;
use Tests\Feature\Rate\Helpers\ExchangeRateHttpResponseHelper;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ExchangeRateServiceTest extends TestCase
{
    private const DATE = '2023-12-01';

    private ExchangeRateService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Prevent the call to Exchange Rate service.
        Http::preventStrayRequests();

        $this->service = app(ExchangeRateService::class);
    }

    public function testGetRates_returnsRateStructure(): void
    {
        $this->getFakeHttpRequest(true);
        $result = $this->service->getRates(Carbon::parse('2023-11-30'), Currency::EUR, [Currency::USD, Currency::GBP]);

        $expected = new RateResult(
            Currency::EUR,
            Carbon::parse(self::DATE),
            [
                [Currency::USD, 1.33518],
                [Currency::GBP, 0.84751],
            ],
        );

        $this->assertEquals($expected, $result);
    }

    public function testGetRates_responseNotSuccessful_throwsException(): void
    {
        $this->getFakeHttpRequest(false);

        $this->expectException(RateRequestException::class);

        $this->service->getRates(Carbon::parse('2023-11-30'), Currency::EUR, [Currency::USD, Currency::GBP]);
    }

    private function getFakeHttpRequest(bool $success): void
    {
        Http::fake(['http://api.exchangerate.host/*' => Http::response(
            ExchangeRateHttpResponseHelper::get(
                'EUR',
                self::DATE,
                [
                    'EURUSD' => 1.33518,
                    'EURGBP' => 0.84751,
                ],
                $success,
            ),
        )]);
    }
}