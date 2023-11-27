<?php

namespace App\Modules\Rate\Exceptions;

class RateRepositoryException extends RateException
{
    public const COULD_NOT_SAVE = 'Could not save rate entry.';
}