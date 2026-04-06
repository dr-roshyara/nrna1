<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CsrfProtectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test registration page includes CSRF token in props
     */
    public function test_register_page_shares_csrf_token()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->has('csrf_token')
        );
    }

    /**
     * Test registration requires valid CSRF token
     */
    public function test_registration_requires_csrf_token()
    {
        $response = $this->post('/register', [
            'firstName' => 'Test',
            'lastName' => 'User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'region' => 'Europe',
            'terms' => true,
        ]);

        // Without valid CSRF token, should get 419
        $response->assertStatus(419);
    }

    /**
     * Test registration succeeds with CSRF token
     */
    public function test_registration_with_valid_csrf_token()
    {
        $response = $this->post('/register', [
            'firstName' => 'Test',
            'lastName' => 'User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'region' => 'Europe',
            'terms' => true,
        ], [
            'X-CSRF-TOKEN' => csrf_token(),
        ]);

        // Should succeed (or redirect if registered)
        $this->assertIn($response->status(), [200, 302, 201]);
    }
}
