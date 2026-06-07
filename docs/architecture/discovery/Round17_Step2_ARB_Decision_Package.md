# Round 17 — Step 2 ARB Decision Package

**Date:** 2026-06-07

**Purpose:** Provide a neutral governance decision package for ARB evaluation of whether Step 2 discovery is complete, requires Stream 3 execution, or needs additional discovery.

---

## 1. Executive Summary

### Streams Executed

| Stream | Document | Status |
|--------|----------|--------|
| Stream 1 — Evidence Analysis | `Round17_Stream1_Evidence_Findings.md` | Approved in Evidence Corpus |
| Stream 2 — Governance & Authority | `Round17_Stream2_Governance_Authority_Findings.md` | Approved in Evidence Corpus |
| Stream 4 — Audit Clarification | `Round17_Stream4_Audit_Clarification_Findings.md` | Approved in Evidence Corpus |
| Stream 5 — Constitutional Rule Discovery | `Round17_Stream5_Constitutional_Rule_Discovery_Findings.md` | Approved in Evidence Corpus |
| Stream 6A — Challenge Presence Assessment | `Round17_Stream6_Dispute_Challenge_Findings.md` | Approved in Evidence Corpus |
| Stream 6B — Invocation & Consequence Analysis | `Round17_Stream6B_Invocation_Consequence_Findings.md` | Ready for acceptance |

### Outstanding

| Stream | Status |
|--------|--------|
| Stream 3 — Voting/Tally Relationship | Authorized but not executed |

### Evidence Corpus Size

| Artifact | Count |
|----------|-------|
| Stream findings documents | 6 |
| Evidence artifacts cataloged | 30+ across all streams |
| Evidence interaction matrices | 5 |
| Relationship inventories | 4 |

### Supporting Registers

| Register | Items |
|----------|-------|
| Hypothesis Register | 23 hypotheses (H1-H23) |
| Discovery Debt Register | 38 items (D1-D38) |
| Assumption Register | 15 items (A1-A15) |

### Saturation Checkpoint

| Document | Status |
|----------|--------|
| `Round17_Step2_Evidence_Saturation_Checkpoint.md` | Complete |

---

## 2. Discovery Scope Covered

### What Was Investigated

| Area | Stream | Evidence Found |
|------|--------|---------------|
| Evidence artifacts and flows | Stream 1 | 11 artifacts, 9 relationships, Evidence→Trust→Capability chain |
| Governance and Authority relationship | Stream 2 | 10 interactions, 7 relationships, 4-check enforcement sequence |
| Audit mechanisms (operational vs replay) | Stream 4 | 7 artifacts, implementation separation documented |
| Constitutional rule structure and sources | Stream 5 | 6 rule categories, rule source classification matrix |
| Challenge/dispute handling presence | Stream 6A | Arbitration mechanisms cataloged, no explicit challenge submission found |
| Arbitration invocation and legitimacy consequences | Stream 6B | D36: Invocation path unresolved; D35: No operational consequence observed |

### What Was Not Investigated

| Area | Reason |
|------|--------|
| Vote recording and result projection coupling | Stream 3 — authorized but not executed |
| Organizational governance practices | Requires stakeholder interviews — not in Step 2 scope |
| Rule origin documents | Not found in repository — requires external sources |
| Officer appointment workflows | Partial investigation — lifecycle details remain |
| Constitutional amendment processes | Not found in repository |

### What Remains Unknown

**Highest-priority unknowns (HIGH STRATEGIC debt items):**

| ID | Question | Requires |
|----|----------|----------|
| D13 | What determines SUFFICIENT vs INSUFFICIENT evidence? | Policy intent or design rationale |
| D18 | Why is ParticipationEligibilityEvidence frozen, hashed, deterministic? | Governance intent |
| D22 | Where do constitutional rules originate? | Organizational sources |
| D30 | Are rules-in-code intentional or temporary? | Architect decision documentation |
| D35 | What happens when legitimacy = EXPIRED? | Enforcement mechanism (organizational or software) |
| D36 | Who may invoke ConstitutionalArbitrationKernel? | Invocation mechanism (organizational or software) |
| D37 | What enforces legitimacy status in practice? | Enforcement mechanism (organizational or software) |

