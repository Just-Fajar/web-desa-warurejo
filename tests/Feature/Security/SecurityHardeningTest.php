<?php

namespace Tests\Feature\Security;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that security headers are present in the response.
     */
    public function test_security_headers_are_returned_in_web_responses(): void
    {
        $response = $this->get('/');

        $response->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '0');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Cross-Origin-Opener-Policy', 'same-origin');
        $this->assertNotEmpty($response->headers->get('Content-Security-Policy'));
    }

    /**
     * Test that script tags automatically receive the generated CSP nonce.
     */
    public function test_script_tags_receive_csp_nonces(): void
    {
        $response = $this->get('/');

        $content = $response->getContent();
        $this->assertStringContainsString('nonce="', $content);

        // Extract nonce value
        preg_match('/nonce="([a-zA-Z0-9+\/={:-]+)"/', $content, $matches);
        $this->assertNotEmpty($matches);
        $nonce = $matches[1];

        // Verify CSP header contains the nonce
        $cspHeader = $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("nonce-{$nonce}", $cspHeader);
    }

    /**
     * Test that public forms are rate limited.
     */
    public function test_public_forms_are_rate_limited(): void
    {
        RateLimiter::clear('public-forms:127.0.0.1');

        // Execute 5 allowed submissions
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/pengaduan', [
                'nama' => 'Test User',
                'email' => 'test@example.com',
                'judul' => 'Test Subject',
                'isi' => 'Test content of complaint that is long enough.',
                'lokasi_kejadian' => 'Warurejo',
            ]);
            // Should not return 429
            $this->assertNotEquals(429, $response->status());
        }

        // 6th submission should be rate limited (429)
        $response = $this->post('/pengaduan', [
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'judul' => 'Test Subject',
            'isi' => 'Test content of complaint that is long enough.',
            'lokasi_kejadian' => 'Warurejo',
        ]);
        $response->assertStatus(429);
    }

    /**
     * Test CORS configuration and allowed headers.
     */
    public function test_cors_options_requests_are_handled(): void
    {
        $response = $this->json('OPTIONS', 'api/profil-desa', [], [
            'Origin' => 'http://localhost',
            'Access-Control-Request-Method' => 'GET',
            'Access-Control-Request-Headers' => 'Content-Type, X-Requested-With, Authorization, Accept, X-XSRF-TOKEN, X-API-Version',
        ]);

        $response->assertHeader('Access-Control-Allow-Origin', 'http://localhost');
        $response->assertHeader('Access-Control-Allow-Headers', 'content-type, x-requested-with, authorization, accept, x-xsrf-token, x-api-version');
        $response->assertHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }

    /**
     * Test admin login auditing and logging behavior.
     */
    public function test_admin_auth_failures_and_successes_are_logged(): void
    {
        // Mock the security log channel
        Log::shouldReceive('channel')
            ->with('security')
            ->andReturn($logger = \Mockery::mock(\Psr\Log\LoggerInterface::class));

        // Expect warning for failed login
        $logger->shouldReceive('warning')
            ->once()
            ->with('Admin login failed', \Mockery::on(function ($data) {
                return isset($data['email']) && $data['email'] === 'wrong@example.com';
            }));

        $this->post('/admin/login', [
            'email' => 'wrong@example.com',
            'password' => 'password123',
        ]);

        // Expect info for successful login
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('Password@123!'),
        ]);

        $logger->shouldReceive('info')
            ->once()
            ->with('Admin login successful', \Mockery::on(function ($data) use ($admin) {
                return isset($data['email']) && $data['email'] === $admin->email;
            }));

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'Password@123!',
        ]);
    }

    /**
     * Test admin password strength minimum length rules.
     */
    public function test_admin_password_updates_require_min_length(): void
    {
        /** @var \App\Models\Admin $admin */
        $admin = Admin::factory()->create([
            'password' => bcrypt('OldPassword@123!'),
        ]);

        $this->actingAs($admin, 'admin');

        // Attempting to set a short password (less than 8 chars)
        $response = $this->put('/admin/profile/password', [
            'current_password' => 'OldPassword@123!',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors(['password']);

        // Attempting to set a valid password (8+ chars)
        $response = $this->put('/admin/profile/password', [
            'current_password' => 'OldPassword@123!',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasNoErrors();
    }
}
