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
        Schema::create('scholarship_criteria', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kriteria');
            $table->decimal('bobot');
            $table->enum('jenis_kriteria', ['Core Factor', 'Secondary Factor']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_criteria');
    }
};