<?php

namespace App\Modules\Account\Repositories;

use App\Modules\Account\Exceptions\AccountRepositoryException;
use App\Modules\Account\Models\Account;

class AccountRepository
{
    /**
     * @throws AccountRepositoryException
     */
    public function save(Account $account): void
    {
        if (!$account->save()) {
            throw new AccountRepositoryException();
        }
    }

    public function getById(int $id): ?Account
    {
        return Account::query()
            ->where(['id' => $id])
            ->first();
    }
}