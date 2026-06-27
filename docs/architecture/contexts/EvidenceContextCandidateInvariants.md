# Round 6B — Candidate Invariants

**Date:** 2026-06-03  
**Objective:** Determine whether candidate responsibilities A, B, D can be expressed as a coherent set of invariants  
**Constraint:** Strategic invariant analysis only. No tactical design, no enforcement mechanisms, no implementation details.

---

## Methodology

For each candidate responsibility from Round 6A, extract and evaluate its invariants:

1. **Invariant Statement** — What rule must hold true?
2. **Scope** — What entities or time periods does it apply to?
3. **Supporting Evidence** — Where did this invariant come from?
4. **Interaction** — How does it relate to other invariants in the cluster?
5. **Testability** — Can a domain expert determine if this invariant holds or is violated?
6. **Confidence** — How strongly is this invariant evidenced?

**Success = Invariants form a coherent, non-overlapping set without internal contradiction**

**Out of scope = How invariants would be enforced, implemented, or persisted**

---

## Responsibility A: Evidence Preservation and Immutability

### Invariant A-1: Freezing

**Statement:** Evidence must be frozen (immutable) after evaluation completes.

**Scope:** All evidence records created for a given election and voter context.

**Supporting Evidence:**
- Invariant I-1 from EvidenceContext.md: "Evidence frozen after evaluation completes"
- SecurityEventRecorder implements append-only storage
- ElectionSecurityEvent model prevents all updates: `save()` throws, `delete()` throws

**Interaction with other invariants:**
- Works with A-2 (retention) to ensure evidence persists unchanged
- Prerequisite for VR-1 (independent verifiability) and VR-5 (replayability)
- Works with B-1 (privacy) — privacy rules apply only to frozen evidence

**Ownership test:**
- Could be Infrastructure? YES — "prevent database updates" is a storage pattern
- Could be Evidence? YES — "evidence cannot change" is a domain rule
- Could be Election? MAYBE — election-specific records could be election responsibility

### Invariant A-2: Retention

**Statement:** Evidence must be retained for a minimum period (730 days default) after creation.

**Scope:** All evidence records across all elections and organisations.

**Supporting Evidence:**
- SecurityEventRecorder enforces 730-day retention policy
- VR-5 (replayability) implies evidence cannot be garbage-collected during election contests
- German Bundestag research: evidence retention is a constitutional requirement

**Interaction with other invariants:**
- Works with A-1 (freezing) — retained evidence must remain unchanged
- Supports VR-1 (independent verifiability) — auditors need historical evidence
- Temporal boundary: retention period must cover all possible replay/appeal windows

**Ownership test:**
- Could be Infrastructure? YES — retention is a database maintenance concern
- Could be Evidence? YES — retention period is a domain business rule
- Could be Election? MAYBE — election-specific retention could be election policy

### Candidate Invariant A-3: Post-Evaluation Evidence Freezing (CANDIDATE — LOW CONFIDENCE)

**Statement:** Once initial evaluation has completed for a voter, no additional evidence can be added to that voter's primary evidence set.

**Scope:** Evidence related to initial voting evaluation (first-pass legitimacy determination).

**Supporting Evidence:**
- VR-5 (replayability) suggests determinism — adding evidence post-evaluation might break replay consistency
- SecurityEventRecorder timing: records AFTER trust decisions complete

**Contradicting Evidence:**
- Appeals processes require adding new evidence after evaluation
- Fraud investigations may add evidence post-evaluation
- Audit processes may uncover evidence not available at evaluation time
- No operational evidence that current system prevents post-evaluation evidence addition

**Confidence:** LOW

**Interaction with other invariants:**
- Potential tension with A-1 (freezing) — if A-3 false, does A-1 still hold?
- Potential tension with VR-5 — if new evidence can be added, can replay remain deterministic?

**Ownership test:**
- Could be Infrastructure? WEAK — blocking additions is harder than preventing updates
- Could be Evidence? MAYBE — only if evidence truly freezes at evaluation
- Could be Evaluation? MAYBE — evaluation context decides if evaluation is final or revisable
- Could be Governance? YES — governance/appeals may own post-evaluation evidence

**Note:** A-3 is a candidate invariant based on inference, not operational evidence. Counterexamples (appeals, audits, investigations) suggest it may not hold.

---

## Responsibility B: Evidence Privacy Preservation

### Invariant B-1: No Voter Identity Storage

