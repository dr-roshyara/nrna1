# Round 6C.6 — Verification Decision Analysis

**Date:** 2026-06-03  
**Objective:** Identify verification decisions required in two election modes; determine if Verification is a core domain  
**Critical Insight:** Evidence is a supporting capability; Verification is the core domain question

---

## Two Election Modes

The system must support fundamentally different verification problems:

### Mode 1: Election Only

**Scenario:** Organization conducts election, receives anonymous votes, wants to verify the result is correct.

**Question:** Was the election conducted correctly?

**Verification Target:** Election Result (aggregate vote counts)

**Verification Consumers:** Election observers, governance authority, public certification

---

### Mode 2: Full Membership

**Scenario:** Organization with membership rolls conducts election. Before votes are counted, must verify who is eligible to vote.

**Question:** Is this person eligible to vote?

**Verification Target:** Individual Member Legitimacy (per-voter)

**Verification Consumers:** Election administrators, eligibility authorities, individual members (appeals)

---

## Verification Decisions in Mode 1 (Election Only)

### Decision V1-1: Election Result Integrity

**Statement:** How is the integrity of election results verified?

**What must be decided:**
- What constitutes proof that votes are counted correctly
- Can verifiers recount votes independently
- What audit data is required
- Who can verify results
- Can results verification be challenged

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Verification | LIKELY | Verification decides what proof is sufficient |
| Election | MAYBE | Election conducts voting; could own result verification |
| Governance | MAYBE | Governance defines result certification standards |
| Evidence | NO | Evidence provides data; doesn't verify it |

**Authority Test:**

Who decides: "These vote counts are correct and verifiable"?

This is a domain decision about:
- What constitutes mathematical proof
- What audit data is necessary
- How independent verification works

**Confidence in ownership:** HIGH

**Likely owner:** Verification Context

**Domain-level decision?** YES — This is core to election legitimacy.

---

### Decision V1-2: Result Certification Authority

**Statement:** Who has authority to certify election results as correct?

**What must be decided:**
- Which parties must agree on certification
- Can certification be challenged
- What happens if parties disagree
- How is disagreement resolved
- Can certification be revoked

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Governance | LIKELY | Governance defines certification authority |
| Verification | MAYBE | Verification provides technical proof |
| Election | MAYBE | Election could decide cert authority |

**Authority Test:**

Who decides: "Observer A, Observer B, and Governance Authority must all certify results"?

This is a governance/constitutional question, not a technical verification question.

**Confidence in ownership:** HIGH

**Likely owner:** Governance (defines authorities)

**Domain-level decision?** YES — Governance decision about constitutional process.

---

### Decision V1-3: Verification Failure Response

**Statement:** What happens if verification fails or results don't match?

**What must be decided:**
- Is recount required
- Who initiates recount
- Can disputed election still be certified
- What makes results disputed vs. invalid
- Appeal/challenge window

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Governance | LIKELY | Governance decides dispute resolution |
| Verification | MAYBE | Verification identifies discrepancies |
| Election | MAYBE | Election could decide failure handling |

**Authority Test:**

Who decides: "If verification fails, recount is mandatory"?

This is a governance decision about election process.

**Confidence in ownership:** HIGH

**Likely owner:** Governance

**Domain-level decision?** YES — Constitutional process decision.

---

## Verification Decisions in Mode 2 (Full Membership)

### Decision V2-1: Member Eligibility Verification

**Statement:** How is individual member eligibility verified?

**What must be decided:**
- What sources of truth determine eligibility
- Can multiple eligibility authorities decide (all must agree? any can approve?)
- What evidence is sufficient
- Can eligibility be verified independently
- Who can challenge eligibility determination

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Verification | LIKELY | Verification decides what proof is sufficient |
| Membership | MAYBE | Membership owns membership rules |
| Governance | MAYBE | Governance sets eligibility standards |
| Evaluation | MAYBE | Evaluation interprets eligibility evidence |

**Authority Test:**

Who decides: "This member is eligible because Authority A and Authority B both approved"?

This is a core domain decision about:
- What constitutes sufficient proof of eligibility
- How multiple authorities combine
- What can override eligibility

**Confidence in ownership:** HIGH

**Likely owner:** Verification Context (with Governance/Membership as input)

**Domain-level decision?** YES — This is core to member participation.

---

### Decision V2-2: Eligibility Appeal/Challenge

**Statement:** Can member eligibility be challenged or appealed?

**What must be decided:**
- Who can appeal eligibility decision
- On what grounds (new evidence? procedural error? authority disagreement?)
- What constitutes successful appeal
- Can eligibility be revoked post-election
- Who makes final appeal decision

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Governance | LIKELY | Governance decides appeal process |
| Verification | MAYBE | Verification re-evaluates on appeal |
| Membership | MAYBE | Membership could decide appeal authority |

**Authority Test:**

