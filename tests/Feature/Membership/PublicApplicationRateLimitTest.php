<?php

namespace Tests\Feature\Membership;

use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class PublicApplicationRateLimitTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['slug' => 'test-org']);
        RateLimiter::clear('join:127.0.0.1');
    }

    protected function tearDown(): void
    {
        RateLimiter::clear('join:127.0.0.1');
        parent::tearDown();
    }

    private function postJoin(string $email = 'jane@example.com'): \Illuminate\Testing\TestResponse
    {
        return $this->post("/organisations/{$this->org->slug}/join", [
            'first_name' => 'Jane',
            'last_name'  => 'Smith',
            'email'      => $email,
            'website'    => '',
        ]);
    }

    public function test_first_three_attempts_are_allowed(): void
    {
        $this->postJoin('a@example.com')->assertSessionHas('success');
        $this->postJoin('b@example.com')->assertSessionHas('success');
        $this->postJoin('c@example.com')->assertSessionHas('success');
    }

    public function test_fourth_attempt_is_rate_limited(): void
    {
        $this->postJoin('a@example.com');
        $this->postJoin('b@example.com');
        $this->postJoin('c@example.com');

        $response = $this->postJoin('d@example.com');
        $response->assertSessionHasErrors('email');

        // Fourth application must NOT be stored
        $this->assertDatabaseMissing('membership_applications', [
            'applicant_email' => 'd@example.com',
        ]);
    }
}
