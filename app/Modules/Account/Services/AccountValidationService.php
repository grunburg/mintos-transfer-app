<?php

namespace App\Modules\Account\Services;

use App\Modules\Account\Exceptions\Validation\AccountValidationException;
use App\Modules\Account\Exceptions\Validation\IncompatibleCurrencyException;
use App\Modules\Account\Exceptions\Validation\InsufficientFundsException;
use App\Modules\Account\Exceptions\Validation\InvalidAmountException;
use App\Modules\Account\Structures\TransferParameters;
use App\Modules\Rate\Exceptions\RateConversionException;
use App\Modules\Rate\Services\RateConversionService;
use App\Modules\Transaction\Models\Transaction;

readonly class AccountValidationService
{
    public function __construct(
        private RateConversionService $conversion,
    ) {}

    /**
     * @throws AccountValidationException|RateConversionException
     */
    public function validate(Transaction | TransferParameters $transaction): void
    {
        if ($transaction->amount <= 0) {
            throw new InvalidAmountException();
        }

        if ($transaction->currency !== $transaction->to->currency) {
            throw new IncompatibleCurrencyException();
        }

        // Check if account has enough source funds.
        $amount = $this->getConvertedSourceAmount($transaction);
        if ($amount > $transaction->from->amount) {
            throw new InsufficientFundsException();
        }
    }

    /**
     * @throws RateConversionException
     */
    private function getConvertedSourceAmount(Transaction | TransferParameters $transaction): float
    {
        if ($transaction->currency === $transaction->from->currency) {
            return $transaction->amount;
        }

        return $this->conversion->convert($transaction->amount, $transaction->currency, $transaction->from->currency);
    }
}