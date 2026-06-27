# Observed Event Analysis (Deep Inspection Round 2)

**Purpose:** Factual findings about four security events  
**Date:** 2026-06-03  
**Method:** Code inspection (class definitions, dispatch locations, consumer analysis)  
**Scope:** Confirmed facts, risk identification, unresolved unknowns  
**Status:** Ready for targeted follow-up (not yet ready for evaluation)

---

## Critical Findings

**All four security events are class definitions only — not dispatched, not consumed, not integrated into running system.**

| Event | Defined | Dispatched | Listeners | Integration Status |
|-------|---------|-----------|-----------|-------------------|
| **ObservationRecorded** | ✅ Yes | ❌ No dispatch found | ❌ None | Unintegrated |
| **LegitimacyGranted** | ✅ Yes | ❌ No dispatch found | ❌ None | Unintegrated |
| **LegitimacyEvaluated** | ✅ Yes | ❌ No dispatch found | ❌ None | Unintegrated |
| **DivergenceObserved** | ✅ Yes | ❌ No dispatch found | ❌ None | Migration (likely temporary) |
| **SovereigntyBoundaryCrossed** | ✅ Yes | ❌ No dispatch found | ❌ None | Migration (likely temporary) |

**Implication:** These events exist as code but do not currently operate in the system.

---

## Event 1: ObservationRecorded

### Meaning

**Class Definition:** `app/Domain/Election/Security/Event/ObservationRecorded.php`

```php
readonly class ObservationRecorded {
    public string $overlayIdentifier,
    public string $finding,
    public array $evidenceContext,
    public string $electionId,
    public DateTimeImmutable $occurredAt,
}
```

**Purpose:** Records raw overlay observations (from docblock comments: "pure telemetry: carries observation finding without any authority semantics")

### Producer

**Current Status:** Not found in codebase. No dispatch location identified.

**Implication:** ObservationRecorded is defined but not yet emitted by any code.

### Consumer

**Current Status:** No listeners registered in EventServiceProvider.

**Implication:** Even if ObservationRecorded were dispatched, nobody would consume it.

### Payload Analysis

| Field | Type | Meaning |
|-------|------|---------|
| `overlayIdentifier` | string | Which overlay system recorded this? |
| `finding` | string | What was observed? (e.g., "divergence", "anomaly") |
| `evidenceContext` | array | **Contents unknown** — array structure not documented |
| `electionId` | string | Which election? |
| `occurredAt` | DateTimeImmutable | When? |

**Critical Unknown:** What is inside `evidenceContext`? The array structure is not defined in the class.

### Lifecycle Role

- **When:** Unclear (event not dispatched)
- **Why:** To record observations without triggering authority decisions
- **Where:** Unknown

### Potentially Relevant Hypotheses

- **H1** (Evidence is valid BC) — ✓ Suggests Evidence-related concepts exist
- **H3** (Evidence owns invariants) — ✓ `evidenceContext` suggests observation preservation
- **H5** (Event clusters exist) — ✓ If dispatched, could show observation patterns

### Architectural Signal

**Strong Signal:** The existence of ObservationRecorded with `evidenceContext` field indicates that evidence-related concepts were anticipated during system design.

**Weak Signal:** No dispatch location means the concept is not yet integrated into the running system.

**Interpretation:** Evidence Context concepts exist in the design layer but not the operational layer.

### Constitutional Relevance

**Observation (not judgment):** 

`evidenceContext` array structure is undefined. Depending on contents:
- If contains identifiers: potential privacy implications
- If contains hashes/digests: potential privacy protection
- Cannot determine without understanding payload structure

**No judgment made yet.**

---

## Event 2: LegitimacyGranted

### Meaning

**Class Definition:** `app/Domain/Election/Security/Event/LegitimacyGranted.php`

```php
readonly class LegitimacyGranted {
    public string $electionId,
    public string $voterIdentifier,
    public string $evidenceEnvelopeHash,
    public DateTimeImmutable $occurredAt,
}
```

**Purpose:** Records the decision that a voter is granted legitimacy (from docblock: "emitted when a voter is granted legitimacy and proceeds past the constitutional gate")

### Producer

**Current Status:** Not found in codebase. No dispatch location identified.

**Implication:** LegitimacyGranted is designed but not yet emitted.

### Consumer

**Current Status:** No listeners registered.

**Implication:** Even if dispatched, nobody listens.

### Payload Analysis

| Field | Type | Meaning |
|-------|------|---------|
| `electionId` | string | Which election granted legitimacy? |
| `voterIdentifier` | string | **Contains identifier** — voter linkage present |
| `evidenceEnvelopeHash` | string | Hash of evidence supporting decision. Contents unknown. |
| `occurredAt` | DateTimeImmutable | When granted? |

