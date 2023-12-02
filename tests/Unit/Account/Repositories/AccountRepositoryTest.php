<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Unit\Account\Repositories;

use App\Modules\Account\Exceptions\AccountRepositoryException;
use App\Modules\Account\Models\Account;
use App\Modules\Account\Repositories\AccountRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Helpers\ModelMockHelper;

class AccountRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private AccountRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new AccountRepository();
    }

    public function testSave_withAccountModel_savesModelToDatabase(): void
    {
        $account = ModelMockHelper::mock(Account::class);
        $account->expects('save')->andReturnFalse();

        $this->expectException(AccountRepositoryException::class);

        $this->repository->save($account);
    }
}