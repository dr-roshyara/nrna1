# Round 19 — Candidate Bounded Context Discovery

**Date:** 2026-06-07

**Phase:** Strategic DDD Boundary Discovery (Step 3)

**Status:** Discovery Complete — Awaiting ARB Review

**Governance Constraint:** Any boundary influenced by unresolved election-integrity guarantees (D42B) is marked PROVISIONAL.

---

## 1. Methodology

Candidate contexts are identified from evidence across all accepted streams (1-6B, 3) and Round 18 Governance Review. Each candidate is assessed for:

- **Ubiquitous Language** — Vocabulary used by the concept
- **Decisions Owned** — What decisions does this concept make independently?
- **Information Owned** — What data does this concept control?
- **Business Responsibilities** — What business activities belong here?
- **Invariants** — What must always be true?
- **Relationships** — How does this interact with other concepts?
- **Guarantee Sensitivity** — Would understanding election integrity guarantees change this boundary?

This is still discovery — candidate contexts are proposed, not finalized.

---

## 2. Candidate Context Inventory

### C1: Trust Attestation

**Observed Language:**
verified, trust level, attestation, verification officer, evidence, revoked, verification decision, identity

**Decisions Owned:**
- Is a claimed identity trustworthy?
- What trust level has been established (Provisionally Trusted, Officer Verified, Organization Verified, High Assurance)?
- Should verification be revoked based on new evidence?
- What evidence supports this trust decision?

**Information Owned:**
- Verification status per participant (verified/withheld/revoked)
- Trust level per participant
- Verification officer identity and decision timestamp
- Evidence artifacts supporting trust decisions
- Device fingerprints and IP context at verification time

**Business Responsibilities:**
- Officer reviews participant-provided evidence
- Officer attests or withholds identity verification
- Assignment of trust level based on evidence quality and organizational policy
- Revocation of prior attestation when new evidence emerges
- Emission of VerificationRevokedEvent for governance consumption

**Invariants:**
- Verification does not grant voting rights (eligibility required separately)
- Revocation does not automatically invalidate past votes (governance decides)
- Trust level is per-organization (policy-dependent)
- Verification officer authority comes from role assignment, not from being verified themselves (bootstrap trust)

**Evidence Sources:** ADR-001 (Trust Attestation Domain), ADR-002 (Verified ≠ Eligible ≠ Authorized), UBIQUITOUS_LANGUAGE.md, TRUST_CHAIN.md

**Guarantee Sensitivity:** LOW — Trust model is well-documented and stable regardless of broader election guarantees.

---

### C2: Eligibility

**Observed Language:**
eligible, membership, voting_rights, enrolled, fee_status, active, suspended, participation eligibility evidence

**Decisions Owned:**
- Is a participant eligible for a specific process (voting, candidacy, delegation)?
- What eligibility dimensions apply (membership status, fees, type, timing, geographic scope)?
- Is eligibility evidence sufficient for participation?

**Information Owned:**
- Membership status (active/expired/suspended)
- Fee payment status (paid/partial/unpaid/exempt)
- Membership type (full/associate/etc.)
- Election enrollment (which elections is participant eligible for?)
- ParticipationEligibilityEvidence (frozen, hashed, replay-addressable snapshot)

**Business Responsibilities:**
- Evaluate eligibility at action time (eligibility is computed, not stored)
- Determine voting rights based on membership status + fees + member type
- Determine candidacy eligibility
- Freeze eligibility evidence at evaluation time for replay divergence detection
- Gate participation based on eligibility status

**Invariants:**
- Eligibility is COMPUTED, not STORED (re-evaluated each time)
- Eligibility changes are automatically reflected on next check
- Eligibility is process-specific (voting ≠ candidacy ≠ delegation)
- Verified identity is necessary but not sufficient for eligibility

**Evidence Sources:** ADR-002 (Verified ≠ Eligible ≠ Authorized), Stream 1 (Evidence), Stream 3 (ParticipationEligibilityEvidence), Stream 5 (preconditions), TRUST_CHAIN.md

**Guarantee Sensitivity:** MEDIUM — Eligibility rules may be influenced by organizational governance, but the core mechanism (computed at time of action) is stable.

---

### C3: Authorization

**Observed Language:**
authorized, role, permission, capability, action, scope, chief, deputy, platform_admin, voter, system

