# Round 36B-02 — Auditability Concept Catalog

**Date:** 2026-06-13

**Phase:** Round 36B — Auditability Research

**Sub-document:** 36B-02 (Concept Catalog — precedes all literature review)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36B-01 Auditability Baseline (APPROVED — prerequisite)
- Round 36A findings accepted as Research Evidence + Candidate Architectural Impacts (ARB ruling 2026-06-13)
- ARB Governance Posture: Round 36 evaluates; does not defend

**Purpose:** Extract auditability concepts from domain evidence alone. Separate auditability vocabulary from adjacent concepts (verification, certification, assurance). Produce the concept→evidence→gap table that all 36B literature findings will map into.

---

## Governing Rule — Concept Catalog Is Not Architecture

```
A concept catalog is not an architecture catalog.

Purpose of 36B-02:

    Establish vocabulary.

Not:

    Propose solutions.

Examples of what does NOT belong in 36B-02:

    "NRNA shall implement RLAs"         ← architecture decision
    "ReplaySession should own audit"    ← ownership proposal
    "Audit context must be extended"    ← design directive

Examples of what DOES belong in 36B-02:

    "Risk Limiting Audit — a literature concept, not yet
     discovered in the domain model"     ← vocabulary entry

    "Certification — distinct from Audit —
     not yet in discovered model"         ← concept separation

    "Audit Completeness — Primary Auditability Risk,
     no invariant established"            ← gap entry
```

---

## ARB Formal Decision

```
Round 36B-02 — Auditability Concept Catalog

APPROVED WITH OBSERVATIONS

Concept Quality:         EXCELLENT
Research Discipline:     EXCELLENT
Governance Discipline:   VERY HIGH
DDD Alignment:           VERY HIGH

Confidence: HIGH

Corrections applied:

OBS-36B-02-1 — Certification Definition Strengthened
  Changed from: "Is somebody authorized to declare it valid?"
  Changed to:   "Has an authorized party attested that defined
                 evidence satisfies defined criteria?"
  Certification requires Evidence + Criteria + Authority together.
  Partial absence of any element means certification has not occurred.

OBS-36B-02-2 — Audit Completeness (Gap A-3) Strengthened
  Completeness is not merely missing-event detection.
  Two distinct failure modes now documented:
    (a) Event produced, never received — possibly detectable
    (b) Event never produced — not detectable from audit record alone
  The fire-and-forget model cannot distinguish between them.
  Literature question added: how do mature systems establish
  completeness when the audit record depends on the audited system
  emitting events?

OBS-36B-02-3 — RH-B1 Made Ownership-Neutral
  Hypothesis restated: Concern A and Concern C are distinct concerns.
  Whether they require distinct ownership remains a literature question.
  "Distinct concern ≠ distinct owner" — three ownership arrangements
  remain possible; literature review will determine which applies.

OBS-36B-02-4 — Audit Evidence Row Added
  Concept: Audit Evidence — PARTIAL
  What artifacts constitute audit evidence in NRNA?
  VoteRecorded, GovernanceDecisionSnapshot, receipt hash, audit log
  entries are candidates — but not all domain artifacts are evidence.
  Literature will repeatedly ask this question; domain answer needed first.

Round 36B-02: APPROVED
Round 36B-03 (Auditability Literature Family Evaluation): AUTHORIZED
```

---

## Section 1 — Vocabulary Separation (Pre-Literature)

The most important function of this catalog is to prevent four adjacent terms from being used interchangeably. Round 36A demonstrated that "verifiability" collapses into multiple distinct properties when examined precisely. Round 36B faces the same risk with "audit."

The following definitions are derived from domain evidence and general epistemic distinctions. They do NOT import literature definitions.

### The Four Terms

**Verification**
> Asks: Is this claim true?

Domain evidence: verifyByReceipt, verifyByCode, verifyChecksum, proveParticipation (Round 24A, Round 36A). Verification produces a yes/no answer about a specific claim.

---

**Audit**
> Asks: Can the evidence be inspected?

Domain evidence: ElectionAuditLog, SecurityEventRecorder, GovernanceDecisionSnapshot, Audit context fire-and-forget observation (Round 24A, Round 34C). Audit makes evidence accessible for inspection. It does not evaluate claims; it preserves evidence for future evaluation.

---

