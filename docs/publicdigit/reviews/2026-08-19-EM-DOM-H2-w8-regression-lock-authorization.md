# `H-2` — `w8` regression lock: PO/ARB authorization, recorded verbatim

**Recorded by:** the governance recording session · 2026-08-19 · **Nothing implemented at recording time.**

## The act, verbatim

> **PO/ARB AUTHORIZATION — H-2**
>
> I authorize one bounded slice to create the regression lock for the D2-approved w8 semantics.
>
> The slice shall encode the already-decided meaning that Restoration and Resumption are distinct: the w8 path represents restoration without a prior halt/resumption target, while retaining its known causal origin.
>
> This authorization is limited to the RED test, the minimum implementation/test change required to establish the regression lock, and independent verification of that lock.
>
> This authorization does not authorize: Act C; Act D; BND-1; BND-3; GREEN-5; changes to RecordedOperationalStatus; persistence or adapters; Application-layer changes; HaltedAtGate redesign; ResumptionTarget redesign; a sentinel, UnknownGate, nullable replacement, new enum, new aggregate, or new event; reopening ADR-2; changing the meaning already decided by D2.
>
> The lane must derive the RED test from the existing D2 decision and frozen domain behaviour. It must not invent a new semantic rule.
>
> RED must be committed separately and demonstrated failing before GREEN.
>
> The implementation/test change must then make only that regression lock GREEN.
>
> An independent verifier must verify the resulting lock.
>
> After verification, the lane must stop.
>
> **PO/ARB: AUTHORIZED.**

✅ **`A-3` satisfied:** performative, own voice, unconditioned, scope-exact, explicit negative list.

---

# ✅✅ **`H-2` COMPLETE AND VERIFIED — 2026-08-19**

| Step | Outcome |
|---|---|
| **RED** | **`4da89888`** — `RestorationWithoutPriorHaltRegressionLockTest.php` **alone** (223 lines), fail-first shown by **mutation in a throwaway export** |
| **GREEN** | **`3da3053d`** — **developer guide only.** ⭐ **No production change and no test change was required** — the `D2` behaviour was already correct, exactly as this record predicted |
| **Independent verification** | ✅ **VERIFIED — `33f7316e`. No defects.** |
| **STOP** | observed — the lane did not verify itself |

**Repo state:** lock `OK (3 tests, 21 assertions)` · domain suite **49 / 2478** *(46 → 49)* · core still the Act-B baseline: **one `A`, no `M`/`D`** vs `1f4b4c5f` · `app/` clean.

## The fail-first route worked, and the verifier reproduced it independently

**The sanctioned mutation route was followed and strengthened:** the lane exported the tree with its own `vendor/`, **ran the lock GREEN inside the export first** to prove self-containment, then mutated **only the copy** — `restored()` fabricating a halt via `$this->haltedAtGate ?? new HaltedAtGate(...)`, **the exact fabrication ADR-2 §6(b) forbids** — and captured 2 of 3 tests failing.

⭐ **The verifier did NOT accept that on trust.** It built its **own** export, proved self-containment via `autoload_psr4.php`'s `__DIR__`-derived `$baseDir`, measured its own pre-slice baseline (`46 tests / 2457 assertions`, confirming *"46 before, +3"* and `2457 + 21 = 2478`), reproduced the mutation, and obtained output **identical down to the object identifiers `#543`/`#424`/`#542`**. **Then it isolated causation** — reverting the mutation in the same export returned `OK (3 tests, 21 assertions)`, so the failures come from the mutation and **not** environment drift.

> ## ⭐ **The named failure mode did NOT occur.** No semantic rule was invented. **All twenty assertions plus the `expectException` read behaviour the frozen core already exhibits** — `ElectionOperationalStatus.php:22-25,33-36,39-42,49-57` · `ResumptionTarget.php:105,107` · `RecoveryProcess.php:50-57,59-67` · `PeriodKind.php:17-18` · `ExpiryConsequence.php:46-48`. **The lock passing with ZERO production change is itself the proof.**

**Also independently established:** the *"refuses on kind alone"* claim — `onHaltedRecoveryExpiry`'s **other three guards each PASS** under the lock's arguments, leaving the kind guard as the **sole** cause. And the 3 `SplObjectStorage` deprecations trace to `vendor/sebastian/recursion-context/src/Context.php:148`, attributed only to the test whose failure message renders the fabricated object; **unmutated runs report none.**

## Three boundary facts — recorded as explicitly NOT defects

