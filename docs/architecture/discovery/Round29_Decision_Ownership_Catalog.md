# Round 29 — Decision Ownership Catalog

**Date:** 2026-06-08

**Phase:** Strategic-to-Tactical Synthesis

**Status:** Authoritative Decision Ownership Map

**Purpose:** Document who owns each core business decision. This is a discovery catalog, not an architecture plan. For each decision: who owns it, what evidence supports ownership, what invariant is protected, what remains unresolved.

**Governance Rule:** Every statement classified as DISCOVERED, PROVISIONAL, or UNRESOLVED. No forward-looking language (should, will, may, foundation for, enables future).

---

## Core Business Decisions (D1–D8)

### D1: Is This Identity Trustworthy?

**Classification: DISCOVERED**

**1. Who Owns the Decision?**

Trust Attestation Context

**2. What Evidence Supports Ownership?**

- ADR-001 defines trust attestation as core domain decision
- VoterVerification model implements aggregate pattern
- Officer-led attestation process documented in UBIQUITOUS_LANGUAGE.md
- Verification entity observed with active/revoked lifecycle

**3. What Invariant Is Protected?**

One active verification per (participant, organization); revocation requires officer attribution; decision is immutable once recorded.

**4. What Remains Unresolved?**

None affecting this decision ownership.

---

### D2: Is This Participant Eligible for This Process?

**Classification: DISCOVERED**

**1. Who Owns the Decision?**

Eligibility Context

**2. What Evidence Supports Ownership?**

- ADR-002 explicitly separates Eligibility from Verification and Authorization
- Stream 5 investigation confirmed eligibility is evaluated at action time
- Stateless computation pattern observed (no stored eligibility state)
- ParticipationEligibilityEvidence created for audit trail only

**3. What Invariant Is Protected?**

Eligibility is computed fresh for each action; no cached eligibility state; evaluation is deterministic.

**4. What Remains Unresolved?**

None affecting this decision ownership.

---

### D3: Is This User Allowed to Perform This Action in This Context?

**Classification: DISCOVERED (ownership), PROVISIONAL (candidate aggregate)**

**1. Who Owns the Decision?**

Authorization Context

**2. What Evidence Supports Ownership?**

- ADR-001 defines authorization as distinct from eligibility and verification
- ConstitutionalTransitionGuard implements authorization checks
- CapabilityResolver serves as central authority (mechanism, not owner)
- ADR-004 documents deterministic resolver pattern

**3. What Invariant Is Protected?**

Authorization resolution is deterministic; central authority is sole decision-maker; no bypass mechanisms exist.

**4. What Remains Unresolved?**

- ADC-1 (PROVISIONAL) — Role uniqueness and exclusivity rules not yet established
- ADC-2 (PROVISIONAL) — Temporal validity and revocation authority not yet established
- RoleAssignment is a candidate aggregate pending ADC-1, ADC-2 resolution

---

### D4: What Is the Valid Election Lifecycle State?

**Classification: DISCOVERED**

**1. Who Owns the Decision?**

Constitutional Governance Context

**2. What Evidence Supports Ownership?**

- ElectionConstitution defines state transition rules (immutable)
- ConstitutionalTransitionGuard enforces preconditions
- Round 27D aggregate discovery confirmed GovernanceState pattern
- Governance rules are centralized and non-bypassable

**3. What Invariant Is Protected?**

State transitions are deterministic; preconditions are enforced centrally; state changes are atomic with validation.

**4. What Remains Unresolved?**

- Exact lifecycle inventory and sequence documented in governance artifacts (not finalized by discovery)
- ADG-2 (Delegated authority governance)
- ADH-1 (Authority hierarchy governance gaps)

---

### D5: Is This Vote Valid and Anonymous?

**Classification: DISCOVERED**

**1. Who Owns the Decision?**

Voting Context

**2. What Evidence Supports Ownership?**

- Stream 3 investigation confirmed vote recording is core business decision
- Vote aggregate implements anonymity (no user_id column)
- Vote hash ensures uniqueness
- Data checksum provides integrity protection
- Receipt hash enables voter verification

**3. What Invariant Is Protected?**

Vote anonymity (no user_id linkage); vote uniqueness (vote_hash); vote integrity (data_checksum); ballot atomicity (all selections recorded together).

**4. What Remains Unresolved?**

- D42B (UNRESOLVED) — Verifiability guarantee ownership: Does Verification context or Voting context own the guarantee that votes are verifiable? Receipt hash exists (Vote owns this). Guarantee scope (UNRESOLVED).
- D39 (UNRESOLVED) — Whether result computations triggered by vote recording belong inside Voting aggregate or separate Results/Tallying context.

---

### D6: What Operational Evidence Must Be Preserved?

**Classification: DISCOVERED**

**1. Who Owns the Decision?**

Audit Context

**2. What Evidence Supports Ownership?**

- Stream 4 investigation confirmed audit is append-only observability
- ElectionAuditLog has no UPDATE/DELETE operations
- Audit never affects business outcomes
- Fire-and-forget pattern confirmed across all contexts

**3. What Invariant Is Protected?**