**Statement:** Evidence records must never store any field that directly identifies a voter.

**Scope:** All evidence records; applies globally to all evidence capture.

**Supporting Evidence:**
- Invariant EVI-5: "Evidence must not permit reconstruction of voter identity without explicit constitutional authority"
- SecurityEventRecorder explicitly nulls `voter_slug_id`
- Domain events carry `voterIdentifier` but SecurityEventRecorder does not (deliberate design choice)
- Round 5 finding: "Raw IP addresses hashed before SecurityEventRecorder receives them"

**Interaction with other invariants:**
- Works with B-2 (indirect re-identification) — together ensure complete voter anonymity
- Prerequisite for VR-2 (verification without voter identity)
- Works with VR-4 (verification independence) — verifiers cannot access voter data

**Ownership test:**
- Could be Infrastructure? WEAK — preventing specific fields is not a storage pattern
- Could be Evidence? YES — "evidence cannot identify voters" is a domain rule
- Could be Cross-Cutting? YES — privacy could be a system-wide policy
- Could be Election? MAYBE — election domain could own voter privacy


### Invariant B-3: Privacy Preservation Through Design, Not Deletion

**Statement:** Evidence achieves privacy through exclusion at capture time, not through post-capture redaction or deletion.

**Scope:** All evidence capture design; applies to SecurityEventRecorder and any future evidence mechanisms.

**Supporting Evidence:**
- SecurityEventRecorder hashes IPs before capture (privacy by design)
- `voter_slug_id` is explicitly set to null before storage (not retroactively removed)
- Round 5 finding: "privacy design (removing voter identity) suggests this is a domain concern"

**Interaction with other invariants:**
- Works with A-1 (immutability) — immutable records cannot be retroactively redacted
- Prerequisite for B-1 and B-2 — privacy by exclusion is stronger than privacy by redaction
- Works with VR-2 (no identity in verification) — verification never has data to expose

**Ownership test:**
- Could be Infrastructure? WEAK — design principle, not storage constraint
- Could be Evidence? YES — "privacy as exclusion" is a domain commitment
- Could be Privacy Policy context? YES — if privacy is cross-cutting

---

## Responsibility D: Constitutional Authority Evidence

### Candidate Invariant D-1: Authority Chain Completeness (CANDIDATE — LOW-MEDIUM CONFIDENCE)

**Statement:** Evidence should record which authorities evaluated and approved participation, in order, with sufficient detail to enable understanding of the evaluation decision.

**Scope:** Evidence related to voter legitimacy determination.

**Supporting Evidence:**
- Invariant VR-5: "Verification must support replayability" (suggests authority chain needed)
- ConstitutionalEvidenceSnapshot hypothesis includes "electionConstitutionSnapshot" (inferred)
- Round 5 finding: "evidence must enable reconstruction of evaluation" (inferred from hypothesis)
- Domain events reference "gate condition" and "migration phases" (events not yet operational)

**Contradicting Evidence:**
- No operational implementation of authority chain in SecurityEventRecorder
- Five domain events (which would carry authority) are inactive/not dispatched
- D-1 is inferred from hypothesis and requirements, not from running code

**Confidence:** LOW-MEDIUM (inferred from requirements; not operationally demonstrated)

**Interaction with other invariants:**
- Possible tension with A-1 (freezing) — if authority chain can be added post-evaluation, does A-1 hold?
- Relates to D-2 (authority immutability) — if authority is recorded, it should not change
- Relates to VR-5 (replayability) — replayability may require knowing which authorities participated

**Ownership test:**
- Could be Infrastructure? NO — authority chain is semantic, not a storage pattern
- Could be Evidence? MAYBE — "which authorities approved this" could be evidence of what happened
- Could be Evaluation? MAYBE — evaluation context decides which authorities to consult
- Could be Governance? MAYBE — governance authorities might own their own records
- **UNRESOLVED:** Does Evidence own authority chain, or does Evaluation provide it and Evidence stores it?

**Note:** D-1 depends on whether Evidence Context is responsible for authority metadata. This belongs primarily to 6C (Candidate Boundaries), not 6B.

### Candidate Invariant D-2: Authority Immutability (CANDIDATE — LOW-MEDIUM CONFIDENCE)

**Statement:** Once recorded, the authority that approved participation should not change without creating a new evaluation event.

**Scope:** Authority data in evidence records; applies per-voter per-election.

