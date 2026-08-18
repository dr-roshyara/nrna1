# 05 · `EM-DOM-001` `H-2` — the `w8` regression lock for `D2`

**Work item:** `EM-DOM-001` (`PBDIGIT-68`) · **slice:** `H-2` (regression lock) · **date:** 2026-08-19
**Commits:** `4da89888` (RED — the lock alone, with mutation evidence) → this guide (GREEN — **no production change was required**)

> ## ⛔ Read this before you use it
> **This is a REGRESSION LOCK, not a RED.** Every assertion in it was already satisfied by the frozen domain core on arrival. It changes **no behaviour**, adds **no** class to `app/`, and selects **no representation** for the halt-absent path. `BND-1` (`D3`) and `BND-3` (`D4`) stay deferred; ADR-2 is not reopened; `GREEN-5` stays **STOPPED**.

## Purpose

`D2` (PO/ARB, 2026-08-18) sharpened ADR-2 §6 without amending it:

> *"Restoration and Resumption are distinct domain concepts. The `w8` case represents Restoration without a prior halt. It therefore has a known causal origin but has no resumption target."*

That meaning was **decided but unpinned**. The domain already behaved this way, so nothing enforced it: any later edit to `ElectionOperationalStatus` that let restoration acquire a halt would have collapsed the two paths **silently**, and ADR-2 §6(b)'s prohibition on a fabricated `HaltedAtGate` / sentinel / *"unknown"* value would have been breached with no test to notice. This step closes that gap and nothing else.

⚠️ **The vocabulary matters and is easy to get wrong.** `w8` lacks the prior **halt**. It does **not** lack a `RecoveryProcess` — the pinned fixture seeds `RecoveryProcess(PeriodKind::CommitteeRestoration, 20)`. ADR-2 §5a records the correction; the lock encodes the corrected form.

## Where it fits

`tests/Unit/Contexts/Election/OperatingCore/` — the domain-level unit suite, alongside `ConditionSemanticsTest` whose test 26 pins the **halted** side of P-7. This lock pins the **halt-absent** side and the discrimination between them. Pure `PHPUnit\Framework\TestCase`, no Laravel bootstrap, no database.

## Key files

| File | Role |
|---|---|
| `tests/Unit/Contexts/Election/OperatingCore/RestorationWithoutPriorHaltRegressionLockTest.php` | the lock — the **only** file this slice adds (`4da89888`) |
| `…/Condition/ElectionOperationalStatus.php` | the behaviour under lock — **unchanged** |
| `…/Policy/ResumptionTarget.php` (P-7) | the sole producer of a resumption target — **unchanged** |
| `…/Recovery/RecoveryProcess.php`, `…/Recovery/PeriodKind.php` | the known causal origin — **unchanged** |
| `…/Policy/ExpiryConsequence.php` | the kind discipline separating the two period kinds — **unchanged** |
| `tests/Unit/…/OperatingCoreApplication/FillCommitteeSeatHandlerRedTest.php` | where `w8` is pinned; consulted for its SHAPE only, **not modified** |

## How it works

Three tests, each derived from `D2` and from behaviour already committed.

**1 · Restoration does not acquire or fabricate a halt.** The `w8` operational shape is an election that was never halted — its gate became `Unachievable` by vacancy arithmetic, so it became `Inoperative` with no halt to retain:

```php
$restored = ElectionOperationalStatus::operative()->becameInoperative()->restored();

$this->assertSame(OperationalCondition::Operative, $restored->condition()); // EM-GOV-059(c)
$this->assertFalse($restored->isHalted());
$this->assertNull($restored->haltedAtGate());                               // ADR-2 §6(b)
```

`becameInoperative()` and `restored()` both carry `$this->haltedAtGate` forward — `EM-GOV-059(b)` **retains** a halt where one exists. On this path there is nothing to retain, and the lock says nothing may be invented to fill the gap.

**2 · Therefore no resumption target — and the halted path is untouched.** `ResumptionTarget::resolve()` is the domain's only producer of a resumption target, and its sole parameter is a **non-nullable** `HaltedAtGate`. That is asserted by reflection, so *"no resumption target"* rests on the domain's own refusal rather than on a caller's restraint:

```php
$parameterType = (new ReflectionMethod(ResumptionTarget::class, 'resolve'))->getParameters()[0]->getType();
$this->assertSame(HaltedAtGate::class, $parameterType->getName());
$this->assertFalse($parameterType->allowsNull());
```

