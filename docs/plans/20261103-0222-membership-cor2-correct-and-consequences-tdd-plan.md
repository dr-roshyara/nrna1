# Plan: COR-2: Correct and its consequences in the Membership kernel (PBDIGIT-327, roadmap W4)

**Status:** **COMPLETED: PBDIGIT-327 ACCEPTED by the PO, 2026-11-03** (kernel only; record `docs/publicdigit/reviews/2026-11-03-pbdigit327-cor2-independent-review-and-acceptance.md` §4.1). *(Earlier:)* **APPROVED by the PO, 2026-11-03 (G-PLAN: "approve the COR-2 plan with P-1…P-6 as recommended").** Steps 1–7 are authorised (kernel only, database-free). Nothing is implemented yet. *(Was: PROPOSED.)* Approval authorises steps 1–7, which are kernel-only and database-free. It does **not** authorise persistence (COR-4), application commands (COR-3), or any database run.
**Story:** `docs/publicdigit/backlog/PBDIGIT-327-membership-correction-c2-correct-and-consequences.md`
**Template:** the C-1 plan `docs/plans/20261010-1009-membership-c1-annul-void-tdd-plan.md` (same oracle-port, golden and differential method).
**Baseline:** HEAD `8e180bd96`.

## 1. Objective

Add **Correct** to the Membership kernel exactly as ratified (canonical §2, §3, §9; FP §14.2–§14.4, §16; act N-3), with:
- the trim consequences (g2 + CFC+);
- the BD-REV-ER = refuse guard;
- correction of the **two dates only** (D-COR-3).

The kernel must then equal an independent PHP port of the g6 oracle on every bounded history.

## 2. Inputs (all decided or ratified)

| Item | Status | Source |
|---|---|---|
| Correct: targets Admit / End / Correct of the same Member; never Annul / Void (N3); a voided target refused; an annulled target accepted **inert** until revived | RATIFIED (act N-3, canonical §9) | contract §3.3, §10a (3)–(5) |
| Value: the latest live Correct in the chain; `active(f)` needs the whole chain live | RATIFIED | canonical §3, §9 (FP §15) |
| Consequences: g2 over Δ = NewBase − PrevBase − own live cuts; a Correct inherits the superseded fact's consequences, clipped; `LiveCut = Cut ∩ BaseClaim(target)` if the cause is active and not superseded | RATIFIED (CFC+, FP §14.2) | canonical §2, §3, §6 |
| Annul / Void create no consequences; they only revive (D-COR-5 (A)) | DECIDED (Human ARB 2026-10-10) | contract §14.6 |
| **BD-REV-ER = refuse**: no correction may leave a live Admit with an empty lifetime | DECIDED (PO 2026-11-02) | decision record |
| **D-COR-3 = dates only** (Admit and End `effectiveAt`) in this slice | DECIDED (PO 2026-11-02) | decision record |
| Trim identity = option C (recorded on its cause); representation relative or absolute is an implementation choice; **relative recommended** | RATIFIED (C) / open (representation) | FP §12.4 |
| Oracle | `docs/publicdigit/membership/architecture/models/g6_correction_oracle.py`: Correct, g2, LiveCut, `refuse_absorption` already present | contract §7 |

## 3. Design points for the PO (decide with the approval)

