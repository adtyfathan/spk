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
        Schema::create('scholarship_applications', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jurusan');
            $table->decimal('ipk', 3, 2);
            $table->integer('pengalaman_organisasi');
            $table->integer('penghasilan_orang_tua');
            $table->integer('kontribusi_sosial');
            $table->integer('jumlah_tanggungan');
            $table->integer('semester');
            $table->decimal('total_score', 4, 2);
            $table->string('eligibility');
            $table->json('calculation_details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_applications');
    }
};