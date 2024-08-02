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
    Schema::create('temperamen_kerja_jabatan', function (Blueprint $table) {
      $table->id();
      $table->foreignId('jabatan_id')->constrained('jabatan');
      $table->foreignId('temperamen_kerja_id')->constrained('temperamen_kerja');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('temperamen_kerja_jabatan');
  }
};