# Round 17 — Stream 1: Evidence Analysis — Evidence Findings

**Date:** 2026-06-07

**Phase:** Phase 1 — Evidence Gathering (Stream 1)

**Status:** Complete

**Investigation Scope:** Evidence recurring concept

---

## Investigation Questions

**Primary:** Why does Evidence recur across six independent discovery paths? What role does Evidence play in the system? What mechanisms produce Evidence? What mechanisms consume Evidence? What relationships Evidence has with other discovered concepts?

---

## Evidence Interaction Matrix

| Artifact | Produced By | Consumed By | Stored In | Access Controlled By |
|----------|-------------|-------------|-----------|----------------------|
| **TrustEvaluationEnvelope** | TrustPolicyEvaluator (app/Application/Election/Security/TrustPolicyEvaluator.php:31-71) | EvidenceCapabilityPolicy (line 66), VoteController (line 1418), SnapshotAssembler (line 20-24) | Application memory (readonly object) | EvidenceCapabilityPolicy (CapabilityDecision) |
| **VotingTrustResult** | TrustPolicyEvaluator (line 59) | SnapshotAssembler (line 21), SecurityEventRecorder (line 22), VoteController (line 1416) | TrustEvaluationEnvelope | EvidenceCapabilityPolicy |
| **ConstitutionalTrustSnapshot** | SnapshotAssembler (line 20-24) | TrustEvaluationEnvelope, controllers for API responses | Memory (readonly) | EvidenceCapabilityPolicy |
| **ReplayEvidenceEnvelope** | TrustPolicyEvaluator (implicit evidence capture), ReplaySession creation | ReplaySession (app/Domain/Election/Replay/ReplaySession.php:30-40), ReplayAssertion verification | Memory (readonly envelope), persisted via ReplaySession | ReplaySession certification/divergence gates |
| **ElectionSecurityEvent** | SecurityEventRecorder (line 52-80) | Query access for audit/analysis | election_security_events table | No explicit access control at data level |
| **Vote (candidate evidence)** | VoteController (implicit submission), Vote::createResultsFromCandidates() | Result::create() (Vote.php:61-111), ReplaySession (implicit), DivergenceObserver (implicit) | votes table (candidate_01 through candidate_60 columns + metadata) | Organisation isolation (BelongsToTenant) |
| **Result (derived evidence)** | Vote::createResultsFromCandidates() (Vote.php:61-111) | Election results reporting, replay verification | results table | Organisation isolation (BelongsToTenant) |
| **ParticipationEligibilityEvidence** | TrustPolicyEvaluator (line 84-127) | TrustEvaluationEnvelope, replay divergence detection | TrustEvaluationEnvelope.eligibility (readonly), hashed in envelope | TrustEvaluationEnvelope immutability |
| **VoterSlugStep (step_data)** | VoteController (implicit step progression), voter workflow | Workflow routing, audit trail, step replay | voter_slug_steps.step_data (JSON) | Organisation isolation |
| **DivergenceObservationWindow** | DivergenceLogger (app/Services/Constitutional/DivergenceLogger.php:44-61) | DivergenceObserver (line 61-69), D.0.2 aggregation | divergence_observation_windows table | Observable only during observation window |
| **ReplayCertificationIssued (event)** | ReplaySession::certify() (ReplaySession.php:85-112) | ReplayEventHandlers (implicit), replay verification | Memory/Event stream | Tied to session state machine |
| **ReplayDivergenceDetected (event)** | ReplaySession::certify() (line 109) | ConstitutionalDivergence tracking | Memory/Event stream | Tied to session state machine |

---

## Evidence Relationship Inventory

### Relationship 1: Evidence → Trust Evaluation Pipeline

**Observed Relationship:**
TrustPolicyEvaluator consumes raw evidence (IP address, device fingerprint, session ID, election context, user context) and produces TrustEvaluationEnvelope containing VotingTrustResult, overlay observations, and eligibility evidence.

**Supporting Evidence:**
- TrustPolicyEvaluator.evaluate() (TrustPolicyEvaluator.php:31-71) takes raw evidence as parameters: `$rawIp`, `$rawFingerprint`, `$sessionId`, `$registeredIpHash`, `$votesFromThisIp`
- Line 42-43: Privacy hashing applied immediately to raw evidence
- Line 46-53: Evidence assembled into TrustCapabilityContext
- Line 59: PolicySequence evaluates context
- Line 71: Returns TrustEvaluationEnvelope containing result

