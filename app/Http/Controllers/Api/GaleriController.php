<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    /**
     * Display a listing of galeri
     */
    public function index(Request $request)
    {
        $query = Galeri::with('images')
            ->where('status', 'active')
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

        return response()->json([
            'success' => true,
            'data' => $galeri->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'deskripsi' => $item->deskripsi,
                    'kategori' => $item->kategori,
                    'tanggal' => $item->tanggal,
                    'views' => $item->views,
                    'images' => $item->images->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'path' => asset('storage/' . $image->path),
                            'urutan' => $image->urutan,
                        ];
                    }),
                    'thumbnail' => $item->images->first() 
                        ? asset('storage/' . $item->images->first()->path) 
                        : null,
                    'created_at' => $item->created_at->toIso8601String(),
                ];
            }),
            'meta' => [
                'current_page' => $galeri->currentPage(),
                'last_page' => $galeri->lastPage(),
                'per_page' => $galeri->perPage(),
                'total' => $galeri->total(),
            ],
        ], 200);
    }

    /**
     * Display the specified galeri
     */
    public function show(int $id)
    {
        $galeri = Galeri::with('images')
            ->where('status', 'active')
            ->findOrFail($id);

        // Increment views
        $galeri->increment('views');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $galeri->id,
                'judul' => $galeri->judul,
                'deskripsi' => $galeri->deskripsi,
                'kategori' => $galeri->kategori,
                'tanggal' => $galeri->tanggal,
                'views' => $galeri->views,
                'images' => $galeri->images->sortBy('urutan')->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'path' => asset('storage/' . $image->path),
                        'urutan' => $image->urutan,
                    ];
                }),
                'created_at' => $galeri->created_at->toIso8601String(),
                'updated_at' => $galeri->updated_at->toIso8601String(),
            ],
        ], 200);
    }

    /**
     * Get latest galeri
     */
    public function latest(Request $request)
    {
        $limit = $request->get('limit', 6);

        $galeri = Galeri::with('images')
            ->where('status', 'active')
            ->orderBy('tanggal', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $galeri->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'kategori' => $item->kategori,
                    'tanggal' => $item->tanggal,
                    'thumbnail' => $item->images->first() 
                        ? asset('storage/' . $item->images->first()->path) 
                        : null,
                    'image_count' => $item->images->count(),
                    'views' => $item->views,
                ];
            }),
        ], 200);
    }

    /**
     * Get galeri categories
     */
    public function categories()
    {
        $categories = Galeri::select('kategori')
            ->where('status', 'active')
            ->groupBy('kategori')
            ->get()
            ->pluck('kategori');

        return response()->json([
            'success' => true,
            'data' => $categories,
        ], 200);
    }
}
