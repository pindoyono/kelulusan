<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $fillable = [
        'nama', 'npsn', 'jenis', 'alamat', 'kota', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
