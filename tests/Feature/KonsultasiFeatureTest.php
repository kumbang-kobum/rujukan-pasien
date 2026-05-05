<?php

namespace Tests\Feature;

use App\Models\Konsultasi;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\RumahSakit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KonsultasiFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_submit_consultation_and_target_can_reply_after_reading(): void
    {
        [$dokterAsal, $dokterTujuan, $kunjungan, $rsTujuan] = $this->makeConsultationFixtures();

        $response = $this->actingAs($dokterAsal)->post(route('konsultasi.store'), [
            'kunjungan_id' => $kunjungan->id,
            'rumah_sakit_tujuan_id' => $rsTujuan->id,
            'dokter_tujuan_id' => $dokterTujuan->id,
            'judul' => 'Konsultasi nyeri dada',
            'alasan_konsultasi' => 'Mohon second opinion untuk evaluasi nyeri dada akut.',
            'pertanyaan_konsultasi' => 'Perlu segera dirujuk atau observasi dahulu?',
            'ringkasan_klinis' => 'Nyeri dada sejak 2 jam yang lalu.',
            'diagnosis_kerja' => 'Suspek ACS',
            'terapi_berjalan' => 'Oksigen dan aspirin',
            'hasil_penunjang' => 'EKG awal non-spesifik',
            'consent_status' => 'diberikan',
            'consent_nama_pemberi' => 'Tn. Pasien',
            'consent_hubungan' => 'Pasien sendiri',
            'consent_metode' => 'tertulis',
            'consent_diberikan_pada' => now()->format('Y-m-d H:i:s'),
            'consent_catatan' => 'Pasien setuju data klinis dibagikan untuk konsultasi.',
            'submit_action' => 'submit',
        ]);

        $consultation = Konsultasi::first();

        $response->assertRedirect(route('konsultasi.show', $consultation));
        $this->assertNotNull($consultation);
        $this->assertSame(Konsultasi::STATUS_TERKIRIM, $consultation->status);

        $this->actingAs($dokterTujuan)
            ->get(route('konsultasi.show', $consultation))
            ->assertOk();

        $consultation->refresh();
        $this->assertSame(Konsultasi::STATUS_DIBACA, $consultation->status);

        $this->actingAs($dokterTujuan)
            ->post(route('konsultasi.reply', $consultation), [
                'tipe' => 'jawaban',
                'pesan' => 'Silakan observasi serial EKG dan enzim jantung, lalu pertimbangkan rujukan bila memburuk.',
            ])
            ->assertRedirect();

        $consultation->refresh();
        $this->assertSame(Konsultasi::STATUS_DIJAWAB, $consultation->status);

        $this->assertDatabaseHas('konsultasi_pesan', [
            'konsultasi_id' => $consultation->id,
            'pengirim_id' => $dokterTujuan->id,
            'tipe' => 'jawaban',
        ]);
    }

    public function test_consultation_can_be_escalated_to_official_referral(): void
    {
        [$dokterAsal, $dokterTujuan, $kunjungan, $rsTujuan, $rsAsal] = $this->makeConsultationFixtures();

        $consultation = Konsultasi::create([
            'kunjungan_id' => $kunjungan->id,
            'rumah_sakit_asal_id' => $rsAsal->id,
            'rumah_sakit_tujuan_id' => $rsTujuan->id,
            'dokter_pengirim_id' => $dokterAsal->id,
            'dokter_tujuan_id' => $dokterTujuan->id,
            'judul' => 'Konsultasi stroke akut',
            'ringkasan_klinis' => 'Hemiparese kanan onset 1 jam.',
            'diagnosis_kerja' => 'Suspek stroke iskemik akut',
            'terapi_berjalan' => 'Stabilisasi awal',
            'hasil_penunjang' => 'Belum CT scan',
            'alasan_konsultasi' => 'Perlu arahan tatalaksana lanjutan.',
            'pertanyaan_konsultasi' => 'Apakah pasien perlu segera dirujuk?',
            'status' => Konsultasi::STATUS_DIJAWAB,
            'consent_status' => Konsultasi::CONSENT_DIBERIKAN,
            'consent_nama_pemberi' => 'Ny. Keluarga',
            'consent_hubungan' => 'Istri',
            'consent_metode' => 'lisan',
            'consent_diberikan_pada' => now(),
            'submitted_at' => now(),
            'read_at' => now(),
        ]);

        $this->actingAs($dokterAsal)
            ->post(route('konsultasi.escalate', $consultation))
            ->assertRedirect();

        $consultation->refresh();

        $this->assertSame(Konsultasi::STATUS_DIRUJUK, $consultation->status);
        $this->assertNotNull($consultation->rujukan_id);

        $this->assertDatabaseHas('rujukan', [
            'id' => $consultation->rujukan_id,
            'kunjungan_id' => $kunjungan->id,
            'rumah_sakit_asal_id' => $rsAsal->id,
            'rumah_sakit_tujuan_id' => $rsTujuan->id,
            'dokter_tujuan_id' => $dokterTujuan->id,
        ]);
    }

    private function makeConsultationFixtures(): array
    {
        $rsAsal = RumahSakit::factory()->create(['nama' => 'RS Asal']);
        $rsTujuan = RumahSakit::factory()->create(['nama' => 'RS Tujuan']);

        $dokterAsal = User::factory()->create([
            'role' => 'dokter',
            'rumah_sakit_id' => $rsAsal->id,
        ]);

        $dokterTujuan = User::factory()->create([
            'role' => 'dokter',
            'rumah_sakit_id' => $rsTujuan->id,
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
            'no_rawat' => '2026/04/28/00001',
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokterAsal->id,
            'user_id' => $dokterAsal->id,
            'rumah_sakit_id' => $rsAsal->id,
            'poli' => 'Rawat Jalan',
            'rajalranap' => 'Rawat Jalan',
            'tanggal_kunjungan' => now()->toDateString(),
            'waktu_masuk' => now()->format('Y-m-d H:i:s'),
            'keluhan_utama' => 'Keluhan utama pasien',
            'status_pulang' => 0,
        ]);

        return [$dokterAsal, $dokterTujuan, $kunjungan, $rsTujuan, $rsAsal];
    }
}
