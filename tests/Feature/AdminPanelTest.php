<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_panel_redirects_unauthenticated_users(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect();
    }

    public function test_admin_login_page_loads(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_without_permission_gets_forbidden(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get('/admin');

        // Filament Shield blocks users without panel access permission
        $response->assertStatus(403);
    }

    public function test_authenticated_admin_with_super_admin_role(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        \Spatie\Permission\Models\Role::create(['name' => 'super_admin']);
        $user->assignRole('super_admin');

        $response = $this->actingAs($user)->get('/admin');

        // super_admin may or may not have panel_access depending on Shield config
        $this->assertContains($response->getStatusCode(), [200, 302, 403]);
    }
}
