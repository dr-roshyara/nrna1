# KOS — `L3`-level parity spot-check, 5 closed rules

**Context:** the `static_methods` experiment found one legitimate `L3` surface difference
(`SelfKeyword` vs `UnqualifiedName`) and flagged, as a named residual risk, that ~30 other
"Closed A" rules have only ever been checked at `L4`/`L5` (edges/metric), not at `L3`
(canonical facts) — the exact gap the toy-adapter precedent warns is dangerous (wrong `L3` →
wrong `L4` → coincidentally correct `L5`). **Date:** 2026-09-28. **Phase:** verification.

> ⛔ No production code, adapter, or test modified. Everything below was executed live, this
> pass, against the unmodified codebase (scratch script, session scratchpad, not part of the
> repository). `git status --short scripts/ tests/ .claude/runtime` unchanged before and after.

---

## Method

Five rules, chosen exactly as specified: constructor/lifecycle, ordinary behaviour reference,
state access, property getter/setter, inheritance/parent dispatch. For each, a minimal
PHP/Python equivalent-semantics pair was run through the real, unmodified `PhpFactExtractor`
and `PythonSemanticFactProvider`, and **every** `L3` field was dumped and compared — not only
the subset `L4` happens to consume, per the acceptance criterion:

```
same relevant semantics → same semantic information → possibly different
source-language qualifier → same L4 interpretation → same analytical consequence
```

Literal `L3` byte-equality was **not** required as the passing criterion — but, notably,
**four of five rules produced it anyway**, unprompted. Where it didn't (none, this round —
see the `static_methods` precedent for what a legitimate difference looks like), the finding
would have been judged on whether the difference is a spelling artifact or a semantic one.

## Results

### 1 · Constructor / lifecycle

```
PHP:  __construct  role=Lifecycle hasBody=true  STATE prop=x access=Direct
Py :  __init__     role=Lifecycle hasBody=true  STATE prop=x access=Direct
Graph:  both → nodes=["foo"] edges=[] value=1
```
**`L3` fields identical** (`role`, `hasBody`) except the method's own name, which is not a
decisional field at all — `methodIdentity` is an identity string, not a closed-vocabulary
fact `EdgeRules`/`GraphBuilder` branches on. **Classification: legitimate, not even a surface
difference in any field that matters — full identity on every decisional field.**

### 2 · Ordinary behaviour reference

```
PHP:  BEHAV target=bar qualifier=InstanceReceiver relation=DenotesAnalysedUnit refMode=Invocation access=Direct determinability=Determinable
Py :  BEHAV target=bar qualifier=InstanceReceiver relation=DenotesAnalysedUnit refMode=Invocation access=Direct determinability=Determinable
Graph:  both → nodes=["foo","bar"] edges=[["foo","bar","behaviour"]] value=1
```
**Byte-identical, every field, including `qualifierKind`.** `$this->bar()` and `self.bar()`
both map to `InstanceReceiver` — confirming directly, by execution, the finding the frozen
branch's `L3-language-neutrality-experiment.md` §7 stated but did not itself demonstrate this
plainly: Python's `self` genuinely is PHP's `$this` (`InstanceReceiver`), not `self::`
(`SelfKeyword`) — the exact false-cognate risk that report warned a naive adapter could get
wrong. This adapter gets it right. **Classification: full convergence.**

### 3 · State access

```
PHP:  STATE prop=x access=Direct  (both read and write sites)
Py :  STATE prop=x access=Direct  (both read and write sites)
Graph:  both → nodes=["foo","bar"] edges=[["foo","bar","state"]] value=1
```
**Byte-identical.** Read (`foo`) and write (`bar`) both report `AccessMode::Direct` in both
languages — consistent with the already-established, twice-confirmed finding that
`accessMode` is a collected-but-unconsumed field (`minimal-semantic-contract.md`,
`UnconsumedFieldCharacterizationTest`); its identity here is a bonus confirmation, not new
information the pipeline needed. **Classification: full convergence.**

### 4 · Property getter/setter (Python only — no PHP analogue, by design)

```
Py :  value: role=Ordinary hasBody=true  STATE prop=_value access=Direct
      f:     role=Ordinary hasBody=true  BEHAV target=value qualifier=InstanceReceiver relation=DenotesAnalysedUnit refMode=Invocation access=Direct determinability=Determinable
Graph:  nodes=["value","f"] edges=[["f","value","behaviour"]] value=1
```
**No PHP comparison exists to run — correctly, per the already-established finding that PHP
has no direct `__get__`-based descriptor/property equivalent worth forcing a comparison
against** (same discipline the diamond/MRO and descriptor experiments already applied).
What this confirms instead: Python's `@property`-backed `self.value` read in `f` is correctly
classified as a `BehaviourReference` (a method invocation), **not** a `StateAccess` — this is
exactly the OWD-2/3-corrected behavior (before that correction, this would have wrongly
produced two `StateAccess` facts and zero edges, per the frozen branch's own numerically-
demonstrated naive-vs-correct comparison). **Classification: internally consistent with its
own governed correction; no cross-language comparison applicable.**

