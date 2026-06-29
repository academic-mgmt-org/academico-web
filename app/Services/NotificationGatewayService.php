<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationGatewayService
{
    public function recent(?string $token, ?array $user = null, int $limit = 3): ?array
    {
        if (! $token) {
            return null;
        }

        $baseUrl = rtrim((string) config('services.gateway.url'), '/');
        $path = '/'.ltrim((string) config('services.notifications.recent_path'), '/');

        try {
            $curlOptions = [];
            if (defined('CURLOPT_HTTP_VERSION') && defined('CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE')) {
                $curlOptions[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE;
            }

            $response = Http::acceptJson()
                ->withToken($token)
                ->withOptions($curlOptions ? ['curl' => $curlOptions] : [])
                ->timeout(5)
                ->get($baseUrl.$path, [
                    'limit' => $limit,
                ]);

            if (! $response->successful()) {
                Log::warning('No se pudieron obtener notificaciones desde gateway', [
                    'status' => $response->status(),
                    'user' => $user['username'] ?? null,
                ]);

                return null;
            }

            $payload = $response->json();
            $notifications = collect($payload['notifications'] ?? [])
                ->take($limit)
                ->map(fn ($item) => [
                    'text' => $item['text'] ?? $item['mensaje'] ?? '',
                    'time' => $item['time'] ?? 'Hace instantes',
                    'icon_id' => $item['icon_id'] ?? $item['iconId'] ?? 'i-bell',
                ])
                ->filter(fn ($item) => $item['text'] !== '')
                ->values()
                ->all();

            return [
                'notifications' => $notifications,
                'unreadCount' => (int) ($payload['unreadCount'] ?? count($notifications)),
                'usuarioId' => $payload['usuarioId'] ?? null,
            ];
        } catch (\Throwable $exception) {
            Log::warning('Error consultando notificaciones desde gateway', [
                'message' => $exception->getMessage(),
                'user' => $user['username'] ?? null,
            ]);

            return null;
        }
    }
}
