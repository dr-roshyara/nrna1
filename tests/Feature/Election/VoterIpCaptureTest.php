<?php

namespace Tests\Feature\Election;

use App\Contexts\Elections\Application\Ports\VotingEntitlementQuery;
use App\Contexts\Trust\Domain\Events\IdentityAttestedEvent;
use App\Models\Election;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Tests\Support\ElectionScenarioFactory;
use Tests\TestCase;

/**
 * Capture IP Address — a separate IP capability (PBDIGIT-222 decisions 2026-10-04; PO 2026-10-06) — RED.
 *
 * The old combined "Verify Voter Identity" action is split: identity → voter_verifications (PBDIGIT-248);
 * IP → voter_ip_verifications (this contract). Display / evidence only — no enforcement (PBDIGIT-247).
 *
 * PROPOSED contract (decisions flagged in the report, to be approved before GREEN):
 *   POST elections.voters.capture-ip  { user_id, notes? }  — Chief / Deputy of the election.
 *   The server captures the voter's last-login IP (users.current_ip); the client sends no IP.
 *   One current row per (election, voter): a re-capture replaces it (like identity, ID-D3).
 *   A voter without a known last-login IP cannot be captured.
 *
 *   I1  capture writes one election-scoped voter_ip_verifications row with the voter's last-login IP
 *   I2  capture never touches voter_verifications and never changes admission / entitlement
 *   I3  capture raises no identity event
 *   I4  only the election's Chief / Deputy may capture
 *   I5  only a registered voter of this election can be captured
 *   I6  a re-capture replaces the current IP (one row per election + voter)
 *   I7  a voter without a known IP cannot be captured
 *   I8  the Identity endpoint still rejects the legacy IP / device fields (unchanged)
 * Synthetic data only.
 */
class VoterIpCaptureTest extends TestCase
{
    use RefreshDatabase;
    use C4Fixtures;

