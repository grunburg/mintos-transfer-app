<?php

namespace App\Modules\Account\Exceptions\Validation;

class IncompatibleCurrencyException extends AccountValidationException
{
    protected $message = 'The currency used is incompatible with the receiver\'s account.';
}