# Round 18 — Governance Discovery Findings

**Date:** 2026-06-07

**Phase:** Round 18 — Governance Discovery Execution

**Status:** Complete — Awaiting ARB Review

---

## 1. Scope

**Sources Examined:**

| Source | Type | Content |
|--------|------|---------|
| ADR-001: Trust Attestation Domain | ADR | Trust as separate bounded context |
| ADR-002: Verified ≠ Eligible ≠ Authorized | ADR | Three-layer trust model |
| ADR-003: Governance-Driven Revocation | ADR | Governance context owns revocation consequences |
| ADR_20260203: Voting Security | ADR | Vote anonymity as fundamental requirement |
| ADR-001 (Constitutional Capability Sovereignty) | ADR | Backend as sole capability authority |
| ADR-002 (Frontend Anti-Corruption) | ADR | Frontend as passive read adapter |
| ADR-003 (Lifecycle vs Phase Projection) | ADR | 12 constitutional lifecycle states |
| ADR-004 (Deterministic Capability Resolver) | ADR | Pure function resolver design |
| ADR-005 (Projection Sovereignty) | ADR | Frontend renders projections, not state |
| Trust Domain: UBIQUITOUS_LANGUAGE | Design doc | Domain vocabulary definitions |
| Trust Domain: TRUST_CHAIN | Design doc | Trust decision flow architecture |
| Round 14 ARB decisions | ARB records | Historical architectural governance |

**Not Examined (Not Available in Repository):**
- Organizational bylaws or constitution
- Election commission regulations
- Stakeholder interviews
- External governance policies

---

## 2. D42B — Intended Election Integrity Guarantees

### Finding 2.1: Vote Anonymity Is Explicitly Intended

**Source:** ADR_20260203 (Voting Security), lines 26-47

**Evidence:**
The ADR explicitly states vote anonymity as a security requirement:

> "The core tension: How to support multiple election types and contexts while preserving the fundamental security property that **votes are anonymous**."

> "The vote itself must NOT contain user_id."

> "This ensures election officials can verify 'this code authorized a vote' but cannot determine 'this user cast this specific vote'."

**Security checklist (lines 465-480) includes explicit verification items:**
- No user_id column in votes table ✓
- No user_id column in results table ✓
- save_vote() does not accept or store user_id parameter ✓
- Tests verify vote anonymity is maintained ✓

**Guarantee Category:** Privacy, Non-Repudiation (limited)

**Confidence:** HIGH — Explicitly documented as a fundamental requirement.

---

### Finding 2.2: Verified ≠ Eligible ≠ Authorized (Compositional Trust Model)

**Source:** ADR-001 (Trust Attestation Domain), ADR-002 (Verified ≠ Eligible ≠ Authorized), UBIQUITOUS_LANGUAGE, TRUST_CHAIN

**Evidence:**
The system explicitly separates trust into three orthogonal decisions:

```text
Verified (identity trustworthy) — Trust Attestation Context
    ↓ enables
Eligible (meets process requirements) — Eligibility Context
    ↓ enables
Authorized (has permission to act) — Authorization Context
```

**ADR-002 (line 197-204) defines the authorization formula:**
```
CanPerformAction(actor, action) =
  Verified(actor) &&
  Eligible(actor, process) &&
  Permission(actor, action, scope)
```

**UBIQUITOUS_LANGUAGE provides explicit definitions:**

| Term | Definition | Enables |
|------|-----------|---------|
| Verified | Officer has attested identity can be trusted | Eligibility evaluation |
| Trust Level | Degree of assurance based on evidence | Trust-weighted decisions |
| Eligible | Meets process-specific requirements | Participation gates |
| Authorized | Has permission for specific action | Action execution |

**TRUST_CHAIN documentation (lines 405-431) establishes architectural principles:**
1. Verification does not grant rights
2. Each step is independent
3. Revocation is localized
4. Bootstrap trust (officers verified by role assignment, not verification)

**Guarantee Categories:** Identity Verification, Eligibility Integrity, Authorization Integrity

**Confidence:** **HIGH** — Explicitly defined with supporting ADRs, ubiquitous language, and trust chain documentation.

---

### Finding 2.3: Governance Context Owns Consequence Decisions

**Source:** ADR-003 (Governance-Driven Revocation)

**Evidence:**
Revocation does NOT automatically affect past votes, results, or audits. These are governance decisions:

> "Trust Attestation Context decides: Verification is revoked. Governance Context decides: Impact on past votes, election results, audits."

**What revocation does NOT automatically do (lines 63-70):**
- ❌ Invalidate past votes
- ❌ Change election results
- ❌ Reopen audits
- ❌ Void certifications

