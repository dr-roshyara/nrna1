# WP-7 Slice 7A — RED Report

**Date:** 2026-08-01 · **Phase:** RED · **Authorization:** R-47 (ARB) on the plan approved by R-46 (Decision Authority)
**Status:** 🔴 **RED CONFIRMED — 8 keystones failing for the expected reason. NO production code written.**
**Repository Integrity Gate:** ✅ PASSED — only two new test files in the working tree.

> ## ⚠️ ONE SCOPE DECISION I MADE, AND WHY — Keystone 8 and the Value Object are 7B, not 7A
>
> The commission listed **Keystone 8 (absent anchor ⇒ window open)** and **`EvidencePreservationWindow`** under 7A's components, while also instructing *"do not extend scope beyond 7A."* **Those two instructions conflict**, so I resolved it against the plan the Decision Authority approved (**R-46**), which is binding:
>
> | Plan slice | Owns |
> |---|---|
> | **7A** *(authorized)* | *"CW and LSM resolvable per election type, INTERIM-marked, fail-closed on absence"* — **config · port · adapter** |
> | **7B** *(NOT authorized)* | *"the `EvidencePreservationWindow` Value Object … exposes is the window open at T?"*, acceptance: *"an undecided/absent anchor yields window open"* |
>
> **Keystone 8 is 7B's acceptance criterion verbatim, and the VO is 7B's deliverable.** Writing them now would extend an authorization that is deliberately **slice-granular**.
>
> **They are deferred, not dropped** — they are 7B's first two keystones. **Flagged rather than silently omitted.**

---

## 1. Tests written

**Two files. No production code.**

| File | Purpose |
|---|---|
| `tests/Feature/Contexts/Election/EvidencePreservationDurationsTest.php` | 7A keystones 1–7 **+ R-D1** |
| `tests/Architecture/DurationPolicyOwnershipTest.php` | **C-1** guard *(+ its discrimination self-test)* |

## 2. RED confirmation — the durations keystones

```
Tests: 8, Assertions: 3, Errors: 5, Failures: 3
```

**All eight fail, and all eight for exactly one reason:**

> `Target class [App\Contexts\Election\Application\Port\EvidencePreservationDurations] does not exist.`

| # | Keystone | Failure mode | Expected? |
|---|---|---|---|
| 1 | CW resolves per election type | error — port missing | ✅ |
| 2 | LSM resolves per election type | error — port missing | ✅ |
| 3 | organisation override wins | error — port missing | ✅ |
| 4 | missing CW **throws, never defaults** | failure — got `BindingResolutionException`, expected `RuntimeException` | ✅ *(the container fails before the adapter can)* |
| 5 | non-numeric LSM throws | failure — same | ✅ |
| 6 | non-positive CW throws, **not clamped** | failure — same | ✅ |
| 7 | MAD **consumed, not copied** (AP-2) | error — port missing | ✅ |
| R-D1 | both adapters agree on MAD | error — port missing | ✅ |

**Keystones 4–6 report as *failures* rather than *errors* because `expectException` caught the container's exception instead of the adapter's.** That is still the missing port — **not a defective test.** Once the port exists they will assert the real fail-closed behaviour.

**No test fails for an unexpected reason. No test passes accidentally.**

## 3. C-1 guard — green from birth, and proven not to be ceremonial

```
Tests: 3, Assertions: 5 — OK
```

**A guard asserting an absence passes while the absence holds. So on its own, C-1 proves nothing** — and by the platform's own **Methodological Fitness Rule**, *"a criterion that never rejects a candidate is presumed ceremonial."*

**So the guard tests itself.** `test_the_detector_would_have_caught_ap1` runs the detector against three synthetic samples:

| Sample | Detector | Correct? |
|---|---|---|
| `max(1, (int) $config->get(...))` — **AP-1's actual shape** | ✅ **rejects** | ✅ |
| `$config->get('x.days') ?? 60` — a substituted business value | ✅ **rejects** | ✅ |
| fail-closed code: `if ($days < 1) { throw … }` | ✅ **accepts** | ✅ — **a comparison is not a clamp** |

**The guard demonstrably catches the defect that passed every gate in WP-6, and does not false-positive on the compliant code that replaced it.** The detector strips comments and docblocks first, so prose cannot trip it.

**C-1b** asserts MAD has **exactly one home** across the duration configs — the AP-2 defect, now executable.

**One honest limit:** C-1's reach is a **hand-maintained list of duration adapters**. An adapter not on the list is unguarded. The list carries that warning in its docblock; **it is the same "manually synchronized inventories drift" risk this programme has already named**, accepted here because the alternative — scanning every file for `max(` — would false-positive everywhere.

## 4. Architecture suite — unaffected

```
Tests: 149, Assertions: 632 — OK (146 before; +3 from C-1)
```

**No existing test changed. No existing test broke.**

## 5. Constraints — verified, not asserted

| Constraint | Status |
|---|---|
| No production code written | ✅ `git status` shows **only the two test files** |
| Scope not extended beyond 7A | ✅ Keystone 8 + the VO **deferred to 7B, and flagged** |
| No duration defined, defaulted, clamped or substituted | ✅ **nothing implemented yet**; keystones 4–6 exist precisely to forbid it |
| MAD not copied into a retention config | ✅ keystone 7 + C-1b assert it; **no config file written yet** |
| Adjudication's port not imported by Election production code | ✅ no production code exists. *(The **test** imports `AdjudicationDurations` deliberately, to prove R-D1's cross-adapter agreement — a test is not a context crossing, and Deptrac scans `app/`, not `tests/`.)* |
| Audit evidence writing unchanged · artifacts B/C untouched | ✅ |
| Architecture and governance not reopened | ✅ |

## 6. What GREEN will require

*(Stated for review, **not** implemented.)*

| Component | Location |
|---|---|
| Port `EvidencePreservationDurations` — CW · MAD · LSM in Election's language | `app/Contexts/Election/Application/Port/` |
| Adapter `ConfiguredEvidencePreservationDurations` — precedence org → type → default, **fail closed** | `app/Contexts/Election/Infrastructure/Config/` |
| Config `election_preservation.php` — `contestation_window_days` · `legal_safety_margin_days` · `per_election_type` · `per_organisation`, **INTERIM-marked**, **no MAD key** | `config/` |
| DI binding | `ElectionServiceProvider` *(mirrors `AdjudicationServiceProvider`)* |

**Everything lands inside `app/Contexts/Election/`** — so Deptrac, greenfield PHPStan and the architecture suite all cover it, exactly as the alignment commission predicted. **That prediction becomes an executed result at GREEN**, which is where A-1's one labelled evidence limit finally closes.

---

> ## **RED is confirmed for slice 7A. Eight keystones fail for the expected reason; the C-1 guard is green and proven to discriminate; the architecture suite is unaffected; no production code exists.**
>
> **Requesting ARB review of this RED report before GREEN**, per the commission's success criteria.

---

**Traceability:** WP-7 plan §5 slice 7A (approved, R-46) · authorization R-47 · A-1 realization R-44 (Election's own consumer-side port) · **AP-1** (fail closed — keystones 4–6, C-1a) · **AP-2** (one home — keystone 7, C-1b) · **R-D1** (cross-adapter agreement) · implementation guard commission (C-1) · house precedents `ConfiguredAdjudicationDurations` and `AdjudicationHorizonTest` (incl. the `DateInterval::$days` vs `->d` lesson) · Methodological Fitness Rule (the standard applied to C-1 itself). **No production code · no scope extension · no architecture or governance reopened.**