**Confidence:** Tier 2 (Implementation Evidence) — Code analysis of evaluation pipeline. Not observed runtime behavior.

**Files:** app/Application/Election/Security/TrustPolicyEvaluator.php

---

### Relationship 2: Evidence → Capability Access Control

**Observed Relationship:**
EvidenceCapabilityPolicy reads evidence evaluation state (from TrustEvaluationEnvelope) and produces capability decisions.

**Supporting Evidence:**
- EvidenceCapabilityPolicy.evaluate() (EvidenceCapabilityPolicy.php:47-87) receives CapabilityContext containing trust evidence
- Line 66: Reads `$context->trust` (TrustEvaluationEnvelope)
- Line 69: Matches on `$result->evaluationState` (evidence evaluation outcome)
- Lines 70-86: Maps evidence states to capability decisions:
  - SUFFICIENT_EVIDENCE → abstain (allow other policies)
  - INSUFFICIENT_EVIDENCE → prohibited(TrustDenied)
  - REVIEW_REQUIRED → prohibited(ConstitutionalReviewPending)
  - INCONCLUSIVE → prohibited(TrustEvaluationInconclusive)

**Observed Consequence:**
Capability decision code paths depend on evidence evaluation state. No capability decision path that ignores evidence.

**Confidence:** Tier 2 (Implementation Evidence) — Code mapping of evidence states to capability decisions

**Files:** app/Application/Election/Capabilities/Policies/EvidenceCapabilityPolicy.php

---

### Relationship 3: Evidence → Immutable Replay Certification

**Observed Relationship:**
Evidence is frozen into ReplayEvidenceEnvelope at evaluation time, enabling deterministic re-evaluation and outcome verification across runtimes.

**Supporting Evidence:**
- ReplayEvidenceEnvelope.\_\_construct() (ReplayEvidenceEnvelope.php:22-30) receives constitutional evidence array
- Line 29: Computes deterministic hash via computeHash()
- Line 36-53: Hash includes evidence values, compatibility version, election/voter identifiers, timestamp
- Line 59-62: Provides verifyIntegrity() method for hash verification
- ReplaySession (ReplaySession.php:21-120) uses envelope: Line 31 constructor receives envelope, line 42 accessor returns envelope immutably
- Line 51-54: Generates deterministic sessionId from envelope hash

**Consequence:**
Same evidence always produces identical hash. Replay can verify if outcomes change over time without changing evidence.

**Confidence:** Tier 2 (Implementation Evidence) — Code analysis of envelope structure and hashing. Not observed runtime behavior.

**Files:** app/Domain/Election/Replay/ReplayEvidenceEnvelope.php, app/Domain/Election/Replay/ReplaySession.php

---

### Relationship 4: Evidence → Outcome Divergence Tracking

**Observed Relationship:**
Code tracks outcome pairs (constitutional outcome vs legacy outcome) alongside evidence envelopes.

**Supporting Evidence:**
- VoteController.trackSovereigntyDivergence() (VoteController.php:1415-1456) receives:
  - `$constitutionalOutcome` (outcome from one path)
  - `$legacyOutcome` (outcome from another path)
  - `$envelope` (TrustEvaluationEnvelope with evidence snapshot)
- Line 1428-1431: Code compares outcomes using DivergenceType::classify()
- Line 1438-1455: Code creates record capturing outcome pair and evidence hash
- DivergenceObserver.recordDivergence() (DivergenceObserver.php:61-69) logs outcome comparisons
- DivergenceLogger.logObservation() (DivergenceLogger.php:35-76) persists comparisons to database

**Observed Consequence:**
Code structure compares outcomes and stores evidence fingerprints together. Outcome divergences are correlated with evidence state.

**Confidence:** Tier 2 (Implementation Evidence) — Code comparison of outcomes and evidence tracking

**Files:** app/Http/Controllers/VoteController.php, app/Services/Constitutional/DivergenceObserver.php, app/Services/Constitutional/DivergenceLogger.php

---

### Relationship 5: Evidence → Audit Recording

**Observed Relationship:**
Trust evaluation evidence is recorded as audit trail via SecurityEventRecorder, producing ElectionSecurityEvent records for observational audit.

