<?php

namespace App\Modules\Rate\Exceptions;

class RateRepositoryException extends RateException
{
    protected $message = 'Could not process the repository action.';
}