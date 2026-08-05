# Strategic Context Map

**Date:** 2026-06-13  
**Phase:** Round 6 — Strategic DDD Mapping  
**Status:** Revised per Evidence Register (ROUND6_EVIDENCE_REGISTER.md)  
**Evidence Basis:** All frontend + backend discoveries, filtered by PROVEN/CANDIDATE/UNRESOLVED

## Evidence Register Gate

Every claim in this document is tagged with its evidence status:

- ✅ **PROVEN** — High confidence, multiple independent sources
- ⚠️ **CANDIDATE** — Evidence exists, not yet definitive
- ❓ **UNRESOLVED** — Competing interpretations, insufficient evidence
- ❌ **REJECTED** — Evidence contradicts claim

---

## Context Map

```
┌──────────────────────────────────────────────────────────────────┐
│                     ELECTION GOVERNANCE                         │
│                   (Core Domain — PROVEN)                        │
│                                                                  │
│     Aggregate Root: Election (PROVEN)                            │
│     ├── transitionTo() via Guard → Constitution                 │
│     ├── State write barrier                                     │
│     └── Cache::lock() for concurrency                           │
│                                                                  │
│     Ubiquitous Language: 14 constitutional actions (PROVEN)     │
│     ├── submit_for_approval, approve, reject, auto_submit       │
│     ├── begin_setup, revise_and_resubmit                        │
│     ├── complete_administration, complete_nomination            │
│     ├── apply_candidacy, open_voting, close_voting               │
│     ├── publish_results, archive                                │
│     ├── suspend, resume                                         │
│                                                                  │
│     Domain Services (PROVEN):                                    │
│     ├── ConstitutionalTransitionGuard                            │
│     ├── ElectionLifecycleEngine                                  │
│     └── ElectionCapabilityResolver                              │
│                                                                  │
│     Capability: capability snapshots → frontend (PROVEN)        │
│     Ownership: eligibility rules (PROVEN)                        │
│                                                                  │
│     Trust artifacts UNDER this context (UNRESOLVED):             │
│     ├── TrustPolicyEvaluator                                    │
│     ├── ReplaySession (CANDIDATE aggregate)                     │
│     ├── Evidence classification                                 │
│     └── Contexts/Trust/ directory (? independent?)              │
└──────────────────────────────────────────────────────────────────┘
                              │
                              │ publishes capability snapshots
                              ▼
┌──────────────────────────────────────────────────────────────────┐
│                        VOTING                                    │
│              (Core Domain — CANDIDATE)                           │
│                                                                  │
│     Aggregate Root: NONE — VotingSession is Domain Snapshot ✅   │
│                                                                  │
│     Domain Services:                                             │
│     ├── VotingEngine                                             │
│     ├── EligibilityEvaluator (consumes pre-computed snapshot)    │
│     ├── VoteAggregator                                          │
│     └── QuorumCalculator                                         │
│                                                                  │
│     Value Objects:                                               │
│     ├── BallotCollection                                         │
│     ├── VotingOutcome                                            │
│     ├── EligibilitySnapshot                                      │
│     └── QuorumDefinition                                         │
│                                                                  │
│     Own Persistence: votes, results tables                       │
│     Own Language: ✓                                              │
│     Own Events:    ✗ (none found)                                │
│     Eligibility:   ❌ consumed, not owned (PROVEN)               │
│                                                                  │
│     Status: Separability depends on eligibility ownership        │
│     and event model maturity.                                    │
└──────────────────────────────────────────────────────────────────┘
                              │
                              │ tenants
                              ▼
┌──────────────────────────────────────────────────────────────────┐
│                     ORGANISATION                                 │
│             (Generic/Supporting — CANDIDATE)                     │
│                                                                  │
│     Aggregate Root: Unknown (no analysis performed)              │
│                                                                  │
│     Owns: tenant identity, governance structure, geo scope       │
│     Relationship: Elections belong-to Organisation               │
│     Not Core: generic multi-tenant tenant root                   │
│                                                                  │
│     Classification needs aggregate boundary analysis.            │
└──────────────────────────────────────────────────────────────────┘
                              │
                              │ voters
                              ▼
┌──────────────────────────────────────────────────────────────────┐
│                     MEMBERSHIP                                   │
│             (Supporting — CANDIDATE)                             │
│                                                                  │
│     Aggregate Root: Unknown (no analysis performed)              │
│                                                                  │
│     Owns: voter status, expiration, suspension, role              │
│     Relationship: Member belongs-to Organisation,                │
│                   Member participates-in Election                │
│                                                                  │
│     Eligibility determined here and published to Voting.         │
│     Classification needs aggregate boundary + events analysis.   │
└──────────────────────────────────────────────────────────────────┘
```

## Context Relationship Summary

| Context | Classification | Evidence Status |
|---------|---------------|-----------------|
| Election Governance | Core Domain — Confirmed Bounded Context | ✅ **PROVEN** |
| Voting | Core Domain — Candidate Bounded Context | ⚠️ **CANDIDATE** |
| Trustworthiness | Supporting Subdomain — Classification UNRESOLVED | ❓ **UNRESOLVED** |
| Organisation | Generic/Supporting — Candidate | ⚠️ **CANDIDATE** |
| Membership | Supporting Subdomain — Candidate | ⚠️ **CANDIDATE** |

## Status of Prior Round 6 Documents

| Deliverable | Status | Next Action |
|-------------|--------|-------------|
| ROUND6_EVIDENCE_REGISTER.md | ✅ Complete | Gate document — approve before proceeding |
| ELIGIBILITY_OWNERSHIP_ANALYSIS.md | ✅ Complete | Confirms Governance owns eligibility |
| CONTEXT_MAP.md | ✅ Revised per register | Ready for review |
| BOUNDED_CONTEXT_ASSESSMENT.md | ⏸ Deferred | Requires Evidence Register approval |
| ARCHITECTURE_HEALTH_REPORT.md | ⏸ Next | After context map approval |
