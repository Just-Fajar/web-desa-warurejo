<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
        ]);
    }

    // ==================== GUEST PROTECTION ====================

    public function test_guest_cannot_access_profile_show()
    {
        $response = $this->get(route('admin.profile.show'));
        $response->assertRedirect(route('admin.login'));
    }

    // ==================== SHOW ====================

    public function test_admin_can_view_profile()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.profile.show'));
        $response->assertStatus(200);
        $response->assertSee('Admin Test');
        $response->assertSee('admin@test.com');
    }

    // ==================== EDIT ====================

    public function test_admin_can_view_edit_form()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.profile.edit'));
        $response->assertStatus(200);
    }

    // ==================== UPDATE PROFILE ====================

    public function test_admin_can_update_name()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => 'Updated Name',
                'email' => $this->admin->email,
            ]);

        $response->assertRedirect(route('admin.profile.show'));
        $this->assertDatabaseHas('admins', [
            'id' => $this->admin->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_admin_can_update_email()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => $this->admin->name,
                'email' => 'newemail@test.com',
            ]);

        $response->assertRedirect(route('admin.profile.show'));
        $this->assertDatabaseHas('admins', [
            'id' => $this->admin->id,
            'email' => 'newemail@test.com',
        ]);
    }

    public function test_update_fails_with_duplicate_email()
    {
        Admin::factory()->create(['email' => 'taken@test.com']);

        $response = $this->actingAs($this->admin, 'admin')
            ->from(route('admin.profile.edit'))
            ->put(route('admin.profile.update'), [
                'name' => $this->admin->name,
                'email' => 'taken@test.com',
            ]);

        // Controller catches ValidationException in try-catch, redirects back with 'error' flash
        $response->assertRedirect();
        $this->assertDatabaseHas('admins', [
            'id' => $this->admin->id,
            'email' => 'admin@test.com', // email should NOT have changed
        ]);
    }

    public function test_update_fails_with_invalid_email()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->from(route('admin.profile.edit'))
            ->put(route('admin.profile.update'), [
                'name' => $this->admin->name,
                'email' => 'not-valid-email',
            ]);

        // Controller catches ValidationException, redirects back
        $response->assertRedirect();
        $this->assertDatabaseHas('admins', [
            'id' => $this->admin->id,
            'email' => 'admin@test.com', // email should NOT have changed
        ]);
    }

    // ==================== UPDATE PASSWORD (NORMAL) ====================

    public function test_admin_can_change_password()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update-password'), [
                'current_password' => 'password',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect(route('admin.profile.show'));
        $response->assertSessionHas('success');
    }

    public function test_password_change_fails_with_wrong_current_password()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update-password'), [
                'current_password' => 'wrongpassword',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_password_change_fails_with_short_password()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->from(route('admin.profile.show'))
            ->put(route('admin.profile.update-password'), [
                'current_password' => 'password',
                'password' => 'short',
                'password_confirmation' => 'short',
            ]);

        // Controller catches ValidationException, redirects back
        $response->assertRedirect();
        // Password should NOT have changed
        $this->assertTrue(Hash::check('password', $this->admin->fresh()->password));
    }

    public function test_password_change_fails_without_confirmation()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->from(route('admin.profile.show'))
            ->put(route('admin.profile.update-password'), [
                'current_password' => 'password',
                'password' => 'newpassword123',
                'password_confirmation' => 'differentpassword',
            ]);

        // Controller catches ValidationException, redirects back
        $response->assertRedirect();
        // Password should NOT have changed
        $this->assertTrue(Hash::check('password', $this->admin->fresh()->password));
    }

    // ==================== UPDATE PASSWORD (LUPA PASSWORD) ====================

    public function test_forgot_password_with_correct_email()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update-password'), [
                'lupa_password' => true,
                'email_verifikasi' => 'admin@test.com',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertRedirect(route('admin.profile.show'));
        $response->assertSessionHas('success');
    }

    public function test_forgot_password_fails_with_wrong_email()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.profile.update-password'), [
                'lupa_password' => true,
                'email_verifikasi' => 'wrong@test.com',
                'password' => 'newpassword123',
                'password_confirmation' => 'newpassword123',
            ]);

        $response->assertSessionHasErrors('email_verifikasi');
    }

    // ==================== UPDATE PHOTO ====================

    public function test_admin_can_upload_photo()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.profile.update-photo'), [
                'photo' => UploadedFile::fake()->image('avatar.jpg', 400, 400),
            ]);

        $response->assertJson(['success' => true]);
    }

    public function test_upload_photo_fails_with_non_image()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin, 'admin')
            ->postJson(route('admin.profile.update-photo'), [
                'photo' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
            ]);

        // Controller catches ValidationException in try-catch, returns 500 JSON
        $response->assertStatus(500);
        $response->assertJson(['success' => false]);
    }

    // ==================== DELETE PHOTO ====================

    public function test_admin_can_delete_photo()
    {
        Storage::fake('public');
        $this->admin->update(['avatar' => 'admins/photos/test.jpg']);
        Storage::disk('public')->put('admins/photos/test.jpg', 'dummy');

        $response = $this->actingAs($this->admin, 'admin')
            ->deleteJson(route('admin.profile.delete-photo'));

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('admins', [
            'id' => $this->admin->id,
            'avatar' => null,
        ]);
    }
}
