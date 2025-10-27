<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            if (!Schema::hasColumn('kunjungan','waktu_pulang')) {
                $table->dateTime('waktu_pulang')->nullable()->after('waktu_masuk');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            if (Schema::hasColumn('kunjungan','waktu_pulang')) {
                $table->dropColumn('waktu_pulang');
            }
        });
    }
};