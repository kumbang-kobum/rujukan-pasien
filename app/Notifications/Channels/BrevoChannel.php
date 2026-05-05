<?php

namespace App\Notifications\Channels;

use App\Services\BrevoMailer;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class BrevoChannel
{
    public function __construct(private BrevoMailer $brevo) {}

    public function send(object $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toBrevo')) {
            return;
        }

        $data = $notification->toBrevo($notifiable);

        if (($data['skip'] ?? false) === true) {
            return;
        }

        try {
            $res = $this->brevo->send(
                toEmail: $data['toEmail'],
                toName: $data['toName'] ?? null,
                subject: $data['subject'],
                html: $data['html'],
                text: $data['text'] ?? null,
                replyToEmail: $data['replyToEmail'] ?? null,
                replyToName: $data['replyToName'] ?? null,
            );

            Log::info('Brevo send OK', [
                'messageId' => $res['messageId'] ?? null,
                'to' => $data['toEmail'],
                'subject' => $data['subject'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Brevo send FAILED: '.$e->getMessage(), [
                'to' => $data['toEmail'] ?? null,
            ]);
            // jangan lempar lagi biar tidak 500
        }
    }
}