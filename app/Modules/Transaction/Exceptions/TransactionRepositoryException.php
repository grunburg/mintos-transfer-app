<?php

namespace App\Modules\Transaction\Exceptions;

class TransactionRepositoryException extends TransactionException
{
    protected $message = 'Could not process the repository action.';
}