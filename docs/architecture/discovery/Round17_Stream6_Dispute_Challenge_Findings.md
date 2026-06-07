# Round 17 — Stream 6: Dispute & Challenge Discovery — Evidence Findings

**Date:** 2026-06-07

**Phase:** Phase 1 — Evidence Gathering (Stream 6A)

**Status:** APPROVED FOR CHECKPOINT REVIEW
- Checkpoint Purpose: Determine if evidence justifies Stream 6B
- Evidence Corpus: Not yet accepted (pending checkpoint decision)
- Stream 6B: Authorized (narrow scope: D35 + D36 only)

**Investigation Scope:** Determine whether challenge/dispute handling is implemented, partial, deferred, organizational, distributed, or unknown.

---

## Stream 6A — Challenge Presence Assessment

### Investigation Questions

1. What mechanisms exist for challenging election results or governance decisions?
2. What evidence is required for valid challenges?
3. Who has authority to hear/resolve disputes?
4. How is legitimacy restored after a challenge or dispute?
5. Are challenge mechanisms implemented in software, deferred, or organizational?
6. Do challenge capabilities emerge through distributed mechanisms (arbitration, verification, authority review)?

---

## Phase 1: Capability Discovery — Observed Behaviors

**Objective:** Search for observable behaviors that support challenge processes BEFORE searching for vocabulary.

### Behavior 1: Decision Review (OBSERVED)

**Artifact:** `ConstitutionalArbitrationKernel`

**Location:** `app/Contexts/Membership/Domain/Committee/Constitutional/ConstitutionalArbitrationKernel.php`

**Observed Behavior (Tier 2 Evidence):**
- Implements `decide(CapabilityContext, CapabilityType, DateTimeImmutable)` method
- Takes a governance decision and subjects it to `ConstitutionalArbitrationPolicy.arbitrate()`
- Returns `ConstitutionalGovernanceDecision` with evaluated legitimacy
- Behavior: **Constitutional decision review and legitimacy evaluation**

**Note:** Relationship to challenge handling is unresolved. Arbitration may support challenges, or may be independent governance mechanism.

---

### Behavior 2: Authority Conflict Resolution (OBSERVED)

**Artifact:** `ConflictResolutionPolicy` interface + `DefaultConflictResolutionPolicy` implementation

**Location:** `app/Contexts/Membership/Domain/Committee/Geo/Resolution/`

**Observed Behavior (Tier 2 Evidence):**
- Takes `AuthorityClassification` (with direct, delegated, overrides, exceptions)
- Resolves conflicting authorities into `FinalAuthorityDecision`
- Can determine a single winning authority from multiple candidates
- Behavior: **Authority conflict resolution**

**Note:** Support for multiple authority evaluation may have relevance to challenge mechanisms, but relationship is not yet determined.

---

### Behavior 3: Legitimacy Determination and Tracking (OBSERVED)

**Artifact:** `ConstitutionalDecision`, `ConstitutionalReason`, `GovernanceLegitimacy`

**Location:** `app/Contexts/Membership/Domain/Committee/Constitutional/`

**Observed Behavior (Tier 2 Evidence):**
- Records legitimacy status (LEGITIMATE, EXPIRED)
- Tracks reason codes (resolved, no_authority)
- Reason contains severity levels (DETERMINATIVE, BINDING, PERSUASIVE)
- Reason contains legitimacy impact (VALID, QUESTIONABLE, INVALID)
- Behavior: **Legitimacy evaluation and status recording**

---

### Behavior 4: Evidence Trail Recording (OBSERVED)

**Artifact:** `ConstitutionalArbitrationTrace`

**Location:** `app/Contexts/Membership/Domain/Committee/Constitutional/ConstitutionalArbitrationTrace.php`

**Observed Behavior (Tier 2 Evidence):**
- Records all evaluated nodes (candidates/authorities)
- Records selected node (winner)
- Records precedence reason (why winner was chosen)
- Records doctrine rules applied (constitutional rules used)
- Records rejection reasons (why others were rejected)
- Behavior: **Evidence trail recording with full decision justification**

---

### Behavior 5: Authority Escalation (OBSERVED)

**Artifact:** `ConstitutionalArbitrationPolicy` interface with `DefaultConstitutionalArbitrationPolicy` implementation

