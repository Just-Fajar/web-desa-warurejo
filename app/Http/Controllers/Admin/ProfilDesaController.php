<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilDesaRequest;
use App\Models\ProfilDesa;
use App\Services\ImageUploadService;
use App\Services\HtmlSanitizerService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfilDesaController extends Controller
{
    protected $imageUploadService;
    protected $htmlSanitizer;

    public function __construct(
        ImageUploadService $imageUploadService,
        HtmlSanitizerService $htmlSanitizer
    ) {
        $this->imageUploadService = $imageUploadService;
        $this->htmlSanitizer = $htmlSanitizer;
    }

    /**
     * Show the form for editing the profil desa.
     */
    public function edit()
    {
        $profil = ProfilDesa::getInstance();
        
        return view('admin.profil-desa.edit', compact('profil'));
    }

    /**
     * Update the profil desa in storage.
     */
    public function update(ProfilDesaRequest $request)
    {
        try {
            $profil = ProfilDesa::getInstance();
            
            // Prepare data - only gambar_header and struktur_organisasi
            $data = [];
            
            // Handle gambar header upload (banner homepage)
            if ($request->hasFile('gambar_header')) {
                // Delete old gambar
                if ($profil->gambar_header && Storage::disk('public')->exists($profil->gambar_header)) {
                    Storage::disk('public')->delete($profil->gambar_header);
                }
                
                // Upload new gambar header (wide banner)
                $data['gambar_header'] = $this->imageUploadService->upload(
                    $request->file('gambar_header'),
                    'profil-desa/header',
                    1920,
                    null
                );
            }
            
            // Handle struktur organisasi upload
            if ($request->hasFile('struktur_organisasi')) {
                // Delete old gambar
                if ($profil->struktur_organisasi && Storage::disk('public')->exists($profil->struktur_organisasi)) {
                    Storage::disk('public')->delete($profil->struktur_organisasi);
                }
                
                // Upload new struktur organisasi
                $data['struktur_organisasi'] = $this->imageUploadService->upload(
                    $request->file('struktur_organisasi'),
                    'profil-desa/struktur',
                    1920,
                    null
                );
            }
            
            // Update profil (only if there's data to update)
            if (!empty($data)) {
                $profil->update($data);
            }
            
            return redirect()
                ->route('admin.profil-desa.edit')
                ->with('success', 'Gambar profil desa berhasil diperbarui!');
                
        } catch (\Exception $e) {
            Log::error('Error updating profil desa: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui gambar: ' . $e->getMessage());
        }
    }
}
