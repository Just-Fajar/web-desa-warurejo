<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthAdminTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();

        // Register YEAR function for SQLite compatibility (Dashboard uses MySQL YEAR())
        if (DB::getDriverName() === 'sqlite') {
            DB::connection()->getPdo()->sqliteCreateFunction('YEAR', function ($date) {
                return date('Y', strtotime($date));
            }, 1);
            DB::connection()->getPdo()->sqliteCreateFunction('MONTH', function ($date) {
                return date('m', strtotime($date));
            }, 1);
        }
    }

    // ==================== HALAMAN LOGIN ====================

    public function test_guest_can_view_login_page()
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.auth.login');
    }

    public function test_authenticated_admin_is_redirected_from_login_page()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.login'));
        $response->assertRedirect(route('admin.dashboard'));
    }

    // ==================== LOGIN BERHASIL ====================

    public function test_admin_can_login_with_correct_credentials()
    {
        $response = $this->post(route('admin.login.post'), [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin, 'admin');
    }

    public function test_admin_session_is_stored_after_login()
    {
        $this->post(route('admin.login.post'), [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticatedAs($this->admin, 'admin');
    }

    // ==================== LOGIN GAGAL ====================

    public function test_login_fails_with_wrong_password()
    {
        $response = $this->post(route('admin.login.post'), [
            'email' => $this->admin->email,
            'password' => 'wrongpassword',
        ]);
        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    public function test_login_fails_with_unregistered_email()
    {
        $response = $this->post(route('admin.login.post'), [
            'email' => 'notexist@example.com',
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    public function test_login_fails_with_empty_email()
    {
        $response = $this->post(route('admin.login.post'), [
            'email' => '',
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors('email');
    }

    public function test_login_fails_with_empty_password()
    {
        $response = $this->post(route('admin.login.post'), [
            'email' => $this->admin->email,
            'password' => '',
        ]);
        $response->assertSessionHasErrors('password');
    }

    public function test_login_fails_with_invalid_email_format()
    {
        $response = $this->post(route('admin.login.post'), [
            'email' => 'not-an-email',
            'password' => 'password',
        ]);
        $response->assertSessionHasErrors('email');
    }

    // ==================== LOGOUT ====================

    public function test_admin_can_logout()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.logout'));
        $response->assertRedirect(route('admin.login'));
        $this->assertGuest('admin');
    }

    public function test_after_logout_cannot_access_admin_pages()
    {
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.logout'));

        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    // ==================== PROTEKSI ROUTE ====================

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_access_berita_index()
    {
        $response = $this->get(route('admin.berita.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_access_potensi_index()
    {
        $response = $this->get(route('admin.potensi.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_access_galeri_index()
    {
        $response = $this->get(route('admin.galeri.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_access_publikasi_index()
    {
        $response = $this->get(route('admin.publikasi.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_access_struktur_organisasi_index()
    {
        $response = $this->get(route('admin.struktur-organisasi.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_access_pengaduan_index()
    {
        $response = $this->get(route('admin.pengaduan.index'));
        $response->assertRedirect(route('admin.login'));
    }



    public function test_guest_cannot_access_profile_show()
    {
        $response = $this->get(route('admin.profile.show'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_can_access_dashboard()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    public function test_authenticated_admin_can_access_berita_index()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.berita.index'));
        $response->assertStatus(200);
    }

    public function test_authenticated_admin_can_access_pengaduan_index()
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.pengaduan.index'));
        $response->assertStatus(200);
    }
}
