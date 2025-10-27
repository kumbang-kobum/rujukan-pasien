<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berkas_medis', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('kunjungan_id');
    $table->unsignedBigInteger('uploader_id')->nullable();
    $table->string('jenis')->nullable();
    $table->string('nama_file');
    $table->string('path');
    $table->timestamps();

    // foreign key harus ke tabel "kunjungan" (singular)
    $table->foreign('kunjungan_id')
          ->references('id')->on('kunjungan')
          ->onDelete('cascade');

    $table->foreign('uploader_id')
          ->references('id')->on('users')
          ->onDelete('set null');
});
    }

    public function down(): void
    {
        Schema::dropIfExists('berkas_medis');
    }
};