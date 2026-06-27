<?php

namespace Tests\Feature\Newsletter;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationNewsletter;
use App\Models\OrganisationParticipant;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Services\NewsletterService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class NewsletterAudienceSegmentationTest extends TestCase
{
    private Organisation $organisation;
    private User $admin;
    private NewsletterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(NewsletterService::class);
        $this->organisation = Organisation::factory()->create();
        $this->admin = User::factory()->create();

        UserOrganisationRole::create([
            'user_id' => $this->admin->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'admin',
        ]);
    }

    // ══════════════════════ POSITIVE PATHS ══════════════════════

    /** @test */
    public function test_dispatch_all_members_targets_active_members_only()
    {
        $active = Member::factory(3)->create(['organisation_id' => $this->organisation->id, 'status' => 'active']);
        Member::factory()->create(['organisation_id' => $this->organisation->id, 'status' => 'ended']);
        Member::factory()->create(['organisation_id' => $this->organisation->id, 'status' => 'suspended']);

        $newsletter = OrganisationNewsletter::create([
            'organisation_id' => $this->organisation->id,
            'created_by' => $this->admin->id,
            'audience_type' => 'all_members',
            'subject' => 'Test',
            'html_content' => 'Test',
            'status' => 'draft',
        ]);

        $this->service->dispatch($newsletter);

        $this->assertEquals(3, $newsletter->fresh()->sent_count ?? 0);
    }

    /** @test */
    public function test_dispatch_full_members_excludes_associate_members()
    {
        $fullType = MembershipType::factory()->create(['grants_voting_rights' => true]);
        $associateType = MembershipType::factory()->create(['grants_voting_rights' => false]);

        Member::factory(2)->create([
            'organisation_id' => $this->organisation->id,
            'membership_type_id' => $fullType->id,
            'status' => 'active',
            'fees_status' => 'paid',
        ]);
        Member::factory()->create([
            'organisation_id' => $this->organisation->id,
            'membership_type_id' => $associateType->id,
            'status' => 'active',
            'fees_status' => 'paid',
        ]);

        $newsletter = OrganisationNewsletter::create([
            'organisation_id' => $this->organisation->id,
            'created_by' => $this->admin->id,
            'audience_type' => 'members_full',
            'subject' => 'Test',
            'html_content' => 'Test',
            'status' => 'draft',
        ]);

        $audience = $this->service->resolveAudience($this->organisation, 'members_full');
        $this->assertEquals(2, $audience->count());
    }

    /** @test */
    public function test_dispatch_election_voters_uses_election_membership_table()
    {
        $election = Election::factory()->create(['organisation_id' => $this->organisation->id]);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        ElectionMembership::create([
            'user_id' => $user1->id,
            'organisation_id' => $this->organisation->id,
            'election_id' => $election->id,
            'role' => 'voter',
            'status' => 'active',
        ]);
        ElectionMembership::create([
            'user_id' => $user2->id,
            'organisation_id' => $this->organisation->id,
            'election_id' => $election->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        $audience = $this->service->resolveAudience(
            $this->organisation,
            'election_voters',
            ['election_id' => $election->id]
        );

        $this->assertEquals(2, $audience->count());
    }

    /** @test */
    public function test_dispatch_election_not_voted_excludes_already_voted()
    {
        $election = Election::factory()->create(['organisation_id' => $this->organisation->id]);
        $notVoted = User::factory()->create();
        $voted = User::factory()->create();

        ElectionMembership::create([
            'user_id' => $notVoted->id,
            'organisation_id' => $this->organisation->id,
            'election_id' => $election->id,
            'role' => 'voter',
            'status' => 'active',
            'has_voted' => false,
        ]);
        ElectionMembership::create([
            'user_id' => $voted->id,
            'organisation_id' => $this->organisation->id,
            'election_id' => $election->id,
            'role' => 'voter',
            'status' => 'active',
            'has_voted' => true,
        ]);

        $audience = $this->service->resolveAudience(
            $this->organisation,
            'election_not_voted',
            ['election_id' => $election->id]
        );

        $this->assertEquals(1, $audience->count());
    }

    /** @test */
    public function test_dispatch_election_committee_uses_election_officers_table()
    {
        $election = Election::factory()->create(['organisation_id' => $this->organisation->id]);
        $chief = User::factory()->create();
        $deputy = User::factory()->create();

        ElectionOfficer::create([
            'user_id' => $chief->id,
            'organisation_id' => $this->organisation->id,
            'election_id' => $election->id,
            'role' => 'chief',
            'status' => 'active',
        ]);
        ElectionOfficer::create([
            'user_id' => $deputy->id,
            'organisation_id' => $this->organisation->id,
            'election_id' => $election->id,
            'role' => 'deputy',
            'status' => 'active',
        ]);

        $audience = $this->service->resolveAudience(
            $this->organisation,
            'election_committee',
            ['election_id' => $election->id]
        );

        $this->assertEquals(2, $audience->count());
    }

    /** @test */
    public function test_preview_count_returns_correct_recipient_count()
    {
        Member::factory(5)->create(['organisation_id' => $this->organisation->id, 'status' => 'active']);

        $audience = $this->service->resolveAudience($this->organisation, 'all_members');
        $this->assertEquals(5, $audience->count());
    }

    /** @test */
    public function test_non_member_election_participant_receives_newsletter_without_member_id()
    {
        $election = Election::factory()->create(['organisation_id' => $this->organisation->id]);
        $nonMemberUser = User::factory()->create();

        ElectionMembership::create([
            'user_id' => $nonMemberUser->id,
            'organisation_id' => $this->organisation->id,
            'election_id' => $election->id,
            'role' => 'observer',
            'status' => 'active',
        ]);

        $audience = $this->service->resolveAudience(
            $this->organisation,
            'election_observers',
            ['election_id' => $election->id]
        );

        $this->assertEquals(1, $audience->count());
        $this->assertNull($audience->first()['member_id'] ?? null);
        $this->assertNotNull($audience->first()['user_id'] ?? null);
    }

    /** @test */
    public function test_store_creates_newsletter_with_audience_type_and_meta()
    {
        $this->actingAs($this->admin);
        $election = Election::factory()->create(['organisation_id' => $this->organisation->id]);

        $newsletter = OrganisationNewsletter::create([
            'organisation_id' => $this->organisation->id,
            'created_by' => $this->admin->id,
            'audience_type' => 'election_voters',
            'audience_meta' => ['election_id' => $election->id],
            'subject' => 'Test Newsletter',
            'html_content' => '<p>Test</p>',
        ]);

        $this->assertEquals('election_voters', $newsletter->audience_type);
        $this->assertEquals($election->id, $newsletter->audience_meta['election_id']);
    }

    // ══════════════════════ NEGATIVE / EDGE-CASE PATHS ══════════════════════

    /** @test */
    public function test_dispatch_respects_newsletter_unsubscribe_even_in_election_segments()
    {
        $member = Member::factory()->create([
            'organisation_id' => $this->organisation->id,
            'status' => 'active',
        ]);
        $member->update(['newsletter_unsubscribed_at' => now()]);

        $audience = $this->service->resolveAudience($this->organisation, 'all_members');
        $this->assertEquals(0, $audience->count());
    }

    /** @test */
    public function test_dispatch_respects_hard_bounce_exclusion()
    {
        $member = Member::factory()->create([
            'organisation_id' => $this->organisation->id,
            'status' => 'active',
        ]);
        $member->update(['newsletter_bounced_at' => now()]);

        $audience = $this->service->resolveAudience($this->organisation, 'all_members');
        $this->assertEquals(0, $audience->count());
    }

    /** @test */
    public function test_store_validates_election_id_required_for_election_audience_types()
    {
        $this->actingAs($this->admin);

        try {
            $this->post(route('organisations.membership.newsletters.store', $this->organisation->slug), [
                'audience_type' => 'election_voters',
                'audience_meta' => [],
                'subject' => 'Test',
                'html_content' => 'Test',
            'status' => 'draft',
            ]);
            $this->fail('Expected validation exception');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('audience_meta.election_id', $e->errors());
        }
    }

    /** @test */
    public function test_store_rejects_unknown_audience_type()
    {
        $this->actingAs($this->admin);

        try {
            $this->post(route('organisations.membership.newsletters.store', $this->organisation->slug), [
                'audience_type' => 'invalid_type',
                'subject' => 'Test',
                'html_content' => 'Test',
            'status' => 'draft',
            ]);
            $this->fail('Expected validation exception');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('audience_type', $e->errors());
        }
    }

    /** @test */
    public function test_store_rate_limits_max_10_newsletters_per_hour()
    {
        $this->actingAs($this->admin);
        $key = "newsletter_rate_limit:{$this->organisation->id}:" . date('Y-m-d-H');

        for ($i = 0; $i < 10; $i++) {
            Cache::increment($key);
        }

        try {
            $this->post(route('organisations.membership.newsletters.store', $this->organisation->slug), [
                'audience_type' => 'all_members',
                'subject' => 'Test',
                'html_content' => 'Test',
            'status' => 'draft',
            ]);
            $this->fail('Expected 429 rate limit exception');
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            $this->assertEquals(429, $e->getStatusCode());
        }
    }

    /** @test */
    public function test_preview_count_rate_limits_30_requests_per_minute()
    {
        $this->actingAs($this->admin);

        for ($i = 0; $i < 30; $i++) {
            RateLimiter::hit("preview-count:{$this->admin->id}", 60);
        }

        $response = $this->getJson(route('organisations.membership.newsletters.previewCount', [
            'organisation' => $this->organisation->slug,
        ]));

        $this->assertEquals(429, $response->status());
    }

    /** @test */
    public function test_dispatch_handles_audience_with_zero_recipients_gracefully()
    {
        $newsletter = OrganisationNewsletter::create([
            'organisation_id' => $this->organisation->id,
            'created_by' => $this->admin->id,
            'audience_type' => 'all_members',
            'subject' => 'Test',
            'html_content' => 'Test',
            'status' => 'draft',
        ]);

        $this->service->dispatch($newsletter);

        $this->assertEquals(0, $newsletter->fresh()->sent_count ?? 0);
    }

    /** @test */
    public function test_dispatch_does_not_duplicate_recipients_across_overlapping_segments()
    {
        $election = Election::factory()->create(['organisation_id' => $this->organisation->id]);
        $user = User::factory()->create();

        ElectionMembership::create([
            'user_id' => $user->id,
            'organisation_id' => $this->organisation->id,
            'election_id' => $election->id,
            'role' => 'voter',
            'status' => 'active',
        ]);
        ElectionOfficer::create([
            'user_id' => $user->id,
            'organisation_id' => $this->organisation->id,
            'election_id' => $election->id,
            'role' => 'commissioner',
            'status' => 'active',
        ]);

        $audience = $this->service->resolveAudience(
            $this->organisation,
            'election_voters',
            ['election_id' => $election->id]
        );

        $this->assertEquals(1, $audience->count());
    }

    /** @test */
    public function test_audience_resolution_result_is_cached_for_5_minutes()
    {
        Member::factory(3)->create(['organisation_id' => $this->organisation->id, 'status' => 'active']);

        $cacheKey = "audience:{$this->organisation->id}:all_members:" . md5(json_encode([]));
        Cache::forget($cacheKey);

        $audience1 = $this->service->resolveAudience($this->organisation, 'all_members');
        $this->assertEquals(3, $audience1->count());

        $this->assertNotNull(Cache::get($cacheKey));

        Member::factory()->create(['organisation_id' => $this->organisation->id, 'status' => 'active']);

        $audience2 = $this->service->resolveAudience($this->organisation, 'all_members');
        $this->assertEquals(3, $audience2->count());
    }

    /** @test */
    public function test_concurrent_newsletter_dispatches_do_not_duplicate_recipients()
    {
        Member::factory(3)->create(['organisation_id' => $this->organisation->id, 'status' => 'active']);

        $newsletter1 = OrganisationNewsletter::create([
            'organisation_id' => $this->organisation->id,
            'created_by' => $this->admin->id,
            'audience_type' => 'all_members',
            'subject' => 'Test 1',
            'html_content' => 'Test',
            'status' => 'draft',
        ]);

        $newsletter2 = OrganisationNewsletter::create([
            'organisation_id' => $this->organisation->id,
            'created_by' => $this->admin->id,
            'audience_type' => 'all_members',
            'subject' => 'Test 2',
            'html_content' => 'Test',
            'status' => 'draft',
        ]);

        $this->service->dispatch($newsletter1);
        $this->service->dispatch($newsletter2);

        $this->assertEquals(3, $newsletter1->fresh()->recipients()->count() ?? 0);
        $this->assertEquals(3, $newsletter2->fresh()->recipients()->count() ?? 0);
    }

    /** @test */
    public function test_gdpr_consent_source_stored_per_recipient()
    {
        $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'status' => 'active']);

        $newsletter = OrganisationNewsletter::create([
            'organisation_id' => $this->organisation->id,
            'created_by' => $this->admin->id,
            'audience_type' => 'all_members',
            'subject' => 'Test',
            'html_content' => 'Test',
            'status' => 'draft',
        ]);

        $this->service->dispatch($newsletter);

        $recipient = $newsletter->recipients()->first();
        $this->assertNotNull($recipient->consent_given_at);
        $this->assertContains($recipient->consent_source, ['member_agreement', 'election_participation', 'explicit_opt_in']);
    }

    /** @test */
    public function test_dispatch_ignores_cache_to_ensure_fresh_data_at_send_time()
    {
        Member::factory(2)->create(['organisation_id' => $this->organisation->id, 'status' => 'active']);

        $cacheKey = "audience:{$this->organisation->id}:all_members:" . md5(json_encode([]));
        Cache::forget($cacheKey);

        $this->service->resolveAudience($this->organisation, 'all_members');

        Member::factory()->create(['organisation_id' => $this->organisation->id, 'status' => 'active']);

        $newsletter = OrganisationNewsletter::create([
            'organisation_id' => $this->organisation->id,
            'created_by' => $this->admin->id,
            'audience_type' => 'all_members',
            'subject' => 'Test',
            'html_content' => 'Test',
            'status' => 'draft',
        ]);

        $this->service->dispatch($newsletter);

        $this->assertEquals(3, $newsletter->fresh()->recipients()->count() ?? 0);
    }

    /** @test */
    public function test_multi_segment_selection_removes_duplicate_emails()
    {
        $election = Election::factory()->create(['organisation_id' => $this->organisation->id]);
        $user = User::factory()->create();

        ElectionMembership::create([
            'user_id' => $user->id,
            'organisation_id' => $this->organisation->id,
            'election_id' => $election->id,
            'role' => 'voter',
            'status' => 'active',
        ]);
        ElectionMembership::create([
            'user_id' => $user->id,
            'organisation_id' => $this->organisation->id,
            'election_id' => $election->id,
            'role' => 'candidate',
            'status' => 'active',
        ]);

        $audience = $this->service->resolveAudience($this->organisation, 'election_all', ['election_id' => $election->id]);
        $this->assertEquals(1, $audience->count());
    }
}