**TRUST_CHAIN (lines 343-349) reinforces:**
> "Revocation Does NOT Determine: Whether past votes are valid, whether election results are legitimate, whether audits need reopening, whether consequences apply."

**Guarantee Categories:** Auditability, Result Integrity, Governance Legitimacy

**Confidence:** **HIGH** — Explicit ADR with clear boundary definitions.

---

### Finding 2.4: No Explicit Guarantees Beyond Trust and Anonymity

**Source:** All examined ADRs, design docs, architecture decisions

**Evidence:**
The examined governance documents address:
- **Anonymity**: Explicitly required (ADR_20260203)
- **Verified ≠ Eligible ≠ Authorized**: Explicitly defined (ADR-001, ADR-002)
- **Governance-driven revocation**: Explicitly defined (ADR-003)
- **Constitutional capability sovereignty**: Explicitly defined (ADR-001 constitutional)

**NOT explicitly addressed in governance documents:**
- Universal verifiability (voter or anyone can verify all votes were counted correctly)
- End-to-end verifiability (E2E-V)
- Coercion resistance beyond vote anonymity
- Receipt-freeness
- Eligibility guarantee at constitutional level
- Result integrity guarantee at constitutional level
- Auditability guarantee at constitutional level
- Transparency guarantee

**The governance documents focus on operational trust decisions** (who is verified, eligible, authorized) **but do not specify election-specific cryptographic guarantees** (verifiability, integrity, auditability) at a governance level.

**Guarantee Categories:** Verifiability, Coercion Resistance, Result Integrity, Auditability, Transparency

**Confidence:** **HIGH** — Absence from governance documents is observable evidence. These guarantees may still exist as design intent without being documented in ADRs.

**Confidence Level:** **MEDIUM** — ADRs document the trust model but not broader election guarantees. D42B requires further investigation of external governance sources.

---

## 3. D22 — Constitutional Rule Origins

### Finding 3.1: Rules Originate from Architectural Decisions, Not External Governance

**Source:** ADR-001 (Constitutional Capability Sovereignty)

**Evidence:**
The constitutional rules system (ElectionConstitution.RULES) is explicitly an architectural design choice:

> "Capability authority is centralized and non-negotiable."
> "Backend is the sole authority."
> "Capabilities are immutable snapshots."

The 12 lifecycle states, transition rules, roles, and preconditions are documented in architecture ADRs (ADR-001 Constitutional, ADR-003 Lifecycle vs Phase) — not in organizational governance documents.

**Confidence:** **HIGH** — ADRs explicitly state the design decisions. No external governance source exists in the examined repository.

---

### Finding 3.2: The "Counting" State Is a Lifecycle State with Meaning

**Source:** ADR-003 (Lifecycle vs Phase Projection), lines 29-47, 148-172

**Evidence:**
The 12 lifecycle states are explicitly defined as constitutional runtime truth. The "counting" state is listed as the 8th state in the constitutional progression order (line 158). It is NOT a placeholder — it is one of 12 defined constitutional states.

From ADR-003 (line 159-160):
```
8. counting
9. results_published
```

The ADR notes that the `results_pending` phase name is provisional:
> "results_pending: Status is provisional. Neither a runtime lifecycle state nor a fully stable projection concept. May be renamed to tabulation or counting_phase."

This indicates that "counting" is a meaningful constitutional state that separates voting from results publication, but its implementation (immediate result generation at vote time) has diverged from its constitutional meaning (a distinct phase after voting closes).

**Confidence:** **HIGH** — The state is explicitly part of the constitutional lifecycle definition. The gap between state meaning and implementation behavior is documented.

---

### Finding 3.3: Suspension Is an Operational Governance Overlay

**Source:** ADR-003 (Lifecycle vs Phase Projection), lines 161-174, ADR-001 (Constitutional Capability Sovereignty) transition rules

**Evidence:**
SUSPENDED is explicitly defined as an operational overlay state, not a linear progression state:

> "SUSPENDED — Operational overlay state. Not a lifecycle progression state. Freezes capabilities only. Pre-suspension position restoration deferred."

> "projectionAvailable: false for SUSPENDED"

This confirms the code comment from Stream 5 that describes suspension as "operational governance overlay."

**Confidence:** **HIGH** — ADR explicitly documents the overlay semantics.

---

### Finding 3.4: No External Governance Source Found for Rules

**Source:** All examined ADRs, design docs, architecture decisions, governance records (Rounds 14-17)

