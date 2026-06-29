<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tender;
use App\Models\TenderDocument;
use Illuminate\Http\JsonResponse;

class TenderController extends Controller
{
    /**
     * GET /tenders
     */
    public function index(): JsonResponse
    {
        $tenders = Tender::with('tenderDocuments')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $tenders->map(fn (Tender $t) => $this->transformList($t)),
        ]);
    }

    /**
     * GET /tenders/{id}
     */
    public function show(int $id): JsonResponse
    {
        $tender = Tender::with('tenderDocuments')->find($id);

        if (! $tender) {
            return response()->json(['error' => 'Tender not found'], 404);
        }

        return response()->json($this->transformDetail($tender));
    }

    private function transformList(Tender $t): array
    {
        return [
            'id'               => $t->id,
            'title'            => $t->title,
            'reference_number' => $t->reference_number,
            'description'      => $t->description,
            'deadline'         => $t->deadline?->toIso8601String(),
            'created_at'       => $t->created_at?->toIso8601String(),
            'status'           => $t->status,
        ];
    }

    private function transformDetail(Tender $t): array
    {
        $docs = $t->tenderDocuments->isNotEmpty()
            ? $t->tenderDocuments->map(fn (TenderDocument $d) => [
                'id'   => $d->id,
                'name' => $d->name,
                'type' => $d->type,
                'url'  => $d->url,
                'size' => $d->size,
            ])->values()->all()
            : null;

        return array_merge($this->transformList($t), [
            'requirements'      => $t->requirements,
            'document_url'      => $t->document_url,
            'has_rfp_document'  => (bool) $t->has_rfp_document,
            'rfp_download_url'  => $t->rfp_download_url,
            'rfp_document_name' => $t->rfp_document_name,
            'rfp_document_size' => $t->rfp_document_size,
            'has_tor_document'  => (bool) $t->has_tor_document,
            'tor_download_url'  => $t->tor_download_url,
            'tor_document_name' => $t->tor_document_name,
            'tor_document_size' => $t->tor_document_size,
            'documents'         => $docs,
        ]);
    }
}