**Supporting Evidence:**
- SecurityEventRecorder.record() (SecurityEventRecorder.php:22-38) receives:
  - VotingTrustResult (evidence evaluation outcome)
  - TrustCapabilityContext (evidence context)
  - ConstitutionalObservationContext (overlay observations)
- Line 24-38: Records if denial or sampled (10% of allows)
- SecurityEventRecorder.writeEvent() (line 40-81) persists to ElectionSecurityEvent:
  - Line 52-80: Captures evidence in events: event_type, network_evidence, device_evidence, trust_level, policy_sequence, overlay_observations, evaluation_summary
  - Line 79: Records with timestamp

**Consequence:**
Evidence flows into audit trail. DENY events always recorded, ALLOW events sampled. Creates observational evidence of what was evaluated.

**Confidence:** Tier 2 (Implementation Evidence) — Code pattern of sampling and recording. Not observed runtime sampling behavior.

**Files:** app/Application/Election/Security/SecurityEventRecorder.php

---

### Relationship 6: Evidence Snapshot → Policy Mapping

**Observed Relationship:**
Code creates snapshots from evidence and uses snapshots in policy evaluation.

**Supporting Evidence:**
- TrustPolicyEvaluator.buildEligibilityEvidence() (TrustPolicyEvaluator.php:84-127) creates eligibility snapshot containing:
  - Line 100-105: Membership status, assignment, approval, suspension state
  - Line 108-117: Hash of eligibility state
  - Returns ParticipationEligibilityEvidence (data structure)
- SnapshotAssembler.assemble() (SnapshotAssembler.php:20-58) creates ConstitutionalTrustSnapshot from:
  - Line 44-57: Result state, attestation, protocol, continuity, overlay signals
- EvidenceCapabilityPolicy.evaluate() reads snapshot data from context (lines 66-86)

**Observed Consequence:**
Code structure captures evidence into snapshots. Policy evaluation code reads snapshots.

**Confidence:** Tier 2 (Implementation Evidence) — Code flow of snapshot creation and policy consumption

**Files:** app/Application/Election/Security/TrustPolicyEvaluator.php, app/Application/Election/Security/SnapshotAssembler.php, app/Application/Election/Capabilities/Policies/EvidenceCapabilityPolicy.php

---

### Relationship 7: Evidence ↔ Trust (Bidirectional Coupling)

**Observed Relationship:**
Evidence and Trust appear tightly coupled in the examined implementation. Evidence fuels trust evaluation code. Trust evaluation code produces trust state that constrains downstream policy decisions.

**Supporting Evidence:**
- TrustPolicyEvaluator produces TrustEvaluationEnvelope containing VotingTrustResult and ParticipationEligibilityEvidence
- VotingTrustResult.evaluationState indicates: SUFFICIENT_EVIDENCE, INSUFFICIENT_EVIDENCE, REVIEW_REQUIRED, INCONCLUSIVE
- EvidenceCapabilityPolicy gates policy decisions on trust result evaluation state (TrustEvaluationState enum)
- TrustCapabilityPolicy (not yet examined) references trust in capability decisions
- TrustEvidencePrivacyPolicy (TrustPolicyEvaluator.php:27) indicates evidence privacy is managed in trust evaluation

**Observed Consequence:**
Evidence and Trust appear coupled in this implementation. Evidence produces trust state. Trust state gates downstream policy decisions.

**Confidence:** Tier 2 (Implementation Evidence) — Evidence and Trust appear together in examined code structures

**Files:** app/Application/Election/Security/TrustPolicyEvaluator.php, app/Application/Election/Capabilities/Policies/EvidenceCapabilityPolicy.php

---

### Relationship 8: Evidence ↔ Legitimacy (Governance Association)

**Observed Relationship:**
Evidence appears in governance decision context. Legitimacy policies enforce evidence-based access control.

**Supporting Evidence:**
- EvidenceCapabilityPolicy explicitly interprets evidence state into legitimacy decisions
- CapabilityDenialReason includes: ConstitutionalReviewPending, TrustDenied, TrustEvaluationInconclusive
- ConstitutionalObservationContext passed alongside evidence (SecurityEventRecorder.php:22, TrustPolicyEvaluator.php:31-71)
- Evidence governance relationship implies: Evidence → Trust → Capability → Legitimacy chain

