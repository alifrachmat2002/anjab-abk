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
    Schema::create('fungsi_pekerjaan_jabatan', function (Blueprint $table) {
      $table->id();
      $table->foreignId('jabatan_id')->constrained('jabatan');
      $table->foreignId('fungsi_pekerjaan_id')->constrained('fungsi_pekerjaan');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('fungsi_pekerjaan_jabatan');
  }
};
