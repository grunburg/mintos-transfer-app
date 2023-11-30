<?php

namespace App\Modules\Account\Services;

use App\Modules\Account\Exceptions\AccountException;
use App\Modules\Account\Exceptions\AccountProcessorException;
use App\Modules\Account\Exceptions\LockAcquirementException;
use App\Modules\Account\Structures\TransferParameters;
use App\Modules\Transaction\Factories\TransactionFactory;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Services\TransactionProcessorService;
use Cache;
use Throwable;

readonly class AccountProcessorService
{
    private const LOCK_SECONDS = 10;

    public function __construct(
        private AccountValidationService $validation,
        private AccountFundTransferService $transfer,
        private TransactionProcessorService $processor,
    ) {}

    /**
     * @throws AccountException
     */
    public function process(TransferParameters $parameters): void
    {
        // Prevent any other queueable transaction task to interact with the source account.
        $lock = Cache::lock($parameters->from->id);


        try {
            if (!$lock->block(self::LOCK_SECONDS)) {
                throw new LockAcquirementException();
            }

            $transaction = TransactionFactory::create($parameters);

            // Before we process the transaction, we should validate it.
            $this->validation->validate($transaction);
        } catch (Throwable $t) {
            $lock->release();

            throw new AccountProcessorException(previous: $t);
        }

        try {
            $this->processor->process($transaction, function (Transaction $transaction) {
                $this->transfer->transfer($transaction);
            });
        } catch (Throwable $t) {
            $lock->release();

            throw new AccountProcessorException(previous: $t);
        }

        $lock->release();
    }
}