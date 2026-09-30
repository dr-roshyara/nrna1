# KOS — Python-first rule implementation experiment: `static_methods` (R6)

**Context:** human-directed controlled implementation experiment — prove the language-neutral
`L3→L4→L5` architecture can absorb a real Python rule without changing the canonical model.
**Date:** 2026-09-28. **Phase:** verification, not implementation — see §5-§6 for why.

> ⛔ No production code, adapter, or test was modified by this pass. Everything reported
> below was either already in the accepted suite or executed live, this pass, against the
> unmodified codebase. `git status --short scripts/ tests/ .claude/runtime` unchanged.

---

## 1 · Rule selected

`static_methods` (R6) — Python's `@staticmethod`, specifically the harder sub-case: two
*genuine* static methods (no `self`/`cls` parameter at all) where one calls the other via the
bare class name — the exact fixture pair the human's own instruction proposed.

## 2 · Why selected, and a finding that changes the experiment before it starts

The instruction asked me to first inspect whether this rule "has already been characterized
for Python" and to explain if a cleaner candidate existed. Inspection produced a stronger
result than "cleaner candidate": **this exact rule, this exact fixture shape, is already
implemented, already tested, and already green — in the accepted suite, using the real,
unmodified adapter, not a hand-built-fact experiment.**

`tests/Unit/Cohesion/StaticMethodsRuleValidationTest.php::test_case_2_true_static_to_static_
chain_via_bare_class_name_converges` constructs precisely the PHP/Python pair the instruction
proposed (`self::beta()` / `A.beta()`, two true statics) and asserts identical `L4` edges and
identical `L5` value, through `PhpFactExtractor::extract()` and the real, AST-based
`PythonSemanticFactProvider`/`extract_facts.py` (373 lines, genuine `ast` parsing — verified
by line count and by reading its `staticmethod`/`classmethod` handling, of which there is
**none**: Python's method extraction needs no decorator-awareness at all for this rule,
because R3's own-class-name resolution already makes `ClassName.method()` resolve correctly
for any method, static or not — static methods require zero special-casing).

**Re-executed live, this pass, to confirm it is not stale:**
```
vendor/bin/phpunit --filter StaticMethodsRuleValidationTest
→ OK, 2/2, 7 assertions
```

**Consequence for the experiment as scoped:** there is no `RED` state to produce. Writing a
new test that duplicates an already-accepted one, or "implementing" a rule with no gap to
close, would misrepresent the situation rather than honestly answer the strategic question.
This report therefore documents the **existing, executed proof** — including deepening it to
the `L3` level the accepted test does not itself print (§7) — rather than fabricating a
redundant implementation cycle. This is disclosed prominently, not buried, because it changes
what this report can honestly claim to demonstrate.

## 3 · Existing canonical representation

`Decision 13.3`'s stratified rule (`EdgeRules::verdict()`): a reference INCLUDED when its
`qualifierKind` is unconditionally self-referential (`Self`/`Static`/`InstanceReceiver`) **or**
names the target as written **and** `targetUnitRelation = DenotesAnalysedUnit`, **and**
`determinability = Determinable`. `static_methods`' own pinned decision (`expected.json`):
static methods are nodes; connectivity follows the same behavioural-dependency rule as any
other method — no special-cased "static" edge type exists anywhere in `L3`/`L4`.

## 4 · Python AST mapping

None beyond ordinary method extraction. `extract_facts.py` treats a `@staticmethod`-decorated
`FunctionDef` exactly like any other method for `L3` purposes — the decorator is not
inspected at all for this rule (confirmed by grep: the file's only decorator-awareness is for
`@property`, an unrelated rule). The genuine static-to-static chain resolves through the same
own-class-name (`ClassName.method()`) path R3 already validated and implemented.

## 5 · `RED` result

**None produced — none exists.** The construct was already correctly handled before this
pass began; no failing test could be honestly written for it.

## 6 · `GREEN` implementation

**None performed.** No file under `Domain/`, `Infrastructure/`, or `Application/` was
changed. This is the report's central, disclosed finding, not an omission.

## 7 · `L3` comparison — executed this pass, not read from a prior report

Dumped raw `BehaviourReference` facts for both languages, same fixture, via the real,
unmodified extractors (scratch script, session scratchpad, not part of the repository):

```
PHP:    alpha -> target=beta qualifier=SelfKeyword    relation=DenotesAnalysedUnit determinability=Determinable refMode=Invocation access=Direct
Python: alpha -> target=beta qualifier=UnqualifiedName relation=DenotesAnalysedUnit determinability=Determinable refMode=Invocation access=Direct
```

