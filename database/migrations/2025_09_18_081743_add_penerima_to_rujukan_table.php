<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('rujukan', function (Blueprint $table) {
        $table->foreignId('penerima_id')->nullable()->constrained('users')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('rujukan', function (Blueprint $table) {
        $table->dropConstrainedForeignId('penerima_id');
    });
}
};
