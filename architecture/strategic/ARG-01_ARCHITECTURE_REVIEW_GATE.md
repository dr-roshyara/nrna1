# ARG-01: Architecture Review Gate

**Date:** 2026-06-13  
**Phase:** Strategic DDD Assessment — Gate Review  
**Purpose:** Convert discoveries into architectural confidence decisions  
**Next Step:** ADR-004 (Strategic Context Boundaries) — only after this gate is approved

## Decision Matrix

| Context | Current Status | Evidence Strength | ARG Decision |
|---------|---------------|-------------------|--------------|
| **Election Governance** | Confirmed | Very High — 7 independent evidence sources | ✅ **CONFIRMED** |
| **Voting** | Candidate | Medium — own language/services/persistence, but no events, no aggregate, eligibility owned by Governance | ⏸ **REMAIN CANDIDATE** |
| **Trustworthiness** | Unresolved | Medium — independent language + events, but embedded in Governance domain | ❓ **REMAIN UNRESOLVED** |
| **Membership** | Candidate | Low-Medium — eligibility source, no aggregate analysis, no events, no ownership analysis | ⏸ **REMAIN CANDIDATE** |
| **Organisation** | Candidate | Low-Medium — tenant root, no aggregate or ownership analysis | ⏸ **REMAIN CANDIDATE** |

## Promotion Criteria

A candidate context may be promoted to CONFIRMED only if ALL of the following are proven via discovery evidence:

| Criterion | Election Governance | Voting | Trustworthiness | Membership | Organisation |
|-----------|-------------------|--------|-----------------|------------|-------------|
| Aggregate ownership | ✅ Proven | ❌ Domain Snapshot only | ⚠️ Candidate (2.5/4) | ❓ Unknown | ❓ Unknown |
| Invariant ownership | ✅ Proven | ✅ Ballot anonymity + deterministic tally | ✅ Evidence immutability + replay determinism | ⚠️ Voter eligibility | ⚠️ Multi-tenant isolation |
| Event ownership | ✅ Proven (10 events) | ❌ None found | ✅ ReplaySessionOpened, Certified, Diverged | ❌ None found | ❌ None found |
| Context boundary | ✅ Proven | ❌ Eligibility dependency | ❌ Embedded in Governance domain | ❓ Unknown | ❓ Unknown |
| Ubiquitous language | ✅ Proven (14 actions) | ✅ Ballot, Outcome, Quorum | ✅ Replay, Certification, Evidence | ⚠️ Membership, Status, Role | ⚠️ Organisation, Governance |

**Result:** Only Election Governance satisfies all promotion criteria.

## Review Record

| Context | ARG Finding | Basis |
|---------|-------------|-------|
| Election Governance | **CONFIRMED** — no further discovery required for context status | 7/7 evidence sources. Aggregate (4/4), Constitution SSOT, capability language, eligibility ownership, lifecycle derivation, backend→frontend bridge, events |
| Voting | **REMAIN CANDIDATE** — requires events + aggregate resolution before promotion | Meets 3/5 criteria. No aggregate, no events. Eligibility dependency is acceptable for upstream/downstream pattern. |
| Trustworthiness | **REMAIN UNRESOLVED** — competing interpretations persist | Meets 3/5 criteria. Has language + events + invariants, but persistence + boundary depend on whether it evolves independently of Governance. |
| Membership | **REMAIN CANDIDATE** — aggregate + events analysis needed | Satisfies 1/5 criteria. No aggregate, events, or boundary analysis performed. |
| Organisation | **REMAIN CANDIDATE** — aggregate + context analysis needed | Satisfies 1/5 criteria. Tenant root pattern suggests Generic Subdomain, but no formal analysis performed. |

## Context Statuses After ARG-01

```
┌─────────────────────────────────────────────────────────────┐
│                                                               │
│   CONFIRMED (1)                                              │
│     Election Governance                                       │
│                                                               │
│   CANDIDATE (3)                                               │
│     Voting                                                     │
│     Membership                                                 │
│     Organisation                                               │
│                                                               │
│   UNRESOLVED (1)                                              │
│     Trustworthiness                                            │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

## Binding Decisions

1. **Frontend may continue consuming capability snapshots.** Backend is confirmed as the authority source for all governance, eligibility, and lifecycle decisions. Frontend must not derive authority.

2. **No Voting bounded-context promotion without events.** Voting must demonstrate independent event ownership before being promoted from Candidate.

3. **Trustworthiness re-evaluation required.** If `Contexts/Trust/` grows beyond 1 event or ReplaySession gains persistence, schedule a re-review.

4. **Membership and Organisation require aggregate analysis** before further classification decisions.

## Authorized Next Steps

| Order | Action | Rationale |
|-------|--------|-----------|
| 1 | **ADR-004: Strategic Context Boundaries** | Documents this gate's decisions formally. Only Election Governance as confirmed. |
| 2 | **ADR-005: Language Ownership & Governance** | Formally establishes Constitution as SSOT and enum synchronization policy. |
| 3 | **Voting events discovery** | Only if Voting promotion is desired. Not urgent. |
| 4 | **Trustworthiness re-evaluation trigger** | Only if `Contexts/Trust/` grows or ReplaySession gains persistence. |

## What This Gate Does NOT Authorize

- ❌ No refactoring of Election.php
- ❌ No microservice boundaries
- ❌ No migration to separate context directories
- ❌ No ADR-006 (Modularization Strategy) — too early
- ❌ No frontend domain extraction beyond existing patterns
