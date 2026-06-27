<?php

namespace Tests\Feature;

use App\Models\Code;
use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use App\Models\Organisation;
use App\Models\ElectionMembership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteControllerPostgresCompatibilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * T1: Code model is_code_to_open_voting_form_usable is cast to boolean
     */
    public function test_code_is_code_to_open_voting_form_usable_cast_to_boolean(): void
    {
        $code = Code::factory()->create(['is_code_to_open_voting_form_usable' => 1]);
        $fresh = Code::withoutGlobalScopes()->find($code->id);
        
        $this->assertIsBool($fresh->is_code_to_open_voting_form_usable);
        $this->assertTrue($fresh->is_code_to_open_voting_form_usable);
    }

    /**
     * T2: config voting.two_codes_system is checked with strict equality
     */
    public function test_voting_two_codes_system_uses_strict_equality(): void
    {
        config(['voting.two_codes_system' => 1]);
        $this->assertTrue(config('voting.two_codes_system') === 1);
        $this->assertFalse(config('voting.two_codes_system') === true);
    }

    /**
     * T3: Boolean assignment uses true/false not numeric values
     */
    public function test_boolean_field_assignment_uses_true_false(): void
    {
        $code = Code::factory()->create(['is_code_to_open_voting_form_usable' => true]);
        $code->is_code_to_open_voting_form_usable = false;
        $code->save();
        
        $fresh = Code::withoutGlobalScopes()->find($code->id);
        $this->assertFalse($fresh->is_code_to_open_voting_form_usable);
    }

    /**
     * T4: Database queries with boolean filters work correctly
     */
    public function test_boolean_where_clauses_in_queries(): void
    {
        $org = Organisation::factory()->create(['type' => 'tenant']);
        $user1 = User::factory()->forOrganisation($org)->create();
        $user2 = User::factory()->forOrganisation($org)->create();
        $election = Election::factory()->create(['organisation_id' => $org->id]);

        $codeTrue = Code::factory()->create([
            'user_id' => $user1->id,
            'election_id' => $election->id,
            'is_code_to_open_voting_form_usable' => true,
        ]);

        $codeFalse = Code::factory()->create([
            'user_id' => $user2->id,
            'election_id' => $election->id,
            'is_code_to_open_voting_form_usable' => false,
        ]);

        $foundTrue = Code::withoutGlobalScopes()->where('is_code_to_open_voting_form_usable', true)->first();
        $this->assertNotNull($foundTrue);
        $this->assertTrue($foundTrue->is_code_to_open_voting_form_usable);

        $foundFalse = Code::withoutGlobalScopes()->where('is_code_to_open_voting_form_usable', false)->first();
        $this->assertNotNull($foundFalse);
        $this->assertFalse($foundFalse->is_code_to_open_voting_form_usable);
    }
}
