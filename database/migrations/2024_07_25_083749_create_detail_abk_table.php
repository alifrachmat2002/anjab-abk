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
        Schema::create('detail_abk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ajuan_id')->constrained('ajuan');
            $table->foreignId('abk_jabatan_id')->constrained('abk_jabatan')->onDelete('cascade');
            $table->foreignId('uraian_tugas_diajukan_id')->nullable()->constrained('uraian_tugas_diajukan');
            $table->string('hasil_kerja')->nullable();
            $table->integer('waktu_penyelesaian')->nullable();
            $table->integer('jumlah_hasil_kerja')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_abk');
    }
};