**Decisions Owned:**
- Is a participant authorized to perform a specific action in a specific context?
- What capabilities are available in the current election state?
- Does the user have the required role for this action?
- Are all precondition rules satisfied?

**Information Owned:**
- Role assignments (ElectionOfficer: chief, deputy, platform_admin, etc.)
- Global permissions (Spatie Permission)
- Capability snapshots (point-in-time allowed/denied actions)
- Action definitions with required roles and preconditions (ElectionConstitution.RULES)

**Business Responsibilities:**
- Centralized capability resolution (backend is sole authority)
- Deterministic permission checking (pure function, no infrastructure calls)
- Role verification against constitutional rules
- Precondition validation before allowing action
- Capability snapshot provision to frontend (never frontend inference)

**Invariants:**
- Authorization requires ALL of: Verified AND Eligible AND Permission (three conditions)
- Capability resolution is deterministic (same inputs → same outputs)
- Frontend is passive — never infers or re-derives permissions
- Backend is sole authority — no bypass mechanisms
- Any denial stops evaluation (first denial pattern)

**Evidence Sources:** ADR-001 (Constitutional Capability Sovereignty), ADR-004 (Deterministic Capability Resolver), Stream 2 (Governance & Authority), ADR-002, TRUST_CHAIN.md

**Guarantee Sensitivity:** LOW — Authorization model is well-documented and implementation is stable.

---

### C4: Constitutional Governance / Lifecycle

**Observed Language:**
constitution, state, transition, lifecycle, preconditions, suspension, governance decision, lifecycle_state

**Decisions Owned:**
- What state transitions are allowed from the current election state?
- What preconditions must be met for each transition?
- Is the election currently suspended?
- What governance decisions have been recorded?

**Information Owned:**
- Constitutional state (12 lifecycle states: draft → submitted → approved → ... → archived)
- State transition rules (ElectionConstitution.RULES)
- Precondition definitions (timezone_set, has_posts, has_voters, has_chief, etc.)
- Suspension status and metadata
- Governance Decision records

**Business Responsibilities:**
- Define allowed state transitions
- Enforce transition rules (ConstitutionalTransitionGuard — no bypasses)
- Validate preconditions before allowing progression
- Manage suspension as operational governance overlay
- Record governance decisions for audit and replay

**Invariants:**
- All transitions defined in ElectionConstitution.RULES — nowhere else
- ConstitutionalTransitionGuard is mandatory — no exceptions
- Suspension freezes capabilities only — does not mutate business facts
- Any precondition failure blocks the transition
- Rules are embedded in code — changing rules requires deployment

**Evidence Sources:** Stream 5 (Constitutional Rules), ADR-003 (Lifecycle vs Phase), ADR-001 (Constitutional Capability Sovereignty), Stream 2 (Governance & Authority), Round 18 Governance Review

**Guarantee Sensitivity:** MEDIUM — Lifecycle is documented and stable. Whether suspension requires separate governance consideration depends on organizational policies.

---

### C5: Voting

**Observed Language:**
vote, cast, ballot, candidate, selection, receipt, checksum, participation proof, cast_at, voting_code

**Decisions Owned:**
- Is this vote valid (election_id, organisation_id, receipt_hash validation)?
- Is this vote hash unique (duplicate prevention)?
- Does device fingerprint indicate duplicate?
- Is the vote receipt valid for voter self-verification?

**Information Owned:**
- Vote record (anonymous — NO user_id)
- Candidate selections (60 candidate JSON columns)
- Vote hash (uniqueness proof)
- Receipt hash (voter self-verification token)
- Data checksum (SHA256 integrity verification)
- Participation proof (admin verification without revealing vote)
- Device fingerprint hash (fraud detection)
- Encrypted vote data
- Cast_at timestamp

**Business Responsibilities:**
- Record anonymous votes
- Ensure vote uniqueness (hash constraint)
- Provide voter self-verification via receipt
- Provide admin participation verification without revealing vote choice
- Detect duplicate device fingerprints
- Maintain data integrity via checksum
- Enable result regeneration from source of truth (syncResults)

**Invariants:**
- NO user_id in votes table (vote anonymity is a fundamental requirement — ADR_20260203)
- Vote hash is unique per vote
- Data checksum covers candidate selections + app key
- vote_hash can verify vote without revealing voter identity
- Receipt verification does not reveal vote choice

