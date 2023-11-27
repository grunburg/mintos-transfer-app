<?php

namespace App\Modules\Rate\Contracts;

use App\Modules\Rate\Structures\RateResult;

interface RateResultFactoryContract
{
    public static function create(object $data): RateResult;
}