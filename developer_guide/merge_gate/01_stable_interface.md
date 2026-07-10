# 01 — The stable interface: `composer merge-gate` / `composer quality-gate` (PB-007 7D)

> **This guide describes the engineering interface, not the implementation.** Internal
> tools, invocation details and supporting scripts may evolve without changing this
> contract. Time-dependent measurements (baselines, counts, trends) live in the
> qualification records — the PB-007 Completion Review, session logs and the IDD —
> never in this guide.

## Purpose

One executable entry point per gate tier. You never invoke Deptrac, PHPStan, Infection or
the fitness suites individually to qualify a merge — the composer script is the contract.

> **ARB R3 (binding):** `composer merge-gate` is a STABLE PUBLIC INTERFACE to the
> engineering system. Internal tooling (driver, tool versions, invocation details) may
> change; the command contract must not.

## Where it fits

Engineering-process layer (EPIC-000 territory) — not product code. It protects the
qualified architecture; it changes no behaviour. Definition: `composer.json` `scripts`
(+ `scripts-descriptions`). Design authority: PB-007 IDD §2d (gate tiers) — FROZEN.

## The blocking tier

```bash
composer merge-gate
```

Runs, fail-fast, in this order (composer aborts on the first non-zero exit):

1. **Architecture fitness suites** — hexagonal completeness, event ownership,
   anonymity (AT-Q7), the CorrelationId minting guard, and every other executable
   architecture rule.
2. **Deptrac in fail mode** — dependency-direction and context-isolation rules derived
   from the APPROVED architecture (ARB R1). Report mode ended with 7A; the gate
   *consumes the exit code*, so any new violation blocks.
3. **Greenfield PHPStan** — the engineering gate over the greenfield contexts.
4. **Widened regression (Behaviour)** — the standard regression set (all greenfield
   contexts + Shared + Replay; F-PB006-4). Architecture ran in step 1, so it is not repeated.

Cheap architectural verdicts fail before expensive regression. The last line on success
is `MERGE GATE: PASS (…)`; the exit code is the verdict — usable directly in CI and
locally before pushing.

## The non-blocking tier

```bash
composer quality-gate
```

Two steps — this shape is deliberate (IDD §2c-iii):

1. **PHPUnit generates coverage under PROJECT semantics** — the coverage driver is
   loaded for this invocation only; the default `php` runtime stays untouched. Output:
   `build/coverage/` (coverage XML + JUnit report).
2. **Infection consumes the pre-generated coverage** (`--skip-initial-tests`).

Why not let Infection run PHPUnit itself? Infection's generated configuration imposes
its own execution semantics (stop-on-defect), which conflict with this repository's
harness behaviour and can silently truncate coverage — producing a *wrong* measurement
rather than a failed one. Principle: **the platform owns orchestration, not the
third-party tool.**

This tier *measures*; it never blocks. It reports:

- MSI
- Mutation Code Coverage
- Covered Code MSI (Test Strength)
- Escaped mutants

The baseline and its interpretation are recorded in the PB-007 qualification evidence
(IDD §2c / Completion Review). Thresholds rise only via the A-3 ratchet — deliberately,
at milestones, never retroactively, never lowered; the first ratchet is backlog item
**ENG-004**.

## How to extend

- **New bounded context reaches hexagonal completeness** → add its layers to
  `deptrac.yaml` (from the approved model), its paths to the PHPStan gate config,
  its test dirs to the regression/mutation suites. The commands do not change.
- **Replace a tool** (PHPStan → Psalm, Xdebug → PCOV, a new mutation tester) → change
  the script internals only. `composer merge-gate` is untouched; callers never notice.
  That is the point of R3.
- **Raising `min-msi`** → only through an ENG-004-style ratchet ticket, never ad hoc.

## Pitfalls

- **Current limitation (temporary):** gate invocations must not run concurrently on one
  machine, because the pgsql test harness performs `migrate:fresh` against a shared
  database and has no per-test rollback — parallel runs race and fail with spurious
  unique violations. This is an operational constraint, not architecture; it should be
  removed once isolated per-run test databases become available.
- Some Feature tests are currently classified as PHPUnit **"risky"** because of the
  shared pgsql harness (framework-level teardown bookkeeping — see F-7C-6). Risky ≠
  fail: the gate treats exit codes, not risky counts, as the verdict, and this does not
  invalidate the quality gate. The current count and trend are maintained in the
  engineering records.
- Don't "fix" an architecture violation by editing `deptrac.yaml` — rules encode the
  approved architecture; violations are ARB findings (rollout order: classify, never silent-fix).

## Key files (implementation detail — may evolve)

| File | Role |
|---|---|
| `composer.json` (`scripts.merge-gate`, `scripts.quality-gate`) | The stable interface |
| `deptrac.yaml` | Architecture rules derived from the APPROVED model (never from the filesystem — ARB R1) |
| `phpstan-greenfield.neon` | Engineering gate scope (greenfield contexts) |
| `phpunit.xml` (`Architecture`, `GreenfieldCore` suites) | Fitness + regression + mutation-kill sets |
| `infection.json5` | Mutation scope (A-3), per-mutant timeout, log destinations |

## Testing

The gate is itself the measuring instrument; its verification is a full PASS run with
all blocking steps green plus a quality-tier run that produces the metric set. The
evidence lives in the PB-007 session log and Completion Review.

**Traceability:** PB-007 7D · IDD §2a (R1–R3), §2c-iii (two-step invocation), §2d (tiers) · A-1..A-3 · F-PB006-4 (regression set) · F-7C-6 (risky artifact) · ENG-004 (first ratchet).
