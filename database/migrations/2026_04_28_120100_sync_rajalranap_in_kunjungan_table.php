<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            if (!Schema::hasColumn('kunjungan', 'rajalranap')) {
                $table->string('rajalranap')->nullable()->after('rumah_sakit_id');
            }
        });

        if (Schema::hasColumn('kunjungan', 'poli') && Schema::hasColumn('kunjungan', 'rajalranap')) {
            DB::table('kunjungan')
                ->whereNull('rajalranap')
                ->update([
                    'rajalranap' => DB::raw('poli'),
                ]);
        }

        if (Schema::hasColumn('kunjungan', 'rajalranap')) {
            DB::table('kunjungan')
                ->whereNull('rajalranap')
                ->update([
                    'rajalranap' => '',
                ]);
        }

        Schema::table('kunjungan', function (Blueprint $table) {
            if (Schema::hasColumn('kunjungan', 'poli')) {
                $table->dropColumn('poli');
            }
        });

        $driver = Schema::getConnection()->getDriverName();
        if (Schema::hasColumn('kunjungan', 'rajalranap') && in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE `kunjungan` MODIFY `rajalranap` varchar(255) NOT NULL');
        }
    }

    public function down(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            if (!Schema::hasColumn('kunjungan', 'poli')) {
                $table->string('poli')->nullable()->after('dokter_id');
            }
        });

        if (Schema::hasColumn('kunjungan', 'poli') && Schema::hasColumn('kunjungan', 'rajalranap')) {
            DB::table('kunjungan')
                ->whereNull('poli')
                ->update([
                    'poli' => DB::raw('rajalranap'),
                ]);
        }

        Schema::table('kunjungan', function (Blueprint $table) {
            if (Schema::hasColumn('kunjungan', 'rajalranap')) {
                $table->dropColumn('rajalranap');
            }
        });
    }
};
