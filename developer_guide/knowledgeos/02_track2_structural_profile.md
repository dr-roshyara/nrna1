# 02 — The Track-2 Structural Profile (deterministic assurance, Phase 0)

> **Phase 0 is the historical back-test** — it proves the mechanically-detectable
> defect classes that Track 2 paid for in amendment cycles are now detectable by
> a **read-only** check, by REDISCOVERING them in the migration plan's own history
> (`0a2fa71d` → `7d3abc59` → `8307beca`). The run is **warn-only**: it reports and
> exits 0; it is wired into no gate, hook, or CI.

## Purpose

Explain the S1–S5 structural checks and the four CLI entry points that reach
them, so a developer can run them, extend them, and — critically — **know what
they do NOT prove**. The rules live in the capability library, never in the
scripts; this guide shows that boundary.

## Where it fits

```
scripts/lib/EngineeringKnowledge/
  Capabilities/
    IdentifierIntegrity/   → S1 document-local register        (CAP-001 / DP-1)
    ReferenceIntegrity/    → S2 intra-document refs · S4 tables (CAP-004 / DP-4)
    VocabularyIntegrity/   → S3 vocabulary · S5 dispositions    (CAP-003 / DP-3)
  Shared/
    Domain/                → Assessment · Verdict               (shared verdict vocabulary)
    Infrastructure/        → StructuralCliReporter              (rendering + D-4 statement)
  Tests/
    Domain/  Application/  Infrastructure/                      (per-rule unit tests)
    BackTest/Phase0BackTest.php                                 (S6 — the §6 matrix as a test)
    Adapters/StructuralAdaptersCliTest.php                      (S7 — integration at the real entry points)
scripts/
  knowledge-lint.php       → --profile=structural --root=<dir> [--vocabulary=<path>] [--strict]
  link-check.php           → --anchors=<dir>
  identifier-check.php     → --document=<path>
  verify.sh                → Gate 7, severity warn
```

## The five slices

| Slice | Rule (application service) | What it catches |
|---|---|---|
| **S1** | `ValidateDocumentLocalIntegrity` (`MarkdownDocumentContentsReader`) | numbered section headings not unique (`## 4.1` twice) or not monotonic — **DI-1** |
| **S2** | `ValidateIntraDocumentReferences` (`MarkdownIntraDocumentReader`) | `§x.y` resolving to no/multiple headings; `step N` cited but not defined by a normative enumeration block — **DI-5** |
| **S3** | `ValidateVocabularyIntegrity` (`YamlVocabularySource` + `MarkdownVocabularyReader`) | retired terms used live (not quoted/§-scoped/Traceability); confusable identifiers (`CASE B`/`CASE β`) without a backticked collision declaration — **DI-2 · DI-7** |
| **S4** | `ValidateTableColumnCount` (`MarkdownTableReader`) | a data row whose cell count differs from its header — a ragged table |
| **S5** | `ValidateCompetingCurrentDefinitions` (`MarkdownDispositionReader`) | an unlabelled superseded disposition competing with a declared split — **DI-4**, `WARN` only |

Every rule emits an `Assessment::of(Verdict, evidence, subject)`. Verdicts come
from the emittable subset **PASS · FAIL · WARN · INCONCLUSIVE**. **Fail-closed
(D-2):** a slice that cannot evaluate — no vocabulary config, no table, no
disposition rows, no headings — is **INCONCLUSIVE**, never PASS. *Absence of
evidence is not PASS* (inherited from `CAP-001`).

## The adapter boundary (why the scripts stay thin)

The four DA-named entry points are **adapters**: they scan, invoke, and report —
they own **no rule**. A script that re-states a policy is a defect. Example —
`link-check.php --anchors`:

```php
$service = new ValidateIntraDocumentReferences(new MarkdownIntraDocumentReader());
$assessment = $service->handle($file);
echo $reporter->sliceLine('S2', $rel, $assessment);   // rendering only
```

