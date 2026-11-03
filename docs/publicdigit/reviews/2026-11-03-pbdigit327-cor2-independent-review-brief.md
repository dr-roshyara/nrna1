# PBDIGIT-327 (COR-2): independent review brief

**Status:** PREPARED 2026-11-03 ("commit step 7 and prepare the review brief"); **COMMISSIONED by the PO 2026-11-03 ("commission the COR-2 review").** The report will be recorded verbatim in `2026-11-03-pbdigit327-cor2-independent-review-and-acceptance.md`, with the PO's verdict.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` convention.

## 1. Reviewer and constraints

- **Reviewer:** a fresh general-purpose Claude sub-agent, as for PBDIGIT-324 and PBDIGIT-264 Part 2. It has **no** context from the implementing session beyond this brief; it reads the code, not the implementer's conclusions.
- **Read-only on the repository.** It works in **its own clean worktree** at `763a47cf3`:
  - `git worktree add --detach <scratch>/rev327 763a47cf3`, then `composer install --no-scripts`;
  - it removes the worktree at the end and reports that it did.
- **Database-free.** No database, no `migrate` or `migrate:*`, no seeders, no artisan.
  - It does not read `.env`.
  - It does not delete or edit `bootstrap/cache/config.php` in the main tree.
  - It does not print credentials.
- **Probes and mutants only in the scratch area.** For example, a mutated copy of the kernel, loaded ahead of the real one by a prepended autoloader. It never edits the worktree's tracked files in a way that is left behind.
- **Evidence labels:**
  - **VERIFIED:** ran it, or read it at a cited `file:line`;
  - **INFERRED:** reasoning only;
  - **NOT CHECKED.**
- **The strongest statement must not exceed the evidence.** The implementer's claims in the plan are claims to test, not facts.

## 2. What is under review

- **Story:** `docs/publicdigit/backlog/PBDIGIT-327-membership-correction-c2-correct-and-consequences.md`.
- **Approved plan, with step-by-step evidence in §10:** `docs/plans/20261103-0222-membership-cor2-correct-and-consequences-tdd-plan.md`.
- **Commits:**

  | Step | Commit | Content |
  |---|---|---|
  | 1–2 | `abb81b1c5` | the C-2 oracle port and golden fixture (test support only) |
  | 3 | `58b89ab44` | the Correct decision, refusals, guard order |
  | 4 | `739875e1c` | corrected dates in Resolution: value precedence, chains, an annulled target inert then revived |
  | 5 | `e5836a816` | g2 trims, LiveCut, CFC+, `PeriodTrimmed` / `TrimRevived` data |
  | 6 | `01fcbc9e3` | BD-REV-ER (ER in G\*, `PeriodAbsorbed`) |
  | 7 | `763a47cf3` | the exhaustive differential, properties, mutation-killing tests, developer guide 13 |

- **Code:** `app/Contexts/Membership/Domain/Organisational/`:
  - `Correct.php`, `TrimConsequence.php`, `TrimRevival.php`;
  - `Member.php`, `Resolution.php`, `InvariantSet.php`, `IntervalSet.php`, `Decision.php`, `RefusalReason.php`.
- **Tests:** `tests/Unit/Contexts/Membership/Domain/Organisational/`:
  - `CorrectDecisionTest`, `CorrectResolutionTest`, `CorrectConsequencesTest`, `CorrectAbsorptionTest`;
  - `G6C2CorrectionOracleGoldenTest`, `C2ExhaustiveDifferentialTest`, `C2PropertyTest`;
  - `Support/G6C2CorrectionOracle.php`, `Support/C2Histories.php`, `Support/MemberHistory.php`;
  - `Fixtures/g6_c2_golden.json`.
- **Generator:** `docs/publicdigit/membership/architecture/models/g6_c2_golden.py`, which runs the unchanged `g6_correction_oracle.py`.
- **Guide:** `developer_guide/membership/13_cor2_correct_and_consequences.md`.

## 3. Authoritative sources (the contract, not the implementer's reading of it)

- **Correction contract** `docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md`:
  - §3.3 (Correct), §4 (refusals), §5 (temporal; metamorphic (a)–(e)), §9 (BD-REV-ER evidence and the P-1…P-6 annotations);
  - §10a Correct (1)–(10), §14.4 (properties with domains), §14.5 (C-2 criteria), §14.7.
- **Decision records:**
  - `2026-11-02-membership-bd-rev-er-decision-record.md` (BD-REV-ER = refuse; §3: ER joins the enforcement set);
  - `2026-11-02-membership-d-cor-3-decision-record.md` (dates only).
- **PO decisions P-1…P-6** (2026-11-03): plan §3, as decided "as recommended".
- **Final proof** `docs/publicdigit/reviews/2026-10-08-membership-final-proof.md`: §12.1 (CX-A/B/C), §12.3 (CFC), §12.4 (trim identity, option C, representation).
- **Ratification act / canonical model**, as cited in the contract, where a question needs them.

## 4. Questions the review must answer

### R-1. Acceptance criteria

For each of plan §6 AC-1…AC-9: met, met in substance, partially met, or not met, with evidence.

### R-2. Semantic correctness against the contract (not against the oracle)

- **Guard order and refusals:** §10a Correct (3)–(7), P-1, P-3, P-4 (both sides), P-5.
  - Is `TargetNotLive` correctly never returned for a Correct?
  - Is an annulled target accepted and inert, and revived by both Annul(Annul) and Void(Annul)?
