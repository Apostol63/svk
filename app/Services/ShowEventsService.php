<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use App\Repositories\Api\Contracts\EventRepositoryInterface;

class ShowEventsService
{
    public function __construct(
        private EventRepositoryInterface $eventRepository
    )
    {}

    public function handle(): array
    {
        try {
            $eventData = $this->eventRepository->shows();

            if (empty($eventData)) {
                throw new \RuntimeException('Список мероприятий пуст.');
            }

            return $eventData;
        } catch (RequestException $e) {
            throw new \RuntimeException('Ошибка получения данных: ' . $e->getMessage());
        }
    }
}