`knowledge-lint --profile=structural` wires all five services in one run and
prints a per-slice summary. `identifier-check --document=<path>` runs S1 over a
single document's numbered headings. `verify.sh` Gate 7 runs the structural
profile over `docs/knowledgeos/architecture` with severity `warn` — it always
returns PASS and never sets `OVERALL_STATUS` (double warn-only: the script exits
0, and the gate treats a non-zero as a warning).

## D-4 — the one sentence every report must carry

`StructuralCliReporter::NOT_CHECKED_STATEMENT` is **one text** shared across all
three scripts. It is the mitigation for the top risk of the whole programme —
**false assurance**:

> mechanical assurance proves DECLARED STRUCTURE; architecture review discovers
> UNDECLARED ARCHITECTURAL CONTENT.

A GREEN report here is *mechanical*, not architectural, assurance. The checker
cannot say "you forgot to declare an act" (`RD-1`, `RD-7`, `RD-3·b` are
architecture-review discoveries). **Never trim the statement; never let a report
be emitted without it.**

## How to run it

```bash
php scripts/knowledge-lint.php --profile=structural \
  --root=docs/knowledgeos/architecture [--vocabulary=docs/knowledge/schema/vocabulary-integrity.yaml]
php scripts/link-check.php --anchors=docs/knowledgeos/architecture
php scripts/identifier-check.php --document=docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md
bash scripts/verify.sh     # Gate 7 included, warn-only
```

All four exit 0 in Phase 0. `--strict` re-exits 1 on FAIL/INCONCLUSIVE but is
**wired into no gate, hook, or CI** — it is a manual developer affordance only.

## Extending

1. **Name the defect class it serves first** — which `DI-*`/`RC-*` row, which `DP-*`,
   which catalogue capability? If none exists, **stop and raise it** — building
   first is capability-by-implementation (`OQ-1` is the live example: `DI-3`/`DI-6`
   has no catalogued capability, so `S8` stays blocked).
2. Domain rule + VO + service in the owning capability; reader in Infrastructure.
3. RED → GREEN → REFACTOR in `Tests/{Domain,Application,Infrastructure}`.
4. Wire it as a new slice in `run_structural_profile()` — one line per service.
5. Add an integration case to `StructuralAdaptersCliTest`.

## Pitfalls

- **Re-implementing a rule in a script.** The scripts must stay D-1-pure. If a
  verdict looks wrong, fix the service + reader, not the echo.
- **Reading `PASS` from silence.** A slice that scanned nothing is `INCONCLUSIVE`,
  not PASS. Tests assert this at the boundary (`StructuralAdaptersCliTest`).
- **A `step N` token in prose.** S2 reads step references from every non-fence
  line, including table rows and backticked code — that is how it caught AMD5's
  real defect. A meta-document quoting a defect must not quote the bare token.
- **Emitting a report without D-4.** The NOT-CHECKED statement is the whole
  honesty contract. A checker that stops printing it has stopped being honest.
- **Wiring `--strict` anywhere.** A blocking check is a governance object; that
  is a Phase-1+ act with its own authority, not an adapter decision.

## Traceability

Plan `docs/plans/20260821-1138-track2-deterministic-assurance-phase0-plan.md` *(§4 D-1…D-6 · §6 exit criterion · §7 DoD)* · report `docs/knowledgeos/reviews/2026-08-21-track2-phase0-historical-back-test-report.md` · catalogue `docs/implementation/PKS_Phase_III_Capability_Catalog.md` *(CAP-003 REALIZED · CAP-004 extension · H-CAT-1/OQ-4 evidence · OQ-1 raised)* · capability library `scripts/lib/EngineeringKnowledge/**` · entry points `scripts/{knowledge-lint,link-check,identifier-check}.php` · `scripts/verify.sh` Gate 7 · implementation commits `895d38cb` · `d16a3a78` · `4a923440` · `73dbe091` · `a2529dde` · `b753cac1` · `7f04e220`.
