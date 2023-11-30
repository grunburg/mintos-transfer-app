<?php

namespace App\Modules\Transaction\Models;

use App\Modules\Account\Models\Account;
use App\Modules\Currency\Enums\Currency;
use App\Modules\Transaction\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'currency',
        'status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'float',
        'status' => TransactionStatus::class,
        'currency' => Currency::class,
    ];

    public function from(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_account_id', 'id');
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account_id', 'id');
    }

    public function isPending(): bool
    {
        return $this->status === TransactionStatus::Pending;
    }
}
