# Round 17 — Stream 6B: Invocation & Consequence Analysis — Evidence Findings

**Date:** 2026-06-07

**Phase:** Phase 1 — Evidence Gathering (Stream 6B)

**Status:** Complete

**Scope:** Two questions only — D36 (invocation) and D35 (legitimacy consequences)

---

## 1. Investigation Scope

Stream 6B investigates exactly two strategic unknowns from Stream 6A:

| Debt | Question |
|------|----------|
| **D36** | Who may invoke ConstitutionalArbitrationKernel? |
| **D35** | What are the consequences of legitimacy = EXPIRED? |

No general dispute/challenge discovery. No expansion into appeal or complaint workflows.

---

## 2. D36 — Invocation Analysis

### 2.1 ConstitutionalArbitrationKernel Structure

**File:** `app/Contexts/Membership/Domain/Committee/Constitutional/ConstitutionalArbitrationKernel.php` (lines 11-35)

**Dependencies (constructor, lines 13-17):**
- `GovernanceDecisionKernel` — core governance decision engine
- `ConstitutionalArbitrationPolicy` — arbitration policy for legitimacy determination
- `GovernanceClock` — time source for evaluation

**Public Method (lines 19-34):**
- `decide(CapabilityContext, CapabilityType, ?DateTimeImmutable): ConstitutionalGovernanceDecision`
- Calls `GovernanceDecisionKernel.decide()` for base governance decision
- Calls `ConstitutionalArbitrationPolicy.arbitrate()` for constitutional/legitimacy review
- Returns combined `ConstitutionalGovernanceDecision`

### 2.2 Observed Call Sites

**Call sites (Tier 2 Evidence):**
| File | Type | Usage | Operational? |
|------|------|-------|-------------|
| `ConstitutionalArbitrationKernelTest.php` | Unit test | 4 test methods constructing and invoking kernel | No (test-only) |
| `GovernanceProvenanceTest.php` | Unit test | References kernel name in GovernanceProvenance data | No (test-only) |

**Files confirmed without invocation during examination:**
- `MembershipServiceProvider.php` — binds GovernanceDecisionStore (persistence) but NOT ConstitutionalArbitrationKernel
- No controllers, console commands, scheduled tasks, listeners, or route handlers found referencing the kernel within the examined implementation
- No dependency injection registration for the kernel in examined service providers

### 2.3 D36 Assessment

**Outcome: F (Invocation path not resolved)**

**Observed:**
- ConstitutionalArbitrationKernel exists as domain-level code with full implementation
- Kernel is testable — tests construct and invoke the kernel directly
- GovernanceDecisionStore (persistence for decision snapshots) IS registered and bound operationally

**Not Observed:**
- No operational invocation path was observed within the examined implementation
- No controllers, commands, listeners, scheduled tasks, or other callers were observed invoking the kernel within the examined code
- No DI registration for the kernel was found in the examined service providers

**Special Instruction Applied:**
Operational invocation path not observed in examined implementation. Possible explanations remain open:
- Future implementation (kernel exists, wiring not yet done)
- Invocation through indirect mechanism not found
- Incomplete migration from legacy system
- Test-only verification artifact (kernel exists for test validation of arbitration logic)
- Evidence insufficient

---

## 3. D36 — Invocation Inventory

| Invocation Type | Observed? | Evidence | Confidence |
|---|---|---|---|
| Automatic invocation | No | No listener/observer/daemon found in examined implementation | Low |
| Administrative invocation | No | No controller/command/task found in examined implementation | Low |
| User-driven invocation | No | No route/endpoint found in examined implementation | Low |
| Governance-driven invocation | No | No governance workflow entry found in examined implementation | Low |
| Multiple invocation paths | No | Only test calls observed | Low |
| Invocation path not resolved | **Yes** | No operational callers observed | High |

**Observation (Tier 2 Evidence):**
GovernanceDecisionStore IS operationally wired (MembershipServiceProvider lines 249-252 binds interface to Eloquent store). This suggests the persistence layer for governance decisions is operational, but the kernel that produces those decisions was not observed to be invoked through any operational path within the examined implementation.

---

## 4. D35 — Consequence Analysis

### 4.1 GovernanceLegitimacy Enum

**File:** `app/Contexts/Membership/Domain/Committee/Constitutional/GovernanceLegitimacy.php` (lines 7-32)

**Cases (line 8-16):**
- `LEGITIMATE` — valid authority
- `EXPIRED` — authority term ended
- `PENDING` — not yet active
- `SUSPENDED` — temporarily disabled
- `EMERGENCY` — emergency authority
- `CARETAKER` — caretaker authority
- `REVOKED` — authority revoked

**Validity (lines 17-23):**
```php
public function isValid(): bool {
    return match ($this) {
        self::LEGITIMATE, self::EMERGENCY, self::CARETAKER => true,
        default => false,
    };
}
```
EXPIRED, PENDING, SUSPENDED, and REVOKED are all `isValid() = false`.

### 4.2 Legitimacy Determination

**File:** `app/Contexts/Membership/Domain/Committee/Constitutional/LegitimacyEvaluator.php` (lines 7-18)

