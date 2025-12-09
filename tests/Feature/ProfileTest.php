<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'isAdmin' => false,
        ]);
    }

    public function test_user_can_view_their_profile()
    {
        $this->actingAs($this->user);

        $response = $this->get('/profile');
        $response->assertStatus(200);
    }

    public function test_user_can_update_their_profile_information()
    {
        $this->actingAs($this->user);

        Livewire::test('profile')
            ->set('firstname', 'Jane')
            ->set('lastname', 'Smith')
            ->set('username', 'janesmith')
            ->set('email', 'jane@example.com')
            ->call('updateProfile');

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'username' => 'janesmith',
            'email' => 'jane@example.com',
        ]);
    }

    public function test_profile_form_is_prefilled_with_user_data()
    {
        $this->actingAs($this->user);

        Livewire::test('profile')
            ->assertSet('firstname', 'John')
            ->assertSet('lastname', 'Doe')
            ->assertSet('username', 'johndoe')
            ->assertSet('email', 'john@example.com');
    }

    public function test_username_must_be_unique_when_updating_profile()
    {
        $otherUser = User::factory()->create([
            'username' => 'existinguser',
        ]);

        $this->actingAs($this->user);

        Livewire::test('profile')
            ->set('username', 'existinguser')
            ->call('updateProfile')
            ->assertHasErrors(['username']);
    }

    public function test_email_must_be_unique_when_updating_profile()
    {
        $otherUser = User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $this->actingAs($this->user);

        Livewire::test('profile')
            ->set('email', 'existing@example.com')
            ->call('updateProfile')
            ->assertHasErrors(['email']);
    }

    public function test_user_can_keep_same_username_when_updating()
    {
        $this->actingAs($this->user);

        Livewire::test('profile')
            ->set('firstname', 'John Updated')
            ->set('username', 'johndoe') // Same username
            ->call('updateProfile')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'username' => 'johndoe',
            'firstname' => 'John Updated',
        ]);
    }

    public function test_profile_validates_required_fields()
    {
        $this->actingAs($this->user);

        Livewire::test('profile')
            ->set('firstname', '')
            ->set('lastname', '')
            ->set('username', '')
            ->set('email', '')
            ->call('updateProfile')
            ->assertHasErrors(['firstname', 'lastname', 'username', 'email']);
    }

    public function test_email_must_be_valid_format()
    {
        $this->actingAs($this->user);

        Livewire::test('profile')
            ->set('email', 'invalid-email')
            ->call('updateProfile')
            ->assertHasErrors(['email']);
    }

    public function test_guest_cannot_access_profile()
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_their_admin_profile()
    {
        $admin = User::factory()->create([
            'isAdmin' => true,
        ]);

        $this->actingAs($admin);

        $response = $this->get('/dashboard/profile');
        $response->assertStatus(200);
    }
}
