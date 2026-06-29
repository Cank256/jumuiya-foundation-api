<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\PartnershipEnquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * POST /contact
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255'],
            'organisation' => ['nullable', 'string', 'max:255'],
            'subject'      => ['required', 'string', 'in:general,partnership,volunteer,donation,media,other'],
            'message'      => ['required', 'string', 'min:10'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        ContactSubmission::create([
            'first_name'   => $request->input('first_name'),
            'last_name'    => $request->input('last_name'),
            'email'        => $request->input('email'),
            'organisation' => $request->input('organisation'),
            'subject'      => $request->input('subject'),
            'message'      => $request->input('message'),
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Thank you. We will be in touch within 2 business days.',
        ]);
    }

    /**
     * POST /partnership-enquiry
     */
    public function partnership(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'             => ['required', 'string', 'max:255'],
            'organisation'     => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'max:255'],
            'partnership_type' => ['nullable', 'string', 'in:Institutional Partner,Funding Partner,Corporate Partner,Community Partner,Other'],
            'message'          => ['required', 'string', 'min:10'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        PartnershipEnquiry::create([
            'name'             => $request->input('name'),
            'organisation'     => $request->input('organisation'),
            'email'            => $request->input('email'),
            'partnership_type' => $request->input('partnership_type'),
            'message'          => $request->input('message'),
            'ip_address'       => $request->ip(),
            'user_agent'       => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Thank you. We will be in touch within 2 business days.',
        ]);
    }
}
