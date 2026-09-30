# OWD-1 — Open-World Python Corpus Discovery

**Context:** `KOS-PYTHON-RULE-VALIDATION` R-series frozen at R6 · **Date:** 2026-09-27
**Phase:** discovery only. **No production code changed.** Two candidate omissions found
and preserved as characterization tests; neither corrected here.

---

## 1. Corpus definition

- **Python version:** `3.13.2 (main, Jun 18 2026, 16:43:14) [GCC 16.1.1 20260515]`
- **Corpus root:** `/home/nab-raj.roshyara@dg-nexolution.de/.pyenv/versions/3.13.2/lib/python3.13`
  (the locally installed CPython standard library — real, professionally maintained
  Python, not manufactured, not downloaded)
- **File count:** 153 top-level `.py` files (`maxdepth 1` — subpackages like `email/`,
  `xml/`, `unittest/` deliberately excluded from this first pass; scope decision, not an
  oversight)
- **File list, exclusion criteria, full per-file data:** `owd1_census.json` (this
  directory) — reproducible via `owd1_census.py` (this directory), which imports
  `extract_facts.py` directly (no PHP subprocess overhead across 153 files) and performs
  both the AST construct inventory and the real adapter run in one pass.
- Nothing in the corpus or script was modified after the run began.

## 2. Extraction robustness

**Parse failures: 0/153. Adapter exceptions: 0/153.** The unmodified Python adapter runs
cleanly on every file in this corpus — a genuinely positive, not-assumed result (this was
an open question; it could have failed).

## 3. Construct inventory (top entries by file frequency; full table in the JSON)

| Construct | Files | Occurrences |
|---|---|---|
| Import | 144 | 1181 |
| FunctionDef | 139 | 6426 |
| TryExcept | 124 | 1303 |
| ClassDef | 111 | 728 |
| DestructuringAssign | 101 | 956 |
| AugmentedAssign | 96 | 797 |
| ListComp | 67 | 269 |
| With | 66 | 270 |
| Call:getattr | 64 | 367 |
| Call:hasattr | 63 | 290 |
| MultiTargetAssign | 59 | 158 |
| GeneratorExp | 58 | 198 |
| Yield | 40 | 186 |
| Call:super | 37 | 177 |
| Decorator:property | 36 | 252 |
| **NestedFunctionOrLambdaInMethod** | **35** | **99** |
| Lambda | 33 | 135 |
| SlotsUsage(textual) | 29 | 29 |
| WalrusOperator | 25 | 41 |
| MultipleInheritance | 14 | 45 |
| Decorator:staticmethod | 10 | 21 |
| ClassKeywordArgs(metaclass_etc) | 9 | 39 |
| MatchStatement | 6 | 8 |
| `<name>.setter`-style decorators | ~20 occurrences across the table (many 1-file entries, individually small, collectively real) | |

`679` classes, `4483` methods examined in total.

## 4. Semantic representation inventory (selected, high-relevance rows)

| Construct | Represented today? | L3 effect | L4 effect | L5 effect |
|---|---|---|---|---|
| `self.x`/`cls.x` (read/write) | Yes (pre-existing) | `StateAccess` | co-touch edge | as designed |
| `self.x()`/`cls.x()`/`super().x()` | Yes (pre-existing/R-series) | `BehaviourReference` | edge or excluded, per `EdgeRules` | as designed |
| Bare-own-class-name call/state (`A.x()`, `A.x`) | Yes (R3/R5) | as above | as above | as designed |
| **Nested function/lambda inside a method, referencing `self`/`cls`** | **Misattributed**, not simply "unrepresented" | the closure's own reference is folded into the OUTER method's facts | spurious edge/state-touch on the outer method | **can silently change LCOM4** |
| **`@property` getter + `@x.setter`, same name** | Represented, but as **two nodes sharing one identity string** | two `MethodFacts`, identical `methodIdentity` | duplicate node in the node list | LCOM4 computed over an ambiguous node set (coincidentally plausible-looking values observed, not verified correct in general) |
| Comprehensions/generator expressions containing `self.x` | Yes — same `FunctionDef` subtree, no separate scope node at the AST level, unaffected by the closure issue above | `StateAccess`/`BehaviourReference` as normal | as normal | as designed |
| Destructuring/multi-target assignment (`self.a, self.b = ...`) | Yes, confirmed by direct execution | one `StateAccess` per target | as normal | as designed |
| `__slots__` | N/A — a class-body-level statement, never inside a method body | none (out of the per-method walk entirely) | none | none |
| Multiple inheritance / metaclass keyword args | Out of scope, matches R2's generalized "unit analysed in isolation" finding — nothing about *how many* bases or *what* metaclass changes per-method extraction | none needed | none needed | none needed |
| `match`/`case`, walrus, `async`/`await`, `yield`/`yield from`, `with` | Not specially handled; if they contain a `self.x` reference within the SAME method's AST subtree it is still picked up (same reasoning as comprehensions) — not independently verified per-construct in this pass | — | — | — |

## 5. Candidate semantic omissions (observations, not classifications)

