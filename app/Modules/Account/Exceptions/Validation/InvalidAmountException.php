<?php

namespace App\Modules\Account\Exceptions\Validation;

class InvalidAmountException extends AccountValidationException
{
    protected $message = 'The value for the amount is invalid. Please provide a valid amount value.';
}