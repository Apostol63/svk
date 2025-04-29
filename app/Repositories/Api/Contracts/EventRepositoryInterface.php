<?php

declare(strict_types=1);

namespace App\Repositories\Api\Contracts;

interface EventRepositoryInterface
{
    public function shows(): array;
    public function events(int $showId): array;
    public function places(int $eventId): array;
    public function reserve(array $reserveData): array;
}
