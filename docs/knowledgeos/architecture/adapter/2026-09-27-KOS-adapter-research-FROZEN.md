# KOS adapter-architecture research — FROZEN

**Context:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-27
**Performer (self-declared, not attestable):** `claude-code-session:e8f324f1-...`

> ⛔ **This research branch is closed by explicit human instruction, 2026-09-27.** This
> document consolidates the branch's findings and marks its end. It authorizes nothing
> further and does not itself decide the one remaining standing question (`D-1`
> incorporation into `expected.json`), which stays exactly where it was.

---

## What this branch investigated

Whether a language-neutral canonical semantic layer (`L3` → `L4` → `L5`) can receive
independently-implemented adapters for different source languages (PHP, Python) and produce
equivalent analysis where the languages are semantically equivalent, while correctly
preserving genuine differences and explicit uncertainty where they exist.

## What was built (real, tested, in the repository)

- `Application/Ports/SemanticFactProvider` — the port, Application-owned.
- `Infrastructure/Php/PhpSemanticFactProvider` — façade; `PhpFactExtractor` itself unchanged.
- `Infrastructure/Python/{extract_facts.py, PythonSemanticFactProvider.php}` — a real,
  AST-based (not regex), deliberately bounded Python adapter.
- One new method, `AnalyseCohesion::observeFromSource()`.
- **94 tests**, all green, covering the port/façade slice and every semantic experiment below.
- **One production correction**, TDD'd (`G-KOS-CONTRACT-PYTHON-EVIDENCE-PRECISION-CORRECTION`):
  the Python adapter no longer conflates "target not in this unit's frame" with "target
  identity unknown" for inherited-method calls — now reports the same, more precise
  `TargetNotDeclaredHere` evidence PHP already did.

**Nothing else was changed.** `PhpFactExtractor`, `GraphBuilder`, `EdgeRules`, `Lcom4`,
every `Domain` type, `expected.json`, and `deptrac.yaml` are byte-identical to before this
branch began.

## Final evidence ledger

| Dimension | Classification | Status |
|---|---|---|
| `@property` (attribute syntax hiding a method call) | A — semantic convergence | Closed |
| Callable reference vs. invocation | A | Closed |
| `self`/`cls` receiver (class/static dispatch) | A | Closed |
| Parent dispatch, `super()`/`parent::` (single inheritance) | A | Closed |
| Diamond/multiple-inheritance `super()` | Gate A — no `MRO` machinery needed | Closed |
| Descriptors (`__get__`) | B — intentional abstraction, no metric effect | Closed |
| Dynamic attribute interception (`__getattr__`/`__getattribute__`/PHP `__get`) | B — intentional abstraction, metric effect exists but excluded by explicit pinned decision (`magic_methods`) | Closed |
| Inheritance without override — evidence precision | **D — adapter limitation, corrected** | Closed |
| Inheritance with override | A | Closed |
| `L3→L4/L5` consumption contract | Fully test-proven, every field, both directions | Closed |
| Reflection | F — out of scope; zero dynamic-invocation usage found in the real corpus | Closed |
| `D-1` (dynamic dispatch, `$this->$name()`) | **A — intentional analytical boundary**, now grounded in the pinned contract's own pre-existing text, not inferred from behavior | Closed as a research question |

## What remains open, deliberately, outside this branch

- **`D-1` incorporation into `expected.json`** — a separate, already-tracked PO/ARB
  authorization question (`2026-09-27-...-V3-incorporation-decision-brief.md`,
  same commission, different directory). This branch clarified *why* the exclusion is
  correct; it does not decide whether/when to write it into the contract.
- **Real Python-source corpus validation** — everything here used small, hand-constructed
  fixtures. No actual Python codebase has been analyzed at scale. If Python adapter work is
  ever taken further, that is new work, not a continuation of this branch.
- Anything explicitly out of every grant's bound in this branch: multiple inheritance beyond
  the one diamond falsification test, `__set__`/`__delete__` descriptors, full `C3` `MRO`,
  a general reflection engine, `expected.json` incorporation, `deptrac.yaml` extension.

## The central finding, stated once, precisely

**The canonical model was never falsified.** Every apparent divergence resolved to one of:
a pre-existing, explicit, already-adjudicated scope decision (`D-1`, magic methods); a real
but narrow adapter-precision defect with a minimal, evidence-justified fix (inheritance
evidence); or a genuine convergence across independently-built adapters and, in one case,
independently-built language mechanisms (`__get`/`__getattr__`/`__getattribute__`). No new
`L3` field, no `L4`/`L5` change, and no general-purpose language-resolution engine was ever
justified by evidence across roughly a dozen bounded, adversarial experiments.

**Traceability — every report this branch produced, in this directory, chronologically:**
`...-adapter-architecture-status-reconstruction.md` ·
`...-L3-language-neutrality-experiment.md` ·
`...-L3-semantic-neutrality-matrix.md` ·
`...-port-hexagonal-architecture-design-proposal.md` ·
`...-architecture-gate.md` ·
`...-minimal-semantic-contract.md` ·
`...-L3-L4-L5-dependency-matrix.md` ·
`...-D1-analytical-definition-decision.md` ·
`...-inheritance-and-reflection-classification.md` · this document.

**STOPPED, per explicit instruction. No further experiments in this branch.**
