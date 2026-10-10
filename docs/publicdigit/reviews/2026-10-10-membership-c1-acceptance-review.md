# Membership C-1 (PBDIGIT-268): acceptance review and recommendation

**Commission:** `docs/publicdigit/membership/architecture/brainstorming/20261010_1101_implementation.md` ("Independent C-1 Acceptance Review").
**Mode:** review and evidence gathering only. **No production, persistence, schema, DB, story or index change.** The F-1 options were tested on scratch copies outside the repository.
**Date:** 2026-10-10 · **Reviewed:** `099587851^..5765b9fa3` (20 commits) on `knowelegeos-modelling`, HEAD `5765b9fa3`.

> **Independence (stated first).** The author of C-1 compiled this review, so it is **not independent**.
> - To reduce author bias, the semantic and TDD questions went to a **fresh-context, read-only reviewer** that did not see the author's reasoning. Its findings are reproduced unfiltered (marked **[IR]**).
> - Checks re-run by the author are marked **[RERUN]**.
> - **Acceptance belongs to the Principal Reviewer and the PO (EP-02, R-34).** Nothing here accepts C-1.

## 1. Executive recommendation

**RETURN FOR TARGETED CORRECTION (test-only, small), then ACCEPT WITH CONDITIONS.**

**The semantics are correct.**
- The contract's rules are implemented: liveness, guards, refusal order, restoration and G\*.
- No scope exclusion was breached: no Correct, consequences, persistence, application, cutover or live-write change.

**One approved C-1 criterion has no evidence.** Contract §14.3 requires a *unit* test for K-E1. That is a test-only correction inside the approved PBDIGIT-268 scope, not a redesign.

**Conditions for acceptance:**
- the TDD-deviation disposition (§4);
- tracking F-1 into C-4 (§5);
- confirming `VoidFact` (§6);
- the documentation corrections (§8).

## 2. Repository state [RERUN]

- **Branch and HEAD:** `knowelegeos-modelling`, `5765b9fa3`.
- **Working tree:** clean for `app/Contexts/Membership` and `tests/Unit/Contexts/Membership`.
- **Others' work:** 3 staged doc renames belong to another session and were not touched.
- **Production scope across the range:** `git diff --stat 099587851^ HEAD -- app/` touches **only** `Domain/Organisational/` (10 files, +221 / −9), matching the reported scope.
- **PHP:** 8.5.11.

## 3. Contract-to-code traceability (§14.3)

| Criterion | Code | Test evidence | Status |
|---|---|---|---|
| K-A1 Annul(x) accepted, x not live afterwards | `Member::decideAnnul`, `Resolution::live` | `test_annulling_an_admission_removes_its_period`, T9, restoration, differential | ✅ |
| K-A2 non-decision provenance refused | `Member.php` guard 5 | `test_annulling_a_fact_without_decision_provenance_is_refused` | ✅ |
| K-A3 Annul(Void) refused | guard 4 | `test_annulling_a_void_is_refused` | ✅ |
| K-A4 `DecidedAtRequired` | guard 3 | `test_annulling_without_decided_at_is_refused_and_changes_nothing` | ✅ (the meaningful assertion is that `fact()` throws; see N-1) |
| K-A5 / K-V2 `TargetNotLive` | guard 6 / Void guard 3, `Resolution::isLive` | `test_reversing_a_fact_that_is_not_live_is_refused` | ✅ |
| K-V1 Void(x) accepted | `decideVoid` | `test_voiding_an_admission_removes_its_period`, differential | ✅ |
| K-R1 / K-R2 restoration | `Resolution::live` | `test_reversing_a_reversal_restores_the_period`; P-REV / P-APPEAL property | ✅ (no RED; §4) |
| K-R3 / K-G1 G\* refusal | `Member::accept` → `InvariantSet` | T8, T10 tests; deletion-counterfactual property | ✅ (no RED; §4) |
| K-H1 P-KNOWN | `Member::knownAt` (Slice 0) | `test_a_reversal_leaves_earlier_knowledge_unchanged` | ⚠️ **example only**; the contract states a property over any accepted F·f [IR MINOR-2] |
| **K-E1** decision carries `FactAnnulled` / `FactVoided` data | the data **is** carried: `Decision::accepted(new Annul($id, $target, $provenance, $decidedAt), version)` | **none** | ❌ **MISSING** [IR MAJOR-1]. A mutant passing `null` for `decidedAt` into `new Annul` would survive, because Resolution never reads it [IR, not run] |
| K-X1 Admit / End unchanged | — | 117 unit + 12 architecture tests [RERUN] | ✅ |
| K-X2 exhaustive differential | — | `test_the_kernel_equals_the_g6_port_within_bounds` | ✅ |

