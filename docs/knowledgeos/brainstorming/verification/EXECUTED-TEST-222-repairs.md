---
artifact: EXECUTED-TEST — Step 221/222 repairs
mandate: 20260830_935_prompts §8 — "The new work around Step 222 must now be TESTED rather than merely praised."
date: 2026-08-30
status: **TEST EXECUTED** — evidence class B (executed computational evidence)
authority: verifier session (adversarial, independent)
corrects: spec/STEP-VERIFY-221-222.md §3.2 — my own claim that conditional invariants
  "structurally dissolve four of the five MCS" was TOO GENEROUS. Tested result below.
reproducibility: |
  Two Python programs, run to completion, output transcribed verbatim.
  Deterministic, no randomness, no external data. Scripts in the session scratchpad.
---

# Executed test of the Step 221/222 repairs

**The mandate's criticism is accurate and lands on this programme: I praised §222.12 and §222.32–34 without
testing them. This document tests them. Two of my own prior conclusions are corrected as a result.**

---

## PART 1 — `SemanticIntegrity` (§222.12)

### The definition under test, verbatim from the corpus

```
SI(T,K,C) = 1  iff  CriticalSemantics(K,C) ⊆ Recoverable(T(K))
               or   Loss(T,K,C) is explicitly represented
            0  otherwise                                         (§222.12)

L(T,K,C) = set of context-relevant semantic distinctions lost
SI = 1 if L = ∅ for critical distinctions, or L ⊆ DeclaredLoss   (§222.13)
```

### Implementation (executable, deterministic)

```python
def SI(K, C, T, critical, declared_loss):
    Kout = T(K)
    recoverable = set(Kout)
    L = set(critical) - recoverable        # critical distinctions lost
    if not L:                 return 1, L, "preserved"
    if L <= set(declared_loss): return 1, L, "declared"
    return 0, L, "undeclared"
```

### EXECUTED RESULTS — the eight cases §8 specifies

```
SI=1  L={}                        preserved   1 complete preservation
SI=0  L=Source                    undeclared  2 partial loss, undeclared
SI=1  L=Source                    declared    3 partial loss, declared
SI=0  L=Inference,Observation     undeclared  4 AI summary -> {Conclusion}
SI=1  L={}                        preserved   5 critical = {} (empty)
SI=1  L={}                        preserved   6 contradictory decl
SI=1  L={}                        preserved   7a ambiguous ctxA
SI=0  L=Time                      undeclared  7b ambiguous ctxB
```

**Cases 1–4 behave correctly.** Case 4 reproduces §222.14's own AI-summarisation example and returns
`SI=0`, matching the corpus's stated expectation. **The definition works on the cases it was designed for.**

### Defect 1 — VACUOUS SATISFACTION (case 5)

**`CriticalSemantics(K,C) = ∅` ⟹ `SI = 1` for ANY transformation, including one that destroys everything.**

The definition has **no non-triviality condition on `critical`**. A transform mapping `K → ∅` scores full
semantic integrity provided nothing was declared critical. Since `CriticalSemantics` is supplied by the
declarer, **any transformation can be made to pass by declaring nothing critical.**

This is the same defect class as step-100's vacuous closure theorem (TV-F-045): a universally quantified
condition over a set that may be empty.

### Defect 2 — INCOHERENT DECLARATIONS ARE UNDETECTED (case 6)

Declaring loss of an attribute the transform actually **preserves** returns `SI = 1` with no complaint.
The definition never checks `DeclaredLoss` against what was actually lost. **A declarer may over-declare
freely**, which weakens the audit value of the declaration itself.

### Defect 3 — `SI` IS NOT A PROPERTY OF A TRANSFORMATION (case 7)

**Identical `K`, identical `T`, two contexts ⟹ `SI` flips from 1 to 0.**

This is *correct behaviour* — `SI` is context-relative by construction, and that is the point of the
definition. **But it has an architectural consequence the corpus does not observe:** one may never say
*"T preserves semantic integrity"*. Only *"T preserves SI relative to C"* is meaningful.

**The corpus says the former repeatedly** — §218.31's Semantic Preservation Principle, §219.25, §222.36's
verdict — **all without naming `C`.** Those statements are not well-formed under the definition that
§222.12 supplies.