**Evidence:**
The examined repository contains:
- 5 architecture ADRs (Constitutional Capability Sovereignty, Frontend ACB, Lifecycle vs Phase, Deterministic Resolver, Projection Sovereignty)
- 3 trust domain ADRs (Trust Attestation, Verified ≠ Eligible ≠ Authorized, Governance Driven Revocation)
- 1 voting security ADR
- Trust domain documentation (Ubiquitous Language, Trust Chain, Bounded Contexts)

None of these documents reference an external governance source (e.g., organizational bylaws, election commission regulations, legal requirements) as the basis for the constitutional rules.

**Possible interpretations (all unresolved):**
1. Rules were derived from platform architects' domain knowledge
2. Rules reflect organizational governance that exists outside the repository
3. Rules evolved organically through implementation iterations
4. Rules are derived from legal or regulatory requirements not referenced in the repository

**Confidence:** **MEDIUM** — Absence of references in examined documents is observable. External sources may exist outside the repository.

---

## 4. D30 — Rules-in-Code Intent

### Finding 4.1: Rules-in-Code Is Intentional Design

**Source:** ADR-001 (Constitutional Capability Sovereignty), ADR-004 (Deterministic Capability Resolver)

**Evidence:**
The ADRs explicitly establish the following design principles:

**Constitutional Capability Sovereignty (ADR-001 lines 22-27):**
> "Backend is the sole authority — All permission checks originate from the backend capability resolver."
> "Capabilities are immutable snapshots — API returns a point-in-time snapshot of allowed/denied actions."

**Deterministic Resolver (ADR-004 lines 24-30):**
> "Resolver is a deterministic pure function: same inputs always produce same outputs."
> "The resolver receives ALL context as arguments (never calls infrastructure)."

**Forbidden calls (ADR-004 lines 52-58):**
- ❌ auth() or auth()->user()
- ❌ now() or Carbon::now()
- ❌ Eloquent queries
- ❌ Facade calls
- ❌ Any I/O

This design is intentionally centralized, deterministic, and side-effect-free. The rules are embedded in code because:
1. **Determinism requires pure functions** — External configuration would introduce non-determinism
2. **Centralized authority** — One resolver, one truth
3. **Testability** — Unit tests are simple: pass arguments, assert result
4. **Immutability** — Rules cannot be changed at runtime without deployment

**Confidence:** **HIGH** — Multiple ADRs explicitly document this as intentional design.

---

### Finding 4.2: No Rule Engine or Configuration Mechanism Is Planned

**Source:** All examined ADRs

**Evidence:**
None of the examined ADRs reference:
- A planned rule engine
- External configuration for rules
- Runtime rule management
- Tenant-specific rule customization
- Rule versioning or migration

The absence of such references in 8 ADRs and multiple design documents suggests the current in-code approach is intentional, not transitional.

**Confidence:** **MEDIUM** — Absence from ADRs is observable but does not prove no plans exist outside examined documents.

---

### Finding 4.3: Stub Implementation (capacity_eligibility) Is the Exception, Not the Rule

**Source:** ConstitutionalTransitionGuard.php, comparison against ADR documentation

**Evidence:**
Most constitutional rules are fully implemented and documented in ADRs. The capacity_eligibility stub (forced true for paid plans) is an exception that suggests one of:
- Incomplete feature (payment integration pending)
- Deliberately deferred business logic (payment system separate from constitutional rules)
- Temporary placeholder while business model is finalized

The stub does NOT indicate that the entire constitutional rules system is transitional — the ADRs clearly document the intentional design.

**Confidence:** **MEDIUM** — The stub is real but context (payment integration) is unrelated to constitutional rule design.

---

## 5. Updated Discovery Debt

### D42B — Updated

**Status:** Partially resolved by governance evidence.

**What is now known:**
- Vote anonymity is an explicit intended guarantee (ADR_20260203)
- Verified ≠ Eligible ≠ Authorized is an explicit trust model (ADR-001, ADR-002)
- Governance context owns consequence decisions (ADR-003)
- Constitutional capability sovereignty is intentional (ADR-001 Constitutional)

**What remains unknown:**
- Whether universal verifiability is an intended guarantee
- Whether end-to-end verifiability (E2E-V) is required
- Whether specific cryptographic guarantees are needed
- What external regulatory or legal requirements apply
- Organizational governance policies beyond architectural decisions

**Recommended Action:** Evaluate whether architectural ADR evidence is sufficient, or whether external governance sources (stakeholder interviews, election regulations, organizational governance documents) are required.

---

### D22 — Updated

**Status:** Partially resolved by governance evidence.

