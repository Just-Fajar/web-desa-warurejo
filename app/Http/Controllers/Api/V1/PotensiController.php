<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Http\Resources\Api\PotensiDesaResource;
use App\Models\PotensiDesa;
use App\Jobs\IncrementViewsJob;
use Illuminate\Http\Request;

class PotensiController extends Controller
{
    /**
     * Display a listing of potensi
     */
    public function index(Request $request)
    {
        $query = PotensiDesa::published()
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter by kategori
        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $potensi = $query->paginate($perPage);

        return ApiResponse::paginate($potensi, PotensiDesaResource::class);
    }

    /**
     * Display the specified potensi
     */
    public function show(string $slug)
    {
        $potensi = PotensiDesa::where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment views in background
        IncrementViewsJob::dispatch($potensi)->afterResponse();

        return ApiResponse::success(new PotensiDesaResource($potensi));
    }

    /**
     * Get featured potensi
     */
    public function featured(Request $request)
    {
        $limit = $request->get('limit', 6);

        $potensi = PotensiDesa::published()
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();

        return ApiResponse::success(PotensiDesaResource::collection($potensi));
    }
}
