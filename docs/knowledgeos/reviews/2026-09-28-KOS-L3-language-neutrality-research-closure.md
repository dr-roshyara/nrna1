# KOS — `L3` cross-language neutrality: research closure

**Context:** closes the `L3`-level parity investigation at its current evidence level —
consolidates `static_methods`, spot-check round 1, and spot-check round 2. **Date:**
2026-09-28. **Phase:** research-state consolidation, not new characterization.

> ⛔ No production code, adapter, test, `expected.json`, `D-1`, or `D-4`/`D-5` document
> modified. `git status --short scripts/ tests/ .claude/runtime` unchanged. Not committed —
> this document alone, pending separate authorization.

---

## 1 · Research question

Does the existing `L3→L4→L5` canonical kernel represent equivalent PHP and Python semantics
identically enough that neither adapter needs language-specific downstream logic — checked at
the level of raw canonical facts, not only at the level of the final metric (the exact gap a
prior toy-adapter precedent showed is dangerous: wrong `L3` → wrong `L4` → a coincidentally
correct `L5` value)?

## 2 · Evidence from Round 1 (`2026-09-28-KOS-L3-parity-spot-check-5-rules.md`)

Five rules: constructor/lifecycle, ordinary behaviour reference, state access, property
getter (Python-only, no PHP analogue), inheritance/parent dispatch. **4 of 5 byte-identical
on every `L3` field**; the property case had no cross-language comparison to run, by design,
and was internally consistent with its own governed correction.

## 3 · Evidence from Round 2 (`2026-09-28-KOS-L3-parity-spot-check-round-2.md`)

Five more rules: first-class callable reference, nested closure scope pruning, inheritance
with override, inheritance without override (evidence precision), static dispatch reached
from an instance method. **4 of 5 byte-identical**; the fifth (static dispatch) showed a
language-specific surface difference in `qualifierKind` (PHP's `self::`, Python's `self.`)
with every decisional field (`targetUnitRelation`, `determinability`, `referenceMode`) and
all downstream `L4`/`L5` output identical.

## 4 · Static-method evidence (`2026-09-28-KOS-python-first-rule-implementation-static-methods.md`)

The first occurrence of the same pattern: PHP's `self::beta()` vs. Python's bare-class-name
`A.beta()` (a true `@staticmethod` has no `self`/`cls` receiver at all) — `qualifierKind`
differs (`SelfKeyword` vs. `UnqualifiedName`), every decisional field and all `L4`/`L5`
output identical.

## 5 · `D-1` evidence — the kernel-extension case

Separately and complementarily, `D-1` (`$this->$m()`/`getattr(self,name)()`) demonstrated the
opposite capability: when the existing vocabulary is genuinely insufficient (not merely
differently-spelled), the kernel can be extended once, language-neutrally, and consumed
identically by both adapters — implemented, tested (188/188), real-corpus-validated in both
languages, and formally incorporated into `expected.json`. This is not a parity check; it is
evidence the kernel's *evolution* mechanism itself works, which parity-checking alone cannot
show.

## 6 · `D-4`/`D-5` evidence — the corpus-falsification case

A third complementary result: `D-4`/`D-5` (library-dispatch callable) were characterized and
found to have **zero genuine occurrences** in either language's examined real corpus —
closed as "no evidence justifies building it," explicitly distinguished from "can never
occur." This demonstrates the research programme does not manufacture work from a backlog
by default; absence of evidence was itself treated as a valid, actionable finding.

## 7 · Cumulative result

**11 structurally distinct semantic mechanisms checked at the `L3` level, across lifecycle,
ordinary behaviour, state, properties, parent/inheritance (with and without override),
callable references, closure scope, and static dispatch:**

- **9 produced byte-identical `L3` facts**, every field.
- **2 produced legitimate language-specific surface differences with semantic
  convergence** — not semantic divergences. In both cases, `qualifierKind` differs because
  the two languages genuinely spell the same relationship differently (a receiver keyword vs.
  a bare name; `self::` vs. `self.`); every field `EdgeRules` actually branches on
  (`targetUnitRelation`, `determinability`, `referenceMode`, `accessMode`) is identical, and
  `L4`/`L5` output is byte-for-byte the same in every case.
- **Zero semantic divergences observed**, across all 11.

## 8 · What is now considered sufficiently established

**Within the currently characterized KnowledgeOS semantic scope, PHP and Python converge on
the same canonical semantic interpretation across 11 structurally distinct mechanisms, with
zero observed semantic divergence. The remaining language-specific differences observed are
surface-level representation differences with convergent semantic interpretation and
downstream behaviour.**

The recurring "different spelling, same decisional fields, same graph" pattern has now been
independently confirmed **three times**, across three structurally distinct constructs
(static-to-static dispatch, static dispatch from an instance context, and — by the same
underlying mechanism — every qualifier-kind distinction `EdgeRules` was ever designed
around). It is treated as established architectural knowledge about this kernel, not a
finding each new rule must re-derive.

## 9 · What remains outside the demonstrated scope

- Roughly 15–19 rows in the full backlog matrix (magic methods, property/call-form idioms in
  full generality, dynamic attribute interception, diamond/MRO's deeper mechanics) remain
  checked only at `L4`/`L5`, not independently re-verified at `L3` — each already carries its
  own separate characterization evidence from earlier work (R4, OWD-4/7, the frozen adapter
  branch's `B`-classification, the diamond/MRO Gate-A consumption-matrix proof), so this is a
  disclosed scope boundary, not an unexamined gap.
- No third language has ever been tested against this kernel — explicitly named as unknown in
  the frozen adapter branch's own epistemic ledger and unchanged by this closure.
- Real-Python-corpus validation at scale has only ever been performed for the specific
  constructs `D-1`'s implementation targeted, not for the general kernel.
- **This is not exhaustive proof of all possible language features converging** — it is
  sufficient empirical support for the specific mechanisms tested, stated at that strength
  and no further.

## 10 · Explicit stopping rationale

Further exhaustive `L3` parity checking is not currently justified by the evidence. Each of
the last several rounds has found the same result (convergence, or the same well-understood
surface-spelling pattern), which is itself the signal that continuing would trade
diminishing new information for the same conclusion restated on a new construct.
**Future parity work should be triggered by a concrete new semantic requirement, a corpus
finding, or an observed divergence — not by completion of a checklist.**

---

## Research-state transition

The research programme is now moving from **cross-language adapter parity validation** to
**identification and validation of the next substantive KnowledgeOS semantic/research
problem.** This document does not choose that next problem.

**STOP after this document.** No implementation performed. Not committed.

**Traceability:** `2026-09-28-KOS-L3-parity-spot-check-5-rules.md` ·
`2026-09-28-KOS-L3-parity-spot-check-round-2.md` · `2026-09-28-KOS-python-first-rule-
implementation-static-methods.md` · `2026-09-28-KOS-D1-implementation-results.md` ·
`2026-09-28-KOS-D4-characterization-closure.md` · `2026-09-28-KOS-python-semantic-
implementation-backlog.md` (the backlog matrix this closure's §9 references) ·
`2026-09-27-KOS-adapter-research-FROZEN.md` (the third-language unknown, first named there).
