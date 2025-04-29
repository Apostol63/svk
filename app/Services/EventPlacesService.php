<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use App\Repositories\Api\Contracts\EventRepositoryInterface;

class EventPlacesService
{
    public function __construct(
        private EventRepositoryInterface $eventRepository
    )
    {}

    public function handle(int $eventId): array
    {
        try {
            $data = $this->eventRepository->places($eventId);

            if (empty($data)) {
                throw new \RuntimeException('Список мест события пуст.');
            }

            return $data;
        } catch (RequestException $e) {
            throw new \RuntimeException('Ошибка получения данных: ' . $e->getMessage());
        }
    }
}
