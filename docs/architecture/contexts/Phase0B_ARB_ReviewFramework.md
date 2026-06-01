# Phase 0B — Architecture Review Board Framework

**Purpose:** Four-lens review of Phase 0A Discovery artifacts  
**Lenses:** Ganesha (Clarity), Leonardo (Discovery), Krishna (Strategy), Shani (Invariants)  
**Input:** Five Phase 0A artifacts + Architect feedback  
**Output:** ARB Decision + Phase 1 clearance (or Phase 0A rework)

---

## Review Framework Overview

| Lens | Question | Disciplines |
|------|----------|-----------|
| **Ganesha** | Do we actually understand what Evidence Context is? | Clarity, Definition, Ubiquitous Language |
| **Leonardo** | What hidden assumptions remain unexamined? | Discovery, Assumptions, Hidden Complexity |
| **Krishna** | Why are we building this? Is the scope correct? | Strategy, Scope, Business Purpose |
| **Shani** | What invariants must never break under pressure? | Resilience, Core Rules, Non-negotiables |

---

## Lens 1: Ganesha — Clarity

### Question 1.1: What is Evidence?

**Current Definition:**
```
Evidence is immutable observed facts frozen at evaluation time,
preserved for constitutional verification and auditable decision-making.
```

**Artifacts Where This Appears:**
- EvidenceContext.md, Section 1 (Mission)
- EvidenceScenarioCatalog.md (SC-07: Replay Request)
- EvidenceEventTaxonomy.md (Constitutional Timeline)

**ARB Checkpoints:**
- [ ] Is this definition consistent across all five artifacts?
- [ ] Could a new developer explain it in one sentence?
- [ ] Does it distinguish Evidence from:
  - Audit Log? (Evidence is immutable; audit logs can be appended)
  - Event Store? (Event store records state changes; Evidence freezes facts)
  - Archive? (Archive stores; Evidence preserves immutably)
  - History? (History is narrative; Evidence is factual)

---

### Question 1.2: What does Evidence own?

**Current Answer:**
```
Evidence owns:
  ✓ Preserved facts (frozen at evaluation time)
  ✓ Observation classification (provenance, not trust)
  ✓ Evidence snapshots (hashed, replay-safe)

Evidence does NOT own:
  ✗ Interpretation (Evaluation)
  ✗ Authority decisions (Legitimacy)
  ✗ Governance actions (Governance)
  ✗ Verification authority (Future Verification Context)
```

**Artifacts Where This Appears:**
- EvidenceContext.md, Section 3 (Authority Ownership)
- EvidenceAggregateDiscovery.md (Step 4: Authority Chain)
- EvidenceInvariantOwnershipMatrix.md (I-3: Evaluation state is not authority)

**ARB Checkpoints:**
- [ ] Are the ownership boundaries defensible?
- [ ] Would a developer accidentally import from Legitimacy/Verification?
- [ ] Is the boundary clear enough to enforce in code reviews?

---

### Question 1.3: Can a new developer explain the constitutional chain?

**Current Chain:**
```
Observation (upstream)
      ↓
Evidence (freezes facts)
      ↓
Evaluation (interprets facts)
      ↓
Legitimacy (decides authority)
      ↓
Governance (executes decisions)

Supporting Contexts:
  Replay (validates evidence)
  Verification (future: independently verifies)
```

**ARB Checkpoints:**
- [ ] Does this chain appear consistently in all artifacts?
- [ ] Can the ARB diagram it from memory after this review?
- [ ] Are future contexts clearly marked as future?

---

## Lens 2: Leonardo — Discovery

### Question 2.1: What if our core assumption is wrong?

**Current Hypothesis:**
```
ConstitutionalEvidenceSnapshot
is the aggregate root.
```

**Leonardo's Challenge:**
```
What if there is no single aggregate?
What if Evidence is a collection of value objects?
What if the aggregate boundary is transactional, not semantic?
```

