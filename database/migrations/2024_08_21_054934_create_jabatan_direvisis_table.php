<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jabatan_direvisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verifikasi_id')->nullable()->constrained('verifikasi');
            $table->foreignId('jabatan_diajukan_id')->nullable()->constrained('jabatan_diajukan')->onDelete('cascade');
            $table->foreignId('abk_jabatan_id')->nullable()->constrained('abk_jabatan')->onDelete('cascade');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan_direvisis');
    }
};
