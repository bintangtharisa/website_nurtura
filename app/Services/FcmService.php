<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    public function sendToToken(string $token, string $title, string $body, array $data = []): bool
    {
        if (trim($token) === '') {
            return false;
        }

        if ($this->canUseHttpV1()) {
            return $this->sendHttpV1($token, $title, $body, $data);
        }

        if (env('FCM_SERVER_KEY')) {
            return $this->sendLegacy($token, $title, $body, $data);
        }

        Log::warning('FCM credentials are not configured.');
        return false;
    }

    private function canUseHttpV1(): bool
    {
        return (bool) (
            env('FIREBASE_PROJECT_ID') &&
            env('FIREBASE_CLIENT_EMAIL') &&
            env('FIREBASE_PRIVATE_KEY')
        );
    }

    private function sendHttpV1(string $token, string $title, string $body, array $data): bool
    {
        $projectId = env('FIREBASE_PROJECT_ID');
        $accessToken = $this->accessToken();

        if (!$accessToken) {
            return false;
        }

        $response = Http::withToken($accessToken)->post(
            "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
            [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => array_map('strval', $data),
                    'android' => [
                        'priority' => 'HIGH',
                    ],
                ],
            ]
        );

        if (!$response->successful()) {
            Log::warning('FCM HTTP v1 failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return $response->successful();
    }

    private function accessToken(): ?string
    {
        $now = time();
        $claims = [
            'iss' => env('FIREBASE_CLIENT_EMAIL'),
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
        ];

        $jwt = $this->jwt($claims, (string) env('FIREBASE_PRIVATE_KEY'));
        if (!$jwt) {
            return null;
        }

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if (!$response->successful()) {
            Log::warning('FCM OAuth token request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        return $response->json('access_token');
    }

    private function jwt(array $claims, string $privateKey): ?string
    {
        $privateKey = str_replace('\\n', "\n", $privateKey);
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $segments = [
            $this->base64Url(json_encode($header)),
            $this->base64Url(json_encode($claims)),
        ];
        $signingInput = implode('.', $segments);

        $signature = '';
        $ok = openssl_sign($signingInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        if (!$ok) {
            Log::warning('Failed signing Firebase JWT.');
            return null;
        }

        $segments[] = $this->base64Url($signature);
        return implode('.', $segments);
    }

    private function base64Url(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function sendLegacy(string $token, string $title, string $body, array $data): bool
    {
        $response = Http::withHeaders([
            'Authorization' => 'key=' . env('FCM_SERVER_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $token,
            'priority' => 'high',
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => $data,
        ]);

        if (!$response->successful()) {
            Log::warning('FCM legacy failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return $response->successful();
    }
}
