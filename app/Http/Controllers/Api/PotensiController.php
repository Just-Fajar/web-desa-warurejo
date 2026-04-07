<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PotensiDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PotensiController extends Controller
{
    /**
     * Display a listing of potensi
     */
    public function index(Request $request)
    {
        $query = PotensiDesa::where('is_active', true)
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $potensi = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $potensi->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'slug' => $item->slug,
                    'deskripsi' => strip_tags($item->deskripsi),
                    'deskripsi_html' => $item->deskripsi,
                    'gambar' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                    'views' => $item->views,
                    'created_at' => $item->created_at->toIso8601String(),
                ];
            }),
            'meta' => [
                'current_page' => $potensi->currentPage(),
                'last_page' => $potensi->lastPage(),
                'per_page' => $potensi->perPage(),
                'total' => $potensi->total(),
            ],
        ], 200);
    }

    /**
     * Display the specified potensi
     */
    public function show(string $slug)
    {
        $potensi = PotensiDesa::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment views
        $potensi->increment('views');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $potensi->id,
                'nama' => $potensi->nama,
                'slug' => $potensi->slug,
                'deskripsi' => $potensi->deskripsi,
                'gambar' => $potensi->gambar ? asset('storage/' . $potensi->gambar) : null,
                'views' => $potensi->views,
                'created_at' => $potensi->created_at->toIso8601String(),
                'updated_at' => $potensi->updated_at->toIso8601String(),
            ],
        ], 200);
    }

    /**
     * Get featured potensi
     */
    public function featured(Request $request)
    {
        $limit = $request->get('limit', 6);

        $potensi = PotensiDesa::where('is_active', true)
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $potensi->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'slug' => $item->slug,
                    'excerpt' => Str::limit(strip_tags($item->deskripsi), 150),
                    'gambar' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                    'views' => $item->views,
                ];
            }),
        ], 200);
    }
}
