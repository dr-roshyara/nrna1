# Implementation Traceability Matrix (living dashboard)

**Status:** implementation-facing · the implementation dashboard (distinct from LIT-A's *research* traceability). Updated every slice/PR. All rows start **Not started**; the Architecture Review Gate (Playbook) checks this matrix before merge.
**Date:** 2026-06-26 · Legend: ☐ not started · ◑ in progress · ✅ done.

## Aggregate → governance → code → tests
| Aggregate | ADR-T | BDR | Events (Catalog v1.0) | State machine (50-07) | Repository (50-08) | Policies (50-06) | Tests (arch+behavioral) | Status |
|-----------|-------|-----|------------------------|------------------------|--------------------|------------------|--------------------------|--------|
| **Challenge** ★ | T1,T2,T11,T12 | greenfield (BDR-05) | ChallengeRaised/Admitted/Dismissed/Routed/Resolved | Raised→…→Resolved | ChallengeRepository | Standing/Content/Admissibility/Routing/StateInvariant | ☐ | ☐ |
| **Determination** ★ | T1,T2,T12 | greenfield (BDR-05) | DeterminationIssued | Draft→Issued→Final | DeterminationRepository | Authority/Legitimacy/Finality | ☐ | ☐ |
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

---
*Implementation Traceability Matrix — living dashboard linking each aggregate to its ADR-T / BDR / events / state machine / repository / policies / tests / status; greenfield Core flagged; checked at the Architecture Review Gate.*
