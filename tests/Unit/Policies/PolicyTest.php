<?php

namespace Tests\Unit\Policies;

use App\Models\Kelulusan;
use App\Models\Pengumuman;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\User;
use App\Policies\KelulusanPolicy;
use App\Policies\PengumumanPolicy;
use App\Policies\SekolahPolicy;
use App\Policies\SiswaPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->regularUser = User::create([
            'name' => 'Regular',
            'email' => 'regular@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    // ==========================================
    // KELULUSAN POLICY TESTS
    // ==========================================

    public function test_kelulusan_policy_view_any_granted(): void
    {
        Permission::create(['name' => 'ViewAny:Kelulusan']);
        $this->adminUser->givePermissionTo('ViewAny:Kelulusan');

        $policy = new KelulusanPolicy();
        $this->assertTrue($policy->viewAny($this->adminUser));
    }

    public function test_kelulusan_policy_view_any_denied(): void
    {
        Permission::create(['name' => 'ViewAny:Kelulusan']);

        $policy = new KelulusanPolicy();
        $this->assertFalse($policy->viewAny($this->regularUser));
    }

    public function test_kelulusan_policy_create_granted(): void
    {
        Permission::create(['name' => 'Create:Kelulusan']);
        $this->adminUser->givePermissionTo('Create:Kelulusan');

        $policy = new KelulusanPolicy();
        $this->assertTrue($policy->create($this->adminUser));
    }

    public function test_kelulusan_policy_create_denied(): void
    {
        Permission::create(['name' => 'Create:Kelulusan']);

        $policy = new KelulusanPolicy();
        $this->assertFalse($policy->create($this->regularUser));
    }

    public function test_kelulusan_policy_update_granted(): void
    {
        Permission::create(['name' => 'Update:Kelulusan']);
        $this->adminUser->givePermissionTo('Update:Kelulusan');

        $policy = new KelulusanPolicy();
        $kelulusan = new Kelulusan();
        $this->assertTrue($policy->update($this->adminUser, $kelulusan));
    }

    public function test_kelulusan_policy_delete_granted(): void
    {
        Permission::create(['name' => 'Delete:Kelulusan']);
        $this->adminUser->givePermissionTo('Delete:Kelulusan');

        $policy = new KelulusanPolicy();
        $kelulusan = new Kelulusan();
        $this->assertTrue($policy->delete($this->adminUser, $kelulusan));
    }

    // ==========================================
    // SEKOLAH POLICY TESTS
    // ==========================================

    public function test_sekolah_policy_view_any_granted(): void
    {
        Permission::create(['name' => 'ViewAny:Sekolah']);
        $this->adminUser->givePermissionTo('ViewAny:Sekolah');

        $policy = new SekolahPolicy();
        $this->assertTrue($policy->viewAny($this->adminUser));
    }

    public function test_sekolah_policy_view_any_denied(): void
    {
        Permission::create(['name' => 'ViewAny:Sekolah']);

        $policy = new SekolahPolicy();
        $this->assertFalse($policy->viewAny($this->regularUser));
    }

    public function test_sekolah_policy_create_granted(): void
    {
        Permission::create(['name' => 'Create:Sekolah']);
        $this->adminUser->givePermissionTo('Create:Sekolah');

        $policy = new SekolahPolicy();
        $this->assertTrue($policy->create($this->adminUser));
    }

    // ==========================================
    // SISWA POLICY TESTS
    // ==========================================

    public function test_siswa_policy_view_any_granted(): void
    {
        Permission::create(['name' => 'ViewAny:Siswa']);
        $this->adminUser->givePermissionTo('ViewAny:Siswa');

        $policy = new SiswaPolicy();
        $this->assertTrue($policy->viewAny($this->adminUser));
    }

    public function test_siswa_policy_view_any_denied(): void
    {
        Permission::create(['name' => 'ViewAny:Siswa']);

        $policy = new SiswaPolicy();
        $this->assertFalse($policy->viewAny($this->regularUser));
    }

    public function test_siswa_policy_create_granted(): void
    {
        Permission::create(['name' => 'Create:Siswa']);
        $this->adminUser->givePermissionTo('Create:Siswa');

        $policy = new SiswaPolicy();
        $this->assertTrue($policy->create($this->adminUser));
    }

    // ==========================================
    // PENGUMUMAN POLICY TESTS
    // ==========================================

    public function test_pengumuman_policy_view_any_granted(): void
    {
        Permission::create(['name' => 'ViewAny:Pengumuman']);
        $this->adminUser->givePermissionTo('ViewAny:Pengumuman');

        $policy = new PengumumanPolicy();
        $this->assertTrue($policy->viewAny($this->adminUser));
    }

    public function test_pengumuman_policy_view_any_denied(): void
    {
        Permission::create(['name' => 'ViewAny:Pengumuman']);

        $policy = new PengumumanPolicy();
        $this->assertFalse($policy->viewAny($this->regularUser));
    }

    public function test_pengumuman_policy_create_granted(): void
    {
        Permission::create(['name' => 'Create:Pengumuman']);
        $this->adminUser->givePermissionTo('Create:Pengumuman');

        $policy = new PengumumanPolicy();
        $this->assertTrue($policy->create($this->adminUser));
    }

    // ==========================================
    // ROLE-BASED PERMISSION TESTS
    // ==========================================

    public function test_role_based_permission_access(): void
    {
        $role = Role::create(['name' => 'admin']);
        Permission::create(['name' => 'ViewAny:Kelulusan']);
        Permission::create(['name' => 'Create:Kelulusan']);
        $role->givePermissionTo(['ViewAny:Kelulusan', 'Create:Kelulusan']);

        $this->adminUser->assignRole('admin');

        $policy = new KelulusanPolicy();
        $this->assertTrue($policy->viewAny($this->adminUser));
        $this->assertTrue($policy->create($this->adminUser));
    }
}
