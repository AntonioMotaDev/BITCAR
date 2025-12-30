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
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('start_vehicle_log_id')->constrained('vehicle_logs')->onDelete('cascade');
            $table->foreignId('end_vehicle_log_id')->nullable()->constrained('vehicle_logs')->onDelete('cascade');
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->decimal('total_distance_km', 10, 2)->nullable();
            $table->decimal('estimated_fuel_consumption', 10, 2)->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'started_at']);
            $table->index(['user_id', 'started_at']);
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
