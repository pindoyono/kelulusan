<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'sekolah_id', 'nisn', 'nis', 'nama', 'jenis_kelamin',
        'kelas', 'jurusan', 'tempat_lahir', 'tanggal_lahir', 'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function kelulusan()
    {
        return $this->hasOne(Kelulusan::class);
    }
}