### Defect 4 — **COMPOSITION FAILS.** The decisive result.

```
T1 : drops Confidence, adds Summary    declares {Confidence}
T2 : drops Source                      declares {Source}
critical = {Confidence, Source}

SI(T1)      = 1   (declared Confidence)
SI(T2)      = 1   (declared Source)
SI(T2 ∘ T1) = 0   L = {Confidence, Source}  -> undeclared
```

**Each stage individually satisfies semantic integrity. The composite does not.**

The composite recovers `SI = 1` only if `DeclaredLoss` composes by union:

```
SI(T2 ∘ T1) with UNION of declarations = 1
```

**The corpus defines no composition rule for `DeclaredLoss`.** §222.12 and §222.13 define `SI` for a single
transformation `T` and say nothing about `T₂ ∘ T₁`.

**Why this matters more than the other three defects:** the corpus's stated motivating case (§218.32,
§219.22) is the AI pipeline `Input → Interpretation → Summary → Recommendation` — **a composition of four
transformations.** `SI` as defined **cannot evaluate the pipeline it was written to govern** without a
composition rule that does not exist.

**And the failure mode is the corpus's own oldest theorem.** `LocalCorrectness ⇏ GlobalCorrectness` is
boxed at step-048 §48.1, re-boxed at step-058 §58.3 (`CorrectContexts ⇏ CorrectComposition`), and again at
steps 091, 208 §208.13 and 211 §211.5 — **five times.** **Step 222's `SI` exhibits exactly this failure and
does not cite any of the five.**

### PART 1 VERDICT

| Property | Result |
|---|---|
| **Well-typed** | **YES** — set inclusion over finite attribute sets. Genuine improvement on §215.10's `∝` |
| **Decidable** | **YES**, given `CriticalSemantics(K,C)` and `Recoverable(T(K))` as finite sets |
| **Falsifiable** | **YES** — cases 2 and 4 return 0. The corpus's claim at §222.12 is correct |
| **Useful** | **PARTIALLY** — correct on its design cases; **vacuously satisfiable** when `critical = ∅` |
| **Stable under composition** | **NO — REFUTED BY EXECUTED COUNTEREXAMPLE** |

**`SI` is a real improvement and a genuine repair of TV-F-083. It is also incomplete in a way that defeats
its own motivating use case.**

---

## PART 2 — Conditional invariants `Iᵢ : Cᵢ ⇒ Rᵢ` (§222.32–34)

**§8 requires, for each MCS: reproduce the conflict · specify the contexts · test mutual exclusivity ·
determine whether contradiction is genuinely avoided · judge refinement vs. relocation into undefined
conditions.** All five executed.

| MCS | uncond. models | cond. models | Contexts mutually exclusive? | **Tested verdict** |
|---|---|---|---|---|
| **1** Unknown semantics | 0 | 1 | **YES** | **REFINEMENT — but conditional on an UNDECLARED projection** |
| **2** Retention | 0 | 1 | **N/A — not two contexts** | **NOT DISSOLVED — survives** |
| **3** Aggregate ownership | 0 | 1 | **NO — and need not be** | **DISSOLVED — but by a DIFFERENT mechanism** |
| **4** Epistemic ordering | 0 | 1 | **UNDECIDABLE** | **RELOCATED, not dissolved** |
| **5** Entropy | 0 | 1 | **YES** | **DISSOLVED by disambiguation** |

### MCS-1 — refinement available, not taken
Contexts `C_verif` (the verification calculus) and `C_display` (the presentation surface) are genuinely
mutually exclusive, and conditionalisation restores satisfiability. **But it is legitimate only if the
projection `π : {T,F,U} → {PASS, DEGRADED, FAIL}` is declared.** The corpus declares none. **The repair
exists and has not been made.**

### MCS-2 — survives, and Step 222 is right to say so
Satisfiability returns **only by asserting `KnowableQ`** — i.e. by flipping a premise, not by scoping a
rule. **This is not two contexts; it is one changed assumption**, and the change is a governance decision
(declaring an enumerable protected-question class) that no step has made. **Step 222's own report that
MCS-2 remains unresolved is confirmed by execution.**

