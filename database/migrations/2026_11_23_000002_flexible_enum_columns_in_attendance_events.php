<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change attendance_events columns to flexible VARCHAR
        Schema::table('attendance_events', function (Blueprint $table) {
            $table->string('event_type', 30)->default('in')->change();
            $table->string('verification_method', 50)->default('fingerprint')->change();
            $table->string('source', 50)->default('device')->change();
            $table->string('processing_status', 50)->default('processed')->change();
        });

        // Change hrm_attendances.source to flexible VARCHAR
        Schema::table('hrm_attendances', function (Blueprint $table) {
            $table->string('source', 50)->default('manual')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
