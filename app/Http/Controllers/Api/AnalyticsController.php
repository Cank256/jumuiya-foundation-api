<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsError;
use App\Models\AnalyticsEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * POST /analytics/event
     * Fire-and-forget from the browser — always return 200.
     */
    public function event(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $type = $data['type'] ?? null;

            if (! in_array($type, ['page_view', 'button_click', 'form_submission'], true)) {
                return response()->json(['success' => true]);
            }

            AnalyticsEvent::create([
                'type'        => $type,
                'path'        => $data['path'] ?? null,
                'title'       => $data['title'] ?? null,
                'button_name' => $data['buttonName'] ?? null,
                'section'     => $data['section'] ?? null,
                'form_name'   => $data['formName'] ?? null,
                'success'     => isset($data['success']) ? (bool) $data['success'] : null,
                'ip'          => $request->header('X-Forwarded-For') ?? $request->ip(),
                'user_agent'  => $request->userAgent(),
                'referer'     => $request->header('Referer'),
                'session_id'  => $request->cookie('analytics_session'),
                'occurred_at' => $data['timestamp'] ?? now(),
            ]);
        } catch (\Throwable) {
            // Never break the frontend — swallow all errors
        }

        return response()->json(['success' => true]);
    }

    /**
     * POST /analytics/error
     */
    public function error(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            AnalyticsError::create([
                'message'    => $data['message'] ?? 'Unknown error',
                'context'    => $data['context'] ?? null,
                'ip'         => $request->header('X-Forwarded-For') ?? $request->ip(),
                'user_agent' => $request->userAgent(),
                'occurred_at'=> $data['timestamp'] ?? now(),
            ]);
        } catch (\Throwable) {
            // Never break the frontend
        }

        return response()->json(['success' => true]);
    }
}