Who decides: "Member can appeal if new evidence emerges"?

This is a governance/constitutional decision about due process.

**Confidence in ownership:** HIGH

**Likely owner:** Governance

**Domain-level decision?** YES — Constitutional process decision.

---

### Decision V2-3: Authority Disagreement Resolution

**Statement:** If authorities disagree on eligibility, how is disagreement resolved?

**What must be decided:**
- Which authority has final say (precedence)
- Can member participate if authorities disagree
- Does disagreement trigger appeal
- What counts as "disagreement"
- Can disagreement be appealed to Governance

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Governance | LIKELY | Governance defines authority precedence |
| Verification | MAYBE | Verification identifies disagreement |
| Membership | MAYBE | Membership could own precedence |

**Authority Test:**

Who decides: "If Authority A approves but Authority B rejects, Authority A wins"?

This is a governance decision about constitutional hierarchy.

**Confidence in ownership:** HIGH

**Likely owner:** Governance

**Domain-level decision?** YES — Constitutional hierarchy.

---

### Decision V2-4: Legitimacy Lifecycle

**Statement:** Can member legitimacy change over time?

**What must be decided:**
- Is eligibility fixed at election time or dynamic
- If dynamic, what triggers change (membership revocation, authority decision, time)
- Can legitimacy be revoked post-vote
- What happens to already-cast votes if legitimacy revoked
- Can legitimacy be restored

**Context Ownership Test:**

| Context | Could own? | Reasoning |
|---------|-----------|-----------|
| Membership | LIKELY | Membership owns membership status changes |
| Verification | MAYBE | Verification re-evaluates legitimacy |
| Governance | MAYBE | Governance decides policy |

**Authority Test:**

Who decides: "Member legitimacy changes immediately if membership revoked"?

This is a governance decision about how membership affects election rights.

**Confidence in ownership:** MEDIUM

**Likely owner:** Governance (with Membership and Verification as executors)

**Domain-level decision?** YES — Constitutional relationship between membership and voting.

---

## Verification Decision Matrix

| Decision | Governance | Verification | Membership | Election | Evidence |
|----------|-----------|--------------|-----------|----------|----------|
| **V1-1: Result Integrity** | Standard-setter | OWNER | — | Consumer | Provider |
| **V1-2: Cert Authority** | OWNER | Executor | — | Consumer | — |
| **V1-3: Failure Response** | OWNER | Identifier | — | Executor | — |
| **V2-1: Eligibility Verify** | Standard-setter | OWNER | Input-provider | Consumer | Provider |
| **V2-2: Appeal Rights** | OWNER | Executor | Executor | Consumer | — |
| **V2-3: Authority Conflict** | OWNER | Identifier | Identifier | Consumer | — |
| **V2-4: Legitimacy Lifecycle** | OWNER | Re-evaluator | Provider | — | — |

**Legend:**
- **OWNER** — Makes the decision
- **Executor** — Implements the decision
- **Standard-setter** — Defines constraints
- **Provider** — Supplies input (data, evidence)
- **Identifier** — Identifies problem/discrepancy
- **Consumer** — Uses the decision
- **Re-evaluator** — Recalculates based on change
- **—** — No role

---

## Analysis: Who Owns Verification?

From the matrix:

| Context | Owner Count | Executor Count | Domain-Level Decisions |
|---------|------------|----------------|------------------------|
| **Verification** | **2** | **2** | V1-1, V2-1 (core decisions) |
| **Governance** | **4** | **3** | V1-2, V1-3, V2-2, V2-3, V2-4 |
| **Membership** | **0** | **1** | — |
| **Election** | **0** | **1** | — |
| **Evidence** | **0** | **0** | — |

---

## Critical Observation

**Verification owns 2 core domain decisions:**
1. V1-1: How is election result integrity verified? (proof of correctness)
2. V2-1: How is member eligibility verified? (proof of eligibility)

Both are **"What constitutes proof?"** questions — the quintessential verification domain problem.

---

## The Verification Test

**Question:** If Verification Context disappeared tomorrow, which constitutional decisions could no longer be made?

**Answer:** 
- Cannot prove election results are correct
- Cannot prove members are eligible
- Cannot detect fraud or discrepancies
- Cannot certify legitimacy

**Implication:** Verification is CRITICAL to constitutional trust.

---

## Comparison: Evidence vs. Verification

| Capability | Domain-Level Decisions | Owns Critical Path? | Replaceable? |
|-----------|---|---|---|
| **Evidence** | 0 | NO | YES (other storage possible) |
| **Verification** | 2 | YES | NO (core to legitimacy proof) |

**Conclusion:** Verification, not Evidence, is the core domain.

---

## Working Hypotheses After Round 6C.6

**These are hypotheses based on decision analysis. They must be stress-tested in Round 6D before becoming architectural conclusions.**

### Hypothesis A: Multi-Level Domain Structure

