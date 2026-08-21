# 03 — Phase-1 Author-Side Adoption (the handoff-assurance report)

> **Phase 1 turns the Phase-0-proven S1–S5 checks into an AUTHOR-SIDE PRACTICE.**
> The author runs the same deterministic checks before Architecture/Governance
> handoff and attaches the evidence. It stays **warn-only**: the report is
> evidence, never a gate — the command always exits 0, and nothing is wired
> into CI, a hook, or a review rule.
>
> ⛔ **The sentence every Phase-1 artifact carries:** *Automation produces
> assurance evidence. Automation does NOT produce: architectural decisions ·
> governance decisions · acceptance · authority · ownership · reviewer
> independence · migration authorization.*

## Purpose

Explain the `knowledge-lint --report=handoff` workflow — the command, what the
report contains, the FIX BEFORE HANDOFF practice, the five-item handoff package,
what remains human, and the pitfalls that produce false assurance. This is the
**developer how-to** for guide 02's checks; guide 02 is the *what/why*, this is
the *run-and-attach*.

## Where it fits

```
scripts/lib/EngineeringKnowledge/
  Shared/
    Domain/
      HandoffContext.php              → six-field provenance VO (target · checker · version · commit · generated-at · command)
      AssuranceHandoffReport.php      → the report VO: nine elements, fail-closed aggregate (D-3), D-4 recommendation + NOT-CHECKED statement
      CheckerVersion.php              → CURRENT = '1.0.0'
    Application/
      GenerateHandoffAssuranceReport.php → composes the five Assessments into the report; supplies named NOT-CHECKED areas + limitations
    Infrastructure/
      StructuralCliReporter.php       → renderHandoffReport() — the terminal/markdown rendering
scripts/
  knowledge-lint.php                  → --report=handoff --document=<path> [--vocabulary=<path>] [--out=<path>]
scripts/lib/EngineeringKnowledge/Tests/Adapters/
  HandoffAdaptersCliTest.php          → the 8 integration tests (real entry point, fixtures)
```

**Layering is the contract.** The report-content rules (nine elements,
aggregate precedence, recommendation vocabulary, the D-4 statement) live in
`Shared/Domain` — they are **business rules** (Phase-1 D-1), and the CLI
adapter is a thin shell over them. `StructuralCliReporter::NOT_CHECKED_STATEMENT`
is an **alias** of `AssuranceHandoffReport::NOT_CHECKED_STATEMENT`, so the
Phase-0 reports and the handoff report can never drift apart — the D-4 text is
one constant, never a copy.

## When to run

Before every Architecture / Governance handoff of a governed artifact (a plan,
a proposal, a decision record). The author runs the command, reads the findings,
remediates, reruns, and attaches the report to the handoff package. This
automates an obligation the migration commission already imposed — AMD6's
mandated **14-point manual pre-delivery check** — changing only who performs it.

## The command

```bash
php scripts/knowledge-lint.php --report=handoff \
    --document=docs/knowledgeos/architecture/My-Plan.md \
    [--vocabulary=path/to/vocabulary.yaml] \
    [--out=handoff-assurance.md]
```

- **`--document`** is required. Missing it is a usage error (exit 3).
- **`--vocabulary`** supplies the S3 config. **Without it, S3 is INCONCLUSIVE**
  (`vocabulary config not readable at …`), never PASS — *absence of evidence is
  not PASS*.
- **`--out=<path>`** writes the report to the file the author directs. Without
  it the report goes to stdout. The scanned document is **never written** —
  the checker is read-only w.r.t. governed artifacts.
- **Exit code is always 0** (warn-only, D-4). `--strict` is NOT applicable to
  handoff mode: combined, it prints a D-4 note and still exits 0.

## What the report contains (the nine required elements)

Rendered by `StructuralCliReporter::renderHandoffReport()`, assembled by
`GenerateHandoffAssuranceReport`:

```
Handoff assurance report — deterministic structural checks
Artifact : docs/.../My-Plan.md
Checker  : knowledge-lint --report=handoff · version 1.0.0
Source   : cb97d70d            ← git rev-parse --short HEAD, or UNKNOWN
Generated: 2026-08-21T14:54:59+00:00
Command  : php scripts/knowledge-lint.php --report=handoff --document=…

Checks executed (per-slice verdicts):
  [PASS] S1   [PASS] S2   [INCONCLUSIVE] S3   [PASS] S4   [PASS] S5

Findings — every non-PASS slice, with its evidence:
[FAIL] S2 · <path>
       dangling step reference 'step 5' (line 380) — the mandated block defines 1 · 2 · 3 · 4

⛔ NOT CHECKED (stated positively): mechanical assurance proves DECLARED
STRUCTURE — identifier uniqueness, intra-document reference resolution, declared
vocabulary, table shape, disposition labelling. It does NOT check soundness,
completeness, authority, or provenance — architecture review discovers
UNDECLARED ARCHITECTURAL CONTENT. A PASS here is mechanical, not architectural,
assurance.
NOT-CHECKED areas (named):
  - The undeclared-act class — … (OQ-1, S8)
  - …
Known limitations:
  - …
Aggregate verdict: [FAIL]
Recommendation: FIX BEFORE HANDOFF
```

