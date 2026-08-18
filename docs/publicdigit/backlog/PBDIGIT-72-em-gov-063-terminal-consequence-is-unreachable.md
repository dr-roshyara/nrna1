# `PBDIGIT-72` — `EM-GOV-063`'s terminal consequence is unreachable *(the same producer gap as P-7)*

**Raised:** 2026-08-18 · **Source:** `EM-DOM-001` Phase-2A design map, finding **V-8** · **Status:** 🔵 **BACKLOG — not authorized, not scheduled, not started.**

## The finding

```php
// Domain/OperatingCore/Policy/ExpiryConsequence.php  (P-6)
public static function onHaltedRecoveryExpiry(
    ElectionId $electionId, HaltedAtGate $halt, RecoveryProcess $process, ...
): RecoveryPeriodExpired
```

**`onHaltedRecoveryExpiry()` requires a non-null `HaltedAtGate`.** No production code produces or persists one — the same unreachability that makes **P-7 `ResumptionTarget`** uncallable.

> ## ⚠️ **The consequence is larger than a missing convenience: `EM-GOV-063`'s terminal consequence — *"the one genuinely new recording obligation"* — is UNREACHABLE. An ADOPTED business rule cannot fire.**

**ELECTION DISCONTINUED (`EM-GOV-069`) is the governed business rendering of that terminal state.** So the gap is not internal plumbing — **a governed election-level outcome cannot currently be recorded.**

## Why it is a separate item and not part of `EM-DOM-001`

⛔ **No `DEP-1…DEP-6` covers it.** `EM-DOM-001` is scoped to the ADR-1/ADR-2 absence and provenance dependencies; this is a **third** consumer of the same missing producer. **Obligation 6:** *new scope discovered mid-slice → a backlog item and a report, never an extension.* **The Phase-2A lane reported it and did not absorb it — correctly.**

## Relationship to `EM-DOM-001`

| | |
|---|---|
| **Shared cause** | the operational overlay has no identity/retrieval path, so no recorded `HaltedAtGate` is obtainable |
| **Act B may HELP** | if the Act-B contract lands, a recorded halt becomes *nameable*; **it does not become retrievable in practice until act C (persistence), which is unauthorized** |
| ⛔ **Act B does NOT fix this** | `ExpiryConsequence`'s reachability additionally needs a **caller** — and no caller exists or is authorized |
| **Not blocked on** | `BND-1` *(no lifecycle-phase discriminator involved)* |
| **Possibly touching** | `BND-3` — if the overlay's boundary determines who may invoke the terminal consequence |

## What this item does NOT claim

⛔ **Not a defect in `ExpiryConsequence`** — the policy is correct; its input is unobtainable. ⛔ **Not authorization** to add a producer, a caller, a port or a scheduler. ⛔ **Not a claim that `EM-GOV-063` is wrongly adopted** — the rule is adopted and sound; only its execution path is absent. ⛔ **Not a claim about severity or urgency** — no election has reached halted-recovery expiry in the current model.

## Next action

**None authorized.** Requires its own PO/ARB authorization. **Recommend it be weighed together with act C (persistence) rather than separately**, since both turn on making recorded operational status obtainable.

**Traceability:** Phase-2A design map V-8 · `ExpiryConsequence::onHaltedRecoveryExpiry()` · `HaltedAtGate` · P-7 · `EM-GOV-063` · `EM-GOV-069` *(ELECTION DISCONTINUED)* · `EM-DOM-001` Obligation 6 · post-decision Rule-8 gate G-3.
