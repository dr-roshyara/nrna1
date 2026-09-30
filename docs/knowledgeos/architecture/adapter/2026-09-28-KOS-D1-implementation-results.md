# KOS — `D-1` implementation results: kernel-extension experiment

**Context:** human-authorized implementation, following the prepared contract
(`2026-09-28-KOS-D1-implementation-contract.md`) and the `Implementation-ready: YES`
readiness check. **Date:** 2026-09-28. **Phase:** RED → GREEN, executed and re-verified.

> This is a real implementation pass — production code was changed. Scope, verified by
> `git status`/`git diff --stat` before and after: **9 files modified, 2 new files, in
> `scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/` and `tests/Unit/Cohesion/`
> only.** No `expected.json` change. No PublicDigit application code touched. No commit
> made — nothing below is committed, per standing instruction.

---

## 1 · Research question

Can KnowledgeOS extend its canonical `L3` semantic vocabulary when a real, observed
construct cannot be represented by the existing vocabulary — while both adapters converge
on the same new canonical concept, and `L4` handles it without any language-specific logic?

## 2 · Existing limitation

`$this->$m()` (PHP) and `getattr(self, name)()` (Python) — a method invocation where the
receiver is known but the method name is a runtime expression, not a literal — produced
**zero facts of any kind** in both adapters before this pass. Confirmed today, this pass,
by reverting the implementation and re-running (RED, §4): not merely read from a report.

## 3 · Minimal `L3` extension

