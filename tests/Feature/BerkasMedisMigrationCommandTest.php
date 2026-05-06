<?php

namespace Tests\Feature;

use App\Models\BerkasMedis;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\RumahSakit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BerkasMedisMigrationCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_dry_run_does_not_move_public_file(): void
    {
        [$berkas] = $this->makeBerkasFixture();

        $this->artisan('berkas:migrate-private')
            ->expectsOutputToContain('Mode simulasi')
            ->assertExitCode(0);

        Storage::disk('public')->assertExists($berkas->path);
        Storage::disk('local')->assertMissing($berkas->path);
    }

    public function test_execute_moves_public_file_to_private_disk(): void
    {
        [$berkas] = $this->makeBerkasFixture();

        $this->artisan('berkas:migrate-private', ['--execute' => true])
            ->assertExitCode(0);

        Storage::disk('local')->assertExists($berkas->path);
        Storage::disk('public')->assertMissing($berkas->path);

        $this->assertDatabaseHas('berkas_medis', [
            'id' => $berkas->id,
            'path' => $berkas->path,
        ]);
    }

    public function test_execute_removes_public_duplicate_when_private_file_already_exists(): void
    {
        [$berkas] = $this->makeBerkasFixture();
        Storage::disk('local')->put($berkas->path, 'private copy');

        $this->artisan('berkas:migrate-private', ['--execute' => true])
            ->assertExitCode(0);

        Storage::disk('local')->assertExists($berkas->path);
        Storage::disk('public')->assertMissing($berkas->path);
    }

    private function makeBerkasFixture(): array
    {
        Storage::fake('local');
        Storage::fake('public');

        $rs = RumahSakit::factory()->create(['nama' => 'RS Migrasi']);
        $dokter = User::factory()->create([
            'role' => User::ROLE_DOKTER,
            'rumah_sakit_id' => $rs->id,
        ]);

        $pasien = Pasien::create([
            'no_rkm_medis' => 'RM-MIG-001',
            'nik' => '3201010101010001',
            'nama' => 'Pasien Migrasi',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Migrasi No. 1',
            'telepon' => '081234567890',
        ]);

        $kunjungan = Kunjungan::create([
            'no_rawat' => '2026/05/06/MIG01',
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokter->id,
            'user_id' => $dokter->id,
            'rumah_sakit_id' => $rs->id,
            'rajalranap' => 'Rawat Jalan',
            'tanggal_kunjungan' => now()->toDateString(),
            'waktu_masuk' => now()->format('Y-m-d H:i:s'),
            'keluhan_utama' => 'Keluhan migrasi',
            'status_pulang' => 0,
        ]);

        $berkas = BerkasMedis::create([
            'kunjungan_id' => $kunjungan->id,
            'uploader_id' => $dokter->id,
            'kategori' => 'LAB',
            'nama_file' => 'hasil-migrasi.pdf',
            'path' => 'berkas/hasil-migrasi.pdf',
        ]);

        Storage::disk('public')->put($berkas->path, 'legacy public file');

        return [$berkas];
    }
}
