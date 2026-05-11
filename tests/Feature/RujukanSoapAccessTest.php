<?php

namespace Tests\Feature;

use App\Models\BerkasMedis;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Rujukan;
use App\Models\RumahSakit;
use App\Models\SOAP;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RujukanSoapAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_target_hospital_can_see_source_soap_after_referral_is_accepted(): void
    {
        [$rujukan,, $soapAsal, $dokterTujuan] = $this->makeAcceptedReferralFixture();

        $this->actingAs($dokterTujuan)
            ->get(route('rujukan.show', $rujukan))
            ->assertOk()
            ->assertSee('Catatan SOAP dari RS Asal')
            ->assertSee($soapAsal->subjektif)
            ->assertSee('Tambah SOAP RS Tujuan');
    }

    public function test_target_petugas_can_open_prefilled_form_and_add_soap_to_accepted_referral(): void
    {
        [$rujukan, $kunjungan,, , $petugasTujuan] = $this->makeAcceptedReferralFixture();

        $this->actingAs($petugasTujuan)
            ->get(route('soap.create', ['kunjungan_id' => $kunjungan->id]))
            ->assertOk()
            ->assertSee('value="'.$kunjungan->id.'" selected', false);

        $this->actingAs($petugasTujuan)
            ->post(route('soap.store'), [
                'kunjungan_id' => $kunjungan->id,
                'subjektif' => 'Catatan subjektif dari RS tujuan',
                'objektif' => 'Pemeriksaan RS tujuan',
                'assessment' => 'Assessment RS tujuan',
                'plan' => 'Plan RS tujuan',
            ])
            ->assertRedirect(route('soap.index'));

        $this->assertDatabaseHas('soap', [
            'kunjungan_id' => $rujukan->kunjungan_id,
            'user_id' => $petugasTujuan->id,
            'subjektif' => 'Catatan subjektif dari RS tujuan',
        ]);
    }

    public function test_target_hospital_can_open_source_soap_attachment_after_referral_is_accepted(): void
    {
        Storage::fake('local');

        [$rujukan,, $soapAsal, $dokterTujuan] = $this->makeAcceptedReferralFixture();
        Storage::disk('local')->put('berkas/hasil-rs-asal.pdf', 'dummy pdf');

        $berkas = BerkasMedis::create([
            'kunjungan_id' => $rujukan->kunjungan_id,
            'soap_id' => $soapAsal->id,
            'uploader_id' => $soapAsal->user_id,
            'kategori' => 'LAB',
            'nama_file' => 'hasil-rs-asal.pdf',
            'path' => 'berkas/hasil-rs-asal.pdf',
        ]);

        $this->actingAs($dokterTujuan)
            ->get(route('soap.show', $soapAsal))
            ->assertOk()
            ->assertSee('hasil-rs-asal.pdf');

        $this->actingAs($dokterTujuan)
            ->get(route('berkas.file', $berkas))
            ->assertOk();
    }

    private function makeAcceptedReferralFixture(): array
    {
        $rsAsal = RumahSakit::factory()->create(['nama' => 'RS Asal']);
        $rsTujuan = RumahSakit::factory()->create(['nama' => 'RS Tujuan']);

        $dokterAsal = User::factory()->create([
            'name' => 'Dokter RS Asal',
            'role' => User::ROLE_DOKTER,
            'rumah_sakit_id' => $rsAsal->id,
        ]);

        $dokterTujuan = User::factory()->create([
            'name' => 'Dokter RS Tujuan',
            'role' => User::ROLE_DOKTER,
            'rumah_sakit_id' => $rsTujuan->id,
        ]);

        $petugasTujuan = User::factory()->create([
            'name' => 'Petugas RS Tujuan',
            'role' => User::ROLE_PETUGAS,
            'rumah_sakit_id' => $rsTujuan->id,
        ]);

        $pasien = Pasien::create([
            'no_rkm_medis' => 'RM-RUJUKAN-001',
            'nik' => '1234567890123456',
            'nama' => 'Pasien Rujukan SOAP',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Rujukan No. 1',
            'telepon' => '081234567890',
        ]);

        $kunjungan = Kunjungan::create([
            'no_rawat' => '2026/05/11/00001',
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokterAsal->id,
            'user_id' => $dokterAsal->id,
            'rumah_sakit_id' => $rsAsal->id,
            'rajalranap' => 'Rawat Jalan',
            'tanggal_kunjungan' => '2026-05-11',
            'waktu_masuk' => '2026-05-11 08:00:00',
            'keluhan_utama' => 'Keluhan sebelum dirujuk',
            'status_pulang' => 0,
        ]);

        $soapAsal = SOAP::create([
            'kunjungan_id' => $kunjungan->id,
            'user_id' => $dokterAsal->id,
            'subjektif' => 'Keluhan dari RS asal',
            'objektif' => 'Objektif dari RS asal',
            'assessment' => 'Assessment dari RS asal',
            'plan' => 'Plan dari RS asal',
        ]);

        $rujukan = Rujukan::create([
            'kunjungan_id' => $kunjungan->id,
            'rumah_sakit_asal_id' => $rsAsal->id,
            'rumah_sakit_tujuan_id' => $rsTujuan->id,
            'dokter_tujuan_id' => $dokterTujuan->id,
            'alasan' => 'Butuh rawat lanjut',
            'alasan_rujukan' => 'Butuh evaluasi lanjutan',
            'catatan' => 'Rujukan uji akses SOAP',
            'status' => 'diterima',
            'penerima_id' => $dokterTujuan->id,
        ]);

        return [$rujukan, $kunjungan, $soapAsal, $dokterTujuan, $petugasTujuan];
    }
}
