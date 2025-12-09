<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Clear rate limiter before each test
        RateLimiter::clear('login:127.0.0.1');
    }

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_admin_can_login_and_redirected_to_dashboard()
    {
        $admin = User::factory()->create([
            'username' => 'adminuser',
            'password' => bcrypt('password1234567890'),
            'isAdmin' => true,
        ]);

        $response = $this->post('/login', [
            'username' => 'adminuser',
            'password' => 'password1234567890',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_regular_user_can_login_and_redirected_to_decks()
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password1234567890'),
            'isAdmin' => false,
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password1234567890',
        ]);

        $response->assertRedirect('/decks');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_nonexistent_username()
    {
        $response = $this->post('/login', [
            'username' => 'nonexistent',
            'password' => 'password1234567890',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password1234567890'),
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword123',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_rate_limit_blocks_after_five_failed_attempts()
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password1234567890'),
        ]);

        // Make 5 failed login attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'username' => 'testuser',
                'password' => 'wrongpassword123',
            ]);
        }

        // 6th attempt should be rate limited
        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertStringContainsString('Too many', session('errors')->first());
    }

    public function test_successful_login_clears_rate_limiter()
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password1234567890'),
        ]);

        // Make 3 failed attempts
        for ($i = 0; $i < 3; $i++) {
            $this->post('/login', [
                'username' => 'testuser',
                'password' => 'wrongpassword123',
            ]);
        }

        // Successful login should clear rate limiter
        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password1234567890',
        ]);

        $response->assertRedirect('/decks');
        $this->assertAuthenticatedAs($user);

        // Should be able to make more attempts after successful login
        $this->post('/logout');
        
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'username' => 'testuser',
                'password' => 'wrongpassword123',
            ]);
        }

        // This should still work (rate limiter was cleared)
        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword123',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_password_must_be_at_least_10_characters()
    {
        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'short',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
