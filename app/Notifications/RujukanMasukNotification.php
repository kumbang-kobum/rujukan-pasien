<?php

namespace App\Notifications;

use App\Models\Rujukan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // optional: queue
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RujukanMasukNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Rujukan $rujukan,
        public User $pengirim
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // pastikan relasi ter-load (penting kalau pakai queue)
        $r = $this->rujukan->fresh()->loadMissing([
            'kunjungan.pasien', 'rsAsal', 'rsTujuan'
        ]);

        $noRawat = $r->kunjungan?->no_rawat ?? '-';
        $nmPasien = $r->kunjungan?->pasien?->nama ?? '-';
        // no RM bisa ada di pasien atau kunjungan, ambil yang ada
        $noRM = $r->kunjungan?->pasien?->no_rkm_medis
              ?? $r->kunjungan?->no_rkm_medis
              ?? '-';

        $namaAsal   = $r->rsAsal?->nama   ?? '(RS Asal tidak tersedia)';
        $namaTujuan = $r->rsTujuan?->nama ?? '(RS Tujuan tidak tersedia)';

        return (new MailMessage)
            ->replyTo($this->pengirim->email, $this->pengirim->name)
            ->subject("Rujukan Pasien: {$noRawat} ({$nmPasien})")
            ->greeting('Yth. '.$notifiable->name)
            ->line("Ada rujukan pasien dari {$namaAsal} ke {$namaTujuan}.")
            ->line("Pasien: {$nmPasien} | No. RM: {$noRM}")
            ->line('Alasan: '.($r->alasan ?: '-'))
            ->line('Detail: '.($r->alasan_rujukan ?: '-'))
            ->line('Catatan: '.($r->catatan ?: '-'))
            ->action('Lihat Rujukan', route('rujukan.show', $r));
    }
}
