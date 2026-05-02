<?php

namespace Tests\Unit\Models;

use App\Models\Kelulusan;
use App\Models\Pengumuman;
use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengumumanTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_pengumuman(): void
    {
        $pengumuman = Pengumuman::create([
            'judul' => 'Pengumuman Kelulusan 2025/2026',
            'tahun_ajaran' => '2025/2026',
            'tanggal_pengumuman' => '2026-05-01',
            'deskripsi' => 'Selamat kepada seluruh siswa.',
            'is_published' => true,
        ]);

        $this->assertDatabaseHas('pengumumen', [
            'judul' => 'Pengumuman Kelulusan 2025/2026',
            'tahun_ajaran' => '2025/2026',
        ]);
    }

    public function test_pengumuman_has_many_kelulusans(): void
    {
        $sekolah = Sekolah::create([
            'nama' => 'SMA Test',
            'npsn' => '30402222',
            'jenis' => 'SMA',
        ]);

        $pengumuman = Pengumuman::create([
            'judul' => 'Kelulusan 2026',
            'tahun_ajaran' => '2025/2026',
            'tanggal_pengumuman' => '2026-05-01',
            'is_published' => true,
        ]);

        $siswa1 = Siswa::create([
            'sekolah_id' => $sekolah->id,
            'nisn' => '0011111111',
            'nama' => 'Siswa A',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII',
        ]);

        $siswa2 = Siswa::create([
            'sekolah_id' => $sekolah->id,
            'nisn' => '0022222222',
            'nama' => 'Siswa B',
            'jenis_kelamin' => 'P',
            'kelas' => 'XII',
        ]);

        Kelulusan::create([
            'siswa_id' => $siswa1->id,
            'pengumuman_id' => $pengumuman->id,
            'status' => 'lulus',
        ]);

        Kelulusan::create([
            'siswa_id' => $siswa2->id,
            'pengumuman_id' => $pengumuman->id,
            'status' => 'tidak_lulus',
        ]);

        $this->assertCount(2, $pengumuman->kelulusans);
        $this->assertInstanceOf(Kelulusan::class, $pengumuman->kelulusans->first());
    }

    public function test_tanggal_pengumuman_is_cast_to_date(): void
    {
        $pengumuman = Pengumuman::create([
            'judul' => 'Test',
            'tahun_ajaran' => '2025/2026',
            'tanggal_pengumuman' => '2026-05-01',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $pengumuman->tanggal_pengumuman);
    }

    public function test_is_published_is_cast_to_boolean(): void
    {
        $pengumuman = Pengumuman::create([
            'judul' => 'Test',
            'tahun_ajaran' => '2025/2026',
            'tanggal_pengumuman' => '2026-05-01',
            'is_published' => 1,
        ]);

        $this->assertIsBool($pengumuman->is_published);
        $this->assertTrue($pengumuman->is_published);
    }

    public function test_is_published_defaults_to_false(): void
    {
        $pengumuman = Pengumuman::create([
            'judul' => 'Test',
            'tahun_ajaran' => '2025/2026',
            'tanggal_pengumuman' => '2026-05-01',
        ]);

        $this->assertFalse($pengumuman->fresh()->is_published);
    }

    public function test_deskripsi_is_nullable(): void
    {
        $pengumuman = Pengumuman::create([
            'judul' => 'Test',
            'tahun_ajaran' => '2025/2026',
            'tanggal_pengumuman' => '2026-05-01',
        ]);

        $this->assertNull($pengumuman->deskripsi);
    }

    public function test_fillable_attributes(): void
    {
        $pengumuman = new Pengumuman();
        $expected = ['judul', 'tahun_ajaran', 'tanggal_pengumuman', 'deskripsi', 'is_published'];
        $this->assertEquals($expected, $pengumuman->getFillable());
    }
}
