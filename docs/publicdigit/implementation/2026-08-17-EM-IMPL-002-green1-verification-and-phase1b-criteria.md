# `EM-IMPL-002` — GREEN-1 independent verification · Phase-1B doubles review criteria

**Type:** Governance verification + criteria registration · **Date:** 2026-08-17

## 0 · ⚠️ Correction to Governance's previous report

**My last report stated GREEN was unstarted and the lane paused. That was STALE:** the GREEN-1 surface had already been committed in the direct channel at **`d2a0fe7c`** (20:28). The PO's status block (*"GREEN-1 Surface ✅ complete"*) was correct and my statement was not. **Corrected here because it changes the picture, not merely the wording.**

## 1 · GREEN-1 verified INDEPENDENTLY by Governance (not from the commit message)

| Check | Result |
|---|---|
| **RED→GREEN ordering** | ✅ **git-provable:** RED `1f4b4c5f` **precedes** GREEN-1 `d2a0fe7c`. The two-phase discipline held even though the direct channel executed it. |
| D-1 wall | ✅ **the surface references the port NOWHERE** |
| Rule arithmetic (G-5) | ✅ none — no `2/3`, `ceil`, `intdiv`, threshold comparison anywhere |
| Domain-predicate reproduction | ✅ none — no `if` over `unableToFunction`/`failed`/`succeeded`/`isExpired` |
| Framework freedom | ✅ no Illuminate/Carbon imports |
| Vacuous-pass protection | ✅ **all five handlers throw** (`BadMethodCallException` ×2 each) — behaviour cannot pass by accident: application suite **36 failed / 16 passed** *(the 16 = structural guards, written BEFORE the code and now legitimately green — a genuine RED→GREEN transition on the structural axis)* |
| Frozen core | ✅ **42 passed (2434 assertions)** — assertion growth +14 is the frozen suite's own D-1 scan now covering the new files: **the baseline verifies the new code, by construction** |
| Domain core | ✅ **byte-identical** since `1f4b4c5f` (empty diff) — the freeze was not widened |
| Q-UC5 | ✅ **still OPEN with a scheduled STOP at GREEN-6** — the record's instruction was honoured, not bypassed |

> **GREEN-1 (surface only) is verified consistent with the granted boundary. The three binding GREEN obligations are not yet exercisable — bodies throw — so they bind from GREEN-2 onward, where behaviour appears.**

## 2 · Phase-1B — the six Support doubles: the PO's review criteria, registered as the checklist

**Per double:** ① implements only the port contract · ② preserves domain authority · ③ introduces no hidden behaviour · ④ records test information without becoming a fake production service · ⑤ creates no authority production does not have.

**The four named watch items:** **`ProtocolAppend` double** ⛔ must not become a second event store with invented rules · **`FixedServicePolicySnapshot`** ⛔ policy data only, never consequences · **`FixedInstantSource`** ⛔ time only, never schedule decisions · **repository doubles** ⛔ store aggregates only, never derive domain meaning.

## 3 · The application-layer invariant, generalised and registered

**The application layer MAY:** receive · load · translate · delegate · append. **It MAY NOT:** interpret · calculate · decide · govern. **Binding on all five handlers, not only UC-4.**

**Traceability.** RED `1f4b4c5f` · GREEN-1 `d2a0fe7c` · File-1 acceptance `d9227f84` · grant G-1…G-5/Q-1…Q-3 · this verification's own commands.
