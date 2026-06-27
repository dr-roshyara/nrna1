# Round 36B-01 — Auditability Baseline

**Date:** 2026-06-13

**Phase:** Round 36B — Auditability Research

**Sub-document:** 36B-01 (Domain Baseline — must precede all literature review)

**Authority:** Architecture Review Board

**Governance Foundation:**
- All Rounds 17–36A discovery and design artifacts
- Literature Research Phasing Plan (BINDING)
- Round 36A findings accepted as Research Evidence + Candidate Architectural Impacts (ARB ruling 2026-06-13)

**Purpose:** Catalogue every auditability-related concept, mechanism, invariant, and gap already discovered in Rounds 17–36A. This baseline becomes the evaluation standard against which all 36B literature findings are measured.

**Governing Rule:** Literature review must not begin until this baseline exists. Without a domain baseline, literature drives the architecture rather than strengthening it.

---

## ARB Formal Decision

```
Round 36B-01 — Auditability Baseline

APPROVED WITH OBSERVATIONS

Research Discipline:      EXCELLENT
DDD Discipline:           EXCELLENT
Governance Discipline:    EXCELLENT
Audit Scope Separation:   VERY HIGH

Confidence: HIGH

Corrections applied:

OBS-36B-01-1 — Concern C Added: Evidence Integrity Audit
  A third audit concern distinct from Governance Audit (A) and
  Vote Tally Audit (B). Addresses: "Can we trust the evidence itself?"
  Evidenced by: GovernanceReplayService, ReplaySession (CANDIDATE),
  D7, DivergenceDetected. Semantics governance-debt-dependent.
  Will become critical during 36C (Threat Modeling) and 36D (Trust Distribution).

OBS-36B-01-2 — Audit Context Ownership Table Scoped
  "Receive all domain events" corrected to
  "Receive all currently discovered domain events."
  Future contexts (Verification Representation, Results/Tallying,
  ReplaySession, Round 36 findings) are not yet established as
  Audit observers.

OBS-36B-01-3 — Gap A-3 Elevated to Primary Auditability Risk (Criticality: HIGH)
  Audit completeness invariant is the foundation on which all other
  audit mechanisms depend. No audit claim is possible over an
  incomplete audit record, regardless of the sophistication of
  integrity hashes, replay, snapshots, or statistical audit.

OBS-36B-01-4 — Literature Question 10 Added: Who owns auditability?
  Ownership question directly follows from C-02 (Verification
  Representation Context), CAH-02 (Independent Verification Observer),
  OBS-36A-08-1 (Audit Gravity Well), and OBS-36A-09-1 (Constitutional
  Enforcement scope). Must explicitly guide 36B literature analysis.

Round 36B-01: APPROVED
Round 36B-02 (Auditability Concept Catalog): AUTHORIZED
```

---

## Section 1 — What "Auditability" Means in This Domain (Pre-Literature)

Before consulting any external source, the domain itself must be asked: what auditability properties has discovery already established?

This section records only what discovery evidence supports. No literature. No assumptions.

---

### 1.1 Two Distinct Audit Concerns

Discovery evidence reveals two structurally different audit concerns in NRNA. They are not the same thing and must not be conflated.

**Concern A — Governance Audit**

The ability to reconstruct, verify, and certify the complete sequence of governance decisions that produced an election outcome.

This concern is well-evidenced in the discovered domain. It covers:
- Who made which governance decisions, and when
- Whether trust attestations were valid at the moment votes were cast
- Whether governance configuration was frozen correctly before voting began
- Whether any governance state transitions occurred that would invalidate the election

**Concern B — Vote Tally Audit**

The ability to verify, statistically or deterministically, that recorded votes were correctly tallied into final results.

This concern has a structural blocker. D39 (Results/Tallying decision ownership) is unresolved. No tally audit mechanism can be designed until D39 is resolved, because the audit subject — the tally — has no known owner.

**Concern C — Evidence Integrity Audit**

The ability to establish that the evidence itself has not been altered, omitted, corrupted, or diverged from its original form.

This concern emerges directly from discovered domain evidence:
- GovernanceReplayService — replays stored evidence; implicitly raises the question of whether stored evidence matches the original
- ReplaySession aggregate (CANDIDATE) — purpose is to replay historical evidence and detect divergence
- D7: "Was evidence integrity preserved?" — the unanswered question ReplaySession would address
- DivergenceDetected — a candidate event whose existence implies that divergence is a possible domain fact

