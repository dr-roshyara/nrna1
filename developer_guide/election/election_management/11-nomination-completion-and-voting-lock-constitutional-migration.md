# Nomination Completion & Voting-Lock Constitutional Migration

**Date:** 2026-09-13
**Commits:** `e023b6555` · `dc04cce18` · `86bf893eb` · `8dbe4f668`
**Status:** implemented and committed as described below. One larger, adjacent question (should the automatic voting-start mechanism itself be brought under the Constitution, and does it comply with `EM-VOT-004`) was investigated read-only, found to require a Product Owner/ARB decision, and is **explicitly not implemented** — see [§7](#7-what-was-deliberately-not-changed-and-why).

## 1. Overview

A user reported that a real election (`testing-1-0c715071`, later also `onf-europe-test-7891ba99`) jumped from `setup_nomination` straight to `voting_active`/`counting` after "Nomination Complete" was clicked, with voting dates already in the past — without anyone ever clicking "open voting." A read-only investigation (no code or data changed) traced this to two problems:

1. **`ElectionLifecycleEngineImpl::getState()`'s Counting-derivation rule** didn't require `voting_locked`, so a stale/elapsed voting window plus a legacy fact-mutation call could make the engine derive `Counting` even though `open_voting` was never invoked.
2. **`Election::completeNomination()` was the one remaining lifecycle action still running the old, pre-constitutional code path** — a plain model method with inline guards, bypassing `ElectionConstitution`/`ConstitutionalTransitionGuard`/`Election::transitionTo()` entirely, while every other lifecycle action (`complete_administration`, `open_voting`, `close_voting`, `approve`, `publish_results`) had already been migrated.

Four slices followed, each TDD-first (characterization tests written and shown RED/GREEN before any production change), each independently reviewed and committed.

### Background: the dual lifecycle authority

This codebase has **two** mechanisms that both talk about an election's lifecycle, and understanding the difference is essential to everything below:

- **`ElectionLifecycleEngineImpl::getState()`** (`app/Application/Election/Services/ElectionLifecycleEngineImpl.php`) — pure, stateless, **fact-derivation**. Given the election's current columns (`approved_at`, `administration_completed`, `nomination_completed`, `voting_locked`, `voting_starts_at`/`voting_ends_at`, etc.), it computes what state the election is *in right now*. It never writes anything, and is re-evaluated on every request.
- **`Election::transitionTo(Transition $transition)`** (`app/Models/Election.php`) — the **constitutional transition mechanism**: `ConstitutionalTransitionGuard::assertAllowed()` → `Election::validateTransitionRules()` → an `ElectionStateTransition` audit row → the raw `state` column write → side effects — all inside one DB transaction — then a post-commit domain event.

A lifecycle *action* (like `complete_nomination`) goes through `transitionTo()` and, as a side effect, sets the *facts* the engine later reads to derive a state. The engine never reads `state` directly for the derived states covered here (`SetupNomination`, `VotingActive`, `Counting`, etc.) — it only reads facts. This separation is why a fact-mutation bug in one place (e.g. `complete_nomination` accidentally setting `voting_locked`) can silently produce a wrong *derived* state somewhere else entirely.

---

## 2. Slice A — Counting requires a legitimately opened voting window

**Commit:** `e023b6555 fix(election): require legitimately opened voting for counting`

### The gap

`ElectionLifecycleEngineImpl::getState()`'s Counting rule was:

```php
if ($election->voting_ends_at !== null && $now->gte($election->voting_ends_at)) {
    if ($election->approved_at !== null && $election->administration_completed && $election->nomination_completed) {
        return ElectionLifecycleState::Counting;
    }
    // ...
}
```

Nothing here checked `voting_locked`. But the Constitution's own transition graph only reaches `Counting` via `close_voting`, and `close_voting` is only reachable from `voting_active`, which itself requires `voting_locked === true`. An election could reach the same observable "Counting" outcome without ever having `voting_locked` set — i.e. without voting ever having been legitimately opened.

### The fix

One line, in `app/Application/Election/Services/ElectionLifecycleEngineImpl.php`:

```php
if ($election->voting_locked && $election->approved_at !== null && $election->administration_completed && $election->nomination_completed) {
    return ElectionLifecycleState::Counting;
}
```

`voting_locked` is the durable fact that records a legitimate open (via `open_voting`) or an authorized operational seal (`Election::enforceVotingLock()`, see §7) — distinct from `voting_starts_at`/`voting_ends_at`, each of which have more than one uncoordinated writer.

### Tests

Two new characterization tests in `tests/Unit/Domain/Election/ElectionLifecycleEngineTest.php`: an election with an elapsed voting window but `voting_locked=false` must not derive `Counting`; the legitimate case (`voting_locked=true`) continues to derive `Counting` exactly as before. Fixture synchronization (same fact, no assertion changes): `ElectionScenarioFactory::votingClosed()` and two inline `Counting` constructions (`ElectionPolicyStateAwareTest`, `ElectionSuspensionTest`) now set `voting_locked=true`, since all three represent "voting happened and was closed" scenarios that must satisfy the same invariant.

---

## 3. Slice B — `complete_nomination` migrated to the constitutional transition mechanism

**Commit:** `dc04cce18 refactor(election): migrate nomination completion to constitutional transitions`

### What changed

`Election::completeNomination(string $reason, ?string $actorId = null)` was a self-contained legacy method:

```php
// BEFORE (removed)
public function completeNomination(string $reason, ?string $actorId = null): void
{
    $pendingCount = $this->candidacies()->withoutGlobalScopes()->where('status', 'pending')->count();
    if ($pendingCount > 0) { throw new \InvalidArgumentException(...); }
    if ($this->candidacies()->withoutGlobalScopes()->where('status', 'approved')->count() === 0) { throw new \InvalidArgumentException(...); }
    $this->update(['nomination_completed' => true, 'nomination_completed_at' => now()]);
    if (!$this->voting_starts_at) { $this->update(['voting_starts_at' => now(), 'voting_ends_at' => now()->addDays(4)]); }
    $this->logStateChange('nomination_completed', ['reason' => $reason, 'actor_id' => $actorId]);
    Event::dispatch(new NominationCompleted($this, $actorId, $reason));
}
```

It is now:

```php
public function completeNomination(string $reason, string $actorId): void
{
    $this->transitionTo(\App\Domain\Election\StateMachine\Transition::manual('complete_nomination', $actorId, $reason));
}
```

`$actorId` is now **non-nullable** — every constitutional path requires a real actor.

A parallel **system** action, `auto_complete_nomination`, was added so the grace-period console path is migrated coherently in the same slice rather than left on a second, non-constitutional path:

```php
// app/Console/Commands/ProcessElectionAutoTransitions.php
$election->transitionTo(\App\Domain\Election\StateMachine\Transition::automatic(
    action: 'auto_complete_nomination',
    trigger: \App\Domain\Election\StateMachine\TransitionTrigger::GRACE_PERIOD,
    reason: 'Automatic transition after grace period',
));
```

### `ElectionConstitution::RULES` (`app/Domain/Election/Constitution/ElectionConstitution.php`)

```php
'complete_nomination' => [
    'allowed_states' => ['setup_nomination'],
    'allowed_roles' => ['chief', 'deputy'],
    'preconditions' => ['has_approved_candidates', 'no_pending_candidacies'],
    'target_state' => 'setup_nomination',   // same-state action — see note below
    'description' => 'Complete candidate approval process (chief/deputy)',
],
'auto_complete_nomination' => [
    'allowed_states' => ['setup_nomination'],
    'allowed_roles' => ['system'],
    'preconditions' => ['has_approved_candidates', 'no_pending_candidacies'],
    'target_state' => 'setup_nomination',
    'description' => 'Complete candidate approval process (automatic, grace-period timeout)',
],
```

`target_state === allowed_states[0]` is an established pattern in this Constitution (`apply_candidacy`, `resume` are prior examples) for a "same-state" action: it produces durable facts (`nomination_completed`) without itself advancing the derived lifecycle state. The engine still derives `SetupNomination` immediately afterward, unless the resulting facts happen to satisfy `ReadyForVoting`'s own conditions.

### The shared predicate authority — `NominationCompletionPredicates`

**New file:** `app/Application/Election/Services/NominationCompletionPredicates.php`

```php
final class NominationCompletionPredicates
{
    public static function hasApprovedCandidates(Election $election): bool
    {
        return $election->candidacies()->withoutGlobalScopes()
            ->where('status', 'approved')->exists();
    }

    public static function hasNoPendingCandidacies(Election $election): bool
    {
        return !$election->candidacies()->withoutGlobalScopes()
            ->where('status', 'pending')->exists();
    }
}
```

This is the **single source of truth** for both preconditions. `withoutGlobalScopes()` is required, not decorative: the console/system caller (`ProcessElectionAutoTransitions`) runs with no HTTP tenant context, so `Candidacy`'s `BelongsToTenant` global scope would otherwise silently return zero rows for a real tenant election.

It is consumed by **both**:

1. **`ConstitutionalTransitionGuard::isPreconditionMet()`** (human path — the guard is skipped entirely for system-triggered transitions, so this only covers the manual `complete_nomination` call):
   ```php
   'has_approved_candidates' => NominationCompletionPredicates::hasApprovedCandidates($election),
   'no_pending_candidacies' => NominationCompletionPredicates::hasNoPendingCandidacies($election),
   ```
   with friendly `PRECONDITION_MESSAGES` added:
   ```php
   'has_approved_candidates' => 'At least one candidate must be approved before nomination can be completed.',
   'no_pending_candidacies' => 'All pending candidacy applications must be approved or rejected before nomination can be completed.',
   ```

2. **`Election::validateCompleteNomination()` / `validateAutoCompleteNomination()`** (new methods on the model, both paths — `validateTransitionRules()` runs unconditionally regardless of trigger type, which is *why* two thin methods are needed instead of one: the method-name-building logic derives a different name per action string):
   ```php
   private function validateCompleteNomination(Transition $transition): void
   {
       $this->assertNominationCompletionPredicatesMet();
   }

   private function validateAutoCompleteNomination(Transition $transition): void
   {
       $this->assertNominationCompletionPredicatesMet();
   }

   private function assertNominationCompletionPredicatesMet(): void
   {
       if (!NominationCompletionPredicates::hasApprovedCandidates($this)) {
           throw new \DomainException('At least one candidate must be approved before nomination can be completed.');
       }
       if (!NominationCompletionPredicates::hasNoPendingCandidacies($this)) {
           throw new \DomainException('All pending candidacy applications must be approved or rejected before nomination can be completed.');
       }
   }
   ```
   In practice, for the human path the Guard already blocks first (it runs before `validateTransitionRules()`); these hooks are the *only* enforcement for the system path, and a defense-in-depth safety net for the human path.

### Side effects and event wiring

**New shared side-effect method**, used by both actions, wired into `transitionTo()`'s side-effect `match`:

```php
private function applySideEffectsForCompleteNomination(\Carbon\Carbon $currentTime): void
{
    $updateData = [
        'nomination_completed' => true,
        'nomination_completed_at' => $currentTime,
    ];
    if (!$this->voting_starts_at) {
        $updateData['voting_starts_at'] = $currentTime;
        $updateData['voting_ends_at'] = $currentTime->copy()->addDays(4);
    }
    \Illuminate\Support\Facades\DB::table('elections')->where('id', $this->id)->update($updateData);
}
```

**This is the single highest-value invariant in this migration: `applySideEffectsForCompleteNomination()` never touches `voting_locked`.** Opening voting remains `open_voting`'s sole responsibility. This is verified directly by `CompleteNominationConstitutionalMigrationTest::test_complete_nomination_never_sets_voting_locked`.

Event dispatch, wired into `transitionTo()`'s post-commit `match`:

```php
'complete_nomination', 'auto_complete_nomination' => event(new NominationCompleted($this, $transition->actorId, $transition->reason)),
```

`$transition->actorId` is always a non-empty string by construction (`Transition`'s constructor casts `null` to `'system'`), so the previously-latent `NominationCompleted(..., string $completedBy, ...)` `TypeError` on a null actor can no longer occur through this path.

### What was dropped

The legacy dual-write audit (`logStateChange()` → JSON `state_audit_log` column + `ElectionAuditLog` row) is gone for this action; `ElectionStateTransition` (the constitutional audit trail) is now the sole record. `forceCloseNomination()` (a separate, still-legacy method) is unaffected.

### An unavoidable, evidence-based side effect: role enforcement

Because `completeNomination()` now goes through `ConstitutionalTransitionGuard`, **every direct/programmatic caller** — not just HTTP requests — must now be an authenticated (`Auth::user()`) active `chief`/`deputy` `ElectionOfficer` for the election. The legacy method had no such check at all. This is not incidental: `ADR-001` (Constitutional Capability Sovereignty) and the ARB-accepted rule-ownership map (`docs/publicdigit/reviews/2026-08-12-election-constitution-authority-investigation.md`) both place "who may act" inside the Constitution's authority, and every other already-migrated action (`complete_administration`, `open_voting`, `close_voting`, `approve`, `publish_results`) already behaves this way — `complete_nomination` was the outlier, not the norm.

**Important, and easy to miss:** `ConstitutionalTransitionGuard::userHasAnyRole()` checks Laravel's `Auth::user()` — **not** the `Transition`'s own `$actorId` string. These are two independent inputs: `$actorId` is audit/event identity only; authorization is derived entirely from the authenticated session. A test (or any script) calling `completeNomination()` directly without `actingAs()` will fail the role check regardless of which `$actorId` it passes.

---

## 4. C1 — corrected a stale, pre-migration test

**Commit:** `86bf893eb test(election): correct nomination voting-lock characterization`

`ElectionModelLockAndAuditTest::test_complete_nomination_locks_voting_on_start` asserted `voting_locked === true` after `completeNomination()`. Verified against the removed legacy body (§3): **neither the legacy implementation nor the current constitutional one has ever touched `voting_locked`.** The test's premise was never true of any implementation that has existed in this repository, and it directly contradicted the invariant Slice A/B exist to protect. Renamed to `test_complete_nomination_does_not_lock_voting`, corrected to assert `nomination_completed`/`_at` are set while `voting_locked`/`voting_locked_by` remain `false`/`null`, and given the now-required active-officer + `actingAs()` fixture. No production code changed.

---

## 5. C2a — automatic voting-lock now requires an approved candidate

**Commit:** `8dbe4f668 fix(election): require approved candidate for automatic voting lock`

### The gap

`ProcessElectionAutoTransitions::processNominationToVotingTransition()` is a **separate, non-constitutional** mechanism (see §7) that can lock voting once nomination is completed, the grace period has elapsed, and no candidacies are pending — but it never checked for an *approved* candidate:

```php
private function hasPendingCandidates(Election $election): bool
{
    return $election->posts()->withoutGlobalScopes()
        ->join('candidacies', 'posts.id', '=', 'candidacies.post_id')
        ->where('candidacies.status', 'pending')->exists();
}
```

An election with **zero** candidacies at all (0 approved, 0 pending) would pass this check and get `voting_locked = true` — and since Slice A made `Counting` require only `voting_locked` (not candidate approval), such an election could later legitimately derive `Counting` with zero candidates.

### The fix

One guard clause, reusing the existing shared predicate (no new predicate, no duplicated query):

```php
if ($this->hasPendingCandidates($election)) {
    return 0;
}

if (!\App\Application\Election\Services\NominationCompletionPredicates::hasApprovedCandidates($election)) {
    return 0;
}

$systemId = null;
try {
    $election->enforceVotingLock($systemId);
    // ...
```

### New invariant

```
processNominationToVotingTransition() may establish voting_locked = true
only when: nomination is completed, the grace period has elapsed,
no candidacies are pending, AND at least one candidacy is approved.
```

### Tests

New: `ProcessElectionAutoTransitionsTest::test_skips_voting_transition_when_zero_approved_candidates` — written and confirmed RED before the fix, GREEN after. Existing coverage for "approved candidate + no pending → locks" and "pending candidate exists → blocked" was confirmed adequate and left untouched (no duplicate test added).

---

## 6. Test files touched or added today

| File | What |
|---|---|
| `tests/Unit/Domain/Election/ElectionLifecycleEngineTest.php` | +2 characterization tests (Slice A) |
| `tests/Support/ElectionScenarioFactory.php` | `votingClosed()` fixture gains `voting_locked => true` |
| `tests/Feature/Election/ElectionPolicyStateAwareTest.php` | inline fixture gains `voting_locked => true` |
| `tests/Feature/Election/ElectionSuspensionTest.php` | inline fixture gains `voting_locked => true` |
| `tests/Unit/Application/Election/NominationCompletionPredicatesTest.php` | **new**, 6 tests, including no-tenant-context (console) execution |
| `tests/Feature/Election/CompleteNominationCharacterizationTest.php` | **new**, 12 tests — characterizes the *pre-migration* legacy `completeNomination()`, intentionally left unmodified; now a documented "expected migration divergence" baseline (11/12 now fail against the migrated code, for the reasons in §3's "unavoidable side effect" — this is intentional and expected, not a regression) |
| `tests/Feature/Election/CompleteNominationConstitutionalMigrationTest.php` | **new**, 12 tests covering both `complete_nomination` (human) and `auto_complete_nomination` (system) against the migrated implementation |
| `tests/Feature/ElectionStateMachineTest.php` | 4 tests updated: real derivation facts instead of a stale raw `state` string, `actingAs()` + active `ElectionOfficer`, one exception-type change (`InvalidArgumentException` → `InvalidTransitionException`) |
| `tests/Feature/Election/StateMachineTransitionAuditTest.php` | 2 tests updated: same pattern, plus expected `state` string corrected `'nomination'` → `'setup_nomination'` (current constitutional name for the same phase) |
| `tests/Unit/Application/Election/EmVot002OpenVotingPreconditionTest.php` | 1 message assertion updated to the new friendly precondition message |
| `tests/Feature/Election/ElectionModelLockAndAuditTest.php` | 1 test corrected (C1, §4) |
| `tests/Feature/Console/ProcessElectionAutoTransitionsTest.php` | +1 test (C2a, §5) |

Run the focused suite:

```bash
php artisan test \
  tests/Feature/Election/CompleteNominationConstitutionalMigrationTest.php \
  tests/Feature/Election/ElectionModelLockAndAuditTest.php \
  tests/Feature/Console/ProcessElectionAutoTransitionsTest.php \
  tests/Unit/Domain/Election/ElectionLifecycleEngineTest.php \
  tests/Unit/Domain/Election/ElectionConstitutionTest.php \
  tests/Unit/Application/Election/NominationCompletionPredicatesTest.php \
  tests/Unit/Application/Election/EmVot002OpenVotingPreconditionTest.php
```

**Known, pre-existing, deliberately untouched failures** (present before this session's work, unrelated to it — a legacy raw-`state`-string fixture pattern still affecting `complete_administration`/`open_voting`/`close_voting`/`approve`/`publish_results` tests that were never migrated to real derivation facts): `ElectionStateMachineTest` (16 remaining), `StateMachineTransitionAuditTest` (3 remaining), `ElectionLifecycleEngineTest::suspension_is_highest_priority_override`, `ElectionModelLockAndAuditTest::test_complete_administration_still_works`. Do not "fix" these opportunistically — they are out of scope for this migration and were explicitly left alone throughout.

---

## 7. What was deliberately NOT changed, and why

A read-only investigation (documented in full in `docs/publicdigit/reviews/2026-09-13-decision-request-start-of-voting-governance-gate.md`) found that `voting_locked` has a **third** writer beyond `open_voting`/`close_voting`:

```php
// app/Models/Election.php
/**
 * Infrastructure enforcement: lock voting window after expiry.
 * This is NOT a constitutional transition action. It is an operational
 * enforcement mechanism used by auto-transition commands to seal expired
 * voting windows. It bypasses the constitutional state machine intentionally.
 */
public function enforceVotingLock(?string $actorId = null): void
{
    $this->update(['voting_locked' => true, 'voting_locked_at' => now(), 'voting_locked_by' => $actorId]);
    $this->logStateChange('voting_locked', ['actor_id' => $actorId]);
}
```

called from **two** places in `ProcessElectionAutoTransitions`: `processNominationToVotingTransition()` (a self-service auto-lock path, now gated per §5) and the command's own `enforceVotingLock()` wrapper (seals an already-expired, still-unlocked window).

The investigation additionally found an **adopted business rule directly on point**: `EM-VOT-004` (`docs/publicdigit/business_rules/ELECTION_MANIFESTO.md`, adopted PO/ARB 2026-08-15) states *"an election does not enter its voting phase by the passage of time... No clock-driven lifecycle mechanism may exercise the Chief Election Officer's authority."* This directly questions whether `processNominationToVotingTransition()`'s clock-driven mechanism should exist in its current form at all — but the same adoption **explicitly withheld implementation authorization**, and the architecture work that followed it is itself self-labeled *"Architecture authorized · implementation NOT authorized"* with its own implementation-boundary section marked **"NOT GRANTED."**

**Therefore, deliberately out of scope for this session's work:**
- Migrating `enforceVotingLock()` or `ProcessElectionAutoTransitions` into the Constitution
- Splitting or retiring `voting_locked` (it currently carries two different meanings depending on which writer set it — a documented, pre-existing architectural finding, not new)
- Changing `voting_starts_at` semantics (`open_voting` currently overwrites it to `now()`, discarding the committee's scheduled window — also a documented, pre-existing finding)
- Resolving `EM-OPEN-021` (what lifecycle state applies to zero approved candidates with an elapsed voting window) — an explicitly ARB-qualified open business decision, not an implementation defect

A governance decision request covering these questions has been drafted for PO/ARB review: `docs/publicdigit/reviews/2026-09-13-decision-request-start-of-voting-governance-gate.md`. **Do not implement any of the above without an explicit authorization following that decision.**

---

## 8. Related work today, documented elsewhere

Two other pieces of work landed in this same session but belong to different developer-guide areas and are not duplicated here:

- **Ballot Preview feature** + two follow-on voting-flow fixes (commits `4865caf15`, `81aa13b83`, `57ac18eeb`) — see `developer_guide/ballot_preview/00_index.md`.
- **Candidate Photo Edit** (crop/reposition) — implemented, tests GREEN, but **paused and deliberately left uncommitted** per explicit instruction. No developer guide written yet since it is not merged; see `.claude/sessions/2026-09-13.md` for its current state if resuming this work.

---

## 9. Traceability

- Commits: `e023b6555`, `dc04cce18`, `86bf893eb`, `8dbe4f668`
- Session log: `.claude/sessions/2026-09-13.md` (second entry, "Election lifecycle constitutional migration")
- Durable architectural facts recorded: `.claude/MEMORY.md` § "Election lifecycle — constitutional authority facts"
- Governance decision request (open, pending PO/ARB): `docs/publicdigit/reviews/2026-09-13-decision-request-start-of-voting-governance-gate.md`
- Prior architecture evidence corroborating the `voting_locked`/`voting_starts_at` findings above (pre-dates this session): `docs/publicdigit/reviews/2026-08-15-session4-election-lifecycle-architecture-adaptation.md`, `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` (`EM-VOT-002` through `EM-VOT-005`, `EM-GOV-006`, `EM-OPEN-021`, `EM-OPEN-025`)
