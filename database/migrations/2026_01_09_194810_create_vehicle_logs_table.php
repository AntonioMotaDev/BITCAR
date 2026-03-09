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
        Schema::create('vehicle_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('checklist_id')->nullable()->constrained('checklists')->onDelete('set null');
            $table->enum('type', ['entry','exit','trip_start','trip_checkpoint','trip_end','fuel','incident','maintenance','other'])->default('other');
            $table->decimal('mileage', 10, 2)->nullable();
            $table->decimal('fuel_level', 5, 2)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['vehicle_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_logs');
    }
};