**Evidence Sources:** Stream 3 (Voting/Tally), BaseVote.php, ADR_20260203 (Voting Security), ADR-002 (trust chain)

**Guarantee Sensitivity:** HIGH — Vote integrity, receipt verification, and participation proof mechanisms directly depend on intended election guarantees (D42B). If universal verifiability is required, Voting context may need to expose additional verification capabilities.

**PROVISIONAL — Pending governance clarification on verifiability guarantees.**

---

### C6: Results / Tallying

**Observed Language:**
result, count, tally, vote_count, position, candidacy, abstention, no_vote, results_published

**Decisions Owned:**
- How many results should a vote produce (expected count)?
- Does stored result count match expected count (integrity verification)?
- Should results be visible to the public (publication gate)?

**Information Owned:**
- Individual result rows (one per candidate selection or abstention)
- Vote count per candidacy (computed on-demand via SQL aggregation)
- Post-level and election-level aggregate counts
- No-vote/abstention records

**Business Responsibilities:**
- Create Result rows from vote candidate selections
- Create Result rows for abstentions
- Regenerate results from vote JSON source of truth (syncResults)
- Compute vote counts via SQL aggregation (on-demand)
- Gate result visibility via results_published flag
- Verify result count integrity against expected count

**Invariants:**
- Results are a derived projection of Vote data — NOT independent records
- Results can be deleted and regenerated from vote JSON at any time
- Vote is the authoritative source of truth for result data
- Counting "state" in state machine gates publication, not computation
- Result integrity can be verified via count comparison + checksum

**Evidence Sources:** Stream 3 (Voting/Tally), Vote.php, ResultController.php, ADR-003 (Lifecycle vs Phase)

**Guarantee Sensitivity:** HIGH — Whether Results is a separate context or part of Voting depends on whether counting is a distinct domain concern (D39). This boundary is affected by the unresolved meaning of the "counting" state.

**PROVISIONAL — Pending resolution of counting state meaning (D39).**

---

### C7: Audit / Operational Recording

**Observed Language:**
audit, log, event, action, record, trace, history, old_values, new_values, ip_address, user_agent, session_id

**Decisions Owned:**
- Should an audit entry be recorded for this action?
- Should a security event be recorded (always for DENY, sampled for ALLOW)?
- Should the log file be rotated (at 100MB)?

**Information Owned:**
- ElectionAuditLog entries (action, old_values, new_values, user_id, ip_address, session_id)
- Per-voter JSONL audit files (step-level voter journey)
- Category audit files (election.jsonl, voters.jsonl, committee.jsonl)
- Security events (trust evaluation outcomes)
- Email masks for privacy

**Business Responsibilities:**
- Record election state changes for accountability
- Log voter actions per-step (5-step workflow)
- Log election events by category
- Record trust evaluation events (fire-and-forget, never affects outcome)
- Rotate log files when exceeding size limits
- Mask email addresses for privacy

**Invariants:**
- Audit recording never affects business outcomes (fire-and-forget)
- Security event recording never propagates exceptions
- Operational audit and governance replay are NOT coupled (separate mechanisms)
- Audit records include timestamp, actor, action, and context (IP, session)

**Evidence Sources:** Stream 4 (Audit Clarification), ElectionAuditService.php, ElectionAuditLog.php, SecurityEventRecorder.php

**Guarantee Sensitivity:** MEDIUM — Operational audit is stable. Whether auditability requires separate governance context depends on governance clarification (D42B).

---

### C8: Governance Replay / Verification

**Observed Language:**
replay, evidence, envelope, hash, seal, certification, divergence, snapshot, integrity

**Decisions Owned:**
- Was evidence tampered with after sealing (integrity verification)?
- Did replay outcome diverge from original (divergence detection)?
- Is a governance decision snapshot valid (integrity verification)?

**Information Owned:**
- ReplayEvidenceEnvelope (sealed evidence with deterministic hash)
- ReplaySession (lifecycle: sealed → replayed → certified/diverged)
- ReplayAssertion (expected outcome bound to evidence)
- ReplayCertification (immutable outcome)
- ReplayDivergenceDetected event
- GovernanceDecisionSnapshot (persisted decision with integrity hash)
- GovernanceArchaeologyRecord (read model for replay)

