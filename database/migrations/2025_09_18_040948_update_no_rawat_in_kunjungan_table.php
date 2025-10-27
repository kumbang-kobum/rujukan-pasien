<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('kunjungan', function (Blueprint $table) {
            if (!Schema::hasColumn('kunjungan', 'no_rawat')) {
                $table->string('no_rawat', 50)->unique()->after('id');
            } else {
                // Hanya ubah tipe jika belum unik
                // Jangan tambahkan unique index lagi kalau sudah ada
                // Tidak perlu .unique()->change()
            }
        });
    }


    public function down(): void {
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->dropUnique(['no_rawat']);
        });
    }
};