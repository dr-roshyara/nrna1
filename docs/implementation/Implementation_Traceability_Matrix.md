# Implementation Traceability Matrix (living dashboard)

**Status:** implementation-facing · the implementation dashboard (distinct from LIT-A's *research* traceability). Updated every slice/PR. The Architecture Review Gate + `Architecture_Review_Checklist.md` check this matrix before EVERY merge — no PR merges without a matching row.
**Date:** 2026-06-26 · rev 2026-07-06 (maturity scale per ARB)
**Maturity scale (replaces binary status):** `Designed → Approved → Implemented → Verified → Production`. Legacy marks map as: ☐ = not yet Designed/at Designed · ◑ = Approved or partially Implemented · ✅ = Implemented+Verified (pre-Production).

## Aggregate → governance → code → tests
| Aggregate | ADR-T | BDR | Events (Catalog v1.0) | State machine (50-07) | Repository (50-08) | Policies (50-06) | Tests (arch+behavioral) | Status |
|-----------|-------|-----|------------------------|------------------------|--------------------|------------------|--------------------------|--------|
| **Challenge** ★ | T1,T2,T11,T12 | greenfield (BDR-05) | ChallengeRaised/Admitted/Dismissed/Routed/Resolved | Raised→…→Resolved | ChallengeRepository | Standing/Content/Admissibility/Routing/StateInvariant | ☐ | ☐ |
| **Determination** ★ | T1,T2,T12,T14 | greenfield (BDR-05) | DeterminationIssued ✅ | Draft→Issued→Final ✅ | DeterminationRepository ✅ (interface + Eloquent, Push A) | Authority/Legitimacy/Finality (in aggregate) | ✅ (unit + integration) | ✅ (Push A) |
| **EvidenceEnvelope** | T10,T11 | BC (Operational) | EvidenceRecorded | Open→Frozen | EvidenceRepository | Recording/Immutability/HashCalc | ☐ | ☐ |
| **Vote** | T1,T3,T11 | BC (Operational) | VoteAccepted | Draft→Cast→Verified/Abandoned | VoteRepository | Eligibility/Admissibility/Anonymity | ☐ | ☐ |
| **Mandate** | T1,T6 | BC (Operational) | MandateGranted/Revoked | Active→Revoked/Expired | MandateRepository | Authority/Scope/Lifecycle | ☐ | ☐ |
| **Election/Lifecycle** | T8,T11 | BC | ElectionCorrectionApplied | …existing… →CorrectionApplied | (existing) | CorrectionType/ContainedCorrection | ☐ | ☐ |

★ greenfield Core — implement first (Slice 1).

## Cross-cutting infrastructure
| Item | ADR-T | Status |
|------|-------|--------|
| Transactional outbox wiring — producer side (`OutboxEventAdapter` + `outbox:process`) | T3 | ✅ (Push A) — relay hydration registry ☐ (Push B step 5; see Blueprint G-1) |
| Inbox / dedupe table (`event_id`, `consumer_context`) | T4 | ☐ (Push B step 6; Blueprint §6) |
| Deptrac config (boundaries/layers) | T7 | ◑ (config staged; PHAR install pending — F-1) |
| Architecture fitness tests (Q7, single-producer, no-foreign-consumer, one-txn) | T1,T2,T7,T11 | ✅ (suite registered F-4; **131/131 green 2026-07-06**; legacy debt AD-001..005 cleared) |
| Event upcasting (version convertibility) | T5 | ☐ |
| AdjudicationService (orchestration, Option A) | T2,T14 | ✅ (in-memory 48/48 + Eloquent integration, Push A) |
| EventOutbox port (real adapter) | T3,T4 | ✅ (Push A: `OutboxEventAdapter`) |

## Push B — Correction loop (Blueprint v1.0 FROZEN 2026-07-06; gate record Blueprint §20)
| Item | Blueprint § | Maturity |
|------|-------------|----------|
| Blueprint v1.0 + Decision Log + Review Checklist | all | **Approved** (ARB 2026-07-06; changes only via v1.1) |
| Event Registry (hydrators per context) | §6 | **Verified** (2026-07-06: `EventHydrator` + `EventHydratorRegistry` + `UnregisteredEventType` [Shared] · `DeterminationIssuedHydrator` [Adjudication] · singleton in AppServiceProvider, registration in AdjudicationServiceProvider::boot; 12 tests green, PHPStan max clean, Architecture suite 131/131) |
| Relay Registry refactor (G-1) | §6 | **Verified** (2026-07-06: `OutboxEventProcessor` delegates to `EventHydratorRegistry`; hardcoded match + `hydrateFeePaid` DELETED; `FeePaidHydrator` migrated to Membership + registered in MembershipServiceProvider::boot; `UnregisteredEventType` → immediate dead-letter, no retry [§7 F2]; delegation-once test + dead-letter test + `EventRegistryCompletenessTest` [2 arch tests]; 14 outbox tests green, greenfield PHPStan clean, Arch suite 133/133) |
| Inbox (`inbox_events` + wrapper + park/re-drive) (G-2) | §6 | Designed |
| Election Reaction (`ElectionCorrectionApplied` + `ApplyCorrection`) (G-3) | §2/§3 | Designed |
| Contestation infra (repo/migration/provider) + reactions T4/T5/T5' (G-4) | §2/§5 | Designed |
| Integration tests IT-1..IT-8 | §9 | Designed |
| Merge Gate (Arch + Security gates, F-1 Deptrac, F-2 Infection) | §17/§20 | Designed |

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
