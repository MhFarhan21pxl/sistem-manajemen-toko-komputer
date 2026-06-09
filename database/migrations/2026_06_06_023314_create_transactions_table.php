<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Relasi ke kasir yang melayani
            $table->string('transaction_code'); // Contoh: INV-001
            $table->integer('total_price');
            $table->integer('money_paid')->nullable();     // Uang yang dibayar pembeli
            $table->integer('money_returned')->nullable(); // Uang kembalian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