**Aggregate (D-3) is fail-closed and position-independent:** one FAIL → FAIL;
else one INCONCLUSIVE → INCONCLUSIVE; else one WARN → WARN; else PASS. An
INCONCLUSIVE slice that merely precedes a FAIL can never mask it. **Never** read
an INCONCLUSIVE aggregate as "clean".

## The FIX BEFORE HANDOFF practice (author-side, warn-only)

| Aggregate | Recommendation | Author action |
|---|---|---|
| **FAIL** | `FIX BEFORE HANDOFF` | Resolve the cited findings (each names slice + line + defect), rerun the same command, attach the green run |
| **INCONCLUSIVE** | `REVIEW BEFORE HANDOFF` | The report names what could not be assessed (e.g. S3 without a vocabulary config). Decide explicitly; do not read it as PASS |
| **WARN** | `RESOLVE WARNINGS BEFORE HANDOFF` | Look at the warning (e.g. DI-4 competing dispositions) at author judgment |
| **PASS** | `ATTACH AS EVIDENCE` | Attach the report with the artifact identity, checker version, result, and NOT-CHECKED areas |

The report never emits `APPROVED`, `REJECTED`, or `ACCEPTED`. The recommendation
is author-side practice — the authority to accept a handoff stays with the
reviewer.

## The handoff package (the five-item minimum)

| # | Item | Where it is |
|---|---|---|
| 1 | Assurance report | the `--report=handoff` output |
| 2 | Artifact / version identity | `Artifact : <path>` + `Source : <commit>` |
| 3 | Checker version | `Checker  : … · version 1.0.0` |
| 4 | Result | `Aggregate verdict:` + `Recommendation:` |
| 5 | Known NOT-CHECKED areas | the D-4 statement + named rows |

No new frontmatter is required on the artifact; the package is the five items.

## What remains human

- **Architecture review stays mandatory.** The D-4 statement names the class
  only human review sees: **UNDECLARED ARCHITECTURAL CONTENT** — an act never
  declared in the plan (`RD-1`, `RD-7`, `RD-3·b`; no catalogued capability owns
  it — `OQ-1`, S8).
- **Authority, acceptance, ownership, migration authorization** — the report
  supplies evidence for these; it does not produce them.
- **Reviewer independence** is untouched: the author's report is not a
  reviewer's finding.

## Pitfalls (each produces false assurance — do not fall in)

1. **Reading PASS from silence.** A `[PASS]` slice proves only that its *shape*
   is absent (D-4). An **INCONCLUSIVE** aggregate is the checker telling you it
   *could not assess* something — that is not a defect and not a PASS; decide
   explicitly.
2. **The `step N` prose trap.** `Proceed to step 4.` in ordinary prose is a
   step-reference and dangles when no normative enumeration block defines `4`
   (S2). Fix the prose or define the block.
3. **Trimming the D-4 statement.** The NOT-CHECKED sentence is the mitigation
   for the whole design's top risk (false assurance). It is one constant
   (`AssuranceHandoffReport::NOT_CHECKED_STATEMENT`); never shorten, reword, or
   drop it from an attached report.
4. **Wiring `--strict`.** Handoff mode is warn-only by design; `--strict` is a
   Phase-0 profile flag and does not apply here (a note is printed). Never build
   a gate on the exit code — it is always 0.
5. **Over-claiming provenance.** `Source :` is **code provenance** (the checker's
   git HEAD), never AI-process attestation. EKS-07 (self-declared process ID ≠
   independently attested authorship) is deliberately not solved.

## Testing

`scripts/lib/EngineeringKnowledge/Tests/Adapters/HandoffAdaptersCliTest.php`
— 8 integration tests / 51 assertions over the **real** entry point:

- defective fixture → report shows FAIL + FIX BEFORE HANDOFF + exit 0
- clean fixture → PASS + ATTACH AS EVIDENCE
- no `--vocabulary` → S3 INCONCLUSIVE visible, aggregate INCONCLUSIVE (never PASS)
- provenance (checker name/version/commit/timestamp) + D-4 statement + named
  NOT-CHECKED + **no governance vocabulary**
- `--out` writes the file and leaves the scanned document untouched
- rerun-after-remediation flips FAIL → PASS
- missing `--document` → usage exit 3
- `--strict` combined → D-4 note + still exit 0

Run: `./vendor/bin/phpunit --testsuite=EngineeringKnowledge`.

## Traceability

Phase-1 plan `docs/plans/20260821-1641-track2-phase1-author-side-adoption-plan.md` (D-1…D-7) · commission of 2026-08-21 · evidence report `docs/knowledgeos/reviews/2026-08-21-track2-phase1-adoption-evidence.md` · Phase-0 guide `02_track2_structural_profile.md` · back-test `Tests/BackTest/Phase0BackTest.php` · implementation `1c326999` · `282ed339` · `cb97d70d` · `AssuranceHandoffReport.php` (D-3 aggregate, D-4 statement) · `HandoffContext.php` (D-5 provenance) · `knowledge-lint.php` (`run_handoff_report()`).
