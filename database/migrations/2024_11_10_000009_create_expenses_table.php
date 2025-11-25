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
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('apartment_id')->constrained('apartments')->cascadeOnDelete();
            $table->foreignId('lease_id')->nullable()->constrained('leases')->cascadeOnDelete();
            $table->enum('type', [
                'maintenance',
                'lighting',
                'furniture',
                'roofing',
                'fees',
                'cleaning',
                'painting',
                'plumbing',
                'electrical',
                'other',
            ]);
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('expense_date');
            $table->decimal('amount', 10, 2);
            $table->string('vendor_name')->nullable();
            $table->string('receipt_file')->nullable();
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('apartment_id');
            $table->index('lease_id');
            $table->index('type');
            $table->index('expense_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
