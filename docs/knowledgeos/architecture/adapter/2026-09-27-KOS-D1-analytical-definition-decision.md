# `D-1` analytical-definition decision — is exclusion the theory, or just the implementation?

**Context:** `KOS-CONTRACT-NEUTRALITY-001` adapter-architecture investigation, continued
**Date:** 2026-09-27 · **Performer (self-declared, not attestable):** `claude-code-session:e8f324f1-...`

> ⛔ Read-only research. No production code, no `expected.json`, no `D-1` implementation.
> This resolves a classification question with pre-existing textual evidence, not by
> inferring intent from what the code currently does.

---

## The logical error being corrected

The prior report's sentence — *"this isn't a new insufficiency; it's the same
already-adjudicated boundary"* — was **not yet earned**. It inferred the theory (Proposition
B: exclusion is theoretically correct) from the implementation (Proposition A: the
implementation already excludes it). Those are different claims. This report resolves B with
evidence that predates and is independent of the implementation's behavior.

## Step 1 — The actual analytical contract, recovered from the pinned decisions themselves

`expected.json._variant_decisions_pinned` has **eight keys**, all dated 2026-08-16, all
attributed "PO/ARB decision" or equivalent standing. Read in full (not partially, as in
prior reports):

| Key | Verbatim |
|---|---|
| `constructors` | "EXCLUDED (`__construct`/`__destruct`) — they touch everything and mask splits" |
| `intra_class_calls` | "...The relationship is determined by the behavioural dependency, NOT by the syntax used to express it... EXCLUDED AS NOT DETERMINABLE: calls whose target cannot be resolved from the class in isolation — dynamic method names, callable arrays, and any runtime-computed target; **a real dependency may exist, but the analysis cannot determine the target reliably.**" |
| `first_class_callables` | "EXCLUDED. ... **They may represent a real dependency, but they are not treated as an invocation relationship for this metric.** A separate metric may be introduced later if method-reference coupling proves valuable." |
| `static_methods` | Included as nodes; isolated component if unconnected — "known, pinned, revisitable" |
| `trait_methods` | "NOT resolved — only methods declared in the class body are analyzed (known limitation)" |
| `inherited_methods` | "NOT included — the class is analyzed in isolation (known limitation)" |
| **`magic_methods`** | **"no special handling beyond constructor/destructor exclusion"** |
| `own_class_name_resolution` | Recognized only as written; no alias/namespace resolution (known limitation) |

**The formal `Edge(m1, m2)` relation, derived from these eight decisions plus `EdgeRules`/`GraphBuilder` (already confirmed by the dependency-matrix report), stated explicitly:**

```
Edge(m1, m2) holds iff:
  m1 has a BehaviourReference naming m2, with referenceMode = Invocation           [excludes first_class_callables]
  AND qualifierKind is unconditionally self-referential (Self/Static/InstanceReceiver)
      OR (qualifierKind names m2 as written AND targetUnitRelation = DenotesAnalysedUnit)  [own_class_name_resolution]
  AND determinability = Determinable                                              [intra_class_calls's exclusion clause]
  AND m2 is declared in THIS unit's own body                                      [inherited_methods, trait_methods]
  AND m1, m2 are not __construct/__destruct                                       [constructors]
  AND no exception exists for magic methods as a category                        [magic_methods]
```

**This is precisely Model 3** (statically determinable, intra-unit, explicit invocation,
evaluated in isolation) — **stated in the contract's own words**, not Model 1 (pure syntax —
explicitly ruled out: "not by the syntax used to express it") and not Model 2 (full runtime
truth — explicitly ruled out, twice, with the phrase "a real dependency may exist" used for
both `D-1` and first-class callables, and *still* excluded).

## Step 2 — `D-1` precedent, re-examined precisely (correcting a conflation)

**`D-1` and the magic-method-interception case rest on *different* grounds, and conflating
them (as the prior report did) understated the strength of the evidence:**

- **`D-1`** (`$this->$name()`): excluded under `intra_class_calls`'s **determinability**
  clause — the target NAME itself is unknown without runtime information (variable value).
- **Magic-method interception** (`__getattr__`/`__getattribute__`/`__get`): excluded under
  the **separate**, **more direct** `magic_methods` decision — "no special handling." The
  target (`__getattr__` itself) is not ambiguous at all; the exclusion isn't about
  determinability — it's a **deliberate scope decision that magic methods receive no special
  semantic weight**, independent of whether their target is determinable.

