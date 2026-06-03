# Round 6C — Candidate Boundaries

**Date:** 2026-06-03  
**Objective:** Test whether a clear boundary can be drawn between Evidence and Evaluation contexts  
**Constraint:** Strategic analysis only. No tactical design, no implementation, no architectural diagrams that assume structure.

---

## Methodology

For each context (Evidence candidate, Evaluation), define:

1. **What it owns** — Responsibilities and invariants (from Rounds 6A-6B)
2. **What it provides** — Data, policies, or decisions it exports to other contexts
3. **What it consumes** — Data or decisions it imports from other contexts
4. **Boundary clarity** — Is the line between them clear?
5. **Unresolved ambiguities** — What remains unclear?

**Success = Boundaries are clear enough to proceed; ambiguities are explicitly documented**

---

## Evidence Context (Candidate) Ownership

### What Evidence Context Owns

From Round 6B, these invariants/responsibilities:

| Item | Status | Evidence |
|------|--------|----------|
| A-1: Evidence Freezing | STRONG | SecurityEventRecorder, operationally demonstrated |
| A-2: Evidence Retention | STRONG | SecurityEventRecorder, operationally demonstrated |
| B-1: No Voter Identity | STRONG | SecurityEventRecorder, operationally demonstrated |
| B-3: Privacy by Design | STRONG | SecurityEventRecorder design principle |
| A-3: Post-Eval Freezing | WEAK | Inferred; appeals/audits may contradict |
| D-1: Authority Chain | UNKNOWN | Ownership unresolved |
| D-2: Authority Immutability | UNKNOWN | Depends on D-1 |

**Core claim:** Evidence Context owns A-1, A-2, B-1, B-3 (what evidence must be and how it must behave).

**Uncertain claim:** Evidence Context may own D-1, D-2 (what evidence must contain).

### What Evidence Context Provides (Exports)

To other contexts, Evidence makes available:

1. **Frozen Evidence Records**
   - Immutable snapshots of observations at a point in time
   - With voter identity removed
   - With retention guarantee (730+ days)
   - Format: structured data (what structure? TBD by 6D)

2. **Privacy Guarantees**
   - "These records cannot re-identify voters"
   - "These records were captured at specified timestamp"
   - Applied uniformly to all evidence

3. **Authority Metadata** (if D-1 is owned by Evidence)
   - Record of which authorities evaluated participation
   - In what order
   - With what outcome
   - *NOT YET DETERMINED*

### What Evidence Context Consumes (Imports)

From other contexts, Evidence needs:

1. **Observations** (from Observation Context OR Election Context)
   - Facts about voters, elections, trust signals
   - Timing information
   - Who performed evaluation
   - What decision was made
   - Format: TBD

2. **Configuration** (from Governance Context?)
   - Retention period (730 days, or configurable?)
   - Which fields must be redacted
   - Privacy thresholds
   - Authority definitions (if not owned by Evidence)

3. **Authority Information** (from Evaluation Context? OR owned by Evidence?)
   - Which authorities evaluated
   - What they decided
   - Timeline of decisions
   - *OWNERSHIP UNRESOLVED*

---

## Evaluation Context Ownership

### What Evaluation Context Owns

| Item | Status | Evidence |
|------|--------|----------|
| Interpretation of evidence | STRONG | "What does this evidence mean?" is evaluation work |
| Decision-making | STRONG | Evaluation decides legitimacy |
| Authority consulting | STRONG | Evaluation decides which authorities to invoke |
| Evaluation policies | STRONG | Thresholds, rules for legitimacy determination |
| Appeals/re-evaluation | STRONG | Evaluation can change decisions |

**Core claim:** Evaluation Context owns the semantics ("what evidence means") and the decisions ("is this voter legitimate?").

### What Evaluation Context Provides (Exports)

To other contexts, Evaluation makes available:

