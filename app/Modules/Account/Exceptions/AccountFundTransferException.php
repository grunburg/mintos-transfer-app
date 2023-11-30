<?php

namespace App\Modules\Account\Exceptions;

class AccountFundTransferException extends AccountException
{
    protected $message = 'Failed to transfer the funds.';
}