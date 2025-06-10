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
        Schema::create('scholarship_sub_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id')->constrained('scholarship_criteria')->onDelete('cascade');
            $table->string('nama_sub_kriteria');
            $table->text('deskripsi');
            $table->integer('nilai');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_sub_criteria');
    }
};