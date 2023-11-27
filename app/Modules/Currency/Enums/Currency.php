<?php

namespace App\Modules\Currency\Enums;

enum Currency: string
{
    public const CURRENCIES = [
        self::USD,
        self::AUD,
        self::EUR,
        self::GBP,
        self::CHF,
    ];

    case USD = 'USD';
    case EUR = 'EUR';
    case GBP = 'GBP';
    case CHF = 'CHF';
    case AUD = 'AUD';

}