# WP-7 Slice 7B — GREEN Report

**Date:** 2026-08-01 · **Phase:** GREEN · **Authorization:** R-58
**Status:** ✅ **GREEN — 11/11 keystones pass; full merge gate PASSES.**

---

## Execution

```
Slice 7B suite:   11 tests · 15 assertions — OK
composer merge-gate:
  Architecture fitness   149 tests · 632 assertions — OK
  Deptrac                0 errors  (deptrac.yaml unmodified)
  PHPStan (greenfield)   No errors (level max)
  Widened regression     266 tests · 665 assertions · 0 failures
MERGE GATE: PASS
```

| Keystone | Result |
|---|---|
| K1 factory-only construction | ✅ |
| K2 EPW = CW + MAD + LSM | ✅ |
| K3 open inside · K4 closed after · K5 closed before anchor | ✅ |
| K6 business values only | ✅ |
| K7 MAD via **Election's own** port | ✅ |
| K8 non-positive rejected, never clamped | ✅ |
| K9 absent anchor ⇒ open | ✅ |
| K10 immutable · no identity | ✅ |

**No test was modified during GREEN.**

## What was written

| File | Role |
|---|---|
| `Election/Domain/EvidencePreservationWindow.php` | the value — private ctor, `forElection()`, `closesAt()`, `isOpenAt()`, rejects non-positive terms |
| `Election/Application/Service/ResolvesEvidencePreservationWindow.php` | resolves anchor + three durations via Election's own port, invokes the factory, **fails closed on an absent anchor** |

**No DI binding was added.** The service depends only on the `EvidencePreservationDurations` interface, bound in 7A, so the container auto-wires it. The deliverable list anticipated a binding; **none was needed, and adding one would have been ceremony.**

## PHPStan — 10 errors, fixed at root

The first gate run produced **10 PHPStan errors, all one root cause**: the legacy `App\Models\Election` is an untyped Eloquent model, so every attribute is `mixed`.

**Fixed by narrowing before use** — `getAttribute()` plus an explicit type check — **not by suppression, and not by a baseline entry.** Same remedy WP-6 applied to the same class of error. **Zero suppressions in this slice.**

## The INTERIM, stated plainly

**The EPW anchor remains an open Q-2 decision.** `anchorOf()` takes the first available of `results_published_at` → `end_date` → `archived_at`, marked INTERIM in the code.

**It resolves which date to measure from. It invents no duration** — all three business values still arrive through the port, and an election with no candidate yields **no window** rather than a substituted one. **When Q-2 rules, that method collapses to one named field and nothing else changes.**

## Scope

| | |
|---|---|
| 7C — the deletion guard | ⛔ untouched, unauthorized |
| `audit:cleanup` | ⛔ unchanged |
| F-WP6R-1 | ⛔ excluded |
| Tests modified during GREEN | none |
| Architecture / governance modified | none |

**7B remains inert — nothing consumes the window. The first observable behaviour change is still 7C.**

## One observation on F-WP6R-1

Risky tests moved **99 → 101**, and the two additions are **this slice's own Feature tests**. That is further evidence F-WP6R-1 is a **global Feature-test condition**, not localised to any slice. **Still recorded, still unauthorized, still not acted on.**

## Definition of Done

| | |
|---|---|
| Keystones pass | ✅ 11/11 |
| Merge gate | ✅ PASS |
| Developer guide | ✅ `developer_guide/election/07_evidence_preservation_window.md` + index |
| Suppressions | ✅ none |

---

> **Slice 7B GREEN is complete. All authorized keystones pass, the full merge gate passes, and no unauthorized functionality was introduced. Acceptance is the ARB's (queue item 9).**

---

**Traceability:** R-58 · R-57 (the mechanism K7 asserts) · R-56 · Policy 2 · §142 · AP-1 (K8, D4) · AP-2 (K7) · P7B-2 (K9's home).
