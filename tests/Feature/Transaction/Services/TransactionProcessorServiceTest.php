<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Feature\Transaction\Services;

use App\Modules\Currency\Enums\Currency;
use App\Modules\Transaction\Enums\TransactionStatus;
use App\Modules\Transaction\Exceptions\TransactionProcessorException;
use App\Modules\Transaction\Services\TransactionProcessorService;
use Exception;
use Tests\Feature\Account\Helpers\AccountHelper;
use Tests\Feature\Account\Helpers\TransactionHelper;
use Tests\TestCase;

class TransactionProcessorServiceTest extends TestCase
{
    private TransactionProcessorService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(TransactionProcessorService::class);
    }

    public function testProcess_transactionIsNotPending_throwsException(): void
    {
        $from = AccountHelper::create(['amount' => 10.00, 'currency' => Currency::USD]);
        $to = AccountHelper::create(['amount' => 10.00, 'currency' => Currency::USD]);

        $transaction = TransactionHelper::create(
            ['amount' => 10.00, 'currency' => Currency::EUR, 'status' => TransactionStatus::Failed],
            ['from' => $from, 'to' => $to],
        );

        $this->expectException(TransactionProcessorException::class);

        $this->service->process($transaction, function () {});

        $this->assertModelMissing($transaction);
    }

    public function testProcess_callbackException_handlesException(): void
    {
        $from = AccountHelper::create(['amount' => 10.00, 'currency' => Currency::USD]);
        $to = AccountHelper::create(['amount' => 10.00, 'currency' => Currency::USD]);

        $transaction = TransactionHelper::create(
            ['amount' => 10.00, 'currency' => Currency::EUR, 'status' => TransactionStatus::Pending],
            ['from' => $from, 'to' => $to],
        );

        $this->expectException(TransactionProcessorException::class);

        $this->service->process($transaction, function () {
            throw new Exception('Test');
        });

        $transaction->refresh();

        $this->assertModelExists($transaction);
        $this->assertEquals(TransactionStatus::Failed, $transaction->status);
    }

    public function testProcess_successfulTransaction(): void
    {
        $from = AccountHelper::create(['amount' => 10.00, 'currency' => Currency::USD]);
        $to = AccountHelper::create(['amount' => 10.00, 'currency' => Currency::USD]);

        $transaction = TransactionHelper::create(
            ['amount' => 10.00, 'currency' => Currency::EUR, 'status' => TransactionStatus::Pending],
            ['from' => $from, 'to' => $to],
        );

        $this->service->process($transaction, function () {});

        $transaction->refresh();

        $this->assertModelExists($transaction);
        $this->assertEquals(TransactionStatus::Success, $transaction->status);
    }
}