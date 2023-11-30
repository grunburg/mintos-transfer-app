<?php

namespace App\Modules\Transaction\Services;

use App\Modules\Account\Exceptions\Validation\AccountValidationException;
use App\Modules\Transaction\Enums\TransactionStatus;
use App\Modules\Transaction\Exceptions\InvalidTransactionStatusException;
use App\Modules\Transaction\Exceptions\TransactionProcessorException;
use App\Modules\Transaction\Exceptions\TransactionRepositoryException;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Repositories\TransactionRepository;
use App\Modules\Transaction\Services\Handlers\TransactionExceptionHandler;
use Throwable;

readonly class TransactionProcessorService
{
    public function __construct(
        private TransactionExceptionHandler $exceptionHandler,
        private TransactionRepository $repository,
    ) {}

    /**
     * @throws TransactionProcessorException
     */
    public function process(Transaction $transaction, callable $callable): void
    {
        try {
            // Ensure the transaction status is pending.
            if (!$transaction->isPending()) {
                throw new InvalidTransactionStatusException();
            }

            $this->repository->save($transaction);
            $transaction = $this->repository->refresh($transaction);

            $callable($transaction);

            $this->success($transaction);
        } catch (Throwable $t) {
            $message = $t instanceof AccountValidationException ? $t->getMessage() : null;
            $this->exceptionHandler->handle($transaction, $message);

            throw new TransactionProcessorException(previous: $t);
        }
    }

    /**
     * @throws TransactionRepositoryException
     */
    private function success(Transaction $transaction): void
    {
        $transaction->status = TransactionStatus::Success;
        $this->repository->save($transaction);
    }
}