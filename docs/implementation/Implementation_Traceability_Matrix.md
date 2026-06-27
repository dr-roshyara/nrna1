# Implementation Traceability Matrix (living dashboard)

**Status:** implementation-facing · the implementation dashboard (distinct from LIT-A's *research* traceability). Updated every slice/PR. All rows start **Not started**; the Architecture Review Gate (Playbook) checks this matrix before merge.
**Date:** 2026-06-26 · Legend: ☐ not started · ◑ in progress · ✅ done.

## Aggregate → governance → code → tests
| Aggregate | ADR-T | BDR | Events (Catalog v1.0) | State machine (50-07) | Repository (50-08) | Policies (50-06) | Tests (arch+behavioral) | Status |
|-----------|-------|-----|------------------------|------------------------|--------------------|------------------|--------------------------|--------|
| **Challenge** ★ | T1,T2,T11,T12 | greenfield (BDR-05) | ChallengeRaised/Admitted/Dismissed/Routed/Resolved | Raised→…→Resolved | ChallengeRepository | Standing/Content/Admissibility/Routing/StateInvariant | ☐ | ☐ |
| **Determination** ★ | T1,T2,T12,T14 | greenfield (BDR-05) | DeterminationIssued ✅ | Draft→Issued→Final ✅ | DeterminationRepository interface ✅ (Eloquent ☐) | Authority/Legitimacy/Finality (in aggregate) | ✅ (31 green) | ◑ |
| **EvidenceEnvelope** | T10,T11 | BC (Operational) | EvidenceRecorded | Open→Frozen | EvidenceRepository | Recording/Immutability/HashCalc | ☐ | ☐ |
| **Vote** | T1,T3,T11 | BC (Operational) | VoteAccepted | Draft→Cast→Verified/Abandoned | VoteRepository | Eligibility/Admissibility/Anonymity | ☐ | ☐ |
| **Mandate** | T1,T6 | BC (Operational) | MandateGranted/Revoked | Active→Revoked/Expired | MandateRepository | Authority/Scope/Lifecycle | ☐ | ☐ |
| **Election/Lifecycle** | T8,T11 | BC | ElectionCorrectionApplied | …existing… →CorrectionApplied | (existing) | CorrectionType/ContainedCorrection | ☐ | ☐ |

★ greenfield Core — implement first (Slice 1).

## Cross-cutting infrastructure
| Item | ADR-T | Status |
|------|-------|--------|
| Transactional outbox wiring (`ProcessOutboxEvents`) | T3 | ☐ |
| Inbox / dedupe table (`EventId`) | T4 | ☐ |
| Deptrac config (boundaries/layers) | T7 | ☐ |
| Architecture fitness tests (Q7, single-producer, no-foreign-consumer, one-txn) | T1,T2,T7,T11 | ☐ |
| Event upcasting (version convertibility) | T5 | ☐ |
| AdjudicationService (orchestration, Option A) | T2,T14 | ✅ (in-memory, 48/48) |
| EventOutbox port (real adapter) | T3,T4 | ☐ |

## Push A — Adjudication persistence (DONE, commit 78aab60d7)
| Item | Status |
|------|--------|
| `determinations` migration (state-only; UNIQUE(org,challenge_ref)) | ✅ |
| `DeterminationModel` + `DeterminationMapper` + `EloquentDeterminationRepository` | ✅ |
| `IdentityGenerator`/`UuidIdentityGenerator`; `TransactionManager`/`LaravelTransactionManager` | ✅ |
| `TransactionalAdjudicationService` decorator (frozen coordinator untouched) | ✅ |
| `OutboxEventAdapter` → existing `OutboxEvent`/`outbox:process` (explicit payload) | ✅ |
| `AdjudicationServiceProvider` registered (config/app.php) | ✅ |
| Integration test (real DB + outbox; exactly-one assertions) | ✅ 2/2 |
| Architecture testsuite registered in phpunit.xml (F-4) | ✅ |
| PHPUnit greenfield (unit+integration+fitness) | ✅ 50/50 |
| PHPStan level max (greenfield contexts) | ✅ clean |

**Push A tracked findings (carry forward):**
- **Deptrac:** config staged; PHAR download URL 404 (asset naming changed) → resolve URL / install; boundaries enforced meanwhile by `GreenfieldCoreArchitectureTest`.
- **Infection:** installed but no coverage driver (xdebug/pcov) in this env → wire with pcov in CI.
- **7 pre-existing legacy architecture-test failures** (e.g. `Phase_C25_SovereigntyLeakagePrevention`, `VocabularyProhibition`) surfaced by registering the suite — NOT introduced by Push A; triage as separate tech-debt.
- **ADR-T18** (AggregateVersion optimistic concurrency) — still deferred.

---
*Implementation Traceability Matrix — living dashboard linking each aggregate to its ADR-T / BDR / events / state machine / repository / policies / tests / status; greenfield Core flagged; checked at the Architecture Review Gate.*