The halt-absent status has `haltedAtGate() === null`, so it can supply P-7 with nothing; the halted status, taken through the identical two calls, still resolves `GateDesignation::Second`. The closing assertion pins the **discrimination** — the thing a regression would destroy.

**3 · The known causal origin is retained.** A `RecoveryProcess(PeriodKind::CommitteeRestoration)` exists and reports its own kind and election. `PeriodKind::HaltedElectionRecovery` does not, hence there is no halt-derived gate — and the two kinds are never merged (`EM-GOV-059(a)`): `ExpiryConsequence::onHaltedRecoveryExpiry()` refuses this origin **on kind alone**, even when a halt is supplied to it. Known causal origin ≠ halt provenance.

## Testing — and how fail-first was demonstrated

```
vendor/bin/phpunit tests/Unit/Contexts/Election/OperatingCore/RestorationWithoutPriorHaltRegressionLockTest.php
  → OK (3 tests, 21 assertions)

vendor/bin/phpunit tests/Unit/Contexts/Election/OperatingCore
  → OK (49 tests, 2478 assertions)     # 46 before this slice, +3
```

⚠️ **The behaviour already passed, so there is no fail-by-absence RED here**, and the `H-2` authorization forbids all three ways of manufacturing one (invent a semantic rule · mutate the committed core · skip fail-first). The **sanctioned route** was used instead — the Act-B step-④ export technique (`67a09e11`):

1. the tree was exported to a scratch directory **outside the repository**, with its own `vendor/`;
2. the lock ran **GREEN there first** (`OK (3 tests, 21 assertions)`), proving the export self-contained — its `app/`, not the repository's, is what loads;
3. **in the copy only**, `ElectionOperationalStatus::restored()` was mutated to fabricate a halt where none was retained (`$this->haltedAtGate ?? new HaltedAtGate(…)`), collapsing the halt-absent path into the halted one — the exact fabrication ADR-2 §6(b) forbids;
4. the lock then failed **2 of 3** inside the export; the verbatim output is recorded in the test's own docblock and in `4da89888`'s message;
5. the export was **discarded**.

**The committed lock passes on the unmutated tree.** That asymmetry *is* the lock.

⚠️ **Unrelated, pre-existing, and not this slice's business:** `tests/Unit/Contexts/Election/OperatingCoreApplication/` carries **15 errors + 1 failure** (GREEN-3…7 pending, `AbsentAggregateReferenceRedTest` RED by design). Identical with and without this file.

## Pitfalls

1. ⛔ **Do not read this lock as a representation decision.** It selects no nullability rule, no sentinel, no `UnknownGate`, no new enum value, no replacement type, no aggregate, no event. `D2` selects none; `D4` defers `BND-3`.
2. ⛔ **Do not extend it to `ElectionRestored`.** That domain fact's `GateDesignation` is non-nullable and ADR-2 §5f leaves the representational consequence **open**. The lock is silent on it, deliberately.
3. ⚠️ **Do not "strengthen" it into a universal claim** such as *"no resumption target is obtainable by any means."* That is not executable; the assertion made is the narrower, checkable one about P-7's signature. Guide 04 records the same boundary for `H-1` (`C-4`).
4. ⚠️ **`w8` is halt-absent, not `RecoveryProcess`-absent.** The broader path — no `RecoveryProcess` of either kind — is structurally reachable but **pinned by no test**. Do not assert it here; it is not `D2`'s subject.
5. ⚠️ **If a future change makes this lock fail, the change is the defect** — not the lock. Fix the change, or bring a new PO/ARB decision; do not relax the assertions.
6. ⚠️ **Keep the mutation evidence with the test.** It is the only record that fail-first was honestly established for a lock that passes on arrival.

**Traceability.** `D2` *(verbatim — `docs/publicdigit/reviews/2026-08-18-EM-DOM-001-decision-recording-surface.md`)* · `H-2` authorization *(`docs/publicdigit/reviews/2026-08-19-EM-DOM-H2-w8-regression-lock-authorization.md`)* · `ADR_20260817_2300` §6(a)/(b)/(h), §5a, §5e, §5f · `EM-GOV-059(a)(b)(c)` · `EM-GOV-062` · P-7 · `w8` pin *(`FillCommitteeSeatHandlerRedTest::test_w8_…`)* · Act-B export technique `67a09e11` · Act-B baseline `1f4b4c5f` *(frozen core unchanged by this slice: one added file total, `Port/RecordedOperationalStatus.php`, none modified)* · guide 04 · commit `4da89888` (RED) · `EP-02`/`R-34` *(verification is a separate designation; this lane does not verify its own work)*.
