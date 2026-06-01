# Evidence Bounded Context — Aggregate Discovery

**Status:** DD.4 — Discovery Candidate (Ready for ARB Review)  
**Input:** EvidenceScenarioCatalog.md (8 scenarios, frozen)  
**Next Phase:** Phase 0B ARB Review, then DD.5 Scenario Analysis (requires real evidence data)  
**Note:** Critical discoveries D1, D2, D3 remain open; require validation during DD.5

---

## Purpose

This document extracts aggregate hypotheses from the Scenario Catalog using disciplined DDD discovery:

```
Scenarios (EvidenceScenarioCatalog.md)
         ↓
Invariant Ownership Matrix
         ↓
Consistency Boundaries
         ↓
Aggregate Hypotheses (derived, not assumed)
```

---

## Step 1: Invariant Ownership Matrix

Extracted from the 8 scenarios in EvidenceScenarioCatalog.md, these invariants are assigned to candidate owners.

**Legend:**
- **High confidence:** Invariant ownership is clear from scenarios
- **Medium confidence:** Likely owner, but scenarios don't fully exercise it
- **Low confidence:** Owner is speculative; requires DD.5 validation
- **TBD:** Ownership uncertain; requires discovery

| # | Invariant | Candidate Owner | Confidence | Scenario Evidence |
|---|-----------|-----------------|-----------|------------------|
| **EVI-1** | Evidence frozen at evaluation time | Evidence Snapshot Boundary | High | SC-03, SC-04, SC-07 — evidence used but never modified |
| **EVI-2** | Observations are flat (no ordering/ranking) | Evidence Snapshot Boundary | Medium | SC-02, SC-04 — no cross-scenario ordering implied |
| **EVI-3** | No raw PII in observations | Evidence Preservation Policy | High | SC-01..SC-08 — consistently excludes user_id, emails, raw IPs |
| **EVI-4** | Classification is factual, not scalar | Evidence Classification Policy | Medium | SC-03, SC-04 — binary facts (changed/unchanged), not trust scores |
| **EVI-5** | No indirect voter re-identification | Privacy Protection Policy | High | SC-01..SC-08 — scrutinizes field combinations; region+time+device checked |
| **I-1** | Evidence immutable after publication | Evidence Snapshot Boundary | High | SC-05, SC-08 — closure/publication events don't alter evidence |
| **I-2** | Observation language separated from evaluation | Evidence Domain Language | High | SC-03, SC-04 — facts (fingerprint_hash) distinct from judgments (suspicious) |
| **I-3** | Evaluation state is not authority | Evaluation Context (not Evidence) | High | SC-06, SC-07 — Evaluation proposes, Legitimacy decides |
| **VR-1** | Evidence independently verifiable | Future Verification Context | Low | SC-07 — audit re-derives decision; suggests future independent verification |
| **VR-2** | Verification without voter identity | Privacy + Future Verification | Low | SC-07 — audit uses facts alone, no voter_id needed |
| **VR-3** | Verification non-alteration | Future Verification Context | Low | SC-07 — evidence read-only during audit |
| **VR-4** | Verification authority separate from governance | Future Context Boundary | Low | Implied by SC-08; explicit separation deferred |
| **VR-5** | Verification supports replay | Future Verification Context | Low | SC-07 mentions replay but deferred to DD.6+ |

---

## Step 2: Observation Clusters (Not Yet Consistency Boundaries)

Which fields/events form natural groupings? Note: **These are observation clusters, not yet validated as consistency boundaries.** A true consistency boundary answers "What must never become inconsistent?" — these answer "What observations arrive together?"

### Discovered from Scenarios

#### Cluster A: Vote Submission Observations

**Fields observed together:**
- Device fingerprint hash
- Network location (anonymized)
- Session hash
- Voting code hash
- Submission timestamp

**Scenarios:**
- SC-03: Vote submitted successfully
- SC-04: Anomalous submission detected

**Open consistency questions:**
- Must these fields be atomic? (Or can they be stored separately?)
- Do they form an aggregate boundary, or are they read-only references?
- Which field is the identity?

**Status:** Observation cluster discovered. Consistency boundary TBD until DD.5.

---

#### Cluster B: Election Window Observations

**Fields observed together:**
- Voting window open timestamp
- Opened-by authority
- Voting window close timestamp
- Closed-by authority

**Scenarios:**
- SC-03, SC-05: Voting window defines submission validity
- SC-08: Results publication implies closure

**Open consistency questions:**
- Must open and close events be atomic together?
- Are they separate aggregates or one?
- Can window be reopened? (Affects immutability)

**Status:** Observation cluster discovered. Consistency boundary TBD until DD.5.

---

#### Cluster C: Result Publication Observations

**Fields observed together:**
- Publication timestamp
- Published-by authority
- Election ID

**Scenarios:**
- SC-08: Result publication triggers external commitment

**Open consistency questions:**
- Are these observations (Evidence) or governance state (Governance)?
- Must they be atomic?
- Does Evidence own this, or only Governance?

**Status:** Observation cluster discovered. Ownership/boundary TBD until DD.5.

---

### Open Consistency Questions (for DD.5)

