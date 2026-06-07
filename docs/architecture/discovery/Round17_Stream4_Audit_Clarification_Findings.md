# Round 17 — Stream 4: Audit Clarification — Evidence Findings

**Date:** 2026-06-07

**Phase:** Phase 1 — Evidence Gathering (Stream 4)

**Status:** Complete

**Investigation Scope:** Clarify whether Audit is unified or two separate concerns (operational logging vs governance replay)

---

## Investigation Questions

**Primary Questions:**

1. Is audit unified or two concerns?
2. What data structures support operational audit vs governance replay?
3. Do operational audit and governance replay participate in shared flows?
4. What is the relationship between SecurityEventRecorder and ReplayEvidenceEnvelope?
5. Does evidence participate in both audit concerns or only one?
6. Are the mechanisms coupled or independently deployable?

---

## Audit Artifacts Inventory

### Operational Audit Artifacts

**A4-1: ElectionAuditLog Model**
- **Source:** `app/Models/ElectionAuditLog.php`
- **Tier:** Tier 2 (Implementation Evidence)
- **Description:** Eloquent model representing audit_logs database table. Tracks actions with before/after values.
- **Columns (lines 16-25):**
  - `action` (string) — what happened (e.g., "state_changed")
  - `old_values` (json) — previous state
  - `new_values` (json) — new state
  - `user_id` (uuid) — who did it
  - `ip_address` (string) — where from
  - `user_agent` (string) — how (device)
  - `session_id` (string) — which session
  - `created_at`, `updated_at` — when
- **Purpose:** Operational accountability tracking
- **Audience:** Administrators, auditors investigating "who changed what"

---

**A4-2: ElectionAuditService**
- **Source:** `app/Services/ElectionAuditService.php`
- **Tier:** Tier 2 (Implementation Evidence)
- **Description:** Service for logging election events to JSONL files in storage/logs/audit
- **Key Methods (lines 28-102):**
  - `log()` — logs event to category-specific file (election, voters, committee)
  - `logVoterAction()` — logs per-voter action with step number
- **Output:** JSONL files organized by:
  - Category files: `election.jsonl`, `voters.jsonl`, `committee.jsonl`
  - Per-voter files: `storage/logs/audit/{election_slug}_{date}_{time}/voters/{voter_name}.jsonl`
- **Data Recorded (lines 46-100):**
  - `event` — event name
  - `category` — event classification
  - `timestamp` — ISO8601 timestamp
  - `user_id`, `user_name`, `user_email` (masked)
  - `ip` — IP address
  - `metadata` — additional data
  - `step` — voter workflow step (1-5)
  - `action` — what voter did at that step
- **Purpose:** Operational event logging for voter action tracking
- **Audience:** Election observers, investigators tracing voter journey

---

**A4-3: SecurityEventRecorder**
- **Source:** `app/Application/Election/Security/SecurityEventRecorder.php`
- **Tier:** Tier 2 (Implementation Evidence)
- **Description:** Fire-and-forget recorder for trust evaluation events (denials always recorded, allows sampled at ~10%)
- **Key Method (lines 22-38):**
  - `record(VotingTrustResult $result, TrustCapabilityContext $ctx, ConstitutionalObservationContext $overlayObservations)`
- **Implementation (lines 25-38):**
  - Line 27-28: Always records DENY events, samples ALLOW events (~10% deterministic hash-based)
  - Line 29: Calls writeEvent()
  - Line 31-37: Fire-and-forget pattern — catches all exceptions, never propagates
- **Data Recorded (lines 52-80):**
  - `event_type` — "trust_denied" or "trust_allowed"
  - `network_evidence` — IP hash, restriction status, vote count from IP
  - `device_evidence` — fingerprint match type, volatility
  - `trust_level_before`, `trust_level_after`
  - `policy_evaluated` — which policies were evaluated
  - `policy_evaluation_sequence` — outcomes of each policy
  - `overlay_observations` — observation signal types
  - `evaluation_summary` — state, reason, policies passed
