<?php

namespace App\Modules\Transaction\Services\Handlers;

use App\Modules\Transaction\Enums\TransactionStatus;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\Transaction\Repositories\TransactionRepository;
use Log;
use Throwable;

class TransactionExceptionHandler
{
    public function __construct(
        readonly private TransactionRepository $repository,
    ) {}

    public function handle(Transaction $transaction, ?string $message = null): void
    {
        $transaction->status = TransactionStatus::Failed;
        $transaction->message = $message;

        try {
            $this->repository->save($transaction);
        } catch (Throwable $t) {
            Log::error($t->getMessage());
        }
    }
}