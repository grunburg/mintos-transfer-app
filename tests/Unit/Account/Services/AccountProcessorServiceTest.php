<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Unit\Account\Services;

use App\Modules\Account\Exceptions\Validation\InvalidAmountException;
use App\Modules\Account\Jobs\AccountTransfer;
use App\Modules\Account\Models\Account;
use App\Modules\Account\Services\AccountFundTransferService;
use App\Modules\Account\Services\AccountProcessorService;
use App\Modules\Account\Services\AccountValidationService;
use App\Modules\Account\Structures\AccountTransferParameters;
use App\Modules\Currency\Enums\Currency;
use App\Modules\Transaction\Services\TransactionProcessorService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Mockery\Mock;
use Tests\TestCase;
use Tests\Unit\Helpers\ModelMockHelper;

class AccountProcessorServiceTest extends TestCase
{
    private Mock | AccountValidationService $mockValidationService;

    private AccountProcessorService $service;

    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();

        $this->service = new AccountProcessorService(
            $this->mockValidationService = Mockery::mock(AccountValidationService::class),
            Mockery::mock(AccountFundTransferService::class),
            Mockery::mock(TransactionProcessorService::class),
        );
    }

    public function testExecute_withValidParameters_queuesJob(): void
    {
        $account = ModelMockHelper::mock(Account::class);
        $parameters = new AccountTransferParameters($account, $account, 1.00, Currency::USD);

        $this->mockValidationService->expects('validate');

        $this->service->execute($parameters);

        Queue::assertPushed(AccountTransfer::class);
    }

    public function testExecute_withInvalidParameters_throwsException(): void
    {
        $account = ModelMockHelper::mock(Account::class);
        $parameters = new AccountTransferParameters($account, $account, 1.00, Currency::USD);

        $this->mockValidationService
            ->expects('validate')
            ->andThrow(InvalidAmountException::class);

        $this->expectException(InvalidAmountException::class);

        $this->service->execute($parameters);
    }

    public function testExecute_withUnknownException_logErrorMessage(): void
    {
        $account = ModelMockHelper::mock(Account::class);
        $parameters = new AccountTransferParameters($account, $account, 1.00, Currency::USD);

        $exception = new Exception('Test');

        $this->mockValidationService
            ->expects('validate')
            ->andThrow($exception);

        Log::expects('error')->with($exception->getMessage());

        $this->service->execute($parameters);
    }
}