| Context | Hypothesized Role |
|---------|---|
| **Governance** | Defines constitutional rules; owns many decisions |
| **Verification** | Owns proof decisions; may be core or supporting |
| **Election** | Conducts voting; may be core or supporting |
| **Membership** | Supplies eligibility input |
| **Evaluation** | Interprets evidence |
| **Evidence** | Stores frozen facts; likely supporting or infrastructure |

### Hypothesis B: Deeper Core Domain

A higher-level domain may exist that combines Governance + Verification + Evidence:

```
Constitutional Trust / Legitimacy / Sovereignty Assurance
    ├── Governance (defines what's legitimate)
    ├── Verification (proves legitimacy)
    └── Evidence (preserves proof material)
```

This would mean Verification and Evidence are not independent domains, but collaborating parts of a larger core domain.

### Hypothesis C: Strategic Differentiator

The core domain question may not be:

```text
"Is Verification a domain?"
```

but rather:

```text
"What makes NRNA unique?"
```

Possible answers:
- Privacy-preserving verification (Evidence + Verification together)
- Constitutional legitimacy proof (Governance + Verification + Evidence)
- Evidence-backed trust (Evidence as domain, not infrastructure)

This requires testing against competitor analysis, not just decision analysis.

---

## The Two Verification Models

### Mode 1: Election Only (Aggregate Verification)

```
Governance defines:
  - What "correct election" means
  - Which parties must certify
  - What happens on disagreement

Verification proves:
  - Vote counts are mathematically correct
  - Votes match ballots
  - No votes were lost/added

Evidence provides:
  - Vote records
  - Audit trail
  - Integrity proofs (future)

Result:
  - Certification of election correctness
```

### Mode 2: Full Membership (Individual Verification)

```
Governance defines:
  - Eligibility standards
  - Authority precedence
  - Appeal process
  - Membership relationship to voting

Membership provides:
  - List of members
  - Membership status
  - Authority assignments

Verification proves:
  - Member is eligible
  - All required authorities agree (or precedence resolves)
  - No status change invalidates eligibility

Evaluation interprets:
  - Authority decisions
  - Member status

Evidence provides:
  - Authority approval records
  - Status history
  - Integrity proofs (future)

Result:
  - Certification of member eligibility
```

---

## Evidence Context: Hypothesized Role

This analysis suggests Evidence's role, but does not prove it:

**Hypothesis:** Evidence is a supporting capability providing proof material.

**Alternative Hypotheses:**
1. Evidence is infrastructure (storage + policy enforcement)
2. Evidence is part of larger "Constitutional Trust" domain
3. Evidence is its own supporting domain (chain of custody, tamper-proof records)
4. Evidence is core domain (if legal/regulatory requirements treat it as such)

**Why the ambiguity?**

Some legal systems treat evidence as a domain concept:
- Chain of custody (provenance)
- Privacy guarantees (rights)
- Tamper evidence (integrity)
- Proof preservation (constitutional requirement)

If NRNA treats evidence this way, Evidence Context becomes domain-level, not infrastructure.

**To be determined in Round 6D:** Whether Evidence's domain characteristics (privacy, immutability, chain of custody) make it a supporting domain rather than infrastructure.

---

## Next Steps

### Proceed to Round 6D

**Stress Test Verification (not Evidence):**
1. Can Verification handle both modes simultaneously?
2. Do verification decisions hold under election scenarios?
3. Are there hidden decision points in Verification?
4. Can Verification be cleanly separated from Election/Governance?

### After 6D

**Proceed to Round 7: Context Mapping**

Map:
- Governance → Verification: defines standards
- Verification → Election: certifies legitimacy
- Verification → Membership: verifies eligibility
- Verification → Evaluation: interprets evidence
- Verification → Evidence: consumes proof material
- Governance → All: sets constitutional rules

---

## Architectural Hypotheses (to be tested in Round 6D)

**What started as:** "Is Evidence Context a bounded context?"

**Became:** "What is the core verification problem?"

**Discovered:** Multiple domains appear to own constitutional decisions, with Verification appearing to own "what constitutes proof."

**Key Hypotheses:**
1. Verification owns core proof decisions (V1-1, V2-1)
2. Governance owns constitutional decisions (V1-2, V1-3, V2-2, V2-3, V2-4)
3. Evidence may be infrastructure, supporting domain, or part of larger domain
4. A deeper "Constitutional Trust" domain may encompass Governance + Verification + Evidence

**What is NOT proven yet:**
- Whether Verification is core or supporting domain
- Whether Evidence is infrastructure or domain-level
- Whether the core domain is "Verification" or "Constitutional Trust" or something else
- Whether the current model breaks under stress testing

---

**Status: Round 6C.6 complete. Verification decision matrix created. Two election modes analyzed. Multiple architectural hypotheses identified. Ready for Round 6D: Verification Stress Test to validate or refute these hypotheses.**