Concern C is not governance audit (Concern A). It is not tally audit (Concern B). It asks: can we trust the evidence that Concerns A and B depend on?

This concern will grow in importance during 36C (Threat Modeling) and 36D (Trust Distribution), where adversarial evidence tampering becomes a research subject.

**The gap between them:** Discovery evidence strongly supports Concern A and cannot yet address Concern B. Concern C is partially evidenced but semantics are governance-debt-dependent (D35/D36/D37/ADGR-1). Round 36B literature research will primarily apply to the question: "what does mature audit literature say about the required evidence for all three?" The answer for Concern A can be evaluated against the discovered domain. The answer for Concern B is noted but cannot yet be acted upon. The answer for Concern C can be partially evaluated — the intent is discovered; the mechanism is not yet settled.

---

## Section 2 — Discovered Auditability Mechanisms

### 2.1 ElectionAuditLog

**Source:** Round 24A — Election Integrity Guarantee Map (Rounds 17–23 repository analysis)

**What was discovered:**
- Records all election state changes with old and new values
- Provides a chronological event record per election

**Auditability relevance:**
- Governance Audit Concern A: DIRECTLY SUPPORTS — complete state change record
- Vote Tally Audit Concern B: INDIRECT — records that tally-related state changes occurred; cannot verify tally correctness

**Confidence:** HIGH (that mechanism exists) / MEDIUM (that it is sufficient for formal auditability)

---

### 2.2 SecurityEventRecorder

**Source:** Round 24A

**What was discovered:**
- Logs trust evaluation events
- Records trust decisions associated with voter eligibility at the time of evaluation

**Auditability relevance:**
- Governance Audit Concern A: SUPPORTS — trust decisions are auditable after the fact
- Provides evidence that eligibility was evaluated at a specific time with specific inputs

**Confidence:** HIGH

---

### 2.3 GovernanceDecisionSnapshot with Integrity Hashes

**Source:** Round 24A

**What was discovered:**
- Snapshots governance decisions at decision points
- Integrity hashes protect snapshot immutability

**Auditability relevance:**
- Governance Audit Concern A: DIRECTLY SUPPORTS — integrity-protected governance record
- This is the closest discovered mechanism to a formal audit evidence record

**Confidence:** HIGH (that mechanism exists) / MEDIUM (that integrity hashes are sufficient for the formal auditability claim)

---

### 2.4 GovernanceReplayService

**Source:** Round 24A

**What was discovered:**
- Internal evidence replay capability
- Can replay governance decisions from stored evidence

**Auditability relevance:**
- Governance Audit Concern A: DIRECTLY SUPPORTS — provides a mechanism for after-the-fact evidence reconstruction
- Internal-only: no external party can invoke replay
- Whether replay output constitutes certified auditability is UNDEFINED (semantics governance-debt-dependent)

**Confidence:** HIGH (that mechanism exists) / LOW (that it constitutes formal auditability without D35/D36/D37/ADGR-1 resolution)

---

### 2.5 Audit Context — Fire-and-Forget Observer

**Source:** Round 34C — Aggregate Interaction Analysis (APPROVED)

**What was discovered:**
- The Audit context receives all domain events via fire-and-forget event observation
- Vote → Audit: APPROVED interaction (fire-and-forget)
- Verification → Audit: APPROVED interaction (fire-and-forget)
- GovernanceState → Audit: APPROVED interaction (fire-and-forget)
- Audit does NOT influence domain decisions
- Audit is passive: it records facts; it does not evaluate, certify, or publish

**Auditability relevance:**
- Governance Audit Concern A: SUPPORTS — all domain events are observed and recorded
- Vote Tally Audit Concern B: DOES NOT SUPPORT — recording that an event occurred ≠ certifying correctness of the tally

**Constitutional characterization (binding, Round 34C):**
Audit is a passive, fire-and-forget observer. This characterization was approved in Round 34C and is binding. Any extension of Audit to own certification, publication, or external verification is an identity change, not a scope expansion. See OBS-36A-08-1 in Section 10.

**Confidence:** HIGH

---

