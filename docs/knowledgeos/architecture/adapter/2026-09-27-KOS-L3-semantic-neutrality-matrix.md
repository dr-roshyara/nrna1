# KOS L3 semantic neutrality matrix (research report)

**Context:** `KOS-CONTRACT-NEUTRALITY-001` adapter-architecture investigation, continued
**Date:** 2026-09-27 · **Performer (self-declared, not attestable):** `claude-code-session:e8f324f1-...`

> ⛔ Non-authoritative. Nothing in the repository modified — verified after every run.
> No `expected.json`, no PO/ARB request, no production adapter, no `D-1`/`D-4`/`D-5`
> implementation. Research principle held throughout: the corpus (including this codebase's
> own naming and prior conclusions) is evidence to test, not authority to defend.

---

## A · Executive conclusion

**`H-L3-N` (the canonical L3 representation is language-independent for the semantic
categories tested) survives every test in this matrix.** Ten semantic categories, including
two directly targeting known risk points (dynamic dispatch, callable-not-invoked reference)
and one metamorphic check (identical semantics via different qualifier surface forms),
produced correct, unmodified-pipeline results. **No new canonical-model gap was discovered.**
The one real gap (`D-1`) is the *same*, already-tracked gap found before — this experiment
adds evidence that it is core-model-general, not new evidence that it exists. The three
previously-flagged naming concerns all classify as low-cost vocabulary/mapping issues, not
canonical-model defects.

## B · Evidence matrix

| Case | Category | PHP-side status | Python-semantic result | Representation | Graph | Metric | Verdict |
|---|---|---|---|---|---|---|---|
| A | Behaviour dependency | supported | `f→g` behaviour edge | ✅ | ✅ | LCOM4=1 (expected 1) | **PASS** |
| B | State dependency | supported | `StateAccess`, no edge (1 method) | ✅ | ✅ | LCOM4=1 (expected 1) | **PASS** |
| C | Shared state | supported | `f-g` state edge, `h` isolated | ✅ | ✅ | LCOM4=2 (expected 2) | **PASS** |
| D | Independent methods (control) | supported | no edges | ✅ | ✅ | LCOM4=2 (expected 2) | **PASS** |
| E | Dynamic dispatch | **known gap** (`D-1`) | no legal representation without a sentinel | — | — | — | **CONFIRMED PRE-EXISTING GAP, not new** |
| F | Callable reference (not invoked) | supported | excluded, `CallableNotInvocation` | ✅ | ✅ | LCOM4=2 (expected: no dependency) | **PASS** |
| G | Class-level/static dispatch | supported | `f→g` behaviour edge (via `StaticKeyword`) | ✅ | ✅ | LCOM4=1 (expected 1) | **PASS** |
| H | Inheritance | **out of scope by design** | `DeclaredUnit` has no parent/extends field at all (confirmed by reading the class — 4 fields: kind, identity, declaredName, methods) | n/a | n/a | n/a | **NOT A GAP — consistent with the pinned `inherited_methods` decision; LCOM4 is a per-unit metric by design** |
| I | Attribute access vs. invocation | supported | already distinguished by *type* (`StateAccess` vs `BehaviourReference`), demonstrated by A vs. B | ✅ | ✅ | — | **PASS** (answered by A/B, not a new test) |
| J | MR-1: same semantics, different qualifier surface form (`self.g()` vs `Example.g(self)`) | supported | identical edges, identical LCOM4 across both forms | ✅ | ✅ | LCOM4=1 both | **PASS (metamorphic relation holds)** |

**Coverage count:** 10 categories tested · 8 direct passes · 1 confirmed-and-tracked gap
(not double-counted as new) · 1 confirmed out-of-scope-by-design (not a gap). **0 categories
produced a representation or graph mismatch.** Metamorphic relations attempted: 1 (`MR-1`,
holds); the remaining `MR-2`–`MR-5` were not run — see §G, this is a deliberate scope
decision, not an oversight.

## C · Neutrality findings

**Survived:** direct behaviour dependency, state dependency, shared-state cohesion,
isolation/control, callable-vs-invoked distinction, class/static-level dispatch, attribute-
vs-invocation distinction, and qualifier-surface-form invariance (`MR-1`). Each was tested by
constructing facts by hand and running them through the **real, unmodified**
`GraphBuilder`/`EdgeRules`/`Lcom4` — not by inspecting documentation.

**Falsified:** nothing new. The one candidate falsifier going in (`E`, dynamic dispatch) was
already known and already tracked (`V-3`/`D-1`) before this matrix — this experiment
reclassifies it (confirms *scope*: core-model, not PHP-specific) rather than discovering it.

## D · Canonical model weaknesses, classified

