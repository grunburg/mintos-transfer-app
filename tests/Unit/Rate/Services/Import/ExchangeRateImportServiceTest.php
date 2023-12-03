<?php

namespace Tests\Unit\Rate\Services\Import;

use App\Modules\Rate\Exceptions\RateRequestException;
use App\Modules\Rate\Repositories\RateRepository;
use App\Modules\Rate\Services\ExchangeRateService;
use App\Modules\Rate\Services\Imports\ExchangeRateImportService;
use Illuminate\Support\Facades\Log;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

class ExchangeRateImportServiceTest extends TestCase
{
    private Mock | ExchangeRateService $mockExchangeRateService;

    private ExchangeRateImportService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ExchangeRateImportService(
            Mockery::mock(RateRepository::class),
            $this->mockExchangeRateService = Mockery::mock(ExchangeRateService::class),
        );
    }

    public function testImport_failsToGetRates_throwsException(): void
    {
        $this->mockExchangeRateService
            ->expects('getRates')
            ->withAnyArgs()
            ->once()
            ->andThrow(RateRequestException::class);

        Log::expects('error')->withAnyArgs();

        $this->service->import();

        $this->expectNotToPerformAssertions();
    }
}