# Round 16 — Governance Evidence Extraction

**Purpose:** Extract actual governance-domain evidence from authoritative governance sources.

**Scope:** CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, NRNA Governance Meaning Layer, ADR-001, ADR-003, Constitutional Governance Engine documentation

**Status:** Evidence extraction in progress

**Date:** 2026-06-06

---

## Extraction Methodology

This document extracts observable evidence from governance sources according to these rules:

**For every extracted finding use exactly this structure:**
- **Observed Evidence:** Direct observation from source (no interpretation)
- **Source:** Document name, section, location
- **Interpretation:** Possible meaning of the evidence
- **Alternative Interpretation:** Competing explanation
- **Confidence:** Low / Medium / High (applies ONLY to observation, not interpretation)

**Critical constraint:** This document extracts evidence. It does NOT:
- Evaluate whether evidence supports/invalidates candidates
- Conclude whether Governance is a bounded context
- Determine architecture classification
- Resolve contradictions
- Infer beyond what sources state

---

## Extraction Area 1 — Domain Language

### Term: "Constitutional Governance Decision"

**Observed Evidence**

```
The system defines "Constitutional Governance Decision" as:

"A wrapper around operational governance decisions that adds constitutional layer 
by answering: 'was this decision constitutionally valid?' without modifying the 
existing kernel."

Structure:
  - govDecision: GovernanceDecision (operational result)
  - constDecision: ConstitutionalDecision (constitutional overlay)
  - Accessors for: id(), decidedAt(), winner(), legitimacy(), isConstitutionallyValid()
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Section "Domain Model", lines 141-157

**Interpretation**

Governance decisions have two layers: operational (who decided) and constitutional (whether that decision was legitimate). The distinction is explicit and structural in the domain model.

**Alternative Interpretation**

This could be implementation detail of how decisions are stored rather than a fundamental domain concept. The wrapper pattern suggests flexibility (can add/remove constitutional layer without breaking kernel).

**Confidence**

High — the domain model explicitly defines this structure with named accessors.

---

### Term: "Temporal Legitimacy"

**Observed Evidence**

```
Legitimacy is explicitly evaluated through temporal windows:

TemporalAuthorityWindow {
  validFrom: DateTimeImmutable
  validUntil: ?DateTimeImmutable (null = open-ended)
  
  stateAt(time): TemporalWindowState (PENDING | ACTIVE | EXPIRED)
  isActiveAt(): bool
  isExpiredAt(): bool
  isPendingAt(): bool
}

Legitimacy States (3 derived, 4 administrative):
  LEGITIMATE    ← validFrom ≤ t AND (validUntil = null OR t ≤ validUntil)
  PENDING       ← t < validFrom
  EXPIRED       ← validUntil != null AND t > validUntil
  SUSPENDED     ← Admin signal (not derivable from window)
  EMERGENCY     ← Admin signal
  CARETAKER     ← Admin signal
  REVOKED       ← Admin signal
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Section "Temporal Legitimacy Foundation (GEO-3.0)" and "Domain Model", lines 260-295

**Interpretation**

Legitimacy is temporal. Authority is only legitimate within its temporal window. The concept of legitimacy is distinct from permission — it is about whether authority itself is valid at a point in time.

**Alternative Interpretation**

This could be a technical implementation detail to handle term limits and expiration. The distinction between derived states (PENDING/ACTIVE/EXPIRED) and administrative states (SUSPENDED/EMERGENCY/etc.) might just be practical classification for testing, not domain semantics.

**Confidence**

High — multiple documents explicitly structure legitimacy as temporal + administrative signals.

---

### Term: "Constitutional Arbitration"

**Observed Evidence**

```
Constitutional Arbitration is the process of answering:
"Who was constitutionally allowed to make this decision at a specific moment?"

Architecture:
  Input: Context + Capability + Optional timestamp
  
  GovernanceDecisionKernel.decide(ctx, capability, at)
    → Returns GovernanceDecision (operational)
  
  ConstitutionalArbitrationPolicy.arbitrate(classification, at)
    → Returns ConstitutionalDecision (constitutional layer)
  
  Returns: ConstitutionalGovernanceDecision (wrapped)

Arbitration Trace (complete accountability):
  evaluatedNodeIds: All authorities considered
  selectedNodeId: Winner (null = no authority found)
  precedenceReason: Why this authority won
  doctrineRulesApplied: Constitutional rules invoked
  rejectionReasons: Why others were rejected
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Section "Constitutional Arbitration Engine (GEO-3.1)", lines 299-346

**Interpretation**

Constitutional arbitration is a distinct phase that wraps operational decisions with accountability. Every governance decision must be justified in terms of constitutional rules. The complete trace is mandatory.

**Alternative Interpretation**

Arbitration might be implementation detail of how the system logs decisions. The fact that it's "wrapped" suggests it could be optional or post-hoc, not integral to the decision process itself.

**Confidence**

High — Constitutional arbitration is explicit in domain model with complete trace structure and multiple supporting documents.

---

### Term: "Doctrine"

**Observed Evidence**

```
Doctrine is immutable constitutional law:

