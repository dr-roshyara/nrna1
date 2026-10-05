<?php

namespace Tests\Feature\Characterization\Phase1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Phase 1 — proves the synthetic fixture completes one real cast through
 * VoteController::store, so the characterization tests observe real behaviour.
 */
class CastFixtureSmokeTest extends TestCase
{
    use RefreshDatabase;
    use CastFixtures;

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

    public function test_one_synthetic_voter_casts_one_real_vote(): void
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();

        $errors = [];
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Log\Events\MessageLogged::class, function ($e) use (&$errors) {
            if (in_array($e->level, ['error', 'critical', 'warning'], true)) {
                $errors[] = $e->message . ' ' . json_encode(array_intersect_key($e->context, array_flip(['error', 'message', 'exception', 'file', 'line'])));
            }
        });

        $response = $this->cast($voter, $slug, $code);

        $this->assertSame(
            1,
            DB::table('votes')->where('election_id', $this->election->id)->count(),
            'one vote row written; response status ' . $response->status() . ' errors ' . json_encode(session('errors')?->all() ?? []) . ' logged: ' . implode(' | ', $errors)
        );
        $this->assertTrue((bool) $code->fresh()->has_voted);
    }
}