Both are pre-existing, textual, dated, attributed decisions — **neither is a historical
implementation accident elevated to doctrine.**

## Step 3 — Are hidden dynamic dependencies in scope?

**No — settled by direct textual evidence**, not inference. Both the `D-1` construct and the
magic-method-interception pattern fall under *distinct, explicit* pinned exclusions that
predate and do not depend on how either adapter happens to behave.

## Step 4 — Real corpus frequency (freshly measured, not reused from an earlier session)

| Pattern | Occurrences in `app/` (1,629 PHP files) |
|---|---|
| `$this->$name(...)` (`D-1` proper) | **1** (`DebugVoterSlug.php`) |
| `call_user_func`/`call_user_func_array` | **1** (`Google.php` — already established as *not* an intra-class-call case: delegates to a collaborator object, not `$this`) |
| Variable variables (`$$x`) | **0** |
| `ReflectionMethod`/`ReflectionClass` usage | 11 files — a related but **distinct**, broader category, not conflated with `D-1` here |
| Magic methods actually *defined* (`__get`/`__set`/`__call`/`__callStatic`) | **2** (`Google.php::__call`, `ElectionReadModel.php::__get`) |

**Population is small and bounded.** Not zero — real, live code depends on this — but not
extensive enough to argue the current architecture is being tested by scale, only by kind.

## Step 5 — Decision matrix

| Question | Evidence | Result |
|---|---|---|
| What does current `LCOM4` measure? | Eight pinned decisions + `EdgeRules`/`GraphBuilder` (dependency matrix, prior report) | Statically determinable, intra-unit, explicit invocation (Model 3) |
| Are dynamic dependencies excluded? | `intra_class_calls`, `first_class_callables`, `magic_methods` | Yes, on two distinct explicit grounds |
| Why? | Pinned text, verbatim, dated before any Python work existed | Determinability (`D-1`) / deliberate scope (magic methods) — **not** a retrofitted rationale |
| Theoretically required by the *stated* definition? | The definition itself is stated in the pinned text, not derived from behavior | No — the stated definition **is** the determinability/scope-bounded one |
| Corpus frequency | Fresh measurement, this report | 1 genuine `D-1` site, 2 magic-method definitions, out of 1,629 files |
| Can it materially change results? | Confirmed in the prior experiment: yes, `LCOM4` differs (2 vs. a hypothetical 1) *if* the hidden dependency were modeled | Yes, but excluded by definition, not by oversight |

## Classification: **A — intentional analytical boundary**

Stronger than the prior report's claim, because this is now grounded in the pinned
contract's own pre-existing words, not inferred from watching two independently-built
adapters agree. **Freezing this conclusion** for `D-1` and for magic-method interception
alike, on their respective (distinct) grounds.

## Consequence for the standing `D-1` representation work

The already-adopted vocabulary (`IndeterminateBehaviourReference`, the `D-4` enumerated
dispatch list, `QualifierKind::ExplicitCallableDispatch`) remains exactly what it was:
**representation** work for a semantic exclusion the contract already, explicitly decided —
not new territory this report reopens. Nothing here changes the standing `D-1`
representation proposal/adoption; it only replaces its justification with stronger,
pre-existing evidence.

## What this does not decide
- Whether `D-1`'s adopted vocabulary should be incorporated into `expected.json` — a separate,
  already-tracked PO/ARB question, untouched here.
- The bounded "inheritance without override" binding-precision finding — a genuinely
  different question (PHP's own binding precision on an *already in-scope*, non-magic,
  non-dynamic case), not resolved by this report.
- Reflection-based dynamic invocation (11 files) — flagged, not investigated; a plausible
  future D-1-adjacent category, not conflated with `D-1` here.

**Traceability:** `scripts/observations/examples/lcom4/expected.json`
(`_variant_decisions_pinned`, all eight keys, read in full for this report) ·
`EdgeRules.php`/`GraphBuilder.php` (dependency-matrix report, same directory) ·
`2026-09-27-KOS-L3-L4-L5-dependency-matrix.md` · prior descriptor and dynamic-attribute
reports (same directory) · fresh corpus grep, this session.