DoctrineArtifact {
  doctrineId:      string ("constitution_v1_2024")
  version:         string ("1.0")
  constitutionalScope: ConstitutionalScope (NATIONAL|REGIONAL|EMERGENCY|CARETAKER|ELECTION)
  effectiveFrom:   DateTimeImmutable
  effectiveUntil:  ?DateTimeImmutable
  doctrineRules:   string[] (sorted canonically)
  doctrineHash:    string (sha256 of canonical sort)
  provenance:      {approvedBy, approvedAt, reason, supersedes}
}

Key property: "Doctrine is constitutional law. It cannot be retroactively 
changed without breaking governance archaeology."
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Section "Doctrine-as-Code with Immutable Artifacts", lines 91-107; Section "Doctrine Foundation & Infrastructure (GEO-3.4)", lines 497-576

**Interpretation**

Doctrine is the constitutional ruleset. It is versioned, immutable, and canonically hashed. This enables historical governance replay — old decisions can be re-evaluated under their original doctrine, not current doctrine.

**Alternative Interpretation**

Doctrine might be versioning mechanism for configuration rules rather than constitutional law per se. The immutability could be technical constraint (snapshot-based replay) rather than semantic claim about governance.

**Confidence**

High — immutability is architectural constraint across GEO-3.0 through GEO-3.4; "locked in" section (lines 765-778) explicitly lists DoctrineArtifact as non-negotiable.

---

### Term: "Governance Legitimacy" (enum)

**Observed Evidence**

```
Legitimacy enum includes these explicit values (string-backed):
  LEGITIMATE = 'legitimate'
  PENDING = 'pending'
  EXPIRED = 'expired'
  SUSPENDED = 'suspended'
  EMERGENCY = 'emergency'
  CARETAKER = 'caretaker'
  REVOKED = 'revoked'

Defined in: GEO-3.4, Task 7 "Typed Constitutional Semantics"

Requirement: "Enum values MUST match legacy string constants exactly 
(snapshot backward compatibility)"
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Section "Doctrine Foundation & Infrastructure (GEO-3.4), Task 7", lines 540-557

**Interpretation**

Legitimacy is a typed domain concept. The specific values are fixed (not extensible). Backward compatibility with snapshots suggests legitimacy has been persisted historically in a specific format that cannot change.

**Alternative Interpretation**

The enum might be implementation detail of the current codebase. "Must match legacy strings" might just mean "don't break existing tests" rather than indicating constitutional importance.

**Confidence**

Medium — defined explicitly but somewhat late in the architecture (GEO-3.4). High backward compatibility requirement suggests historical persistence, but the constraint could be technical rather than domain-semantic.

---

## Extraction Area 2 — Explicit Business Rules

### Rule: "Replay Immutability (Golden Rule)"

**Observed Evidence**

```
"HISTORICAL GOVERNANCE REPLAY MUST NEVER DEPEND ON:
  ✗ Current authority graphs
  ✗ Current constitutional doctrines
  ✗ Current infrastructure state
  ✗ Live policy evaluation

Why this matters: A constitutional decision made in 2024 must replay 
identically in 2026, even if the constitution changed, directors resigned, 
emergency doctrine was declared, or the system was reimplemented."

Implementation:
All snapshots embed:
  - Historical metadata (doctrineVersion, arbitrationPolicyVersion, legitimacyPolicyVersion)
  - Stored legitimacy (not re-evaluated)
  - Stored winning authority (not re-determined)
  - Integrity hash (detects corruption)
  - Replay fingerprint (proves semantic equivalence)
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Section "Replay Immutability (Golden Rule)", lines 50-65

**Interpretation**

Governance decisions are historical artifacts. Once made, they must remain true forever. The system must be able to prove that a decision made years ago under different constitutional rules still meant what it meant then. This is a strong legal/audit requirement.

**Alternative Interpretation**

Replay immutability could be implementation pattern for caching or auditing, not a fundamental governance rule. Snapshots with versioning are common practice in event sourcing.

**Confidence**

High — labeled "Golden Rule"; mentioned first in architectural principles; entire GEO-3.2 phase dedicated to persistent replay; "locked in" section (line 768) lists it as "Change breaks governance archaeology."

---

### Rule: "Scope Mismatch Validation"

**Observed Evidence**

```
Snapshots have constitutional scope:
  NATIONAL | REGIONAL | EMERGENCY | CARETAKER | ELECTION

Scope-Aware Replay Validation:
  null scope snapshot     ← can replay under any scope
  NATIONAL scope snapshot ← can only replay under NATIONAL scope
  REGIONAL scope snapshot ← can only replay under REGIONAL scope

Rule: "Prevents cross-scope governance confusion 
       (NATIONAL decision cannot be replayed as REGIONAL decision)"
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Section "Doctrine Foundation & Infrastructure (GEO-3.4), Task 6", lines 526-538

**Interpretation**

Governance decisions are scoped to institutional jurisdiction. A decision made at national level has different validity from one made at regional level. The scope must be preserved and cannot be retroactively changed.

**Alternative Interpretation**

Scope might be technical partitioning mechanism for multi-tenant isolation. The validation could be schema-level constraint rather than governance rule.

**Confidence**

Medium-High — explicitly named as rule and includes implementation (ScopeAwareReplayValidator), but relatively late addition (GEO-3.4, Task 6).

---

### Rule: "Legitimacy Derivation Without Re-evaluation"

**Observed Evidence**

```
DefaultConstitutionalArbitrationPolicy:

