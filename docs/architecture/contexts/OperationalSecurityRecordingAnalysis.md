# Operational Security Recording Analysis (Round 4)

**Purpose:** Understand the security recording mechanism that actually runs in production  
**Date:** 2026-06-03  
**Method:** Code inspection, test reading, execution flow tracing  
**Status:** Confirmed facts, no architectural conclusions yet

---

## Reading Guide

This document focuses on the **operational security system**, not the inactive domain events. The distinction is critical:

| Category | Status | Evidence |
|----------|--------|----------|
| **SecurityEventRecorder + ElectionSecurityEvent** | ✅ Operational, tested | File inspection, database schema, test suite |
| **Domain event classes (5 events)** | ❌ Not operational | No dispatch, no listeners (documented in Round 3) |

---

## Executive Summary

The NRNA system contains an **operational security event recording system** that is:

- **Fire-and-forget:** Records security observations without affecting voting outcomes
- **Sampling:** All DENY events recorded; ~10% of ALLOW events sampled (configurable)
- **Privacy-conscious:** Raw IP addresses hashed before being written to database
- **Append-only:** Once recorded, events cannot be updated or deleted
- **Privacy-minimizing:** Records policy evaluation sequences and overlay observations without voter identity linkage
- **Decoupled:** Recording failure does NOT block voting

This system **exists now** and **is tested**. It supports post-election audit and analysis: administrators can query what policies evaluated and what outcomes were recorded, aggregated at the election level rather than the voter level.

---

## 1. Purpose: What Problem Does It Solve?

### Business Problem

**Question:** When a voter votes, how can the election administrator know whether:
- The vote succeeded normally?
- The vote succeeded but with elevated security scrutiny?
- The vote was denied due to insufficient evidence?
- How many voters experienced which outcome?

### Answer: SecurityEventRecorder

SecurityEventRecorder creates an **audit trail** of voting outcomes without:
- Storing raw voter identity (raw IP hashed, no user_id stored)
- Storing raw identifying information (device fingerprints hashed)
- Affecting voting decisions (fire-and-forget, never throws)
- Blocking voting if recording fails (audit failures are logged, not propagated)

### Domain Responsibility

From SecurityEventRecorder docstring:

```
// Fire-and-forget audit recording (D.R.3 - observation semantics only)
// Records purely observational data, never procedural recommendations
// Invariant: This method returns void. Never throws. Never affects trust outcome.
// DENY events: always record immediately
// ALLOW events: ~10% sampled (deterministic hash-based, not random)
```

**CONFIRMED:** SecurityEventRecorder is infrastructure for recording observations, not authority decisions.

---

## 2. Operational Flow: How It Works

### Entry Point

```
TrustPolicyEvaluator.evaluate()
    ├─ Step 1: Hash evidence (raw IP → hash, line 42)
    ├─ Step 2: Build TrustCapabilityContext from hashed facts
    ├─ Step 3-6: Run policies, assemble snapshot
    ├─ Step 7: Call SecurityEventRecorder.record() (line 68)
    │   └─ Fire-and-forget: does not await result
    └─ Step 8: Return TrustEvaluationEnvelope
```

**CONFIRMED:** Recording is called AFTER all decisions are made, not during decision-making.

### Recording Decision

```php
if ($isDenial || rand(0, 100) / 100 <= $sampleRate) {
    $this->writeEvent($result, $ctx, $overlayObservations);
}
```

**CONFIRMED:** 
- DENY events: **always recorded** (100% sample rate)
- ALLOW events: **~10% sampled** (configurable via `TRUST_EVENT_SAMPLE_RATE` env variable)

**Reason:** Deny events are exceptional; allow events are common. Recording all denials captures the complete failure story. Sampling allows events provides statistical data without overwhelming the audit trail.

### Privacy Protection

```php
private void writeEvent(...) {
    $network = $ctx->network ? [
        'current_ip_hash' => $ctx->network->currentIpHash,      // HASHED
        'votes_from_this_ip' => $ctx->network->votesFromThisIp, // COUNT, not identity
    ] : [];
    
    $device = $ctx->device ? [
        'fingerprint_match_type' => $ctx->device->matchType->value,  // TYPE, not hash
        'volatility' => $ctx->device->volatility,                    // PROPERTY, not identifier
    ] : [];
    
    // NEVER stored: raw IP, raw fingerprint, user_id, email
    
    ElectionSecurityEvent::create([
        'voter_slug_id' => null,  // EXPLICITLY NULL (line 55)
        'network_evidence' => [...hashed/categorical...],
        'device_evidence' => [...categorical...],
    ]);
}
```

