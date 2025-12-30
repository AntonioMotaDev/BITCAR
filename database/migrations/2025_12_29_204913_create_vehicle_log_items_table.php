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
        Schema::create('vehicle_log_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_log_id')->constrained('vehicle_logs')->onDelete('cascade');
            $table->foreignId('checklist_item_id')->constrained('checklist_items')->onDelete('cascade');
            $table->text('value');
            $table->timestamps();

            $table->index('vehicle_log_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_log_items');
    }
};
