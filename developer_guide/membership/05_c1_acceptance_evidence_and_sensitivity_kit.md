# 05: C-1 acceptance evidence and the reproducible sensitivity kit

**Story:** `PBDIGIT-268` · **Plan:** `docs/plans/20261010-1144-membership-c1-acceptance-correction-plan.md` (approved by the PO 2026-10-10, incl. CP-1) · **Review:** `docs/publicdigit/reviews/2026-10-10-membership-c1-acceptance-review.md` · **Kit:** `docs/publicdigit/membership/architecture/models/c1_sensitivity/`

## Purpose

The acceptance review of C-1 found three gaps in the test evidence:
- K-E1 had no test;
- three guard-order pairs were untested;
- K-H1 (`knownAt`) was checked by example only.

This guide explains the tests that close them, and the **sensitivity kit**: how to show that a test catches a real defect when the behaviour already exists, so a RED phase is impossible.

## Where it fits

Test-only. **No production code changed** (`git diff 5765b9fa3 -- app/` is empty).

```text
tests/Unit/Contexts/Membership/Domain/Organisational/
  AnnulVoidTest.php       ← K-E1 (2 tests), guard order (3 tests)
  C1PropertyTest.php      ← K-H1 property
docs/publicdigit/membership/architecture/models/c1_sensitivity/
  run.php                 ← the runner
  mutants.tsv             ← mutant id → test file → test method → what it violates
  M0.patch … M9.patch     ← one deliberate defect each
```

## Design decisions

- **Sensitivity instead of RED (CP-1, PO-approved for this correction).** A test written after its behaviour passes at once. Its value is shown by a recorded mutant that violates the behaviour and makes the test fail. Commits say "no RED phase"; no history is rewritten (H-2).
- **The working tree is never edited.** Mutants run in a disposable scratch copy, which also keeps within the repository rule against automated edits of source code.
- **A canary (M0)** proves that the scratch copy is the code actually tested. Composer's optimised classmap would otherwise load the real classes.
- **A control run first.** The unpatched test must pass in the scratch copy, so a kill can only come from the mutant.

## The tests

| Test | Contract | What it pins down |
|---|---|---|
| `test_an_accepted_annul_carries_its_fact_data` | K-E1 | the accepted Annul decision exposes id, target, provenance, `decidedAt`, `basedOnVersion`. The log is built with `Member::fromLog`, so the Annul is the only decision |
| `test_an_accepted_void_carries_its_fact_data` | K-E1 | the same for Void, with `decidedAt` exactly as given, including `null` |
| `test_decided_at_is_checked_before_the_target_kind_provenance_and_liveness` | guard order 3 < 4, 5, 6 | `DecidedAtRequired` wins over a Void target, an attested target and a non-live target |
| `test_annul_of_void_is_refused_before_the_provenance_check` | guard order 4 < 5 | an **attested** Void gives `AnnulOfVoidRefused` |
| `test_provenance_is_checked_before_liveness` | guard order 5 < 6 | a voided **attested** Admit gives `TargetHasNoDecisionProvenance` |
| `test_known_at_equals_the_oracle_and_is_prefix_stable` | K-H1 / P-KNOWN | for every accepted history (H = 5, length ≤ 5) and every τ before / at / after each `recordedAt`: (i) `knownAt(τ)` equals the **g6 port's** resolution of the facts recorded ≤ τ; (ii) knownAt over F·f equals knownAt over F for τ < recordedAt(f) |

## How to run the kit

From the repository root:

```bash
php docs/publicdigit/membership/architecture/models/c1_sensitivity/run.php          # all mutants
php docs/publicdigit/membership/architecture/models/c1_sensitivity/run.php M0 M3     # selected
C1_SENSITIVITY_SCRATCH=/some/dir php …/run.php M5                                    # keep the scratch copy
```

Expected output (2026-10-10):

```text
M0: KILLED    test_annulling_an_admission_removes_its_period  [CANARY …]  control OK, mutant: Tests: 1, Assertions: 3, Failures: 1, …
M1: KILLED    test_an_accepted_annul_carries_its_fact_data  [K-E1: decideAnnul drops decidedAt] …
…
M9: KILLED    test_known_at_equals_the_oracle_and_is_prefix_stable  [K-H1: knownAt boundary …] …
working tree app/ tests/: unchanged
```

The exit code is 0 only if:
- every control passes;
- every mutant is killed;
- `app/` and `tests/` are unchanged.

## How to add a mutant

1. Write a unified diff against `app/…` with `a/` / `b/` prefixes. Each hunk must apply to the current code.
2. Add a row to `mutants.tsv`: id, test file, exact test method, the violated rule.
3. Run it, and **read the summary**:
   - `Failures: 1` at the target assertion is a real kill;
   - `Errors: 1` needs inspection: the mutant may be killed for the wrong reason (a setup crash, an undefined method).

## Pitfalls (observed while building it)

- **Kill for the wrong reason.**
  - The first K-E1 Annul test built its history with `commit()`, so the `basedOnVersion` mutant (M3) crashed in the setup (`Errors: 1`). Fixed by building the log with `Member::fromLog`.
  - The first M9 called `Instant::isBefore()`, which does not exist. Fixed to `$tau->isAfter($r->recordedAt())`.
- **Infection does not generate these mutants.** It has no "drop this constructor argument" or "reorder guards" mutator. Infection's MSI (92 %, covered 95 %) is unchanged by this correction; the kit complements it.
- **Bounds.** K-H1 is verified for H = 5, length ≤ 5, with strictly increasing `recordedAt`. Ties (allowed by INV-14) are not enumerated. This is not a proof.
- **Oracle independence.** D-COR-2 (`TargetNotLive`) is not in the independent Python oracle; the wrapper adds it.

## Testing

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Membership/Domain/Organisational/
```

The result on 2026-10-10 was `OK (89 tests, 112487 assertions)`.

**Traceability:** PBDIGIT-268 · contract §14.3 K-E1, K-H1, §14.4 P-KNOWN · plan 20261010-1144 (CP-1, H-1 … H-6) · commits `9ae69b7bc`, `fccf5b778`, `e96d12a5d`.