| # | Observation |
|---|---|
| **O-1** | The **arity pin** at `…LockTest.php:150` is **marginally wider than `D2`'s text** — but it asserts a **true property of committed code** and **is what makes *"no resumption target"* executable** rather than conventional. |
| **O-2** | Two assertions (`:175-179`, `:205-209`) are **tautological** given those preceding them. |
| **O-3** ⚠️ | **The locked type has NO production caller** besides the **uncalled** Act-B port *(independently confirmed: `ElectionOperationalStatus` appears in `app/` only in its own file and in `Port/RecordedOperationalStatus.php`)*. ⇒ **The lock guards `D2`'s DOMAIN semantics, not the executed `w8` APPLICATION path** — which still runs `$decision->gate()` under quarantined `DEP-7`. **This is exactly the scope the lock claims, and it is worth stating plainly: a regression on the Application path would NOT be caught by this lock.** |

## What `H-2` does and does not change

✅ **`D2`'s semantics now have a regression lock**, and it is executable rather than conventional.
⛔ **Nothing else moved.** Act C · Act D · `BND-1` · `BND-3` · `GREEN-5` · `RecordedOperationalStatus` · persistence · Application layer · `HaltedAtGate` · `ResumptionTarget` · ADR-2 — **all untouched and unauthorized.** ⛔ **`EM-GOV-063` remains unreachable** *(`PBDIGIT-72`, two causes)*. ⛔ **ADR-2 §5f — `ElectionRestored`'s non-nullable `GateDesignation` — remains OPEN**, and the lock deliberately says nothing about it *(`ElectionRestored` appears once in the whole test file: inside the "does not assert" list)*.

⚠️ **Housekeeping, no repo impact:** both throwaway exports remain under the session scratchpad — **outside the repository and outside git** — because the sandbox declined `rm -rf`. **Both had their mutations reverted** *(independently checked: the prior export's `ElectionOperationalStatus.php` is byte-identical to the committed file)*. **Neither is repository content.**

---

## 🔴 A structural issue in the RED requirement — flagged, not silently resolved

**The `w8` behaviour ALREADY PASSES on the frozen domain.** The Act-B implementing lane reported exactly this: *"`H-2` … passes on arrival, is explicitly not the RED."* Independently confirmed: `ElectionOperationalStatus.php:22-24,39-42,49-52` already yields `haltedAtGate() === null` and `isHalted() === false` for the halt-absent path.

**So a conventional fail-by-absence RED is impossible here**, and the three obvious ways to manufacture one are each **forbidden by this authorization**:

| Route to a failing test | Why it is forbidden |
|---|---|
| Assert something the domain does **not** currently express | ⛔ *"must not invent a new semantic rule"* — this would be a **new requirement**, not a regression lock |
| **Mutate the frozen core** to break the behaviour, commit that | ⛔ Act-B baseline integrity; the core carries exactly one `A` line vs `1f4b4c5f` and no `M` |
| Skip fail-first and commit a passing test | ⛔ *"RED must be committed separately and demonstrated failing before GREEN"* |

> ## ⚠️ **The danger this creates is specific and must be named: a lane under pressure to produce a RED will INVENT a semantic rule to make something fail.** **That is the one outcome this authorization explicitly forbids, and it is the most likely failure mode of this slice.**

### The sanctioned route — mutation-demonstration in a throwaway export

**Precedent, in this repository, from the Act-B step-④ verifier:** it exported the tree at `e7317077` **with that tree's own `vendor/`** and ran the suite inside the export, proving a failure state **without touching the working tree.**

⇒ **`H-2`'s fail-first is demonstrated the same way:** export the current tree to a scratch location · **mutate the copy** so the `w8` discrimination is broken · run the new lock **inside the export** and capture the failure · **discard the export.** **The committed lock then passes on the unmutated tree** — which is what a regression lock is *for*.

**This satisfies the authorization on its own terms:** the RED is **committed separately** *(test alone, before any other change)* and **demonstrated failing** *(against the mutation, with captured output)*, and **no new semantic rule is invented** — the lock asserts only what `D2` already decided and the frozen domain already does.

⚠️ **Recorded as Governance's reading, offered for correction.** **If the PO/ARB intends a different route — including accepting a lock that passes on arrival with no failure demonstration — that is the PO's call and this note should be overruled.**

### What "minimum implementation/test change" is expected to mean here

**Probably test-only.** The behaviour exists; nothing needs implementing. ⛔ **If the lane concludes that production code must change to establish the lock, that is a FINDING to report and stop on — not a licence to change the core**, because it would mean the behaviour was not in fact already correct.

**Traceability:** `D2` *(decision surface, verbatim)* · ADR-2 §6(a)/(b)/(h) · Act-B step-④ verification `67a09e11` *(the export technique)* · Act-B baseline: core = `1f4b4c5f` + one `A` · `EM-GOV-059(b)/(c)` · P-7 · `EP-02`/`R-34`.
