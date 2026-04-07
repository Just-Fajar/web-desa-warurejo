<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    /**
     * Display a listing of berita
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Berita::with('admin:id,nama')
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

        return response()->json([
            'success' => true,
            'data' => $berita->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'slug' => $item->slug,
                    'konten' => strip_tags($item->konten),
                    'konten_html' => $item->konten,
                    'gambar' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                    'views' => $item->views,
                    'published_at' => $item->published_at?->toIso8601String(),
                    'author' => [
                        'id' => $item->admin->id,
                        'nama' => $item->admin->nama,
                    ],
                ];
            }),
            'meta' => [
                'current_page' => $berita->currentPage(),
                'last_page' => $berita->lastPage(),
                'per_page' => $berita->perPage(),
                'total' => $berita->total(),
                'from' => $berita->firstItem(),
                'to' => $berita->lastItem(),
            ],
            'links' => [
                'first' => $berita->url(1),
                'last' => $berita->url($berita->lastPage()),
                'prev' => $berita->previousPageUrl(),
                'next' => $berita->nextPageUrl(),
            ],
        ], 200);
    }

    /**
     * Display the specified berita
     * 
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $slug)
    {
        $berita = Berita::with('admin:id,nama,email')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views
        $berita->increment('views');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $berita->id,
                'judul' => $berita->judul,
                'slug' => $berita->slug,
                'konten' => $berita->konten,
                'gambar' => $berita->gambar ? asset('storage/' . $berita->gambar) : null,
                'views' => $berita->views,
                'published_at' => $berita->published_at?->toIso8601String(),
                'created_at' => $berita->created_at->toIso8601String(),
                'updated_at' => $berita->updated_at->toIso8601String(),
                'author' => [
                    'id' => $berita->admin->id,
                    'nama' => $berita->admin->nama,
                    'email' => $berita->admin->email,
                ],
            ],
        ], 200);
    }

    /**
     * Get latest berita
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest(Request $request)
    {
        $limit = $request->get('limit', 5);

        $berita = Berita::with('admin:id,nama')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $berita->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'slug' => $item->slug,
                    'excerpt' => Str::limit(strip_tags($item->konten), 150),
                    'gambar' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                    'views' => $item->views,
                    'published_at' => $item->published_at?->toIso8601String(),
                    'author' => $item->admin->nama,
                ];
            }),
        ], 200);
    }

    /**
     * Get popular berita
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function popular(Request $request)
    {
        $limit = $request->get('limit', 5);

        $berita = Berita::with('admin:id,nama')
            ->where('status', 'published')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $berita->map(function ($item) {
                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'slug' => $item->slug,
                    'excerpt' => Str::limit(strip_tags($item->konten), 150),
                    'gambar' => $item->gambar ? asset('storage/' . $item->gambar) : null,
                    'views' => $item->views,
                    'published_at' => $item->published_at?->toIso8601String(),
                    'author' => $item->admin->nama,
                ];
            }),
        ], 200);
    }
}
