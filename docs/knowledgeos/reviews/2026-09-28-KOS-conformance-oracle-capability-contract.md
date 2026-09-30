# EKS Capability Contract — Conformance Oracle

**Date:** 2026-09-28. **Status:** DRAFT CONTRACT — not implemented, not authorized. Read-only design
artifact, matching this session's own capability-identification work
(`2026-09-28-KOS-evidence-derived-architecture-research.md`) and the externally-proposed
Language-Neutral Contract Architecture (`docs/eks/Yes — **we have worked substantially on`).

> ⛔ This document specifies a capability contract only. No code is written here. Implementation
> requires a separate RED→GREEN slice, per this repo's own TDD discipline, and is not begun by
> this document.

---

## 1 · Why this capability, precisely

This session has manually run the same procedure three separate times as one-off forked research:
extract `L3` canonical facts from a PHP fixture and its Python analogue, diff every field (not
just the final `L5` metric — the discipline this whole Cohesion track was built on, since a wrong
`L3`/`L4` can still coincidentally produce a correct `L5` value), and hand-classify any mismatch.
That is a real, demonstrated, repeated need with no reusable tool behind it. This contract turns
that ad-hoc procedure into a first-class EKS capability.

**Ground-truth check performed before writing this contract** (not assumed): `scripts/observations
/examples/lcom4/expected.json` currently pins only the *final `L5` value* per fixture (e.g.
`{"class": "SelfCallExample", "value": 2}`) — **not** `L3`-level canonical facts. The 10 existing
`.php` fixtures have no paired `.py` file anywhere in that directory; every cross-language check
this session ran used hand-constructed or real-corpus Python constructs, never a stored fixture
pair. **This means the oracle cannot simply consume the existing `expected.json` — it needs a
richer golden schema, defined in §3, and a real, currently-absent set of paired fixtures.**

## 2 · Capability statement

> **Conformance Oracle: given a PHP/Python fixture pair and a golden `L3` fact set, determine
> whether both adapters produce canonically-equivalent facts, and if not, classify why — without
> ever treating either adapter's own output as ground truth.**

## 3 · Input

- A **fixture pair**: `<name>.php` and `<name>.py`, each defining one or more classes exercising
  one semantic construct (matching the existing one-construct-per-fixture convention).
- A **golden `L3` fact set** for that fixture, language-neutral, keyed by class and method —
  **new**, since `expected.json` doesn't carry this today. Proposed shape (canonical, not
  PHP/Python-specific):
  ```json
  {
    "unit": "SelfCallExample",
    "kind": "Class",
    "methods": {
      "a": { "role": "Ordinary", "stateAccess": [], "behaviourReferences": [
        {"target": "b", "qualifier": "Bare", "relation": "SameUnit", "mode": "Invocation",
         "access": "Read", "determinability": "Determinable"}
      ]}
    }
  }
  ```
  This is a genuinely new artifact this capability must define and maintain — not a reuse of
  `expected.json`'s current shape.

## 4 · Output

One of exactly two outcomes per fixture pair, never a third silent state:

- **`PASS`** — both adapters' `L3` facts canonicalize to the golden set, field-by-field.
- **A classified failure**, using the vocabulary already proposed in dialogue this session (traced
  to `docs/eks/Yes — **we have worked substantially on`, itself unverified/unratified — adopted
  here as a working vocabulary for this contract only, not as adopted architecture):

| Class | Meaning |
|---|---|
| `CONTRACT_ERROR` | The golden fact set itself is wrong or self-contradictory |
| `PHP_ADAPTER_ERROR` | PHP's `L3` facts diverge from golden, Python matches |
| `PYTHON_ADAPTER_ERROR` | Python's `L3` facts diverge from golden, PHP matches |
| `FIXTURE_ERROR` | The `.php`/`.py` pair don't actually express the same construct |
| `LANGUAGE_SEMANTIC_GAP` | Both adapters agree with each other but **disagree with the golden
  set for a principled reason** — the construct genuinely has no faithful cross-language
  rendering (this session's own precedent: `qualifierKind` differing between `self::` and bare
  class-name is exactly this class, confirmed correct 3 times, never a bug) |
| `UNKNOWN` | Neither adapter matches golden, neither matches the other — needs human triage |

**No result may be silently swallowed.** A fixture with no golden entry yet is a `CONTRACT_ERROR`
("golden set missing"), never treated as passing by omission — this is the same "absence of
evidence is never PASS" discipline PKS's own capability layer already enforces (`AP-8`, verified
this session).

## 5 · Invariants

- **Never trust one adapter as the oracle.** The golden set is authored independently of both
  `PhpFactExtractor.php` and `extract_facts.py` — this is the single most important invariant,
  directly named as the risk this session's own `L3`-parity work was built to avoid.
