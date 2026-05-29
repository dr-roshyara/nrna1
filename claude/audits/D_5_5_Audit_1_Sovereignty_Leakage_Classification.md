# Audit 1 — Sovereignty Leakage Classification
**Date:** 2026-05-26
**Scope:** Complete codebase authority classification
**Status:** COMPLETE

---

## Executive Summary

Comprehensive classification of all 180+ authority-adjacent codebase locations into three categories:

| Classification | Count | Status |
|---|---|---|
| **CANONICAL SOVEREIGN** ✅ | 1 | Correct location for all authority |
| **ILLEGAL SOVEREIGN** ⚠️ | 2 | Must be removed in D.6 |
| **PROJECTION** ✅ | 15+ | Correct usage of resolved authority |
| **SUPPORT FACT** ✅ | 40+ | Domain value objects, no authority |
| **CLEAN** ✅ | 120+ | No authority logic present |

---

## Critical Finding: Two Illegal Sovereigns Identified

### ILLEGAL SOVEREIGN #1 — `ElectionVotingController::resolveIpBlock()`

**Location:** `app/Http/Controllers/ElectionVotingController.php:213-235`

**Code:**
```php
private function resolveIpBlock(Election $election, string $ip): array
{
    // Line 222: Determines if vote can proceed based on IP count
    return $this->evaluateIpCount($election, $ip, $election->ip_restriction_max_per_ip);
}
```

**Violation:** Derives participation authority (vote blocking) outside `ElectionCapabilityResolver`

**Current Usage:**
- Line 66: Called in `show()` method (vote page entry)
- Line 136: Called in `store()` method (vote submission)

**Authority Flow:** `IP count check → blocks participation` (WRONG)

**Correct Authority Flow:** `IP count check → TrustPolicyEvaluator → PolicySequence::NetworkBindingPolicy → OverlayCoordinator → TrustCapabilityPolicy → Resolver → ONLY resolver blocks`

**Risk Level:** 🔴 **HIGH**
- Participation authority exists outside sovereign resolver
- Vote submission can fail based on controller logic, not resolver decision
- No audit trail through security event recorder
- Cannot be replayed via constitutional evidence

**Fix Timeline:** Phase D.6 (remove after TrustCapabilityPolicy is proven equivalent)

---

### ILLEGAL SOVEREIGN #2 — `ElectionVotingController::evaluateIpCount()`

**Location:** `app/Http/Controllers/ElectionVotingController.php:237-260`

**Code:**
```php
private function evaluateIpCount(Election $election, string $ip, int $max): array
{
    $ip_hash = hash('sha256', $ip . env('ELECTION_IP_SALT'));
    $votes_from_ip = Vote::where('election_id', $election->id)
        ->where('ip_hash', $ip_hash)
        ->count();
    
    // Returns: either [allowed => true] or [allowed => false, blocked => true]
    // This is a participation authority decision
    if ($votes_from_ip >= $max) {
        return ['allowed' => false, 'blocked' => true];
    }
    
    return ['allowed' => true];
}
```

**Violation:** Derives participation authority (allowed/blocked) outside `ElectionCapabilityResolver`

**Authority Decision Made Here:** Whether vote can proceed based on IP count limit

**Risk Level:** 🔴 **CRITICAL**
- Returns boolean participation authority (`allowed` field)
- Queried directly without going through resolver pipeline
- No overlay evaluation (malicious activity, velocity thresholds)
- No policy aggregation
- No trust continuity evaluation
- No event recording through official channels
- Vote can fail controller-side before security event recorder is called

**Fix Timeline:** Phase D.6 (remove and redirect to TrustCapabilityPolicy)

---

## CANONICAL SOVEREIGN — Location Approved

### `ElectionCapabilityResolver::evaluate()`

**Location:** `app/Application/Election/Capabilities/ElectionCapabilityResolver.php`

**Authority Flow:** 
```
✅ TrustCapabilityPolicy (interprets OverlayInfluenceContext + TrustEvaluationState)
✅ All policies run first
✅ All overlays aggregated
✅ Events recorded
✅ Resolver interprets all evidence
✅ ONLY resolver returns ElectionCapabilitySnapshot with capability decision
```

**Status:** ✅ **CORRECT** — Authority is derived HERE and ONLY HERE

---

## PROJECTION Usage — All Correct

| Location | Pattern | Status |
|---|---|---|
| `resources/views/*.blade.php` | `{{ $snapshot->capabilities['vote'] }}` | ✅ Reads resolved authority |
| `ElectionVotingController::show()` | Passes `$snapshot` to view | ✅ Projection consumption |
| `ElectionController` | Returns `ElectionCapabilitySnapshot` | ✅ Projection assembly |
| Frontend (Inertia) | Reads `page.props.capabilities` | ✅ Projection display |

**Finding:** Zero authority derivation in templates or display logic — all correct

---

## SUPPORT FACT — Domain Value Objects

| Class | Method | Purpose | Status |
|---|---|---|---|
| `Election` | `ipInRange($ip)` | Returns boolean IP in range | ✅ Fact function |
| `NetworkTrustEvidence` | `exceedsLimit($trustLevel)` | Returns remaining votes | ✅ Fact function |
| `DeviceTrustContext` | `satisfiesDeviceAttestation()` | Returns boolean device match | ✅ Fact function |
| `VerificationAttestationRecord` | `isSatisfied()` | Returns boolean attestation state | ✅ Fact function |

**Finding:** All domain value objects return FACTS, never authority decisions

---

## CLEAN — No Authority Logic

| Category | Count | Status |
|---|---|---|
| Read-only controllers | 20+ | ✅ Display only |
| Authentication (Fortify) | 8 | ✅ Identity, not participation |
| Admin/Officer policies | 5 | ✅ Management, not voting |
| FormRequest validators | 15+ | ✅ Input validation only |
| Event listeners | 10+ | ✅ Logging, not authority |