Legitimacy derivation from resolution type (NOT via LegitimacyPolicy):

$legitimacy = $finalDecision->winningNode !== null
    ? GovernanceLegitimacy::LEGITIMATE   // Exception, Override, Direct, Delegated
    : GovernanceLegitimacy::EXPIRED;     // No authority found

Why: "Graph traversal already validated temporal correctness for each authority. 
Calling LegitimacyPolicy with synthetic windows would manufacture a 
predetermined answer — semantic abuse."
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Section "Constitutional Arbitration Engine (GEO-3.1)", Section "Legitimacy Derivation", lines 319-330

**Interpretation**

Legitimacy is derived from whether authority was found, not from re-evaluating temporal windows. The rule prevents "manufacturing" legitimacy through inappropriate policy invocation. Once a decision is made (authority found or not), legitimacy follows mechanically.

**Alternative Interpretation**

This could be optimization pattern to avoid redundant computations, not a semantic requirement.

**Confidence**

Medium — explicitly stated as design decision with rationale, but relatively narrow scope (specific to GEO-3.1 arbitration, not all legitimacy evaluation).

---

### Rule: "Governance Owns Policy Consequences, Trust Does Not"

**Observed Evidence**

```
From ADR-003 — Governance-Driven Revocation:

Trust Attestation Context Responsibility:
  Officer reviews evidence
    └── Decides: Verify or revoke
    └── Emits: VerificationRevokedEvent
    └── Updates: Verification status to revoked
    └── Does NOT decide: Consequences

Governance Context Responsibility:
  Receives: VerificationRevokedEvent
    ├── Evaluates: Should past votes be invalidated?
    ├── Decides: Should election be challenged?
    ├── Decides: Should audit be reopened?
    ├── Decides: What notification goes to members?
    └── Implements: Governance policy consequences

What Revocation Does NOT Automatically Do:
  ❌ Invalidate past votes
  ❌ Change election results
  ❌ Reopen audits
  ❌ Void certifications
  ❌ Affect other voters
```

**Source**

ADR-003: Governance-Driven Revocation, Section "Decision", lines 40-70

**Interpretation**

There is an explicit boundary between Trust domain (detects problems) and Governance domain (decides consequences). Revocation is a data event; its impact is governance policy. The two domains must not merge.

**Alternative Interpretation**

This boundary might be recommended architecture pattern, not a proven rule. The ADR states these are "separate concerns" but implementation guidance shows the separation is incomplete ("Governance Context (missing)").

**Confidence**

High — explicitly stated as architectural decision with rationale; includes comparison of "Bad Model" vs "Good Model"; includes real-world scenario demonstrating the distinction.

---

### Rule: "Constitutional Authority vs Permission Authority"

**Observed Evidence**

```
ADR-001: Constitutional Capability Sovereignty

"Backend is the sole authority — All permission checks originate 
from the backend capability resolver"

"Capability authority is centralized and non-negotiable:
1. Backend is the sole authority
2. Frontend is passive — reads capabilities, never infers
3. Capabilities are immutable snapshots
4. Composable is a pure read adapter — never augments"

Critical Invariants:
  - Frontend NEVER checks `if (user.role === 'chief')`
  - Frontend NEVER evaluates `if (election.administration_completed)`
  - Frontend NEVER runs capability rules code
```

**Source**

ADR-001: Constitutional Capability Sovereignty, Section "Decision", lines 21-52

**Interpretation**

Authority for permission decisions is centralized in backend. The frontend cannot make independent authority judgments. Authority decisions are computational (backend resolves) not deferred (frontend infers). This is architectural law, not suggestion.

**Alternative Interpretation**

ADR-001 might be design pattern for this specific system rather than a universal governance rule. The "sole authority" could mean "backend coordinates with" rather than "frontend absolutely cannot."

**Confidence**

High — explicitly labeled as non-negotiable ("Capability authority is centralized and non-negotiable"); includes both positive rules and negative invariants; section "Consequences" explicitly discusses tradeoffs.

---

## Extraction Area 3 — Decision Ownership

### Decision: "Who may create governance decisions?"

**Observed Evidence**

```
From NRNA Governance Meaning Layer — Phase 1 — Governance Decision Persistence:

"The existing governance_decisions table never gets written to. 
Fix InternalCreateCommittee to persist every committee creation decision."

Task 1.3 — Wire GovernanceReplayService into InternalCreateCommittee:

Code change required:
  $governanceDecision = $this->constitutionalKernel->adjudicate(
    capabilityType: COMMITTEE_CREATION,
    committeeId: $committee->getId(),
    tenantId: $command->tenantId,
  );
  $this->governanceReplayService->persist($governanceDecision);

Implication: ConstitutionalArbitrationKernel creates decisions; 
InternalCreateCommittee (use case) triggers persistence.
```

**Source**

NRNA Governance Meaning Layer, Section "Phase 1 — Wire Governance Decision Journal", Task 1.3, lines 264-336

**Interpretation**

Committee creation use cases flow through ConstitutionalArbitrationKernel, which determines whether a decision is constitutional. Creation of governance decisions is implicit in operational decisions (committee creation → governance decision). The kernel is the decision authority.