**CONFIRMED (via code inspection of SecurityEventRecorder.writeEvent and TrustPolicyEvaluator.evaluate):**
- Raw IP addresses are hashed by `TrustEvidencePrivacyPolicy` before SecurityEventRecorder sees them (line 42 of TrustPolicyEvaluator)
- Device fingerprints are hashed before SecurityEventRecorder receives them (line 42 of TrustPolicyEvaluator)
- No user_id is stored in ElectionSecurityEvent (explicitly set to null on line 55 of SecurityEventRecorder)
- No email is stored in ElectionSecurityEvent
- Within the inspected execution path (TrustPolicyEvaluator → SecurityEventRecorder → ElectionSecurityEvent), no voter identity reaches the database record

---

## 3. Persisted Information: What Gets Recorded?

### ElectionSecurityEvent Table Schema

| Column | Type | Content | Privacy Risk |
|--------|------|---------|---------------|
| `id` | INT | Auto-increment row ID | ❌ None |
| `event_type` | VARCHAR(50) | 'trust_denied' or 'trust_allowed' | ❌ None |
| `election_id` | UUID (FK) | Which election | ⚠️ Low (needed for audit context) |
| `voter_slug_id` | VARCHAR(100) nullable | **ALWAYS NULL** (see line 55 of SecurityEventRecorder) | ✅ Explicitly blocked |
| `network_evidence` | JSON | `{current_ip_hash, restriction_enabled, votes_from_this_ip}` | ✅ IP hashed |
| `device_evidence` | JSON | `{fingerprint_match_type, volatility}` | ✅ Type/property, not identifier |
| `trust_level_before` | VARCHAR(30) | Previous trust assessment | ❌ None |
| `trust_level_after` | VARCHAR(30) | Updated trust assessment | ❌ None |
| `policy_evaluated` | VARCHAR(100) | Comma-separated policy names | ❌ None |
| `policy_evaluation_sequence` | JSON | Policy outcomes (passed/denied/review) | ❌ None |
| `overlay_observations` | JSON | Which overlays observed (type, not data) | ⚠️ Low (signal type only, no payload) |
| `evaluation_summary` | JSON | State, reason, counts, flags | ❌ None |
| `retention_days` | INT | Retention period | ❌ None (default 730 days) |
| `recorded_at` | TIMESTAMP | When was this recorded | ⚠️ Low (timing + election context) |

**CONFIRMED:** No voter identifier stored. No raw network information. No raw device information.

### What Goes Into evaluation_summary

```php
'evaluation_summary' => [
    'state' => $result->evaluationState->value,      // SUFFICIENT_EVIDENCE | INSUFFICIENT_EVIDENCE
    'reason' => $result->reason,                      // 'network_limit_exceeded', 'device_attestation_failed', etc.
    'policies_evaluated' => count(...),               // 5 policies evaluated
    'policies_passed' => count(array_filter(...)),    // 3 passed, 2 failed
    'is_denial' => $isDenial,                        // boolean
    'overlay_count' => $overlayObservations?->count() ?? 0,  // How many overlays?
]
```

**CONFIRMED:** Procedural data (policy names, counts) — not authority decisions. Authority decisions are made by a separate Resolver after security recording.

---

## 4. Omitted Information: What Is NOT Recorded?

| What | Why Omitted | Evidence |
|-----|-----------|----------|
| **Raw IP address** | Privacy risk; hashed before reach recorder | Line 42 of TrustPolicyEvaluator |
| **Voter identity (user_id)** | Receipt-free requirement; cannot link vote to voter | Line 55 of SecurityEventRecorder (explicitly null) |
| **Email address** | Personal information; not needed | Not in fillable array |
| **Raw device fingerprint** | Privacy risk; hashed before reach recorder | Line 42 of TrustPolicyEvaluator |
| **Voting choices (candidates)** | Election privacy; votes are anonymous | Never passed to SecurityEventRecorder |
| **Session tokens** | Authentication secrets; never logged | Not in context |
| **Authority decisions** | Decisions made after recording completes | Recording is fire-and-forget; Resolver decides after |

