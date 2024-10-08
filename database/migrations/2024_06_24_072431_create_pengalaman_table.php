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
    Schema::create('pengalaman', function (Blueprint $table) {
      $table->id();
      $table->foreignId('kualifikasi_jabatan_id')->constrained('jabatan');
      $table->string('nama');
      $table->integer('lama');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('pengalaman');
  }
};
