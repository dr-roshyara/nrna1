# Evidence Context — Scenario Catalog

**Status:** DD.4 Phase 0A — Discovery Draft  
**Purpose:** Map 7 foundational election flows to validate context boundaries and invariants.  
**Usage:** These scenarios will be tested in Phase 1 (instrumentation capture) and DD.5 (scenario analysis).

**Critical Principle:** Invariants only live in paths. An invariant is abstract until you see it exercised in an actual transaction. These scenarios are where invariants become concrete.

---

## Scenario Structure

Each scenario shows:

```
Context A → Event → Observation → Evidence Created → Context B Consumes → Authority Decision
```

Each scenario answers:

- Which contexts participate?
- What evidence is created?
- What evidence is consumed?
- Which invariants are exercised?
- What must remain consistent?
- **EVI-5 Check:** Can voter identity be reconstructed from stored fields, alone or in combination?

---

## SC-01: Voter Enrollment (Election Administration Context)

**Given:** Election is scheduled, user is eligible (via Membership Context), Election Administration context assigns user to election.

**Authority Owner:** Election Administration Context (NOT Evidence Context)

**Flow:**

```
Election Administration Context
    ↓ VoterAssignedToElection (governance decision: "this user may vote in this election")
Evidence Context
    ↓ Evidence Recorded (optional; some events may be observed, not all)
Membership/Governance
    → Enrollment complete
```

**Role of Evidence Context:**

Evidence Context does NOT initiate enrollment. It MAY record that enrollment occurred, for audit purposes.

**Evidence Recorded (Optional):**

```
aggregate_reference: "election:{election_id}"
source_context: "ElectionAdministration"
event_type: "VoterAssignedToElection"
payload: {
  election_id,
  organisation_id,
  assigned_by,
  // NO user_id (EVI-5)
  // NO detailed assignment reason (that's Membership/Admin's policy)
}
occurred_at: timestamp
```

**Invariants Exercised:**

- **EVI-3:** No raw PII; no user_id stored
- **EVI-5:** No indirect voter re-identification via election + timestamp alone (enrollment is bulk operation, many voters assigned simultaneously)

**Questions for DD.5:**

- Should Evidence Context even record voter enrollment?
- Or does enrollment stay entirely in Election Administration context?
- If recorded: when? Immediately or deferred?

**Boundary Validation:**

```
Election Administration owns: "User is eligible for this election" (governance decision)
Evidence owns: "If we record enrollment, capture only audit facts" (optional observer)

SC-01 is primarily an Administration scenario, not an Evidence/Evaluation scenario.
Evidence participation is optional and secondary. ✓
```

---

## SC-02: Voter Participation Across Elections

---

## SC-02: Voter Participation in New Election (Cross-Election Pattern)

**Given:** User participated in previous election, now participates in new election. Eligibility status unchanged.

**Authority Owner:** Election Administration Context (initial assignment); Evidence + Evaluation (voting observation)

**Flow:**

```
Election Administration Context
    ↓ VoterAssignedToElection (second time, for new election)
Evidence Context
    ↓ Evidence Recorded (this election's observations)
    ↓ Does NOT access previous election's evidence
Evaluation Context
    ↓ Evaluates THIS election's evidence only
    ↓ Does it need historical context? (TBD)
Legitimacy Context
    ↓ Decision based on current evaluation
```

**Evidence Created:**

```
aggregate_reference: "election:{election_id}"
source_context: "Election"
event_type: "VoterAssignedToElection"
payload: {
  election_id,        // THIS election
  organisation_id,
  assigned_by,
  // Does NOT include previous_election_id
  // Evidence is per-election, not cross-election
}
occurred_at: timestamp
```

**Invariants Exercised:**

- **EVI-2:** Observations are flat within an election; no cross-election ordering stored here
- **EVI-5:** Each election's evidence is independent; cross-election correlation is Evaluation/Legitimacy's concern, not Evidence's

**Questions for DD.5:**

