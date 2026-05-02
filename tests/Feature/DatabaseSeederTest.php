<?php

namespace Tests\Feature;

use App\Models\Kelulusan;
use App\Models\Pengumuman;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_expected_data(): void
    {
        $this->seed(\Database\Seeders\DummyDataSeeder::class);

        // Admin user
        $this->assertDatabaseHas('users', [
            'email' => 'admin@kelulusan.test',
        ]);

        // 6 schools
        $this->assertEquals(6, Sekolah::count());

        // 2 announcements
        $this->assertEquals(2, Pengumuman::count());

        // 26 students (13 SMA + 13 SMK)
        $this->assertEquals(26, Siswa::count());

        // 26 kelulusan records
        $this->assertEquals(26, Kelulusan::count());
    }

    public function test_seeder_creates_published_pengumuman(): void
    {
        $this->seed(\Database\Seeders\DummyDataSeeder::class);

        $this->assertGreaterThanOrEqual(1, Pengumuman::where('is_published', true)->count());
    }

    public function test_seeder_creates_both_school_types(): void
    {
        $this->seed(\Database\Seeders\DummyDataSeeder::class);

        $this->assertGreaterThan(0, Sekolah::where('jenis', 'SMA')->count());
        $this->assertGreaterThan(0, Sekolah::where('jenis', 'SMK')->count());
    }

    public function test_seeder_creates_kelulusan_with_both_statuses(): void
    {
        $this->seed(\Database\Seeders\DummyDataSeeder::class);

        $this->assertGreaterThan(0, Kelulusan::where('status', 'lulus')->count());
        $this->assertGreaterThan(0, Kelulusan::where('status', 'tidak_lulus')->count());
    }

    public function test_seeder_creates_active_schools(): void
    {
        $this->seed(\Database\Seeders\DummyDataSeeder::class);

        $this->assertTrue(Sekolah::where('is_active', true)->exists());
    }
}
