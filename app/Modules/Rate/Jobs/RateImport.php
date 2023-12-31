<?php

namespace App\Modules\Rate\Jobs;

use App\Modules\Rate\Services\Imports\ExchangeRateImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RateImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const IMPORTS = [
        ExchangeRateImportService::class,
    ];

    public function handle(): void
    {
        foreach (self::IMPORTS as $import) {
            app($import)->import();
        }
    }
}
