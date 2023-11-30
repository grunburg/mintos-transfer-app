<?php

namespace App\Modules\Rate\Repositories;

use App\Modules\Currency\Enums\Currency;
use App\Modules\Rate\Exceptions\RateRepositoryException;
use App\Modules\Rate\Models\Rate;
use Carbon\Carbon;

readonly class RateRepository
{
    /**
     * @throws RateRepositoryException
     */
    public function save(Currency $from, Currency $to, float $rate, Carbon $date, string $source): void
    {
        $rate = Rate::make([
            'from' => $from->value,
            'to' => $to->value,
            'rate' => $rate,
            'date' => $date->format('Y-m-d'),
            'source' => $source,
        ]);

        if (!$rate->save()) {
            throw new RateRepositoryException();
        }
    }

    public function getRate(Currency $from, Currency $to, ?Carbon $date): ?Rate
    {
        $rate = Rate::where([
            'from' => $from->value,
            'to' => $to->value,
        ])->orderByDesc('date');

        if ($date) {
            $rate->where(['date' => $date->format('Y-m-d')]);
        }

        return $rate->first();
    }
}