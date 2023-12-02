<?php

namespace Database\Factories;

use App\Modules\Account\Models\Account;
use App\Modules\Currency\Enums\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'currency' => Currency::USD,
        ];
    }
}
