<?php

namespace App\Modules\Account\Factories;

use App\Modules\Account\Exceptions\Validation\UnavailableAccountException;
use App\Modules\Account\Repositories\AccountRepository;
use App\Modules\Account\Structures\TransferAccountParameters;
use App\Modules\Currency\Enums\Currency;

readonly class AccountTransferParameterFactory
{
    public function __construct(
        private AccountRepository $repository,
    ) {}

    /**
     * @throws UnavailableAccountException
     */
    public function create(object $data): TransferAccountParameters
    {
        $exception = fn () => throw new UnavailableAccountException();

        $from = $this->repository->getById($data->from_account_id) ?? $exception();
        $to = $this->repository->getById($data->to_account_id) ?? $exception();

        return new TransferAccountParameters($from, $to, (float) $data->amount, Currency::from($data->currency));
    }
}