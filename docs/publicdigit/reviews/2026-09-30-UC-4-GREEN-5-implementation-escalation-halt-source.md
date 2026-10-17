# UC-4 / `GREEN-5` — **IMPLEMENTATION ESCALATION**: no legitimate `HaltedAtGate` source

**Work item:** `EM-IMPL-002` · **Authorization consumed:** ARB Chief decision 2026-09-30, Option A (hold release) + UC-4/GREEN-5 implementation authorization
**Date:** 2026-09-30 · **Raised by:** `claude-code-session:84c0f6f6-795e-4c89-a382-733f2c7b7caf` — self-declared, not attestable (`INV-ATTR-1/2`, `G-2`)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

> ⛔ **STOPPED AT STEP 1 (RED INSPECTION). NO PRODUCTION CODE WRITTEN.** `app/` and `tests/` are byte-untouched. No test modified, none created, none deleted. Triggered **STOP condition 5** (*"`HaltedAtGate` must be created, persisted, retrieved, or wired"*) and **STOP condition 10** (*"the authorized UC-4 scope proves insufficient"*).

---

## 1 · Decision required

> **Does the PO/ARB (a) authorize a UC-4 slice bounded to the restoration branch, (b) authorize a legitimate `HaltedAtGate` source, or (c) leave UC-4 held?**

## 2 · Why — the exact blocking fact

**`ExpiryConsequence::onHaltedRecoveryExpiry()` requires a `HaltedAtGate` argument, and no legitimate source for one exists inside the authorized boundary.**

```php
public static function onHaltedRecoveryExpiry(
    ElectionId $electionId,
    HaltedAtGate $halt,            // ← required by the signature
    RecoveryProcess $process,
    RecordedInstant $reportedAt,
    bool $recoverySucceeded,
    OperationalCondition $operational,
): RecoveryPeriodExpired
```

The only way to obtain one inside the handler would be `new HaltedAtGate($gate, $haltedAt)` — which §6 of the authorization forbids in five verbs (*"create, reconstruct, infer, synthesize, or wire"*), and which UC-3's handler already records would *"invent the halt fact"*.

## 3 · Known facts — verified against the current tree (§20)

| Fact | Evidence |
|---|---|
| `HaltedAtGate` has a **public constructor** `(GateDesignation $gate, RecordedInstant $haltedAt)` | `Condition/HaltedAtGate.php:20-22` |
| **Zero construction sites anywhere in `app/`** | `grep -rn 'new HaltedAtGate\|HaltedAtGate::' app/` → no hits |
| `AcceptanceGateDecision` **carries no halt** — UC-3's 2026-08-17 note still true today | `grep 'HaltedAtGate\|halt'` on `Gate/AcceptanceGateDecision.php` → no hits |
| `RecordedOperationalStatus` **could** supply it (returns `ElectionOperationalStatus`, which carries `?HaltedAtGate`) but is **not in the injected collaborator set**, and wiring it is **Act D** | `Port/RecordedOperationalStatus.php:56-58` *"NO ADAPTER AND NO CALLER IS AUTHORIZED"* · `Condition/ElectionOperationalStatus.php:17-18` · **STOP 6** |
| The protocol is **append-only, no read side** | UC-3 docblock `:77-82` |
| **The handler MUST call P-6 even to refuse** — the refusal tests pin **P-6's own reason strings** | `RefusalTaxonomyRedTest:113-114` asserts the reason contains **`EM-GOV-063`**, with the comment *"§8d verbatim: on precondition failure the handler appends the refusal"* · `ReportPeriodExpiryHandlerRedTest:98` *"The refusal carries P-6's business reason"* |
| The established pattern is **call → catch → record → rethrow**, not handler-side pre-checking | `Q-REF as fixed at GREEN-2`; UC-2 `RecordVacancyEventHandler.php:104-109`, UC-3 `FillCommitteeSeatHandler.php:114-120` |

### Exercise inventory — 5 of 7 blocked

