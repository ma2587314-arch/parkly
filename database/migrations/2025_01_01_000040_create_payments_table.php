<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 8, 2);
            $table->enum('method', ['visa', 'mastercard', 'apple_pay']);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('name_on_card')->nullable();
            $table->string('card_number_last4', 4)->nullable();
            $table->string('expiration_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
