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
        Schema::create('lease_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lease_id')->constrained()->cascadeOnDelete();
            $table->string('month');
            $table->date('due_date');
            $table->decimal('amount_due', 10, 2);
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->date('payment_date')->nullable();
            $table->enum('status', ['paid', 'unpaid', 'partial', 'late'])->default('unpaid');
            $table->string('receipt_file')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lease_payments');
    }
};
