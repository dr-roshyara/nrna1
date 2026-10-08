# Membership Architecture: Principal Reviewer Ratification Act (record)

**Act:** RATIFIED · FROZEN FOR IMPLEMENTATION · EP-01 (Membership Implementation Design) authorised
**Actor:** the Principal Reviewer (human act). **Recorded by:** `claude-code-session:42e0287e…`, which records the act and does not perform it.
**Date of act:** 2026-10-08 · **Provenance:** pasted by the user into session `42e0287e-e0e2-4638-8165-cd5d5862c578`. The text is reproduced verbatim in §2.
**Ratified artifact set:** `2026-10-08-membership-architecture-ratified.md` (canonical) · `…-membership-final-proof.md` (§12–§16) · `…-membership-final-decision-register.md` · `…-membership-final-implementation-blueprint.md` · `…-membership-final-ratification-verdict.md` (freeze-audit verdict: READY FOR PRINCIPAL RATIFICATION)

## 1. Recorder's notes (these do not amend the act)

These notes identify where the act's wording and the ratified artifacts must be read together. **They are for EP-01 to honour, not decisions.**

| # | Act wording | Ratified artifacts say | Reading for EP-01 |
|---|---|---|---|
| N-1 | §4: consequences determined "through the complete causal dependency chain" | D(c) is the **reference closure** (target chain). Full-replay equality is EXHAUSTIVELY VERIFIED (bounded) wherever a replay exists (FP §16.5) | implement D(c) as the target-chain closure; keep the replay-equality property test |
| N-2 | §7: "a single simple `[from, until)` interval is insufficient" | each period's **resolved** lifetime is one contiguous interval (INV-EP). What is insufficient is a **stored** interval as truth; it is derived from BaseClaim − LiveCut | do not store the lifetime as authoritative; projections only |
| N-3 | §4: Correct "according to the ratified correction policy" | Correct targets value-bearing facts and Corrects only. **Annul(Void), Correct(Annul) and Correct(Void) are refused** (FP §16.4). The act settles the BD-REV correction-target sub-policy as recommended (annulled: inert; voided: refused) | command guards per FP §16.4 |
| N-4 | §14 freezes BD-I5, BD-REV, BD-SYNC | **BD-REV-ER** (may a correction absorb a whole period?) is not named in the act | **remains an open business decision**; the recommended default is refuse. It is needed before the reversal slice |
| N-5 | §11: "Membership may provide membership-related business facts" (Finance) | fees are Finance's; Standing is derived from Finance evidence. Blueprint §5 "Membership → Finance: FeePaid" is ambiguous | EP-01 treats FeePaid as a **Finance** fact consumed by Membership |
| N-6 | §10: the aggregate must not absorb committee participation | the legacy `app/Contexts/Membership` contains committee code (`Domain/Committee`, `Application/Committee*`) | EP-01 / migration must place it in Governance; this is a known legacy boundary defect |

## 2. The act (verbatim)

~~~~markdown
# PublicDigit Membership Architecture
## Principal Reviewer Ratification

**Decision:** RATIFIED  
**Role:** Principal Reviewer / Senior DDD Architect  
**Gate:** Membership Architecture Final Freeze  
**Date:** 2026-10-08

---

## 1. Ratification Decision

The PublicDigit Membership Architecture is hereby **RATIFIED** as the architectural baseline for implementation.

The ratification is based on the completed Membership research, architecture synthesis, falsification work, computational verification, and Final Freeze Audit.

No further open-ended Membership ontology research is required before implementation.

The architecture may now proceed to implementation design and controlled implementation.

---

## 2. Ratified Architectural Model

The ratified model is:

**Architecture D + B′**

An Organisation Membership is represented by one enduring `Member` / Membership aggregate for a `(Person, Organisation)` relationship.

Within that Membership:

- each admission creates a distinct Membership Period;
- re-entry creates a new Period;
- Membership history is represented through immutable domain facts;
- membership truth is derived from those facts;
- lifecycle status is not the authoritative source of truth;
- periods preserve historical legal episodes;
- term gaps remain distinguishable from termination;
- suspension is represented as a restriction rather than as a replacement membership state;
- Membership does not grant Election voting entitlement.

The core semantic dependency is:

```text
Facts
  ↓
Liveness
  ↓
Activeness
  ↓
Values
  ↓
BaseClaim
  ↓
LiveCut
  ↓
Lifetime Resolution
  ↓
Derived Membership Predicates
  ↓
Invariant Validation / G*
  ↓
Atomic Commit
```

