# Constitutional Vocabulary Doctrine (D.R.3.5)

**Purpose**: Prevent procedural vocabulary from recreating sovereignty leakage through semantic regression.

**Principle**: Language shapes topology. Terms encode mental models. Procedural vocabulary inevitably evolves into authority behavior.

---

## Forbidden Vocabulary (Universal Ban)

These terms CANNOT appear outside explicit sovereign layers:

### Authority-Implying Terms
- `allow`
- `deny`
- `grant`
- `authorize`
- `permit`
- `block`
- `restrict`
- `suspend`
- `revoke`
- `unlock`

### Control-Flow Terms (Dangerous)
- `continue` → Use: "no constitutional concern"
- `pass` → Use: concern level descriptor
- `fail` → Use: "constitutional inconsistency"
- `skip` → No equivalent (evidence complete always)
- `return` (from aggregation/evaluation) → Use: "preserve evidence"
- `bypass` → Never allowed

### Authority-Outcome Terms
- `verified` / `unverified` (as decision outcome)
- `trusted` / `untrusted` (as decision outcome)
- `eligible` / `ineligible`
- `qualified` / `disqualified`

---

## Allowed Vocabulary (By Layer)

### Domain Layer (Findings, Evidence)
✅ Allowed:
- `finding`
- `concern`
- `evidence`
- `consistency`
- `inconsistency`
- `concern_level`
- `evidence_weight`
- `constitutional_basis`

Example:
```php
// GOOD
return new ConstitutionalFinding(
    concernLevel: HIGH,
    evidenceWeight: WEAK,
    constitutionalBasis: 'device_continuity_uncertain',
);

// BAD
return new ConstitutionalFinding(
    isVerified: false,
    shouldDeny: true,
);
```

### Application Layer (Aggregation, Orchestration)
✅ Allowed:
- `aggregate`
- `compose`
- `orchestrate`
- `collect`
- `preserve`
- `lineage`
- `signal` (overlay signal)
- `influence` (overlay influence)

❌ Forbidden:
- `continue` (use: "no influence")
- `stop aggregation` (never - aggregate all)
- `prioritize` (use: "aggregate severity")

Example:
```php
// GOOD
public function aggregate(TrustCapabilityContext $ctx): OverlayInfluenceContext
{
    // Collect all signals, preserve lineage
    return $this->aggregateSignalsDescriptively($signals);
}

// BAD
public function aggregate(TrustCapabilityContext $ctx): OverlayInfluenceContext
{
    // Stop if review required
    if ($highest_concern > MEDIUM) break;
}
```

### Resolver Layer ONLY
✅ ONLY layer allowed to use authority vocabulary:
- `authority`
- `decision`
- `grant`
- `deny`
- `capability`
- `participation`

CRITICAL: Never used elsewhere.

Example:
```php
// GOOD - Resolver interprets findings into authority
if ($context->trust->snapshot->highestConcern === CRITICAL) {
    return CapabilityDecision::deny(...);
}

// BAD - Policy attempting authority
if ($finding->concernLevel === CRITICAL) {
    return VotingTrustResult::deny(...);
}
```

### Projection Layer (Snapshot, UI)
✅ Allowed:
- `trusted` (as display state only)
- `denied` (as display state only)
- `read-only rendering`
- `display`
- `show`

CRITICAL: Never derives authority. Only displays what Resolver decided.

---

## Vocabulary Migration Examples

### Test Name Migrations

**OLD**: `test_evaluate_returns_continue_when_stable`
**NEW**: `test_reports_no_constitutional_concern_when_device_stable`

Reason: "continue" implies control flow. "reports concern" describes what the finding does.

---

**OLD**: `test_policy_returns_deny_on_violation`
**NEW**: `test_policy_emits_critical_concern_on_constitutional_violation`

Reason: Policy emits findings (evidence), not authority decisions (deny).

---

**OLD**: `test_overlay_stops_aggregation_when_review_required`
**NEW**: `test_aggregation_preserves_all_signals_including_governance_escalation`

Reason: Coordinator aggregates everything. Never stops. Just preserves all evidence.

---

**OLD**: `test_verification_failed_returns_insufficient`
**NEW**: `test_verification_concern_elevated_when_attestation_unsatisfied`

Reason: Verification detected concern (evidence). Not failure (authority).

---

### Assertion Migrations

**OLD**:
```php
$this->assertTrue($result->denied);
$this->assertEquals('verification_failed', $result->reason);
```

