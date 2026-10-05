<?php

namespace Tests\Feature\Voting;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Characterization\Phase1\CastFixtures;
use Tests\TestCase;

/**
 * PBDIGIT-241 · RED — logs must not link a voter to a ballot nor expose the receipt secret (F-V6 / F-V7).
 *
 * Constitutional invariant: participation knowledge without ballot knowledge. The receipt is
 * "{32-hex secret}_{vote id}" (stored in receipt_codes, e-mailed to the voter); with the secret anyone can open
 * the ballot (Phase 1 K2).
 *
 *  L1  final cast: the receipt secret — full or any 16-character prefix — occurs in no captured log entry (F-V6a/b)
 *  L2  confirmCorrect(): no entry contains the secret, and no single entry contains both the acting user's id
 *      and the vote id (F-V7)
 *  L3  submit_code_to_view_vote(): same as L2 (F-V7 sibling)
 *  L4  final cast: no single entry contains both the voter's user id and the vote id (guard)
 *
 * A "captured log entry" = message + JSON-encoded context (CastFixtures::captureLogs / logsMentioning), all channels.
 * NOT asserted here (recorded in PBDIGIT-241, decision needed): vote id + voter_slug_id and vote id + IP in one entry
 * (Phase 1 K8 / A1), cross-entry correlation within one request, the receipt stored in clear in receipt_codes.
 * Synthetic data only.
 */
class VoterBallotLogLinkageTest extends TestCase
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

    /** Cast one real vote; returns [voter, voteId, receipt, secret]. */
    private function castOneVote(): array
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->cast($voter, $slug, $code);

        $voteId = DB::table('votes')->where('election_id', $this->election->id)->value('id');
        $receipt = DB::table('receipt_codes')->where('election_id', $this->election->id)->value('receipt_code');
        $this->assertNotNull($voteId, 'precondition: the vote was cast');
        $this->assertNotNull($receipt, 'precondition: the receipt was stored');
        [$secret] = explode('_', $receipt, 2);
        $this->assertMatchesRegularExpression('/^[0-9a-f]{32}$/', $secret, 'precondition: receipt = {32-hex secret}_{vote id}');

        return [$voter, $voteId, $receipt, $secret];
    }

    /** Messages of captured entries that contain $needle. */
    private function entriesWith(string $needle): array
    {
        return array_map(fn ($e) => $e['message'], $this->logsMentioning($needle));
    }

    /** Messages of captured entries that contain BOTH values. */
    private function entriesWithBoth(string $a, string $b): array
    {
        return array_values(array_map(fn ($e) => $e['message'], array_filter(
            $this->logged,
            function (array $e) use ($a, $b) {
                $text = $e['message'] . ' ' . json_encode($e['context']);

                return str_contains($text, $a) && str_contains($text, $b);
            }
        )));
    }

    private function assertSecretNotLogged(string $secret, string $where): void
    {
        $this->assertSame([], $this->entriesWith($secret), "{$where}: the full receipt secret occurs in a log entry");
        $this->assertSame([], $this->entriesWith(substr($secret, 0, 16)),
            "{$where}: a 16-character prefix of the receipt secret occurs in a log entry");
    }

    // ── L1 / L4: the final cast ──────────────────────────────────────────────

    public function test_l1_the_final_cast_logs_no_part_of_the_receipt_secret(): void
    {
        $this->captureLogs();
        [, , , $secret] = $this->castOneVote();

        $this->assertSecretNotLogged($secret, 'final cast');
    }

    public function test_l4_the_final_cast_logs_no_entry_pairing_the_voter_with_the_vote(): void
    {
        $this->captureLogs();
        [$voter, $voteId] = $this->castOneVote();

        $this->assertSame([], $this->entriesWithBoth($voter->id, $voteId),
            'final cast: no single log entry may contain both the voter\'s user id and the vote id');
    }

    // ── L2: confirmCorrect() ─────────────────────────────────────────────────

    public function test_l2_confirming_a_receipt_logs_neither_the_secret_nor_the_voter_with_the_vote(): void
    {
        [$voter, $voteId, $receipt, $secret] = $this->castOneVote();
        $this->captureLogs();
        $this->logged = [];   // only this request

        $this->actingAs($voter)->post(route('vote.confirm-correct'), ['receipt_code' => $receipt]);

        $this->assertNotNull(DB::table('receipt_codes')->where('receipt_code', $receipt)->value('reverified_at'),
            'precondition: the receipt was confirmed');
        $this->assertSecretNotLogged($secret, 'confirmCorrect()');
        $this->assertSame([], $this->entriesWithBoth($voter->id, $voteId),
            'confirmCorrect(): no single log entry may contain both the acting user\'s id and the vote id (the receipt contains it)');
    }

    // ── L3: submit_code_to_view_vote() ───────────────────────────────────────

    public function test_l3_viewing_a_vote_by_receipt_logs_neither_the_secret_nor_the_voter_with_the_vote(): void
    {
        [$voter, $voteId, $receipt, $secret] = $this->castOneVote();
        $this->captureLogs();
        $this->logged = [];   // only this request

        $this->actingAs($voter)
            ->post(route('vote.submit_code_to_view_vote'), ['voting_code' => $receipt, 'election_type' => 'real']);

        $this->assertIsArray(session('vote_display_data_' . $voteId), 'precondition: the vote was retrieved');
        $this->assertSecretNotLogged($secret, 'submit_code_to_view_vote()');
        $this->assertSame([], $this->entriesWithBoth($voter->id, $voteId),
            'submit_code_to_view_vote(): no single log entry may contain both the acting user\'s id and the vote id');
    }
}
