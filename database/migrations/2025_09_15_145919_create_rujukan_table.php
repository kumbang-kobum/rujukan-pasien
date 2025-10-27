<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rujukan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kunjungan_id'); // TANPA FOREIGN KEY
            $table->unsignedBigInteger('rumah_sakit_asal_id'); // TANPA FOREIGN KEY
            $table->unsignedBigInteger('rumah_sakit_tujuan_id'); // TANPA FOREIGN KEY
            $table->unsignedBigInteger('dokter_tujuan_id'); // TANPA FOREIGN KEY
            $table->text('alasan_rujukan');
            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->text('catatan_penerima')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rujukan');
    }
};