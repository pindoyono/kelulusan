<?php

namespace Tests\Unit\Models;

use App\Models\Kelulusan;
use App\Models\Pengumuman;
use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswaTest extends TestCase
{
    use RefreshDatabase;

    private function createSekolah(): Sekolah
    {
        return Sekolah::create([
            'nama' => 'SMA Negeri 1 Test',
            'npsn' => '30401111',
            'jenis' => 'SMA',
            'kota' => 'Malinau',
        ]);
    }

    public function test_can_create_siswa(): void
    {
        $sekolah = $this->createSekolah();

        $siswa = Siswa::create([
            'sekolah_id' => $sekolah->id,
            'nisn' => '0012345678',
            'nis' => '12345',
            'nama' => 'Ahmad Fauzi',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII IPA 1',
            'jurusan' => 'IPA',
            'tempat_lahir' => 'Malinau',
            'tanggal_lahir' => '2008-05-15',
        ]);

        $this->assertDatabaseHas('siswas', [
            'nisn' => '0012345678',
            'nama' => 'Ahmad Fauzi',
        ]);
    }

    public function test_siswa_belongs_to_sekolah(): void
    {
        $sekolah = $this->createSekolah();

        $siswa = Siswa::create([
            'sekolah_id' => $sekolah->id,
            'nisn' => '0012345678',
            'nama' => 'Ahmad',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII IPA 1',
        ]);

        $this->assertInstanceOf(Sekolah::class, $siswa->sekolah);
        $this->assertEquals($sekolah->id, $siswa->sekolah->id);
    }

    public function test_siswa_has_one_kelulusan(): void
    {
        $sekolah = $this->createSekolah();

        $siswa = Siswa::create([
            'sekolah_id' => $sekolah->id,
            'nisn' => '0012345678',
            'nama' => 'Ahmad',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII IPA 1',
        ]);

        $pengumuman = Pengumuman::create([
            'judul' => 'Kelulusan 2026',
            'tahun_ajaran' => '2025/2026',
            'tanggal_pengumuman' => '2026-05-01',
            'is_published' => true,
        ]);

        Kelulusan::create([
            'siswa_id' => $siswa->id,
            'pengumuman_id' => $pengumuman->id,
            'status' => 'lulus',
            'nilai_rata_rata' => 85.50,
        ]);

        $this->assertInstanceOf(Kelulusan::class, $siswa->kelulusan);
        $this->assertEquals('lulus', $siswa->kelulusan->status);
    }

    public function test_nisn_is_unique(): void
    {
        $sekolah = $this->createSekolah();

        Siswa::create([
            'sekolah_id' => $sekolah->id,
            'nisn' => '0012345678',
            'nama' => 'Ahmad',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Siswa::create([
            'sekolah_id' => $sekolah->id,
            'nisn' => '0012345678',
            'nama' => 'Budi',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII',
        ]);
    }

    public function test_tanggal_lahir_is_cast_to_date(): void
    {
        $sekolah = $this->createSekolah();

        $siswa = Siswa::create([
            'sekolah_id' => $sekolah->id,
            'nisn' => '0012345678',
            'nama' => 'Ahmad',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII',
            'tanggal_lahir' => '2008-05-15',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $siswa->tanggal_lahir);
    }

    public function test_nullable_fields(): void
    {
        $sekolah = $this->createSekolah();

        $siswa = Siswa::create([
            'sekolah_id' => $sekolah->id,
            'nisn' => '0012345678',
            'nama' => 'Ahmad',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII',
        ]);

        $this->assertNull($siswa->nis);
        $this->assertNull($siswa->jurusan);
        $this->assertNull($siswa->tempat_lahir);
        $this->assertNull($siswa->tanggal_lahir);
        $this->assertNull($siswa->foto);
    }

    public function test_fillable_attributes(): void
    {
        $siswa = new Siswa();
        $expected = [
            'sekolah_id', 'nisn', 'nis', 'nama', 'jenis_kelamin',
            'kelas', 'jurusan', 'tempat_lahir', 'tanggal_lahir', 'foto',
        ];
        $this->assertEquals($expected, $siswa->getFillable());
    }
}