| Item | A: vocabulary | B: adapter-mapping | C: canonical expressiveness | D: architectural limitation |
|---|---|---|---|---|
| `QualifierKind::SelfKeyword` | **✅** — the enum's *behavior* is already correct (confirmed: `StaticKeyword` handles Python's class-level self-reference correctly, Case G); only the English label invites a false-cognate reading for a Python author | — | — | — |
| `UnitKind` | — | **✅** — `ClassUnit` maps cleanly for an ordinary Python class; Python's `Enum`/`Protocol`/ABC subclasses require a real interpretive mapping choice, not a mechanical one; the enum's only current *use* is `UnitEligibility::isAnalysedUnit()` — a gating decision, not cohesion-graph logic | possible future generalization, **not justified yet** — no second adapter exists to be blocked by it | — |
| `FactSet`'s `OQ-2` (one-file scope) | **✅** — the *substance* ("one source file = one identity scope") is already language-general; only the docblock's wording says "PHP source file" | — | — | — |
| `V-3`/`D-1` (dynamic dispatch) | — | — | **✅** — confirmed again here: no legal representation exists for "observed, target computed, no name available" without a new fact kind, for Python exactly as for PHP | — |

**No item classifies as D (architectural limitation).** The strongest open item remains `D-1`,
already fully understood and already has a proposed (not yet implemented) fix.

## E · `D-1` conclusion