- **Purpose:** Operational trust evaluation event logging (observational, not procedural)
- **Audience:** Security analysts investigating trust decisions
- **Invariant (line 14-16):** Returns void, never throws, never affects outcome (fire-and-forget semantics)

---

### Governance Replay Artifacts

**A4-4: ReplayEvidenceEnvelope**
- **Source:** `app/Domain/Election/Replay/ReplayEvidenceEnvelope.php`
- **Tier:** Tier 2 (Implementation Evidence)
- **Description:** Sealed container for constitutional evidence at replay time. Produces deterministic hash of evidence.
- **Constructor Parameters (lines 22-28):**
  - `evidence` (array) — all constitutional evidence that participated in evaluation
  - `compatibility` (ReplayCompatibilityVersion) — schema version at freezing time
  - `frozenAt` (DateTimeImmutable) — when evidence was sealed
  - `electionIdentifier` (string) — election scope
  - `voterIdentifier` (string) — hashed voter scope
- **Invariant (lines 12-17):**
  - Evidence is frozen at envelope creation — no mutation allowed
  - Hash integrity is always verifiable
  - Same evidence always produces same envelope hash
- **Hash Algorithm (lines 36-53):**
  - Deterministic SHA256 over compatibility version, election/voter identifiers, frozen timestamp, and all evidence items
  - Uses object identity (spl_object_id) for object evidence, string conversion for scalars
- **Purpose:** Governance replay verification — enables reconstruction of evidence state
- **Audience:** Governance verification systems, divergence detectors
- **Evidence Classification:** This is the primary governance replay artifact

---

**A4-5: ReplaySession**
- **Source:** `app/Domain/Election/Replay/ReplaySession.php`
- **Tier:** Tier 2 (Implementation Evidence)
- **Description:** Root entity managing replay lifecycle — from evidence sealing through certification or divergence detection
- **Lifecycle (lines 11-19):**
  - Created with sealed evidence
  - Assertion recorded (expected outcome bound to evidence)
  - Certification produced (actual outcome recorded)
  - State transitions: sealed → replayed → certified or diverged
- **Key Methods (lines 30-79):**
  - Constructor (line 30-39) — creates session with sealed evidence, generates deterministic session ID
  - `recordAssertion()` (line 60-79) — records expected outcome, transitions to 'replayed' state
- **Invariant (lines 13-19):**
  - A session can be used for exactly one certification cycle
  - Evidence is sealed at creation — no mutation
  - Same evidence → identical certification output across runtimes
- **Purpose:** Governance replay certification — verify outcome matches expected result
- **Audience:** Governance verification, divergence detection systems

---

**A4-6: ReplayDivergenceDetected Event**
- **Source:** `app/Domain/Election/Replay/Event/ReplayDivergenceDetected.php`
- **Tier:** Tier 2 (Implementation Evidence)
- **Description:** REPLAY EVENT emitted when re-evaluated outcome differs from original
- **Data (lines 16-22):**
  - `sessionId` — identifies replay session
  - `envelopeHash` — identifies evidence set
  - `expectedOutcome` — original outcome
  - `actualOutcome` — re-evaluated outcome
  - `occurredAt` — when divergence detected
- **Purpose:** Signal constitutional integrity violation — evaluation pipeline changed
- **Audience:** Governance systems, divergence detectors, compliance auditors

---

### Shared Evidence Artifact

**A4-7: ParticipationEligibilityEvidence**
- **Source:** `app/Domain/Election/Security/Simplified/ParticipationEligibilityEvidence.php`
- **Tier:** Tier 2 (Implementation Evidence)
- **Description:** Frozen replay-addressable observational evidence about voter's constitutional eligibility at evaluation time
- **Data (lines 19-27):**
  - `hasActiveMembership`, `hasValidAssignment`, `hasApproval`, `isSuspended` (bool) — eligibility status
  - `eligibilityEvaluatedAt` (DateTimeImmutable) — when evaluated
  - `eligibilitySourceVersion` (string) — schema version
  - `eligibilityHash` (string) — deterministic hash for replay