**Certification**
> Asks: Has an authorized party attested that defined evidence satisfies defined criteria?

The important distinction from Audit: Certification requires three things together — evidence, criteria against which the evidence is evaluated, and an authority empowered to make the attestation. The absence of any one element means certification has not occurred.

Domain evidence: No certification mechanism discovered. GovernanceDecisionSnapshot integrity hashes are a partial precondition for certification (they protect the record), but no criteria have been defined and no authority has been discovered that declares "this election's evidence satisfies our audit standard." This is a gap.

---

**Assurance**
> Asks: How much confidence exists?

Domain evidence: Not a discovered concept. Assurance is a level-of-confidence claim — e.g., "this audit provides 95% confidence the election was correctly counted." No assurance model or mechanism exists in the discovered domain. Statistical audit (RLA) is the literature concept that addresses this. Currently not designed.

---

### Why This Separation Matters

Papers in 36B literature will use these four terms interchangeably. The mapping framework in Section 6 requires every literature finding to specify which of the four it is claiming — not which general term it uses.

Without this separation, "auditability" will silently absorb verification responsibilities (already assigned to the Verification Representation Context, C-02) and certification responsibilities (unowned, to be resolved), creating an Audit Gravity Well (OBS-36A-08-1).

---

## Section 2 — Concept-Evidence-Gap Table (Domain-Derived)

This table is the primary input for all Round 36B literature reviews. Every literature finding must be placed against a row in this table. A finding that does not map to any row is a candidate for a new row — but requires ARB authorization to add.

| Concept | Discovered? | Evidence Strength | Primary Gap | Audit Concern |
|---------|-------------|------------------|-------------|---------------|
| Audit Trail | YES | HIGH — ElectionAuditLog, SecurityEventRecorder (Round 24A) | Completeness invariant not established — see Gap A-3 | A — Governance |
| Evidence Replay | YES | MEDIUM — GovernanceReplayService (Round 24A), ReplaySession CANDIDATE (Round 34A) | Replay semantics governance-debt-dependent; who may invoke and what it certifies undefined | C — Evidence Integrity |
| Evidence Integrity | PARTIAL | MEDIUM — DivergenceDetected CANDIDATE, D7 question, ReplaySession intent | Active certification that evidence has not diverged is not yet designed | C — Evidence Integrity |
| Audit Completeness | NO | NONE | PRIMARY AUDITABILITY RISK — no invariant that audit record is complete; completeness means proving nothing relevant is missing, not merely detecting that an event was lost after it was produced | A — Governance |
| Tamper Evidence | PARTIAL | LOW-MEDIUM — GovernanceDecisionSnapshot integrity hashes (Round 24A) | Partial coverage: hashes protect governance snapshots only; no domain-wide tamper-evidence invariant | A — Governance / C — Evidence Integrity |
| External Auditability | NO | NONE | Gap A-2: GovernanceReplayService is internal-only; no external party can access audit evidence | A — Governance |
| Certification | NO | NONE | No authority discovered that can declare an election's evidence certified; prerequisite for formal auditability claim | Cross-concern |
| Independent Auditor | CANDIDATE | LOW — CAH-02 (Independent Verification Observer) assessed in 36A-08; ownership unresolved | Whether this is an external actor or an NRNA-owned context is unresolved | C — Evidence Integrity / A — Governance |
| Statistical Audit | NO | NONE | D39 blocks — no tally owner; no counting algorithm; no population of eligible voters settled (D43) | B — Tally (D39-blocked) |
| Risk Limiting Audit | NO | NONE | Literature concept; requires tally access (D39), eligible voter population (D43), and external auditor access (Gap A-2) — three structural blockers | B — Tally (D39-blocked) |
| Configuration Freeze Audit Baseline | YES | HIGH — 36A-DI-05 CONFIRMED; GovernanceState presumptive owner (OBS-36A-08-2) | Freeze mechanism exists; formal audit of "configuration was frozen correctly" is not yet modeled as an auditability invariant | A — Governance |
| Trust Attestation Audit | YES | HIGH — TA-3 append-only; SecurityEventRecorder (Round 24A, Round 33) | Whether append-only is sufficient for formal audit claim is not established | A — Governance |
| Audit Observation (Event) | YES | HIGH — Vote→Audit, Verification→Audit, GovernanceState→Audit (Round 34C APPROVED) | Scoped to currently discovered contexts only; future contexts not yet established as Audit observers | A — Governance |
| Vote Recording Audit | PARTIAL | MEDIUM — VoteRecorded observed by Audit context; VO-1 constrains record content | Audit receives VoteRecorded (anonymous); whether this constitutes a sufficient audit record for the vote is unestablished | A — Governance |
| Audit Evidence | PARTIAL | MEDIUM — VoteRecorded, GovernanceDecisionSnapshot, receipt hash, ElectionAuditLog entries (Round 24A, Round 34A) | What artifacts constitute audit evidence in NRNA? Not all domain artifacts are evidence. Literature will repeatedly ask this question — it needs a domain answer first | Cross-concern |
| Auditability Ownership | NOT ESTABLISHED | NONE | Who owns auditability as a formal responsibility? Audit context? ReplaySession? Verification Representation Context? Results/Tallying? Multiple? | Cross-concern — Literature Question 10 |

