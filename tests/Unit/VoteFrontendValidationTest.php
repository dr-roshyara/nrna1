<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\VoteController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;

/**
 * TDD RED Phase: Validation should catch bug pattern
 *
 * These tests should FAIL initially, proving the validation gap exists
 *
 * @group tdd-red
 * @group validation-enhancement
 */
class VoteFrontendValidationTest extends TestCase
{
    use RefreshDatabase;

    private VoteController $controller;
    private ReflectionClass $reflection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new VoteController();
        $this->reflection = new ReflectionClass(VoteController::class);
        config(['app.select_all_required' => 'no']);
    }

    private function callPrivateMethod($methodName, array $parameters = [])
    {
        $method = $this->reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($this->controller, $parameters);
    }

    /**
     * @test RED PHASE
     * TDD: This should FAIL initially
     *
     * The current validation only checks "too many" candidates
     * It does NOT check for empty candidates with no_vote=false
     */
    public function test_red_validation_should_reject_empty_candidates_without_no_vote()
    {
        // Arrange: Bug pattern
        $buggyVoteData = [
            'national_selected_candidates' => [
                [
                    'post_id' => '2021_01',
                    'post_name' => 'President',
                    'required_number' => 1,
                    'no_vote' => false,      // Says user is voting
                    'candidates' => []       // But no candidates!
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act: Validate
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$buggyVoteData]);

        // Assert: SHOULD catch this bug
        $this->assertArrayHasKey('national_post_0', $errors,
            'Validation MUST reject empty candidates when no_vote is false');

        // Check error message contains key terms
        $errorMessage = strtolower($errors['national_post_0']);
        $this->assertStringContainsString('select', $errorMessage);
        $this->assertStringContainsString('skip', $errorMessage);
    }

    /**
     * @test RED PHASE
     * TDD: Regional validation should also catch bug
     */
    public function test_red_validation_should_reject_empty_regional_candidates()
    {
        // Arrange
        $buggyVoteData = [
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
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$buggyVoteData]);

        // Assert
        $this->assertArrayHasKey('regional_post_0', $errors,
            'Regional validation MUST also catch bug pattern');
    }

    /**
     * @test
     * TDD: Valid data should still pass
     */
    public function test_green_validation_accepts_valid_skip()
    {
        // Arrange: Valid skip
        $validSkip = [
            'national_selected_candidates' => [
                [
                    'post_id' => '2021_01',
                    'post_name' => 'President',
                    'no_vote' => true,
                    'candidates' => []
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$validSkip]);

        // Assert: Should NOT have field-specific error
        $this->assertArrayNotHasKey('national_post_0', $errors);
    }

    /**
     * @test
     * TDD: Valid vote should still pass
     */
    public function test_green_validation_accepts_valid_vote()
    {
        // Arrange: Valid vote
        $validVote = [
            'national_selected_candidates' => [
                [
                    'post_id' => '2021_01',
                    'post_name' => 'President',
                    'no_vote' => false,
                    'candidates' => [
                        ['candidacy_id' => 123]
                    ]
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$validVote]);

        // Assert
        $this->assertArrayNotHasKey('national_post_0', $errors);
    }

    /**
     * @test RED PHASE
     * TDD: Should validate each position independently
     */
    public function test_red_validation_catches_bug_in_mixed_selections()
    {
        // Arrange
        $mixedData = [
            'national_selected_candidates' => [
                // Valid
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
                    'candidates' => []  // ❌
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
        $this->assertArrayNotHasKey('national_post_0', $errors, 'First position should be valid');
        $this->assertArrayHasKey('national_post_1', $errors, 'Second position MUST have error');
        $this->assertArrayNotHasKey('national_post_2', $errors, 'Third position should be valid');
    }

    /**
     * @test RED PHASE
     * TDD: Exact production bug scenario
     */
    public function test_red_validation_catches_production_bug_pattern()
    {
        // Arrange: Exact production data
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

        // Assert: MUST catch this!
        $this->assertArrayHasKey('national_post_0', $errors,
            'Production bug MUST be caught by validation');

        $this->assertStringContainsString('National Deligate', $errors['national_post_0']);
    }

    /**
     * @test
     * TDD: Validation with SELECT_ALL_REQUIRED should still work
     */
    public function test_green_strict_mode_with_zero_candidates_is_caught()
    {
        // Arrange
        config(['app.select_all_required' => 'yes']);

        $strictModeBug = [
            'national_selected_candidates' => [
                [
                    'post_id' => '2021_01',
                    'post_name' => 'President',
                    'required_number' => 1,
                    'no_vote' => false,
                    'candidates' => []
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$strictModeBug]);

        // Assert: Should be caught by existing strict validation OR new validation
        $this->assertArrayHasKey('national_post_0', $errors);
    }

    /**
     * @test RED PHASE
     * TDD: Error message should be clear
     */
    public function test_red_validation_provides_helpful_error_message()
    {
        // Arrange
        $bugData = [
            'national_selected_candidates' => [
                [
                    'post_id' => '2021_01',
                    'post_name' => 'President',
                    'no_vote' => false,
                    'candidates' => []
                ]
            ],
            'regional_selected_candidates' => []
        ];

        // Act
        $errors = $this->callPrivateMethod('validate_candidate_selections', [$bugData]);

        // Assert
        $this->assertArrayHasKey('national_post_0', $errors);

        $errorMessage = strtolower($errors['national_post_0']);

        // Message should mention:
        $this->assertStringContainsString('president', $errorMessage, 'Should mention post name');
        $this->assertStringContainsString('select', $errorMessage, 'Should mention selecting');
        $this->assertStringContainsString('skip', $errorMessage, 'Should mention skip option');
    }
}
