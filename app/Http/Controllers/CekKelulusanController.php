<?php

namespace App\Http\Controllers;

use App\Models\Kelulusan;
use App\Models\Pengumuman;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CekKelulusanController extends Controller
{
    public function index()
    {
        // Show only global announcements (sekolah_id is null) on homepage
        $pengumuman = Pengumuman::where('is_published', true)
            ->whereNull('sekolah_id')
            ->orderBy('tanggal_pengumuman', 'desc')
            ->first();

        $showCountdown = false;
        if ($pengumuman && $pengumuman->tanggal_pengumuman->isFuture()) {
            $showCountdown = true;
        }

        return view('cek-kelulusan', compact('pengumuman', 'showCountdown'));
    }

    public function cari(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|max:20',
        ]);

        $siswa = Siswa::where('nisn', $request->nisn)->first();

        // Get pengumuman based on siswa's sekolah or global
        $pengumumanQuery = Pengumuman::where('is_published', true);

        if ($siswa) {
            // Filter: pengumuman untuk sekolah siswa atau pengumuman global (sekolah_id null)
            $pengumumanQuery->where(function ($q) use ($siswa) {
                $q->where('sekolah_id', $siswa->sekolah_id)
                  ->orWhereNull('sekolah_id');
            });
        } else {
            // Jika siswa tidak ditemukan, hanya tampilkan pengumuman global
            $pengumumanQuery->whereNull('sekolah_id');
        }

        $pengumuman = $pengumumanQuery->orderBy('tanggal_pengumuman', 'desc')->first();

        // Block search if countdown is still active
        if ($pengumuman && $pengumuman->tanggal_pengumuman->isFuture()) {
            return redirect()->route('cek-kelulusan');
        }

        $kelulusan = null;
        if ($siswa) {
            $kelulusan = Kelulusan::with(['siswa.sekolah', 'pengumuman'])
                ->where('siswa_id', $siswa->id)
                ->whereHas('pengumuman', function ($q) use ($siswa) {
                    $q->where('is_published', true)
                      ->where(function ($query) use ($siswa) {
                          $query->where('sekolah_id', $siswa->sekolah_id)
                                ->orWhereNull('sekolah_id');
                      });
                })
                ->latest()
                ->first();
        }

        $showCountdown = false;
        if ($pengumuman && $pengumuman->tanggal_pengumuman->isFuture()) {
            $showCountdown = true;
        }

        return view('cek-kelulusan', compact('pengumuman', 'siswa', 'kelulusan', 'showCountdown'));
    }

    public function downloadSkl(Kelulusan $kelulusan)
    {
        if (!$kelulusan->skl_path || !Storage::disk('public')->exists($kelulusan->skl_path)) {
            abort(404);
        }

        $siswa = $kelulusan->siswa;
        $filename = 'SKL_' . ($siswa ? $siswa->nisn : $kelulusan->id) . '.pdf';

        return Storage::disk('public')->download($kelulusan->skl_path, $filename);
    }
}
