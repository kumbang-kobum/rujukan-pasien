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
        public string $title,
        public string $message,
        public string $category,
        public ?User $actor = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'category' => $this->category,
            'konsultasi_id' => $this->konsultasi->id,
            'no_konsultasi' => $this->konsultasi->no_konsultasi,
            'status' => $this->konsultasi->status,
            'actor_name' => $this->actor?->name,
            'url' => route('konsultasi.show', $this->konsultasi),
        ];
    }
}
