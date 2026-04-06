<?php

namespace Tests\Unit\Models\Demo;

use Tests\TestCase;
use App\Models\Code;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoCodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function demo_code_extends_code_model()
    {
        $code = new \App\Models\Demo\DemoCode();
        $this->assertInstanceOf(Code::class, $code);
    }

    /** @test */
    public function demo_code_uses_demo_codes_table()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        // Use raw insert to test table isolation since demo_codes doesn't have soft deletes
        DB::insert('insert into demo_codes (id, organisation_id, election_id, code_to_open_voting_form, code_to_save_vote, is_code_to_open_voting_form_usable, can_vote_now, has_voted, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $demoCodeId = Str::uuid()->toString(),
            $org->id,
            $election->id,
            'DEMO-ABC-123',
            'DEMO-XYZ-789',
            true,
            false,
            false,
            now(),
            now(),
        ]);

        // Verify it's in demo_codes table
        $demoCode = DB::table('demo_codes')->where('id', $demoCodeId)->first();
        $this->assertNotNull($demoCode);

        // Real codes table should not have this record (codes table still uses old column names)
        $realCode = DB::table('codes')->where('code1', 'DEMO-ABC-123')->first();
        $this->assertNull($realCode);
    }

    /** @test */
    public function demo_code_belongs_to_election()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        $demoCode = \App\Models\Demo\DemoCode::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'code_to_open_voting_form' => 'DEMO-1',
            'code_to_save_vote' => 'DEMO-2',
        ]);

        $this->assertEquals($election->id, $demoCode->election->id);
    }

    /** @test */
    public function demo_code_tracks_usage()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        $demoCode = \App\Models\Demo\DemoCode::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'code_to_open_voting_form' => 'DEMO-USE-1',
            'code_to_save_vote' => 'DEMO-USE-2',
            'is_code_to_open_voting_form_usable' => true,
        ]);

        $this->assertTrue($demoCode->is_code_to_open_voting_form_usable);
        $this->assertNull($demoCode->code_to_open_voting_form_used_at);

        // Use code to open voting form
        $demoCode->markOpenVotingFormCodeAsUsed();
        $demoCode->refresh();

        $this->assertFalse($demoCode->is_code_to_open_voting_form_usable);
        $this->assertNotNull($demoCode->code_to_open_voting_form_used_at);
    }

    /** @test */
    public function demo_code_is_demo_returns_true()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        $demoCode = \App\Models\Demo\DemoCode::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'code_to_open_voting_form' => 'DEMO-CHECK',
            'code_to_save_vote' => 'DEMO-CHECK-2',
        ]);

        $this->assertTrue($demoCode->isDemo());
    }

    /** @test */
    public function demo_code_is_real_returns_false()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        $demoCode = \App\Models\Demo\DemoCode::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'code_to_open_voting_form' => 'DEMO-REAL-CHECK',
            'code_to_save_vote' => 'DEMO-REAL-CHECK-2',
        ]);

        $this->assertFalse($demoCode->isReal());
    }

    /** @test */
    public function demo_code_scope_for_demo_election()
    {
        $org = Organisation::factory()->tenant()->create();
        $demoElection = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);
        $realElection = Election::factory()->forOrganisation($org)->create(['type' => 'real']);

        session(['current_organisation_id' => $org->id]);

        // Create a test user for the real code (codes table requires user_id)
        $userId = Str::uuid()->toString();
        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $userId,
            $org->id,
            'Test User',
            'test@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);

        // Insert demo code using raw insert (demo_codes doesn't have soft deletes)
        $demoDemoCodeId = Str::uuid()->toString();
        DB::insert('insert into demo_codes (id, organisation_id, election_id, code_to_open_voting_form, code_to_save_vote, is_code_to_open_voting_form_usable, can_vote_now, has_voted, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $demoDemoCodeId,
            $org->id,
            $demoElection->id,
            'DEMO-ONLY',
            'DEMO-ONLY-2',
            true,
            false,
            false,
            now(),
            now(),
        ]);

        // Insert real code using raw insert (codes table requires user_id)
        $realCodeId = Str::uuid()->toString();
        DB::insert('insert into codes (id, organisation_id, election_id, user_id, code_to_open_voting_form, code_to_save_vote, is_code_to_open_voting_form_usable, can_vote_now, has_voted, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $realCodeId,
            $org->id,
            $realElection->id,
            $userId,
            'REAL-ONLY',
            'REAL-ONLY-2',
            true,
            false,
            false,
            now(),
            now(),
        ]);

        // Demo codes should only be found in DemoCode table
        $demoCodes = \App\Models\Demo\DemoCode::forElection($demoElection)->get();
        $this->assertCount(1, $demoCodes);
        $this->assertEquals($demoDemoCodeId, $demoCodes->first()->id);

        // Real codes should not appear in demo codes
        $realCodes = Code::forElection($realElection)->get();
        $this->assertCount(1, $realCodes);
        $this->assertEquals($realCodeId, $realCodes->first()->id);
    }

    /** @test */
    public function demo_code_can_be_reset()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        $demoCode = \App\Models\Demo\DemoCode::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'code_to_open_voting_form' => 'DEMO-RESET',
            'code_to_save_vote' => 'DEMO-RESET-2',
            'is_code_to_open_voting_form_usable' => true,
            'code_to_open_voting_form_used_at' => now(),
        ]);

        // Use the code
        $demoCode->markOpenVotingFormCodeAsUsed();
        $this->assertFalse($demoCode->refresh()->is_code_to_open_voting_form_usable);

        // Reset the code (for demo retesting)
        $demoCode->update([
            'is_code_to_open_voting_form_usable' => true,
            'code_to_open_voting_form_used_at' => null,
        ]);

        $this->assertTrue($demoCode->refresh()->is_code_to_open_voting_form_usable);
        $this->assertNull($demoCode->code_to_open_voting_form_used_at);
    }

    /** @test */
    public function demo_code_isolation_from_real_codes()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create(['type' => 'demo']);

        session(['current_organisation_id' => $org->id]);

        // Insert demo code using raw insert (demo_codes doesn't have soft deletes)
        $demoDemoCodeId = Str::uuid()->toString();
        DB::insert('insert into demo_codes (id, organisation_id, election_id, code_to_open_voting_form, code_to_save_vote, is_code_to_open_voting_form_usable, can_vote_now, has_voted, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $demoDemoCodeId,
            $org->id,
            $election->id,
            'DEMO-ISOLATED',
            'DEMO-ISOLATED-2',
            true,
            false,
            false,
            now(),
            now(),
        ]);

        // Code should only exist in demo_codes table
        $demoCode = DB::table('demo_codes')->where('id', $demoDemoCodeId)->first();
        $this->assertNotNull($demoCode);

        // Verify it doesn't appear when querying real Code model (codes table still uses old column names)
        $realCodeQuery = Code::where('code1', 'DEMO-ISOLATED')->first();
        $this->assertNull($realCodeQuery);
    }
}
