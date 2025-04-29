<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class EventsTest extends TestCase
{
    public function testShows()
    {
        Http::fake([
            'https://leadbook.ru/test-task-api/shows' => Http::response([
                'response' => [
                    [
                        'id' => 1,
                        'name' => 'Мероприятие 1',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Мероприятие 2',
                    ],
                ],
            ], 200),
        ]);

        $response = $this->getJson('/api/shows');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                'data' => [
                    'response' => [
                        '*' => [
                            'id',
                            'name'
                        ],
                    ],
                ],
            ]
        );
    }

    public function testEventsWithCorrectShowId()
    {
        $showId = rand(1, 15);
        $date = now()->format('Y-m-d H:i:s');

        Http::fake([
            "https://leadbook.ru/test-task-api/shows/{$showId}/events" => Http::response([
                'response' => [
                    [
                        'id' => 1,
                        'showId' => $showId,
                        'date' => $date,
                    ],
                    [
                        'id' => 2,
                        'showId' => $showId,
                        'date' => $date,
                    ],
                ],
            ], 200),
        ]);

        $response = $this->getJson("/api/shows/{$showId}/events");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                'data' => [
                    'response' => [
                        '*' => [
                            'id',
                            'showId',
                            'date'
                        ],
                    ],
                ],
            ]
        );
    }

    public function testEventsWithWrongShowId()
    {
        $showId = 'hfdg';
        $date = now()->format('Y-m-d H:i:s');

        Http::fake([
            "https://leadbook.ru/test-task-api/shows/{$showId}/events" => Http::response([
                'response' => [
                    [
                        'id' => 1,
                        'showId' => $showId,
                        'date' => $date,
                    ],
                    [
                        'id' => 2,
                        'showId' => $showId,
                        'date' => $date,
                    ],
                ],
            ], 200),
        ]);

        $response = $this->getJson("/api/shows/{$showId}/events");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'message' => 'Ошибка валидации',
            'errors' => [
                'showId' => [
                    'Неверный формат данных',
                ],
            ],
        ]);
    }

    public function testPlacesWithCorrectEventId()
    {
        $eventId = rand(1, 20);

        Http::fake([
            "https://leadbook.ru/test-task-api/events/{$eventId}/places" => Http::response([
                'response' => [
                    [
                        'id' => 1,
                        'x' => 0,
                        'y' => 0,
                        'width' => 20,
                        'height' => 20,
                        'is_available' => true
                    ],
                    [
                        'id' => 2,
                        'x' => 0,
                        'y' => 0,
                        'width' => 20,
                        'height' => 20,
                        'is_available' => true
                    ],
                ],
            ], 200),
        ]);

        $response = $this->getJson("/api/events/{$eventId}/places");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                'data'=> [
                    'response' => [
                        '*' => [
                            'id',
                            'x',
                            'y',
                            'width',
                            'height',
                            'is_available',
                        ],
                    ],
                ],
            ]
        );
    }

    public function testPlacesWithWrongEventId()
    {
        $eventId = 'dfg';
        Http::fake([
            "https://leadbook.ru/test-task-api/events/{$eventId}/places" => Http::response([
                'response' => [
                    [
                        'id' => 1,
                        'x' => 0,
                        'y' => 0,
                        'width' => 20,
                        'height' => 20,
                        'is_avaliable' => true
                    ],
                    [
                        'id' => 2,
                        'x' => 0,
                        'y' => 0,
                        'width' => 20,
                        'height' => 20,
                        'is_avaliable' => true
                    ],
                ],
            ], 200),
        ]);

        $response = $this->getJson("/api/events/{$eventId}/places");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'message' => 'Ошибка валидации',
            'errors' => [
                'eventId' => [
                    'Неверный формат данных',
                ],
            ],
        ]);
    }

    public function testReserve()
    {
        $eventId = rand(1, 15);
        Http::fake([
            "https://leadbook.ru/test-task-api/events/{$eventId}/reserve" => Http::response([
                'response' => [
                    'success' => true,
                    'reservation_id' => '5d519fe58e3cf'
                ]
            ], 201),
        ]);

        $data = [
            'name' => 'Test',
            'places' => [11, 12],
        ];

        $response = $this->postJson("/api/events/{$eventId}/reserve", $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(
            [
                'data'=> [
                    'response' => [
                        'success',
                        'reservation_id',
                    ],
                ],
            ]
        );
    }

    /**
     * @dataProvider invalidReserveDataProvider
     */
    public function testReserveWithWrongData(array $data, string $field)
    {
        $eventId = rand(1, 15);

        $response = $this->postJson("/api/events/{$eventId}/reserve", $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([$field]);
    }

    public static function invalidReserveDataProvider(): array
    {
        return [
            'field name is missing' => [
                [
                    'places' => [12, 13],
                ],
                'name',
            ],
            'field places is missing' => [
                [
                    'name' => 'Test',
                ],
                'places',
            ],
            'field places is`t` array' => [
                [
                    'name' => 'Test',
                    'places' => 'test',
                ],
                'places',
            ],
        ];
    }
}
