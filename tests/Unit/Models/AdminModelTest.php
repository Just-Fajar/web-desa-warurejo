<?php

namespace Tests\Unit\Models;

use App\Models\Admin;
use App\Models\Berita;
use App\Models\Galeri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModelTest extends TestCase
{
    use RefreshDatabase;

    // ==================== RELATIONSHIPS ====================

    public function test_admin_has_many_berita()
    {
        $admin = Admin::factory()->create();
        Berita::factory()->count(3)->create(['admin_id' => $admin->id]);

        $this->assertCount(3, $admin->berita);
    }

    public function test_admin_has_many_galeri()
    {
        $admin = Admin::factory()->create();
        Galeri::factory()->count(2)->create(['admin_id' => $admin->id]);

        $this->assertCount(2, $admin->galeri);
    }

    // ==================== ACCESSORS ====================

    public function test_avatar_url_with_avatar()
    {
        $admin = Admin::factory()->create(['avatar' => 'admins/photos/test.jpg']);
        $this->assertStringContainsString('admins/photos/test.jpg', $admin->avatar_url);
    }

    public function test_avatar_url_without_avatar()
    {
        $admin = Admin::factory()->create(['avatar' => null]);
        $this->assertStringContainsString('default-avatar', $admin->avatar_url);
    }

    // ==================== CASTS ====================

    public function test_password_is_hashed()
    {
        $admin = Admin::factory()->create(['password' => 'password123']);
        $this->assertNotEquals('password123', $admin->password);
    }

    // ==================== HIDDEN ====================

    public function test_password_is_hidden_in_array()
    {
        $admin = Admin::factory()->create();
        $array = $admin->toArray();
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    // ==================== FILLABLE ====================

    public function test_fillable_attributes()
    {
        $admin = Admin::factory()->create([
            'name' => 'Test Admin',
            'email' => 'test@admin.com',
            'phone' => '081234567890',
        ]);
        $this->assertEquals('Test Admin', $admin->name);
        $this->assertEquals('test@admin.com', $admin->email);
        $this->assertEquals('081234567890', $admin->phone);
    }
}
