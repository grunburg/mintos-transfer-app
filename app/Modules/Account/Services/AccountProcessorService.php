<?php

namespace App\Modules\Account\Services;

use App\Modules\Account\Exceptions\AccountException;
use App\Modules\Account\Exceptions\AccountProcessorException;
use App\Modules\Account\Exceptions\LockAcquirementException;
use App\Modules\Account\Exceptions\Validation\AccountValidationException;
use App\Modules\Account\Jobs\AccountTransfer;
use App\Modules\Account\Structures\AccountTransferParameters;
use App\Modules\Transaction\Factories\TransactionFactory;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Services\TransactionProcessorService;
use Cache;
use Log;
use Throwable;

class AccountProcessorService
{
    private const LOCK_SECONDS = 10;

    public function __construct(
        readonly private AccountValidationService $validationService,
        readonly private AccountFundTransferService $transferService,
        readonly private TransactionProcessorService $transactionProcessor,
    ) {}

    /**
     * @throws AccountValidationException
     */
    public function execute(AccountTransferParameters $parameters): void
    {
        try {
            $this->validationService->validate($parameters);

            AccountTransfer::dispatch($parameters);
        } catch (Throwable $t) {
            if ($t instanceof AccountValidationException) {
                throw $t;
            }

            Log::error($t->getMessage());
        }
    }

    /**
     * @throws AccountException
     */
    public function process(AccountTransferParameters $parameters): void
    {
        // Prevent any other queueable transaction task to interact with the source account.
        $lock = Cache::lock($parameters->from->id);

        try {
            if (!$lock->block(self::LOCK_SECONDS)) {
                throw new LockAcquirementException();
            }

            $transaction = TransactionFactory::create($parameters);

            // Before we process the transaction, we should validate it.
            $this->validationService->validate($transaction);
        } catch (Throwable $t) {
            $lock->release();

            throw new AccountProcessorException(previous: $t);
        }

        try {
            $this->transactionProcessor->process($transaction, function (Transaction $transaction) {
                $this->transferService->transfer($transaction);
            });
        } catch (Throwable $t) {
            $lock->release();

            throw new AccountProcessorException(previous: $t);
        }

        $lock->release();
    }
}