### 2.6 ReplaySession Aggregate (CANDIDATE)

**Source:** Round 34A — Domain Event Discovery; Round 33 — Aggregate Boundary Design

**What was discovered:**
- ReplaySession is a CANDIDATE aggregate (not yet approved)
- Purpose: replay historical evidence and detect divergence
- DivergenceDetected is a candidate event
- D7: "Was evidence integrity preserved?" — this is the unresolved question ReplaySession would answer

**Auditability relevance:**
- Governance Audit Concern A: IF APPROVED — would provide formal evidence integrity verification
- Would distinguish between "evidence was observed" (Audit context) and "evidence integrity is certified" (ReplaySession)
- Blocked by D35, D36, D37, ADGR-1 — semantics cannot be finalized until these debts resolve

**Confidence:** MEDIUM (that the concept is discovered) / LOW (that its semantics are settled — governance-debt-dependent)

**Note:** ReplaySession is placed here because it represents real discovered evidence of the domain's intent, not a gap. The incompleteness belongs to Section 7.

---

### 2.7 Governance Configuration Freeze (36A-DI-05 — CONFIRMED)

**Source:** Round 36A-05 Scantegrity/Prêt à Voter, Round 36A-08 Ownership Candidate Matrix

**What was discovered:**
- 36A-DI-05: Governance configuration must be immutable before vote collection or outcome verification begins
- CONFIRMED finding: five-source, two-family evidence
- GovernanceState is PRESUMPTIVE OWNER of Configuration Freeze (OBS-36A-08-2)

**Auditability relevance:**
- Governance Audit Concern A: DIRECTLY SUPPORTS — configuration freeze creates a stable, verifiable baseline
- Without a frozen configuration, audit claims about "what rules governed this election" are ungrounded
- This is a prerequisite for auditability, not auditability itself

**Confidence:** HIGH (finding confirmed in 36A)

---

### 2.8 Vote Anonymity Constraint (VO-1)

**Source:** Round 33 — Aggregate Boundary Design

**What was discovered:**
- VO-1: "The vote-voter link must not exist"
- VoteRecorded contains no voter identity
- Audit events derived from VoteRecorded also contain no voter identity

**Auditability relevance:**
- Governance Audit Concern A: CONSTRAINS — audit records cannot reveal voter identity even in post-hoc analysis
- Vote Tally Audit Concern B: CONSTRAINS — statistical audit methods must work without voter-vote linkage
- This constraint applies to all audit mechanisms, not just to Vote

**Confidence:** HIGH

---

## Section 3 — Audit Context Ownership

**Source:** Round 34C — Aggregate Interaction Analysis (APPROVED)

The Audit context role as discovered:

| Responsibility | Status |
|----------------|--------|
| Receive all currently discovered domain events (fire-and-forget) | APPROVED |
| Record domain facts with timestamp and attribution | APPROVED |
| Influence domain decisions | NOT PERMITTED |
| Certify election outcome correctness | NOT DISCOVERED |
| Publish evidence to external parties | NOT DISCOVERED |
| Own auditability invariants | NOT YET ESTABLISHED |

**Structural observation:** The Audit context, as discovered, records facts. Whether it owns the invariant "the recorded facts are auditable" is not yet established. That distinction — between recording facts and owning auditability as a guarantee — is one of Round 36B's research questions.

---

## Section 4 — Existing Audit Invariants

No formal audit invariants have been approved through the governance program for the Audit context.

**What exists:**
- VO-1 (Vote): constrains audit record content — no voter linkage
- TA-3 (Trust Attestation): append-only — audit trail integrity for identity records
- GovernanceDecisionSnapshot integrity hashes: protect governance record immutability

**What does not yet exist:**
- A formal invariant stating "the audit record is complete"
- A formal invariant stating "the audit record is tamper-evident"
- A formal invariant stating "the audit record is sufficient to verify the election outcome"

**Assessment:** Audit record creation is discovered and supported. Audit record completeness, integrity, and sufficiency as invariants have not been approved. Round 36B literature may generate evidence that these invariants are necessary.

---

## Section 5 — Auditability Properties: Discovery Status

