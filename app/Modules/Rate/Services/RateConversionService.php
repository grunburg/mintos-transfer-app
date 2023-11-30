<?php

namespace App\Modules\Rate\Services;

use App\Modules\Currency\Enums\Currency;
use App\Modules\Rate\Exceptions\RateConversionException;
use App\Modules\Rate\Exceptions\UnavailableRatesException;
use App\Modules\Rate\Repositories\RateRepository;
use Carbon\Carbon;

readonly class RateConversionService
{
    private const PRECISION = 2;

    public function __construct(
        private RateRepository $repository,
        private RateCacheService $cache,
    ) {}

    /**
     * @throws UnavailableRatesException
     */
    public function convert(float $amount, Currency $from, Currency $to, Carbon $date = null): float
    {
        $date = $date ?: now();
        $rate = $this->getCachedRate($from, $to, $date);

        return round($amount * $rate , self::PRECISION);
    }

    /**
     * @throws UnavailableRatesException
     */
    private function getCachedRate(Currency $from, Currency $to, Carbon $date): float
    {
        if ($rate = $this->cache->get($from, $to, $date)) {
            return $rate;
        }

        if ($rate = $this->getCalculatedRate($from, $to, $date)) {
            $this->cache->set($rate, $from, $to, $date);
        }

        return $rate;
    }

    /**
     * @throws UnavailableRatesException
     */
    private function getCalculatedRate(Currency $from, Currency $to, Carbon $date): float
    {
        if ($from === $to) {
            return 1;
        }

        $now = now();
        if ($date->gte($now)) {
            return $this->getCalculatedRate($from, $to, $now);
        }

        $rateFrom = $this->getRate($from, $to, $date);
        $rateTo = $this->getRate($to, $from, $date);

        return round($rateTo / $rateFrom, 9);
    }

    /**
     * @throws UnavailableRatesException
     */
    private function getRate(Currency $base, Currency $target, Carbon $date): float
    {
        if ($rate = $this->repository->getRate($base, $target, $date)) {
            return $rate->rate;
        }

        if ($rate = $this->repository->getRate($base, $target, null)) {
            return $rate->rate;
        }

        throw new UnavailableRatesException();
    }
}