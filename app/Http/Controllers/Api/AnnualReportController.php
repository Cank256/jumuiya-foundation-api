<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AnnualReport;
use Illuminate\Http\JsonResponse;

class AnnualReportController extends Controller
{
    /**
     * GET /annual-reports
     */
    public function index(): JsonResponse
    {
        $reports = AnnualReport::orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'data' => $reports->map(fn (AnnualReport $r) => [
                'id'                  => $r->id,
                'label'               => $r->label,
                'title'               => $r->title,
                'year'                => $r->year,
                'download_url'        => $r->download_url,
                'href'                => $r->href,
                'formatted_file_size' => $r->formatted_file_size,
                'file_size'           => $r->file_size,
            ]),
        ]);
    }
}