| Property | Status | Evidence | Notes |
|----------|--------|---------|-------|
| Governance audit trail | SUPPORTED | ElectionAuditLog, SecurityEventRecorder, GovernanceDecisionSnapshot (Round 24A) | Concrete mechanisms exist; formal sufficiency not yet claimed |
| Evidence replay capability | CANDIDATE SUPPORT | GovernanceReplayService (Round 24A), ReplaySession aggregate (Round 34A) | Internal-only; semantics governance-debt-dependent |
| Governance configuration audit | SUPPORTED | 36A-DI-05 CONFIRMED; GovernanceDecisionSnapshot (Round 24A) | Configuration freeze creates auditable baseline |
| Trust attestation audit | SUPPORTED | TA-3 append-only; SecurityEventRecorder (Round 24A) | Append-only trail for identity decisions |
| Vote recording completeness | NOT ESTABLISHED | VoteRecorded event observed by Audit (Round 34C) | Recording observed ≠ completeness invariant owned |
| Vote tally audit | NOT DESIGNED | D39 unresolved | No tally owner → no tally audit possible |
| Statistical audit (RLA-class) | NOT DESIGNED | No discovered mechanism | Primary subject of 36B literature |
| External auditability | NOT DESIGNED | GovernanceReplayService is internal-only (Round 24A) | No external party can access audit evidence |
| Tamper-evident audit record | PARTIAL | Integrity hashes on GovernanceDecisionSnapshot (Round 24A) | Partial coverage; no domain-wide tamper-evidence invariant |

---

## Section 6 — Auditability Gaps

### Gap A-1 — Vote Tally Audit (Structural)

**What is missing:** No mechanism exists to verify that recorded votes were correctly tallied into final results.

**Why it is structural:** D39 (Results/Tallying decision ownership) is unresolved. The audit subject — the tally — has no modeled owner. Any tally audit mechanism requires D39 resolution first.

**Impact:** Round 36B literature findings about tally auditability (RLA-class) can be recorded as research evidence but cannot be designed into the domain until D39 resolves.

---

### Gap A-2 — External Auditability

**What is missing:** No external party can access NRNA audit evidence. All audit infrastructure is internal-only (GovernanceReplayService).

**Impact:** Formal auditability claims — including those from RLA literature — generally require that an independent party can verify evidence. The current model has no such access point. Round 36B should assess whether external access is required and what form it would take.

---

### Gap A-3 — Audit Completeness Invariant ⚠ PRIMARY AUDITABILITY RISK — Criticality: HIGH

**What is missing:** No invariant establishes that the Audit record is complete. The Audit context receives fire-and-forget events — there is no mechanism to detect a missing event or certify that all expected events were received.

**Why this is the primary auditability risk:** Every other auditability mechanism — integrity hashes, evidence replay, RLA-class statistical audit, GovernanceDecisionSnapshot — depends on the completeness of the underlying record. An incomplete audit record invalidates all downstream audit claims regardless of how rigorous those mechanisms are. Incomplete evidence cannot be made auditable by any technique.

```text
Incomplete audit record
        ↓
No audit claim possible

Even:
    Integrity hashes
    Replay
    Snapshots
    Statistical audit (RLA)

    ...cannot compensate for missing evidence.
```

**Impact:** This gap must be addressed before any formal auditability guarantee can be made. Round 36B literature must be asked: how do mature audit systems establish audit record completeness? Is completeness a property of the recording mechanism, the audit protocol, or both?

---

### Gap A-4 — Tamper-Evidence at Audit Layer

**What is missing:** Integrity hashes exist on GovernanceDecisionSnapshot, but not as a domain-wide audit record protection invariant. A compromised audit record without tamper detection would not support verifiable auditability.

**Impact:** Literature-based auditability mechanisms typically require tamper-evident audit records. Round 36B should assess whether this gap is required to be closed.

---

### Gap A-5 — ReplaySession Semantics (Governance-Debt-Dependent)

**What is missing:** ReplaySession aggregate semantics (certification, who may invoke, what DivergenceDetected means) are blocked by D35/D36/D37/ADGR-1. Until these resolve, evidence integrity verification — as distinct from evidence recording — cannot be formally designed.

---

## Section 7 — What Is Blocked by D39

**D39:** Results/Tallying decision ownership is unresolved.

**D39 does not block only C-01.** D39 blocks all auditability research that crosses the record→tally boundary. This is a structural constraint on 36B's research scope, not an exception for one conflict.

