<?php

namespace Tests\Feature\Demo;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimpleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user()
    {
        $user = User::factory()->create(['name' => 'Test User']);
        
        $this->assertDatabaseHas('users', [
            'name' => 'Test User'
        ]);
    }
}