**Location:** `app/Contexts/Membership/Domain/Committee/Constitutional/`

**Observed Behavior (Tier 2 Evidence):**
- Implements policy-based arbitration
- Evaluates authority classifications
- Produces legitimacy decisions
- Comment on line 15-16 indicates planned extension:
  ```
  // LegitimacyPolicy NOT injected here — extension point for GEO-3.2+
  // when administrative signals (suspension, emergency declarations) materialize
  ```
- Behavior: **Policy-driven authority evaluation with planned administrative override**

**Note:** Administrative override mechanism is planned, not yet implemented.

---

### Behavior 6: Governance Decision Linking (OBSERVED)

**Artifact:** `ConstitutionalGovernanceDecision`

**Location:** `app/Contexts/Membership/Domain/Committee/Constitutional/ConstitutionalGovernanceDecision.php`

**Observed Behavior (Tier 2 Evidence):**
- Links governance decision to constitutional decision
- Exposes `isConstitutionallyValid()` method
- Provides `legitimacy()` accessor
- Behavior: **Legitimacy validation linking to governance decisions**

**Note:** Consequences of validity determination are not observed (reversal, blocking, advisory).

---

## Phase 2: Explicit Challenge Vocabulary Search

**Search terms:** challenge, appeal, dispute, contest, objection, complaint, grievance, nullify, overturn, invalidate

**Results:** 4 files found, none containing challenge/dispute submission mechanisms
- `app/Models/Election.php`
- `app/Domain/Election/Enum/VoterSourceStrategy.php`
- Database migrations (governance metadata, contributions)

**Finding:** **NO explicit challenge/dispute vocabulary or mechanisms found**

---

## Phase 3: Indirect Challenge Vocabulary Search

**Search terms:** arbitration, arbitrate, recertification, revalidation, reinstatement, review authority, manual intervention, exception process, override, escalate

**Results:** Found references to:
- Arbitration mechanisms (observed in Phase 1)
- Authority overrides and escalation (part of ConflictResolutionPolicy)
- Election suspension/resumption (governance overlay)
- No explicit recertification, revalidation, or reinstatement terms

**Finding:** **No distinct legitimacy restoration mechanism observed**

---

## Phase 4: Legitimacy Restoration Discovery (SEPARATE INVESTIGATION)

**Objective:** Investigate legitimacy restoration independently from challenge mechanisms.

**Search terms:** recertification, revalidation, trust restoration, divergence resolution, suspension lifting

**Results:** No direct implementations found. Related artifacts from prior streams:

**From Stream 4 (Audit):**
- `ReplayCertification` — certifies outcomes through replay verification
- `ReplayDivergenceDetected` — detects when evaluation diverged
- These provide **verification and divergence detection**

**From Stream 1-2 (Governance):**
- `GovernanceDecision` — records formal decisions
- `ConstitutionalLegitimacyPolicy` (Governance context) — determines legitimacy
- These provide **trust-based legitimacy decisions**

**Finding:** **No separate legitimacy restoration process observed in examined implementation. Relationship between legitimacy status and decision consequences remains unresolved.**

---

## Phase 5: Challenge Capability Matrix

| Artifact | Observed Behavior | Potential Relevance | Tier | Confidence |
|---|---|---|---|---|
| ConstitutionalArbitrationKernel | Reviews governance decisions; applies policy; determines legitimacy status | May support decision review, but relationship to challenge handling not determined | Tier 2 | Medium |
| ConflictResolutionPolicy | Resolves multiple conflicting authorities to single winner | May support authority arbitration, but relationship to challenge handling not determined | Tier 2 | Medium |
| ConstitutionalArbitrationPolicy | Applies policy logic to authority classification | May evaluate conflicts in decision review, but purpose unclear | Tier 2 | Medium |
| ConstitutionalDecision | Records legitimacy status (LEGITIMATE/EXPIRED) | Status determination observed; enforcement unknown | Tier 2 | High |
| ConstitutionalArbitrationTrace | Records evaluated nodes, winner, rejection reasons | Provides evidence trail for decisions; relevance to disputes unclear | Tier 2 | Medium |
| ConstitutionalReason | Encodes severity and legitimacy impact | Enables classification of decision validity; use in disputes unknown | Tier 2 | Medium |
| GovernanceLegitimacy | Enum with LEGITIMATE, EXPIRED states | Basis for determining decision status; consequences unknown | Tier 2 | High |
| ReplayCertification (Stream 4) | Certifies outcomes by replaying evidence | Could support verification of decisions under dispute | Tier 2 | Low |
| ReplayDivergenceDetected (Stream 4) | Detects when evaluation diverged from original | Could identify when decisions would change; relationship unclear | Tier 2 | Low |

