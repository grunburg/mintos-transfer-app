<?php

namespace App\Modules\Account\Services;

use App\Modules\Account\Exceptions\AccountFundTransferException;
use App\Modules\Rate\Exceptions\UnavailableRatesException;
use App\Modules\Rate\Services\RateConversionService;
use App\Modules\Transaction\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Throwable;

class AccountFundTransferService
{
    public function __construct(
        readonly private AccountFundBalanceService $balanceService,
        readonly private RateConversionService $conversionService,
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
        if ($transaction->from->currency === $transaction->currency) {
            return $transaction->amount;
        }

        return $this->conversionService->convert($transaction->amount, $transaction->from->currency, $transaction->currency);
    }
}