---

## 3. Ratified Fact Algebra

The Membership domain recognises the following fact families:

- Admit
- Renew
- ChangeType
- Suspend
- Restore
- End
- Annul
- Correct
- Void

Facts are ordered by recording sequence.

Correction chains therefore terminate deterministically because a fact can only reference an earlier fact.

Annulment and Void do not create new membership consequences. They alter the liveness of existing facts and therefore may revive consequences that already existed.

---

## 4. Ratified Correction and Annulment Semantics

The Final Freeze Audit explicitly covered:

- Annul(Annul)
- Void(Annul)
- Annul(Void)
- correction chains
- annulment of facts with later dependent facts
- causal closure

The architecture therefore ratifies:

### Annul

Annul applies only to facts that represent a decision.

### Annul(Annul)

An annulment may itself be annulled.

Semantically this represents an appeal that restores the original fact, subject again to the normal Membership invariants and G* validation.

### Void

Void is a distinct legal operation and does not simply mean Annul.

### Correct

Correction is allowed according to the ratified correction policy.

A correction of an annulled fact may remain inert unless the annulment is subsequently overturned.

Correction of a voided fact is refused.

### Causal closure

The relevant consequence set is determined through the complete causal dependency chain.

The Final Freeze Audit found:

- 151,959 exhaustive histories;
- zero invariant violations;
- zero counterfactual mismatches;
- 431,481 replayable counterfactual cases with identical results.

Where replay is impossible because later facts depended on the existence of the annulled fact, the system does not silently re-decide those facts. The operation is either accepted when the resulting state remains valid or refused until the dependent decisions are explicitly addressed.

This is accepted as the ratified semantic rule.

---

## 5. Ratified Temporal Model

Membership uses the distinct temporal dimensions:

```text
effectiveAt
decidedAt
recordedAt
seq
```

These must not be collapsed into one timestamp.

Membership truth is evaluated according to effective time while the recorded history preserves when and in what order the system learned the fact.

This permits:

- backdated decisions;
- corrections;
- appeals;
- historical reconstruction;
- deterministic resolution.

---

## 6. Ratified Membership Period Model

A Membership Period is an identified legal episode opened by an admission.

Re-entry therefore produces:

```text
Membership
 ├── Period 1
 ├── Period 2
 ├── Period 3
 └── ...
```

rather than creating an unrelated membership identity for every re-entry.

The ratified policy is:

> **No overlapping Membership Periods for the same `(Person, Organisation)`.**

This is **BD-I5**.

This policy is enforced through domain validation and concurrency control.

---

## 7. Ratified Lifetime Model

A single simple `[from, until)` interval is insufficient.

The architecture therefore retains:

- BaseClaim;
- LiveCut;
- term segments;
- resolved lifetime.

This preserves the distinction between:

```text
lapse
```

and

```text
termination
```

A lapse does not itself terminate Membership.

A genuine legal break requires an ending act followed by a later admission for a new period.

---

## 8. Ratified Invariants

The architecture is governed by the established Membership invariants, including:

- holder and organisation identity remain stable;
- ended membership is not in force;
- lapse does not imply termination;
- facts are append-only domain history;
- historical truth is derived rather than overwritten;
- membership multiplicity is controlled;
- lifetime coverage is preserved correctly;
- Membership does not infer Election entitlement.

The Final Freeze Audit found no new invariant violation.

---

## 9. G* Classification

G* is explicitly ratified as an **enforcement mechanism**, not as the definition of Membership truth.

The distinction is:

```text
Domain truth
    =
facts + resolution + invariants

G*
    =
mechanism that prevents an invalid resulting state
```

This distinction is architecturally important and must be preserved during implementation.

---

## 10. Ratified Aggregate Boundary

The selected aggregate boundary is **D1**.

The Membership aggregate owns the consistency boundary required for:

- Membership identity;
- Membership Periods;
- Membership facts;
- lifetime resolution;
- cross-period multiplicity;
- Membership-derived predicates;
- Membership number;
- authority evidence relevant to Membership acts.

The aggregate must not absorb:

- Person identity;
- Governance competence;
- Election entitlement;
- accounting;
- committee participation;
- unrelated organisation structures.

---

## 11. Ratified Context Boundaries

### Identity

Owns Person identity and identity equivalence.

