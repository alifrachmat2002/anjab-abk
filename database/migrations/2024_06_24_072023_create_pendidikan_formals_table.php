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
        Schema::create('pendidikan_formals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kualifikasi_jabatan_id')->constrained();
            $table->enum('jenjang', ['D3', 'S1', 'S2', 'S3']);
            $table->string('jurusan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendidikan_formals');
    }
};
