# EM-VOT-002 — The Approved-Candidate Gate on Voting

**Business rule (Election Manifesto §4a, ADOPTED — SD-14 = YES):**
*An election must have at least one approved candidate before voting may be opened.*

**Implemented:** commit `f2c2cc4e` (2026-08-13, PBDIGIT-64) · Independent verification by Session 1: pending at time of writing.

---

## Why this rule needs TWO enforcement points

`voting_active` is reachable by two architecturally different mechanisms:

```
open_voting command                     computed lifecycle path
(Chief clicks "open voting")            (nobody does anything)
        │                                        │
Election::transitionTo()                ElectionLifecycleEngineImpl::getState()
        │                                        │
ConstitutionalTransitionGuard           priority 5: voting window open NOW?
        │                                        │
ElectionConstitution preconditions               │
        └────────────────┬───────────────────────┘
                         ▼
                   voting_active
```

The computed path derives the state **from the clock alone** — the PBDIGIT-64 defect
was an election that became `voting_active` with zero candidacies simply because its
`voting_starts_at` passed. **A command-level precondition cannot protect a path on
which no command executes.** Hence one rule, two expressions:

| Boundary | Where | What it expresses |
|---|---|---|
| Command | `app/Domain/Election/Constitution/ElectionConstitution.php` — `open_voting.preconditions` now includes `has_approved_candidates` | The constitutional transition constraint: the Chief's `open_voting` is refused (guard names the unmet precondition) |
| Domain invariant | `app/Application/Election/Services/ElectionLifecycleEngineImpl.php` — `getState()` priority 5 | The derivation returns `VotingActive` only when the window is open **and** ≥1 approved candidate exists |

## Component responsibilities (keep these distinct)

- **`ElectionConstitution`** — constitutional workflow / transition policy. It is NOT a
  generic business-rule registry: EM-VOT-002 appears there only because `open_voting`
  is a constitutional transition. Do not add membership/entitlement/credential rules to it.
- **`ConstitutionalTransitionGuard`** — application-layer enforcement of the
  constitution's rules on every `transitionTo()`. Unchanged by this work; its
  `has_approved_candidates` evaluator pre-existed (used by `complete_nomination`).
- **`ElectionLifecycleEngineImpl`** — derives lifecycle state from business facts;
  the single source of truth every consumer (routing, middleware, `canVote`) reads
  via the `ElectionLifecycle` facade.

## The authoritative fact

```php
$election->candidacies()->where('status', 'approved')->exists()
```

- "Approved candidate" = `candidacies.status = 'approved'` — the product's
  pre-existing definition (same predicate as `complete_nomination`). Draft/pending
  candidacies do NOT satisfy the rule.
- **`candidates_count` is NOT authoritative.** The `*_count` columns on `elections`
  are cached projections for UI display; the invariant always queries the rows.

## What happens when the rule blocks (deliberately NOT designed)

When the window is open but no approved candidate exists, the engine chooses **no
substitute state** — derivation simply falls through to the pre-existing rules:

- Typical shape (`nomination_completed = false`): resolves to `setup_nomination`
  through existing priority 7.
- Anomalous shape (both completion flags true, still no approved candidate):
  reaches the engine's **pre-existing** `InvalidElectionStateException` backstop.

**This fall-through behaviour is a technical observation, not a business rule.**
What such an election *should* do (hold, warn, extend, close…) is the open governance
question **EM-OPEN-021** — deliberately undecided. Do not encode a fallback state,
new lifecycle state, or precedence change without that decision.

## Tests

- `tests/Feature/Election/EmVot002ApprovedCandidateBeforeVotingTest.php` — computed
  path: zero candidacies / unapproved-only / positive / `canVote` projection / edge
  shape (asserts only "never voting_active", pinning no fallback).
- `tests/Unit/Application/Election/EmVot002OpenVotingPreconditionTest.php` — command
  path: constitution spec pin, guard refusal naming the precondition, positive case.

**Fixture note:** under EM-VOT-002, a test fixture that *means* "a legitimately
voting election" must include an approved candidacy — `ElectionScenarioFactory::votingActive()`
and six local fixtures were corrected accordingly (premise corrections; no assertion
changed). When writing new voting-phase fixtures, create a `Post` + `Candidacy`
with `status='approved'`, or use the scenario factory.

## Pitfalls

- Do not "protect" only `open_voting` — the computed path bypasses it (that was the defect).
- Do not read `candidates_count` as the rule's source of truth.
- Do not resolve the EM-OPEN-021 fall-through in code; report it.
- The engine's `hasCandidatesApproved()` was dormant before this change — it is now
  load-bearing on priority 5.

**Traceability:** EM-VOT-002 (Manifesto §4a) · SD-14 ruling · PBDIGIT-64 ·
implementation-boundary audit `docs/publicdigit/reviews/2026-08-13-em-vot-002-implementation-boundary-audit.md` · commit `f2c2cc4e`.
