<?php

namespace App\Modules\Account\Services;

use App\Modules\Account\Exceptions\AccountException;
use App\Modules\Account\Exceptions\Validation\InsufficientFundsException;
use App\Modules\Account\Models\Account;
use App\Modules\Account\Repositories\AccountRepository;

class AccountFundBalanceService
{
    public function __construct(
        readonly private AccountRepository $repository,
    ) {}

    /**
     * @throws AccountException
     */
    public function add(Account $account, float $amount): void
    {
        $account->amount = $account->amount + $amount;
        $this->repository->save($account);
    }

    /**
     * @throws AccountException
     */
    public function remove(Account $account, float $amount): void
    {
        if ($amount > $account->amount) {
            throw new InsufficientFundsException();
        }

        $account->amount = $account->amount - $amount;
        $this->repository->save($account);
    }
}