---

## Section 3 — Primary Research Hypothesis

Before literature review begins, the baseline generates one primary research hypothesis that is likely to drive the most significant architectural decisions.

### Hypothesis RH-B1: Governance Audit and Evidence Integrity Audit Are Distinct Concerns

**Statement:** Concern A (Governance Audit — making evidence accessible) and Concern C (Evidence Integrity Audit — establishing that evidence has not been altered) address different questions and are served by different discovered mechanisms. Whether they require different architectural ownership remains a literature question — not a conclusion.

**Evidence supporting the hypothesis (concern distinction):**
- Audit context is fire-and-forget, passive (Round 34C — binding) — addresses Concern A
- GovernanceReplayService and ReplaySession are separate mechanisms designed for replay/divergence detection — not extensions of the Audit context — addresses Concern C intent
- Concern C introduces a new question ("has the evidence diverged?") that is distinct from Concern A ("can the evidence be inspected?")

**What remains open (ownership):**
- "Different concerns" does not imply "different owners." Literature may show:
  - Same owner, different capabilities (one context handles both A and C)
  - Different owners, different capabilities (two contexts)
  - Same context, different policies
- ReplaySession is a CANDIDATE — not yet an approved aggregate; the architectural separation may not survive ARB review
- A single Audit context with richer invariants could in principle address both concerns if a completeness invariant and certification mechanism were established

**What literature review must determine:**
- Do mature audit systems separate evidence collection from evidence integrity certification?
- If separate, does separation imply separate ownership or separate capabilities within one context?
- Does RLA literature assume audit record integrity as a precondition, or address it as part of the protocol?

**Why this matters for 36E:** If the concern distinction is confirmed by literature, "auditability" requires at minimum two architectural responsibilities. Whether those responsibilities require different owners is the question that Round 36E Architecture Impact Assessment must answer across all four 36A/B/C/D streams.

---

## Section 4 — Concept Definitions (Domain-Derived, Pre-Literature)

These definitions are extracted from domain evidence. They do NOT import literature definitions. Literature may later provide richer or different definitions — those will be evaluated against these domain-derived baselines.

### 4.1 Audit Trail (DISCOVERED)

**Domain definition:** A chronological record of domain events, governance decisions, and state changes, captured with timestamp and attribution. Produced by ElectionAuditLog and SecurityEventRecorder.

**Scope in discovered model:** Internal record. Not accessible to external parties. Created by Audit context observing fire-and-forget events from Vote, Verification, and GovernanceState (Round 34C).

**Open question:** Does the current audit trail satisfy the completeness property required for a formal audit claim? Gap A-3 (PRIMARY AUDITABILITY RISK) indicates no invariant establishes completeness.

---

### 4.2 Evidence Replay (DISCOVERED)

**Domain definition:** The ability to replay recorded evidence to reconstruct past states or detect divergence between current state and the historical record.

**Scope in discovered model:** GovernanceReplayService (internal-only, Round 24A); ReplaySession aggregate CANDIDATE (blocked by D35/D36/D37/ADGR-1). Internal-only. No external invocation path discovered.

**Open question:** Who may invoke replay? What does a replay result certify? What constitutes "divergence" in formal terms? These are all governance-debt-dependent.

---

### 4.3 Evidence Integrity (PARTIAL — Concern C)

**Domain definition:** The property that evidence has not been altered, omitted, or corrupted from its original form. Distinct from evidence accessibility (Concern A) and evidence correctness (Concern B).