**NEW**:
```php
$this->assertEquals(ConstitutionalConcernLevel::HIGH, $finding->concernLevel);
$this->assertEquals('attestation_unsatisfied', $finding->constitutionalBasis);
```

Reason: Assert on evidence properties, not authority outcomes.

---

## Critical Dangerous Patterns

### ❌ PATTERN 1: Soft Authority Through Recommendation

Dangerous:
```php
if ($overlay->recommendedPath === DEFER_TO_GOVERNANCE) {
    // implies: don't allow participation
}
```

Safe:
```php
$context->overlayInfluence->strongestPath === DEFER_TO_GOVERNANCE
// Just: governance handling recommended by evidence
// Resolver still decides participation
```

### ❌ PATTERN 2: Aggregation-as-Authority-Gate

Dangerous:
```php
if ($highest_concern > THRESHOLD) {
    stop_processing();  // Implicitly blocks participation
}
```

Safe:
```php
// Collect ALL concerns, preserve ALL evidence
$aggregated = $this->aggregateAllSignals($signals);
// Resolver interprets severity independently
```

### ❌ PATTERN 3: Finding Semantics as Decision

Dangerous:
```php
public function evaluate(...): ConstitutionalFinding {
    if ($condition) {
        return $this->denied();  // Implies authority
    }
}
```

Safe:
```php
public function evaluate(...): ConstitutionalFinding {
    if ($condition) {
        return new ConstitutionalFinding(
            concernLevel: CRITICAL,
            basis: 'network_continuity_violation',
        );
    }
}
```

---

## Semantic Governance Rules

### Rule 1: Findings ONLY Describe, Never Prescribe

✅ Descriptive (allowed):
- "network_velocity_elevated"
- "device_continuity_broken"
- "verification_evidence_absent"

❌ Prescriptive (forbidden):
- "block_participation"
- "deny_voting_right"
- "fail_authentication"

### Rule 2: Overlays Report Concerns, Not Orders

✅ Concern reporting (allowed):
- concern_level: CRITICAL
- procedural_path: DEFER_TO_GOVERNANCE (just info)
- suggested_elevation: RegistrarAttested (just info)

❌ Order issuance (forbidden):
- MUST defer to governance
- CANNOT proceed
- STOP evaluation

### Rule 3: Aggregation Preserves Reality

✅ Evidence complete (required):
- All signals collected
- All concerns preserved
- All evidence lineage intact

❌ Evidence selective (forbidden):
- Early exit when "review needed"
- Filtering signals by importance
- Stopping after first concern

### Rule 4: Resolver ONLY Derives Authority

✅ Resolver authority (allowed ONLY here):
- Interprets findings into decisions
- Derives participation capability
- Returns CapabilityDecision (deny/grant)

❌ Authority anywhere else (forbidden everywhere):
- Policies deciding deny/allow
- Overlays blocking participation
- Coordinators terminating evaluation

---

## Vocabulary Audit Checklist

Before committing code, verify:

- [ ] No policies contain `allow`, `deny`, `grant`, `authorize`
- [ ] No coordinators contain `continue` as control flow (only as data value)
- [ ] No policies contain short-circuiting logic (all context evaluation)
- [ ] No findings use outcome vocabulary (`verified`, `trusted`, `passed`)
- [ ] No aggregators use "priority determines outcome" semantics
- [ ] All test names describe EVIDENCE not AUTHORITY
- [ ] No "review required" implying participation block
- [ ] Resolver is only layer using `CapabilityDecision`, `grant()`, `deny()`
- [ ] All RecommendedProceduralPath usage is governance guidance, not soft authority
- [ ] Snapshot never contains decision-making logic (projection only)

---

## Constitutional Semantic Enforcement

### Automated Checks (CI/CD)

Forbidden in non-resolver code:
```
grep -r "allow\|deny\|grant\|authorize\|permit\|block" app/Domain/ app/Application/Election/Security/Policies/ app/Application/Election/Security/Overlays/
```

Should return zero matches (except comments explaining why forbidden).

### Manual Review Gates

Before PR merge:
1. **Vocabulary audit** of all changed terms
2. **Intent verification** - does language match constitutional role?
3. **Semantic consistency** - is this component using correct vocabulary layer?

---

## Long-Term Doctrine Evolution

This vocabulary doctrine is NOT static.

**Allowed Changes**:
- Adding more specific descriptive vocabulary
- Refining concern level names
- Clarifying edge cases

**Forbidden Changes**:
- Introducing authority vocabulary in non-resolver layers
- Re-introducing procedural control flow
- Creating "soft authority" through recommendation semantics

This doctrine protects against **semantic sovereignty regression**.
