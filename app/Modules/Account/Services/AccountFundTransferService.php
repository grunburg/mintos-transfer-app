<?php

namespace App\Modules\Account\Services;

use App\Modules\Account\Exceptions\AccountFundTransferException;
use App\Modules\Rate\Exceptions\UnavailableRatesException;
use App\Modules\Rate\Services\RateConversionService;
use App\Modules\Transaction\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Throwable;

readonly class AccountFundTransferService
{
    public function __construct(
        private AccountFundBalanceService $balanceService,
        private RateConversionService $conversionService,
    ) {}

    /**
     * @throws AccountFundTransferException
     */
    public function transfer(Transaction $transaction): void
    {
        DB::beginTransaction();

        try {
            $amount = $this->getConvertedTransferableAmount($transaction);
            $this->balanceService->remove($transaction->from, $amount);
            $this->balanceService->add($transaction->to, $amount);
        } catch (Throwable $t) {
            DB::rollBack();

            throw new AccountFundTransferException(previous: $t);
        }

        DB::commit();
    }

    /**
     * @throws UnavailableRatesException
     */
    private function getConvertedTransferableAmount(Transaction $transaction): float
    {
        if ($transaction->currency === $transaction->from->currency) {
            return $transaction->amount;
        }

        return $this->conversionService->convert($transaction->amount, $transaction->currency, $transaction->from->currency);
    }
}