**Alternative Interpretation**

ConstitutionalArbitrationKernel might just be one of several paths to creating governance decisions. The focus on committee creation could be a starting point, not the only decision type.

**Confidence**

High — explicit in implementation plan and code; multiple use case mentions (CreateCommitteeUseCase, InternalCreateCommittee).

---

### Decision: "Who may approve governance decisions?"

**Observed Evidence**

```
From NRNA Governance Meaning Layer — Phase 4 — Approval Workflow:

Two explicit use cases:
  1. ApproveCommitteeFormation.php
  2. RejectCommitteeFormation.php

Approval request model:
  approval_type: string (COMMITTEE_FORMATION | DISSOLUTION | SUSPENSION)
  approver_committee_id: ? Committee
  status: enum (pending | approved | rejected)

Flow:
  Request created with status = PENDING
  ApproveCommitteeFormation invoked:
    ├── Validates: status !== APPROVED or REJECTED
    ├── Updates: status = APPROVED
    ├── Records: resolved_by_user_id, resolved_at, notes
    └── Throws: if already resolved

Implication: A committee (approver_committee_id) has authority to approve. 
Approval is explicit and recorded.
```

**Source**

NRNA Governance Meaning Layer, Section "Phase 4 — Approval Workflow Engine", Task 4.2, lines 992-1247

**Interpretation**

Governance decisions require approval from specific committees (not individual users). Approval is a formal action with complete audit trail. Committee-to-committee authority relationship is explicit in the model.

**Alternative Interpretation**

Approval workflow might be operational procedure, not constitutional authority. Committee-based approval could be implementation convenience rather than governance rule.

**Confidence**

Medium-High — implemented as domain entity with explicit state machine (PENDING → APPROVED/REJECTED), but approval is recent addition (Phase 4); reason for approval model not explained in governance sources (only in implementation plan).

---

### Decision: "Who may delegate authority?"

**Observed Evidence**

```
From CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Domain Model:

GovernanceLineageGraph {
  nodes:  Map[decisionId → GovernanceLineageNode]
  edges:  List[GovernanceLineageEdge]
}

GovernanceLineageEdge {
  fromNodeId:   string
  toNodeId:     string
  edgeReason:   string ("successor"|"amendment"|"emergency_fork")
}

From NRNA Governance Meaning Layer — Phase 3 — GeoAuthority Graph:

DelegationEdge {
  from_node_id: char(26)
  to_node_id: char(26)
  delegation_type: string (AUTHORITY | OVERRIDE | TEMPORARY)
  weight: smallint (80=AUTHORITY, 90=OVERRIDE, 70=TEMPORARY)
  valid_from: timestamp
  valid_to: ?timestamp
}

Implication: Authority is delegated via edges. Edges have type and weight. 
Delegation has temporal validity.
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Section "Domain Model", lines 231-256; NRNA Governance Meaning Layer, Phase 3, Task 3.1, lines 576-649

**Interpretation**

Delegation is explicit and modeled. Authority flows through delegation edges. Different delegation types have different weights (AUTHORITY=80, OVERRIDE=90, TEMPORARY=70). Delegation can expire (valid_to). Delegation is not implicit; it requires explicit edge.

**Alternative Interpretation**

Delegation model could be graph implementation detail for technical reasoning about authority relationships, not a domain rule about who can delegate to whom.

**Confidence**

Medium — model is explicit, but sources don't describe WHO creates edges or WHO has authority to delegate. The model exists; the delegation authority rules are absent.

---

## Extraction Area 4 — Authority Relationships

### Relationship: "Authority has temporal validity"

**Observed Evidence**

```
Authority windows are explicit:

TemporalAuthorityWindow {
  validFrom: DateTimeImmutable
  validUntil: ?DateTimeImmutable (null = open-ended)
}

Legitimacy test: "validFrom ≤ t AND (validUntil = null OR t ≤ validUntil)"

Administrative signals add:
  SUSPENDED  ← Temporarily revoked by order
  EMERGENCY  ← Granted temporary extraordinary powers
  CARETAKER  ← Interim/transitional authority

GEO-3.4 Task 6 explicitly implements:
  ScopeAwareReplayValidator.validate(snapshot, replayScope)
    throws ScopeMismatchException
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Sections "Temporal Legitimacy Foundation" (lines 260-295), "Domain Model — Temporal Authority Windows" (lines 184-195), "Scope-Aware Replay Validation" (lines 526-538)

**Interpretation**

Authority is temporal, not eternal. Every authority has a window of validity. Authority can be granted temporarily (EMERGENCY, TEMPORARY delegation type). Authority can be suspended administratively. Scope adds dimension — authority at national level ≠ authority at regional level.

**Alternative Interpretation**

Temporal validity might be implementation requirement for term limits and permissions, not a fundamental governance concept. Different scopes could be technical multi-tenancy rather than constitutional boundary.

**Confidence**

High — temporal authority is foundational concept (GEO-3.0, first phase); explicitly implemented across multiple value objects and services (TemporalAuthorityWindow, LegitimacyEvaluator, ScopeAwareReplayValidator).

---

### Relationship: "Authority is verified before exercise"

**Observed Evidence**

