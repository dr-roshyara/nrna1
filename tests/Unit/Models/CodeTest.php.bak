<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Code;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function code_belongs_to_user()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

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
        $user = User::find($userId);

        $code = Code::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user->id,
            'code1' => 'ABC123',
            'code2' => 'XYZ789',
        ]);

        $this->assertEquals($user->id, $code->user->id);
    }

    /** @test */
    public function code_belongs_to_election()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

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
        $user = User::find($userId);

        $code = Code::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user->id,
            'code1' => 'ABC123',
            'code2' => 'XYZ789',
        ]);

        $this->assertEquals($election->id, $code->election->id);
    }

    /** @test */
    public function code_belongs_to_organisation()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

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
        $user = User::find($userId);

        $code = Code::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user->id,
            'code1' => 'ABC123',
            'code2' => 'XYZ789',
        ]);

        $this->assertEquals($org->id, $code->organisation->id);
    }

    /** @test */
    public function code_first_use_marks_code1_used()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

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
        $user = User::find($userId);

        $code = Code::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user->id,
            'code1' => 'ABC123',
            'code2' => 'XYZ789',
            'is_code1_usable' => true,
        ]);

        // Use code1
        $code->useCode1();

        $this->assertFalse($code->refresh()->is_code1_usable);
        $this->assertNotNull($code->code1_used_at);
    }

    /** @test */
    public function code_second_use_marks_code2_used()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

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
        $user = User::find($userId);

        $code = Code::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user->id,
            'code1' => 'ABC123',
            'code2' => 'XYZ789',
            'is_code2_usable' => true,
        ]);

        // Use code2
        $code->useCode2();

        $this->assertFalse($code->refresh()->is_code2_usable);
        $this->assertNotNull($code->code2_used_at);
    }

    /** @test */
    public function code_prevents_third_use()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

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
        $user = User::find($userId);

        $code = Code::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user->id,
            'code1' => 'ABC123',
            'code2' => 'XYZ789',
        ]);

        // Use both codes
        $code->useCode1();
        $code->useCode2();

        // Verify both are now unusable
        $this->assertFalse($code->refresh()->is_code1_usable);
        $this->assertFalse($code->refresh()->is_code2_usable);
        $this->assertFalse($code->isUsable());
    }

    /** @test */
    public function code_scope_for_organisation_filters()
    {
        $org1 = Organisation::factory()->tenant()->create();
        $org2 = Organisation::factory()->tenant()->create();

        $election1 = Election::factory()->forOrganisation($org1)->create();
        $election2 = Election::factory()->forOrganisation($org2)->create();

        $user1Id = Str::uuid()->toString();
        $user2Id = Str::uuid()->toString();

        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $user1Id,
            $org1->id,
            'User 1',
            'user1@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);

        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $user2Id,
            $org2->id,
            'User 2',
            'user2@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);

        $user1 = User::find($user1Id);
        $user2 = User::find($user2Id);

        Code::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org1->id,
            'election_id' => $election1->id,
            'user_id' => $user1->id,
            'code1' => 'ORG1CODE1',
            'code2' => 'ORG1CODE2',
        ]);

        Code::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org2->id,
            'election_id' => $election2->id,
            'user_id' => $user2->id,
            'code1' => 'ORG2CODE1',
            'code2' => 'ORG2CODE2',
        ]);

        // Test scoping
        $org1_codes = Code::forOrganisation($org1->id)->get();
        $org2_codes = Code::forOrganisation($org2->id)->get();

        $this->assertCount(1, $org1_codes);
        $this->assertCount(1, $org2_codes);
    }

    /** @test */
    public function code_scope_for_election_filters()
    {
        $org = Organisation::factory()->tenant()->create();
        $election1 = Election::factory()->forOrganisation($org)->create();
        $election2 = Election::factory()->forOrganisation($org)->create();

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
        $user = User::find($userId);

        Code::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election1->id,
            'user_id' => $user->id,
            'code1' => 'ELEC1CODE1',
            'code2' => 'ELEC1CODE2',
        ]);

        Code::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election2->id,
            'user_id' => $user->id,
            'code1' => 'ELEC2CODE1',
            'code2' => 'ELEC2CODE2',
        ]);

        // Test scoping
        $elec1_codes = Code::forElection($election1->id)->get();
        $elec2_codes = Code::forElection($election2->id)->get();

        $this->assertCount(1, $elec1_codes);
        $this->assertCount(1, $elec2_codes);
    }

    /** @test */
    public function code_scope_unused_returns_available()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

        $user1Id = Str::uuid()->toString();
        $user2Id = Str::uuid()->toString();

        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $user1Id,
            $org->id,
            'Test User 1',
            'test1@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);

        DB::insert('insert into users (id, organisation_id, name, email, password, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [
            $user2Id,
            $org->id,
            'Test User 2',
            'test2@example.com',
            bcrypt('password'),
            now(),
            now(),
        ]);

        $user1 = User::find($user1Id);
        $user2 = User::find($user2Id);

        $unused_code = Code::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user1->id,
            'code1' => 'UNUSED1',
            'code2' => 'UNUSED2',
        ]);

        $used_code = Code::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user2->id,
            'code1' => 'USED1',
            'code2' => 'USED2',
        ]);
        $used_code->useCode1();
        $used_code->useCode2();

        // Test unused scope
        $available = Code::unused()->get();

        $this->assertCount(1, $available);
        $this->assertEquals($unused_code->id, $available->first()->id);
    }

    /** @test */
    public function code_tracks_usage_timestamps()
    {
        $org = Organisation::factory()->tenant()->create();
        $election = Election::factory()->forOrganisation($org)->create();

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
        $user = User::find($userId);

        $code = Code::create([
            'id' => Str::uuid()->toString(),
            'organisation_id' => $org->id,
            'election_id' => $election->id,
            'user_id' => $user->id,
            'code1' => 'TRACK1',
            'code2' => 'TRACK2',
        ]);

        // Initially timestamps are null
        $this->assertNull($code->code1_used_at);
        $this->assertNull($code->code2_used_at);

        // After use, timestamp is recorded
        $code->useCode1();
        $code->refresh();

        $this->assertNotNull($code->code1_used_at);
        $this->assertNull($code->code2_used_at);

        // After second use
        $code->useCode2();
        $code->refresh();

        $this->assertNotNull($code->code1_used_at);
        $this->assertNotNull($code->code2_used_at);
    }
}