1. **Legitimacy Decisions**
   - "This voter is legitimate" or "is not legitimate"
   - With confidence level
   - With reasoning (which authorities were consulted)
   - Timestamp of decision

2. **Authority Trail** (if NOT owned by Evidence)
   - Which authorities were consulted
   - What they decided
   - In what order
   - Used by Legitimacy/Governance to know if decision is appealable

3. **Re-evaluation Capability**
   - "This decision can be appealed"
   - "This evidence can be re-evaluated"
   - Signal that new evidence may change decision

### What Evaluation Context Consumes (Imports)

From other contexts, Evaluation needs:

1. **Evidence Records** (from Evidence Context)
   - Frozen observations about voter/election
   - Authority metadata (if provided by Evidence)
   - OR just facts, and Evaluation adds authority layer

2. **Authority Policies** (from Governance Context OR creates own)
   - Which authorities are recognized
   - What their approvals mean
   - Trust levels of different authorities

3. **Appeal Triggers** (from Governance? OR defines own)
   - When re-evaluation is allowed
   - What counts as new evidence
   - Appeal windows

---

## Boundary Test 1: "What happened?" vs. "What does it mean?"

**Evidence Context:** "What happened?"
- "At timestamp T, voter V showed trust signal S"
- "Authority A made decision D"
- "These facts are frozen and private"

**Evaluation Context:** "What does it mean?"
- "These trust signals indicate legitimacy"
- "Authority A's decision is sufficient for approval"
- "Legitimacy decision is: APPROVED"

**Clarity:** ✅ CLEAR

The boundary between "facts" and "interpretation" is conceptually clean.

---

## Boundary Test 2: Authority Ownership (CRITICAL)

This is where the boundary becomes unclear.

### Model A: Evidence owns authority

```
Evidence Context owns:
  - Observation data (what happened)
  - Authority information (who approved)
  - Privacy preservation
  - Immutability

Evaluation Context owns:
  - Interpretation (what it means)
  - Decision rules
  - Appeals/re-evaluation
```

**Implication:** Evidence is a domain-level bounded context (owns semantic data about authorities).

**Problems:**
- Authority information is not "what happened" (factual); it's a policy decision
- "Which authorities to consult" is evaluation work, not evidence work
- Creates tight coupling: Evidence must understand authority semantics

### Model B: Evaluation provides authority to Evidence

```
Evaluation Context owns:
  - Observation data (from other sources)
  - Authority information (which authorities decided)
  - Authority policies (what approvals mean)
  - Decision rules
  - Appeals/re-evaluation

Evidence Context owns:
  - Privacy preservation
  - Immutability
  - Retention
  - Freezing
```

**Implication:** Evidence is infrastructure for decisions (frozen, private, retained records).

**Problems:**
- Evidence still has invariants (A-1, A-2, B-1, B-3) that are domain-level
- Authority data is "what happened" (a fact about the evaluation)
- Evidence Context may be a subdomain of Evaluation

### Model C: Shared responsibility (Evidence captures, Evaluation owns semantics)

```
Evidence Context owns:
  - Storing what Evaluation supplies
  - Privacy preservation
  - Immutability
  - Retention
  - No interpretation of authority

Evaluation Context owns:
  - Authority semantics
  - What authorities mean
  - Decision rules
  - Appeals
  - Supplies authority data to Evidence
```

**Implication:** Evidence is infrastructure + privacy policy; not a domain bounded context.

**Problems:**
- Still unclear when authority information enters Evidence
- Still unclear if Evidence has semantic responsibility for authority trail

---

## Boundary Clarity Assessment

| Boundary | Clarity | Status |
|----------|---------|--------|
| Observation → Evidence | MEDIUM | Is "observation recording" part of Evidence, or input to Evidence? |
| Evidence → Evaluation | MEDIUM-HIGH | "Facts" vs. "interpretation" is clear; "authority" is unclear |
| Evidence Privacy | HIGH | Privacy rules are clear; ownership (Evidence vs. policy) is unclear |
| Evidence Freezing | HIGH | Freezing is clear; scope (post-eval or not?) is unclear |
| Evidence Retention | MEDIUM | Retention period is clear; whether it's policy or domain rule is unclear |

