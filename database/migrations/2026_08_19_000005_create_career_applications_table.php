<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('career_applications')) {
            Schema::create('career_applications', function (Blueprint $table) {
                $table->id();
                $table->string('first_name', 100);
                $table->string('last_name', 100);
                $table->string('phone', 30);
                $table->string('email', 150)->nullable();
                $table->string('position', 150)->nullable();
                $table->text('address');
                $table->string('resume_file', 255);
                $table->string('original_filename', 255)->nullable();
                $table->text('notes')->nullable();
                $table->enum('status', ['pending', 'reviewed', 'shortlisted', 'rejected'])->default('pending');
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('career_applications');
    }
};

