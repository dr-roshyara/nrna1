# KOS — `L3`-level parity spot-check, round 2 (5 more rules)

**Context:** continues `2026-09-28-KOS-L3-parity-spot-check-5-rules.md`, closing more of the
residual risk both that report and the `static_methods` experiment explicitly named: most of
the ~26-30-rule backlog has only ever been checked at `L4`/`L5` (edges/metric), not at `L3`
(canonical facts) — the exact gap the toy-adapter precedent warns is dangerous. **Date:**
2026-09-28. **Phase:** verification.

> ⛔ No production code, adapter, or test modified. Executed live, this pass, against the
> unmodified codebase (scratch script, session scratchpad, not part of the repository).
> `git status --short scripts/ tests/ .claude/runtime` unchanged before and after.

---

## Rules chosen, and why

Five mechanisms not yet checked at the `L3` level in either prior spot-check, chosen to cover
distinct `EdgeRules` branches: a callable reference (not invocation), closure-scope pruning,
inheritance with an override, inheritance without one (the corrected evidence-precision case),
and static dispatch reached from an instance-method context.

## Results

### 6 · First-class callable reference (`$this->bar(...)` / bare `self.bar`)

```
PHP:  BEHAV target=bar qualifier=InstanceReceiver relation=DenotesAnalysedUnit refMode=CallableReference determinability=Determinable
Py :  BEHAV target=bar qualifier=InstanceReceiver relation=DenotesAnalysedUnit refMode=CallableReference determinability=Determinable
Graph: both -> excluded=[{method:foo,target:bar,reason:CallableNotInvocation}] value=2
```
**Byte-identical**, every field, including `referenceMode=CallableReference` — the exact axis
that distinguishes "referenced" from "invoked."

### 7 · Nested closure scope pruning

```
PHP:  outer has NO behaviourReferences (the closure's self-reference is pruned)
Py :  outer has NO behaviourReferences (the lambda's self-reference is pruned)
Graph: both -> nodes=["outer","helper"] edges=[] excluded=[] value=2
```
**Byte-identical.** Confirms directly, by execution, that OWD-5's correction (both adapters
prune a nested scope's own `self`/`$this` references from the enclosing method) still holds.

### 8 · Inheritance with override

```
PHP:  C.f -> BEHAV target=helper qualifier=InstanceReceiver relation=DenotesAnalysedUnit determinability=Determinable
Py :  C.f -> BEHAV target=helper qualifier=InstanceReceiver relation=DenotesAnalysedUnit determinability=Determinable
Graph: both -> unit C: edges=[["f","helper","behaviour"]] value=1
```
**Byte-identical.** The overriding `helper` declared in `C` itself is correctly the target in
both languages.

### 9 · Inheritance without override (evidence precision)

```
PHP:  C.f -> BEHAV target=helper qualifier=InstanceReceiver relation=DenotesAnalysedUnit determinability=Determinable
Py :  C.f -> BEHAV target=helper qualifier=InstanceReceiver relation=DenotesAnalysedUnit determinability=Determinable
Graph: both -> unit C: edges=[] excluded=[{method:f,target:helper,reason:TargetNotDeclaredHere}] value=1
```
**Byte-identical, including the exclusion reason.** This is the exact construct the
`PythonEvidencePrecisionCorrectionTest` correction fixed — confirmed here at the raw `L3`
level, not only via the accepted test's `L4` assertions: PHP and Python now produce the
identical `TargetNotDeclaredHere` reason, not `NotDeterminable`.

### 10 · Class/static dispatch reached from an instance method

```
PHP:  instance -> BEHAV target=helper qualifier=SelfKeyword     relation=DenotesAnalysedUnit determinability=Determinable
Py :  instance -> BEHAV target=helper qualifier=InstanceReceiver relation=DenotesAnalysedUnit determinability=Determinable
Graph: both -> edges=[["instance","helper","behaviour"]] value=1
```
**Not byte-identical — the third occurrence of the same, already-understood pattern.**
PHP reaches its static method via `self::helper()` (PHP's own idiom for this); Python via
`self.helper()` (Python's idiom — a staticmethod is legitimately reachable through the
instance). The `qualifierKind` difference is a direct, correct consequence of each language's
own idiomatic spelling — not a divergence. `targetUnitRelation`, `determinability`,
`referenceMode`, `accessMode` are identical, and `L4`/`L5` output is byte-identical
(same edge, same value). This is the same shape of finding as the `static_methods`
experiment's `SelfKeyword`/`UnqualifiedName` case — now confirmed a **third** time, across a
third distinct construct.

## Running tally across both spot-checks + the `static_methods` experiment

**11 rules now checked at the `L3` level** (up from 6): 9 byte-identical on every field; 2
show the identical, well-understood "different idiomatic spelling, same decisional fields"
pattern — confirmed, not new. **Zero semantic divergences found across all 11.**

## What this still does not prove

Roughly 15-19 rows in the full backlog matrix remain checked only at `L4`/`L5`. This round,
like the first, targeted the mechanisms judged most likely to hide a divergence (reference
mode, scope pruning, inheritance resolution, static dispatch) — not an exhaustive sweep.
Stated precisely so this isn't overclaimed into "every rule checked."

## Recommended next step

Given three independent, structurally-distinct confirmations of the same "different spelling,
same semantics" pattern (`self::`/bare-name in `static_methods`; `self::`/`self.` here), this
pattern itself is now well-enough evidenced to treat as **closed, general knowledge about this
architecture** rather than something each new rule needs to re-discover. The remaining
unchecked rows (magic methods, property/call-form idioms, dynamic attribute interception) are
lower-probability candidates for hiding a real divergence, since each already has independent
characterization evidence (R4, OWD-4/7, the frozen branch's `B`-classification) — a further
round would have diminishing return relative to this round and the first.

**STOP after this report.** No implementation performed.

**Traceability:** `2026-09-28-KOS-L3-parity-spot-check-5-rules.md` (round 1) ·
`2026-09-28-KOS-python-first-rule-implementation-static-methods.md` (the first occurrence of
the qualifier-spelling pattern) · `PythonEvidencePrecisionCorrectionTest.php` (the correction
rule 9 re-verifies at `L3`) · scratch script `l3_parity_round2.php` (session scratchpad, not
part of the repository).
