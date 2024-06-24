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
        Schema::create('ajuans', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->enum('status', ['diajukan', 'diterima', 'ditolak']);
            $table->enum('jenis', ['anjab', 'abk']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajuans');
    }
};