**Q1: Cross-election consistency?**
- SC-02 raises it, but ownership is TBD
- Does Evidence maintain cross-election state, or only Governance?

**Q2: History consistency?**
- SC-02, SC-07: Historical context needed?
- If yes, does Evaluation own it, or a separate Archive context?

**Q3: Immutability scope?**
- SC-05, SC-08: What becomes immutable after closure/publication?
- Just evidence? Evaluations? Legitimacy decisions? All?

---

## Step 3: Aggregate Hypotheses (Derived from Boundaries)

### Hypothesis A: Evidence Snapshot Consistency Boundary

**Identity:** Created at vote submission, frozen at evaluation completion

**Invariants it owns:**
- EVI-1 (frozen at evaluation)
- EVI-2 (flat observations)
- EVI-3 (no raw PII)
- EVI-4 (factual classification)
- EVI-5 (no indirect re-identification)
- I-1 (immutable after publication)
- I-2 (fact language)

**Proposed contents (NOT final):**
```
Evidence Snapshot
  ├─ voting_code_hash (reference, not content)
  ├─ device_fingerprint_hash
  ├─ network_location_previous
  ├─ network_location_current
  ├─ session_hash
  ├─ submission_timestamp
  ├─ integrity_verification_mechanism (TBD — NOT constitutionalHash yet)
  └─ occurred_at (frozen timestamp)
```

**Open questions (for DD.5):**
- Is one snapshot per vote, or per voter per election, or per evaluation request?
- Does cross-election evidence belong here, or separate?
- Does candidate selection (vote content) belong in Evidence, or separately?

**Tentative boundaries:**
- **Owns:** Immutable snapshot of device/network/timing facts
- **Does NOT own:** Candidate selections, voter identity, evaluation results
- **Exposes:** Read-only query for audit/dispute scenarios (SC-07)

---

### Hypothesis B: Evaluation Envelope Consistency Boundary

**Identity:** Created when evaluation completes; one per voter per election (suspected)

**Authority:** Evaluation Context (NOT Evidence Context)

**Invariants it owns:**
- I-3 (evaluation state is not authority)
- EVI-4 (factual classification)
- VR-1, VR-2, VR-3 (future verification concerns)

**Proposed contents (NOT final):**
```
Evaluation Envelope
  ├─ Evidence Snapshot reference (read-only)
  ├─ Evaluation result (SUFFICIENT / INSUFFICIENT / REVIEW_REQUIRED / INCONCLUSIVE)
  ├─ Classification (Initial / Attested / ContinuityProven / RegistrarConfirmed)
  ├─ Reason codes (typed, not scalar)
  └─ Historical context reference (TBD ownership)
```

**Open questions (for DD.5):**
- Does Evaluation own historical context, or is that separate?
- Is envelope created once per evaluation, or can it be re-evaluated?
- Does envelope store original evaluation, or only final result?

