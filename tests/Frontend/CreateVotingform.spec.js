/**
 * TDD Frontend Unit Tests for CreateVotingform.vue Bug Fix
 *
 * These tests require Jest + Vue Test Utils
 * Run with: npm test or yarn test
 *
 * @group frontend
 * @group tdd
 * @group vote-bug-fix
 */

import { mount } from '@vue/test-utils';
import CreateVotingform from '@/Pages/Vote/CreateVotingform.vue';

describe('CreateVotingform.vue - Vote Bug Fix', () => {
    let wrapper;

    // Mock props
    const mockPost = {
        post_id: '2021_01',
        name: 'President',
        nepali_name: 'अध्यक्ष',
        required_number: 1
    };

    const mockCandidates = [
        {
            candidacy_id: 100,
            user: {
                name: 'John Doe',
                user_id: 'user_100'
            },
            disabled: false
        },
        {
            candidacy_id: 101,
            user: {
                name: 'Jane Smith',
                user_id: 'user_101'
            },
            disabled: false
        }
    ];

    beforeEach(() => {
        wrapper = mount(CreateVotingform, {
            props: {
                post: mockPost,
                candidates: mockCandidates
            }
        });
    });

    afterEach(() => {
        wrapper.unmount();
    });

    /**
     * @test
     * TDD RED: Test that reproduces the bug scenario
     *
     * Scenario: User clicks "Skip" then unchecks it
     * Expected: Should emit {no_vote: true, candidates: []}
     * Bug: Was emitting {no_vote: false, candidates: []}
     */
    test('it emits no_vote true when skip is unchecked without selecting candidates', async () => {
        // Arrange: Initial state
        expect(wrapper.vm.noVoteSelected).toBe(false);
        expect(wrapper.vm.selected).toEqual([]);

        // Act: Click "Skip this position" checkbox
        const noVoteCheckbox = wrapper.find('input[name="no_vote_option"]');
        await noVoteCheckbox.setValue(true);

        // Verify skip is selected
        expect(wrapper.vm.noVoteSelected).toBe(true);

        // Get first emission (should be {no_vote: true, candidates: []})
        let emissions = wrapper.emitted('add_selected_candidates');
        expect(emissions).toHaveLength(1);
        expect(emissions[0][0].no_vote).toBe(true);
        expect(emissions[0][0].candidates).toEqual([]);

        // Act: UNCHECK "Skip" (change mind)
        await noVoteCheckbox.setValue(false);

        // Verify skip is unchecked
        expect(wrapper.vm.noVoteSelected).toBe(false);
        expect(wrapper.vm.selected).toEqual([]);  // Still no candidates selected

        // Assert: Should emit {no_vote: true, candidates: []} NOT {no_vote: false, candidates: []}
        emissions = wrapper.emitted('add_selected_candidates');
        expect(emissions).toHaveLength(2);  // Two emissions total

        const secondEmission = emissions[1][0];

        // 🐛 BUG FIX: This should be TRUE, not FALSE
        expect(secondEmission.no_vote).toBe(true);
        expect(secondEmission.candidates).toEqual([]);

        // Verify other fields are preserved
        expect(secondEmission.post_id).toBe('2021_01');
        expect(secondEmission.post_name).toBe('President');
        expect(secondEmission.required_number).toBe(1);
    });

    /**
     * @test
     *
     * Scenario: User clicks "Skip" checkbox
     * Expected: Should emit {no_vote: true, candidates: []}
     */
    test('it emits no_vote true when skip checkbox is clicked', async () => {
        // Act
        const noVoteCheckbox = wrapper.find('input[name="no_vote_option"]');
        await noVoteCheckbox.setValue(true);

        // Assert
        const emissions = wrapper.emitted('add_selected_candidates');
        expect(emissions).toHaveLength(1);
        expect(emissions[0][0].no_vote).toBe(true);
        expect(emissions[0][0].candidates).toEqual([]);
    });

    /**
     * @test
     *
     * Scenario: User selects a candidate
     * Expected: Should emit {no_vote: false, candidates: [...]}
     */
    test('it emits no_vote false when candidate is selected', async () => {
        // Act: Select first candidate
        const candidateCheckboxes = wrapper.findAll('input[type="checkbox"]').filter(
            input => input.attributes('name') === 'President'
        );

        await candidateCheckboxes[0].setValue(true);
        await wrapper.vm.$nextTick();

        // Assert
        const emissions = wrapper.emitted('add_selected_candidates');
        const latestEmission = emissions[emissions.length - 1][0];

        expect(latestEmission.no_vote).toBe(false);
        expect(latestEmission.candidates).toHaveLength(1);
        expect(latestEmission.candidates[0].candidacy_id).toBe(100);
    });

    /**
     * @test
     *
     * Scenario: User selects candidate then deselects it
     * Expected: Should emit {no_vote: true, candidates: []} (auto-fix)
     */
    test('it auto-fixes to no_vote true when all candidates are deselected', async () => {
        // Arrange: Select a candidate first
        const candidateCheckboxes = wrapper.findAll('input[type="checkbox"]').filter(
            input => input.attributes('name') === 'President'
        );

        await candidateCheckboxes[0].setValue(true);
        await wrapper.vm.$nextTick();

        // Verify candidate was selected
        let emissions = wrapper.emitted('add_selected_candidates');
        let latestEmission = emissions[emissions.length - 1][0];
        expect(latestEmission.no_vote).toBe(false);
        expect(latestEmission.candidates).toHaveLength(1);

        // Act: Deselect the candidate
        await candidateCheckboxes[0].setValue(false);
        await wrapper.vm.$nextTick();

        // Assert: Should auto-fix to no_vote=true
        emissions = wrapper.emitted('add_selected_candidates');
        latestEmission = emissions[emissions.length - 1][0];

        expect(latestEmission.no_vote).toBe(true);  // ✅ Fixed!
        expect(latestEmission.candidates).toEqual([]);
    });

    /**
     * @test
     *
     * Scenario: User selects skip, then unchecks and selects a candidate
     * Expected: Should emit {no_vote: false, candidates: [...]}
     */
    test('it allows voting after unchecking skip', async () => {
        // Arrange: Click skip first
        const noVoteCheckbox = wrapper.find('input[name="no_vote_option"]');
        await noVoteCheckbox.setValue(true);

        // Act: Uncheck skip
        await noVoteCheckbox.setValue(false);

        // Select a candidate
        const candidateCheckboxes = wrapper.findAll('input[type="checkbox"]').filter(
            input => input.attributes('name') === 'President'
        );
        await candidateCheckboxes[0].setValue(true);
        await wrapper.vm.$nextTick();

        // Assert
        const emissions = wrapper.emitted('add_selected_candidates');
        const latestEmission = emissions[emissions.length - 1][0];

        expect(latestEmission.no_vote).toBe(false);
        expect(latestEmission.candidates).toHaveLength(1);
    });

    /**
     * @test
     *
     * Scenario: Clicking skip disables all candidate checkboxes
     * Expected: All candidates should be disabled
     */
    test('it disables candidate checkboxes when skip is selected', async () => {
        // Act
        const noVoteCheckbox = wrapper.find('input[name="no_vote_option"]');
        await noVoteCheckbox.setValue(true);
        await wrapper.vm.$nextTick();

        // Assert
        const candidateCheckboxes = wrapper.findAll('input[type="checkbox"]').filter(
            input => input.attributes('name') === 'President'
        );

        candidateCheckboxes.forEach(checkbox => {
            expect(checkbox.element.disabled).toBe(true);
        });
    });

    /**
     * @test
     *
     * Scenario: Unchecking skip re-enables candidate checkboxes
     * Expected: All candidates should be enabled
     */
    test('it re-enables candidate checkboxes when skip is unchecked', async () => {
        // Arrange: Select skip first
        const noVoteCheckbox = wrapper.find('input[name="no_vote_option"]');
        await noVoteCheckbox.setValue(true);
        await wrapper.vm.$nextTick();

        // Act: Uncheck skip
        await noVoteCheckbox.setValue(false);
        await wrapper.vm.$nextTick();

        // Assert
        const candidateCheckboxes = wrapper.findAll('input[type="checkbox"]').filter(
            input => input.attributes('name') === 'President'
        );

        candidateCheckboxes.forEach(checkbox => {
            expect(checkbox.element.disabled).toBe(false);
        });
    });

    /**
     * @test
     *
     * Scenario: Production bug reproduction
     * Expected: Bug pattern should not occur with fix
     */
    test('it prevents production bug pattern from occurring', async () => {
        // Reproduce exact production scenario:
        // 1. User clicks "Skip"
        const noVoteCheckbox = wrapper.find('input[name="no_vote_option"]');
        await noVoteCheckbox.setValue(true);

        // 2. User unchecks "Skip" (changes mind)
        await noVoteCheckbox.setValue(false);

        // 3. User submits without selecting candidates
        const emissions = wrapper.emitted('add_selected_candidates');
        const finalEmission = emissions[emissions.length - 1][0];

        // Assert: Should NOT have the bug pattern {no_vote: false, candidates: []}
        if (finalEmission.candidates.length === 0) {
            expect(finalEmission.no_vote).toBe(true);  // ✅ MUST be true
        } else {
            expect(finalEmission.no_vote).toBe(false);  // ✅ OK if has candidates
        }

        // The bug pattern that MUST NOT occur:
        const hasBugPattern = finalEmission.no_vote === false &&
                             finalEmission.candidates.length === 0;

        expect(hasBugPattern).toBe(false);  // ✅ Bug pattern prevented!
    });

    /**
     * @test
     *
     * Scenario: Edge case - rapid click on skip checkbox
     * Expected: Should handle rapid changes correctly
     */
    test('it handles rapid skip checkbox toggling', async () => {
        const noVoteCheckbox = wrapper.find('input[name="no_vote_option"]');

        // Rapidly toggle
        await noVoteCheckbox.setValue(true);
        await noVoteCheckbox.setValue(false);
        await noVoteCheckbox.setValue(true);
        await noVoteCheckbox.setValue(false);

        await wrapper.vm.$nextTick();

        // Assert: Final state should be consistent
        expect(wrapper.vm.noVoteSelected).toBe(false);
        expect(wrapper.vm.selected).toEqual([]);

        const emissions = wrapper.emitted('add_selected_candidates');
        const finalEmission = emissions[emissions.length - 1][0];

        // Should be no_vote=true (no candidates selected)
        expect(finalEmission.no_vote).toBe(true);
        expect(finalEmission.candidates).toEqual([]);
    });
});
