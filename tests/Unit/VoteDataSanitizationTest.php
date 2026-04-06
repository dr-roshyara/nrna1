<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\VoteController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;

/**
 * TDD Unit Tests for Vote Data Sanitization Bug Fix
 *
 * Bug: {no_vote: false, candidates: []} data inconsistency
 * Fix: Sanitize data to ensure no_vote=true when candidates is empty
 *
 * @group vote-sanitization
 * @group tdd
 */
class VoteDataSanitizationTest extends TestCase
{
    use RefreshDatabase;

    private VoteController $controller;
    private ReflectionClass $reflection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new VoteController();
        $this->reflection = new ReflectionClass(VoteController::class);
    }

    /**
     * Helper method to call private methods using reflection
     */
    private function callPrivateMethod($methodName, array $parameters = [])
    {
        $method = $this->reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($this->controller, $parameters);
    }

    /**
     * @test
     * TDD RED: Test fails before fix is implemented
     *
     * Scenario: User clicks "Skip" then unchecks it without selecting candidates
     * Expected: no_vote should be true (not false with empty candidates)
     */
    public function it_sanitizes_selection_with_no_vote_false_and_empty_candidates()
    {
        // Arrange: Create the bug pattern data
        $buggySelection = [
            'post_id' => '2021_02',
            'post_name' => 'National Delegate',
            'required_number' => 3,
            'no_vote' => false,      // ❌ Bug: Says user voted
            'candidates' => []       // ❌ But no candidates!
        ];

        // Act: Call sanitize_selection method
        $sanitizedSelection = $this->callPrivateMethod('sanitize_selection', [$buggySelection]);

        // Assert: Should fix no_vote to true
        $this->assertTrue($sanitizedSelection['no_vote'],
            'no_vote should be true when candidates array is empty');

        $this->assertEmpty($sanitizedSelection['candidates'],
            'candidates array should remain empty');

        $this->assertEquals('2021_02', $sanitizedSelection['post_id']);
        $this->assertEquals('National Delegate', $sanitizedSelection['post_name']);
    }

    /**
     * @test
     *
     * Scenario: Valid skip - user explicitly selects no_vote
     * Expected: Data should remain unchanged (already correct)
     */
    public function it_does_not_modify_valid_no_vote_selection()
    {
        // Arrange: Valid skip data
        $validSkip = [
            'post_id' => '2021_01',
            'post_name' => 'President',
            'required_number' => 1,
            'no_vote' => true,        // ✅ Correct
            'candidates' => []        // ✅ Empty is fine with no_vote=true
        ];

        // Act
        $sanitizedSelection = $this->callPrivateMethod('sanitize_selection', [$validSkip]);

        // Assert: Should remain unchanged
        $this->assertTrue($sanitizedSelection['no_vote']);
        $this->assertEmpty($sanitizedSelection['candidates']);
        $this->assertEquals($validSkip, $sanitizedSelection);
    }

    /**
     * @test
     *
     * Scenario: Valid vote - user selects candidates
     * Expected: Data should remain unchanged (already correct)
     */
    public function it_does_not_modify_valid_vote_with_candidates()
    {
        // Arrange: Valid vote with candidates
        $validVote = [
            'post_id' => '2021_03',
            'post_name' => 'Vice President',
            'required_number' => 1,
            'no_vote' => false,      // ✅ Correct
            'candidates' => [        // ✅ Has candidates
                [
                    'candidacy_id' => 123,
                    'name' => 'John Doe',
                    'user_id' => 456
                ]
            ]
        ];

        // Act
        $sanitizedSelection = $this->callPrivateMethod('sanitize_selection', [$validVote]);

        // Assert: Should remain unchanged
        $this->assertFalse($sanitizedSelection['no_vote']);
        $this->assertCount(1, $sanitizedSelection['candidates']);
        $this->assertEquals($validVote, $sanitizedSelection);
    }

    /**
     * @test
     *
     * Scenario: Multiple candidates selected
     * Expected: no_vote should remain false
     */
    public function it_preserves_no_vote_false_when_multiple_candidates_exist()
    {
        // Arrange
        $multipleSelection = [
            'post_id' => '2021_04',
            'post_name' => 'Secretary',
            'required_number' => 3,
            'no_vote' => false,
            'candidates' => [
                ['candidacy_id' => 1, 'name' => 'Alice'],
                ['candidacy_id' => 2, 'name' => 'Bob'],
                ['candidacy_id' => 3, 'name' => 'Charlie'],
            ]
        ];

        // Act
        $sanitizedSelection = $this->callPrivateMethod('sanitize_selection', [$multipleSelection]);

        // Assert
        $this->assertFalse($sanitizedSelection['no_vote']);
        $this->assertCount(3, $sanitizedSelection['candidates']);
    }

    /**
     * @test
     *
     * Scenario: Sanitize full vote data with national and regional selections
     * Expected: All selections should be sanitized
     */
    public function it_sanitizes_complete_vote_data_structure()
    {
        // Arrange: Mix of valid and buggy data
        $voteData = [
            'user_id' => 19,
            'national_selected_candidates' => [
                // Valid vote
                [
                    'post_id' => '2021_01',
                    'post_name' => 'President',
                    'no_vote' => false,
                    'candidates' => [['candidacy_id' => 100]]
                ],
                // Bug pattern
                [
                    'post_id' => '2021_02',
                    'post_name' => 'National Delegate',
                    'no_vote' => false,     // ❌ Bug
                    'candidates' => []      // ❌ Empty
                ],
                // Valid skip
                [
                    'post_id' => '2021_03',
                    'post_name' => 'Secretary',
                    'no_vote' => true,
                    'candidates' => []
                ]
            ],
            'regional_selected_candidates' => [
                // Bug pattern in regional
                [
                    'post_id' => '2021_10',
                    'post_name' => 'Regional Chair',
                    'no_vote' => false,     // ❌ Bug
                    'candidates' => []      // ❌ Empty
                ]
            ],
            'agree_button' => true
        ];

        // Act
        $sanitizedData = $this->callPrivateMethod('sanitize_vote_data', [$voteData]);

        // Assert: Check national selections
        $this->assertFalse($sanitizedData['national_selected_candidates'][0]['no_vote'],
            'First selection should remain no_vote=false (has candidates)');

        $this->assertTrue($sanitizedData['national_selected_candidates'][1]['no_vote'],
            'Second selection should be fixed to no_vote=true (empty candidates)');

        $this->assertTrue($sanitizedData['national_selected_candidates'][2]['no_vote'],
            'Third selection should remain no_vote=true');

        // Assert: Check regional selections
        $this->assertTrue($sanitizedData['regional_selected_candidates'][0]['no_vote'],
            'Regional selection should be fixed to no_vote=true (empty candidates)');
    }

    /**
     * @test
     *
     * Scenario: Edge case - candidates is null instead of empty array
     * Expected: Should be treated as no_vote=true
     */
    public function it_handles_null_candidates_array()
    {
        // Arrange
        $selectionWithNull = [
            'post_id' => '2021_05',
            'post_name' => 'Treasurer',
            'required_number' => 1,
            'no_vote' => false,
            'candidates' => null    // null instead of []
        ];

        // Act
        $sanitizedSelection = $this->callPrivateMethod('sanitize_selection', [$selectionWithNull]);

        // Assert
        $this->assertTrue($sanitizedSelection['no_vote'],
            'no_vote should be true when candidates is null');
    }

    /**
     * @test
     *
     * Scenario: Edge case - missing candidates key
     * Expected: Should be treated as no_vote=true
     */
    public function it_handles_missing_candidates_key()
    {
        // Arrange
        $selectionMissingCandidates = [
            'post_id' => '2021_06',
            'post_name' => 'Auditor',
            'required_number' => 1,
            'no_vote' => false,
            // candidates key is missing entirely
        ];

        // Act
        $sanitizedSelection = $this->callPrivateMethod('sanitize_selection', [$selectionMissingCandidates]);

        // Assert
        $this->assertTrue($sanitizedSelection['no_vote'],
            'no_vote should be true when candidates key is missing');
    }

    /**
     * @test
     *
     * Scenario: Edge case - missing no_vote key
     * Expected: Should default to false, then check candidates
     */
    public function it_handles_missing_no_vote_key()
    {
        // Arrange: Missing no_vote but empty candidates
        $selectionMissingNoVote = [
            'post_id' => '2021_07',
            'post_name' => 'Coordinator',
            'required_number' => 1,
            // no_vote key is missing
            'candidates' => []
        ];

        // Act
        $sanitizedSelection = $this->callPrivateMethod('sanitize_selection', [$selectionMissingNoVote]);

        // Assert
        $this->assertTrue($sanitizedSelection['no_vote'],
            'no_vote should be set to true when missing and candidates is empty');
    }

    /**
     * @test
     *
     * Scenario: Sanitize null selections in vote data
     * Expected: Null selections should be skipped gracefully
     */
    public function it_skips_null_selections_in_vote_data()
    {
        // Arrange
        $voteDataWithNulls = [
            'national_selected_candidates' => [
                null,  // Null selection
                [
                    'post_id' => '2021_01',
                    'no_vote' => false,
                    'candidates' => []
                ],
                null   // Another null
            ],
            'regional_selected_candidates' => []
        ];

        // Act - Should not throw exception
        $sanitizedData = $this->callPrivateMethod('sanitize_vote_data', [$voteDataWithNulls]);

        // Assert: Should handle nulls gracefully
        $this->assertNull($sanitizedData['national_selected_candidates'][0]);
        $this->assertTrue($sanitizedData['national_selected_candidates'][1]['no_vote']);
        $this->assertNull($sanitizedData['national_selected_candidates'][2]);
    }

    /**
     * @test
     *
     * Scenario: Real-world bug reproduction
     * Expected: Exact bug pattern from production should be fixed
     */
    public function it_fixes_production_bug_pattern()
    {
        // Arrange: Exact data from production Vote ID 75
        $productionBugData = [
            'post_id' => '2021_02',
            'post_name' => 'National Deligate',  // Note: typo from production
            'required_number' => 3,
            'no_vote' => false,
            'candidates' => []
        ];

        // Act
        $fixed = $this->callPrivateMethod('sanitize_selection', [$productionBugData]);

        // Assert: Bug should be fixed
        $this->assertTrue($fixed['no_vote'],
            'Production bug pattern should be fixed to no_vote=true');

        $this->assertEmpty($fixed['candidates'],
            'Candidates should remain empty array');

        $this->assertEquals('National Deligate', $fixed['post_name'],
            'Other fields should be preserved');
    }

    /**
     * @test
     *
     * Scenario: Performance test - sanitize large vote data
     * Expected: Should handle 60 positions efficiently
     */
    public function it_efficiently_sanitizes_maximum_positions()
    {
        // Arrange: Create data for all 60 possible positions
        $nationalSelections = [];
        for ($i = 1; $i <= 30; $i++) {
            $nationalSelections[] = [
                'post_id' => "2021_{$i}",
                'post_name' => "Position {$i}",
                'required_number' => 1,
                'no_vote' => $i % 2 === 0 ? false : true,  // Alternate
                'candidates' => $i % 2 === 0 ? [] : [['candidacy_id' => $i]]
            ];
        }

        $regionalSelections = [];
        for ($i = 31; $i <= 60; $i++) {
            $regionalSelections[] = [
                'post_id' => "2021_{$i}",
                'post_name' => "Position {$i}",
                'required_number' => 1,
                'no_vote' => false,
                'candidates' => []  // All buggy
            ];
        }

        $largeVoteData = [
            'national_selected_candidates' => $nationalSelections,
            'regional_selected_candidates' => $regionalSelections
        ];

        // Act
        $startTime = microtime(true);
        $sanitized = $this->callPrivateMethod('sanitize_vote_data', [$largeVoteData]);
        $endTime = microtime(true);

        // Assert: Should complete quickly (< 100ms)
        $executionTime = ($endTime - $startTime) * 1000; // Convert to ms
        $this->assertLessThan(100, $executionTime,
            'Sanitization should complete in less than 100ms for 60 positions');

        // Assert: All buggy regional selections should be fixed
        foreach ($sanitized['regional_selected_candidates'] as $selection) {
            $this->assertTrue($selection['no_vote'],
                'All regional selections with empty candidates should have no_vote=true');
        }
    }
}