```
From ADR-003: Governance-Driven Revocation

"Officer reviews evidence
  └── Decides: Verify or revoke"

"Trust Attestation Context Responsibility:
  Officer reviews evidence
    └── Decides: Verify or revoke
    └── Emits: VerificationRevokedEvent"

Verification precedes authority:
  Time 1 (Day 1):
    Officer verifies Voter A
      └── Verification: Officer Verified ✓
    Voter A votes

  Time 2 (Day 5):
    Officer discovers duplicate
      └── Officer revokes verification

Implication: Authority to vote requires verification. 
Verification is separate from authorization.
```

**Source**

ADR-003: Governance-Driven Revocation, Section "Decision", lines 40-50; Section "Real-World Example", lines 104-130

**Interpretation**

There is a verification phase that precedes (or accompanies) authority. An officer explicitly verifies a voter before voting authority exists. Revocation of verification is different from removal of voting rights — it's data change, not authority change.

**Alternative Interpretation**

Verification could be operational data collection, not governance concept. The example shows verification happening, but doesn't prove it's required.

**Confidence**

Medium-High — explicitly described in ADR as governance boundary, but ADR-003 itself notes this boundary is "implicit" currently, not fully implemented: "Currently, this separation is implicit. Revocation is recorded but consequences are undefined."

---

### Relationship: "Authority is recognized through legitimacy check"

**Observed Evidence**

```
Constitutional Arbitration process:

Input: Context (who is claiming authority?)
  ├─ Capability type (COMMITTEE_CREATION, etc.)
  ├─ Optional timestamp
  └─ Current authority graph

GovernanceDecisionKernel.decide() →
  Graph traversal finds winning authority

ConstitutionalArbitrationPolicy.arbitrate() →
  Returns: winner (authority recognized) or null (no authority found)

Result: ConstitutionalGovernanceDecision {
  winner: ?JurisdictionNode
  legitimacy: GovernanceLegitimacy (LEGITIMATE | EXPIRED | etc.)
  trace: ConstitutionalArbitrationTrace
}

The trace includes:
  evaluatedNodeIds: All authorities considered
  selectedNodeId: Winner (null = no authority)
  precedenceReason: Why this authority won
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Section "Constitutional Arbitration Engine (GEO-3.1)", lines 299-346

**Interpretation**

Authority is recognized through a computational process that evaluates a graph of possible authorities and selects a winner based on constitutional rules. The process is complete (all nodes evaluated), traceable (full precedence reasoning), and produces a specific decision (this authority wins, or no authority found).

**Alternative Interpretation**

Arbitration might be post-hoc logging mechanism rather than determinative process. The "winning" authority might be predetermined by the request, with arbitration just recording which rule applies.

**Confidence**

High — process is core to GEO-3.1 (22 tests); complete trace is mandatory; "locked in" architectural constraint (line 775) requires "Arbitration trace mandatory — Decision explainability."

---

## Extraction Area 5 — Verification and Trust

### Concept: "Verification is distinct from authority"

**Observed Evidence**

```
ADR-003 explicitly separates:

Trust Attestation Context:
  ├─ Decides: Is voter verified?
  └─ Emits: VerificationRevokedEvent

Governance Context:
  ├─ Receives: VerificationRevokedEvent
  ├─ Decides: What is the consequence?
  └─ Acts: Challenge election? Recount? Invalidate?

The separation is explicit:
"Verification Context detects the problem. 
 Governance Context decides impact."

Current implementation note:
"Trust Attestation Context (no change):
  VoterVerification.revoke(officer_id)
    ├── revoked_by = officer_id
    ├── revoked_at = now()
    └── emit VerificationRevokedEvent

Governance Context (missing):
  class VerificationRevokedHandler { ... }"
```

**Source**

ADR-003: Governance-Driven Revocation, Section "Decision", lines 40-70; Section "Implementation Guidance", lines 161-209

**Interpretation**

Verification is a trust operation (officer attests to fact). Its governance consequences are separate. Revocation is a trust event; its implications are governance decisions. The boundary is architectural (separate contexts) not operational (same code).

**Alternative Interpretation**

The boundary described in ADR-003 might be aspirational. The note that Governance Context handling is "missing" suggests the separation is not yet implemented as rule, only as design principle.

**Confidence**

High for principle (architectural decision explicitly states boundary); Medium for implementation (currently "implicit", handler "missing").

---

### Potential Relationship: "Between Legitimacy and Trust"

**Observed Evidence**

```
From multiple sources:

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md defines:

GovernanceLegitimacy enum:
  LEGITIMATE ← authority valid at decision time
  EXPIRED    ← authority no longer valid
  SUSPENDED  ← temporarily revoked
  REVOKED    ← permanently voided

Temporal Legitimacy Foundation section:
"A constitutional decision made in 2024 must replay identically in 2026, 
 even if the constitution changed"

Trust infrastructure (separate ADR):
  CONSTITUTIONAL_TRUST_INFRASTRUCTURE.md
  └── Describes: Trust and verification mechanisms
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Section "Temporal Legitimacy Foundation (GEO-3.0)", lines 260-295; Section "Legitimacy States", lines 272-282

**Interpretation**

