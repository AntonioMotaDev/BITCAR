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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->datetime('start_time');
            $table->datetime('end_time')->nullable();
            $table->decimal('start_mileage', 10, 2);
            $table->decimal('end_mileage', 10, 2)->nullable();
            $table->decimal('start_fuel_level', 5, 2)->nullable();
            $table->decimal('end_fuel_level', 5, 2)->nullable();
            $table->decimal('distance_km', 10, 2)->nullable();
            $table->decimal('estimated_fuel_consumption', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index(['vehicle_id', 'is_active']);
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
