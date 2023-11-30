<?php

namespace App\Modules\Account\Exceptions;

class AccountRepositoryException extends AccountException
{
    protected $message = 'Could not process the repository action.';
}