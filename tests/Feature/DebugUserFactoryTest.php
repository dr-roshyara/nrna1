<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class DebugUserFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_user_with_factory()
    {
        $user = User::factory()->make();
        $this->assertNotNull($user->name);
        $this->assertNotNull($user->email);
    }

    /** @test */
    public function can_create_user_in_database()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'first_name' => 'Test',
            'last_name' => 'User'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User'
        ]);
    }

    /** @test */
    public function can_create_user_with_factory_and_save()
    {
        $user = User::factory()->create([
            'name' => 'Factory User',
            'email' => 'factory@example.com'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'factory@example.com',
            'name' => 'Factory User'
        ]);
    }
}
