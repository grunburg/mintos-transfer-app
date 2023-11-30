<?php

namespace App\Modules\Transaction\Factories;

use App\Modules\Account\Structures\TransferParameters;
use App\Modules\Transaction\Enums\TransactionStatus;
use App\Modules\Transaction\Models\Transaction;

readonly class TransactionFactory
{
    public static function create(TransferParameters $parameters)
    {
        $transaction = Transaction::make([
            'amount' => $parameters->amount,
            'currency' => $parameters->currency,
            'status' => TransactionStatus::Pending,
        ]);

        $transaction->to()->associate($parameters->to);
        $transaction->from()->associate($parameters->from);

        return $transaction;
    }
}