**Critical Discovery:** LegitimacyGranted carries `voterIdentifier` — a direct link between voter and legitimacy decision.

### Lifecycle Role

- **When:** After evaluation completes, before governance executes
- **Why:** To record the constitutional gate being crossed
- **Where:** Unknown (not dispatched)

### Potentially Relevant Hypotheses

- **H1** (Evidence is valid BC) — ⚠️ Contains voter identifier (may complicate Evidence ownership)
- **H3** (Evidence owns invariants) — ? Contains `evidenceEnvelopeHash`
- **H7** (Evaluation is separate) — ✓ Bridges Evaluation decision to Legitimacy decision
- **H8** (Verification boundary) — ✓ Hash suggests verification support

### Architectural Signal

**Strong Signal:** Explicit `evidenceEnvelopeHash` field indicates evidence is integral to legitimacy decisions, not ancillary.

**Concern Signal:** Direct `voterIdentifier` carries voter identity alongside evidence hash, suggesting potential coupling.

### Constitutional Relevance

**Observation (not judgment):**

LegitimacyGranted creates a direct voter-to-decision link. Depending on how `evidenceEnvelopeHash` is used:
- If hash is sufficient for verification, voter identifier may be unnecessary
- If voter identifier is required for verification, privacy implications arise

**Cannot evaluate without understanding verification design.**

---

## Event 3: DivergenceObserved

### Meaning

**Class Definition:** `app/Domain/Election/Security/Event/DivergenceObserved.php`

```php
readonly class DivergenceObserved {
    public string $electionId,
    public string $voterIdentifier,
    public string $divergenceCategory,
    public string $constitutionalOutcome,
    public string $legacyOutcome,
    public string $evidenceEnvelopeHash,
    public DateTimeImmutable $occurredAt,
}
```

**Purpose:** Records when two different authority paths produce different outcomes (from docblock: "emitted when sovereignty divergence is detected between constitutional and procedural authority paths")

### Producer

**Current Status:** Not found in codebase.

**Implication:** DivergenceObserved is designed but not dispatched.

### Consumer

**Current Status:** No listeners.

### Payload Analysis

| Field | Type | Meaning |
|-------|------|---------|
| `electionId` | string | Which election? |
| `voterIdentifier` | string | **Identifier present** |
| `divergenceCategory` | string | Type of divergence? (structure unknown) |
| `constitutionalOutcome` | string | What constitution path decided |
| `legacyOutcome` | string | What legacy path decided |
| `evidenceEnvelopeHash` | string | Evidence supporting detection |
| `occurredAt` | DateTimeImmutable | When detected? |

### Lifecycle Role

- **When:** During evaluation when two paths diverge
- **Why:** To signal a constitutional-legacy mismatch
- **Context:** Related to D-phase migration (from docblock comment about D.0.3c gate)

### Potentially Relevant Hypotheses

- **H2** (Independent lifecycle) — ✓ Suggests separate evaluation paths
- **H5** (Event clusters) — ✓ Divergence is a cluster indicator
- **H7** (Evaluation separate) — ✓ Compares two evaluation outcomes

### Architectural Signal

**Strong Signal:** Existence of DivergenceObserved with both `constitutionalOutcome` and `legacyOutcome` indicates a parallel constitutional and legacy authority system.

**Phase Signal:** Docblock mentions "D-phase migration" and "D.0.3c middleware retirement" — suggests this is migration instrumentation, not core architecture.

### Constitutional Relevance

**Observation:** 

DivergenceObserved carries voter identifier alongside outcomes. Indicates system is tracking voter-level divergence, which has implications for:
- Audit completeness
- Privacy (voter linkage maintained)
- Verification (can verify individual voter path)

---

## Event 4: SovereigntyBoundaryCrossed

### Meaning

**Class Definition:** `app/Domain/Election/Security/Event/SovereigntyBoundaryCrossed.php`

```php
readonly class SovereigntyBoundaryCrossed {
    public string $electionId,
    public string $voterIdentifier,
    public LegitimacyOutcome $constitutionalOutcome,
    public LegitimacyOutcome $legacyOutcome,
    public string $divergenceType,
    public string $evidenceEnvelopeHash,
    public DateTimeImmutable $occurredAt,
}
```

**Purpose:** Records when dual sovereignty paths produce different outcomes (from docblock: "emitted when dual sovereignty paths produce different outcomes for the same voter, crossing a sovereignty boundary")

### Producer

