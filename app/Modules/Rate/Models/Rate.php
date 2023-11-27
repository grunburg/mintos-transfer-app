<?php

namespace App\Modules\Rate\Models;

use App\Modules\Currency\Enums\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'from' => Currency::class,
        'to' => Currency::class,
        'date' => 'date',
        'rate' => 'float',
    ];
}