**Finding:** No hidden authority derivation in general codebase

---

## AUDIT VECTOR ANALYSIS

### Middleware Chain

| Middleware | Layer | Authority Check | Status |
|---|---|---|---|
| CORS | Transport | None | ✅ |
| TrustProxies | Transport | None | ✅ |
| TenantContext | Application | None (scoping only) | ✅ |
| Authenticate | Transport | Identity only | ✅ |
| Guest | Transport | Identity only | ✅ |
| **ValidateVotingIp** | **Application** | **IP block check** | ⚠️ **Dangerous** |
| Throttle | Transport | Rate limit | ✅ |

**Finding:** `ValidateVotingIp` middleware exists but is NOT registered in routes (currently unused)

**Status:** ⏳ Deferred risk — becomes illegal if registered

---

### FormRequest Authorization

All voting FormRequest `authorize()` methods:
```php
public function authorize(): bool {
    return true;  // ✅ Identity verified by middleware, no voting eligibility check here
}
```

**Finding:** Zero voting eligibility checks in FormRequest — correct

---

### Model Observers

Checked:
- `Election::saving/saved/updated`
- `Vote::creating/created`
- `VoterSlug::creating/updating`

**Finding:** Zero authority derivation in model events — all are logging/housekeeping

---

## Classification Table (Complete Authority Map)

| Component | Location | Type | Classification | Risk | Fix Timeline |
|---|---|---|---|---|---|
| ElectionVotingController | app/Http/Controllers/ | Controller | **ILLEGAL SOVEREIGN** | 🔴 CRITICAL | D.6 |
| resolveIpBlock() | Line 213 | Private method | **ILLEGAL SOVEREIGN** | 🔴 HIGH | D.6 |
| evaluateIpCount() | Line 237 | Private method | **ILLEGAL SOVEREIGN** | 🔴 CRITICAL | D.6 |
| ElectionCapabilityResolver | app/Application/Election/Capabilities/ | Resolver | **CANONICAL SOVEREIGN** | ✅ Correct | Keep |
| TrustCapabilityPolicy | app/Application/Election/Capabilities/Policies/ | Policy | **PROJECTION INTERPRETER** | ✅ Correct | Keep |
| Blade templates | resources/views/ | Templates | **PROJECTION** | ✅ Correct | Keep |
| FormRequest authorize() | app/Http/Requests/ | FormRequest | **CLEAN** | ✅ No authority | Keep |
| ElectionPolicy | app/Policies/ | Policy | **CLEAN** (admin only) | ✅ Correct | Keep |
| ValidateVotingIp | app/Http/Middleware/ | Middleware | **ILLEGAL (deferred)** | ⏳ Unused | Remove D.6 |
| Domain value objects | app/Domain/Election/Security/ | Domain | **SUPPORT FACT** | ✅ Correct | Keep |
| Model observers | app/Models/ | Observer | **CLEAN** | ✅ Correct | Keep |

---

## Sovereignty Topology Diagram

```
HTTP Request (ElectionVotingController::store)
    ↓
Transport/Auth Middleware ✅ (identity only)
    ↓
FormRequest::authorize() ✅ (returns true — no authority check)
    ↓
Controller::store() ✅ (builds context, no authority decision)
    ↓
    ├─→ TrustPolicyEvaluator::evaluate() 🚨 (was bypassed by resolveIpBlock)
    │   ├─→ OverlayCoordinator::aggregate() ✅
    │   ├─→ PolicySequence::evaluate() ✅
    │   │   ├─→ VerificationAttestationPolicy (FACTS ONLY)
    │   │   ├─→ NetworkBindingPolicy (FACTS ONLY)
    │   │   └─→ DeviceBindingPolicy (FACTS ONLY)
    │   └─→ SecurityEventRecorder::record() ✅
    │
    ├─→ ElectionCapabilityResolver::evaluate() 🎯 (ONLY SOVEREIGN AUTHORITY HERE)
    │   ├─→ TrustCapabilityPolicy (interprets evidence → capability decision)
    │   └─→ Other policies (lifecycle, preconditions, authorization)
    │
    ├─→ return ElectionCapabilitySnapshot ✅ (projection only)
    │
    └─→ Blade template displays `$snapshot->capabilities` ✅ (reads resolved authority)
```

**Gap:** Direct IP count check in controller (lines 66, 136) bypasses entire trust pipeline

---

## Remaining Risks (Priority Order)

### 🔴 CRITICAL
1. `evaluateIpCount()` — Returns authority decision outside resolver
2. Mutable `security_articles_snapshot` — Constitutional law can be modified post-creation

### 🔴 HIGH
1. `resolveIpBlock()` — Participates in authority decision
2. `ValidateVotingIp` middleware — Unused but present (risk of re-activation)
3. No semantic regression protection (CI gate missing)

### ⏳ MEDIUM
1. Replay determinism not yet proven
2. Event ordering not yet validated
3. Some edge cases in trust evaluation untested

---

## Audit Verdict

### Structural Sovereignty: **MOSTLY SOUND** ✅

Canonical sovereign resolver exists and is the correct location for authority.

### Operational Sovereignty: **LEAKING** 🚨

Two illegal sovereigns exist in voting controller and actively participate in participation authority decisions.

### Net Assessment

Architecture has correct structure but incorrect runtime behavior.

**Status:** Ready for Phase D.5.5 Audit 2 (Constitutional Mutation Prevention)

---

## Next Action

Verify Audit 2 completion: Constitutional articles frozen forever, no mutation possible.

Then execute Audit 3: Verify no sovereign execution before Resolver.
