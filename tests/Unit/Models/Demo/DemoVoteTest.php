<?php

namespace Tests\Unit\Models\Demo;

use Tests\TestCase;
use App\Models\DemoVote;
use App\Models\Vote;
use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoVoteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function demo_vote_uses_demo_votes_table()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        // Use raw insert to test table isolation (use columns that exist in migration)
        DB::insert('insert into demo_votes (id, organisation_id, election_id, receipt_hash, voted_at, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $demoVoteId = Str::uuid()->toString(),
            $org->id,
            $election->id,
            hash('sha256', 'demo-receipt-' . Str::random(16) . config('app.salt')),
            now(),
            now(),
            now(),
        ]);

        // Verify it's in demo_votes table
        $demoVote = DB::table('demo_votes')->where('id', $demoVoteId)->first();
        $this->assertNotNull($demoVote);

        // Real votes table should not have this record
        $realVote = DB::table('votes')->where('id', $demoVoteId)->first();
        $this->assertNull($realVote);
    }

    /** @test */
    public function demo_vote_belongs_to_organisation()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        $demoVote = DemoVote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'receipt_hash' => hash('sha256', 'demo-receipt-' . Str::random(16) . config('app.salt')),
            'voted_at' => now(),
        ]);

        $this->assertEquals($org->id, $demoVote->organisation->id);
    }

    /** @test */
    public function demo_vote_belongs_to_election()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        $demoVote = DemoVote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'receipt_hash' => hash('sha256', 'demo-receipt-' . Str::random(16) . config('app.salt')),
            'voted_at' => now(),
        ]);

        $this->assertEquals($election->id, $demoVote->election->id);
    }

    /** @test */
    public function demo_vote_is_demo_returns_true()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        $demoVote = DemoVote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'receipt_hash' => hash('sha256', 'demo-receipt-' . Str::random(16) . config('app.salt')),
            'voted_at' => now(),
        ]);

        $this->assertTrue($demoVote->isDemo());
    }

    /** @test */
    public function demo_vote_is_real_returns_false()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        $demoVote = DemoVote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'receipt_hash' => hash('sha256', 'demo-receipt-' . Str::random(16) . config('app.salt')),
            'voted_at' => now(),
        ]);

        $this->assertFalse($demoVote->isReal());
    }

    /** @test */
    public function demo_vote_has_no_user_relationship()
    {
        // CRITICAL ANONYMITY TEST
        // Verify that DemoVote model does NOT have a user() relationship
        // This ensures demo votes cannot be linked back to voters

        $demoVote = new DemoVote();

        // Relationship must not exist - verify no user() method
        $this->assertFalse(method_exists($demoVote, 'user'));

        // Verify no user_id column exists in database
        $columns = DB::getSchemaBuilder()->getColumnListing('demo_votes');
        $this->assertNotContains('user_id', $columns);
    }

    /** @test */
    public function demo_vote_scope_for_organisation_filters()
    {
        $org1 = Organisation::factory()->tenant()->create();
        $org2 = Organisation::factory()->tenant()->create();

        $election1 = Election::factory()->forOrganisation($org1)->create(['type' => 'demo']);
        $election2 = Election::factory()->forOrganisation($org2)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org1->id]);

        DemoVote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org1->id,
            'election_id' => $election1->id,
            'receipt_hash' => hash('sha256', 'vote-org1-' . Str::random(16) . config('app.salt')),
            'voted_at' => now(),
        ]);

        session(['current_organisation_id' => $org2->id]);

        DemoVote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org2->id,
            'election_id' => $election2->id,
            'receipt_hash' => hash('sha256', 'vote-org2-' . Str::random(16) . config('app.salt')),
            'voted_at' => now(),
        ]);

        $org1_votes = DemoVote::forOrganisation($org1->id)->get();
        $org2_votes = DemoVote::forOrganisation($org2->id)->get();

        $this->assertCount(1, $org1_votes);
        $this->assertEquals($org1->id, $org1_votes->first()->organisation_id);

        $this->assertCount(1, $org2_votes);
        $this->assertEquals($org2->id, $org2_votes->first()->organisation_id);
    }

    /** @test */
    public function demo_vote_scope_for_election_filters()
    {
        $org = Organisation::factory()->tenant()->create();
        $election1 = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);
        $election2 = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        DemoVote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election1->id,
            'receipt_hash' => hash('sha256', 'vote-elec1-' . Str::random(16) . config('app.salt')),
            'voted_at' => now(),
        ]);

        DemoVote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election2->id,
            'receipt_hash' => hash('sha256', 'vote-elec2-' . Str::random(16) . config('app.salt')),
            'voted_at' => now(),
        ]);

        $election1_votes = DemoVote::forElection($election1)->get();
        $election2_votes = DemoVote::forElection($election2)->get();

        $this->assertCount(1, $election1_votes);
        $this->assertEquals($election1->id, $election1_votes->first()->election_id);

        $this->assertCount(1, $election2_votes);
        $this->assertEquals($election2->id, $election2_votes->first()->election_id);
    }

    /** @test */
    public function demo_vote_stores_candidate_selections()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        $candidateSelections = [
            'post_1' => [Str::uuid()->toString(), Str::uuid()->toString()],
            'post_2' => [Str::uuid()->toString()],
        ];

        $demoVote = DemoVote::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'receipt_hash' => hash('sha256', 'demo-receipt-' . Str::random(16) . config('app.salt')),
            'candidate_selections' => $candidateSelections,
            'voted_at' => now(),
        ]);

        $this->assertNotNull($demoVote->candidate_selections);
        $this->assertIsArray($demoVote->candidate_selections);
        $this->assertCount(2, $demoVote->candidate_selections);
    }

    /** @test */
    public function demo_vote_isolation_from_real_votes()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        // Insert demo vote using raw insert (use actual columns from migration)
        $demoVoteId = Str::uuid()->toString();
        $demoReceiptString = 'demo-receipt-' . Str::random(16);
        $demoReceiptHash = hash('sha256', $demoReceiptString . config('app.salt'));
        DB::insert('insert into demo_votes (id, organisation_id, election_id, receipt_hash, voted_at, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $demoVoteId,
            $org->id,
            $election->id,
            $demoReceiptHash,
            now(),
            now(),
            now(),
        ]);

        // Verify it's only in demo_votes table
        $demoVote = DB::table('demo_votes')->where('id', $demoVoteId)->first();
        $this->assertNotNull($demoVote);

        // Verify it doesn't appear in votes table (check by ID)
        $realVote = DB::table('votes')->where('id', $demoVoteId)->first();
        $this->assertNull($realVote);
    }

    /** @test */
    public function demo_vote_scope_current_session_filters()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        // Create a recent demo vote (within last day) using raw insert
        $recentVoteId = Str::uuid()->toString();
        $recentReceiptString = 'recent-' . Str::random(16);
        DB::insert('insert into demo_votes (id, organisation_id, election_id, receipt_hash, voted_at, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $recentVoteId,
            $org->id,
            $election->id,
            hash('sha256', $recentReceiptString . config('app.salt')),
            now(),
            now(),
            now(),
        ]);

        // Manually insert an old demo vote (more than 1 day ago)
        $oldVoteId = Str::uuid()->toString();
        $oldReceiptString = 'old-' . Str::random(16);
        DB::insert('insert into demo_votes (id, organisation_id, election_id, receipt_hash, voted_at, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $oldVoteId,
            $org->id,
            $election->id,
            hash('sha256', $oldReceiptString . config('app.salt')),
            now()->subDays(2),
            now()->subDays(2),
            now()->subDays(2),
        ]);

        $sessionVotes = DemoVote::currentSession()->get();

        $this->assertCount(1, $sessionVotes);
        $this->assertEquals($recentVoteId, $sessionVotes->first()->id);
    }
}
