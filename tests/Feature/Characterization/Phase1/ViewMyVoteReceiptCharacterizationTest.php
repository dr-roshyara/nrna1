<?php

namespace Tests\Feature\Characterization\Phase1;

use App\Models\User;
use App\Notifications\SendVoteSavingCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Phase 1 — group 1B, VIEW MY VOTE & RECEIPTS (roadmap Phase 1B, SR §11a K2–K5).
 *
 * For each flow: participation proof · ballot retrieval · correlation bridge · unknown.
 * SYNTHETIC DATA ONLY. The feature is characterized, not changed or removed.
 */
class ViewMyVoteReceiptCharacterizationTest extends TestCase
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

    /** Cast one vote; return [voter, voteId, the code e-mailed to the voter]. */
    private function castAndCaptureReceipt(): array
    {
        [$voter, $slug, $code] = $this->voterReadyToCast();
        $this->cast($voter, $slug, $code);
        $voteId = DB::table('votes')->where('election_id', $this->election->id)->value('id');

        $sent = null;
        Notification::assertSentTo($voter, SendVoteSavingCode::class, function (SendVoteSavingCode $n) use (&$sent) {
            $sent = $n->vote_saving_code;
            return true;
        });

        return [$voter, $voteId, $sent];
    }

    // ── K5: notification content ─────────────────────────────────────────────

    public function test_k5_known_gap_the_email_to_the_voter_contains_the_vote_id(): void
    {
        [$voter, $voteId, $sent] = $this->castAndCaptureReceipt();

        $this->assertMatchesRegularExpression('/^[0-9a-f]{32}_/', $sent, 'format: {32-hex private_key}_{vote_id}');
        $this->assertStringEndsWith('_' . $voteId, $sent,
            'KNOWN GAP (correlation bridge): the message addressed to the voter carries the ballot id — voter mailbox / mail transport ↔ vote');
    }

    // ── K3: receipt creation ─────────────────────────────────────────────────

    public function test_k3_the_receipt_is_stored_as_the_full_secret_without_a_voter_reference(): void
    {
        [$voter, $voteId, $sent] = $this->castAndCaptureReceipt();

        $receipt = DB::table('receipt_codes')->where('election_id', $this->election->id)->first();
        $this->assertNotNull($receipt);
        $this->assertSame($sent, $receipt->receipt_code, 'the server stores the complete secret (private_key_voteId) in clear');

        $columns = Schema::getColumnListing('receipt_codes');
        $this->assertNotContains('user_id', $columns, 'receipt_codes has no user column');

        $vote = DB::table('votes')->where('id', $voteId)->first();
        [$privateKey] = explode('_', $sent, 2);
        $this->assertSame(hash('sha256', $privateKey . $voteId . config('app.key')), $vote->receipt_hash,
            'votes.receipt_hash = sha256(private_key ‖ vote.id ‖ app.key) — verifiable from the stored receipt + config');
    }

    // ── K2: view my vote ─────────────────────────────────────────────────────

    public function test_k2_known_gap_the_receipt_code_retrieves_the_ballot_contents(): void
    {
        [$voter, $voteId, $sent] = $this->castAndCaptureReceipt();

        $this->actingAs($voter)
            ->post(route('vote.submit_code_to_view_vote'), ['voting_code' => $sent, 'election_type' => 'real']);

        $display = session('vote_display_data_' . $voteId);
        $this->assertIsArray($display, 'the ballot display data is produced');
        $this->assertArrayHasKey('vote_selections', $display,
            'KNOWN GAP (ballot retrieval): the code returns the exact ballot selections, not only a participation proof');
    }

    public function test_k2_known_gap_any_authenticated_user_holding_the_code_retrieves_the_ballot(): void
    {
        [$voter, $voteId, $sent] = $this->castAndCaptureReceipt();
        $someoneElse = User::factory()->create(['email_verified_at' => now(), 'organisation_id' => $this->org->id]);

        $this->actingAs($someoneElse)
            ->post(route('vote.submit_code_to_view_vote'), ['voting_code' => $sent, 'election_type' => 'real']);

        $this->assertArrayHasKey('vote_selections', (array) session('vote_display_data_' . $voteId),
            'KNOWN GAP (coercion / vote selling): retrieval is not bound to the voter — whoever holds the code sees the ballot');
    }

    // ── K4: receipt lookup (committee list) ──────────────────────────────────

    public function test_k4_the_receipt_list_source_holds_every_full_secret_of_the_election(): void
    {
        // VotingReceiptController::index/download (authorize viewResults; results published) list
        // ReceiptCode::where('election_id')->receipt_code — i.e. these rows, shuffled.
        foreach (['AAAA1111', 'BBBB2222'] as $c) {
            [$voter, $slug, $code] = $this->voterReadyToCast($c);
            $this->cast($voter, $slug, $code);
        }

        $codes = DB::table('receipt_codes')->where('election_id', $this->election->id)->pluck('receipt_code');
        $this->assertCount(2, $codes);
        foreach ($codes as $receipt) {
            $this->assertMatchesRegularExpression('/^[0-9a-f]{32}_[0-9a-f-]{36}$/', $receipt,
                'KNOWN GAP: each listed receipt is the full secret; a holder of the list can open every ballot via view-my-vote');
        }
    }
}
