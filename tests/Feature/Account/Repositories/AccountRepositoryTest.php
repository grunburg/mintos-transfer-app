<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Feature\Account\Repositories;

use App\Modules\Account\Repositories\AccountRepository;
use Tests\Feature\Account\Helpers\AccountHelper;
use Tests\TestCase;

class AccountRepositoryTest extends TestCase
{
    private AccountRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(AccountRepository::class);
    }

    public function testSave_withAccountModel_savesModelToDatabase(): void
    {
        $account = AccountHelper::create(save: false);
        $this->repository->save($account);

        $this->assertModelExists($account);
    }

    public function testGetById_validAccountId_returnsModel(): void
    {
        AccountHelper::create(['id' => 91273]);
        $result = $this->repository->getById(91273);

        $this->assertNotNull($result);
    }

    public function testGetById_invalidAccountId_returnsNull(): void
    {
        AccountHelper::create(['id' => 90289]);
        $result = $this->repository->getById(20189);

        $this->assertNull($result);
    }
}