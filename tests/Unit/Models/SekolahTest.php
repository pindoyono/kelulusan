<?php

namespace Tests\Unit\Models;

use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SekolahTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_sekolah(): void
    {
        $sekolah = Sekolah::create([
            'nama' => 'SMA Negeri 1 Malinau',
            'npsn' => '30401234',
            'jenis' => 'SMA',
            'alamat' => 'Jl. Pendidikan No. 1',
            'kota' => 'Malinau',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('sekolahs', [
            'nama' => 'SMA Negeri 1 Malinau',
            'npsn' => '30401234',
            'jenis' => 'SMA',
        ]);
    }

    public function test_sekolah_has_many_siswas(): void
    {
        $sekolah = Sekolah::create([
            'nama' => 'SMK Negeri 1 Tarakan',
            'npsn' => '30401235',
            'jenis' => 'SMK',
            'alamat' => 'Jl. Teknik No. 2',
            'kota' => 'Tarakan',
            'is_active' => true,
        ]);

        Siswa::create([
            'sekolah_id' => $sekolah->id,
            'nisn' => '0012345678',
            'nis' => '12345',
            'nama' => 'Ahmad',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII IPA 1',
        ]);

        Siswa::create([
            'sekolah_id' => $sekolah->id,
            'nisn' => '0012345679',
            'nis' => '12346',
            'nama' => 'Budi',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII IPA 2',
        ]);

        $this->assertCount(2, $sekolah->siswas);
        $this->assertInstanceOf(Siswa::class, $sekolah->siswas->first());
    }

    public function test_is_active_is_cast_to_boolean(): void
    {
        $sekolah = Sekolah::create([
            'nama' => 'SMA Test',
            'npsn' => '30401236',
            'jenis' => 'SMA',
            'is_active' => 1,
        ]);

        $this->assertIsBool($sekolah->is_active);
        $this->assertTrue($sekolah->is_active);
    }

    public function test_npsn_is_unique(): void
    {
        Sekolah::create([
            'nama' => 'SMA A',
            'npsn' => '30401237',
            'jenis' => 'SMA',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Sekolah::create([
            'nama' => 'SMA B',
            'npsn' => '30401237',
            'jenis' => 'SMA',
        ]);
    }

    public function test_fillable_attributes(): void
    {
        $sekolah = new Sekolah();
        $this->assertEquals(
            ['nama', 'npsn', 'jenis', 'alamat', 'kota', 'is_active'],
            $sekolah->getFillable()
        );
    }

    public function test_alamat_and_kota_are_nullable(): void
    {
        $sekolah = Sekolah::create([
            'nama' => 'SMA Minimal',
            'npsn' => '30401238',
            'jenis' => 'SMA',
        ]);

        $this->assertNull($sekolah->alamat);
        $this->assertNull($sekolah->kota);
    }

    public function test_cascade_delete_removes_siswas(): void
    {
        $sekolah = Sekolah::create([
            'nama' => 'SMA Delete Test',
            'npsn' => '30401239',
            'jenis' => 'SMA',
        ]);

        Siswa::create([
            'sekolah_id' => $sekolah->id,
            'nisn' => '0098765432',
            'nama' => 'Test Siswa',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII',
        ]);

        $this->assertDatabaseCount('siswas', 1);

        $sekolah->delete();

        $this->assertDatabaseCount('siswas', 0);
    }
}