**Scope in discovered model:** Intent is discovered (DivergenceDetected CANDIDATE, D7 question, ReplaySession purpose). Active certification that integrity holds is not yet designed.

**Open question:** Can the discovered fire-and-forget audit model detect a missing event? If an event was never received by the Audit context, the audit record is silently incomplete — not flagged as divergent.

---

### 4.4 Configuration Freeze Audit Baseline (DISCOVERED)

**Domain definition:** The governance configuration that governed the election is immutable from the point of freeze through the end of result verification. This creates a stable, auditable baseline: "here are the rules that applied."

**Scope in discovered model:** 36A-DI-05 CONFIRMED; GovernanceState presumptive owner (OBS-36A-08-2, Round 36A-08). Freeze mechanism discovered; formal audit of "configuration was frozen correctly" not yet modeled.

**Open question:** What constitutes the auditable record of configuration freeze? A GovernanceDecisionSnapshot at freeze time? A GovernanceTransitionCompleted event? Neither is currently designated as the freeze audit record.

---

### 4.5 Trust Attestation Audit (DISCOVERED)

**Domain definition:** An append-only record of all trust evaluation decisions associated with voter eligibility. Enables after-the-fact verification that trust attestation was valid at the time votes were cast.

**Scope in discovered model:** TA-3 (append-only), SecurityEventRecorder (Round 24A, Round 33).

**Open question:** Is append-only sufficient for a formal audit claim about trust attestation, or does it require tamper-evidence (integrity hashes) on the trust record itself?

---

## Section 5 — What Is Explicitly NOT in the Discovered Model

Concepts for which no domain evidence exists. These are pure gaps — literature may later indicate which gaps require resolution.

### 5.1 Audit Completeness

No invariant discovered that establishes the audit record is complete. The Audit context receives fire-and-forget events. There is no missing-event detection mechanism. An event that never arrives produces no signal. This is Gap A-3 — PRIMARY AUDITABILITY RISK.

**Completeness is not merely missing-event detection.** Completeness is the ability to establish that the audit record is a faithful representation of all relevant domain facts. There are two distinct failure modes:

- **(a) Event produced, never received:** An event was emitted but the Audit context did not receive it. May be detectable by a gap-detection protocol if sequence numbers or expected-event registries exist.
- **(b) Event never produced:** A domain fact occurred but no event was emitted. Not detectable from the audit record alone — requires an independent record of what events should have been produced.

The current fire-and-forget model cannot distinguish between these two failure modes. Round 36B literature must be asked: how do mature audit systems establish completeness when the audit record depends on event emission by the audited system itself?

**Why this matters:** Every formal audit claim — whether Concern A, B, or C — depends on the completeness of the underlying evidence. Incomplete evidence cannot be certified, replayed, or statistically audited into a valid claim.

---

### 5.2 External Auditability

No external access mechanism for audit evidence. GovernanceReplayService is internal-only. No public record. No external observer or auditor access path. This is Gap A-2.

**Why this matters:** Formal audit literature (including RLA) typically assumes an independent external auditor with access to audit evidence. Without this, NRNA can audit internally but cannot support independent third-party verification of election integrity.

---

### 5.3 Certification

No discovered authority that declares "this election's evidence is certified as complete and correct." GovernanceDecisionSnapshot integrity hashes protect the record but do not constitute a certification act by an authorised party.

**Why this matters:** Certification is distinct from audit. Audit makes evidence available. Certification is an authoritative declaration by a named party that the evidence meets a defined standard. Neither the declaring party nor the standard exists in the current model.

---

### 5.4 Statistical Audit (RLA-class)

No mechanism for statistically sampling recorded ballots to verify tally accuracy. Three structural blockers: D39 (no tally owner), D43 (eligible voter population ungrounded), Gap A-2 (no external access).

**Why this matters:** Risk Limiting Audits are the primary literature subject of Round 36B. Their application to NRNA is almost entirely deferred — but the literature findings can be recorded and carried to Round 37.

---

### 5.5 Assurance Model

No discovered concept of "how much confidence does this audit provide?" No assurance level assigned to any audit mechanism. No risk threshold defined.

**Why this matters:** Statistical audit literature (RLA) is specifically about assurance levels — "how confident are we that the tally is correct?" Without an assurance model, statistical audit findings cannot be assessed against NRNA requirements.