- **Built At (line 62, TrustPolicyEvaluator.php):**
  - Built during trust evaluation as part of TrustEvaluationEnvelope
  - Frozen at evaluation boundary (observational snapshot)
- **Purpose (lines 9-12):** 
  - Observational evidence (NOT authority decision)
  - Enables replay systems to detect when runtime eligibility diverged
- **Audience:** Both operational (Trust evaluation) and governance (Replay divergence detection)
- **Classification:** This is shared evidence — built for one purpose (trust evaluation) but designed to be used by replay system

---

## Audit Interaction Matrix

| Operational Artifact | Governance Artifact | Observed Interaction | Source | Tier |
|---|---|---|---|---|
| **ElectionAuditService** (JSONL logs) | **ReplaySession** (replay) | No direct interaction. ElectionAuditService logs voter actions; ReplaySession manages replay cycles. | VoteController usage (implies separate invocations) | Tier 2 |
| **SecurityEventRecorder** (trust events) | **ReplayEvidenceEnvelope** (sealed evidence) | No direct interaction. SecurityEventRecorder records observations; ReplayEvidenceEnvelope seals evidence. SecurityEventRecorder is fire-and-forget (line 14-16, never affects outcome). | Code separation: SecurityEventRecorder in Security/, Replay in Replay/ | Tier 2 |
| **ParticipationEligibilityEvidence** | **ReplaySession** (replay) | Indirect connection: Eligibility evidence has deterministic hash (line 26) designed for replay divergence detection (line 13-15). Built during trust evaluation (TrustPolicyEvaluator line 62), available for replay system to use. Not coupled in code, but evidence is designed for replay use. | TrustPolicyEvaluator.php:62, ParticipationEligibilityEvidence.php:13-15 | Tier 2 |
| **ElectionAuditLog** (database audit) | **ReplayEvidenceEnvelope** (replay envelope) | No interaction found. Database audit table tracks changes; Replay envelope seals evidence. No cross-references. | Grep: no ElectionAuditLog→Replay references found | Tier 2 |
| **TrustPolicyEvaluator** (trust orchestration) | **ReplayEvidenceEnvelope** (governance) | Indirect relationship: TrustPolicyEvaluator builds evidence (including eligibility evidence with hash) that ReplayEvidenceEnvelope could seal, but no coupling in code. TrustPolicyEvaluator is operational; Replay is separate infrastructure. | TrustPolicyEvaluator.php:31-72, lines 62 build eligibility evidence with replay-addressable hash | Tier 2 |

---

## Operational Audit Relationship Inventory

### Relationship 1: Operational Audit Records What Happened

**Observed Relationship:**

Operational audit (ElectionAuditService + ElectionAuditLog) provides accountability tracking of actions taken, by whom, when, and what changed.

**Supporting Evidence:**

- ElectionAuditLog columns (line 14-20):
  - `action` — what happened
  - `old_values`, `new_values` — before/after state
  - `user_id` — who did it
  - `ip_address` — where from
  - `created_at` — when
- ElectionAuditService.logVoterAction() (line 72-102):
  - Records per-voter actions by step (1-5)
  - Logs action name, timestamp, voter identity (non-masked), IP, user agent
  - Per-voter audit files enable reconstructing individual voter journey
- Example flow: Voter submits vote → VoteController logs via ElectionAuditService → JSONL file records action

**Observed Consequence:**

Operational audit creates audit trail for accountability. Audience can answer: "What did voter X do at step 3 on 2026-06-07?" → Look in storage/logs/audit/{election}/voters/{voter_name}.jsonl

**Confidence:** Tier 2 (Implementation Evidence) — Code structure shows voter-centric action logging

**Files:** app/Services/ElectionAuditService.php, app/Models/ElectionAuditLog.php

---

### Relationship 2: Trust Evaluation Events Are Logged Separately

**Observed Relationship:**

SecurityEventRecorder logs trust evaluation decisions (allow/deny) separately from voter action audit.

