<?php

namespace App\Modules\Transaction\Repositories;

use App\Modules\Transaction\Exceptions\TransactionRepositoryException;
use App\Modules\Transaction\Models\Transaction;

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
}