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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('brand', 100);
            $table->string('model', 100);
            $table->smallInteger('year');
            $table->string('license_plate', 20)->unique();
            $table->string('vin', 50)->nullable()->unique();
            $table->string('color', 50);
            $table->enum('type', ['pickup', 'sedan', 'suv', 'van', 'camion']);
            $table->decimal('mileage', 10, 2);
            $table->decimal('fuel_capacity', 10, 2);
            $table->enum('status', ['activo', 'mantenimiento', 'inactivo'])->default('activo');
            $table->timestamps();
            
            // Índices
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