---

## Phase 6: Outcome Assessment

### Observed Facts (Tier 2 Evidence)

1. **Arbitration mechanisms exist** — ConstitutionalArbitrationKernel evaluates governance decisions
2. **Legitimacy determination exists** — Decisions are assigned legitimacy status
3. **Authority arbitration exists** — Multiple authorities can be evaluated
4. **Evidence trails exist** — Decisions are traced with reasoning
5. **NO explicit challenge submission found** — No "file challenge" or "submit dispute" mechanisms
6. **Enforcement unknown** — Consequences of legitimacy determination not observed
7. **Invocation authority unknown** — Who can trigger arbitration review not determined

### Outcome Classification: MULTIPLE INTERPRETATIONS POSSIBLE

The evidence is **consistent with Outcome F (Distributed Challenge Capability)**, but this remains a hypothesis, not a confirmed conclusion.

**Interpretation F (Distributed):** Arbitration mechanisms collectively provide challenge-like capability through constitutional validation. *Hypothesis-consistent but unconfirmed.*

**Alternative Interpretations Remain Possible:**
- **Interpretation C (Deferred):** Arbitration is foundational; challenge handling is not yet implemented
- **Interpretation D (Organizational):** Software provides decision review; dispute resolution is organizational
- **Interpretation E (Insufficient):** Evidence insufficient to determine relationship

**Critical Unknowns:**
- **D35 (HIGH STRATEGIC):** Do legitimacy determinations reverse decisions (corrective), block future actions (preventative), or provide advisory input only?
- **D36 (HIGH STRATEGIC):** Who is authorized to invoke ConstitutionalArbitrationKernel? Anyone or restricted authorities?

**These unknowns prevent definitive outcome classification.** Arbitration and legitimacy evaluation are observed. Challenge handling is not. Whether observed mechanisms constitute a challenge system depends on D35 and D36.

---

## Hypotheses Updates

### H22: Challenge Mechanisms Are Organizational

**Status:** Open (Evidence Insufficient)

**Evidence For (Tier 2):**
- No explicit "submit challenge" or "file dispute" mechanism found
- Invocation authority for arbitration not determined
- Challenge submission may require organizational process

**Evidence Against (Tier 2):**
- ConstitutionalArbitrationKernel provides software-based decision review
- ConflictResolutionPolicy provides software-based arbitration
- Legitimacy evaluation is implemented in software

**Rationale:** Arbitration is implemented in software. Challenge submission mechanism is not observed. Evidence is insufficient to determine whether challenge handling is purely organizational, partially implemented, or deferred.

---

### H23: Legitimacy Restoration Is Independent

**Status:** Open (Evidence Insufficient)

**Evidence For (Tier 2):**
- ConstitutionalDecision records legitimacy status independently
- Legitimacy determination happens in arbitration kernel, not as challenge response
- Replay/divergence detection can trigger legitimacy questions independently

**Evidence Against (Tier 2):**
- No separate legitimacy restoration process observed
- No enforcement mechanism observed

**Rationale:** Legitimacy status is recorded and determined. No separate legitimacy restoration process was observed in examined implementation. Whether legitimacy status triggers automatic reversal, blocks future actions, or serves as advisory information remains unresolved (D35).

---

## New Discovery Debt Items

### D33: How Do Governance Decisions Become Subject to Constitutional Review?

**Question:** ConstitutionalArbitrationKernel exists and can evaluate governance decisions. What mechanism causes a governance decision to be submitted for constitutional review? Is review automatic, triggered by invocation, challenge-driven, governance-driven, or audit-driven?

**Why Unresolved:**
- Review mechanism exists but invocation pattern not determined
- Question phrased as "trigger" assumes implementation mechanism
- Multiple pathways could lead to review

