<?php

namespace App\Modules\Account\Exceptions\Validation;

class InsufficientFundsException extends AccountValidationException
{
    protected $message = 'Insufficient funds to complete the transfer.';
}