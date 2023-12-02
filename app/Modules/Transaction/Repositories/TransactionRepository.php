<?php

namespace App\Modules\Transaction\Repositories;

use App\Modules\Transaction\Exceptions\TransactionRepositoryException;
use App\Modules\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository
{
    /**
     * @throws TransactionRepositoryException
     */
    public function save(Transaction $transaction): void
    {
        if (!$transaction->save()) {
            throw new TransactionRepositoryException();
        }
    }

    public function refresh(Transaction $transaction): Transaction
    {
        return $transaction->refresh();
    }

    public function getByAccountId(int $id, int $limit, int $offset): Collection
    {
        return Transaction::query()
            ->where(['from_account_id' => $id])
            ->orWhere(['to_account_id' => $id])
            ->offset($offset)
            ->limit($limit)
            ->get();
    }
}