---

## Section 6 — Auditability Concern Boundary Analysis

This analysis maps what each discovered context contributes to audit concerns. It does NOT propose ownership — it records what is currently known from discovery evidence.

| Audit Concern | Context / Mechanism | Role (Discovered) | Gap |
|--------------|--------------------|--------------------|-----|
| A — Governance | Audit context | Receives all currently discovered domain events (fire-and-forget) | Completeness invariant absent; external access absent |
| A — Governance | ElectionAuditLog | Records election state changes | Internal-only; completeness not established |
| A — Governance | GovernanceDecisionSnapshot | Integrity-protected governance record | Partial coverage; not domain-wide |
| A — Governance | SecurityEventRecorder | Trust evaluation event log | Internal-only |
| A — Governance | TA-3 (append-only) | Trust attestation trail | Not externally accessible |
| B — Tally | Results/Tallying | D39 unresolved — no owner | Cannot be modeled until D39 resolves |
| C — Evidence Integrity | GovernanceReplayService | Internal evidence replay | No certification of result; internal-only |
| C — Evidence Integrity | ReplaySession (CANDIDATE) | Evidence divergence detection | Blocked by D35/D36/D37/ADGR-1 |
| Cross-concern | Audit context | Passive observer — not certifier | OBS-36A-08-1: extending to certification is an identity change |
| Cross-concern | CAH-02 (Independent Verification Observer) | CANDIDATE — ownership unresolved | May be external actor or NRNA context — 36B should assess |

**Structural observation:** There is no single discovered mechanism that addresses all three audit concerns. Concern A has a foundation. Concern C has intent but no settled mechanism. Concern B has a structural blocker. Auditability ownership — who holds the formal responsibility to assert "this election is auditable" — is not yet established in the discovered model. This is the primary open question for Round 36B (Literature Question 10).

---

## Section 7 — Literature Mapping Framework

All Round 36B literature findings must be expressed in this form before they can affect the domain model:

```
Literature Finding:
    [Paper/System] states that [auditability property]
    requires [mechanism].

Domain Evidence:
    The current model [supports / does not support / partially
    supports] this mechanism via [discovered evidence].

Audit Concern:
    Concern A (Governance) / Concern B (Tally) / Concern C
    (Evidence Integrity) / Cross-concern.

Gap (if any):
    [What is missing from the current model.]

Vocabulary Check:
    Is the paper using "audit" to mean: evidence collection (Audit),
    claim verification (Verification), authorized declaration
    (Certification), or confidence level (Assurance)? Specify.

Candidate Enhancement:
    [What architectural change would close the gap, if any.]

VO-1 Compatibility:
    [Whether the proposed mechanism is compatible with VO-1.]

D39 / D43 Dependency:
    [Whether the finding is blocked by D39 or D43. If blocked,
    record as deferred evidence — do not design.]
```

No literature finding advances without this mapping. "This paper uses RLA" is not a finding. "This paper uses RLA; RLA requires a tally access mechanism we do not have (D39-blocked); RLA also requires an eligible voter population count (D43 dependency); RLA findings are recorded as deferred evidence for Round 37" is a finding.

---

## Section 8 — Open Governance Items Visible During Concept Extraction

| Item | Impact on Auditability |
|------|----------------------|
| D39 (Results/Tallying ownership) | Blocks all Concern B research application; blocks RLA mechanism design |
| D43 / AUTHORITY-GAP-1 (Enrollment Authority) | Blocks eligible voter population for statistical audit; constrains Concern A eligibility audit trail |
| D35, D36, D37, ADGR-1 (ReplaySession governance) | Blocks Concern C — evidence integrity certification semantics |
| C-02 resolution (Verification Representation Context) | May share evidence access patterns with auditability; relationship to audit evidence not yet modeled |
| CAH-02 (Independent Verification Observer) | Candidate that touches all three concerns; ownership unresolved |
| OBS-36A-08-1 (Audit Gravity Well) | Binding guardrail — prevents silent expansion of Audit context to own certification |
| Gap A-3 (Completeness — PRIMARY RISK) | No mitigation discovered; must be addressed by 36B literature |

---

## ARB Review

```
Round 36B-02: APPROVED
Round 36B-03: AUTHORIZED
```
