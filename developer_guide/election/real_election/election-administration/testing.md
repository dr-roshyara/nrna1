# Phase 3.2-3.3: Testing the State Machine

**Date:** 2026-05-20  
**Phase:** 3.2-3.3  
**Status:** Complete and verified  
**Audience:** QA engineers, backend developers, test architects

---

## 🎯 Testing Strategy

### Levels of Testing

```
┌─────────────────────────────────────────────┐
│         ARCHITECTURE TESTS (6 tests)        │  ← Invariants
│  These never fail. They verify the system   │
│  architecture is mathematically correct.    │
└─────────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────────┐
│     UNIT TESTS (60+ tests)                  │  ← Logic
│  Test individual components in isolation.   │
│  SSOT engine, preconditions, transitions.   │
└─────────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────────┐
│   FEATURE TESTS (40+ tests)                 │  ← Integration
│  Test complete workflows end-to-end.        │
│  Submission → Approval → Setup → Voting.    │
└─────────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────────┐
│     E2E TESTS (10+ tests)                   │  ← User flows
│  Test complete user journeys in browser.    │
│  (Separate from unit/feature tests)         │
└─────────────────────────────────────────────┘
```

### Test Coverage Goals

| Component | Target | Method |
|-----------|--------|--------|
| SSOT Engine | 95%+ | Unit tests |
| Transition Guard | 90%+ | Unit + Feature |
| Write Barrier | 90%+ | Unit + Feature |
| State Derivation | 100% | Unit tests (decision tree) |
| Approval Workflow | 85%+ | Feature + E2E |
| Error Handling | 85%+ | Unit + Feature |

---

## 🏗️ Test Structure

### Architecture Tests Directory

```
tests/Architecture/
├── ElectionControllerArchitectureTest.php
├── ElectionLifecycleArchitectureTest.php
├── ElectionGuardArchitectureTest.php
└── ConstitutionalMetricsArchitectureTest.php
```

### Unit Tests Directory

```
tests/Unit/Application/Election/
├── Services/
│   ├── ElectionLifecycleEngineTest.php          (100% state derivation)
│   ├── ConstitutionalTransitionGuardTest.php    (All preconditions + rules)
│   └── ElectionLifecycleTest.php                (Public API)
├── Governance/
│   ├── ElectionStateWriteContextTest.php        (Authorization & recording)
│   └── ElectionStateWriteBarrierTest.php        (Mutator enforcement)
└── Monitoring/
    └── ConstitutionalMetricsTest.php            (Metric recording)
```

### Feature Tests Directory

```
tests/Feature/Election/
├── ApprovalWorkflow/
│   ├── CapacityEligibilityTest.php              (Free vs paid)
│   ├── TimezoneValidationTest.php               (Timezone precondition)
│   └── AutoApprovalTest.php                     (Auto vs manual approval)
├── StateTransitions/
│   ├── DraftToApprovalTest.php
│   ├── ApprovalToSetupTest.php
│   ├── SetupToVotingTest.php
│   └── VotingToResultsTest.php
├── WriteBarrier/
│   ├── AuthorizationTest.php                    (Direct vs authorized)
│   └── ViolationTrackingTest.php                (Metrics recording)
└── EndToEnd/
    ├── CompleteElectionFlowTest.php             (Draft → Results)
    └── ElectionRevisionFlowTest.php             (Rejection → Revise → Resubmit)
```

---

## 🧪 Unit Test Examples

### Test 1: State Derivation (Complete Decision Tree)

