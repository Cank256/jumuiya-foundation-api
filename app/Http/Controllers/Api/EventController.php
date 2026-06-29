<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * GET /events
     * Returns all events (frontend handles upcoming/past split).
     */
    public function index(): JsonResponse
    {
        $events = Event::orderBy('start_date', 'desc')->get();

        return response()->json([
            'data' => $events->map(fn (Event $e) => $this->transform($e)),
        ]);
    }

    /**
     * GET /events/{id}
     */
    public function show(int $id): JsonResponse
    {
        $event = Event::find($id);

        if (! $event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        return response()->json($this->transform($event));
    }

    private function transform(Event $event): array
    {
        return [
            'id'               => $event->id,
            'title'            => $event->title,
            'description'      => $event->description,
            'category'         => $event->category,
            'location'         => $event->location,
            'start_date'       => $event->start_date?->toIso8601String(),
            'end_date'         => $event->end_date?->toIso8601String(),
            'time'             => $event->time,
            'seats'            => $event->seats,
            'status'           => $event->status,
            'featured'         => (bool) $event->featured,
            'featured_image'   => $event->featured_image_url,
            'registration_url' => $event->registration_url,
        ];
    }
}