**Artifacts Affected:**
- EvidenceAggregateDiscovery.md, Hypothesis A
- EvidenceInvariantOwnershipMatrix.md (EVI-1, I-1, I-5)

**ARB Action Items:**
- [ ] Document why ConstitutionalEvidenceSnapshot is likely vs. speculative
- [ ] Identify which DD.5 scenario will test this hypothesis most aggressively
- [ ] What would falsify this hypothesis? (Scenario that breaks if it's wrong?)

---

### Question 2.2: What are we accidentally mixing?

**Recent Discovery:**
```
MISTAKE: Evidence contained evaluation language
  "suspicious"
  "device OK"
  "network OK"

CORRECTION: These are interpretations (Evaluation), not observations (Evidence)
```

**Artifacts Where This Appears:**
- EvidenceEventTaxonomy.md, Section 3 (Excluded Events)
- EvidenceContext.md, Section 5 (I-2: Observation language separated from evaluation)

**ARB Checkpoints:**
- [ ] Are there other mixed concepts hiding in Phase 0A?
- [ ] Could observation/evaluation still be mixed in `ConstitutionalObservationContext`?
- [ ] Should we explicitly test observation/evaluation separation in Phase 1?

---

### Question 2.3: What future contexts are we implicitly creating?

**Discovered Future Contexts:**
```
Verification Context (VR-1..VR-5 documented, not implemented)
Replay Context (already exists; uses ConstitutionalEvidenceSnapshot)
Evaluation Context (may split from Evidence during DD.5)
Archive Context? (History ownership TBD in D1)
```

**Artifacts Where This Appears:**
- EvidenceAggregateDiscovery.md (Step 4: Authority Chain)
- EvidenceContext.md, Section 9 (Future: Verification Context)
- EvidenceEventTaxonomy.md (Section 5: Known Unknowns K1-K5)

**ARB Checkpoints:**
- [ ] Are future contexts clearly marked as future (not Phase 1)?
- [ ] Do VR-1..VR-5 requirements constrain Evidence design?
- [ ] Should Evaluation be formalized as its own context now, or later?

---

## Lens 3: Krishna — Strategy

### Question 3.1: What business problem are we solving?

**Explicit Problem:**
```
NOT: "Store logs"
BUT: "Enable constitutional verification of election outcomes"
```

**Stakeholders Who Benefit:**
```
Voter (can dispute if denied)
Election Committee (can audit if challenged)
Auditor (independent re-derivation)
Observer (transparency)
Regulator (compliance verification)
Court (jurisdiction over disputes)
```

**Artifacts Where This Appears:**
- EvidenceContext.md, Section 1 (Mission)
- EvidenceScenarioCatalog.md (SC-07: Audit/Dispute Request)
- German Bundestag paper (CVP-1..CVP-6)

**ARB Checkpoints:**
- [ ] Does Evidence Context solve this problem, or only part of it?
- [ ] Are there stakeholders we haven't considered?
- [ ] Would a non-technical stakeholder understand why we need Evidence Context?

---

### Question 3.2: Should we build everything at once, or scope it?

**Current Decision:**
```
Phase 1: Evidence preservation instrumentation only
Phase DD.5: Aggregate boundaries (after scenario saturation)
Phase 1+: Evaluation integration
Phase 6+: Verification Context
```

**Alternative (rejected):**
```
Build Evidence + Evaluation + Verification all in Phase 1
```

**Reasoning:**
```
Scope: Too large
Risk: Wrong aggregates → expensive redesign
Timeline: Verification research incomplete (VR-1..VR-5 are requirements, not design)
```

**Artifacts Where This Appears:**
- EvidenceAggregateDiscovery.md (Phase 0B → Phase 1 → Phase DD.5 timeline)
- Plan file: read-and-understand-what-playful-lampson.md (Phase 1 scope)

**ARB Checkpoints:**
- [ ] Is Phase 1 scope correct (discovery instrumentation only)?
- [ ] Should Phase 1 wait for Evaluation design, or can they proceed in parallel?
- [ ] Is the timeline realistic for ARB expectations?

---

### Question 3.3: What is the wisest long-term architecture?

**Current Vision:**
```
Not a monolithic audit system,
but a layered constitutional infrastructure:

Observation → Facts captured
Evidence → Facts preserved
Evaluation → Facts interpreted
Legitimacy → Authority decided
Governance → Actions executed

with:
Replay (validate correctness)
Verification (independent re-derive)
Certification (attest to integrity)
Reporting (transparency for stakeholders)
```

**Artifacts Where This Appears:**
- EvidenceAggregateDiscovery.md, Step 4 (Authority Chain)
- EvidenceContext.md, Section 9 (Relationships diagram)
- EvidenceEventTaxonomy.md (Constitutional Timeline & Context Flow)

**ARB Checkpoints:**
- [ ] Is this vision achievable within project constraints?
- [ ] Does this vision align with regulatory requirements?
- [ ] Is Evidence Context positioned correctly within this vision?

---

## Lens 4: Shani — Invariants

### Question 4.1: What must remain true under pressure?

**Constitutional Invariants (Shani's Core Question):**

| Invariant | Must Never Become False | Why |
|-----------|------------------------|-----|
| **Evidence Immutable** | Evidence cannot be altered after evaluation | Disputes require unchanging facts |
| **Authority Cannot Rewrite** | Even governance cannot change frozen evidence | Prevents retroactive justifications |
| **Privacy Preserved** | Voter identity cannot be reconstructed | Election legitimacy requires anonymity |
| **Legitimacy Cannot Fabricate** | No outcome without evidence | Prevents authority abuse |
| **Verification Independent** | Verification authority separate from governance | Prevents conflicts of interest |

**Mapped to Invariant IDs:**
- EVI-1 (Evidence frozen) → Evidence Immutability
- EVI-5 (No indirect re-identification) → Privacy Preserved
- I-1 (Immutable after publication) → Authority Cannot Rewrite
- I-3 (Evaluation state is not authority) → Legitimacy Cannot Fabricate
- VR-4 (Verification authority separate) → Verification Independent

**Artifacts Where This Appears:**
- EvidenceInvariantOwnershipMatrix.md (all 18 invariants)
- EvidenceContext.md, Section 5 (I-1..I-8)
- EvidenceAggregateDiscovery.md (D1-D3 discoveries)

**ARB Checkpoints:**
- [ ] Can each invariant withstand fraud attempts?
- [ ] Can each invariant survive political pressure?
- [ ] Can each invariant be legally defended in court?
- [ ] Are enforcement mechanisms structural (code) or conventional (process)?

---

### Question 4.2: What happens when they're violated?

**Risk Analysis:**

| Invariant | If Violated | Detection | Recovery |
|-----------|-----------|-----------|----------|
| Evidence Immutability | Election outcome becomes disputable | Hash verification | Cryptographic proof of change |
| Authority Cannot Rewrite | Outcomes fabricated retroactively | Audit trail integrity | Court subpoena of evidence |
| Privacy Preserved | Voter identity reconstructed | Correlation analysis | Legal liability |
| Legitimacy Cannot Fabricate | False outcomes declared legitimate | Evaluation re-run | Election nullification |
| Verification Independent | Corruption of verification process | Independent auditor | Constitutional challenge |

**ARB Checkpoints:**
- [ ] For each invariant, who can detect violation?
- [ ] For each invariant, who can remediate?
- [ ] Are detection mechanisms built into Evidence Context?

---

### Question 4.3: Are all critical invariants enforced structurally?

**Enforcement Levels:**

| Invariant | Enforcement | Current | Need Improvement? |
|-----------|------------|---------|-------------------|
| EVI-1 (Evidence frozen) | Structural (readonly fields) | Proposed in Hypothesis | [ ] Yes |
| EVI-3 (No raw PII) | Ingestion (ACL) | Existing TrustEvidencePrivacyPolicy | [ ] Maybe |
| EVI-5 (No indirect re-id) | Ingestion (ACL + temporal fuzzing) | Proposed in Phase 1 | [ ] Yes |
| I-1 (Immutable after publication) | Structural (sealed aggregate) | Proposed in Hypothesis | [ ] Yes |
| I-3 (Evaluation not authority) | Architectural (separate context) | Need DD.5 validation | [ ] Yes |

**ARB Checkpoints:**
- [ ] Which invariants are enforced by code? By process? By policy?
- [ ] Should any process-enforced invariants become code-enforced?
- [ ] Are tests written to verify invariant enforcement?

---

## ARB Decision Template

### Summary Questions

1. **Ganesha:** Is Evidence Context clearly defined and differentiated?
   - [ ] Yes, proceed
   - [ ] Mostly, with clarifications needed
   - [ ] No, rework Phase 0A

2. **Leonardo:** Are the hidden assumptions identified and documented?
   - [ ] Yes, proceed to DD.5
   - [ ] Mostly, with tests planned
   - [ ] No, conduct deeper discovery

3. **Krishna:** Is the scope and strategy correct for Phase 1?
   - [ ] Yes, Phase 1 is ready
   - [ ] Mostly, with timeline adjustments
   - [ ] No, rethink Phase 1 scope

4. **Shani:** Are critical invariants protected and enforceable?
   - [ ] Yes, all structural
   - [ ] Mostly, with Phase 1 hardening
   - [ ] No, require design changes

---

### ARB Verdict Options

**Option A: APPROVED — Proceed to Phase 1**
```
Phase 0A artifacts satisfy all four lenses.
Phase 1 Discovery Instrumentation: CLEARED
Timeline: Proceed with evidence_capture table, EvidenceCaptureAdapter
Next checkpoint: DD.5 Scenario Saturation
```

**Option B: APPROVED WITH CONDITIONS**
```
Phase 0A mostly clear, but requires:
- [ ] Condition 1: _________________
- [ ] Condition 2: _________________
- [ ] Condition 3: _________________

Phase 1 may proceed with conditions met.
Next checkpoint: ARB validation of conditions
```

**Option C: REWORK REQUIRED**
```
Phase 0A artifacts require substantial revision:
- Rework: _________________
- Rework: _________________

Resubmit Phase 0A for ARB review.
Phase 1 blocked until approval.
```

---

## Post-Review Actions

**If APPROVED:**
1. Proceed to Phase 1 (evidence_capture + CapturedDomainEvent)
2. Implement EVI-5 sanitization in EvidenceCaptureAdapter
3. Run DD.5 scenario saturation (3+ elections, event stabilization)
4. Monthly checkpoint: Scenario saturation progress

**If APPROVED WITH CONDITIONS:**
1. Implement conditions (timeline: 1 week)
2. ARB sign-off on conditions
3. Proceed to Phase 1

**If REWORK REQUIRED:**
1. Rework Phase 0A (timeline: 2 weeks)
2. Resubmit to ARB for re-review
3. No Phase 1 work until re-approval

---

## Appendix: Quick Reference

**Five Phase 0A Artifacts:**
1. EvidenceScenarioCatalog.md (8 scenarios, immutable)
2. EvidenceAggregateDiscovery.md (hypotheses, invariants, rejected ideas)
3. EvidenceEventTaxonomy.md (observed + target events, ACL, temporal fuzzing)
4. EvidenceContext.md (mission, language, ownership, relationships)
5. EvidenceInvariantOwnershipMatrix.md (18 invariants mapped to owners)

**Architect Recommendations (acknowledged but pending ARB review):**
- Challenge 1: Evaluate whether Evaluation should become its own context (DD.5)
- Challenge 2: ConstitutionalEvidenceSnapshot remains hypothesis (not fact)
- Challenge 3: Rename `evidence_capture` to `captured_domain_events` (avoid accidental architecture)
