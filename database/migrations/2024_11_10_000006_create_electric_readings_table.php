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
        Schema::create('electric_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('electric_service_id')->constrained('electric_services')->onDelete('cascade');
            $table->date('reading_date');
            $table->decimal('reading_value', 10, 2);
            $table->decimal('cost', 10, 2);
            $table->string('image_path')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electric_readings');
    }
};
