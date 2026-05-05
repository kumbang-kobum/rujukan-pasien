<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::table('berkas_medis', function (Blueprint $t) {
      $t->foreignId('soap_id')->nullable()->after('kunjungan_id')
        ->constrained('soap')->nullOnDelete();
      $t->string('kategori', 20)->nullable()->after('jenis'); // USG|LAB|LAIN
    });
  }
  public function down(): void {
    Schema::table('berkas_medis', function (Blueprint $t) {
      $t->dropConstrainedForeignId('soap_id');
      $t->dropColumn('kategori');
    });
  }
};

