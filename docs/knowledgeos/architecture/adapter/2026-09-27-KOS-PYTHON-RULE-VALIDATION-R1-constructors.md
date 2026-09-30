# `KOS-PYTHON-RULE-VALIDATION` — Rule 1: constructor/destructor exclusion

**Branch:** `KOS-PYTHON-RULE-VALIDATION` (new, per explicit human direction, 2026-09-27)
**Grant:** `G-KOS-PYTHON-RULE-VALIDATION-R1-CONSTRUCTORS` — characterization and materiality
measurement only; **no correction authorized or made**.

> ⛔ Read-only characterization. Confirms a real, material finding. Proposes nothing beyond
> what's asked; makes no production change.

---

## The rule, defined independently of either language first

Pinned decision (`expected.json`, `constructors`): *"EXCLUDED (`__construct`/`__destruct`) —
they touch everything and mask splits."* Stated as a **general principle** — a lifecycle
method that runs on every instantiation necessarily touches most/all of a class's state,
which would artificially connect otherwise-unrelated methods and understate genuine
cohesion problems. This reasoning has nothing to do with PHP specifically — it applies to
any language with a lifecycle-method concept.

## The leak, confirmed by reading, not assumed

```php
// GraphBuilder.php, Domain layer — the sibling class EdgeRules explicitly documents
// itself as "knows nothing about PHP"; this line does not honor that:
private const EXCLUDED_METHODS = ['__construct', '__destruct'];
```

This is a **literal PHP magic-method name string match**, sitting in the layer this
codebase's own architecture calls language-neutral. Python's constructor is `__init__`
(destructor, rarely used, `__del__`) — different strings entirely.

## Materiality — measured, not assumed

```
PHP:    class A { __construct() sets $this->x,y,z; f() reads x; g() reads y; }
Python: class A: __init__(self) sets self.x,y,z; f() reads x; g() reads y

PHP:    nodes=[f,g]              edges=[]                                    LCOM4=2
Python: nodes=[__init__,f,g]     edges=[[__init__,f,state],[__init__,g,state]] LCOM4=1
```

**A genuine, material metric difference — the first one this whole investigation has found
that isn't already explained by an intentional scope decision (`D-1`, magic methods) or a
narrow adapter-precision bug (the just-frozen inheritance correction).** Without exclusion,
`__init__` becomes a connective hub linking `f` and `g` through shared state they don't
actually share directly — inflating apparent cohesion, exactly the failure mode the pinned
rule exists to prevent, for PHP only.

## Why this is not adapter-fixable, and not the same shape as prior findings

The exclusion decision is made in `GraphBuilder` (`Domain`), not by whichever adapter
extracted the facts. A Python adapter cannot correct this by "translating" `__init__` into
the string `__construct` — that would corrupt the reported method identity used elsewhere
(the audit trail, edge endpoints). The information `GraphBuilder` actually needs — **"is this
method the class's lifecycle constructor/destructor"** — is not currently carried as a *fact*
on `MethodFacts` at all; it's inferred downstream from a name that only one language's
adapter would ever produce.

## Classification: **C — canonical representation insufficiency**

Distinct from every classification in the just-frozen branch. This is not (A) convergence,
not (B) intentional abstraction (nothing pinned decides Python constructors should be
treated differently — the pinned rule's own stated reasoning applies equally), not (D)
adapter limitation (no adapter-side fix exists, by construction), not (F) out of scope (the
rule explicitly, currently, already claims to cover this).

## What a correction would look like, sketched, not built

The smallest fix: `MethodFacts` gains one boolean-shaped fact (e.g. a
`role`/`isLifecycleMethod` concept) that **the adapter** sets based on its own language's
constructor/destructor convention (PHP: name-equals-`__construct`/`__destruct`; Python:
name-equals-`__init__`/`__del__`) at extraction time; `GraphBuilder` checks *that fact*
instead of a hardcoded name list. This would be the first genuinely justified `L3`
extension in this entire investigation — everything else concluded no extension was needed.
**Not implemented here** — this report characterizes and measures; it does not correct.

## Real-corpus note
Not measured in this report (out of the authorized scope — characterization and materiality
via the constructed fixture only). Every PHP class with a non-trivial `__construct` in the
existing 1,629-file corpus is already correctly handled; this finding only bears on a
*future* Python-source analysis, which doesn't yet exist at scale.

**Traceability:** `GraphBuilder.php` (read directly, this report) · pinned decision
`constructors` (`expected.json._variant_decisions_pinned`) · `2026-09-27-KOS-adapter-research-FROZEN.md`
(prior branch, same directory, for contrast — every finding there was A/B/D/F; this is the
first C).

**Next action required: a decision from you, not a default action from me** — authorize the
sketched `MethodFacts` extension (the first real `L3` change this investigation would ever
recommend), or hold it as a documented, bounded limitation for now.