Legitimacy is determined **temporally** — based on a `TemporalAuthorityWindow`:
```
PENDING → PENDING
ACTIVE  → LEGITIMATE
EXPIRED → EXPIRED
```

**File:** `app/Contexts/Membership/Domain/Committee/Constitutional/DefaultTemporalLegitimacyPolicy.php` (lines 7-14)

Delegates to LegitimacyEvaluator. No additional enforcement or consequence logic.

### 4.3 GovernanceDecisionSnapshot — Persistence

**File:** `app/Contexts/Membership/Domain/Committee/Constitutional/GovernanceDecisionSnapshot.php`

ConstitutionalDecision (including legitimacy status) is **persisted as a snapshot**:
- `fromDecision()` (lines 30-103) converts ConstitutionalGovernanceDecision into a persisted snapshot
- Legitimacy stored as string in `$this->legitimacy` (line 22)
- SHA256 integrity hash covers all decision data including legitimacy (lines 77-89)
- `verifyIntegrity()` (lines 134-150) recomputes hash for tamper detection

### 4.4 GovernanceReplayService — Persistence and Replay

**File:** `app/Contexts/Membership/Domain/Committee/Constitutional/GovernanceReplayService.php`

**Two methods:**
- `persist()` (lines 17-30) — stores snapshot via GovernanceDecisionStore
- `replay()` (lines 32-45) — retrieves snapshot, verifies integrity, returns GovernanceArchaeologyRecord

### 4.5 GovernanceArchaeologyRecord — Read Model

**File:** `app/Contexts/Membership/Domain/Committee/Constitutional/GovernanceArchaeologyRecord.php`

Exposes legitimacy status:
- `legitimacy(): GovernanceLegitimacy` (line 18)
- `wasConstitutionallyValid(): bool` (line 21)

### 4.6 GovernanceTimelineProjector — Timeline Projection

**File:** `app/Contexts/Membership/Domain/Committee/Constitutional/Timeline/GovernanceTimelineProjector.php`

Projects snapshot into read-only timeline projection. Legitimacy is mapped into the projection but no enforcement logic.

### 4.7 EloquentGovernanceDecisionStore — Database Persistence

**File:** `app/Contexts/Membership/Infrastructure/Persistence/Repositories/EloquentGovernanceDecisionStore.php`

- `store()` — creates or updates GovernanceDecisionSnapshotModel with all fields including legitimacy
- `findById()` — retrieves by ID, maps back to domain snapshot

### 4.8 Database Schema

**Migration:** `2026_05_08_000001_create_governance_decisions_table.php`

Key columns:
- `legitimacy` (string, line 18)
- Index: `(legitimacy, decided_at)` (line 42) — enables querying decisions by legitimacy status

### 4.9 D35 Observed Consequences

| Consequence Type | Observed? | Evidence | Confidence |
|---|---|---|---|
| Corrective (decision reversal) | No | No code found reversing decisions based on legitimacy status within examined implementation | Low |
| Preventative (blocks future actions) | No | No code found using legitimacy to gate future actions within examined implementation | Low |
| Advisory (informational only) | **Partial** | Legitimacy is stored, persisted, and projected — available for read/query | High |
| Persistence | **Yes** | GovernanceDecisionSnapshot persists legitimacy to governance_decisions table | High |
| Replay verification | **Yes** | GovernanceReplayService.replay() retrieves snapshots and verifies integrity | High |
| Timeline projection | **Yes** | GovernanceTimelineProjector projects legitimacy into read model | High |

### 4.10 D35 Assessment

**Outcome: D (No observable operational consequence found within examined implementation)**

**Special Instruction Applied:**
No operational consequence was observed within the examined implementation. Possible explanations remain open:
- **Organizational consequence** — legitimacy is stored for human governance bodies to act upon
- **Manual governance action** — a governance body reviews EXPIRED decisions and acts outside the software
- **External process** — legitimacy triggers processes outside the examined codebase
- **Deferred implementation** — consequence enforcement is planned but not yet built
- **Indirect consequence** — legitimacy influences decisions through channels not directly observable in this investigation
- **Evidence insufficient** — deeper source analysis needed

---

## 5. Hypotheses Impact

### H22: Challenge Mechanisms Are Organizational

**Status:** Open (remains Open — evidence insufficient to update)

**D36 Impact:** No operational invocation path observed for ConstitutionalArbitrationKernel within examined implementation. This is consistent with H22 (if arbitration requires organizational invocation) but also consistent with deferred implementation or indirect invocation. Evidence insufficient to update.

**D35 Impact:** Legitimacy is recorded and persisted but no software enforcement observed within examined implementation. If legitimacy consequences require organizational action (human review of EXPIRED decisions), this supports H22. Evidence insufficient to confirm.

---

### H23: Legitimacy Restoration Is Independent

**Status:** Open (remains Open — evidence insufficient to update)

**D35 Impact:** Legitimacy status is determined, recorded, and persisted. No separate "restoration" process observed within examined implementation. However, the persistence of legitimacy data (including EXPIRED status) in a queryable format (index on legitimacy, decided_at) could support future restoration processes. Evidence insufficient to determine.

