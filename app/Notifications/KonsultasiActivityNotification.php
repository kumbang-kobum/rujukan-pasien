<?php

namespace App\Notifications;

use App\Models\Konsultasi;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KonsultasiActivityNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Konsultasi $konsultasi,
        public User $actor,
        public string $eventType
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $konsultasi = $this->konsultasi->fresh()->loadMissing([
            'kunjungan.pasien',
            'dokterPengirim',
            'dokterTujuan',
        ]);

        return [
            'domain' => 'konsultasi',
            'event_type' => $this->eventType,
            'konsultasi_id' => $konsultasi->id,
            'judul' => $konsultasi->judul,
            'pasien' => $konsultasi->kunjungan?->pasien?->nama ?? '-',
            'actor_name' => $this->actor->name,
            'message' => $this->messageForEvent($konsultasi),
            'url' => route('konsultasi.show', $konsultasi),
        ];
    }

    private function messageForEvent(Konsultasi $konsultasi): string
    {
        return match ($this->eventType) {
            'konsultasi_baru' => "{$this->actor->name} mengirim konsultasi baru untuk pasien {$konsultasi->kunjungan?->pasien?->nama}.",
            'pesan_baru' => "{$this->actor->name} mengirim pesan baru pada konsultasi {$konsultasi->judul}.",
            default => "{$this->actor->name} memperbarui konsultasi {$konsultasi->judul}.",
        };
    }
}
