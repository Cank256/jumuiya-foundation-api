<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    /**
     * GET /news
     */
    public function index(): JsonResponse
    {
        $articles = NewsArticle::orderBy('published_at', 'desc')->get();

        return response()->json([
            'data' => $articles->map(fn (NewsArticle $a) => $this->transformList($a)),
        ]);
    }

    /**
     * GET /news/{slug}
     * Accepts numeric ID or slug string.
     */
    public function show(string $slug): JsonResponse
    {
        // Try slug first, then fall back to numeric id
        $article = is_numeric($slug)
            ? NewsArticle::with('author')->find((int) $slug)
            : NewsArticle::with('author')->where('slug', $slug)->first();

        if (! $article) {
            return response()->json(['error' => 'Article not found'], 404);
        }

        return response()->json($this->transformDetail($article));
    }

    private function transformList(NewsArticle $a): array
    {
        return [
            'id'             => $a->id,
            'slug'           => $a->slug,
            'title'          => $a->title,
            'excerpt'        => $a->excerpt,
            'category'       => $a->category,
            'featured_image' => $a->featured_image_url,
            'published_at'   => $a->published_at?->toIso8601String(),
            'featured'       => (bool) $a->featured,
        ];
    }

    private function transformDetail(NewsArticle $a): array
    {
        return array_merge($this->transformList($a), [
            'content' => $a->content,
            'creator' => $a->author ? ['name' => $a->author->name] : null,
        ]);
    }
}
