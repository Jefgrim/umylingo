<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard()
    {
        $admin = User::factory()->create([
            'username' => 'admin',
            'isAdmin' => true,
        ]);

        $this->actingAs($admin);

        $response = $this->get('/dashboard');
        
        $response->assertStatus(200);
        Livewire::test('dashboard')
            ->assertStatus(200);
    }

    public function test_admin_can_access_deck_management()
    {
        $admin = User::factory()->create([
            'isAdmin' => true,
        ]);

        $this->actingAs($admin);

        $response = $this->get('/dashboard/decks');
        $response->assertStatus(200);
    }

    public function test_regular_user_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create([
            'isAdmin' => false,
        ]);

        $this->actingAs($user);

        $response = $this->get('/dashboard');
        
        // Should be forbidden for regular users
        $response->assertStatus(403);
    }

    public function test_regular_user_can_access_decks_page()
    {
        $user = User::factory()->create([
            'isAdmin' => false,
        ]);

        $this->actingAs($user);

        $response = $this->get('/decks');
        $response->assertStatus(200);
    }

    public function test_guest_redirected_to_login_from_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_guest_redirected_to_login_from_decks()
    {
        $response = $this->get('/decks');
        $response->assertRedirect('/login');
    }

    public function test_logged_in_admin_accessing_login_redirects_to_dashboard()
    {
        $admin = User::factory()->create([
            'isAdmin' => true,
        ]);

        $this->actingAs($admin);

        $response = $this->get('/login');
        $response->assertRedirect('/dashboard');
    }

    public function test_logged_in_user_accessing_login_redirects_to_decks()
    {
        $user = User::factory()->create([
            'isAdmin' => false,
        ]);

        $this->actingAs($user);

        $response = $this->get('/login');
        $response->assertRedirect('/decks');
    }
}
