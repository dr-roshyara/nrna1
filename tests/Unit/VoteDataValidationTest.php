<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\VoteController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;

/**
 * TDD Unit Tests for Vote Data Validation Bug Fix
 *
 * Tests the validation layer that detects and rejects inconsistent vote data
 *
 * @group vote-validation
 * @group tdd
 */
class VoteDataValidationTest extends TestCase
{
    use RefreshDatabase;

    private VoteController $controller;
    private ReflectionClass $reflection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new VoteController();
        $this->reflection = new ReflectionClass(VoteController::class);

        // Set SELECT_ALL_REQUIRED to 'no' for most tests
        config(['app.select_all_required' => 'no']);
    }

    private function callPrivateMethod($methodName, array $parameters = [])
    {
        $method = $this->reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($this->controller, $parameters);
    }

    /**
     * @test
     * TDD RED: Should detect bug pattern in validation
     *
     * Scenario: Inconsistent data reaches validation
     * Expected: Validation error for no_vote=false with empty candidates
     */
    public function it_rejects_inconsistent_no_vote_false_with_empty_candidates()
    {
        // Arrange: Buggy vote data
        $buggyVoteData = [
            'national_selected_candidates' => [
                [
                    'post_id' => '2021_02',
                    'post_name' => 'National Delegate',
                    'required_number' => 3,
                    'no_vote' => false,      // ❌ Inconsistent
                    'candidates' => []       // ❌ Empty
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$buggyVoteData]);

        // Assert: Should have validation error
        $this->assertNotEmpty($errors, 'Should return validation errors for inconsistent data');
        $this->assertArrayHasKey('national_post_0', $errors);
        $this->assertStringContainsString('Invalid selection', $errors['national_post_0']);
    }

    /**
     * @test
     *
     * Scenario: Valid skip selection
     * Expected: No validation errors
     */
    public function it_accepts_valid_no_vote_true_with_empty_candidates()
    {
        // Arrange: Valid skip
        $validSkipData = [
            'national_selected_candidates' => [
                [
                    'post_id' => '2021_01',
                    'post_name' => 'President',
                    'required_number' => 1,
                    'no_vote' => true,       // ✅ Consistent
                    'candidates' => []       // ✅ Empty is OK
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$validSkipData]);

        // Assert: Should have no errors (but might warn about no selections overall)
        $this->assertArrayNotHasKey('national_post_0', $errors,
            'Valid skip should not have field-specific error');
    }

    /**
     * @test
     *
     * Scenario: Valid vote with candidates
     * Expected: No validation errors
     */
    public function it_accepts_valid_vote_with_candidates()
    {
        // Arrange: Valid vote
        $validVoteData = [
            'national_selected_candidates' => [
                [
                    'post_id' => '2021_03',
                    'post_name' => 'Vice President',
                    'required_number' => 1,
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => 123, 'name' => 'John Doe']
                    ]
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$validVoteData]);

        // Assert
        $this->assertArrayNotHasKey('national_post_0', $errors);
    }

    /**
     * @test
     *
     * Scenario: Validate regional selections with bug pattern
     * Expected: Should detect inconsistency in regional posts too
     */
    public function it_rejects_regional_selections_with_bug_pattern()
    {
        // Arrange
        $voteDataWithRegionalBug = [
            'national_selected_candidates' => [],
            'regional_selected_candidates' => [
                [
                    'post_id' => '2021_10',
                    'post_name' => 'Regional Chair',
                    'required_number' => 1,
                    'no_vote' => false,
                    'candidates' => []
                ]
            ]
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$voteDataWithRegionalBug]);

        // Assert
        $this->assertArrayHasKey('regional_post_0', $errors);
        $this->assertStringContainsString('Invalid selection', $errors['regional_post_0']);
        $this->assertStringContainsString('Regional Chair', $errors['regional_post_0']);
    }

    /**
     * @test
     *
     * Scenario: Too many candidates selected
     * Expected: Validation error for exceeding limit
     */
    public function it_rejects_too_many_candidates()
    {
        // Arrange
        $tooManyCandidates = [
            'national_selected_candidates' => [
                [
                    'post_id' => '2021_04',
                    'post_name' => 'Secretary',
                    'required_number' => 1,
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => 1],
                        ['candidacy_id' => 2],  // Too many!
                    ]
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$tooManyCandidates]);

        // Assert
        $this->assertArrayHasKey('national_post_0', $errors);
        $this->assertStringContainsString('Too many', $errors['national_post_0']);
    }

    /**
     * @test
     *
     * Scenario: SELECT_ALL_REQUIRED mode - exact number required
     * Expected: Error if not exactly required_number candidates
     */
    public function it_validates_exact_count_when_select_all_required()
    {
        // Arrange
        config(['app.select_all_required' => 'yes']);

        $partialSelection = [
            'national_selected_candidates' => [
                [
                    'post_id' => '2021_05',
                    'post_name' => 'Board Member',
                    'required_number' => 3,
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => 1],
                        ['candidacy_id' => 2],
                        // Missing one candidate (need 3, have 2)
                    ]
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$partialSelection]);

        // Assert
        $this->assertArrayHasKey('national_post_0', $errors);
        $this->assertStringContainsString('exactly 3', $errors['national_post_0']);
    }

    /**
     * @test
     *
     * Scenario: Mixed valid and invalid selections
     * Expected: Errors only for invalid ones
     */
    public function it_validates_mixed_selections_correctly()
    {
        // Arrange
        $mixedData = [
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
                    'post_name' => 'VP',
                    'no_vote' => false,
                    'candidates' => []
                ],
                // Valid skip
                [
                    'post_id' => '2021_03',
                    'post_name' => 'Secretary',
                    'no_vote' => true,
                    'candidates' => []
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$mixedData]);

        // Assert
        $this->assertArrayNotHasKey('national_post_0', $errors, 'Valid vote should pass');
        $this->assertArrayHasKey('national_post_1', $errors, 'Bug pattern should fail');
        $this->assertArrayNotHasKey('national_post_2', $errors, 'Valid skip should pass');
    }

    /**
     * @test
     *
     * Scenario: No selections made at all
     * Expected: General error about needing at least one selection
     */
    public function it_requires_at_least_one_selection()
    {
        // Arrange: All positions are null/empty
        $noSelections = [
            'national_selected_candidates' => [null, null],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$noSelections]);

        // Assert
        $this->assertArrayHasKey('no_selections', $errors);
        $this->assertStringContainsString('at least one selection', $errors['no_selections']);
    }

    /**
     * @test
     *
     * Scenario: All positions skipped (no_vote=true)
     * Expected: Should be valid - user can skip all
     */
    public function it_accepts_all_positions_skipped()
    {
        // Arrange
        $allSkipped = [
            'national_selected_candidates' => [
                [
                    'post_id' => '2021_01',
                    'post_name' => 'President',
                    'no_vote' => true,
                    'candidates' => []
                ],
                [
                    'post_id' => '2021_02',
                    'post_name' => 'VP',
                    'no_vote' => true,
                    'candidates' => []
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$allSkipped]);

        // Assert: Should only have general no_selections warning, not field errors
        $this->assertArrayNotHasKey('national_post_0', $errors);
        $this->assertArrayNotHasKey('national_post_1', $errors);
    }

    /**
     * @test
     *
     * Scenario: Complex real-world vote scenario
     * Expected: Proper validation for all edge cases
     */
    public function it_handles_complex_real_world_scenario()
    {
        // Arrange
        config(['app.select_all_required' => 'no']);

        $complexVote = [
            'national_selected_candidates' => [
                // Position 1: Valid single selection
                [
                    'post_id' => '2021_01',
                    'post_name' => 'President',
                    'required_number' => 1,
                    'no_vote' => false,
                    'candidates' => [['candidacy_id' => 100]]
                ],
                // Position 2: Valid multi-selection (2 of 3)
                [
                    'post_id' => '2021_02',
                    'post_name' => 'Board Members',
                    'required_number' => 3,
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => 200],
                        ['candidacy_id' => 201]
                    ]
                ],
                // Position 3: Valid skip
                [
                    'post_id' => '2021_03',
                    'post_name' => 'Secretary',
                    'required_number' => 1,
                    'no_vote' => true,
                    'candidates' => []
                ],
                // Position 4: BUG PATTERN - should fail
                [
                    'post_id' => '2021_04',
                    'post_name' => 'Treasurer',
                    'required_number' => 1,
                    'no_vote' => false,
                    'candidates' => []
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$complexVote]);

        // Assert
        $this->assertArrayNotHasKey('national_post_0', $errors, 'Position 1 should be valid');
        $this->assertArrayNotHasKey('national_post_1', $errors, 'Position 2 should be valid (partial ok when not strict)');
        $this->assertArrayNotHasKey('national_post_2', $errors, 'Position 3 skip should be valid');
        $this->assertArrayHasKey('national_post_3', $errors, 'Position 4 bug pattern should fail');
    }

    /**
     * @test
     *
     * Scenario: Test with actual production bug data
     * Expected: Should fail validation
     */
    public function it_catches_production_bug_in_validation()
    {
        // Arrange: Actual production data that was saved incorrectly
        $productionBug = [
            'national_selected_candidates' => [
                [
                    'no_vote' => false,
                    'post_id' => '2021_02',
                    'post_name' => 'National Deligate',
                    'candidates' => [],
                    'required_number' => 3
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$productionBug]);

        // Assert: MUST catch this bug
        $this->assertArrayHasKey('national_post_0', $errors,
            'Production bug pattern must be caught by validation');

        $this->assertStringContainsString('National Deligate', $errors['national_post_0']);
        $this->assertStringContainsString('Invalid selection', $errors['national_post_0']);
    }
}
