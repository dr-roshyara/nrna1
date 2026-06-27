<?php

namespace Tests\Feature\Election;

use App\Models\Organisation;
use App\Models\Election;
use App\Domain\Election\Enum\VoterSourceStrategy;
use Tests\TestCase;

class VoterStrategyMigrationIntegrityTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_legacy_org_true_maps_correctly_via_from_organisation()
    {
        $org = Organisation::create([
            'name' => 'Full Membership Org',
            'slug' => 'full-membership-org',
            'uses_full_membership' => true,
        ]);

        $mode = VoterSourceStrategy::fromOrganisation($org);
        $this->assertEquals(VoterSourceStrategy::FullMembership, $mode);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_legacy_org_false_maps_correctly_via_from_organisation()
    {
        $org = Organisation::create([
            'name' => 'Election-Only Org',
            'slug' => 'election-only-org',
            'uses_full_membership' => false,
        ]);

        $mode = VoterSourceStrategy::fromOrganisation($org);
        $this->assertEquals(VoterSourceStrategy::ElectionOnly, $mode);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_legacy_election_null_snapshot_falls_back_to_org()
    {
        $org = Organisation::create([
            'name' => 'Test Org',
            'slug' => 'test-org',
            'uses_full_membership' => true,
        ]);

        $election = Election::create([
            'organisation_id' => $org->id,
            'name' => 'Legacy Election',
            'slug' => 'legacy-election',
            'type' => 'real',
            'voter_source_strategy' => null,
        ]);

        $mode = VoterSourceStrategy::fromElection($election);
        $this->assertEquals(VoterSourceStrategy::FullMembership, $mode);
    }
}
