<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('soap', function (Blueprint $t) {
            $t->unsignedSmallInteger('td_sys')->nullable()->after('objektif'); // sistolik
            $t->unsignedSmallInteger('td_dia')->nullable()->after('td_sys');   // diastolik
            $t->unsignedSmallInteger('map')->nullable()->after('td_dia');      // Mean Arterial Pressure
        });
    }
    public function down(): void {
        Schema::table('soap', function (Blueprint $t) {
            $t->dropColumn(['td_sys','td_dia','map']);
        });
    }
};

