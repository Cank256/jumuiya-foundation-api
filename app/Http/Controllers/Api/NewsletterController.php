<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * POST /newsletter/subscribe
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $email = strtolower(trim($request->input('email')));

        $subscriber = NewsletterSubscriber::where('email', $email)->first();

        if ($subscriber) {
            if ($subscriber->active) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors'  => ['email' => ['This email address is already subscribed.']],
                ], 422);
            }

            // Re-subscribe
            $subscriber->update([
                'active'           => true,
                'subscribed_at'    => now(),
                'unsubscribed_at'  => null,
                'ip_address'       => $request->ip(),
            ]);
        } else {
            NewsletterSubscriber::create([
                'email'         => $email,
                'subscribed_at' => now(),
                'ip_address'    => $request->ip(),
            ]);
        }

        return response()->json([
            'message' => 'You have been subscribed successfully.',
        ]);
    }
}