**Tentative boundaries:**
- **Owns:** Interpretation of evidence (what it means)
- **Does NOT own:** Authority decision (that's Legitimacy)
- **Does NOT own:** Evidence facts (read-only reference)

---

### Non-Aggregate Observation: Constitutional Observation Context

**Type:** Flat value-object container (confirmed by scenarios)

**No lifecycle, no identity**
- Created by: Observation Context (upstream)
- Consumed by: Evidence Context (as input)
- Never owned by: Evidence Context

---

## Step 4: Authority Chain (Validated by Scenarios)

The 8 scenarios confirm the constitutional authority chain:

```
Observation Context (upstream)
         ↓ observes facts
Evidence Snapshot Boundary (Evidence Context)
         ↓ preserves facts
Evaluation Envelope (Evaluation Context)
         ↓ interprets facts
Legitimacy Context
         ↓ decides participation
Governance Context
         ↓ executes decisions & publishes
External Systems (downstream)
```

**Key rule:** Each context answers ONE question:
- **Observation:** What happened?
- **Evidence:** What observations were preserved?
- **Evaluation:** What do the preserved facts mean?
- **Legitimacy:** Can this voter participate?
- **Governance:** What operational decisions follow?

**Key invariant:** No feedback loops. Flow is one-way.

**Note on Evidence role:** Evidence does not evaluate trustworthiness — that is Evaluation's job. Evidence's sole responsibility is preservation: keeping observations immutable, accurate, and available for downstream contexts to interpret.

---

## Step 5: Rejected Aggregate Hypotheses

Important discoveries also include **what we rejected** and why. This prevents revisiting disproven ideas.

| Hypothesis | Reason for Rejection | Scenario Evidence |
|------------|---------------------|-------------------|
| **CrossElectionEvidenceAggregate** | Violates Evidence independence. Each election's evidence is separate; cross-election history belongs elsewhere (TBD). | SC-02 explicitly treats each election independently |
| **EvidenceEvaluationAggregate** | Evidence and Evaluation are separate bounded contexts with different responsibilities (preservation vs. interpretation). Cannot be the same aggregate. | SC-06, SC-07 — Evaluation consumes Evidence but does not modify it |
| **EvidenceGovernanceAggregate** | Governance consumes evidence for decisions but does not own evidence. One-way consumer relationship, not co-ownership. | SC-05, SC-08 — Governance reads audit facts but does not control them |
| **VoteContentInEvidenceAggregate** | Candidate selections and vote counts are governance/business data, not evidence of trustworthiness. Evidence preserves device/network/timing, not vote content. | SC-03, SC-04 — Evidence focuses on submission metadata, not selections |
| **SingleEvidenceAggregateForAllScenarios** | Scenarios suggest multiple observation clusters (vote submission, election window, results publication). Unlikely one aggregate owns all. | SC-03 (vote), SC-05 (window), SC-08 (publication) show distinct clusters |

---

## Critical Discoveries (for DD.5)

### Discovery D1: Historical Context Ownership = TBD

**Question:** Does voter history belong in Evidence, Evaluation, Legitimacy, or a separate Archive context?

**Impact:** Affects aggregate boundaries significantly
- If Evidence owns it: Evidence Snapshot becomes cross-election
- If Evaluation owns it: Evaluation Envelope becomes stateful
- If Archive owns it: New bounded context needed
- If TBD: Can't finalize aggregate boundary yet

**Action:** Resolve in DD.5 before finalizing aggregates

---

### Discovery D2: What Becomes Immutable?

**Question:** After result publication (SC-08), what is immutable?

**Candidates:**
- Evidence facts alone?
- Evidence + Evaluation results?
- Evidence + Evaluation + Legitimacy decisions?
- All of the above + Governance decisions?

**Impact:** Defines whether aggregate spans multiple contexts or is Evidence-only

**Action:** Resolve in DD.5 through scenario re-execution with real data

---

### Discovery D3: Vote Content vs Vote Metadata

**Question:** Is candidate selection evidence, or is it separate governance data?

**Current:** Scenarios focus on device/network/timing (metadata)
**Missing:** No scenarios explicitly model vote content handling

**Impact:** May reveal additional consistency boundaries for ballot data

**Action:** Add scenario for vote content handling in DD.5

---

## Exit Criteria for DD.4 → ARB Review → DD.5

**DD.4 Artifact is ready for ARB review when:**

```
✓ Invariant Ownership Matrix complete
✓ Observation Clusters identified (not yet consistency boundaries)
✓ Aggregate Hypotheses proposed (marked as hypotheses, not decisions)
✓ Rejected hypotheses documented with reasons
✓ Open questions clearly flagged
✓ Authority chain validated (with correct Evidence responsibility)
✓ Critical discoveries D1, D2, D3 documented for DD.5
```

**Checked. Ready for Phase 0B ARB Review.**

**IMPORTANT:** This is NOT "complete." Critical ownership questions (D1, D2, D3) remain open and require validation during DD.5 with real evidence data before final aggregate boundaries can be locked.

---

## Phase Deliverables Summary

### Phase 0A — Discovery (COMPLETE)

| Artifact | Status | Purpose |
|----------|--------|---------|
| EvidenceScenarioCatalog.md | ✅ APPROVED, FROZEN | 8 scenarios, no premature assumptions |
| EvidenceAggregateDiscovery.md | ✅ COMPLETE | Invariant → Boundary → Hypothesis |
| EvidenceEventTaxonomy.md | 📋 PENDING | Event inventory (extract from scenarios) |
| EvidenceContext.md | 📋 PENDING | Update with EVI-5 + Verification note |

### Phase 0B — ARB Review Checkpoint

Required approval before Phase 1:
- [ ] EvidenceAggregateDiscovery.md review
- [ ] Invariant matrix validated
- [ ] Aggregate hypotheses accepted as hypotheses, not decisions
- [ ] Open questions documented for DD.5

### Phase 1 — Discovery Instrumentation

**GATE:** Only after Phase 0B approval

- evidence_capture table (follows from Invariant Ownership Matrix)
- CapturedDomainEvent (infrastructure, not domain)
- DiscoveryEventStore (Eloquent model)
- EvidenceCaptureAdapter (wires to observed events)
- Tests (verify EVI-5 in captured data)

---

## Next Steps

1. **Freeze EvidenceScenarioCatalog.md** — Do not edit further
2. **Commit EvidenceAggregateDiscovery.md** (this document)
3. **Create EvidenceEventTaxonomy.md** — Extract observed/target events from scenarios
4. **Update EvidenceContext.md** — Add EVI-5 + Verification context note
5. **Proceed to Phase 0B ARB review** — All 4 docs submitted for approval
6. **After approval:** Begin Phase 1 Discovery Instrumentation

---

## Architecture Review Board Checkpoint

This document serves as the DD.4 Discovery Candidate for ARB review. 

**Key principle demonstrated:**

> Scenarios drove discovery of invariants.  
> Invariants revealed observation clusters.  
> Observation clusters suggest (but do not prove) consistency boundaries.  
> Only then are aggregates hypothesized, not assumed.  
> Critical unknowns are explicitly documented for DD.5 validation.

This is disciplined DDD discovery: asking good questions rather than premature answers.

**Status for ARB Review:** Ready. Critical discoveries flagged. Aggregate hypotheses marked as exploratory. No final decisions made.
