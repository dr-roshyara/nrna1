# Round 6C.5 — Decision Ownership Analysis

**Date:** 2026-06-03  
**Objective:** Identify which constitutional decisions must be made about evidence, and who owns them  
**Critical Question:** If Evidence Context disappeared tomorrow, which domain decisions could no longer be made?

---

## Methodology

For each candidate decision about evidence:

1. **Decision Statement** — What must be decided?
2. **Context Ownership** — Which context should own this decision?
3. **Authority Test** — Who has the authority to change this decision?
4. **Supporting Evidence** — What evidence from Rounds 1–6.0 supports this ownership?
5. **Confidence** — How certain is this assignment?

**Success = Identify which decisions (if any) are owned by Evidence Context, and whether those decisions are domain-level**

---

## Critical Question First

**If Evidence Context disappeared tomorrow, which constitutional decisions could no longer be made?**

This question will reveal whether Evidence is:
- A Core Domain (makes essential constitutional decisions)
- A Supporting Domain (enables core decisions)
- Infrastructure Capability (enforces policies decided elsewhere)

---

## Candidate Decisions About Evidence

### Decision D1: Evidence Freezing Policy

**Statement:** Should evidence be immutable after a certain point? (Yes/No/When?)

**What must be decided:**
- Is evidence frozen immediately after evaluation, or later?
- Can evidence be modified after publication?
- Can evidence be destroyed?

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Evidence | MAYBE | "Evidence cannot change" is an evidence principle |
| Evaluation | MAYBE | Evaluation decides when evaluation is complete; freezing follows |
| Governance | MAYBE | Freezing policy may be constitutional requirement |
| Election | MAYBE | Election could decide freezing timing |

**Authority Test:**

Who decides: "Evidence will be immutable after evaluation"?

Current evidence:
- SecurityEventRecorder implements freezing operationally
- But no domain owner specified
- Could be Infrastructure decision, not domain decision

**Confidence in ownership:** LOW

**Likely owner:** Governance (constitutional requirement) or Infrastructure (storage pattern)

**Domain-level decision?** WEAK

---

### Decision D2: Evidence Retention Period

**Statement:** How long must evidence be retained? (730 days? Configurable? Forever?)

**What must be decided:**
- Minimum retention period
- Who can change it
- What triggers deletion
- What counts as "retention"

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Evidence | MAYBE | "Evidence will be retained" is evidence responsibility |
| Governance | LIKELY | Legal/constitutional retention requirement |
| Election | MAYBE | Election-specific retention needs |
| Infrastructure | MAYBE | Technical retention is infrastructure concern |

**Authority Test:**

Who decides: "Evidence will be retained for 730 days"?

Current evidence:
- SecurityEventRecorder enforces 730-day retention
- German Bundestag research suggests legal requirement
- Suggests Governance owns this, not Evidence

**Confidence in ownership:** MEDIUM

**Likely owner:** Governance (legal requirement)

**Domain-level decision?** WEAK

---

### Decision D3: Voter Privacy Preservation in Evidence

**Statement:** What information can be stored in evidence records about voters?

**What must be decided:**
- Which fields are forbidden (voter_id, email, etc.)
- Which field combinations are forbidden (timestamp + election + region?)
- How strict is the prohibition
- Who reviews for compliance

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Evidence | MAYBE | "Evidence will not re-identify voters" is evidence principle |
| Governance | LIKELY | Voter privacy is constitutional/legal requirement |
| Security | MAYBE | Privacy is often a cross-cutting security concern |
| Election | MAYBE | Election could decide voter privacy thresholds |

**Authority Test:**

Who decides: "Evidence cannot contain voter_id"?

Current evidence:
- SecurityEventRecorder enforces this
- EVI-5 invariant documents the rule
- But appears to be governance policy, not evidence domain decision
- Suggests Governance owns the rule; Evidence enforces it

**Confidence in ownership:** MEDIUM

**Likely owner:** Governance (constitutional requirement)

**Domain-level decision?** WEAK

---

### Decision D4: Evidence Authority Metadata Recording

**Statement:** Should evidence record which authorities evaluated participation? (Yes/No/What detail?)

**What must be decided:**
- Which authorities are recorded
- What level of detail (name, decision, timestamp, reasoning?)
- How is authority data validated
- Who controls authority list
- Can authority chain be questioned/appealed

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Evidence | MAYBE | "Evidence will record authority" could be evidence decision |
| Evaluation | MAYBE | Evaluation decides which authorities to consult |
| Governance | LIKELY | Governance defines which authorities are constitutional |
| Authority | POSSIBLE | Authority metadata could be separate context |

**Authority Test:**

Who decides: "Evidence will record Authority A approved"?

This is complex:
- Governance defines which authorities are valid
- Evaluation decides which to consult
- Evidence stores the decision
- But who owns "what gets recorded"?

**Confidence in ownership:** LOW

**Likely owner:** Governance (defines authorities) + Evaluation (decides which to invoke) + Evidence (stores)

