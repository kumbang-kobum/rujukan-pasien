<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('soap', function (Blueprint $t) {
            $t->text('advice')->nullable()->after('plan');
        });
    }

    public function down(): void
    {
        Schema::table('soap', function (Blueprint $t) {
            $t->dropColumn('advice');
        });
    }
};
