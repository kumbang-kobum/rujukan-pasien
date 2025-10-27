<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            // Tambahkan kolom baru jika belum ada
            if (!Schema::hasColumn('kunjungan', 'dokter_id')) {
                $table->unsignedBigInteger('dokter_id')->after('pasien_id')->nullable();
            }
            if (!Schema::hasColumn('kunjungan', 'poli')) {
                $table->string('poli')->after('dokter_id')->nullable();
            }
            if (!Schema::hasColumn('kunjungan', 'tanggal_kunjungan')) {
                $table->date('tanggal_kunjungan')->after('poli')->nullable();
            }

            // Biarkan user_id bisa nullable supaya tidak error saat kosong
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            if (Schema::hasColumn('kunjungan', 'dokter_id')) {
                $table->dropColumn('dokter_id');
            }
            if (Schema::hasColumn('kunjungan', 'poli')) {
                $table->dropColumn('poli');
            }
            if (Schema::hasColumn('kunjungan', 'tanggal_kunjungan')) {
                $table->dropColumn('tanggal_kunjungan');
            }

            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};