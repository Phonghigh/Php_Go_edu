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
        Schema::create('subject_level_stats', function (Blueprint $table) {
            $table->string('subject');
            $table->enum('level', ['ge8','6-<8','4-<6','lt4']);
            $table->unsignedBigInteger('count');
            $table->primary(['subject','level']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_level_stats');
    }
};
