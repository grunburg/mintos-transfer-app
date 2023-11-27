<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->string('from', 3);
            $table->string('to', 3);
            $table->float('rate', places: 4);
            $table->date('date');
            $table->string('source', 3);

            $table->primary(['from', 'to', 'date']);
            $table->index(['from', 'to', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
