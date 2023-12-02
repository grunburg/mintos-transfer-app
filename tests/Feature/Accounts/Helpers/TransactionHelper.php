<?php

namespace Tests\Feature\Accounts\Helpers;

use App\Modules\Transaction\Models\Transaction;

class TransactionHelper
{
    public static function create(array $properties = [], array $relations = [], bool $save = true)
    {
        $transaction = Transaction::factory()->make($properties);

        foreach ($relations as $key => $value) {
            $transaction->$key()->associate($value);
        }

        if ($save) {
            $transaction->save();
        }

        return $save ? $transaction : $transaction->refresh();
    }
}