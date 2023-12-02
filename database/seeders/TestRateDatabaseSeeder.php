<?php

namespace Database\Seeders;

use App\Modules\Rate\Models\Rate;
use Illuminate\Database\Seeder;

class TestRateDatabaseSeeder extends Seeder
{
    public function __construct(
        private readonly array $rates = [],
    ) {}

    public function run(): void
    {
        foreach ($this->rates as [$from, $to, $rate, $date, $source]) {
            Rate::factory()->create([
                'from' => $from,
                'to' => $to,
                'rate' => $rate,
                'date' => $date,
                'source' => $source,
            ]);
        }
    }
}
