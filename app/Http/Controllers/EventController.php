<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\ShowEventsService;
use App\Services\EventPlacesService;
use App\Http\Requests\ShowEventRequest;
use App\Services\EventActivitiesService;
use App\Http\Requests\EventPlacesRequest;
use App\Http\Requests\EventReserveRequest;
use App\Services\EventReservePlaceService;


/**
 * @OA\Info(
 *     title="API",
 *     version="1.0",
 *     description="Мероприятия и события. Тестовое задание"
 * )
 */
class EventController extends Controller
{
    public function __construct(
        private ShowEventsService $showEventService,
        private EventActivitiesService $eventActivitiesService,
        private EventPlacesService $eventPlacesService,
        private EventReservePlaceService $eventReservePlaceService,
    )
    {}

    /**
     * @OA\Get(
     *     path="/api/shows",
     *     summary="Получить список мероприятий",
     *     tags={"Events"},
     *     @OA\Response(
     *         response=200,
     *         description="Список мероприятий"
     *     )
     * )
     */
    public function index()
    {
        $result = $this->showEventService->handle();

        return response()->json(
            [
                'data' => $result,
            ],
            200
        );
    }

    /**
     * @OA\Get(
     *     path="/api/shows/{showId}/events",
     *     summary="Получить список событий мероприятий",
     *     tags={"Events"},
     *     @OA\Parameter(
     *         name="showId",
     *         in="path",
     *         required=true,
     *         description="ID мероприятия",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список событий мероприятия"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации данных"
     *     )
     * )
     */
    public function events(ShowEventRequest $request)
    {
        $showId = $request->input('showId');

        $result = $this->eventActivitiesService->handle(intval($showId));

        return response()->json(
            [
                'data' => $result,
            ],
            200
        );
    }

    /**
     * @OA\Get(
     *     path="/api/events/{eventId}/places",
     *     summary="Получить список мест события",
     *     tags={"Events"},
     *     @OA\Parameter(
     *         name="eventId",
     *         in="path",
     *         required=true,
     *         description="ID события",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список мест события"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации данных"
     *     )
     * )
     */
    public function places(EventPlacesRequest $request)
    {
        $eventId = $request->input('eventId');

        $result = $this->eventPlacesService->handle(intval($eventId));

        return response()->json(
            [
                'data' => $result,
            ],
            200
        );
    }

    /**
     * @OA\Post(
     *     path="/api/events/{eventId}/reserve",
     *     summary="Забронировать места события",
     *     tags={"Events"},
     *     @OA\Parameter(
     *         name="eventId",
     *         in="path",
     *         required=true,
     *         description="ID события",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "places"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Иван"
     *             ),
     *             @OA\Property(
     *                 property="places",
     *                 type="array",
     *                 @OA\Items(type="integer", example=5)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Бронь места события"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации данных"
     *     )
     * )
     */
    public function reserve(EventReserveRequest $request)
    {
        $eventId = $request->input('eventId');

        $result = $this->eventReservePlaceService->handle([
            'eventId' => $eventId,
            'name' => $request->name,
            'places' => $request->places
        ]);

        return response()->json(
            [
                'data' => $result,
            ],
            201
        );
    }
}
