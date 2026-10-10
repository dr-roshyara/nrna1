# Plan: C-1 acceptance correction (test-only), PBDIGIT-268

**Status:** `APPROVED` by the PO / Human ARB, 2026-10-10 (recorded below). Implementation in progress.
**Story:** `PBDIGIT-268` (IMPLEMENTED, AWAITING REVIEW) · **Source:** `docs/publicdigit/reviews/2026-10-10-membership-c1-acceptance-review.md` §3, §9 (H-1) · commission `…/brainstorming/20261010_1143_implemenation.md`
**Supersedes:** none. It extends `docs/plans/20261010-1009-membership-c1-annul-void-tdd-plan.md` (C-1 plan), whose scope and conditions P-1 to P-3 and 1 to 7 still apply.
**PO / Human ARB decisions (Dr. Nab Raj Roshyara, 2026-10-10, answered in session):**
- **H-1:** the plan is approved for implementation.
- **CP-1:** approved. Reproducible sensitivity via stored mutant patches in a scratch copy, instead of a claimed RED phase; stop if any mutant survives.
- **H-5:** all four documentation changes are authorised (review addendum, guide line 13, story TDD wording, the PBDIGIT-268 README row).
- **H-2:** the TDD deviation is accepted as documented; there must be no false RED / GREEN history.
- **H-3:** F-1 is tracked to C-4; no adapter change.
- **H-4:** `VoidFact` is confirmed.
- **H-6:** formal acceptance is deferred until the correction has been independently reviewed.

(Drafted by the Principal Reviewer in `20261010_1158_implementation.md`. Recorded by the PO's answers, then **confirmed in the PO's written decision**: "As PO / Human ARB, I approve the C-1 acceptance-correction plan … including CP-1 … Stop and report if any required mutant survives … No production-code, adapter, schema, database, application-wiring, cutover, or live-write changes are authorised. Do not start C-2, C-3, or C-4 under this approval.")

## Objective

Close the C-1 test-evidence gaps found by the acceptance review, so the PR and PO can take the formal acceptance decision. **No domain, production, adapter or schema change.**

## Background

The acceptance review and the fresh-context reviewer [IR] found:

| Gap | Finding |
|---|---|
| K-E1 | **MISSING**: no test asserts the data an accepted Annul / Void decision carries [IR MAJOR-1] |
| refusal order | 3 adjacent Annul guard pairs untested (guards 3/4, 4/5, 5/6) [IR MINOR-1] |
| K-H1 | P-KNOWN checked by one example, not as the §14.4 property [IR MINOR-2] |
| sensitivity | hand-made mutants left no artefact, so they could not be verified [IR] |

## Scope

| In | Out (explicitly excluded) |
|---|---|
| new tests in `AnnulVoidTest.php` and `C1PropertyTest.php` | **any production change** (`app/`) |
| a reproducible sensitivity kit (mutant patches + runner) **outside `app/` and `tests/`** | adapter, persistence, schema, DB, migrations (**F-1 stays a C-4 obligation**, draft H-3) |
| on H-5: wording corrections (review addendum, guide, story) and the PBDIGIT-268 README row | Correct, consequences, application wiring, event emission, cutover, live writes |
| plan progress, session log | new story IDs; backlog restructuring; rewriting history |

## Design decisions (for PO review)

