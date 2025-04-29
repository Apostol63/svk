<?php

declare(strict_types=1);

namespace App\Repositories\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use App\Repositories\Api\Contracts\EventRepositoryInterface;

class EventRepository implements EventRepositoryInterface
{
    public function shows(): array
    {
        $response = Http::withToken(config('services.ticket_api.token'))
            ->get('https://leadbook.ru/test-task-api/shows');

        if ($response->failed()) {
            throw new RequestException($response);
        }

        return $response->json();
    }

    public function events(int $showId): array
    {
        $response = Http::withToken(config('services.ticket_api.token'))
            ->get("https://leadbook.ru/test-task-api/shows/{$showId}/events");

        if ($response->failed()) {
            throw new RequestException($response);
        }

        return $response->json();
    }

    public function places(int $eventId): array
    {
        $response = Http::withToken(config('services.ticket_api.token'))
            ->get("https://leadbook.ru/test-task-api/events/{$eventId}/places");

        if ($response->failed()) {
            throw new RequestException($response);
        }

        return $response->json();
    }

    public function reserve(array $reserveData): array
    {
        $eventId = $reserveData['eventId'];

        $response = Http::withToken(config('services.ticket_api.token'))
            ->post("https://leadbook.ru/test-task-api/events/{$eventId}/reserve",
                [
                    'name' => $reserveData['name'],
                    'places' => $reserveData['places'],
                ]
            );

        if ($response->failed()) {
            throw new RequestException($response);
        }

        return $response->json();
    }
}
