<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SekolahMalinauSeeder extends Seeder
{
    public function run(): void
    {
        // --- Buat Role admin_sekolah jika belum ada ---
        $role = Role::firstOrCreate(['name' => 'admin_sekolah', 'guard_name' => 'web']);

        // Assign permissions untuk admin_sekolah
        $permissions = [
            'view_any_kelulusan', 'view_kelulusan', 'create_kelulusan', 'update_kelulusan', 'delete_kelulusan',
            'view_any_siswa', 'view_siswa', 'create_siswa', 'update_siswa', 'delete_siswa',
            'view_any_sekolah', 'view_sekolah', 'update_sekolah',
            'view_any_pengumuman', 'view_pengumuman',
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        $role->syncPermissions($permissions);

        // --- Data SMA/SMK Se-Kabupaten Malinau ---
        $sekolahs = [
            // SMA
            ['nama' => 'SMAN 1 Malinau',           'npsn' => '30402153', 'jenis' => 'SMA', 'alamat' => 'Jl. Jend. Sudirman, Malinau Kota',             'kota' => 'Malinau', 'is_active' => true],
            ['nama' => 'SMAN 2 Malinau',           'npsn' => '30402154', 'jenis' => 'SMA', 'alamat' => 'Jl. Raja Pandita, Malinau Kota',               'kota' => 'Malinau', 'is_active' => true],
            ['nama' => 'SMAN 3 Malinau',           'npsn' => '30402155', 'jenis' => 'SMA', 'alamat' => 'Jl. Tanjung Lapang, Malinau Barat',            'kota' => 'Malinau', 'is_active' => true],
            ['nama' => 'SMAN 1 Mentarang',         'npsn' => '30402156', 'jenis' => 'SMA', 'alamat' => 'Jl. Mentarang Baru, Mentarang',                'kota' => 'Malinau', 'is_active' => true],
            ['nama' => 'SMAN 1 Malinau Selatan',   'npsn' => '30402157', 'jenis' => 'SMA', 'alamat' => 'Jl. Long Loreh, Malinau Selatan',              'kota' => 'Malinau', 'is_active' => true],
            ['nama' => 'SMA Kristen Malinau',       'npsn' => '30402158', 'jenis' => 'SMA', 'alamat' => 'Jl. Pelita Kanaan, Malinau Kota',             'kota' => 'Malinau', 'is_active' => true],
            // SMK
            ['nama' => 'SMKN 1 Malinau',           'npsn' => '30402168', 'jenis' => 'SMK', 'alamat' => 'Jl. Pendidikan No. 1, Malinau Kota',           'kota' => 'Malinau', 'is_active' => true],
            ['nama' => 'SMKN 2 Malinau',           'npsn' => '30402169', 'jenis' => 'SMK', 'alamat' => 'Jl. Batu Lidung, Malinau Kota',                'kota' => 'Malinau', 'is_active' => true],
            ['nama' => 'SMKN 1 Mentarang',         'npsn' => '30402170', 'jenis' => 'SMK', 'alamat' => 'Jl. Long Bisai, Mentarang',                    'kota' => 'Malinau', 'is_active' => true],
            ['nama' => 'SMK Kristen Malinau',       'npsn' => '30402171', 'jenis' => 'SMK', 'alamat' => 'Jl. Pelita Kanaan, Malinau Kota',             'kota' => 'Malinau', 'is_active' => true],
        ];

        foreach ($sekolahs as $data) {
            $sekolah = Sekolah::firstOrCreate(
                ['npsn' => $data['npsn']],
                $data
            );

            // Buat akun pengguna admin untuk setiap sekolah
            $emailSlug = strtolower(str_replace([' ', '.'], ['', ''], $data['nama']));
            $email = $emailSlug . '@kelulusan.test';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Admin ' . $data['nama'],
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'sekolah_id' => $sekolah->id,
                ]
            );

            $user->assignRole('admin_sekolah');
        }

        // --- Buat Super Admin jika belum ada ---
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@kelulusan.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->assignRole('super_admin');
    }
}
