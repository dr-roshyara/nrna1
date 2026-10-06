<?php

namespace Tests\Feature\Voting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\TestCase;

/**
 * PBDIGIT-247 — RED: IP Binding at the final cast (forCast). Plan: docs/plans/20261006-2125-pbdigit-247-ip-binding-plan.md.
 *
 * The cast is re-checked by the Security Verdict Authority (Q3-D2), so a voter who passed the code step from the
 * captured IP but submits the ballot from another network is refused; nothing is consumed.
 * The test client's IP is 127.0.0.1 (CastFixtures::cast()).
 *
 *   B-C1 binding on, the cast comes from an IP other than the captured one → refused, no vote, has_voted unchanged,
 *        specific message (Q2) without the captured IP
 *   B-C2 binding on, the cast comes from the captured IP → the vote is recorded
 *   B-C3 binding on, nothing captured → refused at the cast (fail closed)
 * Synthetic data only. No voter ↔ ballot link is introduced (ADR-T11).
 */
class IpBindingCastTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;
    use IpBindingFixtures;

    private const CAST_IP = '127.0.0.1';

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeVotingElection();
    }

    protected function tearDown(): void
    {
        $this->tearDownCast();
        parent::tearDown();
    }

    private function votes(): int
    {
        return DB::table('votes')->where('election_id', $this->election->id)->count();
    }

    public function test_b_c1_a_cast_from_a_different_ip_is_refused_and_consumes_nothing(): void
    {
        $this->bindingOn();
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->capturedIp($voter, '10.77.0.9');

        $response = $this->cast($voter, $slug, $code);

        $this->assertNotSame(500, $response->status());
        $response->assertSessionHasErrors(['vote' => self::BINDING_MESSAGE]);
        $this->assertStringNotContainsString('10.77.0.9', json_encode(session('errors')?->getBag('default')->toArray()));
        $this->assertSame(0, $this->votes(), 'no vote is written');
        $this->assertFalse((bool) $code->fresh()->has_voted, 'has_voted is not consumed');
    }

    public function test_b_c2_a_cast_from_the_captured_ip_is_recorded(): void
    {
        $this->bindingOn();
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->capturedIp($voter, self::CAST_IP);

        $response = $this->cast($voter, $slug, $code);

        $this->assertNotSame(500, $response->status());
        $response->assertSessionHasNoErrors();
        $this->assertTrue((bool) $code->fresh()->has_voted, 'the vote is recorded from the bound IP');
    }

    public function test_b_c3_a_cast_without_a_captured_ip_is_refused_fail_closed(): void
    {
        $this->bindingOn();
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $response = $this->cast($voter, $slug, $code);

        $this->assertNotSame(500, $response->status());
        $response->assertSessionHasErrors(['vote' => self::BINDING_MESSAGE]);
        $this->assertSame(0, $this->votes());
        $this->assertFalse((bool) $code->fresh()->has_voted);
    }
}