- **Historical context ownership: TBD** — Does Evaluation own it? Legitimacy? A separate Archive/History context?
- If historical context is needed: is it queried from Evidence repository, or does Evaluation maintain its own history?
- **Aggregate boundary:** Per-election or cross-election aggregate?

**Boundary Validation:**

```
Evidence owns: "Facts for THIS election only"
Evaluation owns: "Interpretation of THIS election's evidence"
             (Does NOT own historical context yet — TBD)
Legitimacy owns: "Participation decision based on evaluation"

Cross-election historical context ownership is deferred to DD.5.
Does not contaminate Evidence Context. ✓
```

---

## SC-03: Vote Submission (Happy Path)

**Given:** Voter authenticated, voting window open, candidate selection submitted.

**Authority Owner:** Evidence Context (observes device/network facts); Evaluation Context (interprets as "trustworthy")

**Flow:**

```
Observation Context
    ↓ ObservationRecorded (device fingerprint captured, network continuity measured)
Evidence Context
    ↓ Evidence Captured (raw facts: device hash, network continuity, session hash)
Evaluation Context
    ↓ Trust evaluation: "All signals indicate legitimate submission"
Legitimacy Context
    ↓ GRANTED (vote is eligible for counting)
Governance
    → Vote stored in ballot
```

**Evidence Created:**

```
aggregate_reference: "observation_record_TBD"  // Aggregate identity TBD until DD.5
source_context: "Voting"
event_type: "VoteSubmitted"
payload: {
  election_id,
  voting_code_hash,               // Hashed code (not raw code)
  device_fingerprint_hash,        // One-way hash of device
  network_continuity_value,       // Measurement (not judgment "OK")
  session_hash,                   // Hash of session state
  submission_timestamp,
  // NO user_id
  // NO IP address (not captured; too high-res for EVI-5)
  // NO MAC address (device-level PII)
  // NO candidate selections (vote content is separate)
}
occurred_at: timestamp
```

**Invariants Exercised:**

- **EVI-1:** Evidence captured at submission time and frozen
- **EVI-3:** No raw PII (device hash, not device ID; code hash, not code)
- **EVI-5:** No high-resolution identifiers; network_continuity_value is ordinal, not IP
- **I-2:** Observations are facts (fingerprint captured, continuity measured); judgments ("OK") belong in Evaluation

**Critical Design Decisions:**

- **Observation language:** "device_fingerprint_hash", "network_continuity_value" (facts)
- **NOT evidence language:** "device_ok", "network_trust_score", "signal_green" (these are evaluation)
- **Candidate selections:** Stored separately (voting content is not evidence of trustworthiness; trustworthiness is)

**Questions for DD.5:**

- Should candidate_ids be stored in evidence_capture at all, or only in a separate votes table?
- Is device_fingerprint_hash deterministic (same device → same hash)? If yes, can it be correlated across elections? (EVI-5 risk)
- What is network_continuity_value? (Ordinal scale? Continuous? Binary?)
- When is evidence frozen? (At submission? At election close?)

**Boundary Validation:**

```
Evidence owns: "Device hash, network continuity value, session hash captured at this time"
             (Pure facts, no judgments)
Evaluation owns: "These facts indicate vote is submitted by legitimate participant"
             (Interpretation of facts)
Legitimacy owns: "Legitimate vote = valid participation"
             (Authority decision)

Boundary is clean. Evidence language is fact-based. ✓
```

---

## SC-04: Anomalous Vote Submission (Unusual Pattern Detected)

**Given:** Vote submitted, but network/device pattern differs from baseline.

**Authority Owner:** Evidence Context (observes facts); Evaluation Context (interprets as anomaly); Governance/Policy (decides threshold)

**Flow:**

