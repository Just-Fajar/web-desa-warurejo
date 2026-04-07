<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriImage extends Model
{
    protected $fillable = [
        'galeri_id',
        'image_path',
        'urutan',
    ];

    // Relationship
    public function galeri()
    {
        return $this->belongsTo(Galeri::class);
    }

    // Accessor
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
