<?php

namespace Tests\Feature;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\RumahSakit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KunjunganFilterStateTest extends TestCase
{
    use RefreshDatabase;

    public function test_pulangkan_action_keeps_current_visit_filters_in_form_url(): void
    {
        [$admin, $kunjungan] = $this->makeVisitFixture();

        $filters = [
            'pasien' => 'Pasien Filter',
            'status' => 'rawat',
            'start_date' => '2025-12-01',
            'end_date' => '2026-05-08',
        ];

        $this->actingAs($admin)
            ->get(route('kunjungan.index', $filters))
            ->assertOk()
            ->assertSee('Pulangkan')
            ->assertSee("kunjungan/{$kunjungan->id}/pulangkan", false)
            ->assertSee('status=rawat', false)
            ->assertSee('start_date=2025-12-01', false)
            ->assertSee('end_date=2026-05-08', false);
    }

    public function test_pulangkan_redirects_back_to_filtered_visit_list(): void
    {
        [$admin, $kunjungan] = $this->makeVisitFixture();

        $filters = [
            'pasien' => 'Pasien Filter',
            'status' => 'rawat',
            'start_date' => '2025-12-01',
            'end_date' => '2026-05-08',
        ];

        $this->withSession(['_token' => 'test-token'])
            ->actingAs($admin)
            ->patch(route('kunjungan.pulangkan', array_merge(['kunjungan' => $kunjungan->id], $filters)), [
                '_token' => 'test-token',
            ])
            ->assertRedirect(route('kunjungan.index', $filters));

        $this->assertTrue($kunjungan->fresh()->status_pulang);
    }

    private function makeVisitFixture(): array
    {
        $rumahSakit = RumahSakit::factory()->create();

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN_RS,
            'rumah_sakit_id' => $rumahSakit->id,
        ]);

        $dokter = User::factory()->create([
            'role' => User::ROLE_DOKTER,
            'rumah_sakit_id' => $rumahSakit->id,
        ]);

        $pasien = Pasien::create([
            'no_rkm_medis' => 'RM-FILTER-001',
            'nik' => '3201010101012099',
            'nama' => 'Pasien Filter',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Filter No. 1',
            'telepon' => '081234567899',
        ]);

        $kunjungan = Kunjungan::create([
            'no_rawat' => '2026/05/08/00001',
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokter->id,
            'user_id' => $admin->id,
            'rumah_sakit_id' => $rumahSakit->id,
            'rajalranap' => 'Rawat Jalan',
            'tanggal_kunjungan' => '2026-05-07',
            'waktu_masuk' => '2026-05-07 08:00:00',
            'keluhan_utama' => 'Kontrol filter',
            'status_pulang' => 0,
        ]);

        return [$admin, $kunjungan];
    }
}