```
Observation Context
    ↓ ObservationRecorded (IP location changed, device changed, time delta unusual)
Evidence Context
    ↓ Evidence Captured (facts: previous_location, current_location, time_delta, device_changed)
Evaluation Context
    ↓ Anomaly evaluation: "Pattern diverges from baseline"
Governance/Policy Context
    ↓ Policy evaluation: "Pattern exceeds threshold for rejection"
Legitimacy Context
    ↓ DENIED (vote rejected; investigation initiated)
```

**Evidence Created:**

```
aggregate_reference: "observation_record_TBD"  // Aggregate identity TBD until DD.5
source_context: "Voting"
event_type: "VoteSubmitted"
payload: {
  election_id,
  voting_code_hash,
  device_fingerprint_hash,        // Current device
  previous_device_hash,           // Previous device (if available)
  network_location_previous,      // Previous region/geolocation (anonymized)
  network_location_current,       // Current region/geolocation (anonymized)
  submission_time_delta_seconds,  // Time since last submission in this election
  // NO raw IP addresses
  // NO detailed device IDs
  // NO user_id
}
occurred_at: timestamp
```

**Invariants Exercised:**

- **EVI-3:** No raw PII; locations are anonymized (region, not address)
- **EVI-5:** In low-turnout elections, region + time + device + location could re-identify. Governance policy must account for this.
- **I-2:** Observations are facts (device changed, location changed, time delta); anomaly judgments belong in Evaluation
- **I-6:** Classification is factual (not scalar risk score); thresholds are policy, not fact

**Critical Design Decisions:**

- **Observation language:** "device_fingerprint_changed", "location_changed", "time_delta_seconds" (facts)
- **NOT observation language:** "suspicious", "anomaly", "high_velocity" (these are evaluation/policy)
- **Thresholds:** "time_delta > 300 seconds = anomalous" is POLICY, not evidence or evaluation

**Questions for DD.5:**

- Who decides the time_delta threshold? (300 seconds? 60 seconds? Policy Context? Governance?)
- What is "anonymized location"? (Region? City? Postal code? Must respect EVI-5)
- Can voter appeal anomaly-based rejection? (Audit mechanism needed)
- **EVI-5 Risk:** In 10-voter election, does location + time + device = unique identification? If yes, violates EVI-5.

**Boundary Validation:**

```
Evidence owns: "Device changed, location changed, time delta measured"
           (Pure facts, no thresholds)
Evaluation owns: "These facts constitute anomalous pattern"
           (Interpretation against baseline)
Policy/Governance owns: "Anomalous pattern exceeds rejection threshold"
           (Policy decision; not evidence or evaluation)

Boundary is clear if thresholds are moved to Policy Context. ✓
```

---

## SC-05: Voting Window Closes (Governance Decision)

**Given:** Election schedule says voting closes at 6 PM. Time reaches 6 PM or election administrator manually closes.

**Authority Owner:** Governance Context (OWNS this decision; Evidence merely records it)

**Flow:**

```
Governance Context
    ↓ Decision: "Voting is now closed"
    ↓ VotingClosed event issued
Evidence Context
    ↓ Evidence Recorded (optional; observes that closure occurred)
Governance
    → No new votes accepted after this timestamp
    → Results frozen for counting/certification
```

**Evidence Recorded (Optional):**

```
aggregate_reference: "election:{election_id}"
source_context: "Governance"
event_type: "VotingClosed"
payload: {
  election_id,
  closed_at: timestamp,
  closed_by: admin_id,            // WHO closed it (audit only)
  // Does NOT include final vote count (that's governance state, not evidence)
  // Does NOT include IP logs (not evidence of closure)
}
occurred_at: timestamp
```

**Invariants Exercised:**

- **EVI-3:** Audit trail captured (who closed, when); no sensitive data
- **I-2:** VotingClosed is a governance fact (what happened), not an evaluation

**Critical Role Clarification:**

- **Evidence Context:** Records that closure occurred (audit trail); does NOT initiate closure
- **Governance Context:** Owns the decision to close voting; Evidence Context is a consumer/observer

**Questions for DD.5:**

