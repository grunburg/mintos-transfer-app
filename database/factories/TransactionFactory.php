<?php

namespace Database\Factories;

use App\Modules\Transaction\Enums\TransactionStatus;
use App\Modules\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'status' => TransactionStatus::Pending,
        ];
    }
}