**Not byte-identical — correctly so, and this is the more rigorous finding than identity
would have been.** `qualifierKind` differs because the two languages genuinely spell the
reference differently: PHP's `self::beta()` uses the `self` keyword; Python's true
`@staticmethod` has no `self`/`cls` parameter at all, so its only mechanism is the literal
class name — an `UnqualifiedName`, exactly as R3 already established for own-class-name
calls generally. **Every field that Decision 13.3's rule actually branches on**
(`targetUnitRelation`, `determinability`, `referenceMode`, `accessMode`) **is identical.**
This is the "different qualifier surface form, same relevant semantics" pattern this
investigation has repeatedly found (R1's `MethodRole`, `MR-1` in the frozen branch) — not a
new instance of it, but a direct confirmation that the pattern holds for this specific rule
too, checked rather than assumed.

## 8 · `L4` comparison

From the accepted test, re-executed: both languages produce edge set `[['alpha', 'beta',
'behaviour']]` — identical, byte-for-byte, despite the `L3` qualifier difference in §7. This
is exactly what `EdgeRules::verdict()`'s branching on `targetUnitRelation`/`determinability`
(not on `qualifierKind`'s exact case, for this rule) predicts and requires.

## 9 · `L5` result

Both languages: `LCOM4 = 1`, matching the golden PHP fixture `static-call-chain.php` exactly
(re-confirmed by the accepted test's own assertion against the golden value, not only
cross-language equality — the stronger of the two checks, since two adapters could agree with
each other while both being wrong relative to the golden reference).

## 10 · Real-corpus validation

Not newly run by this pass (no new capability was implemented to validate). Static methods
were already exercised at scale in `OWD-6`/`OWD-9`/`OWD-14` (the 153+22-file CPython
real-corpus passes) and in the frozen branch's `Case G` (class/static dispatch,
confirmatory). No further corpus work is implied by this pass's finding.

## 11 · Regression result

Full `StaticMethodsRuleValidationTest` re-run live, this pass: 2/2 green, unmodified. No
other file touched, so no other regression surface exists to check.

## 12 · Architectural classification

**A — Full architectural success.** Not newly demonstrated by this pass — **already
demonstrated**, by work this session's earlier research (the parity inventory, the backlog
report) had already read but not re-executed to this level of rigor. This pass's addition is
the `L3`-level comparison (§7), which the accepted test itself does not print, and live
re-execution, both of which strengthen the existing "A" classification from "read and cited"
to "independently re-verified, this session, at the level the human's evidence hierarchy
demands" (`L3` equality checked, not only `L4`/`L5`).

## 13 · What this proves

**The strategic question is answered, YES, with executed evidence:** the existing
language-neutral `L3→L4→L5` kernel absorbs a real Python semantic rule — including a rule
(`R3`'s own-class-name resolution) that had to be *corrected* before this specific static-to-
static case worked — **without any core change**, and without the Python adapter needing any
special-case logic for the rule's own decorator syntax at all. The one `L3`-level difference
found (§7) is not a gap; it is the correctly-required consequence of the two languages
genuinely differing in how they spell the same relationship, and the architecture's own
qualifier/relation separation (documented reason `QualifierKind` and `TargetUnitRelation` are
kept as two orthogonal facts, not one fused boolean) is exactly what absorbs that difference
without it reaching `L4`.

## 14 · What this does NOT prove

- That every rule absorbs this cleanly — `D-1` is the one already-known counterexample
  (requires a new `L3` fact kind), explicitly excluded from this experiment by design.
- That Python source at arbitrary scale never breaks this specific rule — `OWD`-series corpus
  work already bears on this generally, but this pass did not re-run a fresh corpus scan
  specifically for `static_methods` (§10).
- That no other rule has an undiscovered `L3` fact-shape difference like §7's — this pass
  checked one rule at the `L3` level; the parity inventory and backlog report checked ~30
  rules only at the `L4`/`L5` level (edges/metric), which is the exact under-checking this
  investigation's own history warns against (a toy adapter once produced a coincidentally
  correct metric from an incorrect intermediate structure). **This is a genuine residual
  risk this pass surfaces, not resolves**: other "Closed A" rows in the backlog matrix have
  not all been re-verified at the `L3` level the way this one now has.

## 15 · Recommended next step

Not a second rule-by-rule port — the instruction's own stopping rule, and this pass agrees
with the reasoning: the architectural question this experiment existed to answer is now
answered with executed, `L3`-level evidence, for one rule chosen specifically because it was
*already* the strongest available candidate. Two honest options, not a recommendation between
them:

1. **Spend a small, bounded pass re-checking `L3`-level equality (not just `L4`/`L5`) for a
   handful of the other "Closed A" rows** in the backlog matrix — closing exactly the
   residual risk named in §14, cheaply, since the mechanism (§7's scratch dump) already
   exists and is reusable.
2. **Move to `D-1`** — the one rule this experiment deliberately excluded, and the one item
   every research thread in this capability now agrees is the actual remaining implementation
   work (per `2026-09-28-KOS-D1-implementation-contract.md`, already drafted, awaiting
   authorization).

**STOP after this report.** No second rule implemented. No `D-1` work performed here.

**Traceability:** `StaticMethodsRuleValidationTest.php` (pre-existing, re-executed) ·
`2026-09-27-KOS-PYTHON-RULE-VALIDATION-R3-*.md` (the correction this rule's harder case
depends on) · `2026-09-28-KOS-python-semantic-parity-inventory.md` · `2026-09-28-KOS-python-
semantic-implementation-backlog.md` · scratch `L3` dump (session scratchpad, not part of the
repository) · `extract_facts.py` (373 lines, read in full for decorator-handling confirmation).
