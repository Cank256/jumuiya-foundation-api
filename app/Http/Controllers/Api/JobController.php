<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\JsonResponse;

class JobController extends Controller
{
    /**
     * GET /jobs
     */
    public function index(): JsonResponse
    {
        $jobs = Career::orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $jobs->map(fn (Career $j) => $this->transformList($j)),
        ]);
    }

    /**
     * GET /jobs/{id}
     */
    public function show(int $id): JsonResponse
    {
        $job = Career::find($id);

        if (! $job) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        return response()->json($this->transformDetail($job));
    }

    private function transformList(Career $j): array
    {
        return [
            'id'                   => $j->id,
            'title'                => $j->title,
            'department'           => $j->department,
            'employment_type'      => $j->employment_type,
            'location'             => $j->location,
            'application_deadline' => $j->application_deadline?->toDateString(),
            'purpose_of_role'      => $j->purpose_of_role,
            'status'               => $j->status,
        ];
    }

    private function transformDetail(Career $j): array
    {
        return [
            'id'                      => $j->id,
            'title'                   => $j->title,
            'status'                  => $j->status,
            'department'              => $j->department,
            'employment_type'         => $j->employment_type,
            'location'                => $j->location,
            'salary_range'            => $j->salary_range,
            'application_deadline'    => $j->application_deadline?->toDateString(),
            'reports_to'              => $j->reports_to,
            'supervises_who'          => $j->supervises_who,
            'description'             => $j->description,
            'purpose_of_role'         => $j->purpose_of_role,
            'responsibilities'        => $j->responsibilities,
            'requirements'            => $j->requirements,
            'core_competencies'       => $j->core_competencies,
            'application_requirements'=> $j->application_requirements,
            'application_process'     => $j->application_process,
            'disclaimer'              => $j->disclaimer,
            'apply_here'              => $j->apply_here,
            'has_document'            => (bool) $j->has_document,
            'document_download_url'   => $j->document_download_url,
            'document_name'           => $j->document_name,
            'formatted_file_size'     => $j->formatted_file_size,
        ];
    }
}
