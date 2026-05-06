<?php

namespace Tests\Feature;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Rujukan;
use App\Models\RumahSakit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_rs_dashboard_is_scoped_to_own_hospital(): void
    {
        [$rsA, $rsB, $adminA, $dokterA, $dokterB, $kunjunganA, $kunjunganB] = $this->makeDashboardFixtures();

        Rujukan::create([
            'kunjungan_id' => $kunjunganA->id,
            'rumah_sakit_asal_id' => $rsA->id,
            'rumah_sakit_tujuan_id' => $rsB->id,
            'dokter_tujuan_id' => $dokterB->id,
            'alasan_rujukan' => 'Rujukan dari A ke B',
            'status' => 'menunggu',
        ]);

        Rujukan::create([
            'kunjungan_id' => $kunjunganB->id,
            'rumah_sakit_asal_id' => $rsB->id,
            'rumah_sakit_tujuan_id' => $rsA->id,
            'dokter_tujuan_id' => $dokterA->id,
            'alasan_rujukan' => 'Rujukan dari B ke A',
            'status' => 'menunggu',
        ]);

        $this->actingAs($adminA)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('scopeLabel', 'RS A')
            ->assertViewHas('pasienCount', 1)
            ->assertViewHas('rujukanKirimCount', 1)
            ->assertViewHas('rujukanTerimaCount', 1)
            ->assertSee('Pasien RS Saya');
    }

    public function test_super_admin_dashboard_sees_platform_totals(): void
    {
        $this->makeDashboardFixtures();

        $superAdmin = User::factory()->create([
            'role' => User::ROLE_SUPER_ADMIN,
            'rumah_sakit_id' => null,
        ]);

        $this->actingAs($superAdmin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('scopeLabel', 'Platform semua rumah sakit')
            ->assertViewHas('pasienCount', 2)
            ->assertViewHas('rujukanTerimaLabel', 'Total Rumah Sakit')
            ->assertSee('Total Pasien');
    }

    private function makeDashboardFixtures(): array
    {
        $rsA = RumahSakit::factory()->create(['nama' => 'RS A']);
        $rsB = RumahSakit::factory()->create(['nama' => 'RS B']);

        $adminA = User::factory()->create([
            'role' => User::ROLE_ADMIN_RS,
            'rumah_sakit_id' => $rsA->id,
        ]);

        $dokterA = User::factory()->create([
            'role' => User::ROLE_DOKTER,
            'rumah_sakit_id' => $rsA->id,
        ]);

        $dokterB = User::factory()->create([
            'role' => User::ROLE_DOKTER,
            'rumah_sakit_id' => $rsB->id,
        ]);

        $pasienA = Pasien::create([
            'no_rkm_medis' => 'RM-DASH-A',
            'nik' => '3201010101011001',
            'nama' => 'Pasien Dashboard A',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Dashboard A',
            'telepon' => '081111111111',
        ]);

        $pasienB = Pasien::create([
            'no_rkm_medis' => 'RM-DASH-B',
            'nik' => '3201010101011002',
            'nama' => 'Pasien Dashboard B',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1991-01-01',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Dashboard B',
            'telepon' => '082222222222',
        ]);

        $kunjunganA = Kunjungan::create([
            'no_rawat' => '2026/05/06/DASH-A',
            'pasien_id' => $pasienA->id,
            'dokter_id' => $dokterA->id,
            'user_id' => $dokterA->id,
            'rumah_sakit_id' => $rsA->id,
            'rajalranap' => 'Rawat Jalan',
            'tanggal_kunjungan' => now()->toDateString(),
            'waktu_masuk' => now()->format('Y-m-d H:i:s'),
            'keluhan_utama' => 'Keluhan A',
            'status_pulang' => 0,
        ]);

        $kunjunganB = Kunjungan::create([
            'no_rawat' => '2026/05/06/DASH-B',
            'pasien_id' => $pasienB->id,
            'dokter_id' => $dokterB->id,
            'user_id' => $dokterB->id,
            'rumah_sakit_id' => $rsB->id,
            'rajalranap' => 'Rawat Jalan',
            'tanggal_kunjungan' => now()->toDateString(),
            'waktu_masuk' => now()->format('Y-m-d H:i:s'),
            'keluhan_utama' => 'Keluhan B',
            'status_pulang' => 0,
        ]);

        return [$rsA, $rsB, $adminA, $dokterA, $dokterB, $kunjunganA, $kunjunganB];
    }
}
