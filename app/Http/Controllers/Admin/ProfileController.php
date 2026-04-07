<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfileController extends Controller
{
    public function show()
    {
        $admin = auth()->guard('admin')->user();
        return view('admin.profile.show', compact('admin'));
    }

    public function edit()
    {
        $admin = auth()->guard('admin')->user();
        return view('admin.profile.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        try {
            /** @var \App\Models\Admin $admin */
            $admin = auth()->guard('admin')->user();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
            ]);

            $admin->update($validated);

            return redirect()->route('admin.profile.show')
                ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error updating admin profile', [
                'admin_id' => auth()->guard('admin')->id(),
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            /** @var \App\Models\Admin $admin */
            $admin = auth()->guard('admin')->user();

            // Cek apakah mode lupa password
            $lupaPassword = $request->has('lupa_password');

            if ($lupaPassword) {
                // Mode LUPA PASSWORD: Verifikasi email, tidak perlu password lama
                $validated = $request->validate([
                    'email_verifikasi' => 'required|email',
                    'password' => ['required', 'confirmed', Password::min(8)],
                ], [
                    'email_verifikasi.required' => 'Email wajib diisi untuk verifikasi',
                    'email_verifikasi.email' => 'Format email tidak valid',
                ]);

                // Validasi email harus sama dengan email admin yang login
                if (strtolower($request->email_verifikasi) !== strtolower($admin->email)) {
                    return back()->withErrors(['email_verifikasi' => 'Email tidak sesuai dengan akun Anda!']);
                }

            } else {
                // Mode NORMAL: Perlu password lama
                $validated = $request->validate([
                    'current_password' => 'required',
                    'password' => ['required', 'confirmed', Password::min(8)],
                ]);

                // Check current password
                if (!Hash::check($request->current_password, $admin->password)) {
                    return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
                }
            }

            // Update password
            $admin->update([
                'password' => Hash::make($validated['password'])
            ]);

            $message = $lupaPassword 
                ? 'Password berhasil direset!' 
                : 'Password berhasil diubah!';

            return redirect()->route('admin.profile.show')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            Log::error('Error updating admin password', [
                'admin_id' => auth()->guard('admin')->id(),
                'exception' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui password: ' . $e->getMessage());
        }
    }

    public function updatePhoto(Request $request)
    {
        try {
            /** @var \App\Models\Admin $admin */
            $admin = auth()->guard('admin')->user();

            $validated = $request->validate([
                'photo' => 'required|image|mimes:jpeg,jpg,png|max:2048', // 2MB max
            ], [
                'photo.required' => 'Foto profil wajib diupload',
                'photo.image' => 'File harus berupa gambar',
                'photo.mimes' => 'Format foto harus jpeg, jpg, atau png',
                'photo.max' => 'Ukuran foto maksimal 2MB',
            ]);

            // Delete old photo if exists
            if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
            }

            // Upload and process new photo
            $file = $request->file('photo');
            $filename = 'admin_' . $admin->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Create image manager with GD driver
            $manager = new ImageManager(new Driver());
            
            // Read and resize image to 400x400
            $image = $manager->read($file);
            $image->cover(400, 400);
            
            // Save to storage
            $path = 'admins/photos/' . $filename;
            Storage::disk('public')->put($path, (string) $image->encode());

            // Update admin photo path
            $admin->update(['avatar' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diupdate!',
                'photo_url' => asset('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating admin photo', [
                'admin_id' => auth()->guard('admin')->id(),
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload foto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deletePhoto()
    {
        try {
            /** @var \App\Models\Admin $admin */
            $admin = auth()->guard('admin')->user();

            if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
            }

            $admin->update(['avatar' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting admin photo', [
                'admin_id' => auth()->guard('admin')->id(),
                'exception' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto: ' . $e->getMessage()
            ], 500);
        }
    }
}
