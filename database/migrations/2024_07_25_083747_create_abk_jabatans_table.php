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
        Schema::create('abk_jabatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jabatan_id')->constrained('jabatan_diajukan')->onDelete('cascade');
            $table->foreignId('abk_id')->constrained('ajuan')->onDelete('cascade');
            $table->foreignId('jabatan_tutam_id')->constrained('jabatan_tugas_tambahan')->onDelete('cascade');
            $table->integer('total_waktu_penyelesaian_tugas')->nullable();
            $table->integer('kebutuhan_pegawai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abk_jabatans');
    }
};