### MCS-3 — dissolved, but NOT by the mechanism Step 222 proposes
`Owns` and `Participates` are **different relations, not different contexts.** They are not mutually
exclusive and need not be. **The conflict was a conflation of two relations, and splitting them dissolves
it without invoking context-scoping at all.** Step 222's conditional-invariant machinery is *not the thing
that does the work here.*

### MCS-4 — relocated into an undefined condition
The chain holds *"on a subdomain where the states are comparable"* — but **the corpus never specifies which
states are comparable**, so mutual exclusivity is **undecidable**. **This is precisely the failure mode §8
warns against: the contradiction moves into an undefined context condition rather than being resolved.**
It cannot be discharged until the partial order is actually specified.

### MCS-5 — dissolved by disambiguation
`H` denotes two different quantities: conditional entropy `H(X ∣ 𝓕_t)` (non-increasing) and the entropy of
the growing state description (increasing). A glyph denotes one per expression, so the contexts are
mutually exclusive. **Genuine dissolution — but the corpus must state which quantity it means, and does not.**

### PART 2 VERDICT — **and a correction to my own record**

**`spec/STEP-VERIFY-221-222.md` §3.2 states that conditional invariants "structurally dissolve four of the
five minimal conflicting subsets." The tested result is less favourable and more precise:**

```
genuinely dissolved by conditionalisation : 2   (MCS-1 conditional on an undeclared projection; MCS-5)
dissolved by a DIFFERENT mechanism        : 1   (MCS-3 — relation-splitting, not context-scoping)
relocated into an undefined condition     : 1   (MCS-4)
survives                                  : 1   (MCS-2)
```

**Conditional invariants are a genuine advance over step-209 §209.32's flat conjunction. They do not
dissolve four of five. Two are dissolved by the mechanism, one by something else, one is merely moved, and
one survives — and every dissolution requires a declaration the corpus has not yet made.**

**This is my second self-correction of the day, and it is the direct product of testing a claim I had
previously only praised.**

---

## PART 3 — What this establishes

**Evidence class B — executed computational evidence.** Both programs ran to completion; output is
transcribed verbatim; results are deterministic and reproducible.

**ESTABLISHED:**
1. `SI` is well-typed, decidable and falsifiable — **TV-F-083 is genuinely repaired.**
2. `SI` is **vacuously satisfiable** when `CriticalSemantics = ∅`.
3. `SI` **does not compose** — refuted by explicit counterexample, and the failure is the corpus's own
   `LocalCorrectness ⇏ GlobalCorrectness`, uncited across five prior boxings.
4. `SI` is a **ternary relation**, so the corpus's unqualified *"T preserves semantic integrity"* statements
   are not well-formed.
5. Conditional invariants dissolve **two** MCS, not four; **MCS-2 survives**; **MCS-4 is relocated, not resolved.**

**NOT ESTABLISHED:** that `SI` is useful at scale; that `Recoverable(T(K))` is computable for real
transformations (it was supplied as a finite set here, which is the favourable case); that any declaration
of `CriticalSemantics` exists anywhere in the corpus for any real artifact.

---

## PART 4 — POSSIBLE REPAIRS (`VERIFIER RECOMMENDS`, evidence class E, **not applied**)

1. **Add a composition rule:** `DeclaredLoss(T₂ ∘ T₁) ⊇ DeclaredLoss(T₁) ∪ DeclaredLoss(T₂)`. Executed test
   confirms this restores `SI = 1` for the counterexample. **One line, and it rescues the pipeline case.**
2. **Add a non-triviality condition** barring `CriticalSemantics(K,C) = ∅`, or require the empty declaration
   to be itself justified — closing the vacuity hole.
3. **Require `DeclaredLoss ⊆ L`** so over-declaration is detected.
4. **Always write `SI(T,K,C)` with `C` explicit**; retire unqualified "preserves semantic integrity" claims.
5. **Declare the projection `π`** for MCS-1; **specify the partial order** for MCS-4; **name the entropy**
   for MCS-5; **declare an enumerable `Q`** for MCS-2.

**None of these is established by the corpus. Item 1 is the highest-leverage: without it, the definition
cannot evaluate the AI pipeline it exists to govern.**
