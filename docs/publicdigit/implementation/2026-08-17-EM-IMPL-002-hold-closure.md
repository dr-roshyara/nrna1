# `EM-IMPL-002` — ARCHITECTURE HOLD: lane work COMPLETE; two rulings on the PO's desk

**Date:** 2026-08-17 · **Verified by Governance's own runs and scans.**

## 1 · The three hold items — all delivered, none decided by the lane

| Item | Delivered | Governance verification |
|---|---|---|
| **1 · Absence-semantics ADR** | `docs/publicdigit/adr/ADR_20260817_2145_Aggregate_Absence_Semantics.md` @ `783c1f7e` | ✅ **§6 decision block genuinely BLANK**; options A (invalid request) · B (integrity failure) · C (lifecycle state) · D (composite) + the subsidiary shape question, each with consequences. ⚠️ **Its sharpest finding: `A-5` already rules the SEAT case but is SILENT on the AGGREGATE case — so extending it by analogy would be exactly the silent choice the ADR exists to prevent.** |
| **2 · `Q-RESTORE` provenance** | `…-q-restore-recovery-origin-provenance-investigation.md` @ `783c1f7e` | ✅ five options, **each labelled with the authorization it needs**: A (AG-3 owns origin — the PO's preference) and B (provenance on the started fact) **both require DOMAIN authorization** · C (protocol read port) **requires new-port authorization** · D (ruled proxy) needs only a ruling but is weakest on sovereignty · **E (command carries it) recorded as REJECTED — it would let an appointment body decide progression meaning.** ⚠️ **Two findings worth the PO's attention: option A retains a fact the F-2 handler ALREADY HOLDS AND DISCARDS, which is why it reads as the natural home; and NO option answers restoration with no prior halt (the `w8` case), so any ruling needs a companion decision.** |
| **3 · The two RED pins** | `ConstitutionalValueConsumptionRedTest` @ `783c1f7e` · `AbsentAggregateReferenceRedTest` @ `b8836ee8` | ✅ verified below |

## 2 · The detector, re-verified after its rewrite (it had been withheld)

`php -l` clean · **every method declared once** · **detection takes `$body`, not the whole source** (`$source` no longer appears at all — the file is in fact cleaner than the lane's own description of it) · and the reported violations are **exactly the two real sites**:

```
FillCommitteeSeatHandler.php: $committee (from a repository find())
FillCommitteeSeatHandler.php: $decision (from the nullable helper establishedAcceptanceDecision())
```

**Both original defects are fixed: the `?? throw` guards in UC-1/UC-2 no longer read as unguarded, and the second UC-3 violation — previously MASKED by a same-named `$decision` guard in a different method — is now visible.** ✅ **Withholding it was right, and the corrected version earns the commit.**

## 3 · Suite state, and why the failure count rose on purpose

| | |
|---|---|
| Application | **16 failed / 40 passed** (was 15/37 — four tests added) |
| Frozen core | **42 passed / 2434** unchanged |
| Domain core | byte-identical |
| **`app/` across the entire hold** | ✅ **zero production change** (verified by diff across the hold's four commits) |

**The +1 failure is the point:** the constitutional-consumption pin **passes** as a regression lock over behaviour already true, while the absent-aggregate pin **fails as genuine RED** whose GREEN is the authorized normalization slice. **A hold that produced a new honest red is a hold that found something.**

## 4 · On the PO's desk — two rulings, then GREEN-5

**① ADR §6:** which meaning absence carries **(a)** · the shape **(b)** · whether normalization of UC-1/2/3 is authorized **as one slice (c)** · whether a RED pin must precede it **(d — already delivered, so this is now "confirm it")**.
**② Provenance ownership:** A/B (domain authorization) · C (new port) · D (ruling only) — **plus the companion decision on restoration with no prior halt.**

**Then GREEN-5.** ⛔ **Until both: no production code moves; the failing pin stays red by design.**

**Traceability.** Hold registration `bf6f5141` · withholding record `035db555` · commits `783c1f7e` (3 artifacts) → `b8836ee8` (RED amendment) · the promoted boundary rule.
