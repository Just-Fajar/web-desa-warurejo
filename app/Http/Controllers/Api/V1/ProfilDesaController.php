<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Http\Resources\Api\ProfilDesaResource;
use App\Models\ProfilDesa;

class ProfilDesaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/profil",
     *     summary="Dapatkan detail profil desa",
     *     description="Mengembalikan informasi detail profil desa termasuk visi, misi, sejarah, struktur organisasi, dan demografi.",
     *     tags={"Profil Desa"},
     *     @OA\Response(
     *         response=200,
     *         description="Detail profil desa berhasil dimuat",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Request successful"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     *
     * Display the profil desa.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $profil = ProfilDesa::getInstance();

        return ApiResponse::success(new ProfilDesaResource($profil));
    }
}
