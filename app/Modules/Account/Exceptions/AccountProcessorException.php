<?php

namespace App\Modules\Account\Exceptions;

class AccountProcessorException extends AccountException
{
    protected $message = 'Failed to process fund transfer.';
}