- Is Evidence Context's role purely observational here, or does it participate in determining "closure"?
- Should VotingClosed trigger an `EvidenceFrozen` event from Evidence Context? (Signals that all evidence for this election is immutable)
- Is final vote count an evidence artifact or governance artifact?

**Boundary Validation:**

```
Evidence owns: "If we record it: audit facts of when/who closed"
          (Optional observer role)
Evaluation owns: "N/A"
Legitimacy owns: "N/A"
Governance owns: "Authority decision to close voting"

SC-05 is primarily a Governance scenario.
Evidence participation is secondary and optional. ✓
```

---

## SC-06: Legitimacy Granted / Denied (Authority Decision)

**Given:** Evaluation completed on voter's evidence. Legitimacy Context decides participation status.

**Flow (Granted):**

```
Evidence Context
    ↓ Evidence collected and frozen
Evaluation Context
    ↓ EvaluationCompleted (all checks pass)
Legitimacy Context
    ↓ GRANTED (voter may participate)
Governance
    → Vote counted in results
```

**Flow (Denied):**

```
Evidence Context
    ↓ Evidence collected and frozen
Evaluation Context
    ↓ EvaluationCompleted (suspicious flags present)
Legitimacy Context
    ↓ DENIED (voter may NOT participate)
Governance
    → Vote not counted; investigation initiated
```

**Evidence Created:** None new. Evidence Context is a consumer here, not a creator.

**Critical Question for DD.5:**

- **Does Evidence Context emit a `LegitimacyGranted` event?** NO. (Legitimacy Context does.)
- **Can Evidence Context emit an `EvidenceFrozen` event?** YES. (Signals that evidence is immutable.)

**Invariants Exercised:**

- **I-3:** Evaluation state (as input to legitimacy) is NOT authority
- **EVI-1:** Evidence is frozen before legitimacy decision
- **VR-4:** Evidence Context has no write authority over Legitimacy decisions

**Boundary Validation:**

```
Evidence owns: "Facts observed and preserved"
Evaluation owns: "Interpretation of facts"
Legitimacy owns: "Participation decision based on evaluation"

Each context has ONE responsibility. Boundaries are clear. ✓
```

---

## SC-07: Audit / Dispute Request (Investigation)

**Given:** Election completed. Voter disputes legitimacy decision, OR administrator initiates audit of a specific vote.

**Authority Owner:** Governance Context (disputes), Evidence Context (provides audit trail), Evaluation Context (re-interprets)

**Flow (Voter Disputes Legitimacy Decision):**

```
Governance Context (Dispute Handler)
    ↓ DisputeOpened (election_id, voter, reason)
Evidence Context
    ↓ Retrieve frozen evidence from SC-03 or SC-04 (immutable)
Evaluation Context
    ↓ Re-evaluate same evidence under same rules
Legitimacy Context
    ↓ Re-derive decision (should match original if evaluation logic is deterministic)
Governance
    ↓ Compare: original decision vs re-evaluation
    → If match: dispute denied; if mismatch: investigation
```

**Evidence Consumed (Not Created):**

```
Evidence retrieved (read-only, immutable):
{
  aggregate_reference: "election:{election_id}:{voting_code_hash}",
  source_context: "Voting",
  event_type: "VoteSubmitted",
  payload: {
    device_fingerprint_hash,
    network_location_previous,
    network_location_current,
    submission_time_delta_seconds,
  },
  occurred_at: original_timestamp
}
```

**Invariants Exercised:**

- **EVI-1:** Evidence is immutable; audit uses frozen snapshot unaltered
- **EVI-5:** Evidence contains no voter identity; audit works on facts alone
- **I-3:** Re-evaluation does not change evidence; evidence is read-only
- **I-4:** Reason codes are typed; audit re-produces same typed reasons as original

**Questions for DD.5:**