- **Field-level comparison, not metric-level.** Every `L3` field (`kind`, `identity`,
  `methodRole`, `stateAccess[].propertyName`/`accessMode`, `behaviourReferences[].*`) is compared;
  a passing `L5` value never substitutes for this.
- **The oracle is a classifier, never a fixer.** It reports; it does not modify adapters, fixtures,
  or the golden set. This matches the kernel-candidate principle already established this session
  (`§13`/`§20` of the evidence-derived research): a kernel-shaped component classifies and gates,
  it never reasons or acts.
- **Deterministic.** Same fixture pair + same golden set → same classification, every run — no
  adapter-version drift silently changes a prior `PASS`.

## 6 · Supported / unsupported / indeterminate cases

- **Supported:** any construct already covered by an existing `L3` fact field (per
  `MethodFacts`/`StateAccess`/`BehaviourReference`/`DeclaredUnit`, including `MethodRole` and
  `IndeterminateBehaviourReference`).
- **Unsupported, explicitly:** anything requiring a new `L3` field not yet justified by real-corpus
  evidence — the oracle does not propose new fields; it reports `CONTRACT_ERROR` if a golden set
  requires one, and stops there (same discipline as `D-4`/`D-5`: characterize, don't build
  speculatively).
- **Indeterminate:** a fixture pair where the construct is real in one language but has no
  meaningful analogue in the other (e.g. PHP traits vs. Python) — these get an explicit
  `not-applicable` golden entry, never a forced match.

## 7 · Provenance

Each run records: fixture pair path, golden-set version/hash, both adapters' git commit, the
classification, and (on failure) the first diverging field. This is deliberately minimal —
matching this session's own governance-engine findings that provenance should be *recorded*, never
*attested* (`INV-ATTR-1`/`INV-ATTR-2`) — the oracle records what happened, it does not certify
correctness beyond its own check.

## 8 · Adapter boundary

The oracle is a **new, small Python script** (`scripts/observations/conformance_oracle.py` or
similar — exact placement TBD at implementation time, not decided here), consuming:
- `PhpFactExtractor.php`'s output via the existing CLI/test harness (read-only)
- `extract_facts.py`'s output directly (same-language, no subprocess boundary issue)
- a new golden-fixture directory (§3)

It does **not** modify either adapter. It is itself a `Kernel`-shaped classifier per §5 — a
one-directional consumer of two producers, never a producer itself.

## 9 · Test strategy

- **RED first:** author the golden schema and 2–3 golden entries for existing fixtures
  (`self-call.php`, `cohesive-class.php`, `constructor-glue.php` — chosen for range: a simple
  case, a cohesive case, and the `MethodRole` case R1 added), run the not-yet-built oracle,
  confirm it fails for the right reason (no implementation yet).
- **GREEN:** implement the oracle; all 3 golden entries `PASS`.
- **Real-corpus validation:** replay this session's own `D-1` real-corpus sites (the 1 PHP site,
  3 Python sites already found and characterized) through the oracle, confirm `PASS` — this is a
  genuine regression test for work already done this session, not new discovery.
- **Negative test:** deliberately introduce a known historical bug (e.g. revert the `R1`
  constructor-matching fix) and confirm the oracle correctly reports `PHP_ADAPTER_ERROR` or
  `PYTHON_ADAPTER_ERROR` — proving the classifier actually discriminates, not just always passing
  (directly avoiding the exact methodological trap the theory-construction programme's own state
  file flagged: *"a discriminator that has never returned `false` has not been shown to
  discriminate"*).

## 10 · What this contract does not decide

- Where the oracle's code lives permanently (Cohesion's own tree vs. a separate observations tool)
  — an implementation-time decision, not a contract question.
- Whether the golden-fixture format should eventually replace or coexist with `expected.json`'s
  current `L5`-only format — a separate, later decision, out of this contract's scope.
- Whether this capability should be registered in PKS's Capability Catalog — a governance
  question, not decided here (and note: Cohesion itself, per PKS's own `U-03` finding verified
  this session, is not yet in that catalog either).

---

**Traceability:** this session's own `D-1`/`L3`-parity work (`2026-09-28-KOS-D1-implementation-
results.md`, `2026-09-28-KOS-L3-parity-spot-check-5-rules.md`, round-2) · the externally-proposed
language-neutrality architecture (`docs/eks/Yes — **we have worked substantially on`) · PKS's own
`AP-8` fail-closed discipline (verified via `PKS-Current-Architecture-Baseline-Stage-2.md`) ·
`INV-ATTR-1`/`INV-ATTR-2` (`EKS-07`) · the evidence-derived Kernel candidate set (`2026-09-28-KOS-
evidence-derived-architecture-research.md`, §6/§13/§20) · `KNOWLEDGEOS-RESEARCH-STATE.md` (the
"discriminator that never returns false" caution, applied here directly to test design).
