<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicle_logs', function (Blueprint $table) {
            $table->foreignId('trip_id')
                  ->nullable()
                  ->after('checklist_id')
                  ->constrained('trips')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_logs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('trip_id');
        });
    }
};