**Current Status:** Not found in codebase.

### Consumer

**Current Status:** No listeners.

### Payload Analysis

| Field | Type | Meaning |
|-------|------|---------|
| `electionId` | string | Election context |
| `voterIdentifier` | string | **Voter linkage** |
| `constitutionalOutcome` | LegitimacyOutcome | Typed outcome enum |
| `legacyOutcome` | LegitimacyOutcome | Typed outcome enum |
| `divergenceType` | string | Category of divergence |
| `evidenceEnvelopeHash` | string | Evidence supporting detection |
| `occurredAt` | DateTimeImmutable | When? |

### Lifecycle Role

- **When:** Critical gate condition for D-phase migration completion
- **Why:** To detect when constitutional and legacy paths diverge
- **Gate:** Docstring: "Zero crossings is the gate condition for D.0.3c (middleware retirement)"

**Implication:** This event is migration instrumentation, not core voting architecture.

### Potentially Relevant Hypotheses

- **H1** (Evidence is valid BC) — ? Used as migration gate
- **H2** (Independent lifecycle) — ✓ Tracks parallel lifecycles
- **H7** (Evaluation separate) — ✓ Compares dual evaluation paths

### Architectural Signal

**Critical Signal:** Gate condition on "zero crossings" indicates this is a migration validation tool, not permanent architecture.

**Temporary Signal:** Once D.0.3c completes and middleware retires, SovereigntyBoundaryCrossed may become obsolete.

### Constitutional Relevance

**Observation:**

SovereigntyBoundaryCrossed carries voter identifier with divergence outcome. Indicates:
- System can track individual voter divergences
- Voter-outcome linkage is maintained for audit
- Useful for identifying systematic errors in migration

---

## Structured Findings

### Confirmed Facts (Verified by Code Inspection)

| Fact | Evidence |
|------|----------|
| Four security event classes defined | Class files exist in `app/Domain/Election/Security/Event/` |
| No dispatch found in codebase | Grep search found zero `event(new ObservationRecorded...)` calls |
| No listeners registered | EventServiceProvider shows no listeners for any security event |
| All events carry `voterIdentifier` | LegitimacyGranted, DivergenceObserved, SovereigntyBoundaryCrossed all include this field |
| Hash fields exist | `evidenceEnvelopeHash` appears in multiple event constructors |
| Migration terminology present | DivergenceObserved and SovereigntyBoundaryCrossed docstrings reference D.0.3c gate |

---

### Risk Facts Requiring Architectural Decision

| Risk | Events Affected | Severity | Reason |
|------|----------------|----------|--------|
| **Voter linkage to legitimacy** | LegitimacyGranted, LegitimacyEvaluated | 🔴 **BLOCKING** | `voterIdentifier` linked to legitimacy decision enables vote proof and coercion/vote-buying attacks per receipt-free voting literature. **Cannot proceed without justifying necessity.** |
| **Voter linkage to divergence** | DivergenceObserved, SovereigntyBoundaryCrossed | 🔴 **BLOCKING** | Voter-to-divergence links could enable voters to prove how they voted, violating ballot secrecy. **Cannot proceed without justifying necessity or removing field.** |
| **Hash purpose unknown** | All events with `evidenceEnvelopeHash` | 🟡 Medium | Cannot evaluate cryptographic safety without understanding computation method and intended use |
| **evidenceContext structure unknown** | ObservationRecorded | 🟡 Medium | Array structure not documented; could contain indirect identifiers depending on contents |

---

## Required Architectural Decisions (Before Constitutional Evaluation)

### Decision 1: LegitimacyGranted `voterIdentifier`

**Question:** Why must legitimacy grant be linked to voter identity?

**Options:**
- ✅ **Keep:** Document why voter identity is necessary for legitimacy (must justify against ballot-secrecy risk)
- ✅ **Redact:** Remove `voterIdentifier`; use anonymous reference instead
- ✅ **Split:** Keep separate from event; store voter-link in separate, access-controlled ledger with strong retention policy

**Decision Required Before:** Constitutional evaluation of Evidence Context

**Retention Policy Required:** If kept, define: who can access, how long retained, what prevents proof-of-voting

---

### Decision 2: LegitimacyEvaluated `voterIdentifier`

**Question:** Is voter identity necessary for evaluation recording?

**Options:**
- ✅ **Keep:** Document necessity and justify against ballot-secrecy risk
- ✅ **Redact:** Use anonymous evaluation reference
- ✅ **Split:** Separate voter link from evaluation record

**Decision Required Before:** Constitutional evaluation

---

### Decision 3: DivergenceObserved `voterIdentifier`

