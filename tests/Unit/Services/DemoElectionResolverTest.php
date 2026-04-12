<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Services\DemoElectionResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemoElectionResolverTest extends TestCase
{
    use RefreshDatabase;

    private DemoElectionResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = new DemoElectionResolver();
    }

    /**
     * @test
     * Scenario: User has organisation + org-specific demo exists
     * Expected: Returns org-specific demo (not platform demo)
     */
    public function returns_org_specific_demo_when_user_has_org_and_org_demo_exists()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        $orgDemo = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $platformDemo = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => null
        ]);

        // Act
        $result = $this->resolver->getDemoElectionForUser($user);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($orgDemo->id, $result->id);
        $this->assertEquals(5, $result->organisation_id);
    }

    /**
     * @test
     * Scenario: User has organisation + NO org-specific demo + platform demo exists
     * Expected: Falls back to platform demo
     */
    public function returns_platform_demo_when_user_has_org_but_no_org_specific_demo()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        $platformDemo = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => null
        ]);

        // Act
        $result = $this->resolver->getDemoElectionForUser($user);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($platformDemo->id, $result->id);
        $this->assertNull($result->organisation_id);
    }

    /**
     * @test
     * Scenario: User has organisation + NO demos at all
     * Expected: Returns null
     */
    public function returns_null_when_user_has_org_but_no_demos_exist()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        // Act
        $result = $this->resolver->getDemoElectionForUser($user);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @test
     * Scenario: Default user (org_id = null) + platform demo exists
     * Expected: Returns platform demo
     */
    public function returns_platform_demo_for_default_user_when_platform_demo_exists()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => null]);

        $platformDemo = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => null
        ]);

        // Act
        $result = $this->resolver->getDemoElectionForUser($user);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($platformDemo->id, $result->id);
        $this->assertNull($result->organisation_id);
    }

    /**
     * @test
     * Scenario: Default user (org_id = null) + NO platform demo
     * Expected: Returns null (should NOT return org-specific demos)
     */
    public function returns_null_for_default_user_when_no_platform_demo_exists()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => null]);

        // Create a demo with org_id (should NOT be returned)
        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 1
        ]);

        // Act
        $result = $this->resolver->getDemoElectionForUser($user);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @test
     * Scenario: Only non-demo elections exist (type = 'real')
     * Expected: Returns null (ignores real elections)
     */
    public function ignores_non_demo_elections()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        // Create a REAL election with same org_id (should be ignored)
        Election::factory()->create([
            'type' => 'real',
            'organisation_id' => 5
        ]);

        // Act
        $result = $this->resolver->getDemoElectionForUser($user);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @test
     * Scenario: Multiple demos exist, user has org
     * Expected: Returns org-specific demo (priority 1, not platform demo)
     */
    public function prioritizes_org_specific_demo_over_platform_demo()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 7]);

        $platformDemo = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => null
        ]);

        $orgDemo = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 7
        ]);

        // Act
        $result = $this->resolver->getDemoElectionForUser($user);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($orgDemo->id, $result->id);
        $this->assertNotEquals($platformDemo->id, $result->id);
    }

    // ==================== VALIDATION TESTS ====================

    /**
     * @test
     * Scenario: Election is demo + matches user's org
     * Expected: Validation returns true
     */
    public function validation_returns_true_for_valid_org_election()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);
        $election = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        // Act
        $result = $this->resolver->isElectionValidForUser($user, $election);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     * Scenario: Election is demo + org_id = null + user has org
     * Expected: Validation returns true (platform demo is valid for all users)
     */
    public function validation_returns_true_for_platform_demo_and_org_user()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);
        $election = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => null
        ]);

        // Act
        $result = $this->resolver->isElectionValidForUser($user, $election);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     * Scenario: Election is demo + org_id = null + user org_id = null
     * Expected: Validation returns true
     */
    public function validation_returns_true_for_platform_demo_and_default_user()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => null]);
        $election = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => null
        ]);

        // Act
        $result = $this->resolver->isElectionValidForUser($user, $election);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     * Scenario: Election is org-specific demo + user has different org
     * Expected: Validation returns false (cross-org access denied)
     */
    public function validation_returns_false_for_wrong_org_demo()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);
        $election = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 10 // Different org
        ]);

        // Act
        $result = $this->resolver->isElectionValidForUser($user, $election);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @test
     * Scenario: Election is real (not demo)
     * Expected: Validation returns false
     */
    public function validation_returns_false_for_non_demo_elections()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);
        $election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => 5
        ]);

        // Act
        $result = $this->resolver->isElectionValidForUser($user, $election);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @test
     * Scenario: Default user tries to access org-specific demo
     * Expected: Validation returns false (security boundary)
     */
    public function validation_returns_false_when_default_user_accesses_org_demo()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => null]);
        $election = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        // Act
        $result = $this->resolver->isElectionValidForUser($user, $election);

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @test
     * Scenario: Multiple elections, find correct one for user
     * Expected: Resolver returns exactly the right election
     */
    public function correctly_identifies_election_among_multiple_demos()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 3]);

        // Create multiple demos
        Election::factory()->create(['type' => 'demo', 'organisation_id' => null]);
        Election::factory()->create(['type' => 'demo', 'organisation_id' => 1]);
        Election::factory()->create(['type' => 'demo', 'organisation_id' => 2]);

        $correctDemo = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 3
        ]);

        // Act
        $result = $this->resolver->getDemoElectionForUser($user);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($correctDemo->id, $result->id);
    }
}
