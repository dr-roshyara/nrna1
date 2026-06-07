# Round 21 — Context Mapping

**Date:** 2026-06-07

**Phase:** Strategic DDD Context Mapping (Step 3)

**Status:** Complete — Awaiting ARB Review

---

## 1. Scope

**Authorized Candidate Contexts (7):**
1. Trust Attestation
2. Eligibility
3. Authorization
4. Constitutional Governance / Lifecycle
5. Audit (Operational)
6. Voting (provisional boundary)
7. Results/Tallying (provisional boundary — merger consideration)

**Not mapped at this stage:** Governance Replay (operational status unresolved), Arbitration/Legitimacy (boundary unresolved), Challenge/Dispute (distributed capability).

**Governance Constraint:** Voting and Results/Tallying boundaries are provisional pending D42B and D39. No final boundaries are locked.

---

## 2. Relationship Inventory

### R1: Trust Attestation → Eligibility

| Dimension | Assessment |
|-----------|-----------|
| **Type** | Upstream/Downstream |
| **Direction** | Trust Attestation (upstream) → Eligibility (downstream) |
| **Dependency** | Eligibility requires Verified identity as prerequisite (isVerified = true before eligibility can be evaluated) |
| **Shared Concepts** | Participant identity, verification status |
| **Data Ownership** | Trust Attestation owns verification status; Eligibility reads it |
| **Published Language** | IdentityAttested event, VerificationRevokedEvent |
| **Potential Integration** | Event-driven: Trust Attestation emits events; Eligibility subscribes and recalculates eligibility |
| **Evidence** | ADR-002 (Verified → Eligible → Authorized chain), TRUST_CHAIN.md |

**Confidence:** HIGH — ADR-002 explicitly defines this dependency.

---

### R2: Eligibility → Authorization

| Dimension | Assessment |
|-----------|-----------|
| **Type** | Upstream/Downstream |
| **Direction** | Eligibility (upstream) → Authorization (downstream) |
| **Dependency** | Authorization requires Eligible as prerequisite (isEligible must be true before authorization can be granted) |
| **Shared Concepts** | Participant, process type (voting, candidacy), scope |
| **Data Ownership** | Eligibility owns eligibility status; Authorization reads it |
| **Published Language** | EligibilityStatusChanged event (if eligibility recalculated) |
| **Potential Integration** | Authorization formula: CanPerformAction = Verified AND Eligible AND Permission |
| **Evidence** | ADR-002 (authorization formula), TRUST_CHAIN.md lines 230-238 |

**Confidence:** HIGH — ADR-002 and TRUST_CHAIN explicitly document this dependency.

---

### R3: Constitutional Governance → Authorization

| Dimension | Assessment |
|-----------|-----------|
| **Type** | Upstream/Downstream |
| **Direction** | Constitutional Governance (upstream) → Authorization (downstream) |
| **Dependency** | Authorization requires lifecycle state to determine which actions are valid (canOpenVoting requires voting_active state) |
| **Shared Concepts** | Election lifecycle state, action definitions, preconditions |
| **Data Ownership** | Constitutional Governance owns lifecycle state and transition rules; Authorization reads them for capability resolution |
| **Published Language** | TransitionCompleted event, lifecycle snapshot |
| **Potential Integration** | Capability resolution reads current lifecycle state from Constitutional Governance |
| **Evidence** | ADR-001 (Constitutional Capability Sovereignty), ADR-004 (Deterministic Resolver), ElectionConstitution.php, ConstitutionalTransitionGuard.php |

**Confidence:** HIGH — Multiple ADRs document the dependency of capability resolution on lifecycle state.

---

### R4: Constitutional Governance → Voting

