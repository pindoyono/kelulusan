<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseMigrationTest extends TestCase
{
    use RefreshDatabase;

    // ==========================================
    // USERS TABLE
    // ==========================================

    public function test_users_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('users'));
    }

    public function test_users_table_has_required_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('users', [
            'id', 'name', 'email', 'password', 'remember_token',
            'email_verified_at', 'created_at', 'updated_at',
        ]));
    }

    // ==========================================
    // SEKOLAHS TABLE
    // ==========================================

    public function test_sekolahs_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('sekolahs'));
    }

    public function test_sekolahs_table_has_required_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('sekolahs', [
            'id', 'nama', 'npsn', 'jenis', 'alamat', 'kota',
            'is_active', 'created_at', 'updated_at',
        ]));
    }

    // ==========================================
    // PENGUMUMEN TABLE
    // ==========================================

    public function test_pengumumen_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('pengumumen'));
    }

    public function test_pengumumen_table_has_required_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('pengumumen', [
            'id', 'judul', 'tahun_ajaran', 'tanggal_pengumuman',
            'deskripsi', 'is_published', 'created_at', 'updated_at',
        ]));
    }

    // ==========================================
    // SISWAS TABLE
    // ==========================================

    public function test_siswas_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('siswas'));
    }

    public function test_siswas_table_has_required_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('siswas', [
            'id', 'sekolah_id', 'nisn', 'nis', 'nama', 'jenis_kelamin',
            'kelas', 'jurusan', 'tempat_lahir', 'tanggal_lahir', 'foto',
            'created_at', 'updated_at',
        ]));
    }

    // ==========================================
    // KELULUSANS TABLE
    // ==========================================

    public function test_kelulusans_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('kelulusans'));
    }

    public function test_kelulusans_table_has_required_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('kelulusans', [
            'id', 'siswa_id', 'pengumuman_id', 'status', 'keterangan',
            'nilai_rata_rata', 'created_at', 'updated_at',
        ]));
    }

    // ==========================================
    // PERMISSION TABLES (Spatie)
    // ==========================================

    public function test_permission_tables_exist(): void
    {
        $this->assertTrue(Schema::hasTable('permissions'));
        $this->assertTrue(Schema::hasTable('roles'));
        $this->assertTrue(Schema::hasTable('model_has_permissions'));
        $this->assertTrue(Schema::hasTable('model_has_roles'));
        $this->assertTrue(Schema::hasTable('role_has_permissions'));
    }

    // ==========================================
    // SUPPORTING TABLES
    // ==========================================

    public function test_sessions_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('sessions'));
    }

    public function test_cache_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('cache'));
    }

    public function test_jobs_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('jobs'));
    }
}
