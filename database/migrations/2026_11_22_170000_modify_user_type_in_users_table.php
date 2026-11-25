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
        try {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `user_type` VARCHAR(50) NOT NULL DEFAULT 'internal'");
        } catch (\Throwable $e) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('user_type', 50)->default('internal')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `user_type` ENUM('internal','external') NOT NULL DEFAULT 'internal'");
        } catch (\Throwable $e) {
            // ignore
        }
    }
};
