<?php

namespace Tests\Feature;

use App\Models\BerkasMedis;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\RumahSakit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BerkasMedisAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_same_hospital_user_can_open_medical_file(): void
    {
        [$berkas, $dokterAsal] = $this->makeBerkasFixture();

        $this->actingAs($dokterAsal)
            ->get(route('berkas.file', $berkas))
            ->assertOk();
    }

    public function test_guest_is_redirected_from_medical_file_route(): void
    {
        [$berkas] = $this->makeBerkasFixture();

        $this->get(route('berkas.file', $berkas))
            ->assertRedirect(route('login'));
    }

    public function test_other_hospital_user_cannot_open_medical_file(): void
    {
        [$berkas,, $rsLain] = $this->makeBerkasFixture();

        $dokterLain = User::factory()->create([
            'role' => User::ROLE_DOKTER,
            'rumah_sakit_id' => $rsLain->id,
        ]);

        $this->actingAs($dokterLain)
            ->get(route('berkas.file', $berkas))
            ->assertForbidden();
    }

    public function test_old_public_medical_file_route_is_not_available(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('berkas/hasil-lab.pdf', 'dummy pdf');

        $this->get('/berkas/file/hasil-lab.pdf')
            ->assertNotFound();
    }

    public function test_legacy_public_disk_file_still_requires_secure_route(): void
    {
        [$berkas, $dokterAsal] = $this->makeBerkasFixture('public');

        $this->actingAs($dokterAsal)
            ->get(route('berkas.file', $berkas))
            ->assertOk();
    }

    public function test_uploaded_medical_file_is_stored_on_private_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        [$kunjungan, $dokterAsal] = $this->makeVisitFixture();

        $this->actingAs($dokterAsal)
            ->post(route('berkas.store'), [
                'kunjungan_id' => $kunjungan->id,
                'kategori' => 'LAB',
                'file' => UploadedFile::fake()->create('hasil-lab.pdf', 10, 'application/pdf'),
            ])
            ->assertRedirect(route('kunjungan.show', $kunjungan));

        $berkas = BerkasMedis::firstOrFail();

        Storage::disk('local')->assertExists($berkas->path);
        Storage::disk('public')->assertMissing($berkas->path);
    }

    public function test_soap_attachment_upload_is_stored_on_private_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        [$kunjungan, $dokterAsal] = $this->makeVisitFixture();

        $this->actingAs($dokterAsal)
            ->post(route('soap.store'), [
                'kunjungan_id' => $kunjungan->id,
                'subjektif' => 'Keluhan pasien',
                'objektif' => 'Pemeriksaan awal',
                'lampiran_kategori' => ['LAB'],
                'lampiran_file' => [
                    UploadedFile::fake()->create('hasil-soap.pdf', 10, 'application/pdf'),
                ],
            ])
            ->assertRedirect(route('soap.index'));

        $berkas = BerkasMedis::firstOrFail();

        Storage::disk('local')->assertExists($berkas->path);
        Storage::disk('public')->assertMissing($berkas->path);
    }

    private function makeBerkasFixture(string $disk = 'local'): array
    {
        Storage::fake($disk);
        Storage::disk($disk)->put('berkas/hasil-lab.pdf', 'dummy pdf');

        [$kunjungan, $dokterAsal, $rsLain] = $this->makeVisitFixture();

        $berkas = BerkasMedis::create([
            'kunjungan_id' => $kunjungan->id,
            'uploader_id' => $dokterAsal->id,
            'kategori' => 'LAB',
            'nama_file' => 'hasil-lab.pdf',
            'path' => 'berkas/hasil-lab.pdf',
        ]);

        return [$berkas, $dokterAsal, $rsLain];
    }

    private function makeVisitFixture(): array
    {
        $rsAsal = RumahSakit::factory()->create(['nama' => 'RS Asal']);
        $rsLain = RumahSakit::factory()->create(['nama' => 'RS Lain']);

        $dokterAsal = User::factory()->create([
            'role' => User::ROLE_DOKTER,
            'rumah_sakit_id' => $rsAsal->id,
        ]);

        $pasien = Pasien::create([
            'no_rkm_medis' => 'RM-001',
            'nik' => '1234567890123456',
            'nama' => 'Pasien Uji',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Pengujian No. 1',
            'telepon' => '081234567890',
        ]);

        $kunjungan = Kunjungan::create([
            'no_rawat' => '2026/05/06/00001',
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokterAsal->id,
            'user_id' => $dokterAsal->id,
            'rumah_sakit_id' => $rsAsal->id,
            'rajalranap' => 'Rawat Jalan',
            'tanggal_kunjungan' => now()->toDateString(),
            'waktu_masuk' => now()->format('Y-m-d H:i:s'),
            'keluhan_utama' => 'Keluhan utama pasien',
            'status_pulang' => 0,
        ]);

        return [$kunjungan, $dokterAsal, $rsLain];
    }
}
