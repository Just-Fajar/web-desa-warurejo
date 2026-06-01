<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Http\Resources\Api\BeritaResource;
use App\Models\Berita;
use App\Jobs\IncrementViewsJob;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Display a listing of berita
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Berita::with('admin')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('published_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('published_at', '<=', $request->to_date);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $berita = $query->paginate($perPage);

        return ApiResponse::paginate($berita, BeritaResource::class);
    }

    /**
     * Display the specified berita
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $slug)
    {
        $berita = Berita::with('admin')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views in background
        IncrementViewsJob::dispatch($berita)->afterResponse();

        return ApiResponse::success(new BeritaResource($berita));
    }

    /**
     * Get latest berita
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest(Request $request)
    {
        $limit = $request->get('limit', 5);

        $berita = Berita::with('admin')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();

        return ApiResponse::success(BeritaResource::collection($berita));
    }

    /**
     * Get popular berita
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function popular(Request $request)
    {
        $limit = $request->get('limit', 5);

        $berita = Berita::with('admin')
            ->where('status', 'published')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();

        return ApiResponse::success(BeritaResource::collection($berita));
    }
}