**Domain-level decision?** MEDIUM

This could be a real domain decision IF Evidence owns the semantic meaning of authority recording.

---

### Decision D5: Evidence Release Authorization

**Statement:** Can evidence be released to external parties? (To whom? Under what conditions?)

**What must be decided:**
- Who can request evidence release
- What authorization is required
- How is privacy preserved during release
- What redaction is required
- Who approves release

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Evidence | MAYBE | Evidence manages access to evidence records |
| Governance | LIKELY | Governance decides release policies |
| Security | MAYBE | Security controls access |
| Verification | MAYBE | Verification Context consumes evidence; may own release rules |

**Authority Test:**

Who decides: "Evidence can be released to Verification Context"?

Current evidence:
- No operational mechanism found
- Deferred to future (Verification Context)
- Suggests Governance owns release policy

**Confidence in ownership:** LOW

**Likely owner:** Governance

**Domain-level decision?** MEDIUM

This becomes important when Verification Context is built.

---

### Decision D6: Evidence Validity/Admissibility

**Statement:** What makes evidence admissible or valid for decision-making?

**What must be decided:**
- What evidence is sufficient for legitimacy determination
- What evidence invalidates a decision
- Can evidence be contested
- What counts as "tampered evidence"
- Who determines validity

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Evidence | MAYBE | Evidence could define its own admissibility rules |
| Evaluation | LIKELY | Evaluation uses evidence; decides what's sufficient |
| Governance | MAYBE | Governance sets constitutional admissibility standards |
| Verification | MAYBE | Verification Context proves evidence validity |

**Authority Test:**

Who decides: "This evidence is admissible for legitimacy determination"?

This is subtle:
- Governance sets constitutional standards
- Evaluation applies them
- Evidence may provide proof mechanisms (hash, signature)

**Confidence in ownership:** LOW

**Likely owner:** Evaluation (applies standards) with Verification support (proves validity)

**Domain-level decision?** STRONG

This is a meaningful domain decision about evidence admissibility.

---

### Decision D7: Evidence Appeal/Revision Eligibility

**Statement:** Can evidence be re-evaluated or evidence-related decisions appealed?

**What must be decided:**
- When can new evidence change a decision
- What counts as "new evidence"
- Appeal window (how long after evaluation?)
- Who can appeal
- What re-evaluation means

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Evidence | NO | Evidence is frozen; cannot decide its own re-evaluation |
| Evaluation | MAYBE | Evaluation decides if decisions are revisable |
| Governance | LIKELY | Governance decides appeal/revision policy |
| Legitimacy | MAYBE | Legitimacy context owns appeal decisions |

**Authority Test:**

Who decides: "Evidence can trigger re-evaluation"?

Current evidence:
- A-3 (post-eval freezing) is weakly supported
- Appeals are mentioned in Round 6B as contradicting A-3
- Suggests Governance owns appeal policy, not Evidence

**Confidence in ownership:** MEDIUM

**Likely owner:** Governance

**Domain-level decision?** WEAK

---

### Decision D8: Evidence Integrity/Tamper-Proofing

**Statement:** How is it proven that evidence has not been tampered with?

**What must be decided:**
- What integrity mechanism (hash, signature, ledger?)
- Who computes integrity proof
- Who validates proof
- What counts as "tampered"
- How is integrity proven to external parties

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Evidence | MAYBE | Evidence could own integrity proofs |
| Verification | LIKELY | Verification Context proves evidence validity |
| Security | MAYBE | Integrity is security concern |
| Cryptography | MAYBE | Technical concern; not domain |

**Authority Test:**

Who decides: "Evidence integrity will be proven via SHA256 hash"?

Current evidence:
- VR-1 (independent verifiability) requires this
- Deferred to Verification Context (future phase)
- Currently unresolved

**Confidence in ownership:** LOW

**Likely owner:** Verification Context (future)

**Domain-level decision?** STRONG

This is a meaningful domain decision about evidence admissibility/verifiability.

---

## Decision Ownership Matrix

| Decision | Governance | Evaluation | Evidence | Verification | Election | Membership |
|----------|-----------|-----------|----------|--------------|----------|-----------|
| **D1: Freezing Policy** | OWNER | — | Enforcer | — | — | — |
| **D2: Retention Period** | OWNER | — | Enforcer | — | — | — |
| **D3: Privacy Rules** | OWNER | Consumer | Enforcer | Consumer | — | — |
| **D4: Authority Recording** | Definer | Owner | Enforcer | Consumer | — | — |
| **D5: Evidence Release** | OWNER | Consumer | Enforcer | Consumer | — | — |
| **D6: Admissibility** | Definer | OWNER | Provider | Owner | — | — |
| **D7: Appeal/Revision** | OWNER | Enforcer | — | — | Consumer | — |
| **D8: Integrity Proof** | Definer | — | Provider | OWNER | — | — |