**What is now known:**
- Rules are based on architectural design decisions documented in ADRs
- 12 lifecycle states are defined with constitutional meaning
- "Counting" state is an explicit constitutional state with meaning
- Suspension is an operational governance overlay
- No external governance source referenced in examined documents

**What remains unknown:**
- Whether rules were ever derived from external governance sources
- Whether organizational bylaws or regulations influenced rule design
- Whether rule evolution has been tracked

**Recommended Action:** Evaluate whether ADR documentation of rule origins is sufficient for current governance understanding, or whether external validation is required.

---

### D30 — Updated

**Status:** Resolved by governance evidence.

**What is now known:**
- Rules-in-code is INTENTIONAL design (not temporary)
- Design documented across ADR-001 Constitutional, ADR-004, ADR-005
- Rationale: determinism, centralized authority, testability, immutability
- No rule engine or configuration mechanism is planned in examined documents
- Stub implementation (capacity_eligibility) is an exception related to payment integration, not constitutional rule design

**What remains unknown:**
- Whether future architectural evolution might change this approach
- Whether tenant-specific customization is planned

**Confidence:** HIGH — Multiple ADRs explicitly document intentional design.

---

## 6. Updated Hypotheses

### H19 (Constitutional Rules Represented in Code) — Updated

**Previous Status:** Strengthening

**New Status:** Strengthening (confirmed with governance evidence)

**Evidence added (Tier 3 — ADRs):**
- ADR-001 (Constitutional Capability Sovereignty): "Backend is sole authority"
- ADR-004 (Deterministic Resolver): Pure function design
- Rules-in-code is intentional, not temporary

---

### H20 (Rule Origin Unknown) — Updated

**Previous Status:** Open

**New Status:** Strengthening (rule origin is architectural, not external governance)

**Evidence added (Tier 3 — ADRs):**
- Rules documented in architecture ADRs with design rationale
- No external governance source referenced in examined documents
- Rules are based on architectural decisions, not external governance

---

### H22 (Challenge Mechanisms Organizational) — Updated

**Previous Status:** Open

**New Status:** Strengthening (revocation consequences explicitly delegated to governance context, not software automation)

**Evidence added (Tier 3 — ADR-003):**
- ADR-003 explicitly states Governance Context owns revocation consequences
- Consequences are governance decisions, not software automation
- Supports the finding that challenge/dispute handling may be organizational

---

## 7. Governance Evidence Sufficiency Assessment

### What Is Sufficiently Evidenced

| Question | Answer | Source | Confidence |
|----------|--------|--------|------------|
| Is vote anonymity intended? | Yes — fundamental requirement | ADR_20260203 | HIGH |
| Is trust compositional? | Yes — Verified ≠ Eligible ≠ Authorized | ADR-001, ADR-002, UBIQUITOUS_LANGUAGE | HIGH |
| Who decides revocation consequences? | Governance Context | ADR-003, TRUST_CHAIN | HIGH |
| Are rules embedded intentionally? | Yes — constitutional sovereignty | ADR-001 Const., ADR-004 | HIGH |
| What are lifecycle states? | 12 defined constitutional states | ADR-003 Lifecycle | HIGH |
| Is counting state meaningful? | Yes — 8th constitutional state | ADR-003 Lifecycle | HIGH |

### What Requires Further Investigation

| Question | Gap | Potential Source |
|----------|-----|-----------------|
| Universal verifiability intended? | Not addressed in examined ADRs | Stakeholder interviews |
| E2E-V required? | Not addressed in examined ADRs | Election regulations, stakeholder interviews |
| External governance sources? | No references in repository | Organizational bylaws, legal review |
| Voting and tallying as separate concerns? | Implementation coupled; state machine separates | D39 resolution needed |

### Governance Evidence Gap Classification

- **Repository-governance evidence gap:** ADRs document architectural intent but not external governance sources. D30 is resolved. D42B and D22 are partially resolved.
- **Known governance gap:** Election integrity guarantees beyond trust model and anonymity are not documented in examined sources.
- **Acceptable gap for Step 3?** D30 can close. D42B and D22 are partially resolved with architectural ADR evidence. Whether this is sufficient depends on ARB's tolerance for provisional context boundaries.

---

**Round 18 Governance Discovery Findings — READY FOR ARB REVIEW**

**Governance sources examined:** 8 ADRs + 3 trust domain documents + 4 architecture decision records

**Debt resolution:** D30 resolved. D42B partially resolved. D22 partially resolved.

**Next step after ARB review:** Determine whether governance evidence is sufficient for Candidate Context Discovery authorization, or whether further non-repository discovery (stakeholder interviews, external governance documents) is required.
