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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location_text');
            $table->string('location_link');
            $table->string('floor_plan')->nullable();
            $table->string('ownership_document')->nullable();
            $table->json('important_files')->nullable();
            $table->integer('bedrooms');
            $table->integer('living_rooms');
            $table->integer('guest_rooms');
            $table->integer('kitchens');
            $table->integer('bathrooms');
            $table->integer('balconies');
            $table->integer('master_rooms');
            $table->json('furniture')->nullable();
            $table->text('notes')->nullable();
            // Existing simplified fields retained for compatibility
            $table->string('apartment_number')->unique();
            $table->integer('floor_number')->nullable();
            $table->decimal('square_meters', 8, 2)->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('rent_price', 10, 2);
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
