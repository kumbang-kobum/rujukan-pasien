<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rujukan_dokter_cc', function (Blueprint $t) {
            $t->id();
            $t->foreignId('rujukan_id')->constrained('rujukan')->cascadeOnDelete();
            $t->foreignId('dokter_id')->constrained('users')->cascadeOnDelete();
            $t->timestamps();

            $t->unique(['rujukan_id','dokter_id']); // hindari duplikat
        });
    }
    public function down(): void {
        Schema::dropIfExists('rujukan_dokter_cc');
    }
};
