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
    Schema::create('bakat_kerja_jabatan_diajukan', function (Blueprint $table) {
      $table->id();
      $table->foreignId('jabatan_diajukan_id')->constrained('jabatan_diajukan');
      $table->foreignId('bakat_kerja_id')->constrained('bakat_kerja');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('bakat_kerja_jabatan_diajukan');
  }
};