| UC-4 exercise | Branch | Needs `HaltedAtGate`? |
|---|---|---|
| `ReportPeriodExpiryHandlerRedTest` test 1 — expired halted recovery | halted | 🔴 **yes** |
| test 2 — expired restoration | restoration | ✅ no |
| test 3 — unexpired report refused | halted | 🔴 **yes** (P-6's reason) |
| test 4 — halted branch while Inoperative refused | halted | 🔴 **yes** (P-6's reason) |
| `HistoryKindAssignmentRedTest` scenario E | halted | 🔴 **yes** |
| `HistoryKindAssignmentRedTest` scenario F | restoration | ✅ no |
| `RefusalTaxonomyRedTest` expiry-precondition | halted | 🔴 **yes** (asserts `EM-GOV-063`) |

**The restoration branch is fully implementable today:** `onRestorationExpiry(ElectionId, RecoveryProcess, RecordedInstant)` needs neither a halt fact nor the gate decision.

## 4 · One M-7 ambiguity is now CLOSED by evidence (not escalated)

The prior analysis left open whether the handler pre-checks preconditions (`§8d`'s *"preconditions: kind · ¬Inoperative · ¬recoverySucceeded · isExpiredAt"*) or delegates to P-6. **The refusal tests settle it: P-6 checks, the handler records P-6's reason.** A handler authoring its own refusal text would fabricate a domain reason in the application layer — forbidden by `ADR-1` §6(b)/`6-guard`. ✅ **No decision needed on this point.**

## 5 · Unknowns — deliberately not resolved here

- Which component *should* own halt production (`ElectionOperationalStatus::operativeHalted()` is the only factory and nothing calls it).
- Whether a halt source implicates `BND-3` (overlay boundary) — `RecordedOperationalStatus` declares it selects nothing within `BND-3`, but a **producer** may differ.
- Whether `recoverySucceeded` derives from AG-2 (§13 `A-4`, still undetermined — **not** resolved by this escalation).

## 6 · Options — neutral, not ranked

| | Option | What it would authorize |
|---|---|---|
| **(a)** | **Branch-bounded slice** — implement the restoration branch only | 2 of 7 exercises go GREEN. The halted branch keeps an explicit pending guard. ⚠️ **This is a scoping act**: the decision record §12 already states *"bounding a slice by branch is itself a scoping act requiring its own authorization."* Does **not** touch `BND-1`/`BND-3`/Acts C-D. |
| **(b)** | **Authorize a `HaltedAtGate` source** | Either a domain slice giving halt production a legitimate owner, or Act C/D wiring of `RecordedOperationalStatus`. ⚠️ Both are **currently unauthorized** (STOP 5 / STOP 6), and (b) may implicate `BND-3`. Would make all 7 reachable. |
| **(c)** | **Leave UC-4 held** | Nothing implemented. `PBDIGIT-72` unchanged (it requires **both** causes covered; cause 1 is exactly this gap). |

⛔ **No option selected, no ranking, no recommendation.** Choosing the slice shape is a scoping act reserved to PO/ARB.

## 7 · Non-decisions — what this escalation leaves untouched

`ADR-1` §6(a)/(b)/(c) unamended · `BND-1` unresolved · `BND-3` unresolved · absence semantics undefined and uninvented · no `Unknown`/sentinel/fallback/exception semantics · no new domain contract · Act C not authorized · Act D not authorized · domain model unchanged · `GREEN-*` not renumbered · `PBDIGIT-72` unmodified · the Option-A hold release **stands as recorded** — this escalation does not question it.

## 8 · Required human act

**Select (a), (b) or (c).** If **(a)**, the branch-bounded scope must be authorized explicitly, since it narrows the slice the ARB authorized. If **(b)**, name which route (domain slice for halt production, or Act C/D wiring) — both need their own authorization.

> ## **Stopped. No improvisation, no inference, no broadened authorization.**

**Traceability:** ARB Chief decision 2026-09-30 (decision record §4, Block A) · decision record §§11-13 · final authorization brief · `ExpiryConsequence.php:38-45,:73-90` · `HaltedAtGate.php:20-22` · `Port/RecordedOperationalStatus.php:56-58` · `Condition/ElectionOperationalStatus.php:17-18,:27` · `RefusalTaxonomyRedTest.php:102-114` · `ReportPeriodExpiryHandlerRedTest.php:85-120` · `RecordVacancyEventHandler.php:104-109` · `FillCommitteeSeatHandler.php:77-82,:114-120` · `PBDIGIT-72:42-47` · STOP conditions 5, 6, 10
