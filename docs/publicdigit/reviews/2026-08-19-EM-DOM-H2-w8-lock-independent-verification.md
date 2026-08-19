# `EM-DOM-001` `H-2` — independent verification of the `w8` regression lock

# ✅ **H-2 LOCK — VERIFIED**

**Verifier:** an independent verification session · 2026-08-19 · **no hand in authoring the lock** (`4da89888` / `3da3053d` were written by a different lane).
**Designation basis:** `EP-02` / `R-34` — engineering supplies evidence and never accepts its own work.
**Subject:** `tests/Unit/Contexts/Election/OperatingCore/RestorationWithoutPriorHaltRegressionLockTest.php` (223 lines) · RED `4da89888` → GREEN `3da3053d`.
**Constraint honoured:** the repository's `app/` and `tests/` were treated as **READ-ONLY**. Every mutation was performed in the verifier's **own** throwaway export outside the working tree. Nothing was repaired; findings are reported only.

> ⛔ **`BND-1` and `BND-3` are not resolved here. ADR-2 is not reopened. `D2` is not reinterpreted. `tests/Feature/Election/ElectionOnlyEntitlementPinTest.php` (quarantined, untracked) was not touched.**

---

## Summary of the nine verification items

| # | Item | Result |
|---|---|---|
| 1 | The lock encodes only `D2`; invents no semantic rule | ✅ **VERIFIED** — every assertion is satisfied by the frozen core as committed |
| 2 | Fail-first evidence is genuine — **independently reproduced** | ✅ **VERIFIED** — verifier's own export + own mutation reproduced `2 of 3` failing |
| 3 | Ordering git-provable: RED before GREEN, RED test-only, GREEN no prod/test change | ✅ **VERIFIED** |
| 4 | The deliberate non-assertions are genuinely absent | ✅ **VERIFIED** |
| 5 | The three assertion groups hold on the real domain | ✅ **VERIFIED** |
| 6 | Core integrity: exactly one `A`, no `M`/`D` | ✅ **VERIFIED** |
| 7 | The 3 PHP 8.5 `SplObjectStorage` deprecations are a rendering artifact | ✅ **VERIFIED** — located at `vendor/sebastian/recursion-context/src/Context.php:148` |
| 8 | Developer guide grounded in committed code, no invented API | ✅ **VERIFIED** |
| 9 | `OperatingCoreApplication/` state unchanged and unrelated | ✅ **VERIFIED** — `56 tests, 15 errors, 1 failure` reproduced |

**Three observations are recorded below as boundary/coverage facts, not defects.** ⛔ **No defect was found that breaches the `H-2` authorization.**

---

## 1 · The lock encodes only `D2` — the central risk the authorization named

`D2`, verbatim (`2026-08-18-EM-DOM-001-decision-recording-surface.md:96-120`):

> *"Restoration and Resumption are distinct domain concepts. / The w8 case represents Restoration without a prior halt. It therefore has a known causal origin but has no resumption target. / … This decision selects no representation for that path."*

Every assertion was checked against that text **and** against the committed behaviour. **All twenty assertion calls plus one `expectException` are satisfied by the frozen core with no production change** — which is the operational proof that no new requirement was imposed (a new requirement would have failed on arrival).

| Assertion site | Committed behaviour it reads | `D2` clause |
|---|---|---|
| `…LockTest.php:113-114` | `ElectionOperationalStatus.php:22-25` — `operative()` constructs with `null` halt | "without a prior halt" |
| `…LockTest.php:117-122` | `ElectionOperationalStatus.php:33-36` — `becameInoperative()` carries `$this->haltedAtGate` forward | `EM-GOV-059(b)` retention; nothing to retain here |
| `…LockTest.php:125-137` | `ElectionOperationalStatus.php:39-42,49-57` — `restored()` carries the same field; `isHalted()` is `$haltedAtGate !== null` | "Restoration without a prior halt" + ADR-2 §6(b) *(fabricated `HaltedAtGate` / sentinel / "unknown" forbidden — `ADR_20260817_2300…md:171`)* |
| `…LockTest.php:149-157` | `ResumptionTarget.php:105` — `resolve(HaltedAtGate $halt)`, sole parameter, non-nullable | "has no resumption target", made **executable** |
| `…LockTest.php:165-173` | `ResumptionTarget.php:107` returns `$halt->gate`; `HaltedAtGate.php:78-81` | "Restoration and Resumption are distinct" — the halted path is unaffected |
| `…LockTest.php:199-209` | `RecoveryProcess.php:59-67`; `PeriodKind.php:17-18` | "has a **known causal origin**" |
| `…LockTest.php:213-221` | `ExpiryConsequence.php:46-48` — the **first** guard, on kind | `EM-GOV-059(a)`: the two kinds are never merged |