Specifically, D39 blocks:

| Item | Why Blocked |
|------|-------------|
| C-01 (Tallied-as-Recorded) | Tally owner unknown → cannot model tally audit |
| Gap A-1 (Vote Tally Audit) | Tally audit subject has no modeled owner |
| RLA literature application | Statistical audit targets the tally; tally has no owner |
| Audit→Results interaction | Cannot model audit access to a context that does not yet exist |
| Any "end-to-end verifiability" claim | Complete chain requires record→tally boundary to be resolved |

**What D39 does NOT block:**
- Governance audit mechanisms (Concern A) — these do not cross the record→tally boundary
- Configuration freeze audit (36A-DI-05) — applies before vote collection begins
- Trust attestation audit — independent of tally
- RLA literature review — literature can be read and understood; only application is blocked

**Round 36B research posture for D39-blocked items:** Document literature findings. Record which findings require D39 resolution. Do not design mechanisms for D39-blocked items. Carry as deferred evidence into Round 37 (ADR Authoring).

---

## Section 8 — What Is Blocked by D43

**D43 / AUTHORITY-GAP-1:** Enrollment Authority ownership is unresolved.

**Audit relevance:** Any audit claim that depends on verifying "an eligible voter cast this ballot" touches the enrollment gap. If enrollment authority is unowned, the eligibility precondition for audit is ungrounded.

Specifically, D43 constrains:

| Item | Why Constrained |
|------|----------------|
| Voter eligibility audit trail | Eligibility evaluation sources are partially unknown |
| "Eligible voters who voted" count | Requires enrollment ownership to establish the population |
| Any RLA calculation involving eligible voter pool | Statistical audit requires a known eligible voter population |

**What D43 does NOT block:**
- Governance audit mechanisms that do not involve voter eligibility
- Configuration freeze audit
- GovernanceDecisionSnapshot audit
- Trust attestation audit (TA-3)

**Round 36B research posture for D43-blocked items:** Same as D39 — document findings, flag D43 dependency, do not design, carry to Round 37.

---

## Section 9 — Literature Questions Generated by This Baseline

The following questions are generated from domain evidence and will guide 36B literature review:

1. What does Risk Limiting Audit (RLA) literature require as a minimum evidence set? Does NRNA's discovered evidence (ElectionAuditLog, GovernanceDecisionSnapshot) satisfy that minimum for Governance Audit (Concern A)?

2. What is the audit record model in RLA literature? Is it event-based, snapshot-based, or document-based? How does it compare to the discovered Audit context (fire-and-forget observer)?

3. Does mature audit literature require completeness invariants on the audit record? If yes, how are they enforced? Does the discovered fire-and-forget model create a structural incompatibility?

4. Does statistical audit (RLA) require external accessibility of audit evidence? If yes, what form? Is internal-only audit evidence (GovernanceReplayService) structurally insufficient?

5. How do mature systems distinguish governance audit (who made which decision) from vote tally audit (were votes counted correctly)? Are they the same infrastructure or separate?

6. What trust assumptions do RLA-based systems make about the audit record? Do those assumptions conflict with NRNA's discovered constitutional governance model?

7. Is tamper-evidence at the audit layer a fundamental RLA requirement, or an implementation choice? Does the partial coverage in NRNA (integrity hashes on GovernanceDecisionSnapshot only) satisfy the requirement?

8. How does VO-1 (no voter linkage) interact with statistical audit requirements? Can RLA-class auditability be achieved without voter-vote linkage? Do any systems demonstrate this?

9. What is the relationship between audit evidence and the Verification Representation Context (C-02 resolution)? Do they publish from the same evidence source or different sources?

10. Who owns auditability? How do mature election systems allocate auditability ownership? Is it owned by a single context (an audit authority), distributed across multiple contexts, or held by an external actor? How does this relate to the discovered NRNA Audit context (passive observer), the Verification Representation Context (C-02, Constitutional Enforcement scope), the ReplaySession CANDIDATE, and CAH-02 (Independent Verification Observer)? This question directly follows from OBS-36A-08-1 (Audit Gravity Well), OBS-36A-09-1 (Constitutional Enforcement Context scope), and CAH-02's unresolved ownership status.

