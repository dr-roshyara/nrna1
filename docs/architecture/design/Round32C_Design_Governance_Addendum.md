# Round 32C — Design Governance Addendum

**Date:** 2026-06-08

**Phase:** Design Governance

**Type:** Design Review Rules

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 32 Design Governance Charter (APPROVED)
- Round 32A Design Work Program (APPROVED)
- Round 32B Design Baseline Consolidation (APPROVED)

**Purpose:** Define how design review will be conducted. Establish review checklists, evidence requirements, and approval workflows so that design execution remains as disciplined as discovery execution.

---

## 1. ADR Requirements

### When an ADR Is Required

An ADR is required before finalizing any design decision that:

- Changes or refines a context boundary
- Changes or refines an aggregate boundary or root
- Changes or refines an invariant statement or classification
- Reassigns decision ownership
- Promotes a PROVISIONAL item to DISCOVERED
- Identifies an aggregate candidate in a context not yet bearing one
- Introduces a design pattern (CQRS, event sourcing, projections, sagas) that affects context boundaries, aggregate boundaries, decision ownership, or invariants
- Adopts a concept from external literature as a domain concept
- Proposes a resolution to D35, D36, D37, ADH-1, or D42B
- Changes any entry in the Round 32B baseline

### ADR Lifecycle

| Status | Meaning | Who Sets It |
|--------|---------|------------|
| PROPOSED | Decision authored, rationale documented | Design team |
| UNDER REVIEW | Submitted to ARB | Design team |
| APPROVED | ARB accepts the decision | ARB |
| SUPERSEDED | Replaced by a later ADR | Design team + ARB |
| REJECTED | ARB does not accept the decision | ARB |

**Rule:** No PROPOSED ADR may be treated as binding. Implementation may not begin until ADR status is APPROVED for decisions listed in Section 1 above.

### ADR Required Content

Every ADR must contain:

1. **Title** — Decision being made
2. **Status** — Current lifecycle status
3. **Baseline Reference** — Which Round 32B entry is affected (context, aggregate, invariant, or decision)
4. **Decision** — What is decided
5. **Rationale** — Why this decision, based on discovery evidence
6. **Evidence Foundation** — Specific Round 17-29 artifact(s) supporting this decision
7. **Alternatives Considered** — Other options that were evaluated
8. **Governance Dependencies** — Any active constraints (D35/D36/D37/ADH-1/D42B) that affect this decision
9. **Consequences** — What changes as a result

For major ADRs (those affecting context boundaries, aggregate boundaries, or decision ownership), also include:

10. **Constitutional Requirement Traceability** — The chain from requirement to design:
    - Constitutional Requirement: which governance or domain rule drives this decision
    - Decision Owner: which D1–D8 decision this serves
    - Invariant: which invariant(s) from Round 32B this protects or affects
    - Aggregate: which aggregate boundary this concerns

**Rule:** An ADR without an evidence foundation reference may not be approved.

---

## 2. Evidence Requirements

### Evidence Tiers

All design decisions must be traceable to one or more evidence tiers. Tier authority is hierarchical.

| Tier | Source | Authority |
|------|--------|-----------|
| **Tier 1** | Round 32B Baseline | Highest — reflects accepted discovery |
| **Tier 2** | Round 29 Discovery Catalogs | High — primary discovery artifacts |
| **Tier 3** | Round 17-28 Discovery Artifacts | High — supporting discovery evidence |
| **Tier 4** | ADRs (approved) | Medium-High — accepted design decisions |
| **Tier 5** | External Literature | Advisory only — may inform, may not override |

### Evidence Rules

- Design decisions must be grounded in Tier 1-4 evidence.
- Tier 5 evidence (literature) may support design reasoning but may not override Tier 1-4 evidence.
- If a design decision contradicts Tier 1-4 evidence, an ADR must explicitly acknowledge the contradiction and provide justification.
- An ADR that relies solely on Tier 5 evidence requires explicit ARB approval.

### Evidence Sufficiency Test

Before submitting an ADR, the design team must answer:

1. Which Round 32B baseline entry does this decision affect?
2. What Round 17-29 discovery artifact supports this decision?
3. Does this decision strengthen, refine, or change the baseline?
4. If it changes the baseline, what is the justification?

If question 4 cannot be answered from Tier 1-4 evidence, escalate to ARB before proceeding.

---

## 3. Literature Governance Rules

These rules carry forward and operationalize the Round 32 Design Governance Charter.

### Literature May