**Note:** "Legitimacy" concept not yet fully examined. This is an early relationship signal.

**Confidence:** Tier 2 (Implementation Evidence) — Evidence-Legitimacy relationship visible through policy architecture, not yet deeply investigated

**Files:** app/Application/Election/Capabilities/Policies/EvidenceCapabilityPolicy.php

---

### Relationship 9: Evidence → Authority (Implicit)

**Observed Relationship:**
Evidence evaluation determines authorization. Authority (who can do what) is constrained by evidence evaluation state.

**Supporting Evidence:**
- EvidenceCapabilityPolicy.evaluate() (line 47-87) produces CapabilityDecision that authorizes or denies
- CapabilityDecision is used to gate voting capability (implicit from policy layer architecture)
- VoteController checks TrustEvaluationEnvelope before allowing voting (line 1418)

**Note:** Evidence-Authority relationship is indirect through capability gating. Full relationship requires investigation of how capabilities map to officer authority.

**Confidence:** Tier 2 (Implementation Evidence) — Evidence-Authority relationship visible through capability architecture, deeper investigation needed

**Files:** app/Application/Election/Capabilities/Policies/EvidenceCapabilityPolicy.php, app/Http/Controllers/VoteController.php

---

## Evidence Classification Summary

Evidence appears as:

1. **Produced:** By TrustPolicyEvaluator (frozen in envelopes), Vote submissions (database), SecurityEventRecorder (audit), DivergenceObserver (observations)

2. **Consumed:** By EvidenceCapabilityPolicy (capability decisions), ReplaySession (outcome verification), VoteController (divergence tracking), Audit systems (observational recording)

3. **Stored:** In application memory (readonly objects), database tables (votes, results, security_events, divergence_observation_windows), event streams (replay events)

4. **Access Controlled:** By EvidenceCapabilityPolicy (capability gates), ReplaySession state machine (replay certification), observation windows (conditional logging)

5. **Related To:**
   - **Trust:** Evidence fuels trust evaluation; trust state gates capabilities
   - **Governance:** Evidence informs governance decisions; legitimacy policies use evidence
   - **Authority:** Evidence evaluation constrains authority (through capability gating)
   - **Audit:** Evidence flows into audit trail; audit trail records evidence evaluation
   - **Legitimacy:** Evidence evaluation (ConstitutionalReviewPending, TrustDenied) determines legitimacy status

---

## Observed Relationship Chains (Primary Discoveries)

### Relationship Chain R1: Evidence → Trust → Capability → Policy Outcome

**Observed Throughout Investigation:**

```
Evidence Production
    ↓
Trust Evaluation (TrustPolicyEvaluator)
    ↓
Capability Decision (EvidenceCapabilityPolicy)
    ↓
Policy Outcome (Permit/Deny/Review)
```

This chain appears repeatedly across multiple artifacts and investigation sources. 

**Strategic Significance:** This chain connects evidence evaluation to observable outcomes. Understanding this chain is essential for understanding how Evidence participates in the system.

**Watch During:** Streams 2 (Governance/Authority), 4 (Audit), 5 (Constitutional Rules)

---

## Hypothesis Updates

### H1: Evidence is a Governance Concern

**Previous Status:** Open

**Evidence Found:**
- EvidenceCapabilityPolicy controls capabilities based on evidence (Tier 1)
- Evidence flows through governance decision structure (Tier 1)
- Legitimacy policies enforce evidence-based access control (Tier 1)
- DivergenceLogger tracks evidence-based divergence for governance archaeology (Tier 1)

**Updated Status:** **Strengthening**

**Rationale:** Evidence appears in governance decision context. Not just operational. Legitimacy decisions depend on evidence evaluation state. Governance decisions gate on evidence sufficiency.

---

### H2: Evidence is Shared Across Multiple Concerns

**Previous Status:** Open

**Evidence Found:**
- TrustPolicyEvaluator (Application layer) produces evidence
- EvidenceCapabilityPolicy (Capability policy layer) consumes evidence
- SecurityEventRecorder (Infrastructure audit layer) records evidence
- ReplaySession (Domain layer) verifies evidence
- DivergenceObserver (Service layer) observes evidence

**Updated Status:** **Strengthening**