**Refusal order** follows the plan exactly [IR].
- **Annul:** DuplicateFactId → TargetNotFound → DecidedAtRequired → AnnulOfVoidRefused → TargetHasNoDecisionProvenance → TargetNotLive → G\*.
- **Void:** DuplicateFactId → TargetNotFound → TargetNotLive → G\*.

**Gap [IR MINOR-1]:** three adjacent pairs among Annul guards 3–6 are untested, so swapping them would go undetected:
- DecidedAtRequired before the target-type checks;
- AnnulOfVoidRefused before TargetHasNoDecisionProvenance;
- TargetHasNoDecisionProvenance before TargetNotLive.

The differential always uses decision provenance and `decidedAt`. **No case yields an unintended refusal.**

**Semantics [IR NOTE-3]:**
- `live` is a correct backward pass, in which only live reversals mark their targets.
- Through `decide*`, a reversal can target only an existing earlier fact.
- Fact liveness stays separate from period activity.
- `liveCuts` stays empty, so there are no implicit consequences (D-COR-5 (A)).
- Restoration chains cannot route around N-3 or R-2.
- CQ-9 stays unresolved and out of scope.

## 4. TDD deviation analysis (plan steps 7–9, 11, 12)

| Step | Criterion | Behaviour introduced by | Test commit | Existed before the test? |
|---|---|---|---|---|
| 7 | K-R1 / K-R2 | `b206cc5ad` (step 2 GREEN: the full backward liveness pass) | `0cf2d5f11` | **yes** |
| 8 | K-G1 / K-R3 | INV-9 from Slice 0 (PBDIGIT-258); reversals routed through `accept()` in `b206cc5ad` | `f3292cb8e` | **yes** |
| 9 | K-H1 | `knownAt` from Slice 0 | `4997cdb32` | **yes** (before C-1) |
| 11 | K-X2 | complete kernel at `f1bcb55b3` | `5f9c40864` | yes; by nature a verification test |
| 12 | §14.4 properties | complete kernel at `f1bcb55b3` | `1e85fbfc0` | yes; verification |

**Root cause [IR MAJOR-2]: the step 2 GREEN was not minimal.** RED `4da3cc3d8` tested only Annul(Admit) and Void(Admit), but GREEN `b206cc5ad` implemented the general liveness rule, which already covers restoration chains. That deviates from §15.7 (1) "one behaviour per cycle".

The plan text labels steps 7–9 "RED → GREEN". Steps 11 and 12 are verification steps in the plan, but §15.7 (1) still says "RED first for each of K-A1 … K-X2".

**What mutant sensitivity establishes:**
- each test *can* fail for at least one realistic violation (a reversed reversal still counts; Annul bypasses G\*; `knownAt` ignores τ; Void without `TargetNotLive`; the reversed-reversal mutant against the properties);
- Infection's independently generated mutants corroborate this [RERUN]: 119 / 129 killed.

**What it does not establish:**
- **Test-first design pressure:** the tests did not drive the code.
- **Completeness:** one chosen mutant does not show that the test specifies the whole criterion.
- **Author-independent mutant choice:** the hand-picked mutants left no artefact, so they are unverifiable by others [IR]. Infection is reproducible; the hand mutants are not.

**Effect on acceptance confidence: low.** The behaviours are cross-checked by means that do not share production code:
- the recursive §14.2 `live` in `C1PropertyTest`;
- the deletion counterfactual;
- the Python oracle stream, which predates C-1 (commit `6a129bfa9`) and is reproduced by the PHP port and the kernel.

**Recommendation:** **accept the deviation as documented.** Do **not** manufacture RED phases by reverting code (needless churn, and a history rewrite is excluded). Record that the process claim "strict TDD" does not hold for K-R1, K-R2, K-R3, K-G1 and K-H1. Adopting the PROPOSED "verification-only tests" rule (strategy §4) would prevent a recurrence; that is a PO decision.

## 5. F-1: `Fact` / `EffectiveDatedFact` and the adapter

