<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Feature\Rate\Factories;

use App\Modules\Currency\Enums\Currency;
use App\Modules\Rate\Factories\ExchangeRateResultFactory;
use App\Modules\Rate\Structures\RateResult;
use Carbon\Carbon;
use Tests\Feature\Rate\Helpers\ExchangeRateHttpResponseHelper;
use Tests\TestCase;

class ExchangeRateResultFactoryTest extends TestCase
{
    private const DATE = '2023-11-25';

    private ExchangeRateResultFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = app(ExchangeRateResultFactory::class);
    }

    public function testCreate_createsStructure(): void
    {
        $response = ExchangeRateHttpResponseHelper::get(
            'EUR',
            self::DATE,
            [
                'EURUSD' => 1.335185,
                'EURGBP' => 0.84751,
            ],
        );

        $structure = $this->factory->create((object) $response);
        $expected = new RateResult(
            Currency::EUR,
            Carbon::parse(self::DATE),
            [[Currency::USD, 1.335185], [Currency::GBP, 0.84751]]
        );

        $this->assertEquals($expected, $structure);
    }
}