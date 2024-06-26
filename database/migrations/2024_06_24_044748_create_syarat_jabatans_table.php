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
        Schema::create('syarat_jabatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jabatan_id')->constrained();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->integer('umur');
            $table->integer('tinggi_badan');
            $table->string('postur_badan');
            $table->string('penampilan');
            $table->string('keterampilan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syarat_jabatans');
    }
};