**Business Responsibilities:**
- Seal constitutional evidence at evaluation time
- Verify evidence integrity via deterministic SHA256 hash
- Certify replay outcomes against expected outcomes
- Detect divergence when re-evaluated outcome differs from original
- Persist governance decisions as snapshots for replay
- Enable governance verification independent of operational audit

**Invariants:**
- Evidence is frozen at envelope creation — no mutation allowed
- Hash integrity is always verifiable
- Same evidence always produces same envelope hash
- A session can be used for exactly one certification cycle
- Governance replay is infrastructure-enabled but operationally deferred (Phase 6)

**Evidence Sources:** Stream 4 (Audit Clarification), Stream 6B (Invocation & Consequence), ReplayEvidenceEnvelope.php, ReplaySession.php, GovernanceReplayService.php, GovernanceDecisionSnapshot.php

**Guarantee Sensitivity:** MEDIUM — Governance replay infrastructure exists. Whether it becomes a separate context depends on governance requirements for verifiability and auditability (D42B).

---

### C9: Arbitration / Legitimacy

**Observed Language:**
arbitration, legitimacy, authority, conflict, resolution, precedent, doctrine, constitutional validity, governance decision

**Decisions Owned:**
- Is a governance decision constitutionally valid?
- When multiple authorities claim jurisdiction, which wins?
- Is an authority's legitimacy status LEGITIMATE, EXPIRED, or other?

**Information Owned:**
- ConstitutionalDecision (winner, legitimacy, reason, trace)
- ConstitutionalGovernanceDecision (links governance decision to constitutional outcome)
- ConstitutionalArbitrationTrace (evaluated nodes, rejection reasons, doctrine rules applied)
- AuthorityClassification (direct, delegated, overrides, exceptions)
- GovernanceLegitimacy status (LEGITIMATE, EXPIRED, PENDING, SUSPENDED, EMERGENCY, CARETAKER, REVOKED)
- Conflict resolution decisions with winning authority selection

**Business Responsibilities:**
- Evaluate governance decisions for constitutional validity (ArbitrationKernel)
- Arbitrate authority conflicts when jurisdiction overlaps
- Determine legitimacy status via temporal window (LegitimacyEvaluator)
- Record all arbitration decisions with full traceability
- Persist governance decision snapshots for replay

**Invariants:**
- A session can be used for exactly one certification cycle
- Legitimacy is temporally determined (window-based)
- Governance decisions are capability-scoped
- Conflict resolution selects single winning authority

**Evidence Sources:** Stream 6B (Invocation & Consequence), ConstitutionalArbitrationKernel.php, ConflictResolutionPolicy.php, LegitimacyEvaluator.php, GovernanceLegitimacy.php

**Guarantee Sensitivity:** LOW — Arbitration mechanisms exist independently of election integrity guarantees. Domain-level relationship to challenge handling remains unresolved (D35, D36, D37).

---

### C10: Challenge/Dispute Handling (Distributed)

**Observed Language:**
(review, evaluation, arbitration, verification, certification — no explicit challenge/dispute vocabulary found)

**Decisions Owned:**
- Distributed across Arbitration (constitutional validity), Governance (decision review), and Verification (replay divergence detection)

**Information Owned:**
- Distributed across ArbitrationKernel, ReplaySession, and GovernanceDecision

**Business Responsibilities:**
- Decision review via ConstitutionalArbitrationKernel
- Outcome verification via ReplayCertification
- Divergence detection via ReplayDivergenceDetected
- Authority escalation via ConflictResolutionPolicy

**Evidence Sources:** Stream 6A (Challenge Presence Assessment), Stream 6B

**Guarantee Sensitivity:** LOW — Challenge handling is distributed by design (Outcome F). Does not depend on specific guarantee definitions.

---

## 3. Context Responsibility Matrix