---

## 3. Strategic Unknowns

### D13 — Evidence Sufficiency Determination

**Question:** What determines SUFFICIENT vs INSUFFICIENT evidence? What policies set sufficiency thresholds?

**Evidence currently available:**
- TrustEvaluationState enum includes SUFFICIENT_EVIDENCE and INSUFFICIENT_EVIDENCE states
- Evidence evaluation is part of TrustPolicyEvaluator and PolicySequence
- Evidence participates in governance capability decisions

**Evidence currently missing:**
- Sufficiency criteria definition
- Policy logic determining threshold
- Whether criteria are coded, configured, or organizational

**Resolution path:** PolicySequence investigation; architect interview on design intent

---

### D18 — Frozen Evidence Purpose

**Question:** Why is ParticipationEligibilityEvidence frozen, hashed, deterministic, and replay-addressable? This is atypical for ordinary audit logging.

**Evidence currently available:**
- Evidence is built during trust evaluation (TrustPolicyEvaluator.buildEligibilityEvidence)
- Has deterministic hash (eligibilityHash field)
- Comments describe it as designed for replay divergence detection
- Replay infrastructure is deferred to Phase 6

**Evidence currently missing:**
- Specific governance guarantee that hashing protects
- Whether replay integration is planned or speculative

**Resolution path:** Governance intent investigation; ADR archaeology

---

### D22 — Constitutional Rule Origin

**Question:** Where do constitutional state transition rules originate? Are they derived from organizational bylaws, domain requirements, or technical constraints?

**Evidence currently available:**
- Rules are defined in ElectionConstitution.RULES array
- Rules control all state transitions, roles, and preconditions
- No origin documentation found in examined repository

**Evidence currently missing:**
- Source documents or references for rule definitions
- Rationale for rule structure and selection
- Authority that defined the rules

**Resolution path:** Organizational document review; stakeholder interviews

---

### D30 — Rule-in-Code Design Intent

**Question:** Are rules hard-coded in PHP arrays by design (immutable constitutional protection) or temporary (pending rule engine implementation)?

**Evidence currently available:**
- All rules in ElectionConstitution.RULES — no configuration mechanism
- Changing rules requires code deployment
- Rules are NOT tenant-differentiated in examined implementation

**Evidence currently missing:**
- Architectural decision documentation
- Whether rule engine is planned
- Whether immutability is intentional constraint

**Resolution path:** ADR archaeology; architect interview

---

### D35 — Legitimacy Consequences

**Question:** What happens when ConstitutionalDecision determines legitimacy = EXPIRED? Does this automatically reverse the original governance decision, block future actions, or provide advisory information only?

**Evidence currently available:**
- Legitimacy determination exists (LegitimacyEvaluator uses temporal window)
- Legitimacy = EXPIRED is stored and persisted (GovernanceDecisionSnapshot)
- Integrity hashing enables tamper detection
- No operational enforcement consequence observed within examined implementation

**Evidence currently missing:**
- Any enforcement mechanism that reads EXPIRED status
- Whether enforcement is organizational (human governance body acts) or software-based
- Whether EXPIRED status is meant to reverse, block, or inform

**Resolution path:** Governance process observation; organizational procedure review

---

### D36 — Arbitration Invocation

**Question:** Who may invoke ConstitutionalArbitrationKernel? Is it restricted to specific authorities, available to anyone, or invoked automatically?

**Evidence currently available:**
- ConstitutionalArbitrationKernel exists in domain code with full implementation
- Kernel is testable — tests construct and invoke it directly
- No operational invocation path observed within examined implementation
- GovernanceDecisionStore (persistence) IS operationally wired

**Evidence currently missing:**
- Any controller, command, listener, or scheduled task invoking the kernel
- Authorization rules for kernel invocation
- Whether invocation is deferred, organizational, or test-only

**Resolution path:** Invocation mechanism investigation; DI container audit; architect interview

---

### D37 — Legitimacy Enforcement

**Question:** What mechanism enforces legitimacy status operationally? Does EXPIRED prevent new governance decisions? Does REVOKED trigger alerts?

