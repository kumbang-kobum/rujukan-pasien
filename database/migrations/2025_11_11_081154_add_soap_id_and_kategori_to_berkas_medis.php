<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    $hasJenis = Schema::hasColumn('berkas_medis', 'jenis');

    Schema::table('berkas_medis', function (Blueprint $t) use ($hasJenis) {
      if (!Schema::hasColumn('berkas_medis', 'soap_id')) {
        $t->foreignId('soap_id')->nullable()->after('kunjungan_id')
          ->constrained('soap')->nullOnDelete();
      }

      if (!Schema::hasColumn('berkas_medis', 'kategori')) {
        $column = $t->string('kategori', 20)->nullable();
        $hasJenis ? $column->after('jenis') : $column->after('uploader_id');
      }
    });

    if (Schema::hasColumn('berkas_medis', 'jenis')) {
      Schema::table('berkas_medis', function (Blueprint $t) {
        $t->dropColumn('jenis');
      });
    }
  }

  public function down(): void {
    Schema::table('berkas_medis', function (Blueprint $t) {
      if (Schema::hasColumn('berkas_medis', 'soap_id')) {
        $t->dropConstrainedForeignId('soap_id');
      }

      if (Schema::hasColumn('berkas_medis', 'kategori')) {
        $t->dropColumn('kategori');
      }
    });
  }
};