**Supporting Evidence:**

- SecurityEventRecorder (line 22-38):
  - Records trust evaluation outcomes (allowed/denied)
  - Captures evidence used in evaluation (network, device, attestation)
  - Fire-and-forget pattern (line 14-16) — never affects outcome, never throws
  - Line 27: Always records DENY events
  - Line 28: Samples ALLOW events (~10% deterministic rate)
- ElectionSecurityEvent table (SecurityEventRecorder line 52-80):
  - Stores trust evaluation evidence, policy outcomes, overlay observations
  - Not the same as ElectionAuditLog (no user_id, different columns)
  - Designed for security analysis, not operational accountability
- No interaction between ElectionAuditLog and SecurityEventRecorder in code

**Observed Consequence:**

Trust evaluation has separate audit track from voter actions. Voter action audit answers "what did voter do"; Trust audit answers "was voter trusted at time of action"

**Confidence:** Tier 2 (Implementation Evidence) — Separate implementations, different purposes

**Files:** app/Application/Election/Security/SecurityEventRecorder.php, app/Models/ElectionSecurityEvent.php (implicit via create() call line 52)

---

## Governance Replay Relationship Inventory

### Relationship 3: Governance Replay Seals Evidence for Divergence Detection

**Observed Relationship:**

ReplayEvidenceEnvelope seals constitutional evidence at evaluation time with deterministic hash. ReplaySession uses sealed envelope to detect when re-evaluation produces different outcome (divergence).

**Supporting Evidence:**

- ReplayEvidenceEnvelope (line 18-71):
  - Line 29: Produces deterministic hash at creation (computeHash())
  - Line 37-52: Hash algorithm is deterministic (same input → same hash)
  - Line 24: Stores compatibility version, frozen timestamp, election/voter identifiers
  - Invariant (line 12-17): Evidence is frozen, hash is verifiable, same evidence → same hash
- ReplaySession (line 21-82):
  - Line 30-39: Constructor seals evidence
  - Line 60-79: recordAssertion() binds expected outcome to sealed evidence
  - Line 28: State transitions track replay lifecycle
- ReplayDivergenceDetected event:
  - Emitted when re-evaluated outcome differs from original
  - Signals constitutional integrity change
- ParticipationEligibilityEvidence (line 26):
  - Has deterministic eligibilityHash (line 26)
  - Description (line 13-15): "eligibility hash enables replay systems to detect when runtime eligibility state diverged"

**Observed Consequence:**

Governance replay can detect when the evaluation pipeline changed in a way that affects outcomes. Sealed evidence provides reproducible baseline; divergence detection reveals integrity violations.

**Confidence:** Tier 2 (Implementation Evidence) — Code shows deterministic sealing, hash verification, divergence tracking

**Files:** app/Domain/Election/Replay/ReplayEvidenceEnvelope.php, app/Domain/Election/Replay/ReplaySession.php, app/Domain/Election/Replay/Event/ReplayDivergenceDetected.php

---

### Relationship 4: Governance Replay is Currently Test-Implemented

**Observed Relationship:**

Replay classes exist in repository but are primarily used in unit tests. No operational invocations of ReplayEvidenceEnvelope or ReplaySession found in controllers or services.

**Supporting Evidence:**

- Grep for "ReplayEvidenceEnvelope\|ReplaySession" usage (conducted):
  - Results: Only in test files and class definitions
  - test/Unit/Domain/Election/Replay/ReplaySessionTest.php
  - test/Unit/Domain/Election/Replay/ReplayEvidenceEnvelopeTest.php
  - No files in app/Http/Controllers use Replay classes
  - No files in app/Services use Replay classes
- GovernanceStateReconstructionService (lines 1-18):
  - Marked as "Interface-only deferred service (AD-05)"
  - Implementation deferred to Phase 6 (requires projection infrastructure)
  - Comment: "Renamed from ConstitutionalReplayService to remove event-sourcing terminology"

**Observed Consequence:**

Governance replay infrastructure exists and is tested, but operational integration is deferred. Operational audit is fully integrated; replay is infrastructure-ready but not active.

