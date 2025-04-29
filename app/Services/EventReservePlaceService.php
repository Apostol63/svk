<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use App\Repositories\Api\Contracts\EventRepositoryInterface;

class EventReservePlaceService
{
    public function __construct(
        private EventRepositoryInterface $eventRepository
    )
    {}

    public function handle(array $reserveData): array
    {
        try {
            $data = $this->eventRepository->reserve($reserveData);

            if (empty($data)) {
                throw new \RuntimeException('Не удалось забронировать места.');
            }

            return $data;
        } catch (RequestException $e) {
            throw new \RuntimeException('Ошибка: ' . $e->getMessage());
        }
    }
}