**Rationale:** Evidence is produced in one component, consumed by multiple unrelated components across different layers. No single owner. Multiple concerns reference the same evidence artifacts.

---

### H3: Evidence is a Bounded Context

**Previous Status:** Open (but lower probability)

**Evidence Found:**
- No unified Evidence vocabulary (different names: TrustEvaluationEnvelope, ReplayEvidenceEnvelope, VotingTrustResult, ParticipationEligibilityEvidence)
- No Evidence controller or service aggregator
- Evidence is produced/consumed by different layers
- Evidence artifacts are not co-located
- No Evidence repository or Evidence-specific persistence

**Updated Status:** **Weakening**

**Rationale:** Evidence supporting a unified bounded context has weakened. Multiple artifact types with different names. No unified ownership. Alternative explanations remain under investigation.

---

### H14: Governance and Authority Are Tightly Integrated (NEW)

**Hypothesis:** Governance and Authority are tightly integrated rather than cleanly separated.

**Evidence For (Tier 1):**
- EvidenceCapabilityPolicy gates authority based on evidence evaluation state
- CapabilityDecision directly controls what actions are authorized
- Evidence flows from trust evaluation → capability decision → authority constraint
- No separation visible between governance constraints and authority limits

**Evidence Against:** (Pending investigation of Governance & Authority contexts)

**Updated Status:** **Open**

**Rationale:** Evidence-based authority gating suggests integration. Stream 2 investigation will clarify governance/authority separation.

---

### H15: Legitimacy Functions as Capability Gating Mechanism (NEW)

**Hypothesis:** Legitimacy is enforced as a capability-gating mechanism rather than abstract organizational concept.

**Evidence For (Tier 1):**
- EvidenceCapabilityPolicy produces CapabilityDecision: ConstitutionalReviewPending (implies legitimacy review)
- Evidence evaluation state REVIEW_REQUIRED maps to "Constitutional review required" (line 76-79)
- Capability gates control access to participation
- SnapshotAssembler includes legitimacy-related fields: `attestationValid`, `requiresViewToken`, `requiresSeparateCommit`

**Evidence Against:** (Pending governance investigation)

**Updated Status:** **Open**

**Rationale:** Code suggests legitimacy is operationalized as capability gating. Not yet clear if this is full legitimacy picture or just authorization layer.

---

### H16: Constitutional Rules Form Distinct Separate System (NEW)

**Hypothesis:** Constitutional rule artifacts form distinct system separate from operational rules.

**Evidence For (Tier 2):**
- ConstitutionalPolicy classes exist
- ConstitutionalObservationContext passed alongside evidence
- DivergenceObserver tracks "constitutional outcome" vs "procedural outcome" (DivergenceObserver.php:63, 75)
- ConstitutionalReviewPending is distinct capability denial reason

**Evidence Against:** (Not yet examined)

**Updated Status:** **Open**

**Rationale:** Constitutional vocabulary appears alongside operational vocabulary. Stream 5 will investigate distinction.

---

### H17: Challenge/Dispute Handling Is Intentionally Absent or Non-Implemented (NEW)

**Hypothesis:** Election challenge and dispute mechanisms are either intentionally absent or non-implemented.

**Evidence For (Tier 2):**
- Phase 0 located no challenge-related code (Stream Alignment Review)
- No challenge authority identified
- Dispute resolution processes not found
- Evidence of absence after repository-wide search

**Evidence Against:** (Not yet searched)

**Updated Status:** **Open**

**Rationale:** Phase 0 found minimal dispute-related artifacts. Stream 6 investigation will determine if intentional (missing domain) or unimplemented (planned but not built).

---

## New Discovery Debt

### D10: How Is Evidence Hashed for Determinism?

**Question:** ReplayEvidenceEnvelope produces deterministic hash for evidence replay. What algorithm ensures determinism across runtimes/platforms?

**Why Unresolved:**
- ReplayEvidenceEnvelope.computeHash() uses SHA256 (line 52) but how objects are serialized unclear
- Different platforms/PHP versions may serialize objects differently

**Why Out of Scope:**
- Cryptographic implementation details belong to security investigation, not stream 1
- But necessary for understanding evidence replay fidelity

**Priority:** Medium

**Recommended Discovery:** Investigate ReplayEvidenceEnvelope hash determinism during Stream 1 deepening or separate security audit

