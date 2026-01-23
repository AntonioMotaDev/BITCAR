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
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->enum('type', [
                'entry', 
                'exit',
                'trip_start',
                'trip_checkpoint',
                'trip_end',
                'fuel',
                'incident',
                'maintenance', 
                'other'
                ])->default('other');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklists');
    }
};