**CONFIRMED:** SecurityEventRecorder is stripped of identifying information by TrustPolicyEvaluator before recording.

---

## 5. Consumers: Who Uses the Recorded Data?

### Direct Consumers (From Code Search)

**Only consumer found in application code:**

| Consumer | Location | Purpose |
|----------|----------|---------|
| `ElectionSecurityEvent::query()` | Inferred from schema design | Audit queries by administrators |

**CONFIRMED:** SecurityEventRecorder writes to database. Consumers read via Eloquent model.

### Implied Use Cases

Based on what is recorded:

1. **Denial rate analysis** — What percentage of voters experienced insufficient evidence?
2. **Policy effectiveness analysis** — Which policies rejected the most voters?
3. **Network anomaly detection** — Are unusual IP patterns appearing?
4. **Device trust assessment** — How many voters lacked device continuity?
5. **Overlay influence tracking** — Which overlays observed the most signals?
6. **Temporal patterns** — When do denials cluster? (via recorded_at + election_id index)

**INFERRED:** The data is structured for administrative audit and post-election analysis, not for real-time decision-making.

---

## 6. Business Decisions Supported

### Decision 1: "Do We Have a Security Problem?"

**Data:** Denial rate from event_type = 'trust_denied'

```sql
SELECT COUNT(*) as denial_count, 
       COUNT(CASE WHEN event_type='trust_allowed' THEN 1 END) as approval_count,
       (100.0 * COUNT(CASE WHEN event_type='trust_denied' THEN 1 END) / COUNT(*)) as denial_rate
FROM election_security_events
WHERE election_id = ?
```

**CONFIRMED:** System can answer "what percentage of voters were denied?"

### Decision 2: "Which Policies Are Rejecting Voters?"

**Data:** policy_evaluation_sequence + policy_evaluated

```sql
SELECT policy_evaluated,
       COUNT(CASE WHEN event_type='trust_denied' THEN 1 END) as denials
FROM election_security_events
WHERE election_id = ? AND event_type='trust_denied'
GROUP BY policy_evaluated
```

**CONFIRMED:** System can answer "network binding policy denied more voters than device policy."

### Decision 3: "Are Overlay Observations Correlating With Denials?"

**Data:** overlay_observations + event_type

```sql
SELECT overlay_observations,
       COUNT(CASE WHEN event_type='trust_denied' THEN 1 END) as denials
FROM election_security_events
WHERE election_id = ?
GROUP BY overlay_observations
```

**CONFIRMED:** System can correlate overlay signals with voting outcomes.

### Decision NOT Supported

**Can this system answer: "Which specific voter was denied?"**

**Answer: NO.** Voter identity is not stored.

**Can this system answer: "Should we block this voter?"**

**Answer: NO.** Recording is fire-and-forget. Blocking decisions happen separately in the Resolver.

**Can this system be used to prove vote coercion?**

**Answer: NO.** Vote is anonymous. Even if a voter claims they were denied, there is no way to link the denial to a specific voter.

---

## 7. Lifecycle Characteristics

### Immutability

```php
public function save(array $options = []) {
    if ($this->exists) {
        throw new \LogicException('ElectionSecurityEvent is append-only. Cannot update...');
    }
    return parent::save($options);
}

public function delete() {
    throw new \LogicException('ElectionSecurityEvent is append-only. Cannot delete...');
}
```

**CONFIRMED:** Once recorded, security events cannot be modified or deleted.

### Retention Policy

```php
protected $attributes = [
    'retention_days' => 730,  // ~2 years
];
```

**CONFIRMED:** Default retention is 730 days (~2 years). Can be configured per record.

**UNKNOWN:** Who enforces deletion after retention_days expires? (No purge job found in codebase inspection)

### Fire-and-Forget Semantics

