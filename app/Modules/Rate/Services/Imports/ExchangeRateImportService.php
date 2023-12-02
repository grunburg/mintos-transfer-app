<?php

namespace App\Modules\Rate\Services\Imports;

use App\Modules\Currency\Enums\Currency;
use App\Modules\Rate\Contracts\RateImportContract;
use App\Modules\Rate\Repositories\RateRepository;
use App\Modules\Rate\Services\ExchangeRateService;
use Log;
use Throwable;

readonly class ExchangeRateImportService extends BaseImportService implements RateImportContract
{
    /** @var Currency[]  */
    private const IMPORTABLE_CURRENCIES = [
        ...Currency::CURRENCIES,
    ];

    public function __construct(
        RateRepository $repository,
        private ExchangeRateService $service,
    ) {
        parent::__construct($repository);
    }

    public static function source(): string
    {
        return 'XRT';
    }

    public function import(): void
    {
        try {
            foreach (self::IMPORTABLE_CURRENCIES as $currency) {
                $result = $this->service->getRates(now(), $currency, self::IMPORTABLE_CURRENCIES);
                $this->save($result);
            }
        } catch (Throwable $t) {
            Log::error($t->getMessage());
        }
    }
}