---

## Section 10 — Research Guardrails

### Guardrail R-B1 — Audit Gravity Well (OBS-36A-08-1, binding)

**Source:** Round 36A-08 Ownership Candidate Matrix (ARB-approved)

The Audit context is a passive, fire-and-forget observer (Round 34C, binding characterization). Extending Audit to own publication, certification, or external verification is an identity change, not a scope expansion.

**Application to 36B:** If literature shows that RLA-class auditability requires capabilities beyond passive observation, that finding must be presented as: "our current Audit context is insufficient; here is the evidence; here is the gap; here is what needs to change." It must not silently expand the Audit context's responsibilities.

**Governance path:** Evidence of required change → carry to Round 36E Architecture Impact Assessment → ADR review in Round 37.

---

### Guardrail R-B2 — Evidence Type ≠ Evidence Quality (OBS-36A-06-2, carry)

**Source:** Round 36A-06 Risk Limiting Audits Literature Review

Cryptographic proof (ElectionGuard-class) and statistical audit (RLA-class) are different mechanisms, not a quality hierarchy. Neither is superior to the other — they address different questions.

**Application to 36B:** Do not assess RLA-class auditability as "less rigorous" than cryptographic verification. Assess it as a different mechanism type. Both may be required in a complete trustworthiness model.

---

### Guardrail R-B3 — D39 Structural Blocker

All 36B findings that cross the record→tally boundary are deferred. Do not design tally audit mechanisms. Record literature findings. Flag D39 dependency. Do not promote to architectural impact until D39 resolves.

---

### Guardrail R-B4 — Architecture Evaluation Filter (Round 36, binding)

For every 36B literature finding ask: "Does this strengthen the discovered model, or does it attempt to replace the discovered model?" Only findings that strengthen may advance. Findings that would require wholesale replacement of the discovered Audit context must be presented through ADR review with full evidence.

---

### Guardrail R-B5 — Candidate D43 Visibility

Flag enrollment authority dependency in every finding that involves the eligible voter population. Do not resolve — flag and carry.

---

## Section 11 — Pre-Literature Summary

**Concrete discovered mechanisms for auditability:**
- `ElectionAuditLog` — state change records per election
- `SecurityEventRecorder` — trust evaluation event log
- `GovernanceDecisionSnapshot` with integrity hashes — immutable governance record
- `GovernanceReplayService` — internal evidence replay capability
- Audit context fire-and-forget observation (all domain events — Round 34C)
- 36A-DI-05 configuration freeze: auditable governance baseline before voting

**What remains undiscovered or undecided:**
- Whether discovered mechanisms satisfy formal RLA-class auditability requirements
- Audit completeness invariant — not yet established
- Tamper-evidence at the audit layer — partial only
- External accessibility of audit evidence — not designed
- Tally audit — structurally blocked by D39
- ReplaySession semantics — governance-debt-dependent

**The auditability position in one sentence:**
Governance audit (Concern A) has a strong foundation of discovered mechanisms but no formal invariant establishing completeness or sufficiency; vote tally audit (Concern B) has no discovered foundation and is structurally blocked by D39.

---

## Section 12 — Round 36 Governance Posture (Carry from 36A-01)

**Source:** ARB clarification 2026-06-10 — binding for all Round 36 work.

Round 36 is an **evaluation** of the current architecture, not a defence of it.

**Acceptable Round 36 outcomes:**
- **Outcome A:** Current model sufficient — minor enhancements required
- **Outcome B:** Additional capabilities required — new aggregates, new contexts, or new responsibilities
- **Outcome C:** Major architectural changes required — current model insufficient for auditability guarantees

All three outcomes are acceptable. The architecture is not sacred. The evidence is.

**The governance rule:**
Do not silently import patterns. Do not reject patterns automatically. Show the literature evidence. Show the discovered-domain evidence. Show the gap. Explain why the change is required. Submit architectural changes through ADR review.

**The constraint that does not change:**
Changes must be submitted with evidence. "RLA literature does it this way" is not sufficient. "Our current model cannot provide property X, which is required by constitutional requirement Y, and RLA provides a proven pattern for Z" is the required form.

---

## ARB Review

```
Round 36B-01 — APPROVED
Round 36B-02 — AUTHORIZED
```
