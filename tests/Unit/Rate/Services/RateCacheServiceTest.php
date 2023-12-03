<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Unit\Rate\Services;

use App\Modules\Currency\Enums\Currency;
use App\Modules\Rate\Services\RateCacheService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\TestCase;

class RateCacheServiceTest extends TestCase
{
    private const CACHE_TTL = 24 * 60 * 60; // 24H

    private RateCacheService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new RateCacheService();
    }

    /**
     * @dataProvider setCacheDataProvider
     */
    public function testSet_setsCacheWithValue(float $rate, array $parameters, string $key): void
    {
        Cache::shouldReceive('set')->with($key, $rate, self::CACHE_TTL);
        $this->service->set($rate, ...$parameters);

        $this->expectNotToPerformAssertions();
    }

    public static function setCacheDataProvider(): iterable
    {
        yield [0.4509, [Currency::USD, Currency::EUR, new Carbon('2023-11-29')], 'RATE-USD-EUR-2023-11-29'];
        yield [0.0501, [Currency::EUR, Currency::CHF, new Carbon('2023-12-28')], 'RATE-EUR-CHF-2023-12-28'];
    }

    /**
     * @dataProvider getCacheDataProvider
     */
    public function testGet_getCacheValue(array $parameters, string $key): void
    {
        Cache::shouldReceive('get')->with($key);

        $this->service->get(...$parameters);

        $this->expectNotToPerformAssertions();
    }

    public static function getCacheDataProvider(): iterable
    {
        yield [[Currency::USD, Currency::EUR, new Carbon('2023-11-29')], 'RATE-USD-EUR-2023-11-29'];
        yield [[Currency::EUR, Currency::CHF, new Carbon('2023-12-28')], 'RATE-EUR-CHF-2023-12-28'];
    }
}