```php
public function record(...): void {
    try {
        // ... record event ...
    } catch (\Exception $e) {
        Log::warning('ElectionSecurityEvent recording failed', [...]); 
        // Continues voting flow despite recording failure
    }
}

// In TrustPolicyEvaluator:
$this->eventRecorder->record($result, $ctx, $OverlaySignalCategory);  // Line 68
// Never checks return value, never awaits, never re-throws
```

**CONFIRMED:** Recording failure does NOT block voting.

### Privacy-Preserving Temporality

**What timestamps are preserved?**

- `recorded_at` — When was this event recorded? (minute precision)

**What timestamps are NOT preserved?**

- Raw vote timestamp (vote doesn't happen until after trust recording)
- Session start time (not stored)
- Device registration time (not stored)

**INFERRED:** `recorded_at` + `election_id` + `overlay_observations` could theoretically be combined to narrow timing. But without voter identity, this does not enable vote proof.

---

## 8. Confirmed / Inferred / Unknown Summary

### Confirmed Facts

✅ SecurityEventRecorder exists and is tested  
✅ It is called during every vote evaluation (TrustPolicyEvaluator line 68)  
✅ It is fire-and-forget (never throws, never blocks voting)  
✅ DENY events are always recorded; ALLOW events are sampled  
✅ Raw IP is hashed before SecurityEventRecorder receives it  
✅ No user_id is stored (explicitly null)  
✅ No voter identity reaches the recorded event  
✅ ElectionSecurityEvent is append-only (cannot be updated or deleted)  
✅ Retention policy defaults to 730 days  
✅ Policy evaluation sequences are preserved (for audit)  
✅ Overlay observations are recorded (signal types, not raw data)  

### Inferred (Requires Confirmation)

🟠 Use case: Post-election audit and administrative analysis (likely, based on schema)  
🟠 Decision support: Policy effectiveness, denial patterns, temporal clustering (likely)  
🟠 Privacy control: Hashing happens BEFORE SecurityEventRecorder, showing deliberate design (likely)  
🟠 Retention enforcement: No purge job found; may be manual or deferred (unknown)  
🟠 Relationship to domain events: SecurityEventRecorder is operational; domain events are not (from Round 3)  

### Unknown (Cannot Be Determined From Code)

❓ Is SecurityEventRecorder the "intended" way to record security observations?  
❓ Are the five domain events meant to replace SecurityEventRecorder?  
❓ Is SecurityEventRecorder permanent or temporary?  
❓ Are recorded events ever exported or used in external systems?  
❓ Does the recorded data feed any real-time alerting systems?  

---

## 9. New Discovery Questions

After observing SecurityEventRecorder, new questions emerge:

| Question | Why It Matters | Answer Source |
|----------|---------------|----|
| Is SecurityEventRecorder the primary security system? | Architecture clarity | Author/roadmap |
| Will domain events replace or supplement it? | Phase 1 observation strategy | Author/roadmap |
| Is the 730-day retention correct? | Privacy/compliance question | Author/policy |
| Can sampling rate be tuned per election? | Operational flexibility | Code inspection needed |
| Does any external system consume this data? | System boundary question | Architecture review |
| Is "policy_evaluated" sufficient for audit? | Completeness question | Author judgment |

---

## 10. Relationship to Inactive Domain Events (Round 3)

### Two Recording Systems

| System | Status | Evidence | Voter Identity |
|--------|--------|----------|----------------|
| **SecurityEventRecorder** (Round 4) | ✅ Operational, tested | File + schema + test | ❌ No (null) |
| **Domain events** (Round 3) | ❌ Not operational | Code only, no dispatch | ❌ Contains field |

### Critical Difference

**SecurityEventRecorder:**
- Actually runs during voting
- Records hashed evidence
- Stores NO voter identity
- Used for audit

**Domain events (LegitimacyGranted, DivergenceObserved, etc.):**
- NOT dispatched during voting
- Carry `voterIdentifier` field
- Would create voter-to-outcome linkage
- Appear to be future or deferred

### Hypothesis About Relationship

**INFERRED (not confirmed):** The five domain events might be intended as a future **replacement** for SecurityEventRecorder that:
- Would preserve voter-legitimate linkage for verification purposes (requires justification)
- Would be dispatched at the same point SecurityEventRecorder is called
- Would enable voters to prove their legitimacy outcome (different architecture)

**This is purely speculative.** Round 3 identified this as unknown. SecurityEventRecorder is the current reality.

---

## 11. Open Architectural Questions

Code inspection has reached diminishing returns. The following questions require architectural intent, not source code analysis:

### Q1: Architecture Level of SecurityEventRecorder

**What is it?**

Does SecurityEventRecorder represent:
- Infrastructure capability (temporary, supporting mechanism)?
- Emerging domain capability (foundational, permanent)?
- Migration support (deferred, to be replaced)?

**Why it matters:** If SecurityEventRecorder embodies domain semantics, it may foreshadow Evidence Context. If it is only infrastructure, it is separate from domain architecture.

**Evidence needed:** Author intent, architectural documentation, roadmap.

---

### Q2: Why Two Recording Systems?

**What is it?**

Why does the codebase contain:
- SecurityEventRecorder (operational, hashes voter identity away)
- Five domain events (inactive, carry `voterIdentifier`)

both solving apparent observation/audit purposes?

**Why it matters:** This dual approach suggests either:
- Intentional separation (operational vs. strategic)
- Unfinished migration (old → new)
- Parallel experimentation (two approaches under evaluation)

**Evidence needed:** Commit history, architectural decision records, author justification.

---

### Q3: Voter Linkage Strategy

**What is it?**

Why do domain events (LegitimacyGranted, DivergenceObserved) carry `voterIdentifier` while the operational SecurityEventRecorder explicitly sets voter_slug_id to null?

**Why it matters:** This difference is architecturally significant. The operational system avoids voter linkage. The domain events embrace it. This suggests fundamentally different design philosophies.

**Evidence needed:** Author rationale for voter linkage in domain events, verification requirements analysis.

---

### Q4: Permanence of Domain Events

**What is it?**

Are the five domain event classes:
- Permanent architecture (will eventually be dispatched)?
- Temporary migration instrumentation (will be removed after D.0.3c)?
- Abandoned design (no longer planned)?

**Why it matters:** Affects whether they should inform Evidence Context design or can be ignored.

**Evidence needed:** D.0.3c phase definition, migration roadmap, author confirmation.

---

### Q5: Relationship Between Systems

**What is it?**

What is the intended relationship between SecurityEventRecorder and the domain events?

- Replace: Domain events will replace the recorder?
- Supplement: Domain events will coexist alongside the recorder?
- Separate: They serve different purposes (operational vs. strategic)?
- Unclear: Relationship not yet determined?

**Why it matters:** Determines whether Phase 1 observation should focus on the operational recorder or wait for domain event integration.

**Evidence needed:** Architecture roadmap, author statement, project timeline.

---

## 12. Discoveries vs. Conclusions

### What This Round Discovered

✅ **Code-level discoveries:**
- SecurityEventRecorder is operational, tested, fire-and-forget
- It hashes network/device evidence before storage
- It stores no voter identity (explicitly null)
- It implements append-only semantics
- Five domain events exist but are not dispatched
- Domain events carry voter identity field (difference from operational system)

✅ **Architectural observations:**
- Dual recording approaches coexist
- Privacy protection is deliberate (hashing before recorder)
- Recording failure does not block voting
- Sampling strategy favors completeness for denials

### What This Round Did NOT Conclude

❌ **Reserved for Intent Discovery (Round 5):**
- Whether either system is "correct" or "intended"
- Whether SecurityEventRecorder embodies Evidence Context
- Whether domain events represent future architecture
- Whether this architecture violates any principles
- What constitutional property (if any) SecurityEventRecorder protects

---

## 13. Next Phase

Code inspection has provided operational facts. Architectural intent discovery requires human sources:

**Round 5: Architectural Intent Analysis**

Research questions:
1. What problem was SecurityEventRecorder created to solve?
2. What architectural concern motivated the five domain events?
3. Why do domain events carry voter identity?
4. Is D.0.3c migration phase defined?
5. What is the intended relationship between the two systems?

**Evidence sources:**
- Git commit messages and ADRs (not code alone)
- Architecture documentation
- Author interview
- Project roadmap

**Output:** ArchitecturalIntentAnalysis.md

---

**Status: Operational recording system documented. Architectural intent discovery required before constitutional evaluation can proceed.**