**⚠️ The one assertion that had to be examined hardest** is `…LockTest.php:154-157` (`allowsNull() === false`). This is where a lane could have smuggled in a rule. It does not: it reads the **committed signature** of P-7 (`ResumptionTarget.php:105`) by reflection. It asserts what the domain *already expresses* — the route the authorization forbade was *"assert something the domain does **not** currently express"* (`…authorization.md:41`). ✅ Compliant.

**Verified as sound: the "refuses on kind alone" claim at `…LockTest.php:211-212`.** `ExpiryConsequence::onHaltedRecoveryExpiry()` has four guards (`ExpiryConsequence.php:46,49,52,55`). With the lock's arguments — `$operational = Operative`, `$recoverySucceeded = false`, onset `20` + duration `20` (`PolicyBinding.php:25-27`) reported at `100`, so `isExpiredAt()` is `true` (`RecoveryProcess.php:124-127`) — **guards 2, 3 and 4 would each pass.** The kind guard is therefore the *only* reason the exception is thrown. The comment is exact, not decorative.

### Observation O-1 *(boundary, not a defect)* — the arity pin is marginally wider than `D2`'s text

`…LockTest.php:150` asserts `assertCount(1, $parameters)`. `D2` says nothing about P-7's arity; adding even an optional second parameter to `resolve()` would fail the lock. **It asserts a true property of committed code, so it invents no semantic rule** — and without arity + non-nullability together, *"no resumption target"* is not executable at all. Recorded so a future lane is not surprised by it.

### Observation O-2 *(redundancy, not a defect)*

`…LockTest.php:175-179` reduces to `assertNotSame(true, false)` given lines 160 and 168, and `…LockTest.php:205-209` is tautological given line 200. Both are harmless expressions of intent. They cost nothing and lock nothing beyond what precedes them.

### Observation O-3 *(coverage boundary — substantiated, and material to how the lock should be relied on)*

`grep -rl ElectionOperationalStatus app/Contexts/Election` returns **exactly two files**: the type itself and `Port/RecordedOperationalStatus.php` (the Act-B contract, deliberately unimplemented and uncalled). ⇒ **the locked type has no production caller today, and the executed `w8` path in `FillCommitteeSeatHandlerRedTest.php:112-128` does not go through it.** The lock therefore protects the **domain type's** `D2` semantics, not the Application-layer `w8` execution path — a regression introduced in act C/D would not be caught by it. ✅ **This is exactly what the lock claims**: its docblock says the `w8` fixture *"is consulted only for the operational SHAPE it pins"* (`…LockTest.php:44-45`) and disclaims act C and act D. **Recorded as a scope fact, not a defect.**

---

## 2 · Fail-first evidence — INDEPENDENTLY REPRODUCED, not accepted on trust

The verifier did **its own** export and **its own** mutation. The lane's captured output was not relied upon.

**Method.** `app/ tests/ phpunit.xml composer.json composer.lock vendor/` copied to a scratch directory **outside the repository** (158 MB). Self-containment established two ways: `vendor/composer/autoload_psr4.php:5-6` derives `$baseDir` from `__DIR__`, so `'App\\' => $baseDir . '/app'` (line 141) resolves to the **export's** `app/`; and PHPUnit reported the **export's** `phpunit.xml` as its configuration. The untracked quarantined feature test was deleted from the export before any run.

**Step 1 — export GREEN before mutation** (proves the export is a faithful, self-contained copy):

```
OK (3 tests, 21 assertions)
```

**Step 2 — pre-slice baseline measured in the export** (the lock file temporarily moved aside): `OK (46 tests, 2457 assertions)`. **46 + 3 = 49 and 2457 + 21 = 2478 — the GREEN commit's "46 before this slice, +3" is arithmetically confirmed.**

**Step 3 — the verifier's own mutation, in the copy only.** `restored()` made to fabricate a halt where none was retained:

```php
// VERIFIER MUTATION (throwaway export only): fabricate a halt where none was retained.
return new self(
    $this->haltedAtGate ?? new HaltedAtGate(GateDesignation::First, RecordedInstant::fromEpochSeconds(0)),
    OperationalCondition::Operative,
);
```