**Canonical-model-general, confirmed again, not newly discovered.** Constructing the Python
`getattr(self, name)()` case requires the identical choice already faced for PHP's
`$this->$m()` — an empty-string (or otherwise fake) `targetMethodName`, which `BehaviourReference`
accepts at the type level (PHP won't stop you) but which `D-1`'s own decided prohibition
already rules out. **The already-proposed fix (`IndeterminateBehaviourReference` — no
target-name field, fixed `NotDeterminable`) needs no Python-specific variant**: its
`EdgeRules` consequence (an always-`exclude`, metric-neutral rule, per the prior
representation-boundary report) does not read `qualifierKind`, source language, or anything
else language-specific — it is trivially and identically applicable to both. **This was
reasoned here, not re-executed**, because the wiring test (does the type-check reject the
new kind, does the exclusion-record shape need to change) was already performed and is
language-invariant by construction — re-running it with a Python label would add no new
information (the "efficient falsification" principle this investigation itself set out).

## F · Architecture conclusion

```
             ┌── PHP Adapter (PhpFactExtractor, exists) ──┐
             │                                            │
Source ──────┤                                            ├──→ Canonical L3 ──→ L4 ──→ L5
             │                                            │      (SUPPORTED for
             └─ Python Adapter (does not exist) ──────────┘       every tested category)
```

**Status: SUPPORTED for the canonical-layer half of this architecture, for every semantic
category tested. NOT YET BUILT for the Python-adapter half** (unchanged from the prior
report — no extraction-layer work was in scope here). **Not falsified anywhere in this
matrix.** This is meaningfully stronger evidence than the prior report's single construct
family, but it remains evidence about `L3`-and-above accepting hand-built facts — it is still
not evidence about a real Python *parser* correctly producing those facts from source text at
any scale (the prior report's caught regex bug is the standing reminder of that gap).

**Verdict on the GREEN/YELLOW/RED scale: GREEN**, with one already-known, already-scoped,
not-newly-discovered exception (`D-1`) that has a proposed fix awaiting implementation — this
is not a YELLOW "new extension needed," because the extension was already designed before
this matrix ran; the matrix's job was to check whether it's Python-compatible, and it is.

## G · Remaining unknowns (only the ones that matter)

- Whether Python's *actual* resolution semantics — descriptors, `__getattr__`/`__getattribute__`
  overriding, multiple inheritance/MRO, properties (which look like attribute access but run
  code) — introduce a genuinely new category this matrix didn't construct. **Not tested.**
  This is the single largest remaining gap in coverage, not any of the ten tested categories.
- Whether a real Python tokenizer/AST-based extractor (not a toy regex) would surface
  extraction-level difficulties distinct from representation-level ones — deliberately
  out of this matrix's scope (per the "no real parser" instruction), still unknown.
- `MR-2`–`MR-5` were not executed. `MR-3` (dependency insertion) and `MR-4` (isolated-method
  insertion) are low-information given `C`/`D` already test equivalent structural variation;
  `MR-2` (formatting-only) is not meaningfully different from `MR-1` at the `L3` level (`L3`
  never sees source text at all, so a formatting change literally cannot reach it — this is
  answerable by construction, not worth spending an execution on). This is a deliberate
  stopping decision per §15's own instruction, not an oversight.

## H · Addendum — the `@property` falsification test (2026-09-27, later same day)

Run as directed, before any port/implementation work: `class Example: @property def value(self):
return self._value` / `def f(self): return self.value`.

**Result: no canonical-model gap. But a real, high-stakes adapter-interpretation risk,
demonstrated numerically, not argued.**

| Interpretation | `value`'s own facts | `f`'s reference to `value` | Edges | **LCOM4** |
|---|---|---|---|---|
| **Correct** (adapter recognizes `value` is a property — i.e. a method — and emits a `BehaviourReference`, not state access, for `self.value` in `f`) | `StateAccess('_value')` | `BehaviourReference('value', Invocation, Determinable)` | `f→value` (behaviour) | **1** |
| **Naive** (adapter goes by syntax alone: no parens ⇒ `StateAccess`) | `StateAccess('_value')` | `StateAccess('value')` | none (only one method touches the name `value`) | **2** |

**Both runs used the real, unmodified `GraphBuilder`/`Lcom4` — the model faithfully represents
either interpretation it's given. The failure mode is entirely at the adapter-interpretation
layer**: `self.value` is syntactically indistinguishable from ordinary attribute access, but
semantically (Python's descriptor protocol; `Example.value.__get__(self)` actually runs) it
is a method invocation. **Getting this wrong produces no error and no warning — just a
silently different, wrong metric value.** This is the single most concrete evidence in this
whole investigation that "adapter correctness" is not a minor implementation detail: a naive,
syntax-only adapter (exactly the shape of the toy regex adapters used earlier in this
investigation) would get this specific, common Python idiom wrong by default.

**Classification (§D's A/B/C/D scheme): (A)/(B) — adapter interpretation responsibility.
Not (C) canonical-L3 expressiveness, not (D) architectural.** No model extension is needed;
what's needed is that any real Python adapter must resolve, for every `self.X` reference,
whether `X` names a stored attribute or a property/method — a real, nontrivial static-analysis
responsibility (decorator recognition at minimum; defeated in full generality by dynamic
attribute magic, `__getattr__`, etc. — already flagged as `UNKNOWN` in §G).

**Per the stopping rule this investigation itself set: this does not reveal a new
fundamental contradiction in the canonical model, so semantic-fixture expansion stops here.**
The open question this addendum sharpens is not "does `L3` need to change" (no) but "how does
an adapter reliably make this classification" (a real, now well-specified adapter-design
requirement, not a research unknown).

## H.1 · Next experiment (exactly one, highest expected information gain)

**Construct one `L3` fact set representing a Python `@property`-style computed attribute**
(`self.value` that is *syntactically* an attribute access but *semantically* invokes a
method) and determine whether `StateAccess`/`BehaviourReference`'s clean type-level split
(confirmed clean for the ten cases above) survives this case — where Python's own syntax
already blurs the very distinction the model assumes is unambiguous. This is the single
highest-leverage remaining test: `Enum`/`Protocol`/`UnitKind` mapping questions are labeling
work with no discovered inconsistency; the property case is the first candidate that could
concretely *contradict* the assumption "attribute access and method invocation are always
distinguishable from the syntax/semantics alone" — worth falsifying cheaply before any
adapter work, exactly the priority order requested (dynamic dispatch and callable-reference
already covered; this is the next-highest-risk untested category, not further confirmation of
what already passed).

---

## Epistemic ledger

**`FACT`**: all ten matrix rows' concrete outputs (§B) · `DeclaredUnit` has no
parent/extends field (read directly) · `UnitKind`'s only current consumer is
`UnitEligibility::isAnalysedUnit()` (read directly, `AnalyseCohesion.php`).

**`DERIVED`**: `D-1`'s fix is language-invariant by construction (from the already-established
wiring facts, not re-executed) · `MR-2`'s outcome (formatting cannot reach `L3`, since `L3`
carries no provenance/source text at all — a direct consequence of `INV-4`/`INV-L3-7`,
already established, not re-tested here).

**`HYPOTHESIS`**: that descriptor/property-style computed-attribute semantics will expose a
genuine new category (§H) — this is a prediction for the next experiment, not a conclusion.

**`FALSIFIED`**: none, this round — every constructed test passed. (The prior report's toy-
adapter bug remains the standing falsification example; nothing new was falsified here.)

**`UNKNOWN`**: real-parser extraction difficulty at scale · descriptor/MRO/`__getattr__`
semantics · whether a third language (not tested at all) would break any of the ten
passing categories.

**STOP after this report.** No `LanguageBindingInterface` formalized. No `expected.json`
change. No PO/ARB request. No production Python adapter. `D-1`/`D-4`/`D-5` incorporation
remains exactly where it was — paused, evidence strengthened, decision still with PO/ARB.

**Traceability:** `2026-09-27-KOS-adapter-architecture-status-reconstruction.md` ·
`2026-09-27-KOS-L3-language-neutrality-experiment.md` (both this directory) ·
`2026-09-27-...-V3-representation-boundary-experiment.md` (parent commission directory) ·
source read directly for this report: `DeclaredUnit.php`, `EdgeRules.php` (re-confirmed
`ParentKeyword`/`StaticKeyword` branches), `AnalyseCohesion.php` (`UnitEligibility` usage) ·
scratch experiments (session scratchpad, not part of the repository).
