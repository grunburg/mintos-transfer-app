<?php

namespace App\Modules\Account\Services;

use App\Modules\Account\Exceptions\Validation\AccountValidationException;
use App\Modules\Account\Exceptions\Validation\FundConversionException;
use App\Modules\Account\Exceptions\Validation\IncompatibleCurrencyException;
use App\Modules\Account\Exceptions\Validation\InsufficientFundsException;
use App\Modules\Account\Exceptions\Validation\InvalidAmountException;
use App\Modules\Account\Structures\TransferAccountParameters;
use App\Modules\Rate\Exceptions\UnavailableRatesException;
use App\Modules\Rate\Services\RateConversionService;
use App\Modules\Transaction\Models\Transaction;

readonly class AccountValidationService
{
    public function __construct(
        private RateConversionService $conversionService,
    ) {}

    /**
     * @throws AccountValidationException
     */
    public function validate(Transaction | TransferAccountParameters $request): void
    {
        if (round($request->amount, 2) <= 0) {
            throw new InvalidAmountException();
        }

        if ($request->currency !== $request->to->currency) {
            throw new IncompatibleCurrencyException();
        }

        // Check if account has enough source funds.
        $amount = $this->getConvertedSourceAmount($request);
        if ($amount > $request->from->amount) {
            throw new InsufficientFundsException();
        }
    }

    /**
     * @throws FundConversionException
     */
    private function getConvertedSourceAmount(Transaction | TransferAccountParameters $request): float
    {
        try {
            if ($request->currency === $request->from->currency) {
                return $request->amount;
            }

            return $this->conversionService->convert($request->amount, $request->currency, $request->from->currency);
        } catch (UnavailableRatesException $t) {
            throw new FundConversionException(previous: $t);
        }
    }
}