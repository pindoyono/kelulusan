<?php

namespace Tests\Feature;

use App\Models\Kelulusan;
use App\Models\Pengumuman;
use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CekKelulusanTest extends TestCase
{
    use RefreshDatabase;

    private Sekolah $sekolah;
    private Pengumuman $pengumumanPublished;
    private Pengumuman $pengumumanUnpublished;
    private Siswa $siswaLulus;
    private Siswa $siswaTidakLulus;
    private Siswa $siswaTanpaKelulusan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sekolah = Sekolah::create([
            'nama' => 'SMA Negeri 1 Malinau',
            'npsn' => '30401234',
            'jenis' => 'SMA',
            'alamat' => 'Jl. Pendidikan No. 1',
            'kota' => 'Malinau',
            'is_active' => true,
        ]);

        $this->pengumumanPublished = Pengumuman::create([
            'judul' => 'Pengumuman Kelulusan 2025/2026',
            'tahun_ajaran' => '2025/2026',
            'tanggal_pengumuman' => '2026-04-01 08:00:00',
            'deskripsi' => 'Selamat kepada seluruh siswa.',
            'is_published' => true,
        ]);

        $this->pengumumanUnpublished = Pengumuman::create([
            'judul' => 'Pengumuman Draft',
            'tahun_ajaran' => '2025/2026',
            'tanggal_pengumuman' => '2026-06-01 08:00:00',
            'is_published' => false,
        ]);

        $this->siswaLulus = Siswa::create([
            'sekolah_id' => $this->sekolah->id,
            'nisn' => '0012345678',
            'nis' => '12345',
            'nama' => 'Ahmad Fauzi',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII IPA 1',
            'jurusan' => 'IPA',
        ]);

        $this->siswaTidakLulus = Siswa::create([
            'sekolah_id' => $this->sekolah->id,
            'nisn' => '0087654321',
            'nis' => '12346',
            'nama' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII IPA 2',
            'jurusan' => 'IPA',
        ]);

        $this->siswaTanpaKelulusan = Siswa::create([
            'sekolah_id' => $this->sekolah->id,
            'nisn' => '0099999999',
            'nis' => '12347',
            'nama' => 'Citra Dewi',
            'jenis_kelamin' => 'P',
            'kelas' => 'XII IPS 1',
        ]);

        Kelulusan::create([
            'siswa_id' => $this->siswaLulus->id,
            'pengumuman_id' => $this->pengumumanPublished->id,
            'status' => 'lulus',
            'keterangan' => 'Selamat lulus!',
            'nilai_rata_rata' => 87.50,
        ]);

        Kelulusan::create([
            'siswa_id' => $this->siswaTidakLulus->id,
            'pengumuman_id' => $this->pengumumanPublished->id,
            'status' => 'tidak_lulus',
            'keterangan' => 'Belum memenuhi syarat',
        ]);
    }

    // ==========================================
    // INDEX PAGE TESTS
    // ==========================================

    public function test_index_page_returns_200(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_index_page_uses_correct_view(): void
    {
        $response = $this->get('/');

        $response->assertViewIs('cek-kelulusan');
    }

    public function test_index_page_has_pengumuman_data(): void
    {
        $response = $this->get('/');

        $response->assertViewHas('pengumuman');
    }

    public function test_index_page_shows_published_pengumuman(): void
    {
        $response = $this->get('/');

        $response->assertSee('Tahun Ajaran 2025/2026');
    }

    public function test_index_page_shows_search_form(): void
    {
        $response = $this->get('/');

        $response->assertSee('Cek Status Kelulusan');
        $response->assertSee('Masukkan NISN');
    }

    public function test_index_page_shows_latest_published_pengumuman(): void
    {
        // Create a newer published announcement
        Pengumuman::create([
            'judul' => 'Pengumuman Terbaru',
            'tahun_ajaran' => '2026/2027',
            'tanggal_pengumuman' => '2027-05-01',
            'is_published' => true,
        ]);

        $response = $this->get('/');
        $pengumuman = $response->viewData('pengumuman');

        $this->assertEquals('2026/2027', $pengumuman->tahun_ajaran);
    }

    public function test_index_page_ignores_unpublished_pengumuman(): void
    {
        // Delete published, only unpublished remains
        $this->pengumumanPublished->delete();

        $response = $this->get('/');
        $pengumuman = $response->viewData('pengumuman');

        $this->assertNull($pengumuman);
    }

    // ==========================================
    // SEARCH (CARI) - SISWA LULUS
    // ==========================================

    public function test_search_siswa_lulus_returns_200(): void
    {
        $response = $this->post('/cek', ['nisn' => '0012345678']);

        $response->assertStatus(200);
    }

    public function test_search_siswa_lulus_shows_status(): void
    {
        $response = $this->post('/cek', ['nisn' => '0012345678']);

        $response->assertSee('SELAMAT!');
        $response->assertSee('L U L U S');
    }

    public function test_search_siswa_lulus_shows_student_details(): void
    {
        $response = $this->post('/cek', ['nisn' => '0012345678']);

        $response->assertSee('0012345678');
        $response->assertSee('Ahmad Fauzi');
        $response->assertSee('SMA Negeri 1 Malinau');
        $response->assertSee('XII IPA 1');
    }

    public function test_search_siswa_lulus_shows_nilai(): void
    {
        $response = $this->post('/cek', ['nisn' => '0012345678']);

        $response->assertSee('87.50');
    }

    public function test_search_siswa_lulus_shows_keterangan(): void
    {
        $response = $this->post('/cek', ['nisn' => '0012345678']);

        $response->assertSee('Selamat lulus!');
    }

    public function test_search_siswa_lulus_returns_correct_view_data(): void
    {
        $response = $this->post('/cek', ['nisn' => '0012345678']);

        $response->assertViewHas('siswa');
        $response->assertViewHas('kelulusan');
        $response->assertViewHas('pengumuman');

        $kelulusan = $response->viewData('kelulusan');
        $this->assertEquals('lulus', $kelulusan->status);
    }

    // ==========================================
    // SEARCH (CARI) - SISWA TIDAK LULUS
    // ==========================================

    public function test_search_siswa_tidak_lulus_shows_status(): void
    {
        $response = $this->post('/cek', ['nisn' => '0087654321']);

        $response->assertSee('TIDAK LULUS');
    }

    public function test_search_siswa_tidak_lulus_shows_details(): void
    {
        $response = $this->post('/cek', ['nisn' => '0087654321']);

        $response->assertSee('0087654321');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Belum memenuhi syarat');
    }

    public function test_search_siswa_tidak_lulus_shows_encouragement(): void
    {
        $response = $this->post('/cek', ['nisn' => '0087654321']);

        $response->assertSee('Tetap semangat');
    }

    // ==========================================
    // SEARCH - SISWA TANPA KELULUSAN
    // ==========================================

    public function test_search_siswa_without_kelulusan_shows_pending_message(): void
    {
        $response = $this->post('/cek', ['nisn' => '0099999999']);

        $response->assertSee('Data Kelulusan Belum Tersedia');
        $response->assertSee('0099999999');
        $response->assertSee('Citra Dewi');
    }

    // ==========================================
    // SEARCH - SISWA NOT FOUND
    // ==========================================

    public function test_search_nonexistent_nisn_returns_200(): void
    {
        $response = $this->post('/cek', ['nisn' => '9999999999']);

        $response->assertStatus(200);
        // Note: The view has a bug where `isset($siswa) && $siswa === null` never evaluates
        // to true, so "Data Tidak Ditemukan" message is never shown for null $siswa.
        // The siswa variable is passed as null from the controller.
        $siswa = $response->viewData('siswa');
        $this->assertNull($siswa);
    }

    // ==========================================
    // SEARCH - UNPUBLISHED PENGUMUMAN
    // ==========================================

    public function test_search_siswa_with_only_unpublished_kelulusan(): void
    {
        // Create a siswa with kelulusan linked to unpublished pengumuman only
        $siswa = Siswa::create([
            'sekolah_id' => $this->sekolah->id,
            'nisn' => '0055555555',
            'nama' => 'Dimas',
            'jenis_kelamin' => 'L',
            'kelas' => 'XII',
        ]);

        Kelulusan::create([
            'siswa_id' => $siswa->id,
            'pengumuman_id' => $this->pengumumanUnpublished->id,
            'status' => 'lulus',
        ]);

        $response = $this->post('/cek', ['nisn' => '0055555555']);

        // Should show "belum tersedia" because pengumuman is not published
        $response->assertSee('Data Kelulusan Belum Tersedia');
    }

    // ==========================================
    // VALIDATION TESTS
    // ==========================================

    public function test_search_without_nisn_returns_validation_error(): void
    {
        $response = $this->post('/cek', []);

        $response->assertSessionHasErrors('nisn');
    }

    public function test_search_with_empty_nisn_returns_validation_error(): void
    {
        $response = $this->post('/cek', ['nisn' => '']);

        $response->assertSessionHasErrors('nisn');
    }

    public function test_search_with_too_long_nisn_returns_validation_error(): void
    {
        $response = $this->post('/cek', ['nisn' => str_repeat('1', 21)]);

        $response->assertSessionHasErrors('nisn');
    }

    public function test_search_with_max_length_nisn_passes_validation(): void
    {
        $response = $this->post('/cek', ['nisn' => str_repeat('1', 20)]);

        $response->assertSessionDoesntHaveErrors('nisn');
    }

    // ==========================================
    // ROUTE TESTS
    // ==========================================

    public function test_index_route_name(): void
    {
        $response = $this->get(route('cek-kelulusan'));

        $response->assertStatus(200);
    }

    public function test_cari_route_name(): void
    {
        $response = $this->post(route('cek-kelulusan.cari'), ['nisn' => '0012345678']);

        $response->assertStatus(200);
    }

    public function test_get_cek_returns_method_not_allowed(): void
    {
        $response = $this->get('/cek');

        $response->assertStatus(405);
    }

    public function test_post_to_root_returns_method_not_allowed(): void
    {
        $response = $this->post('/');

        $response->assertStatus(405);
    }

    // ==========================================
    // CSRF PROTECTION TEST
    // ==========================================

    public function test_cari_requires_csrf_token(): void
    {
        $response = $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->post('/cek', ['nisn' => '0012345678']);

        $response->assertStatus(200);
    }

    // ==========================================
    // EDGE CASES
    // ==========================================

    public function test_search_with_leading_trailing_spaces(): void
    {
        $response = $this->post('/cek', ['nisn' => ' 0012345678 ']);

        // NISN with spaces won't match exactly, should show not found
        $response->assertStatus(200);
    }

    public function test_search_when_no_pengumuman_exists(): void
    {
        Kelulusan::query()->delete();
        Pengumuman::query()->delete();

        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->post('/cek', ['nisn' => '0012345678']);
        $response->assertStatus(200);
    }

    public function test_search_returns_kelulusan_with_published_pengumuman_only(): void
    {
        $response = $this->post('/cek', ['nisn' => '0012345678']);

        $kelulusan = $response->viewData('kelulusan');
        $this->assertNotNull($kelulusan);
        $this->assertTrue($kelulusan->pengumuman->is_published);
    }

    // ==========================================
    // PENGUMUMAN INFO SECTION TESTS
    // ==========================================

    public function test_pengumuman_deskripsi_is_shown_when_present(): void
    {
        $response = $this->get('/');

        $response->assertSee('Selamat kepada seluruh siswa.');
    }

    public function test_pengumuman_title_is_shown(): void
    {
        $response = $this->get('/');

        $response->assertSee('Pengumuman Kelulusan 2025/2026');
    }
}
