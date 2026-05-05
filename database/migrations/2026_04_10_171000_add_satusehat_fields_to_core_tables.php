<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            if (!Schema::hasColumn('pasien', 'patient_ihs_number')) {
                $table->string('patient_ihs_number')->nullable()->after('nik');
                $table->index('patient_ihs_number');
            }
        });

        Schema::table('rujukan', function (Blueprint $table) {
            if (!Schema::hasColumn('rujukan', 'origin_konsultasi_id')) {
                $table->unsignedBigInteger('origin_konsultasi_id')->nullable()->after('kunjungan_id');
                $table->index('origin_konsultasi_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rujukan', function (Blueprint $table) {
            if (Schema::hasColumn('rujukan', 'origin_konsultasi_id')) {
                $table->dropIndex(['origin_konsultasi_id']);
                $table->dropColumn('origin_konsultasi_id');
            }
        });

        Schema::table('pasien', function (Blueprint $table) {
            if (Schema::hasColumn('pasien', 'patient_ihs_number')) {
                $table->dropIndex(['patient_ihs_number']);
                $table->dropColumn('patient_ihs_number');
            }
        });
    }
};
