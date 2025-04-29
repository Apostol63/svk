<?php

declare(strict_types=1);

namespace App\Repositories\Api;

use App\Repositories\Api\Contracts\EventRepositoryInterface;

class OtherEventRepository implements EventRepositoryInterface
{
    //TODO: Реализовать нужный способ запроса в другой внешний сервис API.

    public function events(int $showId): array
    {
        // TODO: Implement events() method.
    }

    public function places(int $eventId): array
    {
        // TODO: Implement places() method.
    }

    public function reserve(array $reserveData): array
    {
        // TODO: Implement reserve() method.
    }

    public function shows(): array
    {
        // TODO: Implement shows() method.
    }
}