**Facts [RERUN]:**
1. **Reproducible.** An ad-hoc PHPStan run (scratch config, no project gate covers Membership) reports `PostgresMemberRepository.php:218 Call to an undefined method Fact::effectiveAt()` at level 2 and at max level. **At max level** it also reports a knock-on: `DateOrdinal::toDate() expects TimePoint, mixed given`.
2. **One call site:** `appendInTransaction` passes `$accepted->fact()`, which is typed `Fact` (`Decision::fact(): Fact`), to `encode()` (line 186).
3. **The runtime cannot reach it today.** `decideAnnul` / `decideVoid` have no caller outside the kernel. `DecideAndAppend` is generic, but only `AdmitMemberHandler` and `EndMembershipHandler` use it.
4. **A latent misbehaviour exists:**
   - `encode()` already ends with a deliberate fail-closed `throw new \LogicException('Unsupported fact kind for Slice 1b')`;
   - but `$fact->effectiveAt()` in `$common` is evaluated *before* it;
   - so an Annul would die with an `Error` (undefined method) instead of the intended `LogicException`;
   - both abort inside `db->transaction`.
5. **C-4 needs a schema change anyway:** `org_membership_facts.effective_at` is `date NOT NULL` (migration `2026_10_09_100000`), and Annul / Void have no valid time.

**Options, each PHPStan-checked on a scratch copy of the adapter at max level** (the error set compared with the current code):

| Option | Change | PHPStan result | Consequence |
|---|---|---|---|
| **A. Track to C-4** | none now | the defect stays (2 errors) | no risk now; a known static defect remains open until C-4 |
| **B. Guard** | first statement of `encode()`: `if (!$fact instanceof EffectiveDatedFact) throw new \LogicException('Unsupported fact kind for Slice 1b: ' . $fact::class);` | **removes both errors, adds none** | Admit / End unchanged; an Annul / Void now hits the *intended* fail-closed exception. C-4 replaces the guard with real Annul / Void branches |
| C. Narrow the signature to `EffectiveDatedFact` | `encode(EffectiveDatedFact&Fact $fact)` | removes `:218` but **adds** `:186 Parameter #1 $fact … expects EffectiveDatedFact&Fact, Fact given` | **not type-safe** at the call site; also contradicts C-4, which must encode Annul / Void. **Rejected** |

**Verification for B:**
- PHPStan, done on a scratch copy;
- the PostgreSQL Membership adapter suite (27 tests) on an **isolated** database, which is **not available**: the shared `nrna_test` is unsafe (ARB-SEC-01, PBDIGIT-266).

**Verification for A:** none now; in C-4, the full adapter suite plus round-trip tests for the new kinds.

**Recommendation: A for C-1 acceptance**, with F-1 recorded as a **C-4 entry obligation**: apply B (or the real branches) first, plus the nullable / separate valid-time schema decision (D-COR-4).

**Why not B now:**
- it is an adapter change, which P-1 excludes;
- its only behavioural verification needs an isolated DB, which does not exist yet;
- the defect has no runtime path.

If the PO prefers to close the static defect early, B is the smallest sound fix, under a separate narrow authorisation and once an isolated DB permits running the adapter suite.

## 6. F-2: `VoidFact`

- `php -r 'eval("class Void {}");'` → `Fatal error: Cannot use "Void" as a class name as it is reserved` (PHP 8.5.11) [RERUN].
- The class docblock states that it is the ratified fact kind Void.
- The `RefusalReason` and contract vocabulary say "Void".

**Disposition: confirm `VoidFact`.** It is a language accommodation, not a model change.

## 7. Quantitative evidence and its limits [RERUN unless noted]

| Check | Command | Result |
|---|---|---|
| Domain unit | `vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational/` | OK (83 tests, 39308 assertions); the same result again from [IR] |
| Application unit | the same command on `…/Application/Organisational/` | OK (34 tests, 93 assertions) |
| Architecture | the 3 Membership architecture test files | OK (12 tests, 18 assertions) |
| Fixture integrity | `oracle_sha256` equal to `sha256(g6_correction_oracle.py)`; regeneration with `python3 -I …/g6_c1_golden.py` is `cmp`-identical | ✅ |
| Mutation | Infection 0.29.10, scratch config, `--filter=Member.php,Resolution.php,Annul.php,VoidFact.php` | 129 mutants, 119 killed, 5 escaped, 5 uncovered; **MSI 92 %, covered 95 %**, identical to the report |
| PostgreSQL feature suite | **not run** | **unverified**: no isolated DB (ARB-SEC-01) |

**Strength of the evidence:**
- **Exhaustive bounded enumeration:** H = 5, L ≤ 5, 15530 decisions. This is **verified within bounds, not a proof**.
- **Independence of the oracle:** the Python oracle predates C-1 and shares no code. It is still correlated through the shared spec and author. **For D-COR-2 (`TargetNotLive`) it gives no independence:** that rule is added by the developer-written wrapper `g6_c1_golden.py` and mirrored in the port [IR MINOR-3]. D-COR-2 rests on the unit tests and the properties.
- **"Nothing appended" assertions [IR NOTE-1]:** at `AnnulVoidTest` :61, :84, :99, :124, :194 and :217 they are **always true**, because `Member` is immutable and `decide*` never commits. They are harmless but not evidence. The real no-state-change evidence is the refused `Decision` (`fact()` throws) plus the unchanged resolution.

