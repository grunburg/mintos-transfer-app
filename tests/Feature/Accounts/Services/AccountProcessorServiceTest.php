<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Feature\Accounts\Services;

use App\Modules\Account\Services\AccountProcessorService;
use App\Modules\Account\Structures\AccountTransferParameters;
use App\Modules\Currency\Enums\Currency;
use App\Modules\Transaction\Enums\TransactionStatus;
use App\Modules\Transaction\Models\Transaction;
use Tests\Feature\Accounts\Helpers\AccountHelper;
use Tests\TestCase;

class AccountProcessorServiceTest extends TestCase
{
    private AccountProcessorService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(AccountProcessorService::class);
    }

    public function testProcess_transfersFunds(): void
    {
        $from = AccountHelper::create(['amount' => 10.00, 'currency' => Currency::USD]);
        $to = AccountHelper::create(['amount' => 10.00, 'currency' => Currency::USD]);

        $parameters = new AccountTransferParameters($from, $to, 5.00, Currency::USD);
        $this->service->process($parameters);

        $from->refresh();
        $to->refresh();

        $this->assertEquals(5, $from->amount);
        $this->assertEquals(15, $to->amount);

        $transaction = Transaction::first();
        $this->assertEquals(TransactionStatus::Success, $transaction->status);
    }
}