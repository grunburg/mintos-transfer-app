<?php

namespace App\Console\Commands;

use App\Modules\Rate\Services\Imports\ExchangeRateImportService;
use Illuminate\Console\Command;

class ImportRates extends Command
{
    protected $signature = 'rate:import';

    protected $description = 'Imports currency rates from an external source.';

    public function handle(): void
    {
        app(ExchangeRateImportService::class)->import();
    }
}
