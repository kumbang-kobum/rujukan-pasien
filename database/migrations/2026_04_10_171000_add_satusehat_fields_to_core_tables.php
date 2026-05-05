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

        Schema::table('rumah_sakit', function (Blueprint $table) {
            if (!Schema::hasColumn('rumah_sakit', 'organization_ihs_number')) {
                $table->string('organization_ihs_number')->nullable()->after('nama');
                $table->index('organization_ihs_number');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'practitioner_ihs_number')) {
                $table->string('practitioner_ihs_number')->nullable()->after('email');
                $table->index('practitioner_ihs_number');
            }

            if (!Schema::hasColumn('users', 'satusehat_practitioner_role_id')) {
                $table->string('satusehat_practitioner_role_id')->nullable()->after('practitioner_ihs_number');
                $table->index('satusehat_practitioner_role_id', 'users_sat_role_id_idx');
            }

            if (!Schema::hasColumn('users', 'spesialisasi')) {
                $table->string('spesialisasi')->nullable()->after('satusehat_practitioner_role_id');
            }
        });

        Schema::table('kunjungan', function (Blueprint $table) {
            if (!Schema::hasColumn('kunjungan', 'satusehat_encounter_id')) {
                $table->string('satusehat_encounter_id')->nullable()->after('rumah_sakit_id');
                $table->index('satusehat_encounter_id');
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

        Schema::table('kunjungan', function (Blueprint $table) {
            if (Schema::hasColumn('kunjungan', 'satusehat_encounter_id')) {
                $table->dropIndex(['satusehat_encounter_id']);
                $table->dropColumn('satusehat_encounter_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'spesialisasi')) {
                $table->dropColumn('spesialisasi');
            }

            if (Schema::hasColumn('users', 'satusehat_practitioner_role_id')) {
                $table->dropIndex('users_sat_role_id_idx');
                $table->dropColumn('satusehat_practitioner_role_id');
            }

            if (Schema::hasColumn('users', 'practitioner_ihs_number')) {
                $table->dropIndex(['practitioner_ihs_number']);
                $table->dropColumn('practitioner_ihs_number');
            }
        });

        Schema::table('rumah_sakit', function (Blueprint $table) {
            if (Schema::hasColumn('rumah_sakit', 'organization_ihs_number')) {
                $table->dropIndex(['organization_ihs_number']);
                $table->dropColumn('organization_ihs_number');
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