---

## 6. Discovery Debt Updates

### D35 — Updated

**Original Question:** Can decisions be reversed through arbitration?

**Updated Status:** No observable consequence found within examined implementation. Legitimacy = EXPIRED is determined, stored, and persisted. Enforcement of EXPIRED status (reversal, blocking, advisory) was not observed. Whether enforcement occurs organizationally, in a different system layer, or is deferred remains unresolved.

---

### D36 — Updated

**Original Question:** Who may invoke ConstitutionalArbitrationKernel?

**Updated Status:** No operational invocation path observed within examined implementation. Kernel exists and is testable but no callers (controllers, commands, services, listeners, routes) were found in examined code. GovernanceDecisionStore (persistence) IS operationally wired. Whether kernel invocation is deferred, organizational, or provided through a mechanism not found remains unresolved.

---

### D37 (New): What Operational Enforcements Exist for Legitimacy Status?

**Question:** GovernanceLegitimacy status (EXPIRED, REVOKED, SUSPENDED) is recorded and persisted. What mechanism, if any, enforces this status operationally? Does EXPIRED prevent new governance decisions? Does REVOKED trigger alerts?

**Why Unresolved:** No enforcement mechanism observed within examined implementation. Database index on `(legitimacy, decided_at)` suggests queryability, but no code was found that reads legitimacy to operationally gate, block, or enforce.

**Priority:** HIGH (STRATEGIC)

**Recommended Discovery:** Trace upstream consumers of GovernanceLegitimacy data. Investigate whether governance decision outcomes are filtered/queried by legitimacy status in code not yet examined. Consider organizational governance process interviews.

---

### D38 (New): Were Any Operational Invocation Paths for GovernanceDecisionKernel Implemented, Deferred, or Externalized?

**Question:** ConstitutionalArbitrationKernel wraps GovernanceDecisionKernel. Were any operational invocation paths for GovernanceDecisionKernel implemented, deferred, or externalized?

**Why Unresolved:** GovernanceDecisionKernel has test coverage. No operational invocation was observed within the examined implementation scope.

**Priority:** MEDIUM

**Recommended Discovery:** Map GovernanceDecisionKernel invocation to determine if operational path exists

---

## 7. Summary of Stream 6B Findings

### D36 — Invocation

```
ConstitutionalArbitrationKernel
    ↓
    EXISTS:         Yes — domain code, fully implemented
    TEST CALLERS:   2 test files, 4+ test methods
    OPERATIONAL:    Not observed within examined implementation
    PERSISTENCE:    GovernanceDecisionStore IS operationally wired (DI bound)
    INVOCATION:     Outcome F — path not resolved
```

**Finding:** The arbitration kernel that evaluates governance decisions and produces legitimacy determinations exists at the domain level but no observable operational invocation path was found within the examined implementation. The persistence layer for its output (GovernanceDecisionSnapshot) IS operationally wired. Possible explanations include: (a) invoked through a mechanism not yet discovered, (b) awaiting wiring, (c) exists as a verification/test artifact with future operational intent.

---

### D35 — Legitimacy Consequences

```
Legitimacy = EXPIRED
    ↓
    DETERMINED:     LegitimacyEvaluator (temporal: window state)
    STORED IN:      GovernanceDecisionSnapshot (legitimacy field)
    PERSISTED TO:   governance_decisions table
    PROJECTED TO:   GovernanceTimelineProjection (read model)
    VERIFIED BY:    GovernanceReplayService (integrity check)
    ENFORCED BY:    Not observed within examined implementation
    CONSEQUENCE:    Outcome D — no operational consequence observed
```

**Finding:** Legitimacy status is determined, stored, persisted, projected, and verifiable. No code was observed that reads EXPIRED status and enforces a consequence (reversal, blocking, notification). The data is queryable (database index on `legitimacy, decided_at`) and may be consumed by organizational processes or future implementation.

---

## 8. Remaining Unknowns

| Debt | Question | Priority |
|------|----------|----------|
| D35 | Consequences of legitimacy = EXPIRED — what enforces? | HIGH STRATEGIC |
| D36 | Who invokes ConstitutionalArbitrationKernel? | HIGH STRATEGIC |
| D37 | What operational enforcements exist for legitimacy status? | HIGH STRATEGIC |
| D38 | What operational invocation paths exist for GovernanceDecisionKernel? | MEDIUM |

---

## 9. Conclusion

Stream 6B is complete. The investigation found:

**D36:** ConstitutionalArbitrationKernel has no observable operational invocation path within examined implementation. Outcome: F (path unresolved).

**D35:** Legitimacy = EXPIRED is determined, stored, and persisted but no operational enforcement consequence was observed within examined implementation. Outcome: D (no consequence observed in examined implementation).

Both findings adhere to Round 17 discipline: absence of observed evidence ≠ evidence of absence. Multiple explanatory paths remain open.

**Note:** Outcome classifications describe observations within examined evidence only and do not describe the complete system.

---

**Stream 6B Complete — STOP. No further streams initiated.**

**Status:** AWAITING ARB REVIEW
