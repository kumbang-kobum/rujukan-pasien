<?php

namespace Tests\Feature;

use App\Models\Konsultasi;
use App\Models\KonsultasiPesan;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\RumahSakit;
use App\Models\User;
use App\Notifications\KonsultasiActivityNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class KonsultasiWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_submitted_consultation_creates_database_notification_for_target_doctor(): void
    {
        [$dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan] = $this->createDoctors();
        $pasien = $this->createPasien();
        $kunjungan = $this->createKunjungan($pasien, $dokterPengirim, $rsAsal);

        $response = $this
            ->actingAs($dokterPengirim)
            ->post(route('konsultasi.store'), [
                'kunjungan_id' => $kunjungan->id,
                'rumah_sakit_tujuan_id' => $rsTujuan->id,
                'dokter_tujuan_id' => $dokterTujuan->id,
                'judul' => 'Konsultasi PPOK',
                'urgensi' => 'segera',
                'alasan_konsultasi' => 'Butuh second opinion pulmonologi.',
                'pertanyaan_klinis' => 'Apakah perlu rawat intensif?',
                'ringkasan_klinis' => 'Sesak meningkat sejak pagi.',
                'diagnosis_kerja' => 'Eksaserbasi PPOK',
                'hasil_penunjang' => 'Analisa gas darah menunjukkan hipoksemia.',
                'terapi_berjalan' => 'Nebulizer dan oksigen nasal cannula.',
                'consent_confirmed' => '1',
                'consent_granted_by_name' => 'Keluarga Pasien',
                'consent_granted_by_role' => 'Keluarga inti',
                'consent_method' => 'tertulis',
                'consent_granted_at' => now()->format('Y-m-d H:i:s'),
                'consent_notes' => 'Consent untuk konsultasi antar rumah sakit.',
                'action' => 'submit',
            ]);

        $konsultasi = Konsultasi::first();

        $response
            ->assertRedirect(route('konsultasi.show', $konsultasi));

        $dokterTujuan->refresh();

        $this->assertSame(1, $dokterTujuan->unreadNotifications()->count());
        $this->assertSame('Konsultasi baru masuk', $dokterTujuan->unreadNotifications()->first()->data['title']);
    }

    public function test_target_doctor_opening_submitted_consultation_marks_it_as_read(): void
    {
        [$dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan] = $this->createDoctors();
        $pasien = $this->createPasien();
        $kunjungan = $this->createKunjungan($pasien, $dokterPengirim, $rsAsal);
        $konsultasi = $this->createKonsultasi($kunjungan, $dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan, Konsultasi::STATUS_SUBMITTED);

        $this->actingAs($dokterTujuan)
            ->get(route('konsultasi.show', $konsultasi))
            ->assertOk();

        $this->assertSame(Konsultasi::STATUS_READ, $konsultasi->fresh()->status);
    }

    public function test_target_doctor_cannot_view_consultation_that_is_still_waiting_for_consent(): void
    {
        [$dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan] = $this->createDoctors();
        $pasien = $this->createPasien();
        $kunjungan = $this->createKunjungan($pasien, $dokterPengirim, $rsAsal);
        $konsultasi = $this->createKonsultasi($kunjungan, $dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan, Konsultasi::STATUS_AWAITING_CONSENT);

        $this->actingAs($dokterTujuan)
            ->get(route('konsultasi.show', $konsultasi))
            ->assertForbidden();
    }

    public function test_source_doctor_cannot_send_answer_message_type(): void
    {
        [$dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan] = $this->createDoctors();
        $pasien = $this->createPasien();
        $kunjungan = $this->createKunjungan($pasien, $dokterPengirim, $rsAsal);
        $konsultasi = $this->createKonsultasi($kunjungan, $dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan, Konsultasi::STATUS_ACCEPTED);

        $response = $this
            ->actingAs($dokterPengirim)
            ->from(route('konsultasi.show', $konsultasi))
            ->post(route('konsultasi.balas', $konsultasi), [
                'jenis_pesan' => 'answer',
                'isi_pesan' => 'Ini jawaban dari pengirim yang seharusnya tidak valid.',
            ]);

        $response
            ->assertRedirect(route('konsultasi.show', $konsultasi))
            ->assertSessionHasErrors('jenis_pesan');
    }

    public function test_target_doctor_can_accept_after_consultation_is_read(): void
    {
        [$dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan] = $this->createDoctors();
        $pasien = $this->createPasien();
        $kunjungan = $this->createKunjungan($pasien, $dokterPengirim, $rsAsal);
        $konsultasi = $this->createKonsultasi($kunjungan, $dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan, Konsultasi::STATUS_READ);

        $this->actingAs($dokterTujuan)
            ->patch(route('konsultasi.ubahStatus', [$konsultasi, Konsultasi::STATUS_ACCEPTED]))
            ->assertRedirect();

        $this->assertSame(Konsultasi::STATUS_ACCEPTED, $konsultasi->fresh()->status);
        $this->assertNotNull($konsultasi->fresh()->accepted_at);
    }

    public function test_target_reply_updates_status_and_notifies_source_doctor_from_read_status(): void
    {
        [$dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan] = $this->createDoctors();
        $pasien = $this->createPasien();
        $kunjungan = $this->createKunjungan($pasien, $dokterPengirim, $rsAsal);
        $konsultasi = $this->createKonsultasi($kunjungan, $dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan, Konsultasi::STATUS_READ);

        $this->actingAs($dokterTujuan)
            ->post(route('konsultasi.balas', $konsultasi), [
                'jenis_pesan' => 'answer',
                'isi_pesan' => 'Pasien disarankan evaluasi ICU dan pertimbangkan NIV.',
            ])
            ->assertRedirect();

        $this->assertSame(Konsultasi::STATUS_ANSWERED, $konsultasi->fresh()->status);
        $this->assertSame(1, $dokterPengirim->fresh()->unreadNotifications()->count());
        $this->assertSame('Jawaban konsultasi masuk', $dokterPengirim->fresh()->unreadNotifications()->first()->data['title']);
    }

    public function test_opening_consultation_marks_messages_and_notifications_as_read(): void
    {
        [$dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan] = $this->createDoctors();
        $pasien = $this->createPasien();
        $kunjungan = $this->createKunjungan($pasien, $dokterPengirim, $rsAsal);
        $konsultasi = $this->createKonsultasi($kunjungan, $dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan, Konsultasi::STATUS_ACCEPTED);

        $pesan = KonsultasiPesan::create([
            'konsultasi_id' => $konsultasi->id,
            'pengirim_id' => $dokterTujuan->id,
            'jenis_pesan' => 'answer',
            'isi_pesan' => 'Perlu observasi ketat 24 jam.',
            'status' => 'sent',
        ]);

        $dokterPengirim->notify(new KonsultasiActivityNotification(
            $konsultasi,
            'Jawaban konsultasi masuk',
            'Ada balasan baru untuk konsultasi ' . $konsultasi->no_konsultasi . '.',
            'consultation_reply',
            $dokterTujuan
        ));

        $this->actingAs($dokterPengirim)
            ->get(route('konsultasi.show', $konsultasi))
            ->assertOk();

        $this->assertNotNull($pesan->fresh()->dibaca_at);
        $this->assertSame(0, $dokterPengirim->fresh()->unreadNotifications()->count());
    }

    private function createDoctors(): array
    {
        $rsAsal = RumahSakit::factory()->create();
        $rsTujuan = RumahSakit::factory()->create();

        $dokterPengirim = User::factory()->create([
            'role' => 'dokter',
            'rumah_sakit_id' => $rsAsal->id,
            'practitioner_ihs_number' => 'prac-pengirim-001',
            'satusehat_practitioner_role_id' => 'role-pengirim-001',
        ]);

        $dokterTujuan = User::factory()->create([
            'role' => 'dokter',
            'rumah_sakit_id' => $rsTujuan->id,
            'practitioner_ihs_number' => 'prac-tujuan-001',
            'satusehat_practitioner_role_id' => 'role-tujuan-001',
        ]);

        return [$dokterPengirim, $dokterTujuan, $rsAsal, $rsTujuan];
    }

    private function createPasien(): Pasien
    {
        return Pasien::create([
            'no_rkm_medis' => 'RM-' . fake()->unique()->numerify('#####'),
            'nik' => fake()->unique()->numerify('################'),
            'patient_ihs_number' => 'ihs-' . fake()->unique()->numerify('######'),
            'nama' => fake()->name(),
            'tanggal_lahir' => '1990-01-01',
            'tempat_lahir' => 'Jakarta',
            'alamat' => 'Jl. Sehat No. 1',
            'jenis_kelamin' => 'L',
            'telepon' => '08123456789',
        ]);
    }

    private function createKunjungan(Pasien $pasien, User $dokter, RumahSakit $rumahSakit): Kunjungan
    {
        $id = DB::table('kunjungan')->insertGetId([
            'no_rawat' => 'RAWAT-' . fake()->unique()->numerify('######'),
            'pasien_id' => $pasien->id,
            'dokter_id' => $dokter->id,
            'user_id' => $dokter->id,
            'rumah_sakit_id' => $rumahSakit->id,
            'poli' => 'Penyakit Dalam',
            'tanggal_kunjungan' => now()->toDateString(),
            'waktu_masuk' => now(),
            'keluhan_utama' => 'Sesak napas',
            'satusehat_encounter_id' => 'enc-' . fake()->unique()->numerify('######'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Kunjungan::findOrFail($id);
    }

    private function createKonsultasi(
        Kunjungan $kunjungan,
        User $dokterPengirim,
        User $dokterTujuan,
        RumahSakit $rsAsal,
        RumahSakit $rsTujuan,
        string $status
    ): Konsultasi {
        $konsultasi = Konsultasi::create([
            'no_konsultasi' => 'KON-TEST-' . fake()->unique()->numerify('####'),
            'kunjungan_id' => $kunjungan->id,
            'pasien_id' => $kunjungan->pasien_id,
            'rumah_sakit_asal_id' => $rsAsal->id,
            'rumah_sakit_tujuan_id' => $rsTujuan->id,
            'dokter_pengirim_id' => $dokterPengirim->id,
            'dokter_tujuan_id' => $dokterTujuan->id,
            'patient_ihs_number' => $kunjungan->pasien->patient_ihs_number,
            'organization_ihs_asal' => 'org-asal-001',
            'organization_ihs_tujuan' => 'org-tujuan-001',
            'practitioner_ihs_pengirim' => $dokterPengirim->practitioner_ihs_number,
            'practitioner_ihs_tujuan' => $dokterTujuan->practitioner_ihs_number,
            'practitioner_role_pengirim' => $dokterPengirim->satusehat_practitioner_role_id,
            'practitioner_role_tujuan' => $dokterTujuan->satusehat_practitioner_role_id,
            'encounter_satusehat_id' => $kunjungan->satusehat_encounter_id,
            'judul' => 'Konsultasi Test',
            'urgensi' => 'rutin',
            'alasan_konsultasi' => 'Mohon second opinion.',
            'pertanyaan_klinis' => 'Apa langkah berikutnya?',
            'ringkasan_klinis' => 'Kondisi stabil namun perlu evaluasi.',
            'diagnosis_kerja' => 'Bronkopneumonia',
            'hasil_penunjang' => 'Foto toraks infiltrat bilateral.',
            'terapi_berjalan' => 'Antibiotik empiris.',
            'consent_status' => 'disetujui',
            'consent_granted_by_name' => 'Keluarga',
            'consent_granted_by_role' => 'Keluarga inti',
            'consent_method' => 'tertulis',
            'consent_granted_at' => now(),
            'status' => $status,
            'submitted_at' => now(),
            'accepted_at' => $status === Konsultasi::STATUS_ACCEPTED ? now() : null,
            'answered_at' => $status === Konsultasi::STATUS_ANSWERED ? now() : null,
            'last_message_at' => now(),
        ]);

        KonsultasiPesan::create([
            'konsultasi_id' => $konsultasi->id,
            'pengirim_id' => $dokterPengirim->id,
            'jenis_pesan' => 'question',
            'isi_pesan' => 'Mohon second opinion untuk pasien ini.',
            'status' => 'sent',
        ]);

        return $konsultasi;
    }
}
