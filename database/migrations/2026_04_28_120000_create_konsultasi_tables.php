<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsultasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kunjungan_id');
            $table->unsignedBigInteger('rumah_sakit_asal_id');
            $table->unsignedBigInteger('rumah_sakit_tujuan_id');
            $table->unsignedBigInteger('dokter_pengirim_id');
            $table->unsignedBigInteger('dokter_tujuan_id');
            $table->unsignedBigInteger('rujukan_id')->nullable();
            $table->string('judul');
            $table->text('ringkasan_klinis')->nullable();
            $table->text('diagnosis_kerja')->nullable();
            $table->text('terapi_berjalan')->nullable();
            $table->text('hasil_penunjang')->nullable();
            $table->text('alasan_konsultasi');
            $table->text('pertanyaan_konsultasi')->nullable();
            $table->enum('status', [
                'draft',
                'terkirim',
                'dibaca',
                'diterima',
                'diskusi',
                'butuh_info',
                'dijawab',
                'ditutup',
                'dijadikan_rujukan',
            ])->default('draft');
            $table->enum('consent_status', ['menunggu', 'diberikan', 'ditolak'])->default('menunggu');
            $table->string('consent_nama_pemberi')->nullable();
            $table->string('consent_hubungan')->nullable();
            $table->enum('consent_metode', ['lisan', 'tertulis', 'digital'])->nullable();
            $table->dateTime('consent_diberikan_pada')->nullable();
            $table->text('consent_catatan')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->unsignedBigInteger('closed_by')->nullable();
            $table->timestamps();
        });

        Schema::create('konsultasi_pesan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('konsultasi_id');
            $table->unsignedBigInteger('pengirim_id');
            $table->enum('tipe', ['pesan', 'jawaban', 'minta_info'])->default('pesan');
            $table->text('pesan');
            $table->timestamps();
        });

        Schema::create('konsultasi_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('konsultasi_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('aksi');
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultasi_audit_logs');
        Schema::dropIfExists('konsultasi_pesan');
        Schema::dropIfExists('konsultasi');
    }
};
