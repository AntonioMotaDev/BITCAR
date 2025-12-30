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
        Schema::create('vehicle_log_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_log_id')->constrained('vehicle_logs')->onDelete('cascade');
            $table->string('file_path');
            $table->timestamps();

            $table->index('vehicle_log_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_log_photos');
    }
};
