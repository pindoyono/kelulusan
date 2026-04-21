<?php

namespace Database\Seeders;

use App\Models\Kelulusan;
use App\Models\Pengumuman;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // --- Buat Admin User ---
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@kelulusan.test',
            'password' => Hash::make('password'),
        ]);

        // --- Buat Sekolah ---
        $sekolahs = [
            ['nama' => 'SMAN 1 Malinau', 'npsn' => '30401001', 'jenis' => 'SMA', 'alamat' => 'Jl. Jend. Sudirman No. 1', 'kota' => 'Malinau', 'is_active' => true],
            ['nama' => 'SMAN 2 Malinau', 'npsn' => '30401002', 'jenis' => 'SMA', 'alamat' => 'Jl. Merdeka No. 10', 'kota' => 'Malinau', 'is_active' => true],
            ['nama' => 'SMKN 1 Malinau', 'npsn' => '30401003', 'jenis' => 'SMK', 'alamat' => 'Jl. Pendidikan No. 5', 'kota' => 'Malinau', 'is_active' => true],
            ['nama' => 'SMKN 2 Malinau', 'npsn' => '30401004', 'jenis' => 'SMK', 'alamat' => 'Jl. Kartini No. 15', 'kota' => 'Malinau', 'is_active' => true],
            ['nama' => 'SMAN 1 Tarakan', 'npsn' => '30402001', 'jenis' => 'SMA', 'alamat' => 'Jl. Gajah Mada No. 3', 'kota' => 'Tarakan', 'is_active' => true],
            ['nama' => 'SMKN 1 Tarakan', 'npsn' => '30402002', 'jenis' => 'SMK', 'alamat' => 'Jl. Diponegoro No. 8', 'kota' => 'Tarakan', 'is_active' => true],
        ];

        $sekolahModels = [];
        foreach ($sekolahs as $data) {
            $sekolahModels[] = Sekolah::create($data);
        }

        // --- Buat Pengumuman ---
        $pengumuman = Pengumuman::create([
            'judul' => 'Pengumuman Kelulusan Siswa Kelas XII Tahun Ajaran 2025/2026',
            'tahun_ajaran' => '2025/2026',
            'tanggal_pengumuman' => '2026-05-02',
            'deskripsi' => '<p>Dengan ini diumumkan hasil kelulusan peserta didik kelas XII SMA/SMK Tahun Ajaran 2025/2026. Siswa dapat mengecek status kelulusan dengan memasukkan NISN pada halaman pencarian.</p><p>Selamat kepada seluruh siswa yang telah dinyatakan <strong>LULUS</strong>. Bagi yang belum lulus, tetap semangat!</p>',
            'is_published' => true,
        ]);

        $pengumuman2 = Pengumuman::create([
            'judul' => 'Pengumuman Kelulusan Siswa Kelas XII Tahun Ajaran 2024/2025',
            'tahun_ajaran' => '2024/2025',
            'tanggal_pengumuman' => '2025-05-03',
            'deskripsi' => '<p>Pengumuman kelulusan tahun ajaran 2024/2025 telah selesai.</p>',
            'is_published' => false,
        ]);

        // --- Data Siswa SMA ---
        $siswasSMA = [
            // SMAN 1 Malinau
            ['sekolah_idx' => 0, 'nisn' => '0051234501', 'nis' => 'SMA001', 'nama' => 'Ahmad Fauzi', 'jenis_kelamin' => 'L', 'kelas' => 'XII IPA 1', 'jurusan' => 'IPA', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-03-15'],
            ['sekolah_idx' => 0, 'nisn' => '0051234502', 'nis' => 'SMA002', 'nama' => 'Siti Nurhaliza', 'jenis_kelamin' => 'P', 'kelas' => 'XII IPA 1', 'jurusan' => 'IPA', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-05-22'],
            ['sekolah_idx' => 0, 'nisn' => '0051234503', 'nis' => 'SMA003', 'nama' => 'Budi Santoso', 'jenis_kelamin' => 'L', 'kelas' => 'XII IPA 2', 'jurusan' => 'IPA', 'tempat_lahir' => 'Tarakan', 'tanggal_lahir' => '2008-01-10'],
            ['sekolah_idx' => 0, 'nisn' => '0051234504', 'nis' => 'SMA004', 'nama' => 'Dewi Lestari', 'jenis_kelamin' => 'P', 'kelas' => 'XII IPS 1', 'jurusan' => 'IPS', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-07-03'],
            ['sekolah_idx' => 0, 'nisn' => '0051234505', 'nis' => 'SMA005', 'nama' => 'Rizki Pratama', 'jenis_kelamin' => 'L', 'kelas' => 'XII IPS 1', 'jurusan' => 'IPS', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-09-12'],
            ['sekolah_idx' => 0, 'nisn' => '0051234506', 'nis' => 'SMA006', 'nama' => 'Anisa Putri', 'jenis_kelamin' => 'P', 'kelas' => 'XII IPA 1', 'jurusan' => 'IPA', 'tempat_lahir' => 'Samarinda', 'tanggal_lahir' => '2008-11-25'],

            // SMAN 2 Malinau
            ['sekolah_idx' => 1, 'nisn' => '0051234601', 'nis' => 'SMA101', 'nama' => 'Muhammad Irfan', 'jenis_kelamin' => 'L', 'kelas' => 'XII IPA 1', 'jurusan' => 'IPA', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-02-14'],
            ['sekolah_idx' => 1, 'nisn' => '0051234602', 'nis' => 'SMA102', 'nama' => 'Nur Aisyah', 'jenis_kelamin' => 'P', 'kelas' => 'XII IPS 1', 'jurusan' => 'IPS', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-04-28'],
            ['sekolah_idx' => 1, 'nisn' => '0051234603', 'nis' => 'SMA103', 'nama' => 'Dimas Aditya', 'jenis_kelamin' => 'L', 'kelas' => 'XII IPS 1', 'jurusan' => 'IPS', 'tempat_lahir' => 'Tarakan', 'tanggal_lahir' => '2008-06-17'],
            ['sekolah_idx' => 1, 'nisn' => '0051234604', 'nis' => 'SMA104', 'nama' => 'Fitriani Rahman', 'jenis_kelamin' => 'P', 'kelas' => 'XII IPA 1', 'jurusan' => 'IPA', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-08-09'],

            // SMAN 1 Tarakan
            ['sekolah_idx' => 4, 'nisn' => '0051234701', 'nis' => 'SMA201', 'nama' => 'Andi Wijaya', 'jenis_kelamin' => 'L', 'kelas' => 'XII IPA 1', 'jurusan' => 'IPA', 'tempat_lahir' => 'Tarakan', 'tanggal_lahir' => '2008-01-20'],
            ['sekolah_idx' => 4, 'nisn' => '0051234702', 'nis' => 'SMA202', 'nama' => 'Rina Marlina', 'jenis_kelamin' => 'P', 'kelas' => 'XII IPA 1', 'jurusan' => 'IPA', 'tempat_lahir' => 'Tarakan', 'tanggal_lahir' => '2008-03-30'],
            ['sekolah_idx' => 4, 'nisn' => '0051234703', 'nis' => 'SMA203', 'nama' => 'Hendra Saputra', 'jenis_kelamin' => 'L', 'kelas' => 'XII IPS 1', 'jurusan' => 'IPS', 'tempat_lahir' => 'Tarakan', 'tanggal_lahir' => '2008-05-11'],
        ];

        // --- Data Siswa SMK ---
        $siswasSMK = [
            // SMKN 1 Malinau
            ['sekolah_idx' => 2, 'nisn' => '0052234501', 'nis' => 'SMK001', 'nama' => 'Yoga Pratama', 'jenis_kelamin' => 'L', 'kelas' => 'XII TKJ 1', 'jurusan' => 'TKJ', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-02-05'],
            ['sekolah_idx' => 2, 'nisn' => '0052234502', 'nis' => 'SMK002', 'nama' => 'Citra Dewi', 'jenis_kelamin' => 'P', 'kelas' => 'XII TKJ 1', 'jurusan' => 'TKJ', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-04-18'],
            ['sekolah_idx' => 2, 'nisn' => '0052234503', 'nis' => 'SMK003', 'nama' => 'Fajar Nugroho', 'jenis_kelamin' => 'L', 'kelas' => 'XII AKL 1', 'jurusan' => 'AKL', 'tempat_lahir' => 'Tarakan', 'tanggal_lahir' => '2008-06-22'],
            ['sekolah_idx' => 2, 'nisn' => '0052234504', 'nis' => 'SMK004', 'nama' => 'Maya Sari', 'jenis_kelamin' => 'P', 'kelas' => 'XII AKL 1', 'jurusan' => 'AKL', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-08-14'],
            ['sekolah_idx' => 2, 'nisn' => '0052234505', 'nis' => 'SMK005', 'nama' => 'Rendi Setiawan', 'jenis_kelamin' => 'L', 'kelas' => 'XII RPL 1', 'jurusan' => 'RPL', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-10-01'],
            ['sekolah_idx' => 2, 'nisn' => '0052234506', 'nis' => 'SMK006', 'nama' => 'Lina Oktaviani', 'jenis_kelamin' => 'P', 'kelas' => 'XII RPL 1', 'jurusan' => 'RPL', 'tempat_lahir' => 'Samarinda', 'tanggal_lahir' => '2008-12-09'],

            // SMKN 2 Malinau
            ['sekolah_idx' => 3, 'nisn' => '0052234601', 'nis' => 'SMK101', 'nama' => 'Eko Kurniawan', 'jenis_kelamin' => 'L', 'kelas' => 'XII TBSM 1', 'jurusan' => 'TBSM', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-01-27'],
            ['sekolah_idx' => 3, 'nisn' => '0052234602', 'nis' => 'SMK102', 'nama' => 'Indah Permata', 'jenis_kelamin' => 'P', 'kelas' => 'XII OTKP 1', 'jurusan' => 'OTKP', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-03-19'],
            ['sekolah_idx' => 3, 'nisn' => '0052234603', 'nis' => 'SMK103', 'nama' => 'Wahyu Hidayat', 'jenis_kelamin' => 'L', 'kelas' => 'XII TBSM 1', 'jurusan' => 'TBSM', 'tempat_lahir' => 'Tarakan', 'tanggal_lahir' => '2008-05-08'],
            ['sekolah_idx' => 3, 'nisn' => '0052234604', 'nis' => 'SMK104', 'nama' => 'Putri Rahayu', 'jenis_kelamin' => 'P', 'kelas' => 'XII OTKP 1', 'jurusan' => 'OTKP', 'tempat_lahir' => 'Malinau', 'tanggal_lahir' => '2008-07-15'],

            // SMKN 1 Tarakan
            ['sekolah_idx' => 5, 'nisn' => '0052234701', 'nis' => 'SMK201', 'nama' => 'Arif Rahman', 'jenis_kelamin' => 'L', 'kelas' => 'XII TKJ 1', 'jurusan' => 'TKJ', 'tempat_lahir' => 'Tarakan', 'tanggal_lahir' => '2008-09-23'],
            ['sekolah_idx' => 5, 'nisn' => '0052234702', 'nis' => 'SMK202', 'nama' => 'Sinta Maharani', 'jenis_kelamin' => 'P', 'kelas' => 'XII MM 1', 'jurusan' => 'MM', 'tempat_lahir' => 'Tarakan', 'tanggal_lahir' => '2008-11-11'],
            ['sekolah_idx' => 5, 'nisn' => '0052234703', 'nis' => 'SMK203', 'nama' => 'Bayu Firmansyah', 'jenis_kelamin' => 'L', 'kelas' => 'XII MM 1', 'jurusan' => 'MM', 'tempat_lahir' => 'Tarakan', 'tanggal_lahir' => '2008-02-28'],
        ];

        $allSiswas = array_merge($siswasSMA, $siswasSMK);

        // --- Insert Siswa & Kelulusan ---
        foreach ($allSiswas as $i => $data) {
            $sekolahIdx = $data['sekolah_idx'];
            unset($data['sekolah_idx']);
            $data['sekolah_id'] = $sekolahModels[$sekolahIdx]->id;

            $siswa = Siswa::create($data);

            // 85% lulus, 15% tidak lulus
            $isLulus = $i % 7 !== 0 || $i === 0;
            $nilaiBase = $isLulus ? rand(7500, 9500) / 100 : rand(4000, 5500) / 100;

            Kelulusan::create([
                'siswa_id' => $siswa->id,
                'pengumuman_id' => $pengumuman->id,
                'status' => $isLulus ? 'lulus' : 'tidak_lulus',
                'nilai_rata_rata' => $nilaiBase,
                'keterangan' => $isLulus
                    ? ($nilaiBase >= 90 ? 'Lulus dengan predikat sangat baik' : ($nilaiBase >= 80 ? 'Lulus dengan predikat baik' : 'Lulus'))
                    : 'Tidak memenuhi syarat kelulusan',
            ]);
        }
    }
}
