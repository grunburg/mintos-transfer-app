<?php

namespace App\Modules\Account\Models;

use App\Modules\Currency\Enums\Currency;
use App\Modules\Transaction\Models\Transaction;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'currency',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'float',
        'currency' => Currency::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from_account_id', 'id');
    }
}
