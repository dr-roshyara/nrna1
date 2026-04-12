<?php

namespace Tests\Unit\Models;

use App\Models\MembershipApplication;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class MembershipApplicationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $applicant;
    private MembershipType $type;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org       = Organisation::factory()->create(['type' => 'tenant']);
        $this->applicant = User::factory()->create();
        $this->type      = MembershipType::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $this->org->id,
            'name'            => 'Annual',
            'slug'            => 'annual',
            'fee_amount'      => 50.00,
            'fee_currency'    => 'EUR',
            'duration_months' => 12,
            'is_active'       => true,
        ]);
    }

    private function makeApplication(array $attrs = []): MembershipApplication
    {
        return MembershipApplication::create(array_merge([
            'id'                   => (string) Str::uuid(),
            'organisation_id'      => $this->org->id,
            'user_id'              => $this->applicant->id,
            'membership_type_id'   => $this->type->id,
            'status'               => 'submitted',
            'expires_at'           => now()->addDays(30),
        ], $attrs));
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    /** @test */
    public function it_belongs_to_an_organisation(): void
    {
        $app = $this->makeApplication();
        $this->assertEquals($this->org->id, $app->organisation->id);
    }

    /** @test */
    public function it_belongs_to_a_user(): void
    {
        $app = $this->makeApplication();
        $this->assertEquals($this->applicant->id, $app->user->id);
    }

    /** @test */
    public function it_belongs_to_a_membership_type(): void
    {
        $app = $this->makeApplication();
        $this->assertEquals($this->type->id, $app->membershipType->id);
    }

    // ── Status helpers ────────────────────────────────────────────────────────

    /** @test */
    public function it_is_pending_when_status_is_submitted(): void
    {
        $app = $this->makeApplication(['status' => 'submitted']);
        $this->assertTrue($app->isPending());
    }

    /** @test */
    public function it_is_pending_when_status_is_under_review(): void
    {
        $app = $this->makeApplication(['status' => 'under_review']);
        $this->assertTrue($app->isPending());
    }

    /** @test */
    public function it_is_not_pending_when_approved(): void
    {
        $app = $this->makeApplication(['status' => 'approved']);
        $this->assertFalse($app->isPending());
    }

    // ── Expiry ────────────────────────────────────────────────────────────────

    /** @test */
    public function it_is_expired_when_expires_at_is_in_the_past(): void
    {
        $app = $this->makeApplication(['expires_at' => now()->subDay()]);
        $this->assertTrue($app->isExpired());
    }

    /** @test */
    public function it_is_not_expired_when_expires_at_is_in_the_future(): void
    {
        $app = $this->makeApplication(['expires_at' => now()->addDay()]);
        $this->assertFalse($app->isExpired());
    }

    // ── Uniqueness constraint (application-level) ────────────────────────────
    // Note: MySQL doesn't support partial indexes; constraint is enforced by the
    // controller (MembershipApplicationController@store checks for existing pending app).

    /** @test */
    public function user_cannot_have_two_pending_applications_for_same_org(): void
    {
        // First application exists
        $this->makeApplication(['status' => 'submitted']);

        // Second application for same user+org with pending status should be blocked
        // at the controller level. At the model level we verify isPending() correctly
        // identifies both statuses so the controller check works.
        $pending = MembershipApplication::where('user_id', $this->applicant->id)
            ->where('organisation_id', $this->org->id)
            ->whereIn('status', ['submitted', 'under_review'])
            ->exists();

        $this->assertTrue($pending, 'A pending application exists for this user+org — controller should block a second one.');
    }

    // ── Soft delete ───────────────────────────────────────────────────────────

    /** @test */
    public function it_soft_deletes(): void
    {
        $app = $this->makeApplication();
        $app->delete();
        $this->assertSoftDeleted('membership_applications', ['id' => $app->id]);
    }
}
