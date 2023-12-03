<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Feature\Rate\Repositories;

use App\Modules\Currency\Enums\Currency;
use App\Modules\Rate\Repositories\RateRepository;
use Carbon\Carbon;
use Database\Seeders\TestRateDatabaseSeeder;
use Tests\Feature\Rate\Helpers\RateHelper;
use Tests\TestCase;

class RateRepositoryTest extends TestCase
{
    private const GET_RATE_DATE = '2023-12-01';

    private const RATES = [
        ['USD', 'GBP', 0.7875, self::GET_RATE_DATE, 'XRT'],
    ];

    private RateRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        // Seeds rates, so we can feature test the repository.
        (new TestRateDatabaseSeeder(self::RATES))->run();

        $this->repository = app(RateRepository::class);
    }

    public function testSave_savesNewRate(): void
    {
        $from = Currency::EUR;
        $to = Currency::USD;
        $rate = 0.9876;
        $date = Carbon::parse('2023-11-27');
        $source = 'XYZ';

        $model = RateHelper::create([
            'from' => $from,
            'to' => $to,
            'rate' => $rate,
            'date' => $date->format('Y-m-d'),
            'source' => $source,
        ], false);

        $this->repository->save($from, $to, $rate, $date, $source);

        $this->assertDatabaseHas('rates', [
            'from' => $model->from,
            'to' => $model->to,
            'date' => $model->date,
        ]);
    }

    public function testGetRate_withExistingRate_returnsRate(): void
    {
        $rate = $this->repository->getRate(Currency::USD, Currency::GBP, Carbon::parse(self::GET_RATE_DATE));

        $this->assertNotNull($rate);
    }

    public function testGetRate_withNullDate_returnsFirstRate(): void
    {
        $rate = $this->repository->getRate(Currency::USD, Currency::GBP, null);

        $this->assertEquals(self::GET_RATE_DATE, $rate->date->toDateString());
    }

    public function testGetRate_withNotExistingRate_returnsNull(): void
    {
        $rate = $this->repository->getRate(Currency::USD, Currency::USD, Carbon::parse(self::GET_RATE_DATE));

        $this->assertNull($rate);
    }
}