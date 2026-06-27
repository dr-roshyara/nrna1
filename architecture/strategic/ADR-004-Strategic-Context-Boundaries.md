# ADR-004: Strategic Context Boundaries

**Status:** Accepted  
**Date:** 2026-06-13  
**Context:** Rounds 1–6 of architecture discovery completed. Evidence consolidated through Frontend Discovery Phase, Backend Discovery Phase, Strategic Discovery Phase, ROUND6_EVIDENCE_REGISTER, BOUNDED_CONTEXT_ASSESSMENT, ARCHITECTURE_HEALTH_REPORT, and ARG-01 Architecture Review Gate.

This ADR does not introduce new architectural theories, refactorings, or target-state designs. Its purpose is to formally record the context classifications approved by ARG-01.

## Decision

The following context classifications are adopted.

### Confirmed Context

**Election Governance**

| Criterion | Evidence |
|-----------|----------|
| Aggregate ownership | Proven — Election meets 4/4 criteria |
| Invariant ownership | Proven — Constitution SSOT, state write barrier, timeline chronology |
| Event ownership | Proven — 10 explicit domain events |
| Context boundary | Proven — Constitution → Guard → Engine → Resolver → Snapshot chain |
| Ubiquitous language | Proven — 14 constitutional actions aligned across backend and frontend |
| Confidence | Very High |

### Candidate Contexts

**Voting** — Status: Candidate. Independent language, services, invariants, and persistence exist, but aggregate ownership and context autonomy are not yet proven.

**Membership** — Status: Candidate. Insufficient aggregate, event, and ownership analysis.

**Organisation** — Status: Candidate. Insufficient aggregate and context-boundary analysis.

### Unresolved Context

**Trustworthiness** — Status: Unresolved. Evidence supports both supporting-subdomain interpretation (embedded in Governance, consumed by policy chain) and independent-context interpretation (own language, events, state machine). Further evidence required.

### Rejected Hypotheses

| Hypothesis | Rejection Basis |
|-----------|-----------------|
| VotingSession is an aggregate | 0.5/4 criteria — immutable domain snapshot |
| Trust is a peer bounded context | All artifacts depend on Governance models |
| Election is a God Object | 36 methods, most delegate to Constitution + Guard + Engine |

## Consequences

**Authorized:**
- Continue evidence-based discovery
- Continue capability-driven frontend architecture (frontend visualizes authority, does not determine it)
- Continue constitutional governance model (Constitution as SSOT)

**Not Authorized:**
- Context extraction or decomposition
- Microservice decomposition
- Aggregate refactoring
- Trust context separation
- Voting context promotion

Such decisions require additional evidence and a future ADR.

## Architectural Principle

Context status is determined by evidence. Discovery evidence supersedes architectural preference. Candidate and unresolved contexts shall not be treated as confirmed architectural boundaries.

## Related

- ARG-01 Architecture Review Gate
- ROUND6_EVIDENCE_REGISTER
- [ADR-005: Language Ownership & Governance](ADR-005-Language-Ownership-and-Governance.md)
