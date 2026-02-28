<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_accounts_can_be_deleted()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->delete('/user', [
            'password' => 'password',
        ]);

        // Verify deletion occurred and user was logged out
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_correct_password_must_be_provided_before_account_can_be_deleted()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->delete('/user', [
            'password' => 'wrong-password',
        ]);

        // Password validation should fail
        $response->assertSessionHasErrors('password');
        // User should still be authenticated
        $this->assertAuthenticatedAs($user);
    }
}
