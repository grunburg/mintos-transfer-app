<?php

namespace App\Modules\Rate\Contracts;

interface RateImportContract
{
    public static function source(): string;

    public function import(): void;
}