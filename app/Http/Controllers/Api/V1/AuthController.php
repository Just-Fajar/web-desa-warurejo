<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create API token for authentication
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            return ApiResponse::error('The provided credentials are incorrect.', 422, [
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        // Create token with abilities
        $token = $admin->createToken($request->device_name, ['api:read', 'api:write'])->plainTextToken;

        return ApiResponse::success([
            'admin' => [
                'id' => $admin->id,
                'nama' => $admin->name,
                'email' => $admin->email,
                'username' => $admin->email,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful');
    }

    /**
     * Revoke current token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success(null, 'Logged out successfully');
    }

    /**
     * Revoke all tokens
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logoutAll(Request $request)
    {
        // Revoke all tokens
        $request->user()->tokens()->delete();

        return ApiResponse::success(null, 'All sessions logged out successfully');
    }

    /**
     * Get authenticated user info
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $admin = $request->user();

        return ApiResponse::success([
            'id' => $admin->id,
            'nama' => $admin->name,
            'email' => $admin->email,
            'username' => $admin->email,
            'foto' => $admin->avatar_url,
            'created_at' => $admin->created_at->toIso8601String(),
        ]);
    }

    /**
     * List all active tokens
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokens(Request $request)
    {
        $tokens = $request->user()->tokens;

        return ApiResponse::success($tokens->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'abilities' => $token->abilities,
                'last_used_at' => $token->last_used_at?->toIso8601String(),
                'created_at' => $token->created_at->toIso8601String(),
            ];
        }));
    }
}
