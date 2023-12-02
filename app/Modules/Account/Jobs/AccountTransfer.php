<?php

namespace App\Modules\Account\Jobs;

use App\Modules\Account\Services\AccountProcessorService;
use App\Modules\Account\Structures\AccountTransferParameters;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Throwable;

class AccountTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly AccountTransferParameters $parameters,
    ) {}

    public function handle(): void
    {
        try {
            app(AccountProcessorService::class)->process($this->parameters);
        } catch (Throwable $t) {
            Log::error($t);
        }
    }
}