### Membership

Owns organisational membership truth.

### Governance

Owns competence, organs, authority and organisational governance rules.

### Election

Owns:

- ElectionMembership;
- VotingEntitlement;
- eligibility;
- exercisability.

Membership does not decide these.

### Finance

Owns:

- settlement;
- refunds;
- credits;
- accounting.

Membership may provide membership-related business facts, but does not become the accounting context.

### Audit

Consumes published Membership information and provides audit projections.

No shared persistence is required between these bounded contexts.

---

## 12. Ratified Election Boundary

The existing PublicDigit Election architecture remains authoritative.

Therefore:

```text
Election-Only
    → ImportedVoterRegistry
```

does not require Membership.

For Full Membership:

```text
MembershipRegistry
       ↓
Election
       ↓
ElectionMembership
       ↓
VotingEntitlement
       ↓
Exercisability
```

Membership itself never becomes the VotingEntitlement engine.

This preserves the previously ratified distinction:

```text
Registration
    ≠
Admission
    ≠
VotingEntitlement
    ≠
Exercisability
```

---

## 13. Ratified Concurrency Requirement

Concurrency is now an explicit architectural requirement.

Commands operating on the same Membership must use:

- optimistic concurrency or equivalent serialization;
- expected aggregate/version information;
- conflict detection;
- recomputation of consequences after a conflict.

A database uniqueness constraint alone is insufficient.

The Final Freeze Audit demonstrated that blind concurrent commits can violate the domain invariants.

Therefore:

> **On a version conflict, the command must be re-evaluated against the new aggregate state; merely repeating the final database constraint check is insufficient.**

---

## 14. Ratified Policy Parameters

The following policy parameters are frozen for the implementation baseline:

| Parameter | Ratified choice |
|---|---|
| BD-I5 | No overlapping periods per `(Person, Organisation)` |
| BD-REV | Trim / controlled correction consequence handling |
| BD-SYNC | Transactional consistency |

Changing these does not automatically reopen the ontology.

### Exception

The following requirement **would reopen the aggregate/ontology design**:

> Two legally distinct simultaneous memberships of the same Person in the same Organisation.

No current requirement requires this.

---

## 15. What Is Proven vs What Is Verified

The architecture does **not** claim that exhaustive finite testing constitutes mathematical proof of all possible histories.

The correct evidence classification is:

### Structurally proven

- correction-chain termination through increasing sequence;
- deterministic dependency ordering;
- definitions of derived predicates;
- core invariant relationships where established by construction.

### Computationally verified within defined bounds

- correction/annulment behavior;
- causal closure;
- counterfactual equivalence;
- invariant preservation;
- concurrency scenarios;
- extended Annul/Void combinations.

### Business policy

- I5;
- correction/trim policy;
- transactional synchronization;
- termination grounds;
- authority rules;
- organisational policy.

This distinction is mandatory and must remain in the architecture documentation.

---

# 16. Architecture Freeze Decision

The Membership architecture is now considered:

**FROZEN FOR IMPLEMENTATION.**

Further changes require an explicit Architecture Decision / Change Request.

Researchers and implementers must not silently reopen:

- Membership identity;
- Period semantics;
- fact algebra;
- temporal model;
- aggregate boundary;
- Election boundary;
- Membership-vs-entitlement separation.

A new business requirement may reopen these decisions only through the normal architecture governance process.

---

# 17. Implementation Gate Opened

The following phase is now authorised:

## EP-01 — Membership Implementation Design

EP-01 shall translate the ratified architecture into:

1. domain classes;
2. value objects;
3. aggregates;
4. commands;
5. domain services;
6. invariant enforcement;
7. fact persistence;
8. resolution algorithm;
9. repositories;
10. transaction boundaries;
11. optimistic concurrency;
12. application services;
13. authorization ports;
14. published integration contracts;
15. read models;
16. APIs;
17. migration strategy;
18. automated tests.

Implementation must preserve the ratified semantic model rather than redesign it.

---

## 18. Final Principal Reviewer Verdict

**RATIFIED.**

The Membership Architecture has passed the Final Freeze Audit.

The research phase is closed.

The architecture phase is closed.

The implementation phase may begin.

The next architectural deliverable is **EP-01 — Membership Implementation Design**, not another Membership ontology research round.

**Principal Reviewer Decision: APPROVED FOR IMPLEMENTATION.**
~~~~
