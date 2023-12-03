<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Feature\Account\Services;

use App\Modules\Account\Services\AccountFundTransferService;
use App\Modules\Currency\Enums\Currency;
use Database\Seeders\TestRateDatabaseSeeder;
use Illuminate\Support\Carbon;
use Tests\Feature\Account\Helpers\AccountHelper;
use Tests\Feature\Account\Helpers\TransactionHelper;
use Tests\TestCase;

class AccountFundTransferServiceTest extends TestCase
{
    private const DATE = '2023-12-01';

    private const RATES = [
        ['USD', 'GBP', 0.7875, self::DATE, 'XRT'],
        ['GBP', 'USD', 1.2699, self::DATE, 'XRT'],
    ];

    private AccountFundTransferService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Seeds USD rates, so we can feature test the service.
        (new TestRateDatabaseSeeder(self::RATES))->run();

        Carbon::setTestNow(Carbon::create(self::DATE));

        $this->service = app(AccountFundTransferService::class);
    }

    /**
     * @dataProvider transferProvider
     */
    public function testTransfer_withProvider_transfersFundsBetweenAccounts(
        Currency $fromCurrency,
        Currency $toCurrency,
        float $expectedFromAmount,
        float $expectedToAmount,
    ): void {
        $from = AccountHelper::create(['amount' => 10, 'currency' => $fromCurrency]);
        $to = AccountHelper::create(['amount' => 15,'currency' => $toCurrency]);

        $transaction = TransactionHelper::create(
            ['amount' => 5, 'currency' => $toCurrency],
            ['from' => $from, 'to' => $to],
        );

        $this->service->transfer($transaction);
        $transaction->refresh();

        $this->assertEquals($expectedFromAmount, $transaction->from->amount);
        $this->assertEquals($expectedToAmount, $transaction->to->amount);
    }

    public static function transferProvider(): iterable
    {
        yield 'USD => USD' => [Currency::USD, Currency::GBP, 1.94, 23.06];
        yield 'USD => GBP' => [Currency::USD, Currency::USD, 5.00, 20.00];
    }
}