`Domain/IndeterminateBehaviourReference.php` — new, distinct, `final readonly` class with
**zero fields**. Determinability is fixed by construction (this kind exists only for the
not-determinable case), so no field stores it; no target-name field exists, because none is
legal (`INV-L3-5` forbids relaxing `BehaviourReference` itself, independently re-derived in
today's schema-sufficiency pass). Exactly Candidate S1 from that pass — the minimal schema
the prior research already converged on.

## 4 · RED evidence — established by reverting, not merely asserted

`IndeterminateBehaviourReference.php` moved aside and every other implementation file
`git stash`ed, then the new test suite run against the resulting unmodified codebase:

```
1) Error: Class "...IndeterminateBehaviourReference" not found
2) test_php_this_dollar_method_...: excluded == [] (expected 1 entry) — the construct is
   silently invisible, exactly as characterized
3) test_python_getattr_self_name_...: excluded == [] (expected 1 entry) — same, Python side
```

Restored (`git stash pop` + file restored) before proceeding. This RED run is the actual
executed proof the pre-existing gap was real, on this exact codebase, today — not inference
from a prior report.

## 5 · `L4` interpretation

`EdgeRules::verdict()` signature widened to `BehaviourReference|IndeterminateBehaviourReference`,
with one new, unconditional branch placed **first**, before the existing nine rows:

```php
if ($ref instanceof IndeterminateBehaviourReference) {
    return EdgeVerdict::exclude(ExclusionReason::NotDeterminable);
}
```

No `if PHP`/`if Python` branch anywhere — the type-check is on the canonical fact kind, not
on source language, exactly as required. The existing nine rows are byte-unchanged.

## 6 · Python adapter

`extract_facts.py`: new helper `_is_getattr_self_dispatch()` recognises exactly
`getattr(self, <anything>, ...)` used as a call's own callee (`getattr(self, name)()`),
deliberately narrow — a name bound to a variable first, `getattr(cls, ...)`, or a
three-argument form used as a value (not called) are **not** recognised, matching this
file's existing bounded-scope convention throughout. Emits `{"kind": "Indeterminate"}`
(no other fields — nothing legal to serialise). `PythonSemanticFactProvider.php`'s
deserialisation checks for this marker before the ordinary `BehaviourReference`
construction and builds `IndeterminateBehaviourReference` instead.

## 7 · PHP adapter

`PhpFactExtractor.php`: a new branch immediately after the existing `$this->` handling,
matching `$this->$method(...)` — a `T_VARIABLE` where the existing branch requires
`T_STRING` — which previously fell through with no fact produced. Emits
`new IndeterminateBehaviourReference()` directly (no serialisation needed — PHP extraction
is in-process).

## 8 · Cross-language parity

Executed, both languages, identical fixture pair (`$this->$m()` / `getattr(self, name)()`
computing the same target):

```
PHP:    nodes=["caller","target"] edges=[] excluded=[{method:caller,target:null,reason:NotDeterminable}]
Python: nodes=["caller","target"] edges=[] excluded=[{method:caller,target:null,reason:NotDeterminable}]
```

**Byte-identical**, including the exclusion record. The two adapters differ internally
(a token-stream guard vs. an AST call-shape check); the canonical semantic representation
does not — the acceptance criterion this experiment set out to test.

## 9 · Test results

**RED confirmed** (§4). **GREEN, this pass's new test file**
(`D1IndeterminateBehaviourReferenceCorrectionTest.php`, 6 tests): `EdgeRules` verdict on
the bare fact kind; PHP observation; Python observation; cross-language parity; a negative
control (an ordinary determinable call, both languages, unaffected); and a metric-neutrality
test (a dynamic-dispatch site does not accidentally create an edge or change `LCOM4` relative
to having no reference at all). **All 6 pass.**

**One genuine, disclosed regression, understood and repaired, not silently patched over:**
`PythonEvidencePrecisionCorrectionTest.php`'s existing Case D had explicitly asserted the
*old*, invisible behavior as its own characterization ("MUST remain untouched"). This
slice's entire purpose is to change exactly that behavior — so this was not a defect, it was
the intended correction reaching a test that had pinned the prior state. Updated to
`HISTORICAL`/corrected assertions, following this repository's own established convention
(the identical pattern used for OWD-5/10/13's characterization-then-correction test pairs) —
disclosed in the test's own docblock, not silently changed.

## 10 · PHP real-corpus result

Ran the real, unmodified extractor against `app/Console/Commands/DebugVoterSlug.php` — the
one already-known genuine site (`$this->$color(...)`, line 67), the full real file, not a
synthetic excerpt:

```
Unit DebugVoterSlug method handle: 1 IndeterminateBehaviourReference(s)
Unit DebugVoterSlug: LCOM4=1, 1 IndeterminateBehaviourReference exclusion in graph output
Extraction completed with no crash/exception for the full real file.
```

Correctly classified, no crash. **Metric-neutrality for this file specifically was not
independently re-verified by reverting and re-running** (the general argument in §12
below already establishes it must hold: the site produced zero graph contribution before,
by never existing as a fact at all, and produces zero graph contribution now, by always
being excluded — the same zero either way, by construction, not by observation of this one
file) — flagged so this is not overclaimed as a separately-executed check.

## 11 · Python real-corpus result

Ran the real, unmodified `extract_facts.py` (imported directly, not merely tested through
the PHP boundary) against the full 153-file top-level CPython 3.13.2 stdlib corpus this
investigation has used throughout:

```
Files examined: 153
Parse/extraction errors: 0
Files with >=1 occurrence of getattr(self, ...)() (this exact call form): 2
Total occurrences: 3
```

**Spot-checked by direct source inspection, not trusted blindly:** `pdb.py:864`
(`return getattr(self, command)(arg)`) and `pydoc.py:605`/`pydoc.py:1234`
(`return getattr(self, methodname)(x, level)`) — all three genuine, immediately-called
dynamic dispatches. The detector's deliberate narrowness was also confirmed: several other
`getattr(self, ...)` occurrences in the same two files (a bare value read, e.g.
`getattr(self, "currentbp", False)`, or a name bound to `func` before being called
separately) were correctly **not** matched — the pattern this adapter recognises is exactly
the immediately-called form, nothing broader.

## 12 · What this proves

- The existing language-neutral `L3→L4→L5` architecture **can evolve** — a genuinely
  missing canonical concept was added once, consumed identically by both adapters, with
  zero language-specific logic anywhere in `L4`.
- The minimal schema this session's own prior research derived (zero fields, Candidate S1)
  was implementation-sufficient — no additional field was needed during implementation.
- Metric-neutrality holds by construction, not by luck: an excluded reference — of any
  kind, old or new — never reaches the edge set, confirmed directly (the metric-neutrality
  test, §9) and by real-corpus execution (§10).
- The construct is real, not hypothetical, in both languages' real corpora (1 PHP site
  measured previously; 3 genuine Python sites found and spot-checked this pass, across 153
  files, 0 extraction failures).

## 13 · What this does NOT prove

- That `D-4`/`D-5` (library-dispatch callable) work the same way — explicitly out of this
  experiment's scope, still the `P2` backlog item.
- That every possible dynamic-dispatch idiom in either language is now covered — this
  slice recognises exactly `$this->$var(...)` and `getattr(self, ...)()`; a name bound to
  a variable first, `self::$var()`/`static::$var()` (PHP), or any other computed-dispatch
  idiom remains exactly as unrepresented as before (the `self::`/`static::` scope question
  this session's schema-sufficiency pass flagged and deliberately excluded from this slice
  remains open, untouched).
- That this construct is now incorporated into `expected.json` — it is not; that remains
  the separate, still-open governance question (`D-4`/`D-5` bundling, Option A/B).
- That the whole Python semantic space is covered — this pass touched exactly one
  construct, per the standing stopping rule.

## 14 · Remaining semantic backlog

Unchanged from `2026-09-28-KOS-python-semantic-implementation-backlog.md`, with one
narrowing: `D-1` moves from "characterized, not implemented" to **"implemented,
not incorporated into `expected.json`."** No new gap was discovered by this experiment —
the `self::`/`static::` scope question was already known and is still deliberately out of
this slice, not newly found.

## 15 · Recommended next research step

Two independent, non-competing options, not a single recommendation:

1. **`D-4`/`D-5` confirmatory experiment** (`P2`, unblocked, standalone) — the same
   discipline this pass just applied, for the library-dispatch callable case.
2. **The `expected.json` incorporation decision** for `D-1` (and, per the governance
   brief, `D-4`/`D-5` independently or bundled) — now materially easier to make than when
   the brief was drafted, since `D-1` is no longer merely designed but **implemented and
   real-corpus-validated** in both languages, which the brief's Option 1 recommendation
   explicitly anticipated ("implementation... and any resulting evidence... a further
   contract amendment is cheap").

**STOP after this report.** No second rule. No further implementation performed by this
pass beyond the scoped `D-1` slice above.

---

## Final decision gate

1. **Can the existing `L3` vocabulary represent `D-1`?** No — confirmed independently
   (schema-sufficiency pass) and now by implementation: `BehaviourReference` cannot legally
   hold a computed name, by its own registered `INV-L3-5`.
2. **Is `IndeterminateBehaviourReference` the minimal sufficient extension?** Yes — zero
   fields sufficed; nothing was added during implementation beyond the type itself.
3. **Do PHP and Python emit the same canonical semantic concept?** Yes — byte-identical
   `L4` output (§8), confirmed by execution, not assumed.
4. **Does `L4` handle it without language-specific logic?** Yes — one `instanceof` check
   on the canonical type, no source-language branch anywhere.
5. **Do all relevant tests pass?** Yes — 188/188 in the full Cohesion suite (182 baseline +
   6 new), including the disclosed, repaired historical-test update.
6. **Does real-corpus validation support the characterization?** Yes — 1 genuine PHP site,
   3 genuine Python sites (153 files, 0 extraction failures), all spot-checked directly.
7. **Did `D-1` reveal any new canonical gap beyond itself?** No — the one open adjacent
   question (`self::`/`static::` scope) was already known, not newly discovered.
8. **What is the single most valuable next research step?** The `expected.json`
   incorporation decision (§15-2) — the implementation work this decision was blocked on
   is now done.

**Traceability:** `2026-09-28-KOS-D1-implementation-contract.md` ·
`2026-09-28-KOS-CONTRACT-NEUTRALITY-001-D1-implementation-readiness-check.md` ·
`2026-09-28-KOS-CONTRACT-NEUTRALITY-001-D1-schema-sufficiency-pass.md` · files modified
(§ header) · `D1IndeterminateBehaviourReferenceCorrectionTest.php` (new) ·
`PythonEvidencePrecisionCorrectionTest.php` (Case D updated) · real-corpus scripts (session
scratchpad, not part of the repository) · CPython 3.13.2 stdlib corpus, 153 top-level files
· `app/Console/Commands/DebugVoterSlug.php`.