| Responsibility | Trust Attest | Eligibility | Authorization | Governance | Voting | Results | Audit | Replay | Arbitrate |
|---|---|---|---|---|---|---|---|---|---|
| Verify identity | ✅ OWNS | | | | | | | | |
| Determine trust level | ✅ OWNS | | | | | | | | |
| Determine eligibility | | ✅ OWNS | | | | | | | |
| Check roles/permissions | | | ✅ OWNS | | | | | | |
| Resolve capabilities | | | ✅ OWNS | | | | | | |
| Manage state transitions | | | | ✅ OWNS | | | | | |
| Enforce preconditions | | | | ✅ OWNS | | | | | |
| Record anonymous votes | | | | | ✅ OWNS | | | | |
| Generate results | | | | | | ✅ OWNS | | | |
| Compute vote counts | | | | | | ✅ OWNS | | | |
| Publish results | | | | ✅ gates | | ✅ displays | | | |
| Log operational events | | | | | | | ✅ OWNS | | |
| Record security events | | | | | | | ✅ OWNS | | |
| Seal evidence for replay | | | | | | | | ✅ OWNS | |
| Certify replay outcomes | | | | | | | | ✅ OWNS | |
| Detect divergence | | | | | | | | ✅ OWNS | |
| Arbitrate authority conflicts | | | | | | | | | ✅ OWNS |
| Determine legitimacy | | | | | | | | | ✅ OWNS |
| Evaluate constitutional validity | | | | | | | | | ✅ OWNS |

---

## 4. Ubiquitous Language Per Candidate Context

| Context | Key Terms |
|---------|-----------|
| **Trust Attestation** | verified, trust level, attestation, officer, evidence, revoked, verification decision, provisionally trusted, officer verified, high assurance |
| **Eligibility** | eligible, membership, voting_rights, enrolled, fee_status, active, suspended, participation evidence, computed eligibility |
| **Authorization** | authorized, role, permission, capability, action, scope, allowed, denied, precondition |
| **Constitutional Governance** | constitution, state, transition, lifecycle, suspension, governance decision, preconditions, lifecycle_state |
| **Voting** | vote, cast, ballot, receipt, checksum, participation proof, anonymity, voting_code, candidate selection |
| **Results/Tallying** | result, count, tally, vote_count, position, candidacy, abstention, publication gate |
| **Audit** | audit log, event, action, record, old_values, new_values, security event, fire-and-forget |
| **Governance Replay** | replay, evidence envelope, seal, hash, certification, divergence, snapshot, integrity |
| **Arbitration/Legitimacy** | arbitration, legitimacy, authority conflict, doctrine, precedent, resolution, constitutional validity |

---

## 5. Context Relationship Inventory

```
Trust Attestation ──enables──► Eligibility ──enables──► Authorization
       │                                                       │
       │ (verified identity                                     │ (capability decisions
       │  is prerequisite)                                       │  gate actions)
       ▼                                                       ▼
  Governance (receives VerificationRevokedEvent)           Voting (receives authorization)
       │                                                       │
       │ (governance decides consequences)                     │ (synchronous)
       ▼                                                       ▼
  Arbitration ◄──────────► Governance Replay              Results/Tallying
       │ (determines          │ (verifies outcomes)             │
       │  constitutional      │                                 │ (computed on-demand)
       │  validity)           │                                 ▼
       │                      │                           Audit (operational recording)
       ▼                      ▼
  Governance (receives divergence signals)
```

**Key Relationship Characteristics:**

| Relationship | Type | Evidence |
|-------------|------|----------|
| Trust → Eligibility | Enables (prerequisite) | ADR-002, TRUST_CHAIN |
| Eligibility → Authorization | Enables (prerequisite) | ADR-002, TRUST_CHAIN |
| Authorization → Voting | Gates (capability check) | Stream 2, ADR-001 |
| Voting → Results | Synchronous projection (coupled) | Stream 3 |
| Results → Audit | Records (operational logging) | Stream 4 |
| Trust → Governance | Event notification (VerificationRevoked) | ADR-003 |
| Arbitration → Governance | Decision review (authority conflict resolution) | Stream 6B |
| Governance → Replay | Verification (outcome certification) | Stream 4, Stream 6B |

---

## 6. Context Ownership Matrix

