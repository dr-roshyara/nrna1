# 01 — The Registry-First Workflow

## Purpose

Since Iteration 1 (2026-07-08), the `.claude` directory is not a folder of scripts — it is the **PublicDigit AI Engineering Platform**, and it has one inventory of record: `.claude/platform/registry.yaml`. This guide explains the workflow every contributor (human or AI) must follow to add, change, or retire any `.claude` artifact.

**The rule (binding, ARB 2026-07-08):**

```
1. REGISTER   the component/asset in registry.yaml (with full traceability)
2. REVIEW     the registry entry (approve before any implementation)
3. IMPLEMENT  the artifact
4. VERIFY     the implementation matches the registered intent
```

Never the reverse. A `.claude` file that has no registry entry should not exist.

## Where it fits

- **Component:** `platform_registry` (CMP-008), owned by Design & Decision Support.
- **Authority:** ADR-AIP-01 (Baseline v1.0); the five-question rule is ARB ruling R-17; registry-first is the ARB strategic rule recorded in the registry header and in `.claude/plans/AIP-iteration-1-construction.md`.

## Key files

| File | Role |
|---|---|
| `.claude/platform/registry.yaml` | The registry — runtime configuration, machine-readable. Human docs (like this one) are generated *from* it conceptually; the YAML always wins on conflict. |
| `.claude/settings.json` | Composition root (AST-001) — wires hook scripts to lifecycle events. |
| `.claude/plans/AIP-iteration-1-construction.md` | The active construction plan: slices, Platform Cost ledger, iteration-close protocol. |
| `engineering/architecture/adr/ADR-AIP-01…` / `ADR-AIP-02…` | The decisions that authorize all of this (ADR-AIP class, see `docs/adr/README.md`). |

## How the registry is structured

Three levels, deliberately separated (Clean Architecture applied to the platform itself):

```
Component  (CMP-nnn)  — architectural abstraction, versioned, owned by a bounded context
    └── Implementation — the component's concrete realizations
            └── Asset  (AST-nnn) — one concrete file, with adoption state + VERIFY evidence
```

Example from the committed registry: the Verification Engine (CMP-005, version 0.1) is the *abstraction*; `db-safety-check.sh` (AST-007, adopted) and `run-gates.sh` (AST-010, planned) are merely its current implementations. Tomorrow an implementation could be PHP or a GitHub Action — CMP-005 would not change.

**IDs are the stable identity.** Reference `AST-007` in ADRs and reviews, never the file path — paths change, IDs do not.

## The five questions

Every asset entry must carry a `trace:` block answering (R-17):

```yaml
trace:
  capability: CAP-06        # which approved capability owns this?
  context: implementation-guidance   # which bounded context?
  principle: AIP-09         # which architecture principle requires it?
  decision: PD-13           # which Platform Decision governs it?
  adr: ADR-AIP-01           # which ADR authorizes it?
```

If any answer is missing, the artifact must not be created. (CAP/AIP/PD are defined in the Baseline corpus: `engineering/architecture/baseline/Phase-02.5-Certification-Plan.md` and `Phase-02.7-Platform-Decisions.md`.)

## Adoption states

`adopted` (verified, part of the Baseline — the `verified:` block records what was actually read/checked and when) · `planned` (registered ahead of implementation — the registry-first step 1) · `deprecated` (superseded; kept in the registry for the audit trail, per AIP-11: nothing is rewritten, everything is superseded) · `verify` (awaiting evaluation).

Nothing joins the Baseline automatically: reused/legacy files earn `adopted` only through a recorded VERIFY evaluation (ARB amendment 4).

## How to validate

The registry must always parse and satisfy its invariants. Current check (run from repo root; PHP + the project's own `symfony/yaml`):

```bash
php -r "require 'vendor/autoload.php';
  \$r = Symfony\Component\Yaml\Yaml::parseFile('.claude/platform/registry.yaml');
  printf('components=%d assets=%d', count(\$r['components']), count(\$r['assets']));"
```

The full invariant set checked at slice C1 (see session log 2026-07-08): unique CMP/AST ids · every `asset.component` resolves to a component id · five-question completeness · VERIFY evidence on every non-`planned` asset · `runtime_moments` values within `platform.runtime_moment_enum`. A registered fitness-function script will absorb this in a later slice; until then, run the check after any registry change and paste its output as evidence.

## Worked example (real, from slice C3 planning)

`run-gates.sh` was **registered before it existed**:

```yaml
- id: AST-010
  path: .claude/scripts/run-gates.sh
  component: CMP-005
  adoption: planned      # ← registered first; implementation comes in slice C3
  trace: { capability: CAP-07, context: verification-evidence,
           principle: AIP-01, decision: PD-16, adr: ADR-AIP-01 }
```

When C3 implements it, the slice must verify the script does exactly what the entry registered (execute checks → collect evidence → return verdict — nothing more), then flip `adoption: planned → adopted` with the VERIFY evidence.

## Pitfalls

- **Don't put documentation in the registry.** It is runtime configuration; keep it small. Explanations belong here or in the Baseline corpus.
- **Don't optimize the Platform Cost numbers.** The ledger (in the construction plan) is a discussion tool for ER-05, not a KPI — 9 components is not worse than 8 if PB-004 justified the ninth.
- **Don't add "useful" artifacts speculatively.** AIP-14 (Product Primacy, ADR-AIP-02): every addition must be justified by a real PublicDigit feature. When in doubt, DEFER.
- **Don't edit history.** Deprecated/superseded entries stay; adoption states move forward only.
- **Machine-local files are not platform assets.** Anything under `~/` fails AIP-03 (no hidden state) — see AST-008's deprecation.

## Traceability

CMP-008 / AST-009 (`registry.yaml`) · CAP-13 Platform Self-Governance · Design & Decision Support · AIP-03/AIP-12/AIP-14 · PD-13 · ADR-AIP-01 (+ Addendum), ADR-AIP-02 · ARB rulings R-17, R-21, R-23 and the C1 acceptance (2026-07-08) · plan: `.claude/plans/AIP-iteration-1-construction.md`.