```php
// tests/Unit/Application/Election/Services/ElectionLifecycleEngineTest.php

namespace Tests\Unit\Application\Election\Services;

use App\Application\Election\Services\ElectionLifecycleEngineImpl;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use Tests\TestCase;

class ElectionLifecycleEngineTest extends TestCase
{
    private ElectionLifecycleEngineImpl $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = app(ElectionLifecycleEngineImpl::class);
    }

    /** @test */
    public function derives_draft_from_empty_facts()
    {
        $e = Election::factory()->create([
            'submitted_for_approval_at' => null,
            'rejected_at' => null,
            'approved_at' => null,
            'administration_completed_at' => null,
        ]);

        $this->assertEquals(
            ElectionLifecycleState::Draft,
            $this->engine->getState($e)
        );
    }

    /** @test */
    public function rejection_takes_priority_over_approval()
    {
        $e = Election::factory()->create([
            'submitted_for_approval_at' => now()->subHour(),
            'approved_at' => now()->subMinutes(30),
            'rejected_at' => now(),  // Most recent fact
        ]);

        $this->assertEquals(
            ElectionLifecycleState::Rejected,
            $this->engine->getState($e)
        );
    }

    /** @test */
    public function submitted_not_approved_state()
    {
        $e = Election::factory()->create([
            'submitted_for_approval_at' => now(),
            'approved_at' => null,
            'rejected_at' => null,
        ]);

        $this->assertEquals(
            ElectionLifecycleState::SubmittedForApproval,
            $this->engine->getState($e)
        );
    }

    /** @test */
    public function voting_active_when_window_open()
    {
        $e = Election::factory()->create([
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);

        $this->assertEquals(
            ElectionLifecycleState::VotingActive,
            $this->engine->getState($e)
        );
    }

    /** @test */
    public function ready_for_voting_when_window_not_yet_open()
    {
        $e = Election::factory()->create([
            'nomination_completed_at' => now(),
            'voting_starts_at' => now()->addHour(),
            'voting_ends_at' => now()->addHours(2),
        ]);

        $this->assertEquals(
            ElectionLifecycleState::ReadyForVoting,
            $this->engine->getState($e)
        );
    }

    /** @test */
    public function counting_when_voting_ended_results_not_published()
    {
        $e = Election::factory()->create([
            'voting_starts_at' => now()->subHours(2),
            'voting_ends_at' => now()->subHour(),  // Ended
            'results_published_at' => null,
        ]);

        $this->assertEquals(
            ElectionLifecycleState::Counting,
            $this->engine->getState($e)
        );
    }

    /** @test */
    public function results_published_when_results_set()
    {
        $e = Election::factory()->create([
            'results_published_at' => now()->subMinute(),
        ]);

        $this->assertEquals(
            ElectionLifecycleState::ResultsPublished,
            $this->engine->getState($e)
        );
    }
}
```

### Test 2: Precondition Validation

```php
// tests/Unit/Application/Election/Services/ConstitutionalTransitionGuardTest.php

class ConstitutionalTransitionGuardTest extends TestCase
{
    private ConstitutionalTransitionGuard $guard;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guard = app(ConstitutionalTransitionGuard::class);
    }

    /** @test */
    public function timezone_set_precondition_passes_with_timezone()
    {
        $e = Election::factory()
            ->create(['timezone' => 'Europe/Berlin']);

        $result = $this->guard->isPreconditionMet($e, 'timezone_set');

        $this->assertTrue($result);
    }

    /** @test */
    public function timezone_set_precondition_fails_without_timezone()
    {
        $e = Election::factory()
            ->create(['timezone' => null]);

        $result = $this->guard->isPreconditionMet($e, 'timezone_set');

        $this->assertFalse($result);
    }

    /** @test */
    public function capacity_eligibility_free_plan_under_40()
    {
        $org = Organisation::factory()->free()->create();
        $e = Election::factory()
            ->forOrganisation($org)
            ->create(['expected_voter_count' => 35]);

        $result = $this->guard->isPreconditionMet($e, 'capacity_eligibility');

        $this->assertTrue($result);
    }

    /** @test */
    public function capacity_eligibility_free_plan_over_40()
    {
        $org = Organisation::factory()->free()->create();
        $e = Election::factory()
            ->forOrganisation($org)
            ->create(['expected_voter_count' => 150]);

        $result = $this->guard->isPreconditionMet($e, 'capacity_eligibility');

        $this->assertFalse($result);
    }

    /** @test */
    public function capacity_eligibility_paid_plan_always_true()
    {
        $org = Organisation::factory()->paid()->create();
        $e = Election::factory()
            ->forOrganisation($org)
            ->create(['expected_voter_count' => 5000]);

        // Paid plan should allow any capacity
        $result = $this->guard->isPreconditionMet($e, 'capacity_eligibility');

        $this->assertTrue($result);
    }
}
```