**Question:** Is voter identity necessary to record divergence, or can divergence be detected anonymously?

**Critical Sub-question:** Can a voter use a divergence record to prove how they voted?

**If answer is YES:** Event must be redesigned (remove voter linkage or add compensating controls)

**If answer is NO:** Justify why voter linkage is safe despite divergence tracking

**Options:**
- ✅ **Keep (Justified):** Explain necessity and ballot-secrecy mitigation
- ✅ **Keep (Migration-Only):** Mark as temporary D.0.3c instrumentation; confirm deprecation timeline
- ✅ **Redact:** Remove `voterIdentifier`; detect divergence anonymously
- ✅ **Delete:** Event not needed in permanent architecture

---

### Decision 4: SovereigntyBoundaryCrossed `voterIdentifier`

**Question:** Why is voter identity necessary to record a sovereignty boundary crossing?

**Critical:** This event explicitly tracks individual voter outcomes across dual authority paths. This directly enables vote proof if retained.

**Options:**
- ✅ **Keep (Justified):** Strong justification required; explain ballot-secrecy mitigation
- ✅ **Keep (Migration-Only):** Confirm this is D.0.3c temporary instrumentation with explicit deprecation date
- ✅ **Redact:** Track boundary crossings without voter identity
- ✅ **Delete:** Not needed in permanent architecture

---

## Governance Requirement: Voter-Linked Event Policy

**Before any voter-linked event can be constitutional:**

| Requirement | Required For Events | Definition |
|-------------|-------------------|------------|
| **Necessity Justification** | LegitimacyGranted, LegitimacyEvaluated, DivergenceObserved, SovereigntyBoundaryCrossed | One sentence: "This field is necessary because..." or "This field will be removed because..." |
| **Retention Policy** | Any voter-linked event | Max retention duration, deletion schedule, who enforces |
| **Access Control Policy** | Any voter-linked event | Who can read, what audit trail, what prevents voter-to-outcome proof |
| **Ballot-Secrecy Test** | Any voter-linked event | "Can a voter use this event to prove how they voted?" If yes, event is unsafe |
| **Permanence Confirmation** | DivergenceObserved, SovereigntyBoundaryCrossed | If migration-only: explicit deprecation date. If permanent: justification |

---

### Unresolved Unknowns (Cannot Proceed Without Answers)

| Unknown | Why It Matters | Required For |
|---------|---------------|--------------|
| What is `evidenceContext`? | Payload could contain re-identification risk | Privacy compliance determination |
| How is `evidenceEnvelopeHash` computed? | Hash algorithm and salt affect privacy guarantees | Verification design evaluation |
| Where would these events be dispatched? | Current lack of dispatch suggests either: (a) planned for future, (b) dead code, (c) not yet integrated | Architecture readiness assessment |
| What is retention policy for voter-linked events? | Voter linkage + long-term retention = coercion risk | Constitutional compliance check |
| Are DivergenceObserved/SovereigntyBoundaryCrossed temporary? | Docstrings suggest D.0.3c migration; unclear if events are deprecated post-migration | Architecture permanence assessment |

---

## Status: Blocking Issues Identified

**Cannot proceed to constitutional evaluation.** Four voter-linked events carry fields that violate ballot-secrecy and coercion-resistance principles if retained without explicit justification and governance control.

---

## Blockers (Must Resolve Before Constitutional Evaluation)

| Blocker | Events | Required Decision |
|---------|--------|-------------------|
| **Voter linkage necessity not justified** | LegitimacyGranted, LegitimacyEvaluated, DivergenceObserved, SovereigntyBoundaryCrossed | Architecture owner must decide: justify necessity OR redact/delete field |
| **Retention policy missing** | All voter-linked events | Governance must define max retention, deletion schedule, access control |
| **Permanence of migration events unconfirmed** | DivergenceObserved, SovereigntyBoundaryCrossed | Confirm: temporary (D.0.3c gate) OR permanent architecture |
| **Ballot-secrecy test not passed** | All voter-linked events | Can voter prove how they voted using this event? If yes, event must be redesigned |

---

## Next Action (Required Before Constitutional Evaluation)

**Architecture Review focused on voter-linkage minimization:**

1. For each voter-linked event: decide keep/redact/split/delete
2. For each "keep" decision: write one-sentence justification
3. For all voter-linked events: define retention policy and access control
4. For migration events: confirm deprecation timeline or justify permanence
5. For all events: pass ballot-secrecy test (voter cannot prove how they voted)

---

**Analysis Status: Architectural decision required — not ready for constitutional evaluation.**
