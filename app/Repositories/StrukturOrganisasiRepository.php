<?php

namespace App\Repositories;

use App\Models\StrukturOrganisasi;

class StrukturOrganisasiRepository extends BaseRepository
{
    /**
     * Constructor - Inject StrukturOrganisasi model
     * Repository untuk database queries struktur organisasi desa
     */
    public function __construct(StrukturOrganisasi $model)
    {
        parent::__construct($model);
    }

    /**
     * Get semua struktur organisasi aktif dengan ordering
     * Untuk halaman public struktur organisasi
     */
    public function getActive()
    {
        return $this->model
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Get all struktur organisasi dengan pagination untuk admin list
     * Include inactive untuk management
     */
    public function getPaginated($perPage = 15)
    {
        return $this->model
            ->ordered()
            ->paginate($perPage);
    }

    /**
     * Get struktur organisasi by level hierarki
     * Level: 1=Kepala, 2=Sekretaris, 3=Kaur/Kasi, dll
     */
    public function getByLevel($level)
    {
        return $this->model
            ->active()
            ->byLevel($level)
            ->ordered()
            ->get();
    }

    /**
     * Get Kepala Desa (top hierarchy)
     * Return single record atau null
     */
    public function getKepalaDesa()
    {
        return $this->model
            ->active()
            ->kepala()
            ->first();
    }

    /**
     * Get Sekretaris Desa (second hierarchy)
     * Return single record atau null
     */
    public function getSekretarisDesa()
    {
        return $this->model
            ->active()
            ->sekretaris()
            ->first();
    }

    /**
     * Get semua Kaur (Kepala Urusan)
     * Return collection, sorted by urutan
     */
    public function getKaur()
    {
        return $this->model
            ->active()
            ->kaur()
            ->ordered()
            ->get();
    }

    /**
     * Get semua Staff Kaur (bawahan Kaur)
     * Return collection, sorted by urutan
     */
    public function getStaffKaur()
    {
        return $this->model
            ->active()
            ->staffKaur()
            ->ordered()
            ->get();
    }

    /**
     * Get semua Kasi (Kepala Seksi)
     * Return collection, sorted by urutan
     */
    public function getKasi()
    {
        return $this->model
            ->active()
            ->kasi()
            ->ordered()
            ->get();
    }

    /**
     * Get semua Staff Kasi (bawahan Kasi)
     * Return collection, sorted by urutan
     */
    public function getStaffKasi()
    {
        return $this->model
            ->active()
            ->staffKasi()
            ->ordered()
            ->get();
    }

    /**
     * Get data terstruktur untuk tampilan hierarki organisasi
     * Return array dengan keys: kepala, sekretaris, kaur, staff_kaur, kasi, staff_kasi
     * Untuk display di halaman public dengan hierarki jelas
     */
    public function getStructuredData()
    {
        return [
            'kepala' => $this->getKepalaDesa(),
            'sekretaris' => $this->getSekretarisDesa(),
            'kaur' => $this->getKaur(),
            'staff_kaur' => $this->getStaffKaur(),
            'kasi' => $this->getKasi(),
            'staff_kasi' => $this->getStaffKasi(),
        ];
    }

    /**
     * Get semua bawahan berdasarkan atasan_id
     * Untuk hierarki nested/tree structure
     */
    public function getBawahanByAtasan($atasanId)
    {
        return $this->model
            ->where('atasan_id', $atasanId)
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Update urutan tampilan untuk sorting
     * Digunakan saat drag & drop reorder
     */
    public function updateUrutan($id, $urutan)
    {
        return $this->update($id, ['urutan' => $urutan]);
    }
}
