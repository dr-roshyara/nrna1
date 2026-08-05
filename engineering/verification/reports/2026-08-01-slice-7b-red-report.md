# WP-7 Slice 7B — RED Report

**Date:** 2026-08-01 · **Phase:** RED · **Authorization:** **R-58** on the plan approved by R-56 and corrected by R-57
**Status:** 🔴 **RED CONFIRMED — 11 tests, 11 failing, no production code written.**
**Repository Integrity Gate:** ✅ PASSED — working tree carries only the two new test files.

---

## 1. Actual execution

```
tests/Unit/Contexts/Election/Domain/EvidencePreservationWindowTest.php
  Tests: 9 · Assertions: 1 · Errors: 8 · Failures: 1

tests/Feature/Contexts/Election/EvidencePreservationWindowResolutionTest.php
  Tests: 2 · Assertions: 0 · Errors: 2
```

**Two causes, both intended:**

> `Class "App\Contexts\Election\Domain\EvidencePreservationWindow" not found`
> `Class "App\Contexts\Election\Application\Service\ResolvesEvidencePreservationWindow" does not exist`

**K8 reports as a *failure* rather than an *error*** because `expectException` caught `Error` (class missing) instead of `InvalidArgumentException`. **Same single cause — not a defective test.** Once the class exists it will assert the real fail-closed behaviour.

**No test fails for an unexpected reason. None passes accidentally.**

## 2. Keystone verification

| Keystone | Test | Written | Failure confirmed |
|---|---|---|---|
| **K1** factory-only construction | `test_k1_…` | ✅ | ✅ class not found |
| **K2** EPW = CW + MAD + LSM | `test_k2_…` | ✅ | ✅ |
| **K3** open inside the window | `test_k3_…` | ✅ | ✅ |
| **K4** closed after the window | `test_k4_…` | ✅ | ✅ |
| **K5** closed before the anchor | `test_k5_…` | ✅ | ✅ |
| **K6** business values only | `test_k6_…` | ✅ | ✅ |
| **K8** non-positive duration rejected, never clamped | `test_k8_…` | ✅ | ✅ *(reports as failure — §1)* |
| **K10** immutable | `test_k10_…immutable` | ✅ | ✅ |
| **K10** no identity — equal inputs, equal windows | `test_k10_…equal` | ✅ | ✅ |
| **K7** MAD via **Election's own** port | `test_k7_…` | ✅ | ✅ service not found |
| **K9** absent anchor ⇒ open | `test_k9_…` | ✅ | ✅ service not found |

**10 keystones · 11 tests** — K10 carries two, because *immutability* and *no identity* are distinct properties of a Value Object and a single test would prove only one.

## 3. Where each keystone lives, and why

| Keystones | Home | Reason |
|---|---|---|
| K1–K6, K8, K10 | **`Election\Domain\EvidencePreservationWindow`** (unit test, no framework) | the value and its arithmetic |
| **K7, K9** | **7B's application-layer collaborator** | **P7B-2**: the VO takes business values only, so it **never resolves a port** (K7) and **can never observe a missing anchor** (K9) |

**Why 7B contains an application-layer collaborator at all** *(the P7B-3 question, settled)*: 7B's objective requires MAD **through Election's own port**, and the construction commission forbids the VO a port. **A pure VO cannot satisfy 7B's own objective.** Plan §2 names the responsibility directly — application orchestration *"compute an election's EPW"*.

**Named `ResolvesEvidencePreservationWindow`**, following the house verb-phrase convention (`CoordinatesContestation`).

## 4. ⚠️ The open business decision, deliberately not settled by a test

**The EPW anchor — which date anchors the window — remains an open Q-2 decision.**

**The keystones are written so that no test depends on which candidate is chosen:**

- **K3/K4/K5** pass the anchor **explicitly** to the VO — no business decision required.
- **K9** exercises the case where **no candidate is present at all** (`end_date`, `results_published_at`, `archived_at` all null), which holds under **every** possible choice.

> **A keystone must not quietly settle a business value.** Asserting *"`end_date` is the anchor"* would have decided Q-2's question in a test file — the AP-1 failure mode, in a new place.

## 5. Constraints

| Constraint | Status |
|---|---|
| No production code written | ✅ **only two test files** in the working tree |
| Scope limited to 7B | ✅ **7C untouched** — no deletion guard, no `audit:cleanup` change |
| F-WP6R-1 excluded | ✅ |
| Architecture / governance unmodified | ✅ |
| Approved plan unmodified | ✅ *(R-57's correction was a governance act, already applied)* |
| No business value invented | ✅ §4 |

## 6. What GREEN will require

*(Stated for review; **not** implemented.)*

| Component | Location |
|---|---|
| `EvidencePreservationWindow` — private ctor · `forElection()` · `closesAt()` · `isOpenAt()` · rejects non-positive terms | `app/Contexts/Election/Domain/` |
| `ResolvesEvidencePreservationWindow` — resolves anchor + three durations via **Election's own port**, invokes the factory, **fails closed on an absent anchor** | `app/Contexts/Election/Application/Service/` |
| DI binding | `ElectionServiceProvider` |

**Everything lands inside `app/Contexts/Election/`**, so Deptrac, greenfield PHPStan and `test_greenfield_domain_is_framework_free` all cover it — and the merge gate can complete, which R-55 restored.

---

> ## **Slice 7B RED is complete. All authorized keystone tests (K1–K10) have been written, executed, and confirmed to fail for the expected reasons. No production code has been introduced. Engineering is ready to enter the GREEN phase upon authorization.**

---

**Traceability:** **R-58** (execution authorized) · **R-57** (plan mechanism corrected — K7 asserts the restored mechanism) · **R-56** (plan) · **R-44** (Election's own port) · **R-45** (Election answers; Audit acts — 7B builds the answering half only) · **P7B-2** (K7/K9 allocation) · **P7B-3** (withdrawn — why the collaborator is 7B's) · Policy 2 · §142 · **AP-1** (K8). **No production code · no scope extension · no architecture or governance modified · no business value settled by a test.**
