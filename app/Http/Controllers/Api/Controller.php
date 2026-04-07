<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Website Desa Warurejo API",
 *     version="1.0.0",
 *     description="RESTful API untuk Website Profil Desa Warurejo - Menyediakan endpoints untuk mengakses data berita, potensi desa, dan galeri foto. API ini mendukung autentikasi menggunakan Laravel Sanctum.",
 *     @OA\Contact(
 *         email="admin@warurejo.desa.id",
 *         name="Tim Pengembang Warurejo"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost/api/v1",
 *     description="Development Server"
 * )
 * 
 * @OA\Server(
 *     url="https://warurejo.desa.id/api/v1",
 *     description="Production Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your bearer token in the format: Bearer {token}"
 * )
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="Endpoints untuk autentikasi dan manajemen token"
 * )
 * 
 * @OA\Tag(
 *     name="Berita",
 *     description="Endpoints untuk mengelola dan mengakses berita desa"
 * )
 * 
 * @OA\Tag(
 *     name="Potensi Desa",
 *     description="Endpoints untuk mengelola dan mengakses potensi desa"
 * )
 * 
 * @OA\Tag(
 *     name="Galeri",
 *     description="Endpoints untuk mengelola dan mengakses galeri foto"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
