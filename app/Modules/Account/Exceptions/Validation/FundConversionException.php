<?php

namespace App\Modules\Account\Exceptions\Validation;

class FundConversionException extends AccountValidationException
{
    protected $message = 'There was an issue while converting the funds.';
}