| Dimension | Assessment |
|-----------|-----------|
| **Type** | Upstream/Downstream |
| **Direction** | Constitutional Governance (upstream) → Voting (downstream) |
| **Dependency** | Voting can only accept votes when lifecycle state is 'voting_active'. Voting is gated by lifecycle progression. |
| **Shared Concepts** | Election, voting window (start/end dates), lifecycle state |
| **Data Ownership** | Constitutional Governance owns lifecycle state and voting window constraints; Voting reads them |
| **Published Language** | StateTransition event (e.g., VotingActivated) |
| **Potential Integration** | Voting controller checks lifecycle state before accepting submissions |
| **Evidence** | Stream 3, ElectionConstitution.RULES (open_voting precondition: voting_window_defined), ConstitutionalTransitionGuard, Stream 5 |

**Confidence:** HIGH — Dependencies are clear from lifecycle state machine and transition guard.

---

### R5: Authorization → Voting

| Dimension | Assessment |
|-----------|-----------|
| **Type** | Upstream/Downstream |
| **Direction** | Authorization (upstream) → Voting (downstream) |
| **Dependency** | Voter must be authorized (verified + eligible + permission) before vote is accepted |
| **Shared Concepts** | Participant, voter role, election scope |
| **Data Ownership** | Authorization owns capability decision; Voting reads it |
| **Published Language** | CapabilitySnapshot (point-in-time allowed/denied actions) |
| **Potential Integration** | Voting controller calls capability resolver before accepting vote submission; vote is not recorded unless authorized |
| **Evidence** | Stream 2, Stream 3, ConstitutionalTransitionGuard, TrustPolicyEvaluator, VoteController authorization checks |

**Confidence:** HIGH — Authorization gate is well-documented implementation pattern.

---

### R6: Voting → Results/Tallying

| Dimension | Assessment |
|-----------|-----------|
| **Type** | Synchronous Coupling (provisional — may be merger) |
| **Direction** | Voting (upstream) → Results/Tallying (downstream) |
| **Dependency** | Results are a derived projection of Vote data. Result rows are created synchronously on vote save (createResultsFromCandidates). Results can be regenerated from Vote data at any time (syncResults). |
| **Shared Concepts** | Candidate selection, post, abstention, position_order |
| **Data Ownership** | Voting owns the authoritative data (JSON candidate columns). Results/Tallying owns only derived data. |
| **Published Language** | VoteRecorded event (if asynchronous) |
| **Potential Integration** | Currently synchronous Eloquent event coupling. Future integration depends on D39 resolution. |
| **Evidence** | Stream 3, Vote.php:61-122 (createResultsFromCandidates), Vote.php:260-271 (syncResults) |

**Confidence:** HIGH for coupling observation. Boundary MERGER CONSIDERATION — provisional pending D39.

---

### R7: Voting → Audit (Operational)

| Dimension | Assessment |
|-----------|-----------|
| **Type** | Upstream/Downstream (event notification) |
| **Direction** | Voting (upstream) → Audit (downstream) |
| **Dependency** | Audit records voter actions per-step (ElectionAuditService.logVoterAction). Audit is fire-and-forget — Voting does not depend on Audit. |
| **Shared Concepts** | Voter ID, election ID, step number, action, timestamp |
| **Data Ownership** | Voting owns vote data. Audit owns audit trail. No shared ownership. |
| **Published Language** | VoterActionLogged event (conceptual) |
| **Potential Integration** | Audit subscribes to voter actions; Voting emits action records without awaiting audit confirmation. |
| **Evidence** | Stream 4, ElectionAuditService.logVoterAction, VoteController usage of audit service |

**Confidence:** HIGH — Fire-and-forget pattern is well-documented. No coupling from Voting to Audit.

---

### R8: Constitutional Governance → Audit

| Dimension | Assessment |
|-----------|-----------|
| **Type** | Upstream/Downstream (event notification) |
| **Direction** | Constitutional Governance (upstream) → Audit (downstream) |
| **Dependency** | State transitions are recorded in ElectionAuditLog. Audit is fire-and-forget — Governance does not depend on Audit. |
| **Shared Concepts** | Election, action, user, timestamp, old/new values |
| **Data Ownership** | Governance owns state/transition data. Audit owns audit records. |
| **Published Language** | StateChanged event |
| **Potential Integration** | Audit subscribes to governance actions; Governance emits action data without awaiting audit. |
| **Evidence** | Stream 4, ElectionAuditLog, ElectionAuditService.log() |

