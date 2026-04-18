<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPermissionPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_with_permission_can_access_admin_panel(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $admin->syncPlatformRole(UserRole::Admin);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Kontrola platforme');
    }

    public function test_regular_user_cannot_access_admin_panel(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create([
            'role' => UserRole::User,
        ]);

        $user->syncPlatformRole(UserRole::User);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_sync_platform_role_attaches_spatie_role(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create([
            'role' => UserRole::Dealer,
        ]);

        $user->syncPlatformRole(UserRole::Admin);

        $this->assertTrue($user->fresh()->hasRole(UserRole::Admin->value));
        $this->assertSame(UserRole::Admin, $user->fresh()->role);
    }
}