- Should Evidence Context store a hash of each record for integrity verification during audit?
- Is re-evaluation deterministic (same input → same output)? If not, audit is ambiguous.
- Who initiates disputes? (Voter? Governance? Verification Context?)
- Audit trail: Should we log that an audit occurred, or keep audits outside Evidence Context?

**Note on Future Replay (DD.6+):**

This SC-07 is **audit/dispute** appropriate for DD.4. Future DD.6 work will add **Replay Context** for deterministic verification with hashing, timeline reconstruction, and cryptographic proof. SC-07 does not assume that future Replay capability.

**Boundary Validation:**

```
Evidence owns: "Immutable snapshot of facts; available for audit"
Evaluation owns: "Re-derives decision from frozen evidence"
Governance owns: "Dispute decision authority; audit orchestration"

Clear one-way flow: Evidence → Evaluation → Governance (no feedback).
Does not assume future Replay infrastructure. ✓
```

---

## SC-08: Election Result Published (Governance Authority Finalization)

**Given:** Voting closed, votes counted, results audited. Governance declares results official and publishes them.

**Authority Owner:** Governance Context (OWNS publication decision; Evidence records it)

**Flow:**

```
Governance Context
    ↓ Decision: "Results are official and published"
    ↓ ResultsPublished event issued
Evidence Context
    ↓ Evidence Recorded (optional; audit trail of publication)
Legitimacy Context
    ↓ N/A (results are governance decision, not legitimacy evaluation)
External Systems
    ↓ Results available to electorate, observers, regulatory bodies
    ↓ No changes allowed after publication (immutable)
```

**Evidence Recorded (Optional):**

```
aggregate_reference: "election:{election_id}"
source_context: "Governance"
event_type: "ResultsPublished"
payload: {
  election_id,
  published_at: timestamp,
  published_by: admin_id,
  // Audit trail only
  // Does NOT include result_summary (that is Governance state, not Evidence)
  // Does NOT include vote counts (Governance responsibility)
  // Does NOT include individual vote results (Governance/public data)
  // Does NOT include voter breakdowns (privacy)
}
occurred_at: timestamp
```

**Invariants Exercised:**

- **EVI-3:** Audit trail (who published, when); no PII
- **I-2:** Publication is a governance fact, not an evidence interpretation

**Critical Boundary Questions:**

- **What is "immutable" after publication?** (Vote counts? Legitimacy decisions?)
- **Evidence/Governance split:** Does Evidence Context own "count verification", or only Governance?
- **Can results be revised?** (If yes, where are revisions stored? In Evidence? In Governance?)
- **Certification:** Is publication the final step, or does certification come after?

**Questions for DD.5:**

- Should ResultsPublished trigger an event from Evidence Context (to seal all prior evidence)?
- Does Evidence Context provide a "verification interface" for external auditors to check vote counts against evidence?
- What evidence supports vote counting itself? (Is vote-counting auditable via Evidence records?)
- Future: What is the relationship between Results Publication and Certification?

**Boundary Validation:**

```
Evidence owns: "Audit trail of publication fact"
          (Optional observer role)
Legitimacy owns: "N/A (results are governance, not legitimacy)"
Governance owns: "Authority decision to publish results; immutability guarantee"

SC-08 is primarily a Governance scenario.
Evidence participation is audit-trail only. ✓
```

---

## Cross-Scenario Invariant Saturation Check

After all 7 scenarios, verify invariant coverage:

| Invariant | SC-01 | SC-02 | SC-03 | SC-04 | SC-05 | SC-06 | SC-07 | Coverage |
|-----------|-------|-------|-------|-------|-------|-------|-------|----------|
| **EVI-1** (Evidence frozen at evaluation) | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | 7/7 |
| **EVI-2** (Observations flat, no order) | ✓ | ✓ |  |  |  |  |  | 2/7 |
| **EVI-3** (No raw PII in observations) | ✓ | ✓ | ✓ | ✓ |  |  |  | 4/7 |
| **EVI-4** (Classification is factual) |  |  | ✓ | ✓ |  |  |  | 2/7 |
| **EVI-5** (No indirect re-identification) | ✓ | ✓ | ✓ | ✓ |  |  |  | 4/7 |
| **VR-1** (Independently verifiable) |  |  |  |  |  |  | ✓ | 1/7 |
| **VR-2** (No voter identity in verification) |  |  |  |  |  |  | ✓ | 1/7 |
| **VR-3** (Verification non-alteration) |  |  |  |  |  |  | ✓ | 1/7 |
| **I-1** (Evidence immutable after publication) |  |  |  |  | ✓ | ✓ | ✓ | 3/8 |