## 8. Backlog and documentation integrity

| Artefact | Finding | Smallest correction |
|---|---|---|
| Implementation review (`2026-10-10-membership-c1-implementation-review.md`) | omits K-E1; "no deviation from the approved scope" needs qualifying (the step 2 GREEN was not minimal); §6 omits P-KNOWN's example-only status | addendum noting this acceptance review (append, do not rewrite) |
| Guide `04_…` line 13 | "no events" is ambiguous. Correct: no event *emission* (C-3); the decision carries the event data (K-E1) | one sentence |
| Story PBDIGIT-268 | "done TDD-first" overclaims (§4) | "TDD-first except K-R1 / R2 / R3 / G1 / H1 (verification, see acceptance review §4)" |
| Backlog `README.md` | **no uncommitted edits now** (`git diff` empty); the PBDIGIT-268 row is missing | insert one row after the `PBDIGIT-267` row (line 146), committed through a temporary index with that path only. Prerequisite: PO authorisation (audit D-1) and a re-check that the README is clean at commit time |
| Plan, CONTEXT, guide index | statuses agree ("awaiting review") | none |

**Follow-up obligations (existing owners checked, no new IDs):**
- F-1 → the C-4 story when it is authorised (none exists; contract §10).
- TG-4 in `PBDIGIT-267` is partly exercised now (audit).

## 9. Risks, blockers, human decisions

| # | Decision | Who |
|---|---|---|
| H-1 | Authorise the **targeted test-only correction** within PBDIGIT-268: (a) a K-E1 unit test (an accepted Annul / Void decision exposes id, target, provenance, decidedAt, basedOnVersion; a mutant on `decidedAt` is killed); (b) the three refusal-order pair tests (MINOR-1); (c) optionally, P-KNOWN as a property over the bounded histories (MINOR-2) | PO |
| H-2 | Accept the TDD deviation as documented (§4) | PR, PO |
| H-3 | F-1: option A (track to C-4) or B (narrow guard now, needs an isolated DB to verify) | PO |
| H-4 | Confirm `VoidFact` | PR / PO |
| H-5 | Authorise the documentation corrections and the README row (§8) | PO |
| H-6 | After H-1 is done: formal C-1 acceptance | PR, then PO |

**Blockers:** none for the correction. The isolated test DB remains a blocker for any adapter verification (F-1 B, C-4, PBDIGIT-264 / 266).

## 10. Permitted next actions

**Without new authorisation:**
- PR / PO review of this document;
- answering review questions (read-only).

**With H-1:** the test-only correction, as RED where possible (the K-E1 decidedAt mutant first) or with sensitivity shown, and a regression re-run.

**Not permitted without separate authorisation:** F-1 adapter edits, C-2 / C-3 / C-4, persistence, migration, cutover, live writes, story or index edits, new IDs.

**Stop.**

## 11. Correction evidence (2026-10-10, after the PO's approval of plan 20261010-1144; append-only)

**Authority:**
- PO / Human ARB H-1 (the plan) and CP-1 (sensitivity instead of RED);
- H-2 … H-6 recorded in the plan and confirmed in writing.

**Scope kept:** test-only. `git diff --stat 5765b9fa3 -- app/` is empty. There was no adapter, schema, DB, application, cutover or live-write change. The PostgreSQL suite was **not run**.

| Gap | Closed by | Commit | Sensitivity (mutant killed by the named test, in a scratch copy) |
|---|---|---|---|
| K-E1 | `test_an_accepted_annul_carries_its_fact_data`, `test_an_accepted_void_carries_its_fact_data` | `9ae69b7bc` | M1 drop decidedAt (Annul), M2 drop decidedAt (Void), M3 basedOnVersion + 1, M4 id ↔ target |
| guard order 3 < 4, 5, 6 | `test_decided_at_is_checked_before_the_target_kind_provenance_and_liveness` | `fccf5b778` | M5 |
| guard order 4 < 5 | `test_annul_of_void_is_refused_before_the_provenance_check` | `fccf5b778` | M6 |
| guard order 5 < 6 | `test_provenance_is_checked_before_liveness` | `fccf5b778` | M7 |
| K-H1 property | `test_known_at_equals_the_oracle_and_is_prefix_stable` (g6 port reference + metamorphic; H = 5, length ≤ 5) | `e96d12a5d` | M8 ignore τ, M9 boundary ≤ → < |
| runner validity | canary M0 (`Annul::target()` returns its own id) | `9ae69b7bc` | killed, so the scratch copy is the code under test |

