<?php

namespace Tests\Feature\Membership;

use App\Models\MembershipApplication;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicApplicationDuplicateGuardTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['slug' => 'test-org']);
    }

    private function postJoin(array $overrides = []): \Illuminate\Testing\TestResponse
    {
        return $this->post("/organisations/{$this->org->slug}/join", array_merge([
            'first_name' => 'Jane',
            'last_name'  => 'Smith',
            'email'      => 'jane@example.com',
            'website'    => '',
        ], $overrides));
    }

    public function test_duplicate_submitted_application_is_rejected(): void
    {
        MembershipApplication::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id'         => null,
            'applicant_email' => 'jane@example.com',
            'source'          => 'public',
            'status'          => 'submitted',
            'submitted_at'    => now(),
        ]);

        $response = $this->postJoin();
        $response->assertSessionHasErrors('email');

        $this->assertEquals(1,
            MembershipApplication::withoutGlobalScopes()->where('applicant_email', 'jane@example.com')->count(),
            'Second application must not be stored'
        );
    }

    public function test_duplicate_under_review_application_is_rejected(): void
    {
        MembershipApplication::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id'         => null,
            'applicant_email' => 'jane@example.com',
            'source'          => 'public',
            'status'          => 'under_review',
            'submitted_at'    => now(),
        ]);

        $response = $this->postJoin();
        $response->assertSessionHasErrors('email');
    }

    public function test_same_email_different_org_is_allowed(): void
    {
        $otherOrg = Organisation::factory()->create();

        MembershipApplication::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $otherOrg->id,
            'user_id'         => null,
            'applicant_email' => 'jane@example.com',
            'source'          => 'public',
            'status'          => 'submitted',
            'submitted_at'    => now(),
        ]);

        $response = $this->postJoin();
        $response->assertSessionHas('success');
    }

    public function test_previously_rejected_email_can_reapply(): void
    {
        MembershipApplication::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'user_id'         => null,
            'applicant_email' => 'jane@example.com',
            'source'          => 'public',
            'status'          => 'rejected',
            'submitted_at'    => now(),
        ]);

        $response = $this->postJoin();
        $response->assertSessionHas('success');
    }
}
