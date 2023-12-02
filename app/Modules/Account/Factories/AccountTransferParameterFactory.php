<?php

namespace App\Modules\Account\Factories;

use App\Modules\Account\Exceptions\Validation\UnavailableAccountException;
use App\Modules\Account\Repositories\AccountRepository;
use App\Modules\Account\Structures\AccountTransferParameters;
use App\Modules\Currency\Enums\Currency;

class AccountTransferParameterFactory
{
    public function __construct(
        readonly private AccountRepository $repository,
    ) {}

    /**
     * @throws UnavailableAccountException
     */
    public function create(object $data): AccountTransferParameters
    {
        $exception = fn () => throw new UnavailableAccountException();

        $from = $this->repository->getById($data->from_account_id) ?? $exception();
        $to = $this->repository->getById($data->to_account_id) ?? $exception();

        return new AccountTransferParameters($from, $to, (float) $data->amount, Currency::from($data->currency));
    }
}