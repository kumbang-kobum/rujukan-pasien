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
            $table->string('no_konsultasi', 40)->unique();
            $table->unsignedBigInteger('kunjungan_id');
            $table->unsignedBigInteger('pasien_id')->nullable();
            $table->unsignedBigInteger('rumah_sakit_asal_id');
            $table->unsignedBigInteger('rumah_sakit_tujuan_id');
            $table->unsignedBigInteger('dokter_pengirim_id');
            $table->unsignedBigInteger('dokter_tujuan_id');
            $table->unsignedBigInteger('escalated_to_rujukan_id')->nullable();
            $table->string('patient_ihs_number')->nullable();
            $table->string('organization_ihs_asal')->nullable();
            $table->string('organization_ihs_tujuan')->nullable();
            $table->string('practitioner_ihs_pengirim')->nullable();
            $table->string('practitioner_ihs_tujuan')->nullable();
            $table->string('practitioner_role_pengirim')->nullable();
            $table->string('practitioner_role_tujuan')->nullable();
            $table->string('encounter_satusehat_id')->nullable();
            $table->string('judul');
            $table->string('urgensi', 20)->default('rutin');
            $table->text('alasan_konsultasi');
            $table->text('pertanyaan_klinis');
            $table->text('ringkasan_klinis')->nullable();
            $table->text('diagnosis_kerja')->nullable();
            $table->text('hasil_penunjang')->nullable();
            $table->text('terapi_berjalan')->nullable();
            $table->string('consent_status', 30)->default('belum_diminta');
            $table->string('consent_granted_by_name')->nullable();
            $table->string('consent_granted_by_role')->nullable();
            $table->string('consent_method')->nullable();
            $table->timestamp('consent_granted_at')->nullable();
            $table->timestamp('consent_expires_at')->nullable();
            $table->text('consent_notes')->nullable();
            $table->string('status', 40)->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->timestamps();

            $table->index('kunjungan_id');
            $table->index('pasien_id');
            $table->index('rumah_sakit_asal_id');
            $table->index('rumah_sakit_tujuan_id');
            $table->index('dokter_pengirim_id');
            $table->index('dokter_tujuan_id');
            $table->index('status');
        });

        Schema::create('konsultasi_pesan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('konsultasi_id');
            $table->unsignedBigInteger('pengirim_id');
            $table->string('jenis_pesan', 30)->default('message');
            $table->text('isi_pesan');
            $table->string('status', 20)->default('sent');
            $table->timestamp('dibaca_at')->nullable();
            $table->timestamps();

            $table->index('konsultasi_id');
            $table->index('pengirim_id');
        });

        Schema::create('konsultasi_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('konsultasi_id');
            $table->unsignedBigInteger('actor_user_id')->nullable();
            $table->string('event_type', 50);
            $table->text('deskripsi');
            $table->json('payload')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('konsultasi_id');
            $table->index('actor_user_id');
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultasi_audit_logs');
        Schema::dropIfExists('konsultasi_pesan');
        Schema::dropIfExists('konsultasi');
    }
};
