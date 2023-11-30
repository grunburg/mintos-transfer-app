<?php

use App\Modules\Account\Models\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class, 'from_account_id');
            $table->foreignIdFor(Account::class, 'to_account_id');
            $table->float('amount', total: 9)->default(0);
            $table->string('currency', 3);
            $table->string('message')->nullable();
            $table->string('status', 32);
            $table->timestamps();

            $table->index(['from_account_id', 'to_account_id']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