- **Value precedence:** "latest live Correct by seq, recursively". Does counting **live** (not active) corrections in `valueOf` match the contract and canonical stage order?
- **Stage 2 activeness:** a Correct is active iff its target is active.
- **Stage 5 LiveCut:**
  - clipping to the **cause's period's current BaseClaim**;
  - "in force" = active and not superseded.
- **g2:** Δ = BaseClaim after − before − LiveCut before, against other periods' current lifetimes; inherited trims clipped to the new BaseClaim; merged per period.
- **CFC / CFC+:**
  - Annul / Void of a Correct removes its trims;
  - restoring it, or setting aside its superseder, revives them;
  - `revivedTrims()` is exact.
- **ER:** in `InvariantSet`, checked last.
- **D-COR-5 (A):** Annul / Void still create no consequences.

### R-3. Evidence quality and independence

- **Correlated error:** the kernel, the PHP port and the unit-test expectations come from the same implementing session.
  - Check whether the kernel's structure was copied from the port (e.g. the same helper decomposition or shared mistakes).
  - Check whether the port faithfully transcribes the **Python** oracle. The golden test only shows this on the stream it hashes.
- **Bounds:** the differential is exhaustive at H = 5, L = 5 (277,835 decisions); the properties run at L = 4.
  - The implementer found a stage-2 defect class that needs 7 facts, so the bounds are a live risk.
  - **Run a time-boxed check beyond the bounds:** e.g. an exhaustive L = 6 over a narrowed candidate set, or seeded random histories of length 6–9 at H ≥ 8, kernel against port.
  - Report the bound, the seed and the counts.
- **Mutation** (plan §10 step 7): spot-check the survivor classification. Re-run if feasible (Infection 0.29.10 + pcov, a scratch config, the scope as stated).
  - Are any "equivalent" or "defensive" survivors actually killable?
- **The direct-mutant harness claims:** reproduce at least two kills.

### R-4. The four points the PO must decide at the verdict

Give a recommendation for each, with reasons. **Do not decide them.**

1. **P-2 reading** (plan §10 step 5): the stored trim is "the removed interval as stated by the decision", clipped at resolution exactly as the oracle's "absolute" form. Is the relative / absolute distinction then only nominal? Or does FP §12.4 "relative" mean something the implementation does not do (e.g. intervals relative to the cause's claim)?
2. **ER scope** (plan §10 step 6): ER is enforced as a G\* invariant on **every** decision, so an End dated inside its own period's existing trim is refused (oracle: 21 such End refusals at L = 5). Is that within the PO's decision ("a correction is refused if …", record §2), given record §3 (ER in the enforcement set) and canonical §6? Or should ER apply to Correct only?
3. **The ≤ 1-consequence argument:** two surviving mutants (`Member.php:269`, `Resolution.php:302`) are classified equivalent because "every accepted Correct carries consequences on at most one period". Try to falsify it, e.g. LiveCut holes in Δ, chains, revived causes. Report the search space if no counterexample is found.
4. **Guide 12:** promised by the PBDIGIT-264 Part 2 plan (`:99`) and never written. A backlog item, or something else?

### R-5. Deviations and interim states

- **`Correct` is not an `EffectiveDatedFact`:** R-1a-6 for the corrected value is deferred to COR-4. Is that sound, and is it recorded where COR-4 will see it?
- `PeriodAbsorbed` was declared in step 3, before its behaviour in step 6.
- Any interim behaviour left behind by steps 3–6.

### R-6. Regression and boundaries

- **Database-free suites:**
  - Membership Domain / Organisational, Application / Organisational, Infrastructure / Organisational;
  - `tests/Architecture/Membership*Test.php`;
  - `tests/Unit/Testing`, which needs the clean worktree because of the main tree's cached config.
- **AC-8:** no persistence, application or schema change. Run `git diff --stat abb81b1c5^ 763a47cf3`.

### R-7. Code quality, at proportionate depth

- Correctness risks first.
- **Performance:** Resolution is recomputed several times per decision (g2, `trimsInForce` before and after). Record as an observation for COR-3 / COR-4 only; no redesign.

### R-8. Documentation

- Guide 13 is grounded in the committed code (no invented APIs).
- Plan §10, story status and session log are consistent with the commits.
- Anonymity (ADR-T11) is untouched.

## 5. Report format

- **A. Verdict recommendation:** ACCEPT / ACCEPT WITH CONDITIONS / DO NOT ACCEPT. Give the conditions, and say which ones need code changes.
- **B. AC-1…AC-9 table:** status and evidence.
- **C. Findings:** ID, severity (critical / major / minor / observation), VERIFIED / INFERRED, `file:line`, a reproduction for any defect.
- **D. R-4 points 1–4:** a recommendation each, with reasons.
- **E. Evidence consistency:** counts, hashes, commit references and claims in plan §10 that do or do not reproduce.
- **F. Residual risks:** for the PO's acceptance record.
- **G. Out of scope:** anything found outside COR-2, as candidate backlog items, not fixes.

Also report the commands actually run, and confirm that the worktree and any scratch artefacts were cleaned up.

## 6. What happens after

The report goes verbatim into the review-and-acceptance record. The implementing session spot-checks it and states what it checked. **The PO decides.** Conditions, if any, become authorised follow-up steps under PBDIGIT-327 or new backlog items. **Engineering never accepts its own work.**
