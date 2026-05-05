<?php

namespace App\Services;

use GuzzleHttp\Client;

class BrevoMailer
{
    private Client $http;

    public function __construct(?Client $client = null)
    {
        $this->http = $client ?: new Client([
            'base_uri' => 'https://api.brevo.com',
            'timeout'  => 20,
        ]);
    }

    /**
     * Kirim email via Brevo Transactional API (v3).
     *
     * @return array JSON response dari Brevo
     */
    public function send(
        string $toEmail,
        ?string $toName,
        string $subject,
        string $html,
        ?string $text = null,
        ?string $replyToEmail = null,
        ?string $replyToName = null
    ): array {
        $apiKey = (string) config('services.brevo.key');

        if (!$apiKey) {
            throw new \RuntimeException('BREVO_API_KEY belum di-set. Pastikan config(services.brevo.key) ada.');
        }

        $fromEmail = (string) config('services.brevo.sender_email');
        $fromName  = (string) config('services.brevo.sender_name');

        if (!$fromEmail) {
            throw new \RuntimeException('BREVO_SENDER_EMAIL belum di-set di config/services.php atau .env.');
        }

        $payload = [
            'sender' => [
                'email' => $fromEmail,
                'name'  => $fromName ?: 'App',
            ],
            'to' => [
                [
                    'email' => $toEmail,
                    'name'  => $toName ?: $toEmail,
                ],
            ],
            'subject'     => $subject,
            'htmlContent' => $html,
        ];

        if ($text) {
            $payload['textContent'] = $text;
        }

        if ($replyToEmail) {
            $payload['replyTo'] = [
                'email' => $replyToEmail,
                'name'  => $replyToName ?: $replyToEmail,
            ];
        }

        $res = $this->http->post('/v3/smtp/email', [
            'headers' => [
                'accept'       => 'application/json',
                'content-type' => 'application/json',
                'api-key'      => $apiKey,
            ],
            'json' => $payload,
        ]);

        $body = (string) $res->getBody();
        return $body ? json_decode($body, true) : [];
    }
}