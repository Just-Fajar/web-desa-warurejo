<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Http\Resources\Api\GaleriResource;
use App\Models\Galeri;
use App\Jobs\IncrementViewsJob;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    /**
     * Display a listing of galeri
     */
    public function index(Request $request)
    {
        $query = Galeri::with('images')
            ->published()
            ->orderBy('tanggal', 'desc');

        // Filter by kategori
        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $galeri = $query->paginate($perPage);

        return ApiResponse::paginate($galeri, GaleriResource::class);
    }

    /**
     * Display the specified galeri
     */
    public function show(int $id)
    {
        $galeri = Galeri::with('images')
            ->published()
            ->findOrFail($id);

        // Increment views in background
        IncrementViewsJob::dispatch($galeri)->afterResponse();

        return ApiResponse::success(new GaleriResource($galeri));
    }

    /**
     * Get latest galeri
     */
    public function latest(Request $request)
    {
        $limit = $request->get('limit', 6);

        $galeri = Galeri::with('images')
            ->published()
            ->orderBy('tanggal', 'desc')
            ->limit($limit)
            ->get();

        return ApiResponse::success(GaleriResource::collection($galeri));
    }

    /**
     * Get galeri categories
     */
    public function categories()
    {
        $categories = Galeri::select('kategori')
            ->published()
            ->groupBy('kategori')
            ->get()
            ->pluck('kategori');

        return ApiResponse::success($categories);
    }
}
