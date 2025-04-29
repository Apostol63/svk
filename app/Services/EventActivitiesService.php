<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use App\Repositories\Api\Contracts\EventRepositoryInterface;

class EventActivitiesService
{
    public function __construct(
        private EventRepositoryInterface $eventRepository
    )
    {}

    public function handle(int $showId): array
    {
        try {
            $data = $this->eventRepository->events($showId);

            if (empty($data)) {
                throw new \RuntimeException('Список событий мероприятия пуст.');
            }

            return $data;
        } catch (RequestException $e) {
            throw new \RuntimeException('Ошибка получения данных: ' . $e->getMessage());
        }
    }
}