**Confidence:** HIGH — Fire-and-forget pattern confirmed in Stream 4.

---

### R9: Authorization → Audit

| Dimension | Assessment |
|-----------|-----------|
| **Type** | Upstream/Downstream (event notification) |
| **Direction** | Authorization (upstream) → Audit (downstream) |
| **Dependency** | Capability decisions (allowed/denied) are recorded by SecurityEventRecorder. Audit is fire-and-forget. |
| **Shared Concepts** | User, capability, decision, election |
| **Data Ownership** | Authorization owns capability decisions. Audit owns event records. |
| **Published Language** | SecurityEvent (trust_allowed, trust_denied) |
| **Potential Integration** | SecurityEventRecorder subscribes to trust evaluation outcomes. |
| **Evidence** | Stream 4, SecurityEventRecorder, TrustPolicyEvaluator |

**Confidence:** HIGH — Fire-and-forget pattern confirmed for security events.

---

### R10: Constitutional Governance → (distributed) → Arbitration / Legitimacy

| Dimension | Assessment |
|-----------|-----------|
| **Type** | Evaluation (Constitutional Governance decisions are evaluated by Arbitration) |
| **Direction** | Constitutional Governance (upstream) → Arbitration (downstream evaluator) |
| **Dependency** | Arbitration evaluates governance decisions for constitutional validity (ConstitutionalArbitrationKernel.decide). Not currently operationally integrated (D36). |
| **Shared Concepts** | Governance decision, capability type, constitutional validity |
| **Data Ownership** | Governance owns decisions. Arbitration owns legitimacy determinations. |
| **Published Language** | None currently — operational invocation not observed |
| **Potential Integration** | Unknown pending D35, D36, D37 resolution |
| **Evidence** | Stream 6B, ConstitutionalArbitrationKernel, ConflictResolutionPolicy |

**Confidence:** MEDIUM — Implementation exists. Operational path unresolved. Boundary unresolved.

---

## 3. Context Dependency Map

```
Trust Attestation ──────► Eligibility ──────► Authorization ──────► Voting
       │                      │                     │                  │
       │ (revocation          │ (eligibility         │ (capability      │ (vote
       │  events)             │  status)             │  decisions)      │  recorded)
       ▼                      ▼                     ▼                  ▼
  Constitutional Governance ──┼──────────────────────┘                  │
       │                      │                                        │
       │ (lifecycle state)    │ (state transitions)                    │
       │                      ▼                                        ▼
       │                 Audit (Operational) ────────────────► Results/Tallying
       │                      ▲                         (derived projection,
       │                      │                         synchronous coupling)
       │                      │
       └──────────────────────┘
         (governance actions recorded)
```

### Key Characteristics

| Characteristic | Assessment |
|---------------|------------|
| Primary upstream | Trust Attestation, Constitutional Governance |
| Primary downstream | Voting, Results/Tallying |
| Shared dependency layer | Audit (consumes from all) |
| Strongest coupling | Voting → Results/Tallying (synchronous, derived) |
| Weakest integration | Governance → Arbitration (operationally deferred) |
| Event-driven patterns | Trust → Eligibility, Auth → Audit, Governance → Audit |

---

## 4. Context Integration Pattern Analysis

### Upstream/Downstream Relationships

| Upstream | Downstream | Pattern | Coupling |
|----------|-----------|---------|----------|
| Trust Attestation | Eligibility | Event notification | Loose — event-driven |
| Eligibility | Authorization | Shared query | Loose — read-only dependency |
| Constitutional Governance | Authorization | Shared query | Loose — reads lifecycle state |
| Constitutional Governance | Voting | State check | Loose — reads lifecycle state |
| Authorization | Voting | Capability check | Loose — resolver call |
| Voting | Results/Tallying | **Synchronous creation** | **TIGHT — same request lifecycle** |
| Voting | Audit | Event notification | Loose — fire-and-forget |
| Constitutional Governance | Audit | Event notification | Loose — fire-and-forget |
| Authorization | Audit | Event notification | Loose — fire-and-forget |

