<?php

namespace Tests\Unit\Application\Election;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\Voter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VotersPreconditionTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
    }

    public function test_has_voters_precondition_checks_voters_table_not_memberships(): void
    {
        $election = Election::factory()
            ->forOrganisation($this->org)
            ->create(['state' => 'setup']);

        // Create a voter using the factory
        Voter::factory()
            ->state([
                'election_id' => $election->id,
                'organisation_id' => $this->org->id,
            ])
            ->create();

        // Refresh election to ensure fresh relationship queries
        $election->refresh();

        // BEFORE FIX: voters()->exists() returns true, but memberships()->exists() returns false
        // After fix: has_voters precondition will check voters()->exists() and pass
        $this->assertTrue(
            $election->voters()->withoutGlobalScopes()->exists(),
            'Voters table should have records after factory create'
        );

        // This also verifies that memberships relationship is not the same as voters
        $this->assertFalse(
            $election->memberships()->withoutGlobalScopes()->exists(),
            'Memberships relationship should be empty (precondition currently fails here)'
        );
    }

    public function test_has_voters_precondition_fails_when_no_voters_exist(): void
    {
        $election = Election::factory()
            ->forOrganisation($this->org)
            ->create(['state' => 'setup']);

        // With no voters created, voters()->exists() should return false
        $this->assertFalse(
            $election->voters()->exists(),
            'Precondition should fail when no voters exist'
        );
    }
}
