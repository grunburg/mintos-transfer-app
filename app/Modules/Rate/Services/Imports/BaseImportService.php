<?php

namespace App\Modules\Rate\Services\Imports;

use App\Modules\Currency\Enums\Currency;
use App\Modules\Rate\Contracts\RateImportContract;
use App\Modules\Rate\Repositories\RateRepository;
use App\Modules\Rate\Structures\RateResult;
use Throwable;

abstract class BaseImportService implements RateImportContract
{
    public function __construct(
        protected RateRepository $repository,
    ) {}

    protected function save(RateResult $result): void
    {
        /**
         * @var Currency $currency
         * @var float $rate
         */
        foreach ($result->rates as [$currency, $rate]) {
            try {
                $this->repository->save(
                    $result->source,
                    $currency,
                    round($rate, 4),
                    $result->date,
                    static::source()
                );
            } catch (Throwable) {
                continue;
            }
        }
    }
}