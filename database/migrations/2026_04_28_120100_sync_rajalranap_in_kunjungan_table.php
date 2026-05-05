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
    }

    public function down(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            if (Schema::hasColumn('kunjungan', 'rajalranap')) {
                $table->dropColumn('rajalranap');
            }
        });
    }
};
