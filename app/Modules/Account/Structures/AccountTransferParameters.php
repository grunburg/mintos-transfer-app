<?php

namespace App\Modules\Account\Structures;

use App\Modules\Account\Models\Account;
use App\Modules\Currency\Enums\Currency;

class AccountTransferParameters
{
    public function __construct(
       public Account $from,
       public Account $to,
       public float $amount,
       public Currency $currency,
    ) {}
}