---

## The Authority Question (Round 6C Focus)

### Does Evidence own authority metadata?

**If YES:**
- Evidence Context is responsible for "which authorities approved"
- Evidence Context becomes a domain-level bounded context
- Authority is treated as evidence of what happened
- Evidence answers: "What happened and who decided it?"

**If NO:**
- Evaluation Context owns authority metadata
- Evidence Context is infrastructure + policy layer
- Authority is evaluation's responsibility, not evidence's
- Evidence answers: "What facts are frozen/private/retained?"

### Evidence from Rounds 1–6.0

| Indicator | Suggests | Weight |
|-----------|----------|--------|
| Domain events carry `voterIdentifier` (differ from SecurityEventRecorder null) | Different models; maybe Evidence doesn't own authority | MEDIUM |
| SecurityEventRecorder records AFTER evaluation | Evidence is downstream; evaluation supplies authority | MEDIUM |
| D-1/D-2 marked LOW-MEDIUM confidence | Inferred, not operational; ownership unresolved | MEDIUM |
| VR-5 (replayability) requires authority chain | Authority data needed for verification | MEDIUM |
| ConstitutionalEvidenceSnapshot hypothesis includes "evaluatedAt" | Hypothesis suggests Evidence owns authority | MEDIUM |
| Five domain events inactive; no dispatch path | Authority data not yet integrated; unclear if planned | MEDIUM |
| SecurityEventRecorder is operational; domain events are not | Infrastructure exists; domain layer doesn't | HIGH |

**Assessment:** Evidence leans toward NOT owning authority (Model B or C), but ownership remains unresolved.

---

## Boundary Coherence: If Ownership Were Resolved

### Scenario 1: Evidence owns authority (Model A)

Evidence Context boundary:
```
Owns:
  - A-1: Evidence Freezing
  - A-2: Evidence Retention
  - B-1, B-3: Privacy Preservation
  - A-3: Post-eval Freezing (candidate)
  - D-1, D-2: Authority Chain (candidate)

Provides:
  - Frozen, private, authorized evidence records

Consumes:
  - Observations/facts (from Observation or Election)
  - Governance policies (retention, privacy thresholds)

Invariants: A-1, A-2, B-1, B-3, (A-3), (D-1, D-2)
```

**Assessment:** Boundaries would be coherent; Evidence would be a domain-level BC owning observations + authority + privacy.

### Scenario 2: Evaluation owns authority (Model B/C)

Evidence Context boundary:
```
Owns:
  - A-1: Evidence Freezing
  - A-2: Evidence Retention
  - B-1, B-3: Privacy Preservation
  - A-3: Post-eval Freezing (candidate)

Provides:
  - Frozen, private, retained records (no authority)

Consumes:
  - Observations from Evaluation
  - Privacy/retention policies from Governance
  - Authority data from Evaluation (stores but doesn't own semantics)

Invariants: A-1, A-2, B-1, B-3, (A-3)
```

**Assessment:** Boundaries would be coherent; Evidence would be a data/policy layer serving Evaluation Context.

---

## Unresolved Boundary Ambiguities

1. **Authority ownership (D-1/D-2)** — Is authority metadata owned by Evidence or Evaluation?
   - Affects: Whether Evidence is domain-level or infrastructure
   - Status: Cannot resolve from Rounds 1–6.0 without author input

2. **Observation recording (C)** — Is "capturing observations" part of Evidence or upstream?
   - Affects: What Evidence consumes vs. owns
   - Status: Unresolved from 6A; not clarified in 6C

3. **Post-eval freezing (A-3)** — Are appeals allowed?
   - Affects: Whether A-3 invariant holds
   - Status: Weak evidence; needs validation

