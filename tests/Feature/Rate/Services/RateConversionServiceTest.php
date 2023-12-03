<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Feature\Rate\Services;

use App\Modules\Currency\Enums\Currency;
use App\Modules\Rate\Services\RateConversionService;
use Cache;
use Carbon\Carbon;
use Database\Seeders\TestRateDatabaseSeeder;
use Tests\TestCase;

class RateConversionServiceTest extends TestCase
{
    private const DATE = '2023-12-01';

    private const RATES = [
        ['USD', 'GBP', 0.7883, self::DATE, 'XRT'],
        ['GBP', 'USD', 1.2685, self::DATE, 'XRT'],
        ['USD', 'EUR', 0.9193, self::DATE, 'XRT'],
        ['EUR', 'USD', 1.0878, self::DATE, 'XRT'],
    ];

    private RateConversionService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Seeds rates, so we can feature test the repository.
        (new TestRateDatabaseSeeder(self::RATES))->run();

        // Flush the cache due to its usage in the service.
        Cache::flush();

        $this->service = app(RateConversionService::class);
    }

    /**
     * @dataProvider conversionDataProvider
     */
    public function testConvert_withProvider_convertsCurrencyRate(
        float $amount,
        Currency $from,
        Currency $to,
        float $expected
    ): void {
        $result = $this->service->convert($amount, $from, $to, Carbon::parse(self::DATE));

        $this->assertEquals($expected, $result);
    }

    public static function conversionDataProvider(): iterable
    {
        yield 'USD -> GBP' => [10.00, Currency::USD, Currency::GBP, 16.09];
        yield 'USD -> EUR' => [10.00, Currency::USD, Currency::EUR, 11.83];
        yield 'USD -> USD' => [10.00, Currency::USD, Currency::USD, 10.00];
    }
}