    private Election $election;
    private User $chief;
    private User $voter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeOrganisation();
        $this->election = ElectionScenarioFactory::setupAdministration($this->org);
        $this->chief = $this->officer($this->election, 'chief');
        $this->voter = $this->registeredVoter($this->election);
        $this->voter->forceFill(['current_ip' => '203.0.113.7'])->save();
        // "Voter's IP Verification" enabled for I1–I14 (fixture only; I15 covers the disabled election).
        DB::table('elections')->where('id', $this->election->id)->update(['ip_verification_enabled' => true]);
    }

    private function capture(User $actor, User $target, array $extra = [])
    {
        return $this->actingAs($actor)->withSession(['current_organisation_id' => $this->org->id])
            ->from('/voters-manage')
            ->post($this->voterRoute('elections.voters.capture-ip', $this->election),
                array_merge(['user_id' => $target->id, 'notes' => 'home WiFi'], $extra));
    }

    private function ipRows(): \Illuminate\Support\Collection
    {
        return DB::table('voter_ip_verifications')->where('election_id', $this->election->id)->where('user_id', $this->voter->id)->get();
    }

    public function test_i1_capture_records_the_voters_last_login_ip_for_this_election(): void
    {
        $this->capture($this->chief, $this->voter)->assertSessionHasNoErrors();

        $rows = $this->ipRows();
        $this->assertCount(1, $rows);
        $this->assertSame('203.0.113.7', $rows[0]->ip_address, 'the server captures users.current_ip');
        $this->assertSame($this->chief->id, $rows[0]->captured_by);
        $this->assertNotNull($rows[0]->captured_at);
        $this->assertSame('home WiFi', $rows[0]->notes);
    }

    public function test_i2_capture_never_touches_identity_evidence_or_admission(): void
    {
        $this->requireIdentityVerification($this->election);
        $standingBefore = app(VotingEntitlementQuery::class)->standingOf($this->voter->id, $this->election->id);

        $this->capture($this->chief, $this->voter)->assertSessionHasNoErrors();

        $this->assertSame(0, DB::table('voter_verifications')->where('election_id', $this->election->id)->count(), 'no identity evidence row');
        $this->assertSame($standingBefore, app(VotingEntitlementQuery::class)->standingOf($this->voter->id, $this->election->id),
            'admission / entitlement unchanged — capturing an IP admits nobody');
        $this->assertFalse(app(VotingEntitlementQuery::class)->standingOf($this->voter->id, $this->election->id)->isEntitled());
    }

    public function test_i3_capture_raises_no_identity_event(): void
    {
        Event::fake([IdentityAttestedEvent::class]);

        $this->capture($this->chief, $this->voter)->assertSessionHasNoErrors();

        Event::assertNotDispatched(IdentityAttestedEvent::class);
    }

    public function test_i4_only_the_elections_chief_or_deputy_may_capture(): void
    {
        $outsider = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);

        $this->assertContains($this->capture($outsider, $this->voter)->status(), [403, 302]);
        $this->assertCount(0, $this->ipRows());
    }

    public function test_i5_only_a_registered_voter_of_this_election_can_be_captured(): void
    {
        $stranger = User::factory()->create(['organisation_id' => $this->org->id, 'current_ip' => '198.51.100.1']);

        $this->capture($this->chief, $stranger)->assertSessionHasErrors('user_id');
        $this->assertSame(0, DB::table('voter_ip_verifications')->where('user_id', $stranger->id)->count());
    }

    public function test_i6_a_recapture_replaces_the_current_ip(): void
    {
        $this->capture($this->chief, $this->voter)->assertSessionHasNoErrors();
        $this->voter->forceFill(['current_ip' => '198.51.100.20'])->save();

        $this->capture($this->chief, $this->voter)->assertSessionHasNoErrors();

        $rows = $this->ipRows();
        $this->assertCount(1, $rows);
        $this->assertSame('198.51.100.20', $rows[0]->ip_address);
    }

    public function test_i7_a_voter_without_a_known_ip_cannot_be_captured(): void
    {
        $this->voter->forceFill(['current_ip' => null])->save();

        $this->capture($this->chief, $this->voter)->assertSessionHasErrors('user_id');
        $this->assertCount(0, $this->ipRows());
    }

    public function test_i9_an_ipv4_address_is_captured(): void
    {
        $this->voter->forceFill(['current_ip' => '192.0.2.44'])->save();

        $this->capture($this->chief, $this->voter)->assertSessionHasNoErrors();

        $this->assertSame('192.0.2.44', $this->ipRows()[0]->ip_address);
    }

    public function test_i10_an_ipv6_address_is_captured_accurately(): void
    {
        $this->voter->forceFill(['current_ip' => '2001:db8:85a3::8a2e:370:7334'])->save();

        $this->capture($this->chief, $this->voter)->assertSessionHasNoErrors();

        $this->assertSame('2001:db8:85a3::8a2e:370:7334', $this->ipRows()[0]->ip_address);
    }

    public function test_i11_the_client_cannot_supply_or_override_the_ip(): void
    {
        $this->capture($this->chief, $this->voter, ['ip_address' => '10.66.66.66', 'verified_ip' => '10.66.66.66'])
            ->assertSessionHasErrors(['ip_address', 'verified_ip']);

        $this->assertSame(0, DB::table('voter_ip_verifications')->where('ip_address', '10.66.66.66')->count(), 'no client-supplied IP is stored');
    }

    public function test_i12_capture_is_election_scoped_and_leaves_another_election_untouched(): void
    {
        $other = ElectionScenarioFactory::setupAdministration($this->org);
        $this->registeredVoter($other);   // fixture voter of the other election (distinct user)
        \App\Models\ElectionMembership::create([
            'user_id' => $this->voter->id, 'organisation_id' => $this->org->id, 'election_id' => $other->id,
            'role' => 'voter', 'status' => 'active',
        ]);

        $this->capture($this->chief, $this->voter)->assertSessionHasNoErrors();

        $this->assertCount(1, $this->ipRows(), 'recorded for this election');
        $this->assertSame(0, DB::table('voter_ip_verifications')->where('election_id', $other->id)->count(),
            'the same voter in another election is unaffected');
    }

    public function test_i13_capture_does_not_consult_the_security_verdict_authority(): void
    {
        $refuse = fn () => throw new \LogicException('Capture IP must not consult the Security Verdict Authority');
        $this->app->instance(\App\Contexts\Elections\Application\Ports\VotingSecurityVerdictQuery::class,
            new class($refuse) implements \App\Contexts\Elections\Application\Ports\VotingSecurityVerdictQuery {
                public function __construct(private \Closure $refuse) {}
                public function verdictFor(\App\Contexts\Elections\Application\DTO\SecurityAttemptContext $c): \App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict { ($this->refuse)(); }
            });
        $this->app->instance(\App\Contexts\Elections\Application\Ports\VotingSecurityAssessmentQuery::class,
            new class($refuse) implements \App\Contexts\Elections\Application\Ports\VotingSecurityAssessmentQuery {
                public function __construct(private \Closure $refuse) {}
                public function assess(\App\Contexts\Elections\Application\DTO\SecurityAttemptContext $c): \App\Contexts\Elections\Application\DTO\SecurityAssessment { ($this->refuse)(); }
            });

        $this->capture($this->chief, $this->voter)->assertSessionHasNoErrors();

        $this->assertCount(1, $this->ipRows());
    }

    public function test_i14_a_suspended_voter_cannot_be_captured(): void
    {
        \App\Models\ElectionMembership::where('election_id', $this->election->id)->where('user_id', $this->voter->id)->update(['status' => 'inactive']);

        $this->capture($this->chief, $this->voter)->assertSessionHasErrors('user_id');
        $this->assertCount(0, $this->ipRows());
    }

    /**
     * I15 (PO 2026-10-06, change 8): the setting is enforced server-side, fail-closed.
     * A direct POST while "Voter's IP Verification" is disabled is refused and changes nothing.
     */
    public function test_i15_capture_is_refused_when_ip_verification_is_disabled(): void
    {
        DB::table('elections')->where('id', $this->election->id)->update(['ip_verification_enabled' => false]);
        $this->requireIdentityVerification($this->election);
        $standingBefore = app(VotingEntitlementQuery::class)->standingOf($this->voter->id, $this->election->id);
        $codesBefore = DB::table('codes')->count();
        $votesBefore = DB::table('votes')->count();
        $hasVotedBefore = DB::table('election_memberships')->where('election_id', $this->election->id)
            ->where('user_id', $this->voter->id)->value('has_voted');

        $refuse = fn () => throw new \LogicException('Capture IP must not consult the Security Verdict Authority');
        $this->app->instance(\App\Contexts\Elections\Application\Ports\VotingSecurityVerdictQuery::class,
            new class($refuse) implements \App\Contexts\Elections\Application\Ports\VotingSecurityVerdictQuery {
                public function __construct(private \Closure $refuse) {}
                public function verdictFor(\App\Contexts\Elections\Application\DTO\SecurityAttemptContext $c): \App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict { ($this->refuse)(); }
            });
        $this->app->instance(\App\Contexts\Elections\Application\Ports\VotingSecurityAssessmentQuery::class,
            new class($refuse) implements \App\Contexts\Elections\Application\Ports\VotingSecurityAssessmentQuery {
                public function __construct(private \Closure $refuse) {}
                public function assess(\App\Contexts\Elections\Application\DTO\SecurityAttemptContext $c): \App\Contexts\Elections\Application\DTO\SecurityAssessment { ($this->refuse)(); }
            });

        $this->capture($this->chief, $this->voter)->assertSessionHasErrors('ip_verification');

        $this->assertCount(0, $this->ipRows(), 'no IP evidence row');
        $this->assertSame(0, DB::table('voter_verifications')->where('election_id', $this->election->id)->count(), 'no identity evidence row');
        $this->assertSame($standingBefore, app(VotingEntitlementQuery::class)->standingOf($this->voter->id, $this->election->id),
            'admission / entitlement unchanged');
        $this->assertSame($codesBefore, DB::table('codes')->count());
        $this->assertSame($votesBefore, DB::table('votes')->count());
        $this->assertSame($hasVotedBefore, DB::table('election_memberships')->where('election_id', $this->election->id)
            ->where('user_id', $this->voter->id)->value('has_voted'));
    }

    /**
     * I16 (UI feedback): the Voter Management page projects the current IP capture per voter
     * (display only), so the officer can see that — and when — the IP was captured.
     */
    public function test_i16_the_voter_management_page_shows_the_current_ip_capture(): void
    {
        $page = fn () => $this->actingAs($this->chief)->withSession(['current_organisation_id' => $this->org->id])
            ->get($this->voterRoute('elections.voters.index', $this->election));

        $page()->assertOk()->assertInertia(fn ($p) => $p->missing("ipVerification.{$this->voter->id}"));

        $this->capture($this->chief, $this->voter)->assertSessionHasNoErrors();

        $page()->assertOk()->assertInertia(fn ($p) => $p
            ->where("ipVerification.{$this->voter->id}.ip_address", '203.0.113.7')
            ->where("ipVerification.{$this->voter->id}.notes", 'home WiFi')
            ->whereNot("ipVerification.{$this->voter->id}.captured_at", null)
        );
    }

    public function test_i8_the_identity_endpoint_still_rejects_legacy_ip_and_device_fields(): void
    {
        $this->actingAs($this->chief)->withSession(['current_organisation_id' => $this->org->id])
            ->post($this->voterRoute('elections.voters.verify', $this->election),
                ['user_id' => $this->voter->id, 'attested' => true, 'verified_ip' => '203.0.113.7'])
            ->assertSessionHasErrors('verified_ip');

        $this->assertSame(0, DB::table('voter_verifications')->where('election_id', $this->election->id)->count());
    }
}
