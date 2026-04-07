<?php

namespace App\Services;

use App\Repositories\StrukturOrganisasiRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StrukturOrganisasiService
{
    protected $repository;
    protected $imageUploadService;

    /**
     * Constructor - Inject repository dan image upload service
     * Service untuk manage Struktur Organisasi Desa (Kepala Desa, Sekdes, dll)
     */
    public function __construct(
        StrukturOrganisasiRepository $repository,
        ImageUploadService $imageUploadService
    ) {
        $this->repository = $repository;
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Get all struktur organisasi dengan pagination untuk admin list
     */
    public function getPaginatedStrukturOrganisasi($perPage = 15)
    {
        return $this->repository->getPaginated($perPage);
    }

    /**
     * Get hanya struktur organisasi aktif untuk ditampilkan ke public
     */
    public function getActiveStrukturOrganisasi()
    {
        return $this->repository->getActive();
    }

    /**
     * Get data terstruktur untuk tampilan hierarki organisasi
     * Dikelompokkan berdasarkan jabatan dan urutan
     */
    public function getStructuredData()
    {
        return $this->repository->getStructuredData();
    }

    /**
     * Get single struktur organisasi by ID untuk detail atau edit
     */
    public function getStrukturOrganisasiById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Create struktur organisasi baru
     * - Upload foto profile 800x800 (square) jika ada
     * - Throw exception jika gagal untuk rollback
     */
    public function createStrukturOrganisasi(array $data)
    {
        try {
            // Handle foto upload
            if (isset($data['foto']) && $data['foto']) {
                $data['foto'] = $this->imageUploadService->upload(
                    $data['foto'],
                    'struktur-organisasi',
                    800, // width
                    800  // height (square for profile photos)
                );
            }

            return $this->repository->create($data);
        } catch (\Exception $e) {
            Log::error('Error creating struktur organisasi: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update struktur organisasi
     * - Jika ada foto baru, delete foto lama lalu upload baru
     * - Jika tidak ada foto baru, keep foto lama (unset dari data)
     */
    public function updateStrukturOrganisasi($id, array $data)
    {
        try {
            $strukturOrganisasi = $this->repository->find($id);

            // Handle foto upload
            if (isset($data['foto']) && $data['foto']) {
                // Delete old foto
                if ($strukturOrganisasi->foto && Storage::disk('public')->exists($strukturOrganisasi->foto)) {
                    Storage::disk('public')->delete($strukturOrganisasi->foto);
                }

                // Upload new foto
                $data['foto'] = $this->imageUploadService->upload(
                    $data['foto'],
                    'struktur-organisasi',
                    800,
                    800
                );
            } else {
                // Remove foto from data if not provided
                unset($data['foto']);
            }

            return $this->repository->update($id, $data);
        } catch (\Exception $e) {
            Log::error('Error updating struktur organisasi: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete struktur organisasi beserta foto profile-nya
     * Log error jika gagal delete foto tapi tetap delete record
     */
    public function deleteStrukturOrganisasi($id)
    {
        try {
            $strukturOrganisasi = $this->repository->find($id);

            // Delete foto
            if ($strukturOrganisasi->foto && Storage::disk('public')->exists($strukturOrganisasi->foto)) {
                Storage::disk('public')->delete($strukturOrganisasi->foto);
            }

            return $this->repository->delete($id);
        } catch (\Exception $e) {
            Log::error('Error deleting struktur organisasi: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Bulk delete multiple struktur organisasi sekaligus
     * Loop semua ID dan delete satu per satu (include foto)
     */
    public function bulkDelete(array $ids)
    {
        try {
            foreach ($ids as $id) {
                $this->deleteStrukturOrganisasi($id);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Error bulk deleting struktur organisasi: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update urutan tampilan untuk sorting hierarki
     * Digunakan saat drag & drop reorder di admin
     */
    public function updateUrutan($id, $urutan)
    {
        return $this->repository->updateUrutan($id, $urutan);
    }
}