### Test 3: Write Barrier Authorization

```php
// tests/Unit/Application/Election/Governance/ElectionStateWriteContextTest.php

class ElectionStateWriteContextTest extends TestCase
{
    /** @test */
    public function authorized_closure_can_write()
    {
        $e = Election::factory()->inDraftState()->create();

        ElectionStateWriteContext::authorize(function() use ($e) {
            $e->state = 'approved';
            $e->save();
        });

        $this->assertEquals('approved', $e->fresh()->state);
    }

    /** @test */
    public function unauthorized_write_recorded_at_level_1()
    {
        config(['election.enforcement_level' => 1]);

        $e = Election::factory()->inDraftState()->create();
        $metrics = app(ConstitutionalMetricsContract::class);

        // Try unauthorized write
        $e->state = 'voting';
        $e->save();  // Does NOT throw at Level 1

        // But violation recorded
        $health = $metrics->getHealth();
        $this->assertGreaterThan(0, $health['unauthorized_state_mutations_24h']);
    }

    /** @test */
    public function unauthorized_write_throws_at_level_4()
    {
        config(['election.enforcement_level' => 4]);

        $e = Election::factory()->inDraftState()->create();

        $this->expectException(UnauthorizedStateMutationException::class);
        
        $e->state = 'voting';
        $e->save();  // THROWS at Level 4
    }

    /** @test */
    public function authorization_context_restores_after_closure()
    {
        // Ensure authorization flag is restored
        $before = ElectionStateWriteContext::isAuthorized();

        ElectionStateWriteContext::authorize(function() {
            // During closure: authorized
            $this->assertTrue(ElectionStateWriteContext::isAuthorized());
        });

        // After closure: restored
        $this->assertEquals($before, ElectionStateWriteContext::isAuthorized());
    }
}
```

---

## 🔄 Feature Test Examples

### Feature Test 1: Complete Approval Flow

```php
// tests/Feature/Election/ApprovalWorkflow/CompleteApprovalFlowTest.php

class CompleteApprovalFlowTest extends TestCase
{
    /** @test */
    public function free_plan_under_capacity_auto_approves()
    {
        // Create free plan organisation
        $org = Organisation::factory()->free()->create();

        // Create election in draft
        $e = Election::factory()
            ->forOrganisation($org)
            ->inDraftState()
            ->withExpectedVoters(35)
            ->withTimezone('Europe/Berlin')
            ->create();

        $lifecycle = ElectionLifecycle::of($e);

        // Submit for approval
        $lifecycle->transitionVia('submit_for_approval');

        // Should be auto-approved
        $this->assertEquals('approved', $e->fresh()->state);
        $this->assertNotNull($e->fresh()->approved_at);
    }

    /** @test */
    public function free_plan_over_capacity_rejected()
    {
        $org = Organisation::factory()->free()->create();
        $e = Election::factory()
            ->forOrganisation($org)
            ->inDraftState()
            ->withExpectedVoters(150)
            ->withTimezone('Europe/Berlin')
            ->create();

        $lifecycle = ElectionLifecycle::of($e);

        // Attempt to submit
        $this->expectException(TransitionBlockedException::class);
        $lifecycle->transitionVia('submit_for_approval');

        // Election still in draft
        $this->assertEquals('draft', $e->fresh()->state);
    }

    /** @test */
    public function can_revise_and_resubmit()
    {
        $org = Organisation::factory()->free()->create();
        $e = Election::factory()
            ->forOrganisation($org)
            ->inDraftState()
            ->withExpectedVoters(150)  // Too many
            ->withTimezone('Europe/Berlin')
            ->create();

        // Try to submit - fails
        $lifecycle = ElectionLifecycle::of($e);
        $this->expectException(TransitionBlockedException::class);
        $lifecycle->transitionVia('submit_for_approval');

        // User revises - reduces voters
        $e->update(['expected_voter_count' => 35]);

        // Try again - succeeds
        $lifecycle = ElectionLifecycle::of($e);
        $lifecycle->transitionVia('submit_for_approval');

        // Now approved
        $this->assertEquals('approved', $e->fresh()->state);
    }
}
```