- Inform design reasoning
- Suggest patterns for evaluation
- Provide reference implementations for D42B investigation
- Support candidate assessment in Stream C

### Literature May Not

- Override a discovered context boundary
- Override a discovered aggregate boundary
- Override a discovered invariant
- Override discovered decision ownership
- Introduce a domain concept not found in discovery
- Resolve D42B without ARB review of the recommendation

### Prohibited Literature-Driven Actions (Without ADR + ARB Approval)

| Action | Reason |
|--------|--------|
| Adding a Verifiability Context because ElectionGuard has one | D42B is a Design Knowledge Gap — literature does not resolve it |
| Adopting CQRS because it fits the domain | CQRS was not discovered — it is an architecture pattern choice requiring ADR |
| Splitting Voting aggregate because Helios separates ballot submission from tallying | Aggregate boundaries follow discovered invariants, not literature analogies |
| Introducing a Ledger or Blockchain concept | Not discovered in domain |
| Adding coercion-resistance requirements | Not discovered in domain |

### Literature Review Test

Before citing literature in a design decision, apply this test:

```
Does this literature support a discovery finding?   → Permitted
Does this literature suggest a new domain concept?  → Requires ADR
Does this literature override a discovery finding?  → Requires ARB review
```

---

## 4. Design Approval Workflow

### Standard Approval Path

```
Design team identifies decision
        ↓
Draft ADR (PROPOSED)
        ↓
Evidence Foundation verified (self-review)
        ↓
Submit to ARB (UNDER REVIEW)
        ↓
ARB reviews ADR
        ↓
APPROVED or REJECTED (with reasons)
        ↓
If APPROVED: decision is binding
If REJECTED: revise and resubmit
```

### Expedited Path (Model Refinement)

For model-refinement debts (ADG-2, ADC-1, ADC-2, ADGR-1) that do not change context or aggregate boundaries:

```
Design team identifies refinement
        ↓
Draft ADR (PROPOSED)
        ↓
Submit to ARB with baseline reference
        ↓
ARB reviews and approves (streamlined review)
```

### Governance-Dependent Path

For decisions depending on D35/D36/D37/ADH-1:

```
Design team prepares placeholder ADR
        ↓
Governance item resolved (D35/D36/D37/ADH-1)
        ↓
ARB confirms resolution
        ↓
Design team completes ADR with resolution context
        ↓
Standard approval path
```

**Rule:** A governance-dependent ADR may not be approved before the governance item it depends on is resolved.

---

## 5. Aggregate Review Checklist

Before any aggregate design may be submitted for ARB approval, the following must be verified.

### Evidence Checklist

- [ ] Aggregate is traceable to a Round 32B baseline entry
- [ ] Aggregate root is identified and justified
- [ ] Core responsibility is stated in terms of decision ownership (which D1-D8 decision does it protect?)
- [ ] Consistency boundary is explicitly stated (what must be atomic?)
- [ ] All invariants the aggregate protects are listed
- [ ] Each invariant is traceable to Round 32B invariant baseline
- [ ] Active debts affecting this aggregate are listed

### Governance Checklist

- [ ] No governance-dependent design conclusions are marked APPROVED
- [ ] Governance constraints on this aggregate are listed (D35/D36/D37/ADH-1/D42B as applicable)
- [ ] If provisional items are included, they are explicitly marked PROVISIONAL
- [ ] Literature references are advisory only (no literature override of discovery)

### Boundary Checklist

- [ ] Aggregate boundary does not expand beyond discovered decision ownership without ADR
- [ ] No neighboring context's decision ownership has been absorbed without ADR
- [ ] Zero-aggregate context classification has not been silently converted to aggregate-bearing without ADR + ARB

---

## 6. Invariant Review Checklist

Before any invariant formalization may be submitted for ARB approval:

### Evidence Checklist

- [ ] Invariant is traceable to Round 32B invariant baseline
- [ ] Invariant statement is a business rule (what must be true), not an implementation strategy (how it is enforced)
- [ ] Invariant classification (DISCOVERED / PROVISIONAL) matches Round 32B
- [ ] Confidence level is preserved or justified if changed
- [ ] Aggregate protecting this invariant is identified

### Governance Checklist

- [ ] If invariant is PROVISIONAL, the blocking debt is identified
- [ ] No PROVISIONAL invariant is promoted to DISCOVERED without evidence that the blocking debt is resolved
- [ ] Promotion of any invariant requires ADR + ARB review

### Scope Checklist

