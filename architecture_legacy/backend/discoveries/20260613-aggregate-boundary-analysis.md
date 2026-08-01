# Discovery: Aggregate Boundary Analysis

**Date:** 2026-06-13  
**Phase:** Phase 2 — Backend Domain Discovery (Round 5D)  
**Status:** Complete

## Three Aggregate Candidates

| Candidate | File | Type | Evidence |
|-----------|------|------|----------|
| **Election** | `Models/Election.php` | Eloquent Model + Domain Logic | 2279 lines, 36 methods, state write barrier, `transitionTo()` entry point |
| **VotingSession** | `Domain/Voting/Aggregate/VotingSession.php` | Pure domain object | Immutable, readonly, computed, `fingerprint()` |
| **ReplaySession** | `Domain/Election/Replay/ReplaySession.php` | Stateful domain object | State machine (4 states), guards transitions, owns invariants |

## Criterion 1: Protects Invariants

| Candidate | Invariants Protected | Assessment |
|-----------|---------------------|------------|
| **Election** | Constitutional fields immutable, state writes require authorization, timeline chronological order | ✅ Strong — defense in depth (Constitution + Guard + Model hooks) |
| **VotingSession** | Ballots normalized by voter ID, results deterministic | ⚠️ Weak — invariants held at construction, no ongoing protection |
| **ReplaySession** | One certification cycle per session, evidence frozen at creation, certification immutable | ✅ Strong — state machine guards, exceptions on invalid transitions |

## Criterion 2: Receives Commands

| Candidate | Commands | Assessment |
|-----------|----------|------------|
| **Election** | `submitForApproval()`, `approve()`, `reject()`, `openVoting()`, `closeVoting()`, `suspend()`, `resume()` etc. | ✅ Strong — 10+ commands, all through `transitionTo()` |
| **VotingSession** | None — constructed in `VotingEngine::execute()` | ❌ No commands |
| **ReplaySession** | `recordAssertion()`, `certify()` | ✅ Medium — 2 commands with state guards |

## Criterion 3: Changes State

| Candidate | State Changes | Assessment |
|-----------|--------------|------------|
| **Election** | `state` column + phase flags + timestamps | ✅ Strong — `transitionTo()` is the single entry point |
| **VotingSession** | None — `final readonly` | ❌ Immutable |
| **ReplaySession** | `$state` property changes through lifecycle | ✅ Medium — 4 states with enforced ordering |

## Criterion 4: Owns Consistency Boundary

| Candidate | Consistency Boundary | Assessment |
|-----------|---------------------|------------|
| **Election** | `transitionTo()` uses `Cache::lock()` for pessimistic locking | ✅ Strong — protects against concurrent transitions |
| **VotingSession** | No boundary needed — pure function | ❌ No boundary |
| **ReplaySession** | In-memory state guards, no persistence locking | ⚠️ Medium — state guards exist but no cross-process locking |

## Aggregate Classification

| Candidate | Score | Classification |
|-----------|-------|---------------|
| **Election** | 4/4 | ✅ **Aggregate Root** — protects invariants, receives commands, changes state, owns consistency boundary |
| **ReplaySession** | 2.5/4 | ⚠️ **Aggregate Candidate** — stateful with invariants, but no persistence boundary. May become a true aggregate when persisted. |
| **VotingSession** | 0.5/4 | ❌ **Domain Snapshot / Decision Artifact** — immutable, computed, no commands, no state |

## Final Aggregate Map

```
┌─────────────────────────────────────────────────────────────┐
│                    ELECTION GOVERNANCE                        │
│                                                               │
│  Aggregate Root: Election                                     │
│    ├── Owns: state, lifecycle, approval, suspension          │
│    ├── Protects: constitutional invariants, timeline, roles  │
│    └── Entry point: transitionTo() via Guard                 │
│                                                               │
│  Aggregate Candidate: ReplaySession                          │
│    ├── Owns: evidence → assertion → certification lifecycle  │
│    ├── Protects: one cycle per session, evidence immutability│
│    └── TODO: Needs persistence and cross-process locking     │
│                                                               │
│  Domain Snapshot: VotingSession                              │
│    ├── Computed outcome of VotingEngine::execute()           │
│    ├── Immutable, deterministic, replayable                 │
│    └── Not an aggregate — no commands, no state changes      │
│                                                               │
│  Supporting: TrustPolicyEvaluator                            │
│  Supporting: ReplayCertification                              │
│  Supporting: EvidenceClassification                          │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────┐
│   VOTING (Bounded            │
│   Context Candidate)         │
│   (awaiting eligibility      │
│    ownership analysis)       │
│                               │
│  Domain Service:              │
│    VotingEngine               │
│  Value Objects:               │
│    BallotCollection           │
│    VotingOutcome              │
│    EligibilitySnapshot        │
│  Policies:                    │
│    QuorumRule                 │
│    EligibilityEvaluator       │
└─────────────────────────────────┘
```

## Architectural Impact

| Claim | Status |
|-------|--------|
| `Election` is the aggregate root | ✅ **Confirmed** — meets all 4 criteria |
| `ReplaySession` is an aggregate | ⚠️ **Candidate** — stateful with invariants, needs persistence boundary |
| `VotingSession` is an aggregate | ❌ **Rejected** — immutability + no commands = Domain Snapshot |
| Voting is a separate bounded context | ⚠️ **Candidate** — has its own services/value objects but eligibility ownership (EligibilitySnapshot source) must be determined before classification can be finalized |
| Trust is separate from Governance | ❌ **Rejected** — Supporting Subdomain within Election Governance |

## Round 5 Complete

All 5 rounds of Phase 2 — Backend Domain Discovery are complete. The architecture framework now contains a comprehensive evidence-based map of aggregate boundaries, context relationships, and ownership.