Auditability (all actions recorded); evidence immutability (logs are append-only); no business coupling (logs don't affect decisions).

**4. What Remains Unresolved?**

None affecting this decision ownership.

---

### D7: Was Evidence Integrity Preserved? Does Replay Match Original?

**Classification: PROVISIONAL (candidate)**

**1. Who Owns the Decision?**

Governance Evidence Replay Context (candidate)

**2. What Evidence Supports Ownership?**

- Stream 4 investigation identified replay capability
- GovernanceReplayService orchestrates replay operations
- ReplayCertification artifacts exist
- Evidence envelopes seal data

**3. What Invariant Is Protected?**

Evidence seals are immutable (PROVISIONAL); replay outcomes are deterministic (PROVISIONAL); divergence detection is reliable (PROVISIONAL).

**4. What Remains Unresolved?**

- ADGR-1 (UNRESOLVED) — Replay session governance and operational authority. Who can invoke replays? What are consequences of divergence detection? How is certification used?
- Governance consequences of ReplaySession remain unresolved.

---

### D8: Is This Governance Decision Constitutionally Valid?

**Classification: PROVISIONAL (candidate)**

**1. Who Owns the Decision?**

Arbitration / Legitimacy Context (candidate)

**2. What Evidence Supports Ownership?**

- ConstitutionalArbitrationKernel applies predetermined constitutional rules
- LegitimacyEvaluator evaluates authority classifications
- ConflictResolutionPolicy arbitrates conflicting authorities
- GovernanceLegitimacy enum records legitimacy status

**3. What Invariant Is Protected?**

Constitutional rules are applied deterministically; legitimacy status is recorded; decision record documents rule application.

**4. What Remains Unresolved?**

- D35 (UNRESOLVED) — Legitimacy consequences. When legitimacy = EXPIRED, what happens? (Reversal, blocking, advisory only?)
- D36 (UNRESOLVED) — Invocation authority. Who is permitted to invoke ConstitutionalArbitrationKernel?
- D37 (UNRESOLVED) — Enforcement mechanisms. How are legitimacy determinations enforced?
- ADH-1 (UNRESOLVED) — Authority hierarchy governance impacts arbitration decision authority.

---

## Decision Ownership Confidence Matrix

| Decision | Owner | Classification | Confidence |
|----------|-------|-----------------|-----------|
| D1 | Trust Attestation | DISCOVERED | HIGH |
| D2 | Eligibility | DISCOVERED | HIGH |
| D3 | Authorization | DISCOVERED | HIGH |
| D4 | Constitutional Governance | DISCOVERED | MEDIUM-HIGH |
| D5 | Voting | DISCOVERED | HIGH |
| D6 | Audit | DISCOVERED | HIGH |
| D7 | Governance Evidence Replay | PROVISIONAL | MEDIUM |
| D8 | Arbitration / Legitimacy | PROVISIONAL | MEDIUM |

**Key Insight:** 6 of 8 decisions have high or medium-high confidence in ownership. D7 and D8 remain provisional pending governance debt resolution (ADGR-1, D35, D36, D37).

---

## Decision Ownership Patterns

### Pattern 1: Core Business Decisions (D1, D2, D3, D5)

**Ownership:** Clear and non-negotiable
**Status:** DISCOVERED with HIGH confidence
**Evidence:** Strong from domain artifacts and code
**Invariants:** Well-defined and enforceable
**Observed Pattern:** These four decisions are associated with the strongest aggregate evidence identified during discovery.

---

### Pattern 2: Governance Decisions (D4, D6)

**Ownership:** Clear
**Status:** DISCOVERED
**Evidence:** Strong from constitutional rules and implementation
**Invariants:** Well-defined
**Governance Consequence:** D4 gates all operational decisions; D6 is fire-and-forget.

---

### Pattern 3: Verification Decisions (D7, D8)

**Ownership:** Identified but governance incomplete
**Status:** PROVISIONAL
**Evidence:** Mechanisms exist but invocation and consequences unresolved
**Invariants:** Candidates only
**Governance Consequence:** Cannot be finalized until D35, D36, D37, ADGR-1 resolved.

---

## Decision Dependency Graph

```
D1: Identity trustworthy?
    ↓ [read by]
D2: Participant eligible?
    ↓ [read by]
D3: Action allowed?
    ↓ [enforces preconditions from]
D4: Lifecycle state valid?
    ↓ [allows]
D5: Vote valid & anonymous?
    ↓ [observed implementation relationship]
    Result-related computations (D39 unresolved)

D6: Evidence preserved?
    ↓ [fire-and-forget from all decisions]

D7: Replay matches original?
    ↓ [potential relationship - unresolved ADGR-1]
D8: Decision constitutional?
    ↓ [consequences unresolved - D35, D36, D37]
```

**Critical Path:** D1 → D2 → D3 → D4 → D5 must all succeed before vote is recorded.

**Unresolved Paths:** D7 ↔ D8 relationship and consequences not yet established.

---

## What Was NOT Discovered

**Important:** Decisions discovered through rounds 17–28A are listed above.

**Not discovered in this program:**

```text
Domain events
Command definitions
Query specifications
API endpoints
Technical architecture
Implementation decisions
```

Those belong in later tactical design phases after synthesis is approved.

---

## Summary: Discovery Discipline

This catalog documents what discovery found:
- **DISCOVERED:** 6 decisions with clear ownership
- **PROVISIONAL:** 2 decisions with identified owners but unresolved governance
- **UNRESOLVED:** 7 governance debts blocking finalization

Every statement is classified. No forward-looking language appears. This is discovery reporting, not architecture planning.

---

**Round 29 — Decision Ownership Catalog REVISED**

**Status:** Ready for ARB Review

**Next Deliverable:** Round29_Invariant_Catalog.md

