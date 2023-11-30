<?php

namespace Database\Seeders;

use App\Modules\Account\Models\Account;
use App\Modules\Currency\Enums\Currency;
use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(100)->create()->each(function (User $user) {
            collect(Currency::CURRENCIES)->random(rand(1, 5))->each(function (Currency $currency) use ($user) {
                Account::factory()->for($user)->create([
                    'currency' => $currency,
                ]);
            });
        });
    }
}
