# 03 — The Adjudication Process Manager: core state, guards and store (WP-2)

**Step:** WP-2 (second implementation slice of the EPIC-004 roadmap) · **Decisions realized:** EPIC-004K §§3, 5, 6, 11 · ADR-T8 · ADR-T23 · Q-1 · Q-2 (horizon) · Policy 4

## Purpose

The correction loop was an **open arc** (EPIC-003 risk R-1): nothing in production carried a routed challenge to issuance. The Adjudication Process Manager is that missing **head** — and only the head. From `DeterminationIssued` onward the loop remains pure event choreography; the APM never observes or coordinates the Election/Contestation reactions (ADR-T8: no saga, no compensation).

## Where it fits

The APM is **orchestration, not an aggregate.** The Candidate-2 ruling (EPIC-004C) found that "AdjudicationProceeding" has no business identity, so:

- state and manager live in **Application** (`app/Contexts/Adjudication/Application/Process/`),
- the store is a **store, not a domain repository** (EPIC-004K §11; RMSP applies only by analogy),
- the **constitutional record remains the `Determination` aggregate** and its `DeterminationIssued` event. The PM's log is the deliberation's *working* record. **One truth, two records, one authoritative.**

## Key files

| File | Role |
|------|------|
| `Application/Process/AdjudicationProcessStatus.php` | The six business states + `isTerminal()`. The set is **closed** — a seventh case is an architectural act (ASP) |
| `Application/Process/AdjudicationProcessState.php` | Immutable state; every §6 transition and guard |
| `Application/Process/AdjudicationProcessId.php` | Process identity (required because uniqueness binds *active* processes — see below) |
| `Application/Process/Exception/IllegalProcessTransition.php` | Refused step; nothing changes |
| `Application/Process/AdjudicationProcessManager.php` | PM-1..PM-8 conduct; idempotent entry points |
| `Application/Port/AdjudicationProcessStore.php` | Four operations, no query zoo |
| `Infrastructure/Models/AdjudicationProcessModel.php` | Row (`BelongsToTenant`) |
| `Infrastructure/Persistence/AdjudicationProcessMapper.php` | Sole translation state ↔ row |
| `Infrastructure/Repositories/EloquentAdjudicationProcessStore.php` | Store realization; resolves tenant |
| `…/Migrations/Tenant/2026_07_30_000001_create_adjudication_processes_table.php` | Table + **partial** unique index |

## How it works

```php
$id = $manager->openFor($challengeRef);          // PM-1 — replay-safe
$manager->admitEvidence($challengeRef, $ref);    // PM-2 — opaque references only
$manager->submitToAuthority($challengeRef);      // basis goes to the authority
$manager->receiveRulingDecision(                 // PM-4 → PM-5: decision arrives WHOLE
    $challengeRef, $outcome, $legitimacy, $reason, $authority, $consideredEvidence,
);
```

States and transitions follow EPIC-004K §6 exactly:

```
∅ → Opened → Assembling ⇄ AwaitingDecision → Concluded-RulingRequested
                                           → Concluded-FailureDeclared
     any non-terminal ───────────────────────→ Expired
```

## Design decisions

- **The authority decides; the process receives** (K1 · Q-1 · **ADR-T23**). Nothing here computes legitimacy or sufficiency, and nothing validates a delegation — that is Governance's, solely. The decision arrives whole.
- **Exactly one conclusion, of exactly one kind — or an expiry, never a mix** (§6). Enforced by the state guards; a second or conflicting decision cannot overwrite a conclusion.
- **Conclude-time atomic fixation (PM-5):** the conclusion, the evidence-set-as-considered and the deciding authority are set in one step and persisted in one write. A partially concluded process is unrepresentable. The **permanent** fixation is the aggregate's, at issuance (**ADR-T22** / INV-4).
- **Immutable state.** Every transition returns a new instance, so "a refused step mutates nothing" is structural rather than merely asserted. Time is always injected (`ClockInterface`, ER-03/04) — never read.
- **Uniqueness binds ACTIVE processes.** PM-1 says *"open exactly one **active** process per challenge"* and §6's guard reads *"no **active** process exists"*. Hence a **partial** unique index (`WHERE status NOT IN (…terminal…)`) — the INV-B1 two-seat pattern mirrored: application guard + DB backstop. A full index would forbid what the guard permits. *This is a derived implication of approved authority; it introduces no new business rule. If the Decision Authority later rules one-process-per-challenge-forever, this implementation must be revised.* (WP-2 plan §13.)
- **Expiry is not an adjudication** (Policy 4). The horizon firing records no conclusion, no considered set and no authority. Q-2 owns the duration (bootstrap MAD 60 days); the APM only enforces it.
- **Idempotent by design.** Transports deliver at least once: a duplicate request opens no second process, and a decision for an already-concluded process is a no-op. Precedent: `GovernanceApprovalProcessManager`'s terminal guard.

## Testing

- `tests/Unit/Contexts/Adjudication/Process/AdjudicationProcessStateTest.php` — 15 tests: lawful path, exactly-one-conclusion, no-admission-after-conclusion, atomic fixation, expiry-never-concludes, closed state set.
- `tests/Unit/…/AdjudicationProcessManagerTest.php` — 5 tests: one active process, **exactly-once conclusion under redelivery**, conflicting late decision ignored.
- `tests/Feature/Contexts/Adjudication/AdjudicationProcessUniquenessTest.php` — 5 tests: persistence, the **DB-level race**, the active-scope obligation, tenant isolation.
- Written **RED first** (25 tests, 25 expected errors) before any production code.

## Pitfalls

- **Never compute sufficiency or legitimacy here** — constitutionally forbidden (K1/Q-1/ADR-T23). If you feel the urge to "help" the authority decide, stop.
- **Never treat the PM log as a second source of authority.** The determination is the constitutional record.
- Evidence references are **opaque strings**; never resolve or join them to anything voter-linked (ADR-T11/AT-Q7).
- Do not add `whereIn`/`whereNotIn` to the store's typed queries — the array forms are forwarded to the query builder and erase the model type (see `nonTerminalQuery()`).
- **Out of scope here:** the published loop-head event (WP-3), handler wiring and the crash-safe conclude→issue seam (WP-4), timer execution (WP-6). This slice deliberately registers nothing and publishes nothing.

## Traceability

EPIC-004K §§3/5/6/11 (APPROVED 2026-07-26) · `EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-2 · ADR-T8 · ADR-T22 · ADR-T23 · Q-1 · Q-2 · EPIC-004E INV-B1 · Policy 4 · work plan `.claude/plans/WP-2-apm-core.md` (§11 traceability review · §13 business assumption review).
