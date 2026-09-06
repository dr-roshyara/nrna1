# Programme status — Zero, Knowledge Algebra, Knowledge Extraction
### Business-level assessment, 2026-09-03 · with two technical corrections

**Assessment supplied by governance. Recorded here with the corrections marked ⚠️.**
**Theory v1.2 unchanged · no v1.3 · kernel NOT SELECTED · nothing adopted.**

---

# 1. Status — agreed

| problem | status | business meaning |
|---|---|---|
| **Zero as a formal concept** | 🟢 **substantially solved** | we can define when information may be removed **without changing the knowledge we contracted to preserve** |
| **Zero as a simple universal rule** | 🔴 **not solved — evidence says it is not that simple** | an item removable alone may matter in combination |
| **Knowledge Algebra** | 🟡 **foundations established, not finished** | the beginnings of a framework for transformation, preservation, elimination, reduction |
| **General knowledge-extraction algebra** | 🟡/🔴 **not proven** | demonstrated on controlled experiments, **not on real-world knowledge** |

**The reframing is the substantive gain:**

```
FROM   "what information is irrelevant?"
TO     "what information can be removed while preserving the knowledge
        we have CONTRACTED to preserve?"
```

# 2. ⚠️ Two corrections to the four-operation summary

The summary listed four operations. **Two of them, as written, reinstate things this programme
spent three experiments excluding.**

## ⚠️ Correction 1 — `Eliminate: D → D − S` is **not** the elimination primitive

> **`E_S(D) = D ∖ S` is invalid in general, and demonstrably invalid inside our own experiment.**

Verified against `KR-REP-REDUCTION-2026-09` at level `R2`, where the numeric field is a within-case
rank:

```
R2              [(1,A) (2,B) (3,C)]
naive  D − S    [(2,B) (3,C)]     ← ranks {2,3}: NOT a valid rank vector
typed  E_S      [(1,B) (2,C)]     ← ranks {1,2}: valid
identical?      False
```

**Set subtraction leaves the survivors carrying ranks that no longer denote their positions.** The
correct primitive is the **typed, per-level** operator:

```
E_S^{(n)} : R_n → R_n        removes, then RE-ESTABLISHES the level's invariants
```

**Why this matters commercially, not just formally:** any implementation that models "remove an item"
as list-subtraction will silently corrupt every representation whose fields are **relative to the
other items present** — ranks, percentages, normalised scores, positions, "top-N" flags. **That is
most useful summary representations.**

## ⚠️ Correction 2 — `Preserve: Π(T(D)) = Π(D)` conflates two distinct conditions

The programme separates them deliberately, and the separation was **measured**, not assumed:

```
ADEQUACY      Ĥ( Q(D) | T(D) ) = 0        the representation MAKES Q RECOVERABLE
                                          — a property of the representation

REALIZATION   O( T(D) ) = Q(D)            the COMMITTED decoder actually recovers it
                                          — a property of an operator

ZERO          Π( T(D) ) = Π( T(E_S(D)) )  original vs REDUCED, both in the representation
                                          space — the comparison is WITHIN one type
```

`Π(T(D)) = Π(D)` compresses all three. **`KR-REP-REDUCTION` §5 showed adequacy and realization can
come apart by 54 points** under an information-equivalent recoding — so collapsing them would discard
the programme's sharpest result.

# 3. The negative results — all eight confirmed against the artifacts

**These are the assets.** Each prevents building the wrong algebra:

| | evidence |
|---|---|
| `Zero` is not an algebraic zero | `KR-ZERO-ALGEBRA` — `L` is not a projection |
| not universally element-wise | `H5`/`H6` both refuted — 131 and 63 witnesses |
| depends on the preservation contract | `H8` refuted — and the true rate is *higher* than published (three contracts were extensionally identical) |
| can depend on interactions | case I `[x,x]`, case J `[+c,−c]` |
| reduction can preserve knowledge while changing representation | `R5` adequate at `Ĥ(Q\|R)=0` |
| **information-equivalent representations behave differently under a fixed decoder** | **54-point argmax swing** |
| sequential reduction has constraints we did not initially recognize | **the DPI — non-monotone adequacy is impossible along a chain** |
| a single scalar "reduction" measure is insufficient | bytes flat at 37.0 while cardinality fell 8× |

# 4. The four-phase roadmap — agreed, with the gate stated

```
Phase 1  Zero            context + transformation + contract + eliminability
Phase 2  Reduction       source → representations → boundary          ← FIRST INSTANCE DONE
Phase 3  Knowledge Algebra   composition · identity · equivalence · reduction ·
                             preservation · interaction · boundary · remainder
Phase 4  Extraction      real documents, with guaranteed preservation
```

> **The gate between 2 and 3 is not "did it work" but "does it work on a carrier we did not design."**
> Everything demonstrated so far uses `r = (value, source, timestamp)`, `Q = (argmax, decile)`, one
> chain, `V = 12`. **Phase 3 should not begin on that carrier alone.**

# 5. What is NOT currently running

**No computation is in progress.** `KR-REP-REDUCTION-2026-09` completed: fidelity tests passed,
positive control passed, results written and reviewed. **The next step is a decision about Phase 3
scope, not a running job.**

# 6. The claim we have earned, and the one we have not

> ✅ **"We have solved enough of the Zero problem to turn it from a philosophical question into an
> experimentally testable mathematical problem."**
>
> ✅ **"We have the first working foundation of a Knowledge Algebra."**
>
> ❌ **"We have a Knowledge Algebra."** The operations exist; **their algebraic properties are not
> established** — that is Phase 3's whole content.
>
> ❌ **"We can extract knowledge from documents with preservation guarantees."** Not attempted.

**Both refusals are load-bearing.** The programme's method has been to state results at the strength
of their evidence and no higher, and the four-phase framing is what protects that.