**Commands and results (actual):**

```text
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational/   OK (89 tests, 112487 assertions)
vendor/bin/phpunit … tests/Unit/Contexts/Membership/Application/Organisational/                                            OK (34 tests, 93 assertions)
vendor/bin/phpunit … the 3 Membership architecture files                                                                    OK (12 tests, 18 assertions)
php docs/publicdigit/membership/architecture/models/c1_sensitivity/run.php                                                  M0 … M9: KILLED (control OK each); working tree unchanged; exit 0
php vendor/bin/infection … (scope as §5)                                                                                    129 mutants, 119 killed; MSI 92 %, covered 95 % (unchanged)
```

**No RED phase for any new test.** The behaviour existed, so these tests verify existing behaviour, as H-2 and CP-1 allow.

**Process deviations within the correction (disclosed):**
- M3 first killed by a setup crash, so the test was rebuilt on `Member::fromLog`;
- the M9 draft called a non-existent `Instant::isBefore`, so the patch was regenerated.

**Remaining:**
- **independent review** of this correction, then formal C-1 acceptance (H-6, PR then PO);
- F-1 as a C-4 entry obligation;
- persistence unverified (no isolated DB);
- D-COR-2 has no independent-oracle coverage;
- recordedAt ties are not enumerated by T-H1.

## 12. Independent-review conditions: disposition (2026-10-10; append-only)

**Source:** `2026-10-10-membership-c1-correction-independent-review.md` (fresh-context reviewer; report verbatim in its §1).
**Recommendation (quoted):** "ACCEPT WITH EXPLICIT CONDITIONS". No BLOCKING findings.
**Authority for this section:** Human PO / ARB decisions G1, G2, G2-b (gate decision record row 5, C-AMEND-1). **G3 (formal acceptance) is PENDING.**

| Condition | Disposition | Recorded in |
|---|---|---|
| G-1: sensitivity-kit deviations | **acknowledged**; runner hardening is separate work (D-A1), proposed as `PBDIGIT-269` (OPEN, not authorised; D-A2) | this section; plan 20261010-1144 Progress; guide 05 Pitfalls |
| G-2: authority evidence and "reason" in provenance → C-3 | **adopted** as C-3 entry criteria; "reason" stays OPEN under CQ-3 (D-A4) | contract §14.5 annotation C-AMEND-1; register row 5 |
| G-3: F-1 and isolated-DB persistence → C-4 | **adopted** as C-4 entry criteria | contract §14.5 annotation C-AMEND-1; register row 5 |

**Sensitivity-kit facts:**
- The runner copies the **working tree**, not `git archive HEAD` as plan CP-2 specified.
- It does **not abort** when the canary M0 survives; it marks the run failed and continues.
- It counts **any non-zero exit** as KILLED.

**It therefore does not by itself guarantee that a kill is an assertion failure.** The ten current kills (M0 – M9) were each confirmed as a genuine assertion failure at the intended line by the independent reviewer (review §1 E), with the controls passing and `app/` / `tests/` unchanged. That manual confirmation covers these ten kills only; it is not evidence that the runner classifies every future mutant correctly.

**Evidence:**
- commits `9ae69b7bc`, `fccf5b778`, `e96d12a5d`, `d90bf8af9`; `git diff 5765b9fa3 -- app/` is empty;
- targeted results (independent review, re-run): Organisational domain 89 tests, application 34, architecture 12.

**The wider Membership unit directories:** running them gives **27 errors** (Domain, 265 tests) and **29 errors** (Application, 81 tests), in files C-1 did not touch. They were **not re-investigated**. `PBDIGIT-167` (OPEN, "re-measure first") may be the relevant owner; that has not been established. **The full Membership suite does not pass.**

**Limitations (unchanged):**
- D-COR-2 is not in the independent Python oracle (the developer-written wrapper adds it);
- equal `recordedAt` values are not enumerated by the K-H1 property;
- the evidence is bounded (H = 5, length ≤ 5), not a proof;
- Infection was re-run only by the developer (92 % / 95 %); the independent reviewer did not re-run it;
- the PostgreSQL feature suite was not run, so persistence is **unverified**.

**Formal C-1 acceptance: PENDING (G3: PR, then Human PO / ARB).**