**Priority:** MEDIUM

**Recommended Discovery:** Investigate ConstitutionalArbitrationKernel invocation patterns and decision review initiation mechanisms

---

### D34: What Is the GEO-3.2+ Legitimacy Escalation?

**Question:** DefaultConstitutionalArbitrationPolicy references GEO-3.2+ adding administrative signals (suspension, emergency declarations) for legitimacy. What capability is planned?

**Priority:** MEDIUM

**Recommended Discovery:** Check GEO-3.2 specification; clarify administrative signals intent

---

### D35: Can Decisions Be Reversed Through Arbitration?

**Question:** When ConstitutionalDecision determines legitimacy = EXPIRED, what happens? Does this automatically reverse the original governance decision, block future actions, or provide advisory information only?

**Why Critical:** This determines whether arbitration is:
- **Corrective** (reverses decisions) — would support challenge-like capability
- **Preventative** (blocks future actions) — enforcement without correction
- **Advisory** (informs without enforcing) — observational only

**Priority:** HIGH STRATEGIC

**Rationale:** This distinction is critical for understanding whether arbitration mechanisms function as dispute resolution. The consequences of legitimacy determination determine whether arbitration is corrective (challenge-supporting) or merely preventative/advisory.

**Recommended Discovery:** Trace ConstitutionalGovernanceDecision through capability enforcement to understand legitimacy status consequences

---

### D36: Who Is Permitted to Invoke ConstitutionalArbitrationKernel?

**Question:** ConstitutionalArbitrationKernel.decide() exists and can review governance decisions. Who/what is authorized to invoke this kernel? Is it available to anyone, restricted to constitutional authorities, or automatic?

**Why Critical:** Determines whether challenge-like capability is:
- **Distributed** (anyone can invoke review) — Outcome F
- **Restricted** (only officials can invoke) — Outcome B/C/D
- **Automatic** (invocation is not a choice) — no submission required

**Priority:** HIGH STRATEGIC

**Rationale:** Invocation authority shapes whether system supports independent challenge mechanisms (distributed) or only official review (organizational). This directly informs outcome classification.

**Recommended Discovery:** Examine ConstitutionalArbitrationKernel call sites; identify authorization requirements

---

## Summary of Stream 6A Findings

**Challenge Presence Assessment Outcome: INDETERMINATE (Pending D35, D36)**

**Outcome F (Distributed) — HYPOTHESIS, NOT CONFIRMED**

Arbitration and legitimacy evaluation are observed. Challenge handling is not explicitly observed. Whether observed mechanisms constitute a challenge system depends on unknown enforcement and authorization factors.

**Observed:**
- Constitutional arbitration kernel (decision review)
- Conflict resolution policy (authority arbitration)
- Legitimacy determination (validity assessment)
- Evidence tracing (decision justification)

**Not Observed:**
- Explicit "submit challenge" or "file dispute" mechanism
- "Challenge" vocabulary in codebase
- Enforcement consequences of legitimacy determination
- Authorization rules for arbitration invocation

---

**Stream 6A Status: APPROVED FOR CHECKPOINT REVIEW**

**Outcome: INDETERMINATE (F-hypothesis consistent, alternatives possible)**

**Hypotheses: H22 (Open), H23 (Open)**

**Discovery Debt: D33 (MEDIUM), D34 (MEDIUM), D35 (HIGH STRATEGIC), D36 (HIGH STRATEGIC)**

**ARB Checkpoint Decision:**
- Stream 6A quality: APPROVED
- Evidence Corpus acceptance: PENDING checkpoint results
- Stream 6B authorization: APPROVED (narrow scope)

**Stream 6B Scope: INVOCATION AND CONSEQUENCE ANALYSIS ONLY**

Narrow focus:
- **D35:** What happens after legitimacy becomes EXPIRED?
- **D36:** Who is permitted to invoke ConstitutionalArbitrationKernel?

NOT included in Stream 6B:
- General dispute/challenge discovery
- Appeal processes
- Complaint handling
- Full challenge workflow investigation

**Strategic Note:**
Round 17 pattern emerging across all streams:
- Strong: Evaluation, classification, verification mechanisms
- Weak: Initiation, ownership, origin, authority-to-act

This pattern is becoming a discovery result in its own right.

