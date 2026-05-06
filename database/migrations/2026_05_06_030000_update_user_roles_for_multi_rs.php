<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','perawat','dokter','super_admin','admin_rs','petugas') NOT NULL DEFAULT 'dokter'");
            DB::statement('ALTER TABLE `users` MODIFY `rumah_sakit_id` bigint unsigned NULL');
        }

        DB::table('users')->where('role', 'admin')->update(['role' => User::ROLE_ADMIN_RS]);
        DB::table('users')->where('role', 'perawat')->update(['role' => User::ROLE_PETUGAS]);

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','admin_rs','dokter','petugas') NOT NULL DEFAULT 'dokter'");
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('super_admin','admin_rs','dokter','petugas','admin','perawat') NOT NULL DEFAULT 'dokter'");
        }

        DB::table('users')->where('role', User::ROLE_SUPER_ADMIN)->update(['role' => 'admin']);
        DB::table('users')->where('role', User::ROLE_ADMIN_RS)->update(['role' => 'admin']);
        DB::table('users')->where('role', User::ROLE_PETUGAS)->update(['role' => 'perawat']);

        $fallbackRsId = DB::table('rumah_sakit')->orderBy('id')->value('id');
        if ($fallbackRsId) {
            DB::table('users')->whereNull('rumah_sakit_id')->update(['rumah_sakit_id' => $fallbackRsId]);
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('dokter','perawat','admin') NOT NULL DEFAULT 'dokter'");
            DB::statement('ALTER TABLE `users` MODIFY `rumah_sakit_id` bigint unsigned NOT NULL');
        }
    }
};
