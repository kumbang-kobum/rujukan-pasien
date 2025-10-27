<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rujukan', function (Blueprint $table) {
            if (!Schema::hasColumn('rujukan', 'rumah_sakit_tujuan_id')) {
                $table->unsignedBigInteger('rumah_sakit_tujuan_id')->nullable()->after('kunjungan_id');
            }
            if (!Schema::hasColumn('rujukan', 'catatan')) {
                $table->text('catatan')->nullable()->after('rumah_sakit_tujuan_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rujukan', function (Blueprint $table) {
            $table->dropColumn(['rumah_sakit_tujuan_id', 'catatan']);
        });
    }
};