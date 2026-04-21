<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $fillable = [
        'judul', 'tahun_ajaran', 'tanggal_pengumuman', 'deskripsi', 'is_published',
    ];

    protected $casts = [
        'tanggal_pengumuman' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function kelulusans()
    {
        return $this->hasMany(Kelulusan::class);
    }
}