**Legend:**
- **OWNER** — This context makes the decision
- **Enforcer** — This context implements the decision made elsewhere
- **Consumer** — This context uses the decision
- **Provider** — This context supplies input (data, proofs) to decision
- **Definer** — This context sets constraints/standards
- **—** — No role

---

## Analysis: What Decisions Does Evidence Actually Own?

Counting from the matrix:

| Context | Owner Count | Enforcer Count | Domain-Level Decisions |
|---------|------------|----------------|------------------------|
| Governance | 5 | — | D1, D2, D5, D7, (D4, D8 definer) |
| Evaluation | 2 | 1 | D4, D6 (partly) |
| Evidence | 0 | 5 | **None** |
| Verification | 2 | — | D6, D8 (future) |

---

## Critical Observation

**Evidence Context owns ZERO domain-level decisions.**

Evidence:
- ✅ Enforces decisions made by Governance (D1, D2, D3, D5)
- ✅ Provides data/integrity to other contexts (D4, D6, D8)
- ❌ Makes NO independent domain decisions

---

## The Decision Test Result

**Question:** If Evidence Context disappeared tomorrow, which constitutional decisions could no longer be made?

**Answer:** None.

Other contexts would:
- Find another way to store frozen/private/retained records (Infrastructure)
- Or directly implement freezing/retention (Governance)
- Or pass through to different storage (Evaluation, Verification)

Evidence is **replaceable** at the decision level.

---

## Classification Based on Decision Ownership

### Evidence Context is NOT:

❌ **Core Domain** — Does not make domain-level decisions
❌ **Supporting Domain** — Does not support core domain decisions through decision-making (only through data storage)
❌ **Independent Bounded Context** — Does not own meaningful domain decisions

### Evidence Context is likely:

✅ **Infrastructure Capability** — Enforces policies decided elsewhere
✅ **Generic Subdomain** — Retention/freezing/privacy are generic concerns, not domain-specific
✅ **Policy Enforcement Layer** — Implements Governance policies

---

## Decision Ownership Confidence Summary

| Decision | Owned By | Evidence Owns? | Confidence |
|----------|----------|---|---|
| D1: Freezing | Governance | NO | MEDIUM |
| D2: Retention | Governance | NO | HIGH |
| D3: Privacy | Governance | NO | MEDIUM |
| D4: Authority Recording | Evaluation (with Governance definer) | NO | MEDIUM |
| D5: Evidence Release | Governance | NO | MEDIUM |
| D6: Admissibility | Evaluation (with Verification future) | NO | HIGH |
| D7: Appeal/Revision | Governance | NO | MEDIUM |
| D8: Integrity Proof | Verification (future) | NO | MEDIUM |

---

## The Question That Decides

**Does Evidence Context make any constitutional decisions that cannot be delegated to Governance, Evaluation, or Infrastructure?**

**Current answer: NO**

Evidence enforces, stores, and provides data. But it does not decide.

---

## Implications for Evidence Context

### If Classification is "Infrastructure Capability"

Then:
- ✅ Round 6A-6C analysis is still valid
- ✅ Invariants (A-1, A-2, B-1, B-3) are valid
- ✅ Can proceed to 6D (Stress Test)
- ❌ Evidence is NOT a bounded context
- ❌ Evidence is NOT eligible for tactical DDD design
- ✅ Evidence is infrastructure policy + storage pattern

### If Classification is "Generic Subdomain"

Then:
- ✅ Evidence has a clear, reusable domain
- ✅ Could exist in multiple contexts (not just Election)
- ✅ Has invariants and policies
- ❌ Still not a core/supporting domain

### Implications for Future Phases

- Do NOT create Domain/ folder for Evidence
- DO create Infrastructure/Policies/ folder
- DO treat as shared library across contexts
- DO NOT use Tactical DDD (aggregates, repositories, domain events)
- DO use Policy Enforcement pattern

---

## Open Question: Does This Change D Ownership?

Earlier analysis asked: "Does Evidence own authority metadata?"

**New answer:** It doesn't matter.

Whether Evidence owns or stores authority:
- Evidence still makes NO constitutional decisions about authority
- Authority ownership belongs to Governance/Evaluation
- Evidence's role (store/enforce) is unchanged

---

## Next Steps

**Round 6D — Stress Test**

Test Evidence as **Infrastructure Capability**, not as bounded context:
- Do invariants (A-1, A-2, B-1, B-3) hold under election scenarios?
- Does Evidence enforce policies from other contexts consistently?
- Are there hidden decision points that suggest Evidence is more than infrastructure?

**Round 6E — Final Classification**

Decide:
- Evidence Context: Infrastructure Capability? Generic Subdomain? Something else?
- Where does Evidence live architecturally?
- What pattern should it follow?

---

**Status: Round 6C.5 complete. Evidence Context owns ZERO domain-level decisions. Evidence is Infrastructure Capability or Generic Subdomain, not an independent bounded context. Decision ownership is clear; does not belong to Evidence.**
