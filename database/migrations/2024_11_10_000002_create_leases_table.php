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
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained('apartments')->onDelete('cascade');
            $table->string('tenant_name');
            $table->string('tenant_phone');
            $table->string('tenant_email');
            $table->string('tenant_national_id')->nullable();
            $table->string('tenant_image')->nullable();
            $table->string('national_id_image')->nullable();
            $table->string('lease_document')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('rent_amount', 10, 2);
            $table->enum('payment_status', ['paid', 'unpaid', 'partial']);
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->json('documents')->nullable();
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
        Schema::dropIfExists('leases');
    }
};
