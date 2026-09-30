# OWD-6 — first full L3→L4→L5 real-corpus validation

**Context:** post-OWD-5, closing a gap every prior OWD report flagged · **Date:** 2026-09-28
**Phase:** validation only. **No production code changed.**

---

## Why this, now

Every LCOM4 computation in this entire investigation — R1 through OWD-5 — was run
against hand-built synthetic fixtures. OWD-1 ran the real CPython corpus through the
*extraction* layer only (`extract_facts.extract()`), never through `GraphBuilder`/
`Lcom4`. After fixing two real, material bugs (OWD-3's node-identity collision,
OWD-5's nested-closure misattribution), the natural next step is to run the *complete*
pipeline — `PythonSemanticFactProvider` → `AnalyseCohesion::observe()` — against real
code for the first time, not just synthetic fixtures modeled on real examples.

## Corpus and method

Same corpus as OWD-1: all 153 top-level `.py` files, CPython 3.13.2 standard library.
For every file: `(new PythonSemanticFactProvider())->extract($source)` then
`AnalyseCohesion::observe($facts)` — the real, current, fully-fixed (R1, R3, R5, OWD-3,
OWD-5) pipeline, unmodified for this run. ~52 seconds wall-clock (dominated by
per-file `python3` subprocess spawn overhead, not computation).

## Result 1 — robustness, end to end

**0 crashes, 153/153 files, 679/679 analysed units.** Every unit reached
`GraphBuilder`/`Lcom4` cleanly. This closes the gap OWD-1 left: extraction robustness
was already confirmed; full-pipeline robustness was not, until now.

## Result 2 — OWD-3's fix genuinely engages on real code

**36 disambiguated (`name#0`/`name#1`) nodes across multiple real classes** — not a
theoretical concern confirmed only by hand-built fixtures. Examples: `csv.DictReader`
(`fieldnames` getter+setter), `ssl.SSLObject`/`SSLSocket` (`context`, `session`), and
most strikingly **`ssl.SSLContext`, which has at least five separate property/setter
pairs** (`options`, `_msg_callback`, `verify_flags`, `verify_mode`, and others) that
would have silently collapsed into fewer components under the pre-OWD-3 bug. This is
concrete, real-world confirmation the fix matters beyond synthetic examples.

## Result 3 — LCOM4 distribution across 679 real classes (never previously measured)

```
0 => 181   1 => 269   2 => 114   3 => 48   4 => 15   5 => 18   6 => 13   7 => 4
8 => 3    10 => 2    11 => 2    13 => 1   16 => 1   17 => 2   18 => 2   19 => 1
20 => 1   27 => 1    65 => 1
```
The overwhelming majority (450/679, ~66%) score 0 or 1 — highly cohesive, as expected
for well-maintained standard-library code. A long tail exists, and every outlier
checked is legitimate, not a symptom of a bug:

| Class | LCOM4 | Why it's legitimate |
|---|---|---|
| `_pydecimal.Context` | 65 | A genuine facade over dozens of independent arithmetic operations (add, multiply, quantize, ...) sharing little state — exactly the shape LCOM4 is designed to flag |
| `pickle._Unpickler` | 27 | The well-known per-opcode dispatch-table pattern (`load_int`, `load_dict`, ...) — many largely-independent handlers |
| `typing.IO`, `numbers.Complex` | 20, 19 | Abstract interfaces — every method is an independent `@abstractmethod` stub touching nothing; `LCOM4 == method count` is the mathematically correct result for a pure interface, not an artifact |

## Classification

**A — confirmatory**, not a new finding. This report validates prior corrections at
scale rather than discovering a new gap. No classification of a defect is being made.

## Production changes

**None.**

## Theoretical consequence

This is the first point in the whole `KOS-PYTHON-RULE-VALIDATION`/OWD investigation
where the metric's output on **genuine, unmodified, real-world code** has been examined
end to end, rather than inferred from synthetic fixtures modeled on real examples. The
result is reassuring in both directions: no crash, no nonsensical value, and every
extreme value traces to a real, explicable structural property of the class being
measured — not to a pipeline defect. Combined with OWD-3/OWD-5's fixes visibly engaging
on real classes (`ssl.SSLContext` specifically), this is meaningful evidence the
corrected pipeline is not just theoretically sound but practically trustworthy on real
Python.

## Remaining limitation, unchanged

Still one corpus (CPython stdlib), still no Python *production* corpus in this project.
This validates the pipeline's behavior on real code; it does not manufacture a
production corpus where none exists.

## Next step

Not automatically started. With both HIGH-priority OWD-1 findings resolved (OWD-2→4
property identity; OWD-5 nested closures) and full-pipeline robustness now confirmed at
scale, the two remaining named-but-deferred gaps are: (a) OWD-4's read/write target
resolution (explicitly frozen, held pending materiality decision), (b) the
`property(fset=...)` call-form idiom (OWD-4, low priority, not pursued) and PHP arrow
functions (OWD-5, low priority, not pursued). None is clearly higher-information than
the others; recommend deciding by your own priority rather than defaulting to one.

**Traceability:** `PythonSemanticFactProvider`, `AnalyseCohesion` (real pipeline, run
unmodified against all 153 files) · OWD-1 census (same corpus, extraction-layer only,
superseded in scope by this full-pipeline run) · `2026-09-27-KOS-PYTHON-OWD3-
implementation.md`, `2026-09-27-KOS-PYTHON-OWD5-implementation.md` (the fixes validated
here at scale).