**Supporting Evidence:**
- Derived from A-1 (evidence frozen) — if evidence frozen, authority in evidence should be frozen
- VR-5 (replayability) suggests determinism — authority should not change during replay
- VR-4 (verification independence) suggests verifiers should see consistent authority

**Contradicting Evidence:**
- No operational implementation of authority immutability (D-1 not yet implemented)
- Depends on D-1 existing and being recorded
- If authority can be questioned/appealed (appeals process), immutability may not hold

**Confidence:** LOW-MEDIUM (derived from A-1 by inference; not operationally demonstrated)

**Interaction with other invariants:**
- Depends on A-1 (freezing) — authority immutability is only meaningful if evidence is frozen
- Depends on D-1 (authority chain) — requires authority chain to exist and be recorded
- Relates to VR-5 (replayability) — assumes deterministic replay requires unchangeable authority

**Ownership test:**
- Could be Infrastructure? WEAK — immutability of semantic fields is not a storage pattern
- Could be Evidence? MAYBE — if Evidence owns authority, Evidence owns immutability
- Could be Evaluation? MAYBE — evaluation owns decisions; authority might be part of that
- **DEPENDS ON D-1:** Ownership cannot be determined until D-1 ownership is resolved.

**Note:** D-2 is conditional on D-1 being true and owned by Evidence. This is primarily a 6C (Candidate Boundaries) question.

---

## Candidate Policies (Not Invariants)

These are important architectural principles that don't meet the definition of strict invariants (testable, deterministic constraints).

### B-2: No Indirect Voter Re-Identification (CANDIDATE POLICY)

**Statement:** Evidence records should not store field combinations that, together, permit reconstruction of voter identity without explicit constitutional authority.

**Why not an invariant?**
- Cannot be tested deterministically without semantic analysis
- "Permit reconstruction" is subjective (depends on attacker capability, external data, etc.)
- Requires ongoing security review, not automatic enforcement
- Is a security principle, not a business rule

**Classification:** Candidate Privacy Policy (architectural principle, not invariant)

**Related to:** B-1 (No voter identity); VR-2, VR-4 (verification requirements)

---

## Coherence Analysis: Do A, B Invariants Form a Coherent Set?

### Internal Consistency

| Invariant | Confidence | Conflicts with | Reinforces |
|-----------|------------|---|---|
| A-1 (Freezing) | HIGH | None | A-2, B-1, B-3, VR-5 |
| A-2 (Retention) | HIGH | None | A-1, VR-1 |
| A-3 (Post-eval freezing) | LOW | Maybe A-1? | VR-5 (maybe) |
| B-1 (No voter identity) | HIGH | None | B-3, VR-2, VR-4 |
| B-3 (Privacy by design) | HIGH | None | B-1, A-1 |
| D-1 (Authority chain) | LOW-MEDIUM | Depends on scope | D-2 (if true), VR-5 |
| D-2 (Authority immutability) | LOW-MEDIUM | Depends on D-1 | D-1 (if exists) |

**Assessment:** 
- ✅ A-1, A-2, B-1, B-3 show no internal conflicts and reinforce each other
- ⚠️ A-3 is weakly supported and conflicts with appeals/audits
- ⚠️ D-1, D-2 are conditional on ownership questions (6C), not settled here

---

### Boundary Clarity

| Boundary | Question | Status |
|----------|----------|--------|
| **A vs. Infrastructure** | Is freezing a domain rule or storage pattern? | UNRESOLVED |
| **B vs. Cross-Cutting** | Is privacy Evidence-specific or system-wide? | UNRESOLVED |
| **D vs. Evaluation** | Does Evidence own authority chain or store it? | **CRITICAL ARCHITECTURAL UNCERTAINTY** |
| **A vs. Election** | Could Election own evidence freezing? | UNRESOLVED |

---

### The D Question: Critical Architectural Uncertainty

**Invariants D-1 and D-2 are only meaningful if Evidence Context owns or co-owns authority metadata.**

**Two scenarios:**

**Scenario 1: Evidence owns D-1/D-2**
```
Evidence Context owns:
  - A: What evidence is (frozen, retained)
  - B: How evidence is private
  - D: What evidence must contain (authority chain)

Result: Coherent domain bounded context.
Domain concept: "Constitutional evidence trail"
```

