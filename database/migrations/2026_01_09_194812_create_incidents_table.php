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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_log_id')->constrained('vehicle_logs')->onDelete('cascade');
            $table->text('description');
            $table->enum('severity', ['baja', 'media', 'alta', 'critica'])->default('media');
            $table->boolean('is_resolved')->default(false);
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index('severity');
            $table->index('is_resolved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