Legitimacy and trust appear in proximity in the sources. A LEGITIMATE authority state might be correlated with trustworthiness. An EXPIRED authority state might correspond to loss of trust. However, the sources do not explicitly state the causal or semantic relationship.

**Alternative Interpretation A**

Legitimacy is the basis of trust. LEGITIMATE authority is trustworthy by definition.

**Alternative Interpretation B**

Legitimacy is a status label. Trust comes from other sources (constitutional doctrine, verification, officer attestation). Legitimacy might be consequence of trust, not cause.

**Alternative Interpretation C**

Legitimacy and trust are independent concepts that happen to coexist. Legitimacy addresses temporal validity; trust addresses reliability.

**Observation Status**

Open. The relationship exists in the sources but requires investigation to determine whether it is causal, correlative, or independent.

---

## Extraction Area 6 — Contradictions and Ambiguities

### Contradiction: "Authority creation vs. Authority delegation"

**Observed Evidence**

```
Sources describe two different authority flows:

Flow 1: Authority from Governance Lineage Graph
  Input: JurisdictionNode + DelegationEdge
  Output: Authority hierarchy
  Source: GovernanceLineageGraph used in replay (GEO-3.4)

Flow 2: Authority from approval workflow
  Input: Committee + ApprovalRequest
  Output: Approval decision
  Source: ApproveCommitteeFormation use case (Phase 4)

Question: Are these the same authority mechanism or different?
- Lineage graph has temporal edges (valid_from, valid_to)
- Approval workflow has approval status (pending, approved, rejected)
- Both can result in authority, but mechanisms differ

Missing: How do these two authority flows interact?
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Section "Doctrine Foundation & Infrastructure (GEO-3.4), Task 11", lines 597-607; NRNA Governance Meaning Layer, Phase 3 and Phase 4

**Interpretation**

Two potential authority mechanisms are described but their relationship is undefined. Are approvals recorded in the lineage graph? Do lineage edges require approval first? Can authority exist without approval?

**Alternative Interpretation**

These might be sequential stages: lineage graph represents the formal authority structure; approvals are workflow for creating new nodes in that graph.

**Observation Confidence**

High — both mechanisms explicitly exist in different documents

---

### Contradiction: "Legitimacy derivation vs. Legitimacy Policy"

**Observed Evidence**

```
GEO-3.1 specifies:

"Legitimacy derivation from resolution type (NOT via LegitimacyPolicy)"

$legitimacy = $finalDecision->winningNode !== null
    ? GovernanceLegitimacy::LEGITIMATE
    : GovernanceLegitimacy::EXPIRED;

Rationale: "Calling LegitimacyPolicy with synthetic windows would 
            manufacture a predetermined answer — semantic abuse."

BUT GEO-3.0 explicitly defines:

LegitimacyEvaluator
  └─ Maps temporal window to legitimacy state
  └─ Uses LegitimacyPolicy abstraction (defined as interface)

Question: Why is LegitimacyPolicy defined if it's not used in arbitration?

AND GEO-3.4 mentions:

"LegitimacyPolicy abstraction"
  └─ "Enables constitutional doctrine variation"
  └─ "Enables future policy changes"

This suggests LegitimacyPolicy IS meant to be used, but GEO-3.1 says it shouldn't be.
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md, Section "Constitutional Arbitration Engine (GEO-3.1)", lines 319-330; Section "Temporal Legitimacy Foundation (GEO-3.0)", lines 290-295; Section "Critical Architectural Constraints", line 777

**Interpretation**

There may be conflict between GEO-3.0 (LegitimacyEvaluator uses LegitimacyPolicy) and GEO-3.1 (arbitration does NOT use LegitimacyPolicy). This could indicate evolutionary refinement or unresolved design tension.

**Alternative Interpretation**

These might not be in conflict — LegitimacyEvaluator and LegitimacyPolicy might be for different use cases (temporal window evaluation vs. arbitration). The "do NOT call in arbitration" rule might apply specifically to arbitration, not universally.

**Confidence**

Medium — apparent contradiction between phases; unclear whether resolved or still open.

---

### Gap: "Who creates initial authority? (No source found)"

**Observed Evidence**

```
Sources describe:
  ✓ How authority is delegated (DelegationEdge)
  ✓ How authority is revoked (VerificationRevokedEvent)
  ✓ How authority is verified (Officer attestation)
  ✗ How initial authority is created (no description)

Approval workflow describes approval of committee formation, but doesn't explain:
  - Who is the initial approver?
  - Who delegates the first authority to approve?
  - What creates the root of the authority graph?

Constitutional Arbitration describes finding authority in graph:
  - Graph traversal finds winning node
  - BUT how are nodes initially added to graph?

Lineage graph describes edges:
  - edgeReason: "successor"|"amendment"|"emergency_fork"
  - But what creates the first node?
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md (checked all sections); ADR-003 (checked all sections); NRNA Governance Meaning Layer (checked all phases)

**Interpretation**

Initial authority creation (bootstrapping) is not addressed in governance sources. Either it's handled elsewhere (in operational/election domain) or it's a missing design element.

**Alternative Interpretation**

Initial authority might be implicit (system admin creates it in code, not through governance process). Or initial authority might be delegation from a constitutional authority (national constitution) that exists outside the computational system.

**Confidence**

High for gap (thoroughly searched, found no rule); Medium-High for interpretation (could be multiple explanations).

---

### Gap: "What makes a governance decision binding? (Partial answer only)"

**Observed Evidence**

```
Sources state:
  ✓ Governance decisions are created through arbitration
  ✓ They are persisted as snapshots
  ✓ They are replayed deterministically
  ✓ They have legitimacy status (LEGITIMATE | EXPIRED | etc.)