- [ ] Invariant does not add obligations not present in discovery
- [ ] Invariant does not weaken a DISCOVERED invariant without ADR + ARB approval
- [ ] Invariant statement distinguishes between business rule and implementation mechanism

---

## 7. Context Boundary Review Checklist

Before any context boundary decision may be submitted for ARB approval:

### Evidence Checklist

- [ ] Context is traceable to Round 32B context map baseline
- [ ] Decision ownership statement is preserved or revised via ADR
- [ ] Boundary stability classification is preserved or revised via ADR
- [ ] Context relationships are traceable to Round 32B (ACCEPTED or PROVISIONAL)

### Governance Checklist

- [ ] Context has not been merged with another context without ADR + ARB approval
- [ ] Context has not been split without ADR + ARB approval
- [ ] PROVISIONAL context relationships have not been promoted to ACCEPTED without evidence
- [ ] Arbitration / Legitimacy boundary (UNRESOLVED) has not been resolved without D35/D36/D37 governance resolution

### Ownership Checklist

- [ ] No decision ownership has been reassigned without ADR
- [ ] If a context has acquired new decision ownership, an ADR documents the acquisition
- [ ] Zero-aggregate context has not acquired an aggregate candidate without ADR

---

## 8. Threat Modeling Review Checklist

This project governs trust, authority, voting, legitimacy, and evidence — not simple CRUD software. Design decisions must be evaluated for security implications before approval.

The following checklist applies to all aggregate designs and all major ADRs.

### Authority Abuse

- [ ] Does this design allow an actor to acquire authority they were not granted?
- [ ] Does this design allow authority to persist beyond its intended scope?
- [ ] Does this design allow authority to be delegated beyond discovered delegation rules?

### Privilege Escalation

- [ ] Does this design allow a lower-authority role to perform a higher-authority action?
- [ ] Does this design allow a bypass of the central authorization authority (AU-2)?
- [ ] Does this design create a path that circumvents the ConstitutionalTransitionGuard pattern?

### Evidence Tampering

- [ ] Does this design preserve vote anonymity (VO-1)?
- [ ] Does this design preserve vote tamper evidence (VO-2)?
- [ ] Does this design preserve audit append-only guarantee?
- [ ] Does this design preserve evidence seal immutability (GR-1 provisional)?

### Replay Abuse

- [ ] Does this design protect against unauthorized invocation of replay mechanisms?
- [ ] Does this design ensure replay outcomes are deterministic (GR-2 provisional)?
- [ ] Does this design prevent divergence results from being suppressed or falsified?

### Legitimacy Manipulation

- [ ] Does this design prevent unauthorized modification of legitimacy status?
- [ ] Does this design ensure legitimacy determinations are applied deterministically (AR-1 provisional)?
- [ ] Does this design preserve the append-only nature of governance decisions?

### Denial of Governance

- [ ] Does this design create a single point of failure for critical governance decisions?
- [ ] Does this design ensure that governance processes cannot be permanently blocked by a single actor?
- [ ] Does this design preserve the ability to invoke arbitration when needed (D36 — pending governance resolution)?

**Rule:** Any aggregate design that cannot pass the threat modeling checklist may not be approved, even if it is functionally correct.

---

## 9. Governance Compliance at Design Reviews

At each ARB Design Review, the following compliance check is conducted against this addendum.

### Review Compliance Checklist

- [ ] All finalized design decisions have APPROVED ADRs
- [ ] All APPROVED ADRs have evidence foundation references
- [ ] No PROPOSED ADR has been treated as binding
- [ ] Governance-dependent items remain PENDING
- [ ] Risk Register (Round 32B Section 7) has been consulted
- [ ] "Discovery Did Not Establish" list (Round 32B Section 6) has been consulted
- [ ] No literature has overridden discovery findings
- [ ] Aggregate review checklist was completed for each submitted aggregate
- [ ] Invariant review checklist was completed for each submitted invariant
- [ ] Context boundary checklist was completed for each boundary decision
- [ ] Threat modeling checklist was completed for each aggregate design and major ADR
- [ ] Constitutional requirement traceability was included in all major ADRs

---

## ARB Review

Round 32C is submitted for ARB review.

ARB must confirm:

- Are the ADR requirements complete and appropriate?
- Are the evidence requirements correct?
- Are the literature governance rules enforced correctly?
- Is the design approval workflow appropriate?
- Are the review checklists complete?
- Is Round 32C ready to serve as the review framework for Round 33+?

**Upon ARB approval of Round 32C: design execution under Round 33 may begin using this addendum as the review framework.**

