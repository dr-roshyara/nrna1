# R4 — `magic_methods`: no architecture change, research branch closed

**Context:** `KOS-PYTHON-RULE-VALIDATION`, Rule 4 · **Date:** 2026-09-27
**Result:** characterization only — 3 new tests, 0 production files touched, 114/114
Cohesion tests green.

---

## R4 hypothesis

Python's runtime-triggered special methods (iterator protocol, context-manager protocol,
attribute interception) introduce no semantic distinction the current L3→L4→L5 contract
needs to represent beyond what it already represents for any ordinarily-named method.

## Evidence

**Synthetic** (this session, real execution against the unmodified adapter, not assumed):
- Iterator protocol (`__iter__`/`__next__`): `__iter__` returns bare `self` (zero facts,
  isolated node); `__next__` and a plain method `f` both read `self.value` (ordinary
  state edge). Graph: `{__iter__}` isolated, `{__next__, f}` connected. **Precision
  correction**: the current extractor produces no `__iter__`→`__next__` edge because it
  only ever represents explicit, source-level, intra-body references — this does NOT
  establish that Python's runtime iteration relationship is semantically absent; it
  establishes that the current contract intentionally does not model invocation
  mechanism, a narrower and more defensible claim (see Theory).
- Context-manager protocol (`__enter__`/`__exit__`): `__exit__` explicitly calls
  `self.cleanup()` (ordinary self-call, already-handled mechanism, included);
  `__enter__` returns bare `self` (isolated). No special "protocol pairing" modeled.
- Both categories: every method reported `MethodRole::Ordinary` — R1's lifecycle
  mechanism correctly ignores them (regression control, not reopened).

**Already-existing evidence, cited, not re-run**: attribute interception
(`__getattr__`/`__getattribute__`/PHP `__get`) was already classified using this exact
decision's text as its explicit grounds
(`2026-09-27-KOS-D1-analytical-definition-decision.md`), with real corpus measurement at
the time. Re-examined for whether R4 changes that interpretation: it does not — R4's
theory is a strict generalization of the same reasoning, not a revision of it.

**Real-corpus evidence** (this session, `app/`, the real PHP application under analysis
here — no Python production corpus exists to measure; stated as a limitation, not
fabricated): non-lifecycle magic-method definitions, grepped directly: `__toString` × 39,
`__invoke` × 1, `__get` × 1, `__call` × 1. None receive, or need, any special handling —
directly confirmed by grepping `Domain`/both adapters for any reference to these names:
none exists anywhere except the R1 lifecycle pair.

**Runtime evidence**: not executed this session (no need — the question is what the
*static* extractor represents, not what Python's interpreter actually calls; the theory
is precisely that the two are allowed to diverge, by design, for this metric).

## L3

No new fact required. Every tested category is fully expressed by the existing
`MethodFacts`/`StateAccess`/`BehaviourReference` vocabulary — the same fields R1 already
extended (`methodRole`) and R2/R3 already found sufficient.

## L4

No graph-construction change. `GraphBuilder` treats every magic method exactly as any
other declared method, correctly, with zero modification.

## L5

No metric consequence beyond what any ordinarily-named method's body would already
produce. Values above (2 for both new fixtures) are exactly what the same body shapes
would yield under any method names.

## Classification: **B / F**

**B** (intentional abstraction) for the underlying principle — this was never an
oversight: the contract only ever modeled explicit, source-level, intra-body references,
never invocation *mechanism*, which is exactly what "no special handling" states and what
the dependency-matrix/D-1 reports already established from the pinned text directly, not
from behavior. **F** (intentionally out of scope) for the runtime-triggered aspect
specifically — Python's implicit `__next__`/`__exit__` calls are categorically outside
what this metric was ever built to see, by the same "class analysed in isolation"
boundary R2 generalized for composition.

## Production changes

**None.** `git status`, scoped to `Domain`/`Infrastructure`: identical to the state after
R3 — only the already-frozen R1 diffs remain.

## Theory

**The contract was never modeling *how or when* a method is invoked — only what a
method's own body explicitly references.** This is why "no special handling beyond
constructor/destructor" is not a list of exemptions to maintain per magic method; it is
the necessary consequence of a contract that only ever had one invocation-mechanism
concept in the first place (explicit, intra-body, source-level reference) and one
lifecycle carve-out (R1). Runtime-triggered dispatch — whichever language, whichever
protocol — was never in the domain this contract measures, so no per-protocol vocabulary
(`IteratorRole`, `ContextManagerRole`, etc.) is needed, and inventing one would encode a
distinction the metric structurally cannot act on.

## Remaining uncertainty

Not tested here, and explicitly out of this slice's scope: whether the R3 receiver-
resolution question (own-class bare-name/alias recognition) extends to `StateAccess` —
e.g. `A.value` (a bare-class-name *attribute read*, not a call) inside `A` itself. This is
a genuinely different question from R4 (it is about receiver resolution, not about
runtime-triggered dispatch) and was correctly not conflated with it here.

## Next experiment (exactly one, not started)

**Does the R3 receiver-resolution gap (adapter recognizes `self`/`cls` receivers, and now
the class's own bare name/alias for calls) extend to plain attribute *reads* via a bare
class name — e.g. `A.value` read from within `A` — or is it confined to the call form
`A.method(...)` this R3 slice fixed?** Minimal falsifying pair: `class A: value = 1; def
f(self): return A.value` vs. the already-working `self.value`. This distinguishes "R3 was
a call-specific fix" from "R3 revealed a general receiver-resolution abstraction" — a
genuinely different architectural conclusion depending on the result, per the standing
concern raised (but not yet tested) about this question.

**No production change justified. Research branch closed.**

**Traceability:** `expected.json` (`magic_methods`), `GraphBuilder.php`,
`MethodFacts.php`, `extract_facts.py` (grepped, confirmed no non-lifecycle magic name
anywhere) · `MagicMethodRuntimeSemanticsExperimentTest.php` (new, 3/3 green) ·
`2026-09-27-KOS-D1-analytical-definition-decision.md` (prior grounding, cited not
re-run) · `2026-09-27-KOS-PYTHON-RULE-VALIDATION-R3-implementation.md` (prior slice).
