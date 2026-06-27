# Evidence Context — Invariant Ownership Matrix

**Status:** Phase 0A Supporting Artifact  
**Purpose:** Map each domain invariant to its candidate owner (aggregate hypothesis, policy, or future context)  
**Input:** EvidenceContext.md (I-1..I-8) + EvidenceAggregateDiscovery.md  
**Use:** ARB Review checkpoint + DD.5 hypothesis validation  

---

## Invariant Ownership Matrix

| ID | Invariant | Candidate Owner | Owner Type | Confidence | Evidence | DD.5 Action |
|:---|-----------|-----------------|------------|------------|----------|-------------|
| **EVI-1** | Evidence frozen at evaluation time | ConstitutionalEvidenceSnapshot (hypothesis) | Aggregate Root | High | SC-03, SC-04, SC-07 — evidence never re-queried during evaluation | Validate: does snapshot enforce immutability? |
| **EVI-2** | Observations are flat (no ordering/ranking) | Evidence Preservation Aggregate | Invariant Policy | Medium | SC-02, SC-04 — no cross-scenario ordering implied | Validate: do observation collections enforce flatness? |
| **EVI-3** | No raw PII in observations | TrustEvidencePrivacyPolicy (existing) | Ingestion ACL | High | SC-01..SC-08 — consistently excludes user_id, emails, raw IPs | Validate: does ACL catch all PII variants? |
| **EVI-4** | Classification is factual, not scalar | Evidence Classification Policy | Policy Constraint | Medium | SC-03, SC-04 — binary facts (Initial/Attested/etc), not trust scores | Validate: can classification ever become scalar? Structural enforcement needed? |
| **EVI-5** | No indirect voter re-identification | Evidence Capture Anti-Corruption Layer | Ingestion Guard | High | EvidenceEventTaxonomy.md Section 4 — timestamp+device+region temporal fuzzing | Validate: does temporal bucketing adequately prevent correlation? |
| **I-1** | Evidence immutable after publication | ConstitutionalEvidenceSnapshot (hypothesis) | Aggregate Root | High | SC-05, SC-08 — closure/publication events don't alter evidence | Validate: is post-publication mutation structurally impossible? |
| **I-2** | Observation language separated from evaluation | Evidence Domain Language | Ubiquitous Language | High | SC-03, SC-04 — facts (fingerprint_hash) distinct from judgments (suspicious) | Validate: could observation language contaminate evaluation logic? |
| **I-3** | Evaluation state is not authority | Evaluation Context (separate BC) | Context Boundary | High | SC-06, SC-07 — Evaluation proposes, Legitimacy decides | Validate: should Evaluation be its own bounded context? |
| **I-4** | Reason codes are typed | EvaluationEnvelope (hypothesis) | Value Object | Medium | EvidenceEvaluationResult has typed EvaluationReasonCode | Validate: is stringly-typed reason creation impossible? |
| **I-5** | Evidence snapshots are replay-safe | ConstitutionalEvidenceSnapshot (hypothesis) | Aggregate Root | Medium | SC-07 — audit re-derives decision; requires deterministic hash | Validate: is hash deterministic across processes/hosts/runtimes? |
| **I-6** | Classification is factual, not scalar | Evidence Classification Policy | Policy Constraint | Medium | EvidenceClassification enum exists; no numeric conversion | Validate: is scalar conversion structurally prevented? |
| **I-7** | No raw PII in observations | TrustEvidencePrivacyPolicy (existing) | Ingestion ACL | High | Evidence context contains only hashed/minimized data | Validate: is PII hashing complete before domain processing? |
| **I-8** | No indirect voter re-identification | Evidence Capture Anti-Corruption Layer | Ingestion Guard | High | EvidenceEventTaxonomy.md — field combination analysis prevented | Validate: what is acceptable correlation risk tolerance? |
| **VR-1** | Evidence independently verifiable | Future Verification Context | Supporting Context (future) | Low | German Bundestag paper + IEEE research — requirement only | DD.6+: Design Verification Context aggregate |
| **VR-2** | Verification without voter identity | Future Verification Context | Supporting Context (future) | Low | Derived from EVI-5; Verification consumes snapshot anonymously | DD.6+: Enforce read-only + anonymous access |
| **VR-3** | Verification non-alteration | Future Verification Context | Supporting Context (future) | Low | Verification has no write authority over evidence | DD.6+: Access control layer design |
| **VR-4** | Verification authority separate from governance | Future Verification Context | Supporting Context (future) | Low | Verification is independent supporting context | DD.6+: Formalize Verification context boundary |
| **VR-5** | Verification supports replay | Future Verification Context | Supporting Context (future) | Low | Implies Verification has access to frozen snapshots + hash verification | DD.6+: Design replay integration |

