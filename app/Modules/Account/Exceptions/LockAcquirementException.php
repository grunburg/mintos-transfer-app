<?php

namespace App\Modules\Account\Exceptions;

class LockAcquirementException extends AccountException
{
    protected $message = 'Failed to acquire transaction lock.';
}