<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            if (!Schema::hasColumn('kunjungan', 'status_pulang')) {
                $table->boolean('status_pulang')->default(0)->after('keluhan_utama');
            }
            if (!Schema::hasColumn('kunjungan', 'waktu_pulang')) {
                $table->dateTime('waktu_pulang')->nullable()->after('status_pulang');
            }
            if (!Schema::hasColumn('kunjungan', 'no_rawat')) {
                $table->string('no_rawat', 50)->nullable()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->dropColumn(['status_pulang', 'waktu_pulang', 'no_rawat']);
        });
    }
};