**Evidence currently available:**
- GovernanceLegitimacy enum defines 7 states with isValid() method
- Database index on (legitimacy, decided_at) enables queryability
- No enforcement code observed within examined implementation

**Evidence currently missing:**
- Any consumer of legitimacy status that gates, blocks, or triggers
- Whether enforcement is organizational (manual review) or software-based

**Resolution path:** Trace upstream legitimacy consumers; governance process review

---

## 4. Stream 3 Status

### Original Objective

Stream 3 (Voting/Tally Boundary Analysis) was intended to investigate whether vote recording and result projection are computationally coupled or separate operational concerns.

### Current Status

**Not executed.** Stream 3 is authorized but no evidence collection has been performed.

### Related Hypotheses

| ID | Hypothesis | Current Status |
|----|-----------|---------------|
| H7 | Voting and tallying are operationally coupled | Strengthening |
| H8 | Voting and tallying are operationally distinct | Weakening |
| H9 | Observed coupling may relate to one or more operational or integrity concerns | Open |

### Potential Evidence Areas

If Stream 3 were executed, it would examine:
- Vote submission controllers (e.g., VoteController, DemoVoteController)
- Result calculation/update code
- Vote table and Result table schemas
- Any temporal separation between vote recording and result projection

**Note:** Stream 3 investigates the primary election processing path (voting and tallying are the core electoral mechanics) and therefore may reveal relationships not visible through governance-focused streams. This is noted for ARB awareness, not as a recommendation.

---

## 5. Non-Repository Unknowns

### Stakeholder Interviews

| Debt | Question |
|------|----------|
| D1 | What is Legitimacy in this organization? |
| D2 | Who actually resolves disputes? |
| D3 | What are the actual governance practices? |
| D6 | Relationship between legitimacy and authority |
| D8 | What is Trust in this governance model? |

### ADR Archaeology / Architect Interviews

| Debt | Question |
|------|----------|
| D4 | Why are voter identity and votes structurally separated? |
| D20 | Is SecurityEventRecorder intended to support replay? |
| D30 | Are rules-in-code intentional immutability or temporary? |
| D22 | Where do constitutional rules originate? |
| D25 | Where do frozen policies come from? |

### Governance Document Review

| Debt | Question |
|------|----------|
| D7 | Can constitutional rules be amended? |
| D9 | What evidence standards are required for governance? |
| D26 | What authority established suspension rules? |
| D33 | How are decisions submitted for review? |

### Business Model / Policy Documentation

| Debt | Question |
|------|----------|
| D27 | Where did the 40-voter threshold originate? |
| D24 | Why is capacity_eligibility a precondition? |

### Implementation Detail (resolvable through further repository analysis)

| Debt | Question |
|------|----------|
| D10 | Evidence hashing determinism |
| D11 | ConstitutionalObservationContext meaning |
| D12 | Divergence detection mechanism |
| D14 | Precondition enforcement details |
| D15 | Officer authority lifecycle |
| D21 | Replay-readiness criteria |
| D23 | Precondition selection rationale |
| D28/D29 | Officer role assignment |
| D31 | Other incomplete rules |
| D32 | Frozen policy inheritance |
| D34 | GEO-3.2+ escalation plan |
| D38 | GovernanceDecisionKernel invocation |

### Organizational / External Process

| Debt | Question |
|------|----------|
| D5 | Coercion relevance (not yet a discovered concern) |
| D16 | Role of Legitimacy in governance decisions |
| D17 | How governance policies integrate |
| D19 | Governance replay integration timeline |
| D35 | What enforces legitimacy = EXPIRED — organizational? |
| D36 | Who invokes arbitration — organizational process? |
| D37 | Is legitimacy enforcement organizational? |

---

## 6. ARB Decision Options

### Option A: Step 2 Complete

**Supporting evidence:**
- 6 of 7 streams executed with documented findings
- 23 hypotheses documented, 38 debt items logged
- 22 of 38 debt items require non-repository evidence — further code analysis would not resolve them
- Implementation patterns across Evidence, Governance, Authority, Audit, Constitutional Rules, Dispute/Challenge, and Arbitration are documented

