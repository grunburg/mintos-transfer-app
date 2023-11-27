<?php

namespace App\Modules\Rate\Structures;

use App\Modules\Currency\Enums\Currency;
use Carbon\Carbon;

readonly class RateResult
{
    public function __construct(
        public Currency $source,
        public Carbon $date,
        public array $rates,
    ) {}
}