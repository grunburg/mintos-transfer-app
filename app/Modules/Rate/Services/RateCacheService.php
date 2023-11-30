<?php

namespace App\Modules\Rate\Services;

use App\Modules\Currency\Enums\Currency;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

readonly class RateCacheService
{
    private const CACHE_TTL = 24 * 60 * 60; // 24H
    private const KEY_PREFIX = 'RATE';

    public function set(float $rate, Currency $from, Currency $to, Carbon $date): void
    {
        Cache::set($this->key($from, $to, $date), $rate, self::CACHE_TTL);
    }

    public function get(Currency $from, Currency $to, Carbon $date): ?float
    {
        return Cache::get($this->key($from, $to, $date));
    }

    private function key(Currency $from, Currency $to, Carbon $date): string
    {
        return implode('-', [self::KEY_PREFIX, $from->value, $to->value, $date->format('Y-m-d')]);
    }
}