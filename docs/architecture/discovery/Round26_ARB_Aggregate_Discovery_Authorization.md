# Round 26 — ARB Aggregate Discovery Authorization

**Date:** 2026-06-08

**Phase:** Governance Authorization — Aggregate Discovery

**Status:** Decision Recorded

---

## 1. Decision Context

**Purpose:** Determine whether aggregate discovery is authorized, define scope and rules of engagement. This is a governance authorization — no aggregate discovery, design, or implementation is performed.

**Inputs:**

| Phase | Artifacts |
|-------|-----------|
| Round 25 | ARB Context Acceptance Review |
| Rounds 24A-24G | Literature & Governance Support Package |
| Round 18 | Governance Clarification |
| Rounds 17 | Repository Discovery Corpus |
| — | Discovery Debt Register (D1-D42) |
| — | Hypothesis Register (H1-H23) |

---

## 2. Authorized Candidate Context Inventory

All decisions from Round 25 are authoritative. No boundaries are reinterpreted.

| # | Candidate | Acceptance | Boundary Stability | Evidence Strength |
|---|-----------|------------|-------------------|-------------------|
| C1 | Trust Attestation | ACCEPTED | STABLE | HIGH |
| C2 | Eligibility | ACCEPTED | STABLE | HIGH |
| C3 | Authorization | ACCEPTED | STABLE | HIGH |
| C4 | Constitutional Governance | ACCEPTED | STABLE | HIGH |
| C5 | Audit | ACCEPTED | STABLE | HIGH |
| C6 | Voting | ACCEPTED | PROVISIONAL (D42B) | HIGH |
| C7 | Results/Tallying | ACCEPTED (Provisional) | PROVISIONAL (D39) | MEDIUM |
| C8 | Governance Evidence Replay | ACCEPTED (Provisional) | PROVISIONAL (D36) | MEDIUM |
| C9 | Arbitration/Legitimacy | ACCEPTED (Provisional) | UNRESOLVED (D35/D36/D37) | MEDIUM |
| C10 | Challenge/Dispute | RECLASSIFIED | Distributed Domain Capability | LOW |

---

## 3. Aggregate Discovery Scope

| Candidate | Aggregate Discovery | Constraint |
|-----------|-------------------|------------|
| Trust Attestation | ✅ Authorized | None |
| Eligibility | ✅ Authorized | None |
| Authorization | ✅ Authorized | None |
| Constitutional Governance | ✅ Authorized | None |
| Audit | ✅ Authorized | None |
| Voting | ✅ Authorized | Boundary with Verification remains provisional (D42B) |
| Results/Tallying | ✅ Authorized | Boundary stability provisional (D39) |
| Governance Evidence Replay | ✅ Authorized | D36 invocation unresolved |
| Arbitration/Legitimacy | ✅ Authorized | D35/D36/D37 unresolved; proceed under current accepted candidate boundary |
| Challenge/Dispute | ❌ NOT AUTHORIZED | Reclassified as Distributed Domain Capability |

---

## 4. Aggregate Discovery Rules

### Authorized Activities

Aggregate discovery may identify:
- Aggregates
- Aggregate roots
- Core invariants per aggregate
- Consistency boundaries
- Decision ownership per aggregate
- Transactional boundaries per aggregate

### Forbidden Activities

Aggregate discovery may NOT identify:
- Microservices
- APIs or API contracts
- Database schemas or table designs
- Read models
- Technical architecture
- Deployment topology
- Refactoring plans
- Implementation recommendations

Commands and domain events may be noted if they emerge naturally during invariant analysis. However, command modeling, event storming, messaging design, and integration design are not authorized.

The purpose is tactical DDD discovery — identifying aggregates and their invariants.

---

## 5. Provisional Boundary Rules

For candidates with PROVISIONAL or UNRESOLVED boundary stability:

1. **Aggregate candidates may be identified** within the current candidate boundary.
2. **Findings must not assume final boundary stability.** If an aggregate's responsibilities would change under a different boundary configuration, that must be documented.
3. **Aggregates that cross provisional boundaries must be flagged** for review when the boundary condition (D39, D42B, D36, D35/D36/D37) is resolved.

No merger or split recommendations are authorized during aggregate discovery.

---

## 6. Exit Criteria

Aggregate discovery is complete when, for each accepted candidate:

| Criterion | Description |
|-----------|-------------|
| Aggregate roots identified | The primary aggregate roots within the context are identified |
| Aggregate responsibilities identified | What each aggregate owns and is responsible for |
| Core invariants identified | Business rules that must always be maintained |
| Decision ownership mapped | Which aggregate owns which decisions |
| Consistency boundaries understood | Transactional boundaries within the context |
| Provisional boundaries flagged | Any aggregate that crosses a provisional boundary is documented |

No implementation guidance is required. No event storming is required. No service design is required.

---

## 7. ARB Decision

### Is Aggregate Discovery Authorized?

**Decision: YES**

### Authorized Contexts (9)

1. Trust Attestation
2. Eligibility
3. Authorization
4. Constitutional Governance
5. Audit
6. Voting (provisional boundary)
7. Results/Tallying (provisional boundary)
8. Governance Evidence Replay (provisional boundary)
9. Arbitration/Legitimacy (provisional boundary)

### Excluded Contexts (1)

10. Challenge/Dispute — Distributed Domain Capability, not a bounded context

### Constraints

- Provisional boundary rules (Section 5) apply to Voting, Results/Tallying, Governance Evidence Replay, and Arbitration/Legitimacy.
- No merger or split recommendations are authorized.
- No technical architecture, events, commands, or implementation recommendations.

### Exit Criteria

As defined in Section 6. Complete per-candidate when aggregate roots, responsibilities, invariants, decision ownership, and consistency boundaries are identified.

---

**Round 26 ARB Aggregate Discovery Authorization — DECISION RECORDED**

**Aggregate discovery is authorized for 9 accepted candidate contexts. Aggregate discovery is NOT authorized for Challenge/Dispute. Provisional boundary rules apply to 4 contexts. No architecture, events, commands, or implementation work is authorized. Exit criteria defined.**
