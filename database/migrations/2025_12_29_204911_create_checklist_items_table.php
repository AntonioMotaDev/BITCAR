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
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_id')->constrained('checklists')->onDelete('cascade');
            $table->string('label');
            $table->text('answer')->nullable();
            $table->enum('type', ['boolean', 'text', 'number', 'photo']);
            $table->boolean('required')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['checklist_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_items');
    }
};