| # | Point | Proposal | Why |
|---|---|---|---|
| CP-1 | **No RED is possible.** Every behaviour already exists, and the correction is test-only | use **sensitivity instead of RED** for every new test: a recorded mutant violating the behaviour makes the test fail; then the mutant is discarded. Each commit says "no RED phase" and names its mutants | H-2 forbids claiming strict RED / GREEN where the behaviour came first. This applies strategy §4 (PROPOSED) to this correction only; **needs explicit PO approval** |
| CP-2 | **Reproducible sensitivity** (fixes the [IR] objection) | mutants as **patch files** in `docs/publicdigit/membership/architecture/models/c1_sensitivity/`. A runner script exports `app/` + `tests/` (`git archive HEAD`) into a **scratch directory**, applies one patch, runs the named test filter there with a scratch bootstrap that maps `App\` / `Tests\` to the scratch copy before `vendor/autoload.php`, and expects failure | **the working tree is never edited**: no temporary production edits (source-code editing policy) and no history impact. Anyone can re-run it. A **canary** (a patch that must fail a trivial test) proves the scratch copy is the code actually exercised |
| CP-3 | K-H1 reference must not call the production `knownAt` / liveness | P-KNOWN as a property over the bounded histories (`C1Histories`, H = 5, length ≤ 5, recordedAt strictly increasing by commit). For every τ (before the first record, at each recordedAt, between them): **(i) reference:** `G6CorrectionOracle::resolve` over the g6 facts recorded ≤ τ, live periods only, compared with kernel `knownAt(τ)` per period on 0..H−1; **(ii) metamorphic:** for each history F·f and τ < recordedAt(f), `knownAt_{F·f}(τ) = knownAt_F(τ)` | (i) is independent of production code; (ii) is the literal §14.4 statement. The bounds are stated; **verified within bounds, not a proof**. Ties in recordedAt (allowed by INV-14) are not enumerated (limitation, stated) |
| CP-4 | Always-true "nothing appended" assertions [IR NOTE-1] | **leave the existing tests unchanged**; record in the review addendum that they are not evidence | changing passing tests is outside the gaps; no evidence is lost |

## Test cases (expected to pass at once; sensitivity per CP-1)

| ID | Test (file) | Inputs | Asserts | Killing mutant(s) |
|---|---|---|---|---|
| T-E1a | `test_an_accepted_annul_carries_its_fact_data` (`AnnulVoidTest`) | admitted A1; `decideAnnul(X1, A1, Provenance::decision('registrar-2'), Instant 900)` | `fact()` is an `Annul`; id `X1`; target `A1`; provenance kind and actor equal; `decidedAt` = 900; `basedOnVersion` = version before | M1 drop `decidedAt` (`null`); M3 `basedOnVersion` off by one; M4 target ↔ id swapped |
| T-E1b | `test_an_accepted_void_carries_its_fact_data` (`AnnulVoidTest`) | the same, Void, once with `decidedAt` 900 and once with `null` | the same fields; `decidedAt` exactly as given (incl. `null`) | M2 drop `decidedAt` in `decideVoid` |
| T-O1 | `test_decided_at_is_checked_before_the_target_kind_provenance_and_liveness` | `decidedAt` null with targets (a) a Void, (b) an attested Admit, (c) a non-live Admit | `DecidedAtRequired` in all three | M5 move guard 3 after guard 4 (and after 5 / 6) |
| T-O2 | `test_annul_of_void_is_refused_before_the_provenance_check` | a Void recorded with **attested** provenance; Annul it | `AnnulOfVoidRefused` | M6 swap guards 4 / 5 |
| T-O3 | `test_provenance_is_checked_before_liveness` | an attested Admit, then voided (not live); Annul it | `TargetHasNoDecisionProvenance` | M7 swap guards 5 / 6 |
| T-H1 | `test_known_at_equals_the_oracle_and_is_prefix_stable` (`C1PropertyTest`) | CP-3 | (i) and (ii) | M8 `knownAt` ignores τ; M9 boundary (`recordedAt ≤ τ` → `< τ`) |
| canary | runner self-check | M0: a patch that makes `Annul::target()` return the id | a known existing test fails | proves the scratch copy is the code under test |

Each mutant M0 to M9 is one patch file. The runner reports, per mutant, **killed by the named test** or **survived** (a survivor fails the plan's DoD).

## Task checklist (after approval)

- [ ] 0. **Baseline:** domain + application unit, 3 architecture files; record the counts (expected 83 / 34 / 12 at HEAD `5765b9fa3`, re-verified, not assumed). Build the sensitivity runner and prove the canary.
- [ ] 1. T-E1a, T-E1b: run (expect pass); run M1 to M4 (expect killed). Commit: `test(membership): K-E1 decision data, no RED phase, sensitivity M1-M4 (PBDIGIT-268)`.
- [ ] 2. T-O1, T-O2, T-O3: likewise with M5 to M7. One commit.
- [ ] 3. T-H1: likewise with M8, M9. One commit.
- [ ] 4. Regression: unit + architecture; Infection with the same scratch configuration and scope as the acceptance review (expect no new survivors; report the MSI with its scope).
- [ ] 5. On H-5 only:
  - an addendum to `2026-10-10-membership-c1-implementation-review.md` (K-E1, the qualified TDD claim, P-KNOWN, NOTE-1; append, do not rewrite);
  - guide `04` line 13: "no event *emission* (C-3); the decision carries the event data (K-E1)";
  - the story wording "TDD-first except K-R1 / R2 / R3 / G1 / H1 (verification-only)";
  - the README row after `PBDIGIT-267`, committed through a temporary index with that path only, after re-checking that the README is clean.
- [ ] 6. A correction report (append to the acceptance review as §11 "Correction evidence"), story progress, session log. **STOP** for the independent review and the formal C-1 acceptance (H-6).

## Definition of Done

1. T-E1a / b, T-O1 to O3 and T-H1 exist and pass, and each asserts the contract text it cites.
2. **Every mutant M1 to M9 is killed by its named test.** The canary M0 shows the runner exercises the mutated copy. The runner output is recorded verbatim.
3. Unit and architecture regression green; Infection re-measured with the scope stated; the working tree's `app/` is untouched (`git diff --stat HEAD -- app/` empty).
4. Bounded evidence is described as bounded. D-COR-2's lack of oracle independence [IR MINOR-3] stays stated.
5. No excluded scope touched. **The PostgreSQL feature suite is not run** (no isolated DB); persistence stays unverified.
6. Commands and outcomes recorded. **No claim of RED** for these tests.

## Risks

| Risk | Mitigation |
|---|---|
| the scratch-copy runner silently runs the real code (autoload path resolution) | the canary M0 must fail. The runner aborts if M0 survives |
| T-H1 runtime (≈ 5800 histories × ≤ 7 τ values) | measured at step 3. If over 10 s, length ≤ 4 (bounds restated) |
| a mutant survives (a real gap) | **stop and report**: a surviving mutant means a test is wrong or the contract is unclear. No production change under this plan |
| H-5 not recorded | steps 0 to 4 still deliver the evidence; step 5 waits |

## Open questions and dependencies

- **Requires PO decisions:** H-1 (approve this plan for implementation), H-2 (deviation accepted), **CP-1 (sensitivity instead of RED for this correction)**, H-5 (step 5). H-3 / H-4 do not block this plan.
- Not affected: CQ-9, BD-REV-ER, D-COR-1 / 3 / 4, CQ-3 (outside C-1).

## Acceptance criteria remaining after the correction

- formal PR / PO acceptance of C-1 (H-6);
- F-1 → C-4 entry obligation;
- persistence integration (PostgreSQL suite on an isolated DB) → C-4 / PBDIGIT-264 / 266 gate;
- MINOR-3 (D-COR-2 not in the independent oracle) is documented, with no action.

## Progress

- 2026-10-10 11:44: plan drafted. **Stopped for approval.**
- 2026-10-10: **APPROVED** by the PO (H-1, CP-1, H-2 … H-6). Implementation started.