**Observations:**

- **High coverage:** EVI-1, EVI-5, EVI-3, I-2 are well-exercised
- **Medium coverage:** I-1, I-3 covered in some scenarios
- **Low coverage:** VR-1, VR-2, VR-3 only in audit scenario; future Verification Context will expand these
- **SC-08 added:** Election Results Published (governance boundary clarified)

**Recommendation for DD.5:**

Add SC-08 (Results Published) if accounting invariants matter, or defer to Governance Context specification.

---

## Aggregate Boundary Hypotheses (Derived from Scenarios)

### Hypothesis A: ConstitutionalEvidenceSnapshot (Evidence Context)

**Evidence from scenarios:**

- SC-01, SC-02: Evidence created at voter assignment
- SC-03, SC-04: Evidence created at vote submission with device/network trust
- SC-06: Evidence is read-only input to legitimacy decision
- SC-07: Evidence is frozen and hashed for replay verification

**Tentative aggregate boundary:**

```
Aggregate: ConstitutionalEvidenceSnapshot

Owns:
  - VerificationEvidence (device, network)
  - NetworkEvidence (continuity, velocity)
  - ParticipationEligibilityEvidence (voter history?)
  - evaluatedAt (timestamp)
  - constitutionalHash (for replay)

Consistency rule:
  Evidence is immutable after creation.
  Hash is deterministic (same input → same hash).

Open questions (for DD.5):
  - Cross-election history: owned by Evidence or Evaluation?
  - Candidate selections: stored in evidence or stored separately?
  - Aggregate per voter per election, or per vote?
```

### Hypothesis B: EvaluationEnvelope (Evaluation Context, NOT Evidence)

**Evidence from scenarios:**

- SC-06: Evaluation interprets evidence (Legitimacy makes decision)
- SC-07: Evaluation re-evaluates under same rules

**Tentative aggregate boundary:**

```
Aggregate: EvaluationEnvelope (owns by Evaluation Context)

Owns:
  - EvidenceEvaluationResult (checks passed/failed)
  - EvidenceClassification (initial, attested, verified)
  - EvaluationReasonCode (typed reasons for decision)

Does NOT own:
  - Evidence itself (read-only reference to Evidence Context)
  - Legitimacy decision (that's Legitimacy Context's job)

Consistency rule:
  Evaluation is idempotent; re-evaluation must produce same result
  if evidence is unchanged.

Open questions (for DD.5):
  - Is Evaluation per-voter per-election, or per-vote?
  - Does Evaluation store historical results, or only latest?
```

---

## Next Steps

1. **Review this catalog with Architecture Review Board.**
   - Do the 7 scenarios capture the domain correctly?
   - Are boundary questions answered or deferred appropriately?
   - Are invariants exercised in the right scenarios?

2. **After ARB approval:**
   - Create `EvidenceAggregateDiscovery.md` (hypotheses derived from scenarios)
   - Create `EvidenceEventTaxonomy.md` (observed events extracted from scenario steps)
   - Update `EvidenceContext.md` (refinements from discovery)

3. **In Phase 1:**
   - Use these scenarios to validate evidence_capture schema
   - Verify that captured data matches scenario expectations

4. **In DD.5 (Scenario Analysis):**
   - Run all 7 scenarios against real evidence data
   - Confirm aggregate boundaries match hypotheses, or adjust
   - Identify any missing scenarios or invariants
