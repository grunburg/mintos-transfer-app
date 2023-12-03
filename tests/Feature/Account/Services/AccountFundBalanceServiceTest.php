<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Feature\Account\Services;

use App\Modules\Account\Exceptions\Validation\InsufficientFundsException;
use App\Modules\Account\Services\AccountFundBalanceService;
use Tests\Feature\Account\Helpers\AccountHelper;
use Tests\TestCase;

class AccountFundBalanceServiceTest extends TestCase
{
    private AccountFundBalanceService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(AccountFundBalanceService::class);
    }

    public function testAdd_addsBalanceToAccount(): void
    {
        $account = AccountHelper::create(['amount' => 10]);

        $this->service->add($account, 10);
        $account->refresh();

        $this->assertEquals(20, $account->amount);
    }

    public function testRemove_removesBalanceToAccount(): void
    {
        $account = AccountHelper::create(['amount' => 10]);

        $this->service->remove($account, 10);
        $account->refresh();

        $this->assertEquals(0, $account->amount);
    }

    public function testRemove_amountIsGreaterThanAccount_throwsException(): void
    {
        $account = AccountHelper::create(['amount' => 10]);

        $this->expectException(InsufficientFundsException::class);

        $this->service->remove($account, 100);
    }
}