| # | Question | Options | Recommendation |
|---|---|---|---|
| **P-1** (m5) | A Correct whose chain **root** is voided (e.g. Correct(Correct) after the root Admit was voided): refuse, or accept inert? | (a) refuse with `CorrectOfVoidedFact`; (b) accept inert (the oracle's current behaviour) | **(a) refuse.** A voided root "never happened", so correcting it is meaningless, and this matches R-5 for a directly voided target. The test oracle gets the same restriction (as C-1 did for D-COR-2) |
| **P-2** | Trim representation inside the kernel | (a) **relative** (the removed interval as stated by the decision); (b) absolute (as the oracle) | **(a) relative**, per FP §12.4: it states the legal meaning. The differential test compares resolved lifetimes, so it is representation-neutral |
| **P-3** | A no-op correction (same value) | (a) refuse `ValueUnchanged`; (b) accept | **(a) refuse:** a no-op creates audit noise and no truth |
| **P-4** | Corrected End date at or before the period start | (a) refuse (carry C-S0-1 over); (b) allow | **(a) refuse** with the existing `EndNotAfterPeriodStart`. Symmetric: a corrected Admit date at or after its live End is refused the same way |
| **P-5** | Dating rules for the corrected value (D-5(b): no future-dated admissions or endings; finite dates; R-1a-6 range) | (a) apply the same rules to the corrected value; (b) none | **(a) apply:** a correction must not smuggle in a future date. Infinite dates are an `InvalidArgumentException` (H1 pattern) |
| **P-6** (D-COR-1) | The register says "Correct requires a live / active target"; act §4 and N-3 say an annulled target is accepted inert | the act governs; annotate the register | **confirm "the act governs"** and annotate the register (no behaviour change) |

## 4. Design (kernel)

- **New fact** `Correct` (`app/Contexts/Membership/Domain/Organisational/Correct.php`): `FactId`, target `FactId`, corrected `TimePoint` (valid-time date), `Provenance`, `?Instant decidedAt`, frozen `consequences` (P-2 representation).
- **`Member::decideCorrect(FactId $id, FactId $target, TimePoint $value, TimePoint $decisionDate, Provenance $p, ?Instant $decidedAt): Decision`**, following the guard order of the C-1 / 2a pattern:
  1. finite dates (`InvalidArgumentException`);
  2. `DuplicateFactId`;
  3. `TargetNotFound`;
  4. `CorrectionOfAnnulOrVoidRefused`;
  5. `CorrectOfVoidedFact` (direct, and via the root: P-1);
  6. `TargetNotLive` per D-COR-2 where applicable (an annulled target → accepted inert, *not* refused);
  7. `ValueUnchanged` (P-3);
  8. `EndNotAfterPeriodStart` / the Admit-after-End symmetric case (P-4);
  9. `EffectiveDateAfterDecisionDate` (P-5);
  10. g2 consequences;
  11. G\* (INV-EP, INV-9, INV-COV, INV-1);
  12. **`PeriodAbsorbed`** (BD-REV-ER).
  
  The exact order is fixed by RED tests (a pairwise guard-order test, as in C-1).
- **`Resolution`:** `value(f)`, `active(f)`, `LiveCut`, `lifetime = BaseClaim − ∪ LiveCut`, with the dependency order of canonical §3.
- **Annul / Void of a Correct:** allowed by the existing rules. Annul needs the Correct's decision provenance. Reverting a Correct revives the superseded value and its consequences (CFC+).
- **Decision event data:** an accepted Correct carries the data for `FactCorrected` plus one `PeriodTrimmed` per consequence created. An accepted Annul / Void that revives consequences carries `TrimRevived`. **Emission stays COR-3.**
- **`RefusalReason`:** add `CorrectionOfAnnulOrVoidRefused`, `CorrectOfVoidedFact`, `ValueUnchanged`, `PeriodAbsorbed`, as named in contract §4. `TargetNotCorrectable` and `HolderChangeNotAllowed` are **not needed**: under D-COR-3 the command can only carry a date, so other attributes cannot be expressed.

## 5. Steps (strict TDD: every behaviour RED → GREEN; the failing run is recorded before production code)

| # | Step | RED evidence | Notes |
|---|---|---|---|
| 1 | **Oracle port:** extend `tests/…/Support/G6CorrectionOracle.php` with Correct, g2, LiveCut and `refuse_absorption = true`, plus the P-1 / P-3 / P-4 / P-5 restrictions | the golden test fails (no Correct yet) | independent of production `Resolution` |
| 2 | **Golden fixture:** generate the C-2 golden stream from the Python oracle (scratch run of `g6_correction_oracle.py`, with the same restrictions), store it as a fixture with its sha256; the PHP port must reproduce it | the golden test RED → GREEN | transcription fidelity only (audit m6) |
| 3 | **Correct decisions:** the refusals and the guard order (contract §10a (3)–(7), P-1, P-3…P-5) | unit tests RED | `decideCorrect` |
| 4 | **Value precedence and chains** (§10a (1), (2)); an annulled target is inert, then revived (§10a (5)) | RED | `Resolution` |
| 5 | **Consequences:** g2 Δ + inherited clipping; LiveCut; CFC+; Annul(Correct) revives (§10a (8)); `PeriodTrimmed` / `TrimRevived` data (§10a (10)) | RED | the representation per P-2 |
| 6 | **BD-REV-ER guard:** the absorption case → `PeriodAbsorbed` (§10a (9)); the minimal case `Admit(p0@0), End(p0@1), Admit(p1@1), Correct(Admit p1 → 0)` | RED | — |
| 7 | **Exhaustive differential + properties:** `decide*` / `resolve` equal the oracle port on all histories within the bounds (**≥ 5 facts**, so INV-EP / INV-COV become reachable); P-CFC, P-KNOWN (prefix-stable), recorded-counterfactual Res(F · Annul(c)) = Res(F ∖ D(c)); metamorphic §5; mutation measurement (Infection, scoped to the new code); developer guide `13_cor2_correct_and_consequences.md` | RED where new | the bounds are fixed in step 1 and recorded (runtime budget) |

**After every step:**
- the full database-free Membership regression (Domain, Application, Infrastructure, Architecture);
- **no regression** in C-1 (K-A1…K-X2, K-A6), Slice 0, 1a or 2a.

## 6. Acceptance criteria

| AC | Criterion |
|---|---|
| AC-1 | the contract §10a Correct (1)–(10) and §14.5 C-2 criteria, with P-1…P-6 as decided |
| AC-2 | the oracle port reproduces the Python golden stream (sha256 of the fixture recorded) |
| AC-3 | exhaustive differential equality within the stated bounds (≥ 5 facts), 0 mismatches; the count is asserted |
| AC-4 | properties P-CFC, P-KNOWN and the recorded-counterfactual theorem hold on the bounded space; the metamorphic properties pass |
| AC-5 | the BD-REV-ER guard refuses absorption; it changes nothing else in the bounded space (contract §9 evidence: 35 / 14,365) |
| AC-6 | mutation measurement reported with its scope; surviving mutants classified |
| AC-7 | the existing Membership suites green (Domain, Application, Infrastructure, Architecture, `tests/Unit/Testing`); the PostgreSQL suite **not** required (kernel-only) |
| AC-8 | changes confined to `app/Contexts/Membership/Domain/Organisational/`, its tests, test support and the fixture; **no** persistence, application or schema change (the architecture purity tests stay green) |
| AC-9 | developer guide 13; contract annotations for P-1…P-6; an independent review, then the PO's verdict |

## 7. Risks

| Risk | Mitigation |
|---|---|
| The exhaustive space at ≥ 5 facts is slow | bound the domain (as C-1, H = 5, L ≤ 5); record the runtime; split the differential into a `membership-slow` group if needed |
| The oracle and the kernel share an author (AI), so they are not independent | the golden cross-check proves transcription only; properties and the counterfactual theorem are specification-derived (audit §13.5); the independent review is part of AC-9 |
| P-2 relative representation vs the absolute oracle | compare resolved lifetimes and consequence *effects*, not stored shapes; one adapter function in the test support |
| COR-4 must later store Correct and its consequences | the representation is fixed here (P-2) and documented for COR-4; D-COR-4 (column name) is untouched |
| C-1 F-1 (`encode()` typing) | COR-4 scope (ACC-260 condition K-2), not COR-2 |

## 8. Gates

| Gate | Owner |
|---|---|
| G-PLAN: approve this plan + P-1…P-6 | PO |
| G-ACC: an independent review, then the PO's verdict | PO |
| COR-4 / COR-3 | separate plans |

## 9. Traceability

Contract §3.3, §4, §7, §9, §10, §10a, §14.5, §14.7; BD-REV-ER and D-COR-3 decision records; canonical §2, §3, §6, §9; FP §12.4, §14.2–§14.4, §16; act N-3; roadmap W4 (`…20261016_2219_…_completion_plan.md:178`).

## 10. Progress

- 2026-11-03: plan prepared; awaiting G-PLAN and P-1…P-6.
- 2026-11-03: **G-PLAN approved; P-1…P-6 decided as recommended.** Recorded as annotations in the correction contract §9 and, for P-6, in the decision register.
- 2026-11-03: **Steps 1–2 done (oracle port + golden fixture, test support only).**
  - **Generator** `docs/publicdigit/membership/architecture/models/g6_c2_golden.py`: it runs the **unchanged** `g6_correction_oracle.py` (sha256 `1e05fb98…`) on the C-2 fragment with D-COR-2, P-1, P-3, P-4 and BD-REV-ER (`refuse_absorption`). P-5 is not modelled (no decision date). The line format adds the consequences of the decided fact, so the port's g2 is checked too, not only the lifetimes.
  - **Bound measured:** L = 4 → 14,230 decisions at depth 4 (35 `period_absorbed`, matching contract §9); **L = 5 → 262,600 decisions at depth 5**, with INV-EP now reachable (57 `period_lifetime_not_contiguous`) and INV-COV never firing. Python 7.8 s. **The fixture uses L = 5** (`Fixtures/g6_c2_golden.json`, sha256 `7f44bd9b…`).
  - **RED:** `G6C2CorrectionOracleGoldenTest`, class absent (1 error).
  - **GREEN:** the new port `Support/G6C2CorrectionOracle.php` (a transcription; no production code shared) reproduces the listing, the per-depth counts and the stream sha256 on the first run, in 2.7 s.
  - **The C-1 port and fixture are untouched.**
  - **Regression:** Domain 118, Application 62, Infrastructure 22, Architecture 97.
- 2026-11-03: **Step 3 done (the Correct decision: refusals and guard order).**
  - **RED:** `CorrectDecisionTest` (18 tests): 18 errors, `Member::decideCorrect()` undefined. The test helper `MemberHistory::correct()` was added.
  - **GREEN:**
    - the new fact `Correct` (target, value, provenance, `decidedAt`);
    - the four `RefusalReason` cases of §4. `PeriodAbsorbed` is declared now, but has no behaviour until step 6;
    - `Resolution::valueOf()` / `rootOf()` / `isVoided()`;
    - `Member::decideCorrect()`.
  - **Guard order, as pinned pairwise by the tests:**
    1. finite dates;
    2. `DuplicateFactId`;
    3. `TargetNotFound`;
    4. `CorrectionOfAnnulOrVoidRefused`;
    5. `CorrectOfVoidedFact` (direct, or via the root: P-1);
    6. an annulled target is not refused;
    7. `ValueUnchanged`;
    8. `EndNotAfterPeriodStart` (P-4, both sides);
    9. `EffectiveDateAfterDecisionDate`;
    10. G\* (unchanged `accept()`).
  - **`TargetNotLive` never applies to Correct:** a target that is not live is either voided (→ 5) or annulled (→ accepted inert). This matches the oracle.
  - **Not yet in effect:** an accepted Correct does not change the resolved state until step 4 (value precedence in `Resolution::of`). It creates no consequences until step 5.
  - **Deviation, recorded:** `Correct` does **not** implement `EffectiveDatedFact`. `MembershipStorableValidTimeCoverageTest` pins the dated facts to Admit and End. The storable-range part of P-5 (R-1a-6) is checked at the persistence port, so it moves to **COR-4**, which must extend `StorableValidTime`. The kernel applies the finite and dating parts of P-5.
  - **Regression:** Domain 136, Application 62, Infrastructure 22, Architecture 97.
  - Committed `58b89ab44`.
- 2026-11-03: **Step 4 done (value precedence, chains, annulled target inert then revived).**
  - **RED:** `CorrectResolutionTest`, 8 tests, 8 failures. Every one failed first on the stale recorded date (e.g. "got [3, ∞)").
  - **Test defect found after GREEN (fixture, not code):** three tests asked `isMember`, which means "in force" (lifetime ∧ term). Their admits had no term. They now admit with an open-ended term, as the existing tests do.
  - **GREEN, in `Resolution`:**
    - the start of the BaseClaim is `value(Admit)` and its end is `value(latest live End)`; stage 3 runs before stage 4;
    - the term starts at the resolved start (its until date is not correctable, D-COR-3);
    - `value` counts only **live** corrections, so a correction of an annulled fact is inert until the fact is live again (via Annul(Annul) or Void(Annul)), with no special case;
    - for a live root, live and active corrections coincide, so `active()` stays the identity.
  - **Interim, until step 5:** a correction that would overlap another period is refused by G\* (`OverlappingMembershipPeriods`). Step 5's trim consequences replace this.
  - **Regression:** Domain 144, Application 62, Infrastructure 22, Architecture 97.
  - Committed `739875e1c`.
- 2026-11-03: **Step 5 done (consequences: g2 Δ + inherited clipping, LiveCut, CFC+, event data).**
  - **Expected values computed first** by the oracle port (`G6C2CorrectionOracle::decide` / `resolve`, H = 10) for all ten histories in the test, including FP §12.1 CX-B (refused `period_lifetime_not_contiguous`).
  - **RED:** `CorrectConsequencesTest`, 10 tests: 3 errors (no `consequences()` / `revivedTrims()`) and 7 failures (the step-4 overlap refusal; no trims).
  - **GREEN:**
    - `TrimConsequence` (trimmed period + removed interval), frozen on `Correct::consequences()`; each one is the data of one `PeriodTrimmed`;
    - `TrimRevival` (cause, period); `Decision::revivedTrims()` carries the `TrimRevived` data, i.e. the trims in force after the fact but not before it, excluding the fact's own;
    - `IntervalSet::intersectedWith()` / `without()`;
    - in `Resolution`:
      - stage 2 is real: a Correct is active iff its target is active;
      - stage 5: LiveCut is the union of the trims in force (on an active, non-superseded Correct), each clipped to the cause's period's current BaseClaim;
      - stage 6: lifetime = BaseClaim − LiveCut;
      - public `baseClaimOf()`, `liveCutOf()`, `trimsInForce()`, `periodOfChain()`;
    - in `Member`: g2 (Δ = BaseClaim after − before − LiveCut before, against every other period's lifetime; plus the target's trims clipped to the new BaseClaim), merged to at most one consequence per trimmed period.
  - **P-2 reading, for the PO to confirm at review:** the plan defines "relative" as "the removed interval as stated by the decision". That is what is stored. Like the oracle, it is clipped to the cause's current BaseClaim at resolution. Any difference from the oracle's "absolute" representation is therefore one of naming; the observable lifetimes are identical by construction.
  - **Interim, until step 6:** an absorbing correction (a live period left with an empty lifetime) is still accepted.
  - **Regression:** Domain 154, Application 62, Infrastructure 22, Architecture 97.
  - Committed `e5836a816`.
- 2026-11-03: **Step 6 done (BD-REV-ER guard, `PeriodAbsorbed`).**
  - **Oracle probe first** (port, L = 5):
    - the 616 `period_absorbed` refusals come from **Correct (595) and End (21)**;
    - no reachable state violates both another invariant and ER, so their relative order is only pinned at the unit level.
    - The fixture outcomes were also checked against the port, including the minimal case.
  - **RED:** `CorrectAbsorptionTest`, 6 tests: 4 failures. The other 2 pass already; they pin acceptances (annul-then-correct; an empty claim is not absorbed).
  - **GREEN:** ER is added to `InvariantSet` as the last check: a live period with a non-empty BaseClaim and an empty lifetime → `PeriodAbsorbed`.
  - **Reading for the PO to confirm at review (ER scope):**
    - The decision record §3 places ER in the enforcement set of G\* (canonical §6), so it applies to every decision, as in the oracle.
    - Its visible effect beyond corrections: an **End** dated inside its own period's existing trim is refused, because it would leave that period with a claim but no lifetime (21 cases at L = 5).
    - *Added 2026-11-03 (review F-5, condition C-2):* it also refuses **reversals** that would leave a live period absorbed, e.g. an appeal Annul(Annul A1) refused `PeriodAbsorbed` (review F-1, 10-fact history).
    - The PO's words were "a correction is refused if … another period … would have an empty resolved lifetime". The alternative, ER for Correct only, would accept those Ends, and then diverge from the golden oracle.
  - **Regression:** Domain 160, Application 62, Infrastructure 22, Architecture 97.
  - Committed `01fcbc9e3`.
- 2026-11-03: **Step 7 done (exhaustive differential, properties, mutation, developer guide 13).**
  - **AC-3, `C2ExhaustiveDifferentialTest`:**
    - compares the verdict, the reason, the per-period lifetimes and the consequences of every accepted Correct (removed points per trimmed period) with the port;
    - covers **277,835 decisions, 139,722 accepted (H = 5, L = 5), 0 mismatches**; both counts equal the golden fixture and are asserted;
    - runs in 12 s.
  - **AC-4, `C2PropertyTest`** (H = 5, L = 4, every kernel-accepted history; generator `Support/C2Histories`):
    - §5 (b) P-REV-V / P-REV-A, Correct included;
    - §5 (c) P-APPEAL;
    - §5 (d) a repeated correction → `ValueUnchanged`;
    - §5 (e) P-CFC by fact deletion with the recorded consequences, accepted iff F ∖ D(c) satisfies G\*, else the same reason; exercised on histories with consequences;
    - P-EQ;
    - §5 (a) P-KNOWN against the port;
    - 289,726 assertions, 9 s.
  - **AC-6, mutation** (Infection 0.29.10 + pcov; scratch configuration only, as for C-1; tests = the kernel suite):
    - **Scope:** `--filter=Member.php,Resolution.php,Correct.php,TrimConsequence.php,TrimRevival.php,IntervalSet.php,InvariantSet.php,Decision.php` (the `Member.php` filter also matches `ResolvedMember.php`).
    - **First run:** 408 mutants, 358 killed, 33 escaped, 15 not covered, 2 errors; MSI 88 %, covered-code MSI 91 %.
    - **Real gaps:** 9 mutants. Each got a killing test, confirmed by running the mutant directly (a mutated kernel copy loaded ahead of the real one; the tree was never edited):
      - the `Correct` / `TrimConsequence` constructor guards → `test_a_correct_and_a_trim_reject_invalid_parts_when_constructed`;
      - `revivedTrims` exactness (`Member.php:327`, `:329`; `Resolution.php:82`) → `test_only_the_restored_trim_is_reported_as_revived`;
      - **stage 2 (`Resolution.php:216`, a Correct is active iff its target is active)** → `test_the_trims_of_a_correction_whose_target_is_annulled_stop_applying`. It needs 7 facts, so it survived the L = 5 differential. The oracle port refuses the case identically;
      - `IntervalSet::equals` with a different number of pieces (Slice 0 code) → `IntervalAlgebraTest`.
    - **Final run:** 408 mutants, **368 killed, 2 errored (detected: `Resolution.php:153` negations make `value()` recurse without end), 26 escaped, 12 not covered; MSI 90 %, mutation code coverage 97 %, covered-code MSI 93 %.**
    - **Survivors, classified:**
      - **equivalent:**
        - `isset()` true for `false` (`Resolution.php:197`, `:219`, `:257`);
        - `array_values` on lists that are already lists, or only iterated (`IntervalSet.php:25`, `Resolution.php:261`, `Member.php:39`, `:82`);
        - the defensive / unreachable `match` arms and throws (`Resolution.php:161`, `:163`, `:164`, `:176`, `:323`, `:325`, `:326`; `Member.php:283`, `:284`, `:303`);
        - the revival key's separator and order (`Member.php:326`);
      - ~~**equivalent under the ≤ 1-consequence observation:** `Member.php:269` and `Resolution.php:302` …~~ **WITHDRAWN 2026-11-03 (independent review F-1, condition C-1).** The argument was false:
        - it considered only accepted decisions with a non-degenerate BaseClaim;
        - its evidence was "0 counterexamples at L = 5", but the smallest counterexample needs 6 facts;
        - a 6-fact refused case and a 9-fact accepted case (two live periods trimmed) kill both mutants.

        They were a **test gap**, now closed by two killing tests (see the review-conditions entry below);
      - **coverage artefacts:** `continue` / `match (true)` reported as not covered but killed when run directly (`Member.php:251`, `Resolution.php:161`, `:217` (the full suite), `:323`);
      - **pre-existing, as classified for C-1:**
        - `Decision.php:45` (message text), `:50`;
        - `Member.php:26`, `:28`, `:31` (TG R-1a-3);
        - `Resolution.php:30`, `:193`;
        - `ResolvedMember.php:74`.
  - **AC-7 regression:**
    - main tree: Domain **169**, Application 62, Infrastructure 22, Architecture 97;
    - `tests/Unit/Testing`: **168 OK**, run in a clean scratch worktree with the step 7 files copied in, because the main tree's cached config makes its boot guards refuse, as known. The worktree was removed afterwards;
    - 2 pre-existing deprecations: `config/database.php:66` `PDO::MYSQL_ATTR_SSL_CA` on PHP 8.5, and doc-comment metadata in `TestDatabaseSafetyTest`.
  - **AC-8:** changes confined to the kernel namespace, its tests, the test support and the fixture. No persistence, application or schema change; the architecture tests are green.
  - **AC-9:** developer guide `developer_guide/membership/13_cor2_correct_and_consequences.md` plus the index entry. Contract annotations for P-1…P-6 were done at G-PLAN. **Open:** the independent review, then the PO's verdict.
  - **For the PO at the verdict:**
    1. the P-2 reading (step 5);
    2. ER for every decision, including an End inside its own trim (step 6);
    3. the ≤ 1-consequence argument (above);
    4. **found out of scope:** guide `12_pbdigit264_part2.md`, promised by the PBDIGIT-264 Part 2 plan (`:99`), was never written; Part 2 updated guide 03 instead. Recommend a backlog item rather than extending COR-2.
  - **Status:** steps 1–7 done; **STOP for the independent review.**
  - Committed `763a47cf3`. Review brief `09e89f3df`.
- 2026-11-03: **Independent review returned: ACCEPT WITH CONDITIONS C-1…C-3, no production defect.**
  - Record: `docs/publicdigit/reviews/2026-11-03-pbdigit327-cor2-independent-review-and-acceptance.md`.
  - **PO: option (A), close the conditions first, then accept.**
- 2026-11-03: **Review conditions closed (tests and records only; no `app/` change).**
  - **C-1 (F-1):**
    - `CorrectConsequencesTest::test_one_correction_can_trim_two_live_periods`: the reviewer's 9-fact history, accepted with trims A0 [1, 2) and A2 [2, 6), lifetimes A0 [0, 1), A1 [1, 6), A2 [6, ∞);
    - `CorrectAbsorptionTest::test_a_correction_that_trims_two_periods_and_absorbs_one_is_refused_as_absorption`: the 6-fact history, `PeriodAbsorbed`.
    - Expected values from the oracle port (H = 10 and H = 5).
    - Both tests pass on the kernel, and **both fail against each of the mutants `Member.php:269` and `Resolution.php:302`**, run directly with mutated kernel copies loaded first.
    - The "equivalent" classification is withdrawn above.
  - **C-2 (F-5):** ER is a G\* invariant, so it also refuses **reversals**. Example (review F-1, 10 facts): an appeal Annul(Annul A1) refused `PeriodAbsorbed`. Recorded in the step-6 reading here and in guide 13. **The PO confirms the ER scope at the verdict.**
  - **C-3 (F-6, F-7, F-8):** recorded as **COR-4 entry criteria V-13…V-15** in the roadmap verification table (`…20261016_2219_…_completion_plan.md` §3.2), next to V-1 / V-4. Guide 13 points to them.
  - **Regression:** Domain **171**, Application 62, Infrastructure 22, Architecture 97. No change under `app/`.
  - **Next:** the PO's acceptance verdict on PBDIGIT-327. It decides the P-2 reading, the ER scope, guide 12, and F-3 / F-4 as contract questions.
  - Committed `9173c841f`.
- 2026-11-03: **PBDIGIT-327 ACCEPTED by the PO** ("accept PBDIGIT-327; 1–3 as recommended; F-3/F-4 as contract questions").
  - P-2: absolute representation; the distinction is nominal.
  - ER on every decision: annotated in the BD-REV-ER record.
  - Guide 12 → PBDIGIT-328.
  - OQ-C2-1 / OQ-C2-2: open in contract §9.
  - **This plan is complete.** Next in W4: the COR-4 plan, which needs its own story and approval; entry criteria V-1, V-4, V-13…V-15 and ACC-260 K-2.
