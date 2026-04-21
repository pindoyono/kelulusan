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
        $pengumuman = Pengumuman::where('is_published', true)
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

        $pengumuman = Pengumuman::where('is_published', true)
            ->orderBy('tanggal_pengumuman', 'desc')
            ->first();

        // Block search if countdown is still active
        if ($pengumuman && $pengumuman->tanggal_pengumuman->isFuture()) {
            return redirect()->route('cek-kelulusan');
        }

        $siswa = Siswa::where('nisn', $request->nisn)->first();

        $kelulusan = null;
        if ($siswa) {
            $kelulusan = Kelulusan::with(['siswa.sekolah', 'pengumuman'])
                ->where('siswa_id', $siswa->id)
                ->whereHas('pengumuman', fn ($q) => $q->where('is_published', true))
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
