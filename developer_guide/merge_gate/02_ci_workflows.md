# 02 — CI: the gate on every PR, the quality tier on a schedule (PB-007 7E)

> Like guide 01, this describes the **contract**, not the implementation. The
> workflows contain no gate logic — they invoke the stable composer interface.

## Purpose

Make the blocking tier unavoidable: every pull request executes the same single
command a developer runs locally. CI adds no steps, skips no steps, and never
re-encodes the gate's composition.

## The two workflows

| Workflow | Trigger | Runs | Blocks merges? |
|---|---|---|---|
| `greenfield-merge-gate.yml` | every PR · push to main branches · manual | `composer merge-gate` | **Yes** — the job's exit code is the gate verdict |
| `greenfield-quality-tier.yml` | weekly schedule · manual | `composer quality-gate` | **No** — measures and uploads the mutation reports as artifacts (A-3) |

## Design decisions

- **One command per workflow (ARB R3).** If the gate's composition changes, only
  `composer.json` changes; the workflows are stable. CI can never drift from the
  local gate, because both ARE the same interface.
- **Quality tier is separate and scheduled** — mutation measurement takes over an
  hour under the validated execution model and must never sit on the merge path.
- **Validated execution model.** The quality tier runs single-threaded: the F-7D-2
  evidence validation proved parallel mutant execution produced false kills
  (concurrent `migrate:fresh` races on the shared test database). Restoring speed
  *with* validity (per-thread `TEST_TOKEN` databases) is recorded future work.
- **Database credentials** mirror `tests/bootstrap-test-database.php`, which
  force-overrides them for every test run — the CI service simply provides the
  database that guard expects.
- **Coverage driver per platform is an implementation detail:** locally Xdebug is
  loaded per-invocation from the extension dir; in CI setup-php loads it via ini.
  Callers of `composer quality-gate` never know or care.

## Pitfalls

- Don't add gate steps to the workflow "just for CI" — new gates enter through
  `composer.json` (and an ARB-approved design), or they don't exist.
- Don't schedule the quality tier more often than the evidence is consumed —
  measurement without a reader is noise.
- The quality job's success does NOT mean the mutation numbers are good; it means
  the measurement ran. The numbers live in the uploaded artifacts and are
  interpreted at qualification/retrospective time.

## Testing

The merge-gate workflow's first green run on a real PR is its verification (the
same command already passes locally — see guide 01 / IDD §2e). The quality tier
verifies on its first scheduled or manually dispatched run.

**Traceability:** PB-007 7E · IDD §2d (tiers), §2e (F-7D-2 validated execution model) · ARB R3 · A-3.
