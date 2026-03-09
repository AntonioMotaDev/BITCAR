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
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_log_id')->constrained('vehicle_logs')->onDelete('cascade');
            $table->foreignId('checklist_item_id')->constrained('checklist_items')->onDelete('cascade');
            $table->longText('signature_data');
            $table->string('signer_name', 255);
            $table->datetime('signed_at');
            $table->timestamps();
            
            // Una firma por item por log
            $table->unique(['vehicle_log_id', 'checklist_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