**Open risks:**
- Stream 3 is authorized but not executed — full stream coverage is incomplete
- Legitimacy consequences (D35/D37) and arbitration invocation (D36) remain unresolved strategic unknowns
- Confidence is MEDIUM for 4 of 7 concepts (Authority, Constitutional Rules, Legitimacy, Arbitration)
- Stream 3 may contain evidence or relationships not visible through completed streams

**Unknowns:**
- Whether Stream 3 would change understanding of governance structure — impact cannot be assessed without execution
- Whether unresolved strategic unknowns are acceptable for next governance phase

---

### Option B: Execute Stream 3

**Supporting evidence:**
- Stream 3 is authorized and would complete full stream coverage
- Would provide evidence for or against H7 (operational coupling) and H9 (security intent)
- Scope is repository-based — does not require non-code evidence
- May reveal artifacts or relationships not visible in completed streams

**Open risks:**
- Stream 3 scope (vote recording/result projection coupling) does not directly address HIGH STRATEGIC debt items (D13, D18, D22, D30, D35, D36, D37)
- Executing Stream 3 would require additional discovery effort
- Impact on governance structure understanding cannot be predicted before execution

**Unknowns:**
- Whether Stream 3 findings would justify the additional discovery effort
- Whether Stream 3 evidence would change any HIGH STRATEGIC understanding
- Whether executing Stream 3 delays decision on non-repository discovery needed for HIGH STRATEGIC items

---

### Option C: Additional Discovery Required

**Supporting evidence:**
- 4 of 7 concepts have MEDIUM confidence — understanding is not fully resolved
- Arbitration invocation (D36) and legitimacy consequences (D35) are unresolved strategic unknowns
- Additional repository analysis might find indirect invocation paths or enforcement mechanisms
- Stream 3 is not executed and could contribute additional evidence

**Open risks:**
- D35 and D36 were the subject of Stream 6B targeted analysis — no operational path or consequence was observed within examined implementation
- 22 of 38 debt items require non-code evidence — repository analysis alone cannot resolve them
- Additional repository analysis of the same areas may produce similar results without resolving unknowns
- Diminishing returns risk for further code investigation

**Unknowns:**
- What specific additional investigation would resolve the identified gaps
- Whether additional resources should be directed at repository investigation or non-repository discovery
- Whether unknowns are resolvable through any available discovery method

---

## 7. Questions Requiring ARB Judgment

### Question 1: Stream 3 Necessity

Is Stream 3 (Voting/Tally Boundary) required before Step 2 can be considered complete, or is the existing evidence from Streams 1, 2, 4, 5, 6A, and 6B sufficient for the purposes of Step 2 discovery?

### Question 2: Repository Discovery Sufficiency

Has repository-based discovery reached a point where the highest-value remaining unknowns (D13, D18, D22, D30, D35, D36, D37) require non-repository evidence (interviews, ADRs, governance documents) rather than further code analysis?

### Question 3: Strategic Unknown Acceptability

Are the unresolved strategic unknowns (legitimacy consequences, arbitration invocation, rule origin, evidence sufficiency criteria) acceptable to carry forward into the next governance phase, or must they be resolved before Step 2 can close?

### Question 4: Non-Repository Discovery

Should non-repository discovery (stakeholder interviews, ADR archaeology, governance document review) begin as a new phase of Round 17, or be deferred to a subsequent discovery round?

### Question 5: Step 2 Closure Conditions

What specific conditions would need to be met for the ARB to consider Step 2 discovery complete? For example:
- All 7 streams executed (including Stream 3)?
- 38 debt items resolved to a defined threshold?
- Specific HIGH STRATEGIC items resolved?
- Evidence confidence thresholds reached?

### Question 6: Election Integrity Guarantees

Are election integrity guarantees (participation integrity, eligibility integrity, result integrity, auditability, verifiability) sufficiently understood for Step 2 closure? Many discovered concepts (Evidence, Trust, Legitimacy, Governance, Audit, Constitutional Rules) appear related to election guarantees, but this relationship has not been explicitly evaluated.

---

**This decision package presents evidence for all three options. The decision belongs to the ARB.**

---

**Round 17 Step 2 ARB Decision Package — READY FOR ARB REVIEW**