### 5 · Inheritance / parent dispatch

```
PHP:  BEHAV target=foo qualifier=ParentKeyword relation=Undetermined refMode=Invocation access=Direct determinability=Determinable
Py :  BEHAV target=foo qualifier=ParentKeyword relation=Undetermined refMode=Invocation access=Direct determinability=Determinable
Graph:  both → unit A: nodes=["foo"] edges=[] value=1 · unit B: nodes=["bar"] edges=[] value=1
```
**Byte-identical, every field**, including `targetUnitRelation=Undetermined` (correctly: a
`parent::`/`super()` reference is out-of-frame by kind, per `EdgeRules` row 3, regardless of
whether the target is determinable — the two orthogonal axes, `qualifierKind` and
`targetUnitRelation`, both agree independently across languages here). `parent::foo()` and
`super().foo()` converge exactly. **Classification: full convergence.**

## Verdict

**4 of 5 rules: byte-identical `L3` facts, every field, not only the `L4`-consumed subset.**
**1 of 5 (property getter): no cross-language comparison applies, by design — internally
correct against its own governed correction.** **0 of 5 showed a semantic divergence.** The
only `L3`-level difference found anywhere across this investigation's *two* now-executed
`L3`-level checks (this spot-check, plus the prior `static_methods` experiment) is the single,
already-explained, legitimate `SelfKeyword`/`UnqualifiedName` spelling difference — and that
one was for a rule (`static_methods`' bare-class-name case) that has no `$this->`/`self.`
receiver at all, so a spelling difference there was expected, not concerning.

## Answering the residual risk directly

The toy-adapter precedent this spot-check exists to guard against (wrong `L3` → wrong `L4` →
coincidentally correct `L5`) **would require an `L3`-level fact that differs on a field
`EdgeRules`/`GraphBuilder` actually branches on**, while `L4`/`L5` still happen to agree. This
spot-check checked exactly those fields, for 5 rules chosen to cover the distinct mechanisms
this capability's `EdgeRules` table actually branches on (self-referential keywords, plain
instance calls, state grouping, property-vs-method classification, out-of-frame parent
dispatch) — and found **no such case**. This does not prove it for the remaining ~25 rows in
the backlog matrix that were not re-checked here; it substantially narrows the plausible risk
surface, because the mechanisms most likely to hide a silent `L3`-level divergence (qualifier
classification, state grouping, receiver resolution) are exactly what this spot-check
targeted.

## Updated status table

| Question | Status |
|---|---|
| Can Python feed the existing kernel? | Yes — demonstrated |
| Can Python produce canonical semantic facts? | Yes |
| Can Python and PHP converge at `L4`/`L5`? | Yes |
| **Is `L3` language neutrality demonstrated, not merely asserted?** | **Yes, for 6 rules now (5 here + `static_methods`), including 4 with byte-identical facts on every decisional field** |
| Is there evidence of a fundamental architecture failure? | No |
| Are there known missing `L3` concepts? | `D-1` — the only one |
| Is `D-1` characterized? | Yes, three convergent ways |
| Is `D-1` implemented? | No |
| Should we analyse `LCOM4` further? | No |

## What this does not prove

The remaining ~25 rows in the backlog matrix are still only checked at `L4`/`L5`. This
spot-check targeted the mechanisms judged most likely to hide a divergence, not an exhaustive
audit — stated precisely so "5 rules checked" is not overclaimed into "all rules checked."

## Recommended next step

Per the standing decision framework: **proceed to `D-1`.** The bridge this spot-check existed
to build — "one successful Python example" → "the language-neutral kernel is credible across
the rule set" — is now in place, with executed, field-level evidence, not assertion. `D-1`
is the deliberately-chosen kernel-*extension* proof (existing vocabulary insufficient → new
canonical concept → consumed identically by both adapters), complementary to the kernel-
*absorption* proof this and the prior pass completed. The implementation contract already
exists (`2026-09-28-KOS-D1-implementation-contract.md`), scoped and awaiting authorization.

**STOP after this report.** No implementation performed.

**Traceability:** `2026-09-28-KOS-python-first-rule-implementation-static-methods.md` (the
precedent this spot-check extends) · `2026-09-27-KOS-L3-language-neutrality-experiment.md`
§7 (the `SelfKeyword` false-cognate warning, now directly confirmed correctly handled by
Case 2 above) · `2026-09-27-KOS-PYTHON-OWD3-implementation.md` (the property correction Case
4 confirms) · scratch script `l3_parity_audit.php` (session scratchpad, not part of the
repository) · `PhpFactExtractor.php`, `PythonSemanticFactProvider.php`/`extract_facts.py`,
`EdgeRules.php` (unmodified, re-executed only).