### Feature Test 2: State Transitions

```php
// tests/Feature/Election/StateTransitions/CompleteElectionFlowTest.php

class CompleteElectionFlowTest extends TestCase
{
    /** @test */
    public function complete_flow_draft_to_results()
    {
        $org = Organisation::factory()->paid()->create();
        $e = Election::factory()
            ->forOrganisation($org)
            ->inDraftState()
            ->withTimezone('UTC')
            ->create();

        $lifecycle = ElectionLifecycle::of($e);

        // Step 1: Draft → Submitted
        $lifecycle->transitionVia('submit_for_approval');
        $this->assertEquals('submitted_for_approval', $e->fresh()->state);

        // Step 2: Submitted → Approved
        $lifecycle->transitionVia('approve');
        $this->assertEquals('approved', $e->fresh()->state);

        // Step 3: Approved → Setup
        $e->update(['administration_completed_at' => now()]);
        $this->assertEquals('setup', ElectionLifecycle::of($e)->state()->value);

        // Step 4: Setup → Ready for Voting
        $e->update([
            'nomination_completed_at' => now(),
            'voting_starts_at' => now()->addHour(),
            'voting_ends_at' => now()->addHours(2),
        ]);
        $this->assertEquals('ready_for_voting', ElectionLifecycle::of($e)->state()->value);

        // Step 5: Ready → Voting (manually open)
        $e->update(['voting_starts_at' => now()->subMinute()]);
        $this->assertEquals('voting_active', ElectionLifecycle::of($e)->state()->value);

        // Step 6: Voting → Counting (manually close)
        $e->update(['voting_ends_at' => now()->subMinute()]);
        $this->assertEquals('counting', ElectionLifecycle::of($e)->state()->value);

        // Step 7: Counting → Results Published
        $e->update(['results_published_at' => now()]);
        $this->assertEquals('results_published', ElectionLifecycle::of($e)->state()->value);
    }
}
```

---

## 🏛️ Architecture Test Examples

Architecture tests verify that the system always obeys fundamental rules:

```php
// tests/Architecture/ElectionLifecycleArchitectureTest.php

class ElectionLifecycleArchitectureTest extends TestCase
{
    /** @test */
    public function state_always_matches_ssot_engine_computation()
    {
        // This test never fails - it verifies architecture
        // For 1000 random elections, compute state via engine
        // and verify it's mathematically consistent

        for ($i = 0; $i < 100; $i++) {
            $e = Election::factory()->create([
                'submitted_for_approval_at' => rand(0, 1) ? now() : null,
                'approved_at' => rand(0, 1) ? now() : null,
                'administration_completed_at' => rand(0, 1) ? now() : null,
                'voting_starts_at' => rand(0, 1) ? now() : null,
                'voting_ends_at' => rand(0, 1) ? now() : null,
            ]);

            $engine = app(ElectionLifecycleEngineImpl::class);
            $computed = $engine->getState($e);

            // If we run getState 100 times with same facts
            // we always get the same result
            for ($j = 0; $j < 100; $j++) {
                $computed2 = $engine->getState($e);
                $this->assertEquals($computed, $computed2);
            }
        }
    }

    /** @test */
    public function preconditions_deterministic()
    {
        // Same input always produces same precondition result
        $e = Election::factory()->create(['timezone' => 'UTC']);
        $guard = app(ConstitutionalTransitionGuard::class);

        for ($i = 0; $i < 100; $i++) {
            $result = $guard->isPreconditionMet($e, 'timezone_set');
            $this->assertTrue($result);  // Always true
        }
    }

    /** @test */
    public function rejected_state_always_terminal()
    {
        // Once rejected, state should remain rejected
        // no matter what other facts are set
        $e = Election::factory()->create([
            'rejected_at' => now(),
            'approved_at' => now(),  // Both set
        ]);

        $engine = app(ElectionLifecycleEngineImpl::class);
        $this->assertEquals(
            ElectionLifecycleState::Rejected,
            $engine->getState($e)
        );

        // Still rejected even if we set other facts
        $e->update(['results_published_at' => now()]);
        $this->assertEquals(
            ElectionLifecycleState::Rejected,
            $engine->getState($e)
        );
    }
}
```