4. **Privacy scope (B-2)** — What field combinations should be prohibited?
   - Affects: How strict B-1/B-3 rules are
   - Status: Requires security review

5. **Retention policy (A-2)** — Is 730 days a domain rule or governance policy?
   - Affects: Whether Evidence or Governance owns retention
   - Status: Unclear

---

## Boundary Coherence Summary

| Question | Answer | Confidence |
|----------|--------|------------|
| Can Evidence/Evaluation boundary be drawn? | YES | HIGH |
| Is boundary clear without authority? | YES (A-1, A-2, B-1, B-3) | HIGH |
| Is boundary clear with authority (D)? | NO | N/A |
| Does Evidence have sufficient invariants to be a BC? | YES (A, B) | MEDIUM |
| Does Evidence need D to be a proper BC? | MAYBE | LOW |
| Can boundary survive if Evidence doesn't own D? | YES | HIGH |
| Is Evidence Context coherent as infrastructure? | YES | MEDIUM |
| Is Evidence Context coherent as domain BC? | YES (with D); MAYBE (without D) | MEDIUM |

---

## Boundary Test Results

### ✅ Clear Boundaries

- **Observation Recording** (what Evidence captures)
- **Privacy Preservation** (what Evidence guarantees)
- **Freezing/Retention** (what Evidence enforces)
- **Privacy vs. Governance** (Evidence owns privacy; Governance owns policy)

### ⚠️ Ambiguous Boundaries

- **Authority Ownership** (does Evidence own D-1/D-2, or does Evaluation?)
- **Post-Evaluation Updates** (does A-3 hold, or are appeals allowed?)
- **Policy vs. Domain Rule** (is retention a domain invariant or governance policy?)

### 🔄 Unresolved

- **Observation Recording Ownership** (Evidence captures, but who decides what to capture?)
- **Appeal Boundaries** (if appeals allowed, who owns re-evaluation logic?)

---

## Impact on Evidence Context Classification

### If Authority Ownership Is Resolved to: Evidence owns D

✅ **Conclusion:** Evidence is a domain-level bounded context
- Owns observations, authority trail, privacy preservation
- Coherent with A-1, A-2, B-1, B-3, D-1, D-2
- Provides: frozen, private, authorized evidence records
- Consumes: observations and policies
- **Readiness for 6D:** Ready to proceed to stress testing

### If Authority Ownership Is Resolved to: Evaluation owns D

✅ **Conclusion:** Evidence is an infrastructure + policy layer
- Owns freezing, privacy, retention (policies, not domain logic)
- Coherent with A-1, A-2, B-1, B-3
- Provides: frozen, private, retained records
- Consumes: observations and policies
- **Readiness for 6D:** Ready to proceed, but with different scope

### If Authority Ownership Remains Unresolved

⚠️ **Conclusion:** Boundary is ambiguous but testable
- Evidence Context is coherent around A-1, A-2, B-1, B-3 (core invariants)
- D-1, D-2 remain candidate-level until ownership clarified
- **Readiness for 6D:** Can proceed with explicit assumption: "D ownership is deferred to author clarification"

---

## Next Step

**Recommended:** Proceed to Round 6D — Stress Test

**Evaluate:**
1. Test A-1, A-2, B-1, B-3 invariants against election scenarios (from EvidenceScenarioCatalog)
2. Determine if Evidence Context invariants remain consistent under stress
3. Identify which scenarios reveal D ownership question
4. Test whether post-eval freezing (A-3) holds under appeals/audits

**Conditional:** If stress test reveals that D ownership must be resolved before proceeding, defer author clarification to after 6D.

---

**Status: Round 6C complete. Evidence/Evaluation boundary is testable. Authority ownership (D) remains the critical unresolved question. Evidence Context is coherent around A-1, A-2, B-1, B-3 regardless of D resolution.**