**Step 4 — the verifier's own captured failure, verbatim:**

```
FF.                                                                 3 / 3 (100%)

There were 2 failures:

1) Tests\Unit\Contexts\Election\OperatingCore\RestorationWithoutPriorHaltRegressionLockTest
   ::test_restoration_without_a_prior_halt_neither_acquires_nor_fabricates_a_halt
D2: the w8 case is Restoration WITHOUT a prior halt; restoration must not manufacture one.
Failed asserting that true is false.
…/RestorationWithoutPriorHaltRegressionLockTest.php:130

2) Tests\Unit\Contexts\Election\OperatingCore\RestorationWithoutPriorHaltRegressionLockTest
   ::test_the_halt_absent_path_has_no_resumption_target_while_the_halted_path_still_has_one
Nothing exists to hand to P-7: the halt-absent restoration has no resumption target.
Failed asserting that App\Contexts\Election\Domain\OperatingCore\Condition\HaltedAtGate Object #543 (
    'gate' => App\Contexts\Election\Domain\OperatingCore\Gate\GateDesignation Enum #424 (First, 'first'),
    'haltedAt' => App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant Object #542 (
        'epochSeconds' => 0,
    ),
) is null.

FAILURES!
Tests: 3, Assertions: 16, Failures: 2, Deprecations: 3.
```

> ## ✅ **This is the lane's recorded output, reproduced independently — down to the object identifiers `#543` / `#424` / `#542` and the exact tally `Tests: 3, Assertions: 16, Failures: 2, Deprecations: 3`. The captured evidence in `4da89888` is genuine.**

**Step 5 — causation isolated, so this is not environment drift.** Reverting the mutation **in the same export, same PHP 8.5.8, same PHPUnit 11.5.6, same directory** returned `OK (3 tests, 21 assertions)`. The repository's own run is GREEN both before and after, and `git status --porcelain -- app tests` shows no modification to `ElectionOperationalStatus.php`. ⇒ **the two failures are caused by the mutation and by nothing else.**

**Step 6 — mutation reverted; the export is not repository content.** The verifier's export lives under the session scratchpad (`…/80ac7b3c-…/scratchpad/verify-h2-export`), outside the repository and outside git. Its mutation was reverted and its GREEN re-confirmed; deletion was declined by the sandbox, so it is left in place as a session-scoped scratch directory. ⛔ **It is not, and never was, repository content.**

---

## 3 · Ordering — git-provable

| Fact | Evidence |
|---|---|
| RED strictly before GREEN | `git merge-base --is-ancestor 4da89888 3da3053d` → true; `4da89888` `2026-08-19 01:01:08 +0200` < `3da3053d` `01:02:53 +0200` |
| RED contains the test **alone** | `git show --name-only 4da89888` → exactly one path, the lock; `1 file changed, 223 insertions(+)` |
| GREEN contains **no** production and **no** test change | `git show --name-only 3da3053d` → `developer_guide/election_operating_core/00_index.md` + `05_step_h2_w8_regression_lock.md` only; `2 files changed, 94 insertions(+)` |
| The one commit between them is unrelated | `1cc86067` `docs(governance): Amendment 1 to the V-3 decision register` — touches `.claude/sessions/2026-08-19.md` and one `docs/publicdigit/reviews/` file; **no `app/`, no `tests/`** |

✅ The authorization's *"RED must be committed separately and demonstrated failing before GREEN"* (`…authorization.md:19`) is satisfied on both halves, by the sanctioned route (`…authorization.md:47-53`).
✅ The authorization's expectation that *"minimum implementation/test change"* would mean **test-only** (`…authorization.md:57-59`) held. The lane did **not** change the core, and correctly did not treat the passing behaviour as a licence to.

---

## 4 · The deliberate non-assertions are genuinely absent

Checked against the executable body (lines 98-223), not the docblock:

| Must be absent | Evidence |
|---|---|
| any representation for the halt-absent path — nullability rule, sentinel, `UnknownGate`, new enum, replacement type, new aggregate, new event | ✅ absent. The twelve imports (`…LockTest.php:7-21`) are all pre-existing committed types plus `ReflectionMethod`/`ReflectionNamedType`. No `app/` file was added by the slice; core diff carries one `A` and no `M` |
| `ElectionRestored` / its non-nullable `GateDesignation` (ADR-2 §5f open) | ✅ the identifier appears **once in the whole file**, at `…LockTest.php:41`, inside the explicit *"does not assert"* list. Zero occurrences in the body |
| lifecycle **phase** (`BND-1`, `D3`) | ✅ no `phase`/`lifecycle` identifier in the body; `GateDesignation` names *which gate*, never a phase |
| act C (persistence/adapters) and act D (Application handlers) | ✅ no `Port\`, no repository, no handler, no `RecordedOperationalStatus` import; the suite is pure `PHPUnit\Framework\TestCase` with no Laravel bootstrap and no database |
| a non-executable *"no target obtainable by any means"* claim | ✅ explicitly disclaimed at `…LockTest.php:46-48`, and the pin actually made is the narrower signature fact. Consistent with `C-4` as already recorded at `developer_guide/election_operating_core/04_step_act_b_recorded_operational_status_contract.md:55,68` |

---

## 5 · The three assertion groups hold on the real domain

Run in the repository working tree, PHP 8.5.8 / PHPUnit 11.5.6:

```
vendor/bin/phpunit tests/Unit/Contexts/Election/OperatingCore/RestorationWithoutPriorHaltRegressionLockTest.php
  → OK (3 tests, 21 assertions)

vendor/bin/phpunit tests/Unit/Contexts/Election/OperatingCore
  → OK (49 tests, 2478 assertions)
```

**(a) Restoration acquires no halt and fabricates none.** ✅ `ElectionOperationalStatus.php:35,41` both pass `$this->haltedAtGate` through unchanged; `isHalted()` (line 49-52) is a pure null test. There is no code path by which `restored()` could produce a halt — which is precisely what the mutation had to add.

**(b) No resumption target for the halt-absent path, while the halted path still resolves — established by reflection, not caller restraint.** ✅ `ResumptionTarget::resolve()` (`ResumptionTarget.php:105`) has exactly one parameter, typed `HaltedAtGate`, non-nullable, and `ResumptionTarget`'s constructor is private with no other public method — so it is the sole producer. The lock reads the signature rather than merely declining to call it, which is the distinction the item required. The halted control still returns `GateDesignation::Second` (`ResumptionTarget.php:107`), so the two paths stay discriminated. *(The halted side is independently pinned by `ConditionSemanticsTest.php:168-175`, test 26 — the lock adds the halt-absent side, it does not duplicate.)*

**(c) The known causal origin is retained.** ✅ A `RecoveryProcess(PeriodKind::CommitteeRestoration)` exists (`RecoveryProcess.php:50-57`, `PeriodKind.php:18`); no `HaltedElectionRecovery` period does; and `ExpiryConsequence::onHaltedRecoveryExpiry()` refuses **on kind alone** (`ExpiryConsequence.php:46-48`, guard #1 — the other three guards each pass under the lock's arguments, as shown in §1). ✅ **The vocabulary correction of 2026-08-18 is honoured**: the `w8` pin does seed `PeriodKind::CommitteeRestoration, 20` (`FillCommitteeSeatHandlerRedTest.php:118`), so `w8` is halt-absent, **not** `RecoveryProcess`-absent — matching ADR-2 §5a/§5e as corrected and `D2`'s recorded wording.

---

## 6 · Core integrity

```
$ git diff 1f4b4c5f HEAD --name-status -- app/Contexts/Election/Domain/OperatingCore
A       app/Contexts/Election/Domain/OperatingCore/Port/RecordedOperationalStatus.php
```

✅ **Exactly one `A`. No `M`. No `D`.** The Act-B baseline is intact: the frozen core is `1f4b4c5f` plus that single added file. `git status --porcelain -- app tests` reports only the quarantined untracked `tests/Feature/Election/ElectionOnlyEntitlementPinTest.php`, which was not touched. ⇒ **the forbidden route *"mutate the frozen core and commit that"* (`…authorization.md:42`) was not taken.**

---

## 7 · The three PHP 8.5 deprecations

Reproduced in the verifier's own mutant with `--display-deprecations`:

```
1 test triggered 3 PHP deprecations:

1) …/vendor/sebastian/recursion-context/src/Context.php:148
Method SplObjectStorage::contains() is deprecated since 8.5, use method SplObjectStorage::offsetExists() instead

