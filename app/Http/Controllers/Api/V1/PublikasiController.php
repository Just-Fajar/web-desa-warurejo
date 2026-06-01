<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Http\Resources\Api\PublikasiResource;
use App\Models\Publikasi;
use App\Jobs\IncrementViewsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublikasiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/publikasi",
     *     summary="Dapatkan daftar dokumen publikasi resmi",
     *     description="Mengembalikan paginated list untuk dokumen publikasi (APBDes, RPJMDes, RKPDes) dengan filter kategori, tahun, pencarian, dan pengurutan.",
     *     tags={"Publikasi"},
     *     @OA\Parameter(
     *         name="kategori",
     *         in="query",
     *         description="Filter berdasarkan kategori (APBDes, RPJMDes, RKPDes)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="tahun",
     *         in="query",
     *         description="Filter berdasarkan tahun dokumen",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Cari publikasi berdasarkan judul",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="urutkan",
     *         in="query",
     *         description="Pengurutan data (terbaru, terlama, terpopuler)",
     *         required=false,
     *         @OA\Schema(type="string", default="terbaru")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Jumlah item per halaman",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar publikasi berhasil dimuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="meta", type="object"),
     *             @OA\Property(property="links", type="object")
     *         )
     *     )
     * )
     *
     * Display a listing of publikasi.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $kategori = $request->get('kategori');
        $tahun = $request->get('tahun');
        $search = $request->get('search');
        $urutkan = $request->get('urutkan', 'terbaru'); // terbaru, terlama, terpopuler

        $query = Publikasi::published();

        if ($kategori) {
            $query->byKategori($kategori);
        }

        if ($tahun) {
            $query->byTahun($tahun);
        }

        if ($search) {
            $query->where('judul', 'like', '%'.$search.'%');
        }

        switch ($urutkan) {
            case 'terpopuler':
                $query->orderBy('jumlah_download', 'desc');
                break;
            case 'terlama':
                $query->oldest();
                break;
            case 'terbaru':
            default:
                $query->latest();
                break;
        }

        $perPage = $request->get('per_page', 10);
        $publikasi = $query->paginate($perPage);

        return ApiResponse::paginate($publikasi, PublikasiResource::class);
    }

    /**
     * @OA\Get(
     *     path="/publikasi/{id}",
     *     summary="Dapatkan detail dokumen publikasi",
     *     description="Mengembalikan detail lengkap satu dokumen publikasi berdasarkan ID, serta memicu background job untuk menaikkan views counter.",
     *     tags={"Publikasi"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID dokumen publikasi",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail publikasi berhasil dimuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Dokumen tidak ditemukan"
     *     )
     * )
     *
     * Display the specified publikasi.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $publikasi = Publikasi::published()->findOrFail($id);

        // Increment views in background
        IncrementViewsJob::dispatch($publikasi)->afterResponse();

        return ApiResponse::success(new PublikasiResource($publikasi));
    }

    /**
     * @OA\Get(
     *     path="/publikasi/{id}/download",
     *     summary="Unduh berkas dokumen publikasi resmi",
     *     description="Menaikkan counter unduhan dokumen dan mengunduh berkas PDF fisik.",
     *     tags={"Publikasi"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID dokumen publikasi",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File download stream",
     *         @OA\MediaType(mediaType="application/pdf")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="File atau dokumen tidak ditemukan"
     *     )
     * )
     *
     * Download the document file.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
     */
    public function download($id)
    {
        $publikasi = Publikasi::published()->findOrFail($id);
        $publikasi->incrementDownload();

        if (!Storage::disk('public')->exists($publikasi->file_dokumen)) {
            return ApiResponse::error('File not found', 404);
        }

        $filePath = Storage::disk('public')->path($publikasi->file_dokumen);
        return response()->download($filePath, $publikasi->judul.'.pdf');
    }

    /**
     * @OA\Get(
     *     path="/publikasi/categories",
     *     summary="Dapatkan daftar kategori publikasi yang tersedia",
     *     tags={"Publikasi"},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar kategori berhasil dimuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string", example="APBDes"))
     *         )
     *     )
     * )
     *
     * Get available publikasi categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories()
    {
        $categories = ['APBDes', 'RPJMDes', 'RKPDes'];
        return ApiResponse::success($categories);
    }

    /**
     * @OA\Get(
     *     path="/publikasi/years",
     *     summary="Dapatkan daftar tahun publikasi yang tersedia",
     *     tags={"Publikasi"},
     *     @OA\Parameter(
     *         name="kategori",
     *         in="query",
     *         description="Filter tahun berdasarkan kategori tertentu",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar tahun berhasil dimuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="integer", example=2024))
     *         )
     *     )
     * )
     *
     * Get available years for filtering.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function years(Request $request)
    {
        $kategori = $request->get('kategori');

        $query = Publikasi::published();

        if ($kategori) {
            $query->byKategori($kategori);
        }

        $years = $query->distinct()
            ->pluck('tahun')
            ->sort()
            ->reverse()
            ->values();

        return ApiResponse::success($years);
    }
}
