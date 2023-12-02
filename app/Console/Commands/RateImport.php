<?php

namespace App\Console\Commands;

use App\Modules\Rate\Services\Imports\ExchangeRateImportService;
use Illuminate\Console\Command;

class RateImport extends Command
{
    private const IMPORTS = [
        ExchangeRateImportService::class,
    ];

    protected $signature = 'rate:import';

    protected $description = 'Imports currency rates from an external source.';

    public function handle(): void
    {
        foreach (self::IMPORTS as $import) {
            app($import)->import();
        }
    }
}
