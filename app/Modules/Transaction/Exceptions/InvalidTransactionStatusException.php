<?php

namespace App\Modules\Transaction\Exceptions;

class InvalidTransactionStatusException extends TransactionException
{
    protected $message = 'Invalid transaction status caught.';
}