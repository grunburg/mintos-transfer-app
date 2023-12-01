<?php

namespace App\Modules\Transaction\Services;

use App\Modules\Account\Models\Account;
use App\Modules\Transaction\Repositories\TransactionRepository;
use Illuminate\Database\Eloquent\Collection;

readonly class TransactionService
{
    public function __construct(
        private TransactionRepository $repository,
    ) {}

    /**
     * @param Account $account
     * @param int $limit Limit the returned transaction amount.
     * @param int $offset Offset the returned transaction amount, e.g. start with 5th transaction.
     * @return Collection
     */
    public function getAccountTransactions(Account $account, int $limit = 100, int $offset = 0): Collection
    {
        return $this->repository->getByAccountId($account->id, $limit, $offset);
    }
}