| Information | Owned By | Accessed By |
|-------------|----------|-------------|
| Identity verification status | Trust Attestation | Eligibility, Governance |
| Trust level | Trust Attestation | Eligibility |
| Evidence artifacts | Trust Attestation | Governance (revocation), Audit |
| Membership status | Eligibility | Voting (precondition check) |
| Eligibility evidence | Eligibility | Governance Replay (divergence detection) |
| Role assignments | Authorization | Constitutional Governance (guard checks) |
| Capability snapshots | Authorization | Voting, Results (precondition checks) |
| Constitutional state | Constitutional Governance | Authorization (capability resolution) |
| Governance decisions | Constitutional Governance | Arbitration (validity check), Governance Replay |
| Anonymous votes | Voting | Results (projection), Audit (log) |
| Vote counts | Results/Tallying | Audit (log), Public (publication) |
| Audit logs | Audit | Governance (investigation), Arbitration (evidence) |
| Replay evidence envelopes | Governance Replay | Arbitration (verification) |
| Replay certifications | Governance Replay | Governance (legitimacy confirmation) |
| Arbitration traces | Arbitration/Legitimacy | Governance (decision records) |

---

## 7. Open Boundary Questions

| Question | Affected Contexts | Depends On |
|----------|------------------|------------|
| Are Voting and Results/Tallying the same context or separate? | Voting, Results | D39 (counting state meaning) |
| Is Verification a separate context or cross-cutting concern? | Voting, Trust Attestation, Governance Replay | D42B (verifiability guarantee) |
| Are operational Audit and Governance Replay separate contexts? | Audit, Governance Replay | Their implementation is already separate (Stream 4) — boundaries appear clear |
| Is Arbitration part of Governance or a separate context? | Arbitration, Governance | D35, D36, D37 (legitimacy enforcement, invocation) |
| Should Eligibility be part of Voting or separate? | Eligibility, Voting | D42B (eligibility guarantee scope) |
| Is Challenge/Dispute Handling a named context or distributed? | Multiple | Stream 6A conclusion (Outcome F — distributed) |

---

## 8. Guarantee-Sensitive Boundary Decisions (PROVISIONAL)

The following candidate context boundaries are influenced by unresolved election-integrity guarantees (D42B):

| Boundary Decision | Current Assessment | If Verifiability Required | If Auditability Required |
|------------------|-------------------|--------------------------|------------------------|
| Voting vs Verification | Receipt verification is in Voting | May need separate Verification context | — |
| Voting vs Results | Currently coupled | May need separation for independent verification | — |
| Audit vs Governance Replay | Currently separate | — | May confirm separation |
| Eligibility vs Governance | Eligibility evidence crosses boundary | — | May need stronger event integration |

**Status:** All above boundaries are **PROVISIONAL** pending governance clarification on:
1. Universal verifiability requirements
2. Auditability requirements
3. Result integrity requirements

Boundaries NOT listed above (Trust Attestation, Authorization, Constitutional Governance, Arbitration) are **stable** and do not depend on unresolved guarantees.

---

## 9. Summary

| Candidate Context | Confidence | Guarantee Sensitive | Status |
|------------------|-----------|-------------------|--------|
| C1 — Trust Attestation | HIGH | NO | Stable |
| C2 — Eligibility | HIGH | MEDIUM | Stable |
| C3 — Authorization | HIGH | NO | Stable |
| C4 — Constitutional Governance | HIGH | MEDIUM | Stable |
| C5 — Voting | HIGH | HIGH | **PROVISIONAL** |
| C6 — Results/Tallying | MEDIUM | HIGH | **PROVISIONAL** |
| C7 — Audit | HIGH | MEDIUM | Stable |
| C8 — Governance Replay | MEDIUM | MEDIUM | Stable |
| C9 — Arbitration/Legitimacy | MEDIUM | LOW | Stable |
| C10 — Challenge/Dispute | MEDIUM | LOW | Distributed (Outcome F) |

**Stable contexts (7):** Trust Attestation, Eligibility, Authorization, Constitutional Governance, Audit, Governance Replay, Arbitration/Legitimacy

**Provisional contexts (2):** Voting, Results/Tallying

**Distributed context (1):** Challenge/Dispute Handling

---

**Round 19 Candidate Bounded Context Discovery — READY FOR ARB REVIEW**

**10 candidate contexts identified. 7 stable. 2 provisional (guarantee-sensitive). 1 distributed.**

**Next step after ARB review:** Determine whether candidate contexts are accepted, need adjustment, or require further evidence. If accepted, the next phase would explore context responsibilities in depth and begin aggregate discovery.