**Confidence:** Tier 2 (Implementation Evidence) — Code shows test implementations and deferred service interface

**Files:** app/Domain/Election/Replay/*, app/Contexts/Governance/Domain/Replay/GovernanceStateReconstructionService.php

---

## Separation Evidence: Are They Coupled or Independent?

### Test 1: Code Location Separation

**Operational Audit:**
- app/Services/ElectionAuditService.php
- app/Models/ElectionAuditLog.php
- database/migrations/2026_04_22_000002_create_election_audit_logs_table.php
- app/Application/Election/Security/SecurityEventRecorder.php
- app/Models/ElectionSecurityEvent.php

**Governance Replay:**
- app/Domain/Election/Replay/ (entire directory)
- app/Contexts/Governance/Domain/Replay/GovernanceStateReconstructionService.php

**Observation:** Distinct directories, separate namespaces — no co-location.

**Tier:** Tier 2 (Implementation Evidence)

---

### Test 2: Cross-Reference Analysis

**Conducted Grep:**
- Search for "ElectionAuditLog" in Replay classes → No matches
- Search for "ReplaySession\|ReplayEvidenceEnvelope" in Audit services → No matches
- Search for "ElectionAuditService" in Replay classes → No matches
- Search for "SecurityEventRecorder" in Replay domain → No matches

**Observation:** No cross-references between operational audit and governance replay. They do not import each other.

**Tier:** Tier 2 (Implementation Evidence)

---

### Test 3: Data Model Separation

**Operational Audit Stores:**
- ElectionAuditLog table: action, old_values, new_values, user_id, ip_address, session_id
- ElectionSecurityEvent table: event_type, network_evidence, device_evidence, trust_level, policy_evaluation_sequence
- JSONL files: voter actions, events, metadata

**Governance Replay Stores:**
- ReplayEvidenceEnvelope: evidence array, compatibility version, frozen timestamp, envelope hash
- ReplaySession: session state, assertion, certification
- ReplayCertification: certification outcome

**Observation:** Different data structures, different purposes. Operational audit stores "what happened"; replay stores "sealed evidence state".

**Tier:** Tier 2 (Implementation Evidence)

---

### Test 4: Invocation Patterns

**Where ElectionAuditService is used:**
- Multiple controllers call auditService.logVoterAction()
- Integrated into operational request flow

**Where ReplaySession is used:**
- Unit tests only
- No operational invocations found
- GovernanceStateReconstructionService is interface-only (deferred)

**Observation:** Operational audit is integrated and active; replay is infrastructure but not operationally integrated.

**Tier:** Tier 2 (Implementation Evidence)

---

## Hypothesis Updates

### H18: Operational Audit and Governance Replay Participate in a Larger Verification Concern

**Previous Status:** New (discovered during Stream 4)

**Evidence Found (Tier 2):**
- ParticipationEligibilityEvidence is built during operational trust evaluation (TrustPolicyEvaluator line 62)
- Same evidence has deterministic hash designed for replay divergence detection (ParticipationEligibilityEvidence line 13-15)
- Replay infrastructure exists but is currently deferred (GovernanceStateReconstructionService marked AD-05)
- No coupling exists in current code, but artifacts are designed to support future integration
- Both systems interact with the same evidence artifacts, though through different mechanisms

**Updated Status:** **Open**

**Rationale:** Operational audit and governance replay may participate in a larger verification ecosystem while remaining distinct mechanisms. They do not currently integrate, but shared evidence artifacts suggest potential future convergence. The nature of this potential relationship is unresolved.

---

### H10: Operational Logging and Governance Replay are Unified

**Previous Status:** Open

**Previous Status:** Open

**Evidence Found (Tier 2):**
- Different code locations (app/Services/ElectionAuditService vs app/Domain/Election/Replay/)
- Different data structures (ElectionAuditLog vs ReplayEvidenceEnvelope)
- No cross-references or coupling found
- Different invocation patterns (operational vs test-only)
- Observed usage intent differs: operational audit artifacts contain accountability-oriented information; governance replay artifacts contain verification-oriented information
- Different audiences: administrators/auditors vs governance verification systems

**Updated Status:** **Weakening**

**Rationale:** Evidence for unification has weakened. Implementation separation and distinct data structures are observed. However, implementation separation does not necessarily indicate domain separation. The relationship between these concerns at the domain level remains unresolved.

---

### H11: Operational Logging and Governance Replay are Distinct Concerns

**Previous Status:** Open

**Evidence Found (Tier 2):**
- Implementation separation observed: ElectionAuditService and ReplaySession are in different namespaces with no cross-references
- Data models are distinct: operational audit tracks changes and actions; replay tracks sealed evidence state
- Observed usage intent differs: operational audit artifacts are accountability-oriented; replay artifacts are verification-oriented
- Invocation patterns are distinct: operational is integrated, replay is infrastructure-only (deferred)
- No shared invocation point found in current codebase
- ParticipationEligibilityEvidence has deterministic hash but is built during operational trust evaluation

**Updated Status:** **Strengthening**

**Rationale:** Evidence for implementation-level separation has strengthened. However, implementation separation does not determine domain separation. The mechanisms are distinct in code structure and current operational status. Whether they are truly separate concerns or participate in a larger verification system remains unresolved.

---

## Shared Evidence Finding

### The Eligibility Evidence Bridge

**Observation:**

ParticipationEligibilityEvidence is built during trust evaluation (operational context) and designed for replay use (governance context). It is the bridge between two concerns but is NOT a unification point.

**Evidence:**

- Built at: TrustPolicyEvaluator.buildEligibilityEvidence() (line 84-100)
- Used by: Returned in TrustEvaluationEnvelope (line 71)
- Designed for: Replay divergence detection (eligibilityHash enables detection)
- NOT coupled: No code path shows replay system consuming this evidence yet (deferred to Phase 6)

**Classification:** This is "separated-by-design" — evidence produced for operational use, designed to be usable by governance systems without requiring operational/governance coupling.

**Consequence:** Even though governance replay is deferred, the operational path already captures evidence (with deterministic hashes) that governance systems will need. This enables future integration without retrofitting operational code.

---

## New Discovery Debt

### D18: Why Is ParticipationEligibilityEvidence Frozen, Hashed, Deterministic, and Replay-Addressable?

**Question:** ParticipationEligibilityEvidence contains deterministic hash and is explicitly described as "replay-addressable." This is atypical for ordinary audit logging. Why does eligibility evidence require these properties?

**Why Unresolved:**
- Eligibility evidence is frozen at evaluation time with deterministic hash
- Comments describe it as designed for replay systems to detect divergence
- But operational use case (trust evaluation) alone does not require these properties
- This suggests a deeper governance concern beyond simple accountability

**Priority:** **HIGH — STRATEGIC INTEREST**

**Rationale:** This artifact may reveal underlying governance concept about what must be preserved across time boundaries. Understanding why eligibility must be deterministically sealed may be more important than understanding how to audit it.

**Recommended Discovery:** Investigate design intent of ParticipationEligibilityEvidence hashing and determinism — what guarantee does it protect?

**Source:** Stream 4 (ParticipationEligibilityEvidence.php:13-15)

---

### D19: When Will Governance Replay Be Operationally Integrated?

**Question:** GovernanceStateReconstructionService is deferred to Phase 6. What are the preconditions for operational integration of governance replay?

**Why Unresolved:** 
- Interface exists but implementation deferred
- No timeline or prerequisites documented
- Relationship to operational audit at integration time unclear

**Priority:** Medium

**Recommended Discovery:** Investigate Phase 6 plan, identify preconditions for replay integration

**Source:** Stream 4 (GovernanceStateReconstructionService.php comment)

---

### D20: Does SecurityEventRecorder Data Support Replay?

**Question:** SecurityEventRecorder logs trust evaluation details (policy outcomes, overlay observations). Are these logs intended to support governance replay, or purely operational observability?

**Why Unresolved:**
- SecurityEventRecorder builds observational data
- No direct coupling to ReplayEvidenceEnvelope
- Purpose is not explicitly documented as supporting replay

**Priority:** Medium

**Recommended Discovery:** Investigate SecurityEventRecorder design intent — is it pre-computing data for future replay integration?

**Source:** Stream 4 (SecurityEventRecorder.php, ReplayEvidenceEnvelope.php)

---

### D21: What Determines Replay-Readiness of Evidence?

**Question:** ParticipationEligibilityEvidence has deterministic hash; other evidence (network, device, attestation) may not. What makes evidence "replay-addressable"?

**Why Unresolved:**
- No explicit criteria found for replay-readiness
- Some evidence has hashes, some does not
- Unclear whether all evidence must be replay-ready or only subset

**Priority:** Medium

**Recommended Discovery:** Investigate TrustEvaluationEnvelope construction to understand evidence eligibility for replay

**Source:** Stream 4 (ParticipationEligibilityEvidence.php, TrustPolicyEvaluator.php)

---

## Stream 4 Completion Status

**✅ Complete**

Deliverables Produced:

✅ Audit Artifacts Inventory (7 artifacts documented: 3 operational, 3 governance, 1 shared)

✅ Audit Interaction Matrix (5 interactions documented)

✅ Operational Audit Relationship Inventory (2 relationships with evidence)

✅ Governance Replay Relationship Inventory (4 relationships with evidence)

✅ Separation Evidence (4 tests: location, cross-reference, data model, invocation)

✅ Hypothesis Updates (H10 weakening, H11 strengthening, H18 new)

✅ Discovery Debt (4 new items logged: D18-D21)

---

## Summary of Stream 4 Findings

**Observed Characteristics:**

1. **Implementation Separation Observed** — Operational audit (ElectionAuditService, ElectionAuditLog, SecurityEventRecorder) and governance replay (ReplaySession, ReplayEvidenceEnvelope) are in distinct code locations with no cross-references. Domain-level separation remains unresolved.

2. **Distinct Data Models** — Operational audit stores actions and outcomes (who, what, when, where); governance replay stores sealed evidence with deterministic hashes. The structural difference is clear; the domain significance is not.

3. **Observed Usage Intent** — Operational audit artifacts contain accountability-oriented information; governance replay artifacts contain verification-oriented information. Whether these reflect true domain purposes or implementation convenience is unresolved.

4. **Different Operational Status** — Operational audit is fully integrated and active; governance replay is infrastructure-ready but operationally deferred. This is observable fact, not evidence of domain separation.

5. **Shared Evidence Design** — ParticipationEligibilityEvidence is built during operational trust evaluation and designed (via deterministic hash) to support future governance replay. This suggests potential larger verification concern, with no current coupling.

6. **No Integration Point Found** — Unlike Evidence/Trust/Governance from Streams 1-2, operational audit and governance replay do not converge at an integration point in current codebase. Whether they will integrate in future is unresolved.

---

## Observed Evidence Flow

**Operational Path (Active):**
```
Trust Evaluation (TrustPolicyEvaluator)
    ↓
Build Eligibility Evidence (with hash)
    ↓
Security Events Recorded (fire-and-forget)
    ↓
Voter Actions Logged (ElectionAuditService)
    ↓
Audit Trail Available (JSONL files + database)
```

**Governance Path (Deferred):**
```
Evidence Sealed (ReplayEvidenceEnvelope)
    ↓
Assertion Recorded (ReplaySession)
    ↓
Certification Issued (ReplayCertification)
    ↓
Divergence Detected (if needed)
    ↓
Verification Available (hash comparison)
```

**Observed Facts:**
- Replay infrastructure is deferred (Phase 6)
- Operational audit is active and integrated
- Shared evidence artifacts exist with deterministic properties
- Future relationship between operational and governance mechanisms remains unresolved

---

**Stream 4 Status: READY FOR ARB REVIEW**