Missing:
  ✗ What enforces a governance decision?
  ✗ Who is required to accept the decision?
  ✗ What happens if someone ignores it?
  ✗ How is violation of governance decision detected?

Related concept from ADR-001:
  "Backend is sole authority for capability decisions"
  BUT: Does this apply to all governance decisions or only capability decisions?

Example ambiguity:
  If arbitration says "Committee X cannot be created" (authority denied)
  Who prevents the creation?
  What if the creation happens anyway?
  How is the violation detected/logged?
```

**Source**

CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md (all sections on decisions); ADR-001 (lines 21-52)

**Interpretation**

Governance decisions are authored and recorded, but enforcement mechanism is undefined in governance sources. This could be a domain boundary (enforcement belongs to operations, not governance) or a gap.

**Alternative Interpretation**

Enforcement might be implicit — if arbitration denies authority, the operation simply cannot proceed because the authority lookup fails. The system is designed so violation is impossible by construction.

**Confidence**

High for gap (thoroughly searched); Medium for whether it's a real gap or intended design (enforcement by construction).

---

## Summary: Evidence Extracted

### Terms Discovered

| Term | Source | Explicit Definition |
|------|--------|------------------|
| Constitutional Governance Decision | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md | Yes — Domain Model section |
| Temporal Legitimacy | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md | Yes — GEO-3.0 section |
| Constitutional Arbitration | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md | Yes — GEO-3.1 section |
| Doctrine | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md | Yes — Multiple sections |
| Governance Legitimacy (enum) | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md | Yes — GEO-3.4 Task 7 |
| Approval Request | NRNA Governance Meaning Layer | Yes — Phase 4 section |
| Delegation Edge | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md | Yes — Domain Model section |
| Authority Window | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md | Yes — GEO-3.0 section |
| Verification | ADR-003 | Yes — Multiple sections |

---

### Rules Discovered

| Rule | Source | Explicit |
|------|--------|----------|
| Replay Immutability (Golden Rule) | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md | Yes — Labeled Golden Rule |
| Scope Mismatch Validation | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md | Yes — GEO-3.4 Task 6 |
| Legitimacy Without Re-evaluation | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md | Yes — GEO-3.1 section |
| Governance Owns Consequences | ADR-003 | Yes — Decision section |
| Backend Sole Authority | ADR-001 | Yes — Decision section |

---

### Decision Ownership Discovered

| Decision Type | Who Decides | Source | Explicit |
|---------------|------------|--------|----------|
| Committee Creation | ConstitutionalArbitrationKernel | NRNA Governance Meaning Layer | Yes |
| Committee Formation Approval | ApproverCommittee | NRNA Governance Meaning Layer | Yes |
| Authority Delegation | ? | CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md | No — model exists, ownership unclear |
| Initial Authority Creation | ? | (No source found) | No |

---

### Authority Relationships Discovered

| Relationship | Explicit | Evidence Quality |
|--------------|----------|------------------|
| Authority has temporal validity | Yes | High — multiple implementations |
| Authority verified before exercise | Yes | Medium-High — ADR-003 describes, notes implementation incomplete |
| Authority recognized through arbitration | Yes | High — core to GEO-3.1 |
| Legitimacy-Trust relationship | Implicit | Unresolved — concepts coexist, relationship undefined |

---

### Contradictions & Gaps Discovered

| Item | Type | Investigation Status |
|------|------|-------------------|
| Lineage graph vs. Approval workflow | Contradiction | Requires investigation — two authority mechanisms, relationship undefined |
| LegitimacyPolicy use vs. non-use | Contradiction | Requires investigation — GEO-3.0 defines it; GEO-3.1 forbids using it in arbitration |
| Initial authority creation | Gap | Unresolved — no source describes bootstrapping authority |
| Governance decision enforcement | Gap | Unresolved — decisions recorded, but enforcement mechanism undefined |
| Delegation authority rules | Gap | Unresolved — who can delegate? to whom? under what conditions? |

---

## Next Step

This extraction has identified:

**Strong evidence bases:**
- Constitutional Governance Engine (comprehensive, 233 tests, GEO-3.0 through GEO-3.4)
- Approval Workflow (Phase 4, implementation plan detailed)
- Temporal Legitimacy (GEO-3.0 foundational)
- Arbitration Process (GEO-3.1 central)

**Weak evidence bases:**
- Initial authority creation (no sources)
- Governance decision enforcement (undefined)
- Delegation authority rules (no explicit ownership)
- Relationship between lineage and approval mechanisms (unclear)

The evidence has been extracted. It does NOT yet answer:
- Is Governance a bounded context?
- Should Governance be separate from Authority?
- Does this evidence strengthen or weaken the candidate signal?

Those are reassessment questions. This document extracts only.

---

## Evidence Relevance Matrix

**Extracted evidence may be relevant to these candidate contexts. This is traceability only. No conclusions are drawn.**

### Governance & Authority Candidate Signal

**Potentially relevant evidence:**
- Constitutional Governance Decision (term, arbitration structure)
- Temporal Legitimacy (decision validity window)
- Constitutional Arbitration (who decides authority)
- Doctrine (constitutional ruleset)
- Approval Request (committee approval workflow)
- Delegation Edge (authority delegation model)
- Authority Window (temporal validity)

**Contradictions/Gaps in this context:**
- Initial authority creation (undefined)
- Governance decision enforcement (undefined)
- Delegation authority rules (undefined)
- Lineage vs. Approval mechanism relationship (unclear)

---

### Audit Candidate Signal

**Potentially relevant evidence:**
- Replay Immutability (replay audit trail)
- Arbitration Trace (complete decision trace)
- Snapshot Integrity Hash (corruption detection)

**Contradictions/Gaps in this context:**
- None directly identified; audit evidence is partial

---

### Election Administration Candidate Signal

**Potentially relevant evidence:**
- Approval Request (workflow for committee formation)
- Scope Mismatch Validation (scope-aware decisions)
- Governance decision enforcement (partially relevant if authority enforcement is administrative)

**Contradictions/Gaps in this context:**
- Enforcement mechanism undefined (relevant if admin owns enforcement)

---

### Verification Concern

**Potentially relevant evidence:**
- Governance Owns Consequences (separation of Trust from Governance)
- Verification Revocation (governance decides impact, not trust)

**Contradictions/Gaps in this context:**
- Implementation of governance consequence handling (missing)

---

## Evidence Type Assessment (Critical Warning)

**Most extracted evidence originates from:**

- Architecture Decision Records (ADR-001, ADR-003)
- Architecture documentation (Constitutional Governance Architecture Handbook)
- Implementation guides (Governance Meaning Layer, Governance Engine documentation)
- Implementation planning documents (Phase 1-6 plans)

**These are solution-space artifacts.**

They describe how the system was designed and built to implement governance concepts.

**Not yet examined:**

Authoritative business artifacts such as:
- Election bylaws or organizational bylaws
- Election regulations (if external)
- Operating procedures for governance committees
- Decision-making policies from organizational archives
- Meeting minutes or governance records
- Member or stakeholder governance documents

**Methodological implication:**

This extraction may reflect **architectural intent** more strongly than **business reality**.

A governance boundary was designed into the system. That design is well-documented. But we have not yet examined whether governance is actually the business domain's center of gravity, or whether governance is *implemented as* a supporting structure for voting/registration/tallying which are the actual core domains.

**This is a critical distinction for DDD.**

If governance is:
- **Problem-space central** → Should be a bounded context
- **Solution-space central** → Might be implementation detail; core domain lies elsewhere

The current evidence base cannot distinguish between these possibilities.

---

---

## Final Assessment

```
Methodology Quality:           9/10
Evidence Quality:              8/10
Solution-Space Clarity:        High
Problem-Space Coverage:        Incomplete
DDD Readiness:                 7.5/10
```

---

## Approval Decision

✅ **Approved as Governance Evidence Extraction**

This document successfully:
- Extracts from actual authoritative sources
- Separates observation from interpretation
- Preserves genuine ambiguities
- Documents gaps and contradictions honestly
- Avoids premature architectural conclusions
- Distinguishes solution-space from problem-space evidence

❌ **Not Sufficient for Bounded Context Discovery**

Cannot begin bounded context design based on solution-space evidence alone.

❌ **Not Sufficient for Candidate Reassessment**

The Governance & Authority candidate signal remains as stated in Round 16 Step 1A: **Weak Candidate Signal**.

Documentation abundance does not equal domain importance.

---

## Critical Methodological Principle

```
Documentation Volume ≠ Domain Importance