### CANDIDATE 1 — nested closure misattribution (HIGH)
Confirmed by direct execution (not assumed from the AST count alone): a method `f` that
itself touches nothing, containing a nested `def inner(): return self.y`, is reported
with a `StateAccess` on `y` that is not its own. `ast.walk(m)` recurses into nested
`FunctionDef`/`AsyncFunctionDef`/`Lambda` subtrees without distinguishing them from the
outer method's own body. **99 methods across 35 files (2.2% of all methods examined)**
contain this shape. Preserved as `OpenWorldCorpusDiscoveryCharacterizationTest
::test_nested_closure_self_reference_is_misattributed_to_the_outer_method`.

### CANDIDATE 2 — property getter/setter duplicate node identity (HIGH)
Confirmed by direct execution: Python's getter/setter idiom (`@property` +
`@x.setter`, same method name) — impossible in PHP, where duplicate method names are a
syntax error — produces two `MethodFacts` entries with the identical `methodIdentity`.
`GraphBuilder`'s node list is a plain array; nothing deduplicates it. Whether the
resulting `LCOM4` is coincidentally reasonable or subtly wrong was not established here —
only that the underlying assumption (method identity is unique within a unit) is
violated by real, common Python code. ~20 `.setter`-style decorator occurrences across
the corpus. Preserved as
`OpenWorldCorpusDiscoveryCharacterizationTest::test_property_getter_and_setter_produce_duplicate_node_identity`.

### Everything else in §4 — LOW priority
No execution-confirmed anomaly found; reasoning from direct code-reading (AST subtree
containment) rather than per-construct empirical testing in this pass. Not claimed to be
exhaustively verified — flagged as lower priority because nothing here suggested a
distinguishable-scope problem the way nested closures do.

## 6. Existing contract coverage

Multiple inheritance, metaclass usage, and "class analysed in isolation" generally are
already covered by R2's generalized finding (composition mechanisms don't change
per-method, per-unit extraction). Comprehensions/generators/`with`/`try` are covered by
the general principle established across R1–R6: only explicit, source-level references
within the SAME AST subtree as the method matter, regardless of surrounding control-flow
syntax.

## 7. Novel constructs (not covered by any R1–R6 finding)

Both HIGH candidates above are novel: neither nested-scope attribution nor
duplicate-identity-by-language-idiom was tested or anticipated by any prior synthetic
fixture in this branch, because neither has a natural PHP analogue that would have
prompted the question (PHP has closures via `function() use (...)`, structurally
different from Python's implicit-capture nested `def`; PHP cannot declare two same-named
methods at all).

## 8. High-information candidates (priority buckets)

- **HIGH:** duplicate node identity (getter/setter idiom) — this is arguably a
  **canonical** question (`GraphBuilder`'s node model assumes unique identity), not
  merely an adapter one, unlike every R1–R6 finding — no adapter-only fix is obviously
  available.
- **HIGH:** nested closure misattribution — likely an **adapter-local** fix (stop
  `ast.walk` from recursing into nested `FunctionDef`/`AsyncFunctionDef`/`Lambda`
  subtrees), but not attempted here.
- **MEDIUM/LOW:** everything else in §4.

## 9. Limitations

- CPython's standard library is one corpus, written by a small, expert group over
  decades, with unusually heavy use of certain idioms (`abc`, `functools`, `enum`
  metaprogramming) — not representative of average application Python.
- No Python *production* corpus exists in this project; this remains true after OWD-1.
- Only top-level files were scanned (153 of a much larger stdlib tree) — a deliberate
  scope decision, not a completeness claim.
- Static extraction cannot establish runtime-only semantics (e.g., whether a
  metaclass actually injects methods at class-creation time) — out of scope by design,
  consistent with every prior R-slice's static/runtime distinction.

## 10. Recommendation — exactly one next experiment, not started

**Investigate the property getter/setter duplicate-node-identity finding**, because it is
the first discovery in this whole investigation that plausibly implicates `GraphBuilder`
itself (a `Domain`/canonical question), not an adapter-only one — and because, unlike
the nested-closure issue, it is unclear whether even a "fix" is well-defined without
first answering a real semantic question: *should a getter and its setter be treated as
one logical unit for cohesion purposes, or two, and if two, how should the graph
represent two distinct nodes that happen to share a name?* This is exactly the kind of
question the R1–R6 discipline (recover meaning, don't assume, classify only after
evidence) should be applied to next — not a syntax-coverage exercise.

---

## Required summary

```
OWD-1 status:            complete
Corpus:                   CPython 3.13.2 stdlib, 153 top-level files
Files processed:          153/153, 0 parse failures, 0 adapter exceptions
Major construct families: imports, functions, exceptions, classes, destructuring/augmented
                          assignment, comprehensions, context managers, dynamic-attribute
                          calls, properties, closures, decorators, multiple inheritance,
                          metaclasses, match statements
Candidate semantic omissions: 2 (HIGH) — nested closure misattribution;
                          getter/setter duplicate node identity
Already-covered constructs: composition/inheritance (R2), control-flow/comprehension
                          containment (general R1-R6 principle)
Novel high-information candidates: both HIGH candidates above
Production changes:       none
Recommended next experiment: property getter/setter node-identity investigation
                          (exactly one; not started)
```

**Traceability:** `owd1_census.py`, `owd1_census.json` (this directory, reproducible) ·
`OpenWorldCorpusDiscoveryCharacterizationTest.php` (new, 2/2 green, full Cohesion suite
136/136) · `extract_facts.py` (read, unmodified) · R1–R6 reports (same directory, prior
findings this discovery builds on and does not duplicate).
