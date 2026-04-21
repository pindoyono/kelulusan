<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelulusan extends Model
{
    protected $fillable = [
        'siswa_id', 'pengumuman_id', 'status', 'keterangan', 'nilai_rata_rata', 'skl_path',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pengumuman()
    {
        return $this->belongsTo(Pengumuman::class);
    }
}
