<?php

namespace Tests\Unit\Models;

use App\Models\Kelulusan;
use App\Models\Pengumuman;
use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KelulusanTest extends TestCase
{
    use RefreshDatabase;

    private function createBaseData(): array
    {
        $sekolah = Sekolah::create([
            'nama' => 'SMA Test',
            'npsn' => '30403333',
            'jenis' => 'SMA',
        ]);

        $siswa = Siswa::create([
            'sekolah_id' => $sekolah->id,
            'nisn' => '0033333333',
            'nama' => 'Test Siswa',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII IPA 1',
        ]);

        $pengumuman = Pengumuman::create([
            'judul' => 'Kelulusan 2026',
            'tahun_ajaran' => '2025/2026',
            'tanggal_pengumuman' => '2026-05-01',
            'is_published' => true,
        ]);

        return compact('sekolah', 'siswa', 'pengumuman');
    }

    public function test_can_create_kelulusan_lulus(): void
    {
        $data = $this->createBaseData();

        $kelulusan = Kelulusan::create([
            'siswa_id' => $data['siswa']->id,
            'pengumuman_id' => $data['pengumuman']->id,
            'status' => 'lulus',
            'keterangan' => 'Selamat!',
            'nilai_rata_rata' => 87.50,
        ]);

        $this->assertDatabaseHas('kelulusans', [
            'siswa_id' => $data['siswa']->id,
            'status' => 'lulus',
            'nilai_rata_rata' => 87.50,
        ]);
    }

    public function test_can_create_kelulusan_tidak_lulus(): void
    {
        $data = $this->createBaseData();

        $kelulusan = Kelulusan::create([
            'siswa_id' => $data['siswa']->id,
            'pengumuman_id' => $data['pengumuman']->id,
            'status' => 'tidak_lulus',
            'keterangan' => 'Belum memenuhi syarat',
        ]);

        $this->assertDatabaseHas('kelulusans', [
            'status' => 'tidak_lulus',
        ]);
    }

    public function test_kelulusan_belongs_to_siswa(): void
    {
        $data = $this->createBaseData();

        $kelulusan = Kelulusan::create([
            'siswa_id' => $data['siswa']->id,
            'pengumuman_id' => $data['pengumuman']->id,
            'status' => 'lulus',
        ]);

        $this->assertInstanceOf(Siswa::class, $kelulusan->siswa);
        $this->assertEquals($data['siswa']->id, $kelulusan->siswa->id);
    }

    public function test_kelulusan_belongs_to_pengumuman(): void
    {
        $data = $this->createBaseData();

        $kelulusan = Kelulusan::create([
            'siswa_id' => $data['siswa']->id,
            'pengumuman_id' => $data['pengumuman']->id,
            'status' => 'lulus',
        ]);

        $this->assertInstanceOf(Pengumuman::class, $kelulusan->pengumuman);
        $this->assertEquals($data['pengumuman']->id, $kelulusan->pengumuman->id);
    }

    public function test_keterangan_and_nilai_are_nullable(): void
    {
        $data = $this->createBaseData();

        $kelulusan = Kelulusan::create([
            'siswa_id' => $data['siswa']->id,
            'pengumuman_id' => $data['pengumuman']->id,
            'status' => 'lulus',
        ]);

        $this->assertNull($kelulusan->keterangan);
        $this->assertNull($kelulusan->nilai_rata_rata);
    }

    public function test_status_defaults_to_lulus(): void
    {
        $data = $this->createBaseData();

        $kelulusan = Kelulusan::create([
            'siswa_id' => $data['siswa']->id,
            'pengumuman_id' => $data['pengumuman']->id,
        ]);

        $this->assertEquals('lulus', $kelulusan->fresh()->status);
    }

    public function test_cascade_delete_from_siswa(): void
    {
        $data = $this->createBaseData();

        Kelulusan::create([
            'siswa_id' => $data['siswa']->id,
            'pengumuman_id' => $data['pengumuman']->id,
            'status' => 'lulus',
        ]);

        $this->assertDatabaseCount('kelulusans', 1);

        $data['siswa']->delete();

        $this->assertDatabaseCount('kelulusans', 0);
    }

    public function test_cascade_delete_from_pengumuman(): void
    {
        $data = $this->createBaseData();

        Kelulusan::create([
            'siswa_id' => $data['siswa']->id,
            'pengumuman_id' => $data['pengumuman']->id,
            'status' => 'lulus',
        ]);

        $this->assertDatabaseCount('kelulusans', 1);

        $data['pengumuman']->delete();

        $this->assertDatabaseCount('kelulusans', 0);
    }

    public function test_fillable_attributes(): void
    {
        $kelulusan = new Kelulusan();
        $expected = ['siswa_id', 'pengumuman_id', 'status', 'keterangan', 'nilai_rata_rata', 'skl_path'];
        $this->assertEquals($expected, $kelulusan->getFillable());
    }
}