**Scenario 2: Evaluation owns D-1/D-2**
```
Evidence Context owns:
  - A: What evidence is (frozen, retained)
  - B: How evidence is private

Evaluation Context owns:
  - D: What authorities decided

Result: Evidence is data/policy; Evaluation is decision-making.
Evidence becomes: Infrastructure for decisions
```

---

### Coherence Assessment

**If D belongs to Evidence:**
- ✅ A, B, D form a coherent cluster
- ✅ Evidence is a domain-level bounded context
- ✅ Evidence answers: "What happened, who decided it, and why it matters"
- ⚠️ Requires authority metadata to be part of evidence model (not yet implemented)

**If D belongs to Evaluation:**
- ✅ A, B form a coherent cluster (form of evidence)
- ⚠️ D belongs elsewhere (content of evidence)
- ⚠️ Evidence becomes infrastructure (frozen private records)
- ⚠️ A coherent domain context, but not Evidence Context

---

## Invariant Verification Against Rounds 1–6.0

| Invariant | Evidence Type | Confidence | Source |
|-----------|---|---|---|
| A-1 (Freezing) | Operational + Domain | HIGH | SecurityEventRecorder, I-1 |
| A-2 (Retention) | Operational | HIGH | SecurityEventRecorder policy |
| A-3 (Post-eval freezing) | Domain (inferred) | LOW | VR-5; contradicted by appeals/audits |
| B-1 (No voter ID) | Operational + Domain | HIGH | SecurityEventRecorder, EVI-5 |
| B-3 (Privacy by design) | Operational | HIGH | SecurityEventRecorder design |
| D-1 (Authority chain) | Domain (inferred) | LOW-MEDIUM | VR-5, hypothesis; not yet operational |
| D-2 (Authority immutability) | Domain (inferred) | LOW-MEDIUM | Inferred from A-1; depends on D-1 |

**Assessment:**
- ✅ A-1, A-2, B-1, B-3 are strongly evidenced (operational + domain)
- ⚠️ A-3 is weakly evidenced; counterexamples exist (appeals, audits, investigations)
- ⚠️ D-1, D-2 are inferred from hypotheticals and requirements, not operational evidence
- 🔄 B-2 (indirect re-identification) reclassified as Candidate Policy, not invariant

---

## Open Questions from Invariant Analysis

1. **A-3 validity:** Does "no post-evaluation evidence" hold, or are appeals/audits/investigations allowed? (Needed to validate A-3)

2. **D ownership (CRITICAL ARCHITECTURAL UNCERTAINTY):** Does Evidence Context own authority chain metadata, or does Evaluation Context provide it? (Belongs primarily to 6C)

3. **Retention scope (A-2):** Is 730-day retention a domain rule, or a configurable infrastructure policy?

4. **Privacy as policy (B-2):** What specific field combinations should be prohibited? (Requires security review, not just code analysis)

---

## Decision Gate

**Question:** Can Evidence Context invariants be expressed coherently?

**Answer:** YES (with caveats)

**Strong candidates (HIGH-MEDIUM confidence):**
- ✅ A-1 (Freezing) — operationally demonstrated
- ✅ A-2 (Retention) — operationally demonstrated  
- ✅ B-1 (No voter identity) — operationally demonstrated
- ✅ B-3 (Privacy by design) — operationally demonstrated

**Weak candidates (LOW confidence):**
- ⚠️ A-3 (Post-eval freezing) — counterexamples exist; needs validation
- ⚠️ D-1 (Authority chain) — inferred from requirements, not operational
- ⚠️ D-2 (Authority immutability) — conditional on D-1

**Policies (not invariants):**
- 🔄 B-2 (No indirect re-id) — reclassified as Candidate Privacy Policy

**Critical uncertainty:**
- ❓ D ownership — belongs primarily to 6C (Candidate Boundaries)

---

## Next Step

**Recommended:** Proceed to Round 6C — Candidate Boundaries

**Evaluate:**
1. Can a clear boundary be drawn between Evidence and Evaluation contexts?
2. Does that boundary clarify D ownership (authority chain)?
3. What does Evidence Context own vs. provide?
4. What does Evaluation Context consume vs. own?

**Note:** Do NOT attempt to resolve D ownership in 6C by force. If 6C shows D ownership remains ambiguous, that is a valid outcome for author clarification later.

---

**Status: Round 6B complete. Invariants A, B are coherent and operationally evidenced. D invariants are candidate-level; ownership is a 6C question. A-3 needs validation. B-2 reclassified as policy.**