---

## Ownership Confidence Legend

| Confidence | Meaning | ARB Decision |
|-----------|---------|--------------|
| **High** | Invariant ownership is clear from scenarios and existing code | ✅ Accept as likely; validate in DD.5 |
| **Medium** | Likely owner, but scenarios don't fully exercise it | ⚠️ Accept as hypothesis; test extensively in DD.5 |
| **Low** | Owner is speculative; belongs to future context | 🔄 Document as future; do not implement in Phase 1 |

---

## Aggregate Hypothesis Validation Checklist

**For each High/Medium confidence invariant:**

During DD.5 Scenario Analysis, verify:

- [ ] Invariant is actually enforced by the candidate owner
- [ ] Invariant cannot be violated if the owner is implemented
- [ ] No competing owner for the same invariant
- [ ] The owner's boundaries match the invariant's scope

---

## Known Ownership Questions (for ARB)

| Question | Impact | ARB Recommendation |
|----------|--------|-------------------|
| Should EVI-4 have structural enforcement, or is convention sufficient? | EvidenceClassification could accidentally become scalar | Add structural test in DD.5 |
| Is ConstitutionalEvidenceSnapshot truly immutable, or does it need setter guards? | I-1 effectiveness depends on implementation | Design with readonly fields or sealed class |
| Should Evaluation become its own bounded context, or remain inside Evidence? | I-3 suggests separate context, but current spec co-locates them | Revisit boundary during DD.5 |
| Is temporal bucketing (minute-level granularity) the right EVI-5 defense, or too aggressive? | EVI-5 enforcement trades precision for privacy | ARB: approve fuzzing strategy before Phase 1 |
| Do we need explicit replay hash versioning for Verification Context? | I-5/VR-5 intersection may require version tracking in evidence | Plan for DD.6 Verification design |

---

## Phase 1 Discovery Instrumentation — Invariant Enforcement

When Phase 1 capture adapter is built, **these invariants must be enforced at ingestion:**

| Invariant | Phase 1 Enforcement Point |
|-----------|--------------------------|
| **EVI-3** | Payload sanitization in EvidenceCaptureAdapter (strip user_id, email, raw_ip_address) |
| **EVI-5** | Temporal fuzzing + region-level geolocation in ingestion schema (NOT minute-level timestamp, NOT individual IP) |
| **I-2** | Never capture suspicion flags or evaluation language — only observations |
| **I-7** | Hash device fingerprint before capture (election-scoped salt) |

---

## Relationship to Phase 0A Artifacts

This matrix is **derived from** but **independent of** the four Phase 0A documents:

- **EvidenceContext.md** — Invariants I-1..I-8 (Section 5)
- **EvidenceAggregateDiscovery.md** — Invariant Ownership Matrix (Step 1)
- **EvidenceScenarioCatalog.md** — Scenario evidence columns (cross-reference)
- **EvidenceEventTaxonomy.md** — EVI-5 enforcement (Section 4)

---

## Next Steps

**Phase 0B ARB Review:**
- Approve/challenge each ownership assignment
- Decide on confidence levels (are any upgradeable? downgradeable?)
- Clarify ownership questions above
- Approve which invariants enforce Phase 1 discovery instrumentation

**Phase 1 (only after ARB approval):**
- Implement EVI-3, EVI-5, I-2, I-7 as ingestion guardrails
- Capture evidence_capture table data (discovery instrumentation, not domain model)
- Log violations for ARB visibility

**Phase DD.5:**
- Validate each invariant is actually enforced by its candidate owner
- Promote hypotheses to confirmed aggregates where evidence is strong
- Escalate any contradictions back to ARB
