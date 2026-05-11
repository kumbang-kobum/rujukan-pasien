<?php

namespace Tests\Feature;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\RumahSakit;
use App\Models\User;
use App\Notifications\RujukanMasukNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RujukanNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_referral_sends_notification_to_target_doctor_and_cc(): void
    {
        Notification::fake();

        [$dokterAsal, $dokterTujuan, $dokterCc, $kunjungan, $rsAsal, $rsTujuan] = $this->makeReferralFixtures();

        $this->actingAs($dokterAsal)
            ->post(route('rujukan.store'), [
                'kunjungan_id' => $kunjungan->id,
                'rumah_sakit_asal_id' => $rsAsal->id,
                'rumah_sakit_tujuan_id' => $rsTujuan->id,
                'dokter_tujuan_id' => $dokterTujuan->id,
                'dokter_cc_ids' => [$dokterCc->id],
                'alasan' => 'Butuh layanan spesialis',
                'alasan_rujukan' => 'Mohon evaluasi dan tata laksana lanjutan.',
                'catatan' => 'Pasien stabil saat dirujuk.',
            ])
            ->assertRedirect(route('rujukan.index'));

        Notification::assertSentTo($dokterTujuan, RujukanMasukNotification::class);
        Notification::assertSentTo($dokterCc, RujukanMasukNotification::class);
    }

    public function test_referral_email_uses_patient_name_from_pasien_model(): void
    {
        Notification::fake();

        [$dokterAsal, $dokterTujuan,, $kunjungan, $rsAsal, $rsTujuan] = $this->makeReferralFixtures();

        $this->actingAs($dokterAsal)
            ->post(route('rujukan.store'), [
                'kunjungan_id' => $kunjungan->id,
                'rumah_sakit_asal_id' => $rsAsal->id,
                'rumah_sakit_tujuan_id' => $rsTujuan->id,
                'dokter_tujuan_id' => $dokterTujuan->id,
                'alasan' => 'Butuh layanan spesialis',
                'alasan_rujukan' => 'Mohon evaluasi dan tata laksana lanjutan.',
            ]);

        Notification::assertSentTo($dokterTujuan, RujukanMasukNotification::class, function ($notification) use ($dokterTujuan) {
            $mail = $notification->toMail($dokterTujuan);

            return str_contains($mail->subject, 'Pasien Rujukan')
                && collect($mail->introLines)->contains(fn ($line) => str_contains($line, 'Pasien Rujukan'));
        });
    }

    private function makeReferralFixtures(): array
    {
        $rsAsal = RumahSakit::factory()->create(['nama' => 'RS Asal']);
        $rsTujuan = RumahSakit::factory()->create(['nama' => 'RS Tujuan']);

        $dokterAsal = User::factory()->create([
            'role' => User::ROLE_DOKTER,
            'rumah_sakit_id' => $rsAsal->id,
        ]);

        $dokterTujuan = User::factory()->create([
            'role' => User::ROLE_DOKTER,
            'rumah_sakit_id' => $rsTujuan->id,
        ]);

        $dokterCc = User::factory()->create([
            'role' => User::ROLE_DOKTER,
            'rumah_sakit_id' => $rsTujuan->id,
        ]);

        $pasien = Pasien::create([
            'no_rkm_medis' => 'RM-RJK-001',
            'nik' => '3201010101012001',
            'nama' => 'Pasien Rujukan',
            'tempat_lahir' => 'Padang',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Rujukan No. 1',
            'telepon' => '081234567890',
        ]);

        $kunjungan = Kunjungan::create([
            'no_rawat' => '2026/05/07/RJK01',
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokterAsal->id,
            'user_id' => $dokterAsal->id,
            'rumah_sakit_id' => $rsAsal->id,
            'rajalranap' => 'Rawat Jalan',
            'tanggal_kunjungan' => now()->toDateString(),
            'waktu_masuk' => now()->format('Y-m-d H:i:s'),
            'keluhan_utama' => 'Keluhan utama rujukan',
            'status_pulang' => 0,
        ]);

        return [$dokterAsal, $dokterTujuan, $dokterCc, $kunjungan, $rsAsal, $rsTujuan];
    }
}