Banking System Example:
  Security documentation = 1000 pages
  Payments documentation = 100 pages
  Core domain = Payments (not Security)
```

This extraction proves:

```
Governance has rich solution-space documentation.
```

It does NOT prove:

```
Governance is the business core domain.
```

These are completely different conclusions.

---

## What Must Happen Before Reassessment

Equivalent evidence discovery must be attempted for:

1. **Voting context** — Find authoritative sources (bylaws, procedures, business rules)
2. **Voter Registration context** — Find authoritative sources
3. **Vote Tallying context** — Find authoritative sources

Then either:

**Option A:** Equivalent evidence is found for Voting/Registration/Tallying
→ Proceed to balanced candidate reassessment

**Option B:** It is confirmed that equivalent evidence does not exist
→ Investigate why architecture is governance-heavy in solution-space but voting-focused in problem-space
→ Then proceed to reassessment with this asymmetry explicitly acknowledged

---

## Warning: Do Not Claim Step 1A Signal Was Wrong

Based on this extraction, do NOT conclude:

```
"The weak Governance & Authority signal from Step 1A was incorrect."
```

The extraction shows:

```
Governance was heavily engineered and documented.
```

It does NOT show:

```
Governance is the business domain's center of gravity.
```

Engineering effort and domain importance are orthogonal properties.

---

**STATUS: Governance Evidence Extraction - APPROVED**

**READINESS FOR CANDIDATE REASSESSMENT: Not Yet**