### Dependency Direction Summary

```
Upstream contexts:        Trust Attestation, Constitutional Governance
Mid-stream contexts:     Eligibility, Authorization
Downstream contexts:     Voting, Results/Tallying
Observability layer:     Audit (receives from all, affects none)
```

---

## 5. Published Language Per Context

| Context | Published Language | Consumed By |
|---------|-------------------|-------------|
| Trust Attestation | IdentityAttested, VerificationRevokedEvent, VerificationStatus | Eligibility, Governance |
| Eligibility | EligibilityStatus (may change), ParticipationEligibilityEvidence | Authorization, Governance Replay |
| Authorization | CapabilitySnapshot (allowed/denied actions), SecurityEvent | Voting, Audit |
| Constitutional Governance | LifecycleSnapshot, StateTransition events, GovernanceDecision | Authorization, Voting, Audit, Arbitration |
| Voting | VoteRecorded event (conceptual), VoterAction events | Results, Audit |
| Results/Tallying | VoteCounts (aggregate query result) | Public display |
| Audit | AuditLog, SecurityEvent, VoterAuditTrail | Governance (investigation) |

---

## 6. Data Ownership Matrix

| Data | Owned By | Read/Referenced By |
|------|----------|-------------------|
| Identity verification status | Trust Attestation | Eligibility, Authorization |
| Trust level | Trust Attestation | Eligibility |
| Eligibility status | Eligibility | Authorization |
| Eligibility evidence | Eligibility | Governance Replay (divergence) |
| Role assignments | Authorization | Governance (guard check) |
| Capability decisions | Authorization | Voting, Audit |
| Lifecycle state | Constitutional Governance | Authorization, Voting |
| Transition rules | Constitutional Governance | Authorization |
| Governance decisions | Constitutional Governance | Arbitration |
| Anonymous votes | Voting | Results, Audit |
| Results (derived) | Results/Tallying | Audit, Public |
| Audit logs | Audit | Governance (investigation) |

---

## 7. Open Relationship Questions

| Question | Affects | Depends On |
|----------|---------|-----------|
| Should Voting and Results/Tallying merge? | R6, Voting, Results | D39 (counting state meaning) |
| Do receipt verification and checksum verification belong inside Voting or in a separate Verification context? | Voting boundary | D42B (verifiability guarantee) |
| Should Arbitration evaluate governance decisions through event-driven or query-driven integration? | R10 | D35, D36, D37 |
| Is there a Membership/Party context that is upstream of Eligibility but outside current scope? | Eligibility boundaries | Future discovery |
| Should SecurityEventRecorder be part of Audit or Governance? | Audit boundaries | D20 (design intent) |

---

## 8. Summary

| Dimension | Finding |
|-----------|---------|
| Authorized contexts mapped | 7 (Trust, Eligibility, Authorization, Governance, Audit, Voting, Results) |
| Relationships documented | 10 dependency relationships + 3 notification relationships |
| Strongest coupling | Voting → Results/Tallying (synchronous, derived) |
| Weakest integration | Governance → Arbitration (operationally deferred) |
| Primary upstream sources | Trust Attestation, Constitutional Governance |
| Observability layer | Audit (fire-and-forget from all sources) |
| Event patterns identified | Trust→Eligibility, Governance→Audit, Auth→Audit |
| Relationship confidence | HIGH for 7 relationships, MEDIUM for 3 (Arbitration-related) |
| Provisional boundaries | Voting/Results coupling, Voting/Verification boundary |

---

**Round 21 Context Mapping — READY FOR ARB REVIEW**

**7 candidate contexts mapped with 10 documented relationships. Voting and Results/Tallying boundaries marked provisional pending D39 and D42B. No final boundaries locked. No aggregate discovery or tactical design performed.**