Triggered by:
* …::test_the_halt_absent_path_has_no_resumption_target_while_the_halted_path_still_has_one (2 times)
```

✅ **Confirmed on both counts.** They originate in `vendor/sebastian/recursion-context` — PHPUnit's object exporter — and are attributed **only** to the single test whose failure message renders the fabricated `HaltedAtGate` object graph. **Unmutated runs report none**, verified with `--display-deprecations` both in the export and in the repository: `OK (3 tests, 21 assertions)` with no deprecation section. They are an artifact of the mutant's failure *output*, not of the lock, and not of any repository code.

---

## 8 · The developer guide

`developer_guide/election_operating_core/05_step_h2_w8_regression_lock.md` — ✅ **grounded, no invented API.**

- Both code snippets (lines 41-45 and 53-55) are faithful condensations of the committed test and resolve against committed signatures; `ReflectionMethod::getParameters()[0]->getType()` is valid against `ResumptionTarget::resolve()` as committed.
- Every "unchanged" claim in its key-files table (lines 26-32) matches §6's core diff.
- Its test figures (lines 65-69) match the verifier's own runs exactly, **including the `# 46 before this slice, +3` note**, which the verifier measured independently (§2 step 2).
- Its cross-reference to guide 04's `C-4` boundary (pitfall 3, line 88) is real: `04_step_act_b_…md:55,68`.
- Its pre-existing-failure note (line 82) matches §9.
- The `00_index.md` row (line 05) is accurate and additive.
- ✅ The guide states plainly that this is a **regression lock, not a RED**, and that fail-first was shown by mutation in a discarded export — it does not overstate its own evidence.

---

## 9 · The pre-existing Application-layer state

```
vendor/bin/phpunit tests/Unit/Contexts/Election/OperatingCoreApplication
  → Tests: 56, Assertions: 1336, Errors: 15, Failures: 1.
```

✅ **Exactly the reported figure.** Neither `4da89888` nor `3da3053d` touches any file under `tests/Unit/Contexts/Election/OperatingCoreApplication/` or under `app/`, and the lock lives in a different suite directory, so it cannot influence that result. `AbsentAggregateReferenceRedTest.php:84` is RED by design (GREEN-3…7 pending). **Pre-existing and unrelated.** The `F-3` label defect at `StructuralApplicationGuardsRedTest.php:228` was left alone, as instructed, and is not part of this verification.

---

## Unsubstantiated / defective

⛔ **No defect found.** Nothing in the lock imposes a requirement the frozen domain does not already meet; the fail-first evidence is genuine and was reproduced independently; the ordering, the core integrity and the deliberate omissions all hold.

**Nothing is left unsubstantiated.** Every claim above rests on a command this session ran or a `file:line` it read. Three items are recorded as **boundary facts rather than defects**, and are the only things a later lane should carry forward:

| ID | Recorded fact | Why it is not a defect |
|---|---|---|
| **O-1** | `…LockTest.php:150` pins P-7's **arity** at one, which `D2`'s text does not require | it asserts a true property of committed code; without it, *"no resumption target"* is not executable |
| **O-2** | `…LockTest.php:175-179` and `205-209` are tautological given the assertions preceding them | expressions of intent; they lock nothing extra and cost nothing |
| **O-3** | the locked type has **no production caller** (only the uncalled Act-B port), so the lock guards `D2`'s **domain semantics**, not the executed `w8` Application path | this is exactly the scope the lock claims (`…LockTest.php:44-45`); acts C and D are outside the `H-2` authorization |

> ## ✅ **VERDICT: H-2 LOCK — VERIFIED.** **The most likely failure mode this authorization named — *"a lane under pressure to produce a RED will INVENT a semantic rule"* (`…authorization.md:45`) — did not occur.** **After verification, the lane must stop** (`…authorization.md:25`); this verification creates **no** implementation authority, resolves **no** open boundary, and reopens **nothing**.

**Traceability.** `H-2` authorization *(`docs/publicdigit/reviews/2026-08-19-EM-DOM-H2-w8-regression-lock-authorization.md`)* · `D2` verbatim *(`docs/publicdigit/reviews/2026-08-18-EM-DOM-001-decision-recording-surface.md:96-120`)* · `ADR_20260817_2300` §5e, §5f, §6(a)/(b)/(h) · `EM-GOV-059(a)/(b)/(c)` · `EM-GOV-062` · P-7 `ResumptionTarget.php:105` · Act-B baseline `1f4b4c5f` · commits `4da89888` (RED) · `3da3053d` (GREEN) · Act-B export technique `67a09e11` · `EP-02` / `R-34` · `PBDIGIT-68`.