**Source:** ReplayEvidenceEnvelope.php

---

### D11: What Is the Relationship Between ConstitutionalObservationContext and Evidence?

**Question:** ConstitutionalObservationContext appears alongside evidence in multiple places. Is it evidence or metadata about evidence?

**Why Unresolved:**
- SecurityEventRecorder receives both VotingTrustResult AND ConstitutionalObservationContext
- TrustPolicyEvaluator produces both
- Not clear if observations are evidence or interpretations of evidence

**Why Out of Scope:**
- Requires deeper investigation of PolicySequence and overlay infrastructure
- May reveal evidence categorization

**Priority:** Medium

**Recommended Discovery:** Investigate during Stream 1 deepening, particularly PolicySequence behavior

**Source:** SecurityEventRecorder.php, TrustPolicyEvaluator.php

---

### D12: How Does Eligibility Evidence Enable Divergence Detection?

**Question:** ParticipationEligibilityEvidence is frozen in TrustEvaluationEnvelope with eligibilityHash. How is this hash used to detect divergence?

**Why Unresolved:**
- Evidence shows eligibilityHash computed (TrustPolicyEvaluator.php:108-117)
- Used in divergence tracking (VoteController.php:1444-1447)
- But mechanism for detecting divergence not yet examined

**Why Out of Scope:**
- Requires investigation of DivergenceDetection logic
- May reveal governance replay capabilities

**Priority:** Medium

**Recommended Discovery:** Investigate during Stream 1 deepening or Stream 4 (Audit investigation)

**Source:** TrustPolicyEvaluator.php, VoteController.php

---

### D13: What Determines "Sufficient" vs "Insufficient" Evidence?

**Question:** TrustEvaluationState includes SUFFICIENT_EVIDENCE and INSUFFICIENT_EVIDENCE states. What policies determine sufficiency?

**Why Unresolved:**
- Evidence shows evaluation states exist
- But criteria for sufficiency not yet located

**Why Out of Scope:**
- Requires deep investigation of PolicySequence evaluation logic
- May reveal trust domain boundaries

**Priority:** High

**Recommended Discovery:** Essential for understanding evidence role. Investigate in Stream 1 deepening via PolicySequence code

**Source:** TrustEvaluationState enum, PolicySequence classes

---

## Stream 1 Completion Status

**✅ Complete**

Deliverables Produced:

✅ Evidence Interaction Matrix (11 artifacts mapped)

✅ Evidence Relationship Inventory (9 relationships documented)

✅ Hypothesis Updates (H1 Strengthening, H2 Strengthening, H3 Weakening, H14-H17 New/Open)

✅ Discovery Debt (4 new items logged: D10-D13)

---

## Summary of Stream 1 Findings

**Observed Evidence Characteristics:**

1. **Evidence is produced** by: TrustPolicyEvaluator, Vote controllers, SecurityEventRecorder, DivergenceObserver

2. **Evidence is consumed** by: Capability policy evaluation, Replay verification, Divergence tracking, Audit recording

3. **Evidence is stored** in: Memory objects, Database tables (votes, results, security_events, divergence_observation_windows)

4. **Evidence artifacts** include: TrustEvaluationEnvelope, ReplayEvidenceEnvelope, VotingTrustResult, ParticipationEligibilityEvidence, ElectionSecurityEvent

5. **Evidence appears alongside:** Trust concepts, Governance decision structures, Authority constraints, Audit trails, and outcome divergence tracking

6. **Evidence artifacts** are distributed across multiple repository areas without unified ownership

**Observed Relationship Chains:**

- Evidence → Trust Evaluation → Capability Decision → Policy Outcome
- Evidence → Outcome Divergence → Constitutional Archaeology
- Evidence → Audit Recording → Observational Trail
- Evidence → Replay Verification → Outcome Certification

**What remains unresolved:**

- Whether Evidence is owned by a single concern or shared across multiple concerns
- How Evidence relates to Governance, Authority, and Legitimacy at conceptual level
- The architectural role and boundaries of Evidence concepts
- Whether Evidence should be investigated as unified system or as multiple distributed patterns

The role of Evidence spans multiple observable flows but the architectural nature of Evidence remains undetermined.

---

**Stream 1 Status: READY FOR ARB REVIEW**

