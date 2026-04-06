<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoterProgressServiceTest extends TestCase
{
    use RefreshDatabase;

    protected VoterProgressService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new VoterProgressService();
    }

    /** @test */
    public function it_advances_from_current_step()
    {
        $user = User::factory()->create();
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        $this->service->advanceFrom($slug, 'slug.code.create');

        $this->assertEquals(2, $slug->fresh()->current_step);
    }

    /** @test */
    public function it_does_not_advance_from_wrong_step()
    {
        $user = User::factory()->create();
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 2, // Currently on step 2
        ]);

        // Try to advance from step 1 (wrong current step)
        $this->service->advanceFrom($slug, 'slug.code.create');

        // Should remain on step 2
        $this->assertEquals(2, $slug->fresh()->current_step);
    }

    /** @test */
    public function it_handles_non_existent_routes()
    {
        $user = User::factory()->create();
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        $this->service->advanceFrom($slug, 'non.existent.route');

        // Should remain on step 1
        $this->assertEquals(1, $slug->fresh()->current_step);
    }

    /** @test */
    public function it_does_not_advance_beyond_final_step()
    {
        $user = User::factory()->create();
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 5, // Final step
        ]);

        $this->service->advanceFrom($slug, 'voter.vote.submit');

        // Should remain on step 5
        $this->assertEquals(5, $slug->fresh()->current_step);
    }

    /** @test */
    public function it_updates_step_meta_when_advancing()
    {
        $user = User::factory()->create();
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
            'step_meta' => ['previous_data' => 'preserved'],
        ]);

        $this->service->advanceFrom($slug, 'voter.code.create', ['code_completed' => true]);

        $refreshedSlug = $slug->fresh();
        $this->assertEquals(2, $refreshedSlug->current_step);
        $this->assertTrue($refreshedSlug->step_meta['code_completed']);
        $this->assertEquals('preserved', $refreshedSlug->step_meta['previous_data']);
    }

    /** @test */
    public function it_can_reset_to_beginning()
    {
        $user = User::factory()->create();
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 3,
            'step_meta' => ['some_data' => 'value'],
        ]);

        $this->service->resetToStep($slug, 1);

        $refreshedSlug = $slug->fresh();
        $this->assertEquals(1, $refreshedSlug->current_step);
        $this->assertEquals([], $refreshedSlug->step_meta);
    }

    /** @test */
    public function it_can_get_next_route_name()
    {
        $user = User::factory()->create();
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 2,
        ]);

        $nextRoute = $this->service->getNextRouteName($slug);

        $this->assertEquals('voter.vote.create', $nextRoute);
    }

    /** @test */
    public function it_returns_null_for_final_step_next_route()
    {
        $user = User::factory()->create();
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'test-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 5,
        ]);

        $nextRoute = $this->service->getNextRouteName($slug);

        $this->assertNull($nextRoute);
    }
}
