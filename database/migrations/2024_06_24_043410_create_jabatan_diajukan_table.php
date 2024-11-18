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
    Schema::create('jabatan_diajukan', function (Blueprint $table) {
      $table->id();
      $table->foreignId('ajuan_id')->nullable()->constrained('ajuan')->cascadeOnDelete();
      $table->foreignId('jenis_jabatan_id')->nullable()->constrained('jenis_jabatan')->cascadeOnDelete();
      $table->unsignedBigInteger('parent_id')->nullable();
      $table->string('nama')->nullable();
      $table->string('kode')->nullable();
      $table->text('ikhtisar')->nullable();
      $table->text('prestasi')->nullable();
      $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
      $table->integer('umur')->nullable();
      $table->integer('tinggi_badan')->nullable();
      $table->string('postur_badan')->nullable();
      $table->string('penampilan')->nullable();
      $table->string('keterampilan')->nullable();
      $table->integer('berat_badan')->nullable();
      $table->string('getaran')->nullable();
      $table->string('suhu')->nullable();
      $table->string('suara')->nullable();
      $table->string('penerangan')->nullable();
      $table->string('letak')->nullable();
      $table->string('tempat')->nullable();
      $table->string('udara')->nullable();
      $table->string('keadaan_ruangan')->nullable();
      $table->timestamps();

      $table->foreign('parent_id')->references('id')->on('jabatan_diajukan')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('jabatan_diajukan');
  }
};
