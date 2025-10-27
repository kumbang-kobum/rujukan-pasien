<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();
            $table->string('no_rawat', 50)->unique();
            $table->unsignedBigInteger('pasien_id');
            $table->unsignedBigInteger('dokter_id');
            $table->unsignedBigInteger('user_id'); // user yang input
            $table->unsignedBigInteger('rumah_sakit_id'); // RS asal
            $table->string('poli');
            $table->date('tanggal_kunjungan');
            $table->dateTime('waktu_masuk')->nullable();
            $table->text('keluhan_utama')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kunjungan');
    }
};