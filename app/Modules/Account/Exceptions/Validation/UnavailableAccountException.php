<?php

namespace App\Modules\Account\Exceptions\Validation;

class UnavailableAccountException extends AccountValidationException
{
    protected $message = 'Could not find requested account by given id.';
}