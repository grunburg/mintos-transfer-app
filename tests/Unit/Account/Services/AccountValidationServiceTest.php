<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Unit\Account\Services;

use App\Modules\Account\Exceptions\Validation\IncompatibleCurrencyException;
use App\Modules\Account\Exceptions\Validation\InsufficientFundsException;
use App\Modules\Account\Exceptions\Validation\InvalidAmountException;
use App\Modules\Account\Models\Account;
use App\Modules\Account\Services\AccountValidationService;
use App\Modules\Account\Structures\AccountTransferParameters;
use App\Modules\Currency\Enums\Currency;
use App\Modules\Rate\Services\RateConversionService;
use Mockery;
use Tests\TestCase;
use Tests\Unit\Helpers\ModelMockHelper;

class AccountValidationServiceTest extends TestCase
{
    private AccountValidationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new AccountValidationService(
            Mockery::mock(RateConversionService::class),
        );
    }

    public function testValidate_withValidData_validationSucceeds(): void
    {
        $from = $this->getAccountMock(10.00, Currency::USD);
        $to = $this->getAccountMock(10.00, Currency::USD);
        $parameters = new AccountTransferParameters($from, $to, 5.00, Currency::USD);

        $this->service->validate($parameters);

        $this->expectNotToPerformAssertions();
    }


    public function testValidate_withUnavailableAmount_throwsException(): void
    {
        $from = $this->getAccountMock(10.00, Currency::USD);
        $to = $this->getAccountMock(10.00, Currency::USD);
        $parameters = new AccountTransferParameters($from, $to, 0.00, Currency::USD);

        $this->expectException(InvalidAmountException::class);

        $this->service->validate($parameters);
    }

    public function testValidate_withIncompatibleCurrency_throwsException(): void
    {
        $from = $this->getAccountMock(10.00, Currency::USD);
        $to = $this->getAccountMock(10.00, Currency::USD);
        $parameters = new AccountTransferParameters($from, $to, 5.00, Currency::EUR);

        $this->expectException(IncompatibleCurrencyException::class);

        $this->service->validate($parameters);
    }

    public function testValidate_withInsufficientFunds_throwsException(): void
    {
        $from = $this->getAccountMock(10.00, Currency::USD);
        $to = $this->getAccountMock(10.00, Currency::USD);
        $parameters = new AccountTransferParameters($from, $to, 15.00, Currency::USD);

        $this->expectException(InsufficientFundsException::class);

        $this->service->validate($parameters);
    }

    private function getAccountMock(float $amount, Currency $currency)
    {
        return ModelMockHelper::mock(Account::class, ['amount' => $amount, 'currency' => $currency]);
    }
}