---

## 🚀 Running Tests

### Run All Tests

```bash
php artisan test --no-coverage
```

### Run Specific Test Suite

```bash
# Architecture tests only
php artisan test tests/Architecture/ --no-coverage

# Unit tests only
php artisan test tests/Unit/Application/Election/ --no-coverage

# Feature tests only
php artisan test tests/Feature/Election/ --no-coverage

# Approval workflow tests
php artisan test tests/Feature/Election/ApprovalWorkflow/ --no-coverage
```

### Run With Coverage

```bash
php artisan test tests/Unit/Application/Election/ --coverage
```

### Watch Mode (During Development)

```bash
php artisan test --watch tests/Unit/Application/Election/Services/
```

---

## ✅ Test Verification Checklist

Before considering state machine production-ready:

- [ ] All unit tests passing (60+)
- [ ] All feature tests passing (40+)
- [ ] All architecture tests passing (6/6)
- [ ] Coverage >85% for critical paths
- [ ] No pending/skip tests
- [ ] All precondition paths tested
- [ ] All state transitions tested
- [ ] Authorization tests passing
- [ ] Metrics recording tests passing
- [ ] Violation detection tests passing
- [ ] Integration tests passing
- [ ] E2E flow tested in browser
- [ ] Performance tests (large election sets)
- [ ] Concurrent update tests

---

## 🧩 Helper Factories

### Factory Extensions

```php
// tests/Factories/ElectionFactory.php

public function inDraftState()
{
    return $this->state([
        'submitted_for_approval_at' => null,
        'rejected_at' => null,
        'approved_at' => null,
    ]);
}

public function inSetupState()
{
    return $this->state([
        'approved_at' => now(),
        'administration_completed_at' => now(),
        'nomination_completed_at' => null,
    ]);
}

public function inVotingState()
{
    return $this->state([
        'voting_starts_at' => now()->subHour(),
        'voting_ends_at' => now()->addHour(),
    ]);
}

public function withExpectedVoters($count)
{
    return $this->state(['expected_voter_count' => $count]);
}

public function withTimezone($tz)
{
    return $this->state(['timezone' => $tz]);
}
```

### Usage

```php
$e = Election::factory()
    ->forOrganisation($org)
    ->inDraftState()
    ->withExpectedVoters(50)
    ->withTimezone('Europe/Berlin')
    ->create();
```

---

## 🎯 Summary

**Testing the state machine:**
1. ✅ Unit tests verify logic in isolation
2. ✅ Feature tests verify integration
3. ✅ Architecture tests verify invariants
4. ✅ >85% coverage on critical paths
5. ✅ All preconditions tested
6. ✅ All transitions tested
7. ✅ Authorization tested at all levels
8. ✅ Metrics recording tested

**Key test files:**
- `ElectionLifecycleEngineTest.php` - State derivation
- `ConstitutionalTransitionGuardTest.php` - Preconditions
- `ElectionStateWriteContextTest.php` - Authorization
- Feature tests in `tests/Feature/Election/`
- Architecture tests in `tests/Architecture/`

**Next step:**
- Troubleshoot issues: see `troubleshooting.md`
