<?php

namespace Tests\Feature;

use App\Models\RumahSakit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationMenuRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_sees_platform_menu_only_for_management(): void
    {
        $superAdmin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
            'rumah_sakit_id' => null,
        ]);

        $this->actingAs($superAdmin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Platform')
            ->assertSee('Master Rumah Sakit')
            ->assertSee('Pengguna Platform')
            ->assertDontSee('Administrasi RS')
            ->assertDontSee('Pelayanan RS');
    }

    public function test_admin_rs_sees_rs_administration_and_clinical_menu(): void
    {
        $adminRs = $this->makeUser(User::ROLE_ADMIN_RS);

        $this->actingAs($adminRs)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Pelayanan RS')
            ->assertSee('Administrasi RS')
            ->assertSee('Pengguna RS')
            ->assertSee('Password Admin')
            ->assertSee('Konsultasi Dokter')
            ->assertDontSee('Master Rumah Sakit')
            ->assertDontSee('Pengguna Platform');
    }

    public function test_doctor_sees_consultation_but_not_admin_menu(): void
    {
        $dokter = $this->makeUser(User::ROLE_DOKTER);

        $this->actingAs($dokter)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Pelayanan RS')
            ->assertSee('Konsultasi Dokter')
            ->assertDontSee('Administrasi RS')
            ->assertDontSee('Pengguna RS')
            ->assertDontSee('Platform');
    }

    public function test_petugas_sees_clinical_menu_without_consultation_or_admin_menu(): void
    {
        $petugas = $this->makeUser(User::ROLE_PETUGAS);

        $this->actingAs($petugas)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Pelayanan RS')
            ->assertSee('Pasien')
            ->assertSee('Kunjungan')
            ->assertSee('SOAP')
            ->assertSee('Rujukan')
            ->assertDontSee('Konsultasi Dokter')
            ->assertDontSee('Administrasi RS')
            ->assertDontSee('Platform');
    }

    private function makeUser(string $role): User
    {
        $rs = RumahSakit::factory()->create(['nama' => 'RS Menu']);

        return User::factory()->create([
            'role' => $role,
            'rumah_sakit_id' => $rs->id,
        ]);
    }
}
