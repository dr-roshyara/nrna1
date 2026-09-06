# Level-A Falsification — is the kernel capability basis **derived** or **stipulated**?

**Date:** 2026-09-06 · **Analysis only. `𝔐_K` NOT computed · `𝒪_core` NOT frozen · no vocabulary selected · no code.**

---

## 1. Executive finding

$$\boxed{\mathcal K_4 \text{ is STIPULATED. } \mathcal K_9 \text{ is DERIVED — as a reading of ratified invariants. And they are NOT RIVALS: they are at DIFFERENT LEVELS.}}$$

`𝒦₄` = `{HoldState, TransitionLegally, RejectIllegally, Replay}` is **machine-shaped**: what a
*reference kernel* must structurally do. `𝒦₉` = `{C-1 … C-9}` is **domain-shaped**: what the *ratified
canon requires the system to be able to do*, each row citing the ratified element that forces it.

**A third finding is sharper than either.** The `invariant-map`'s own meta-pattern:

> *"everything that survived regime transitions was either **ownership-shaped** (who owns the frame)
> or **non-collapse-shaped** (what must not merge). **Nothing that was merely a definition survived
> unchanged.**"*

> ### `[EXP]` **None of `𝒦₄`'s four capabilities is ownership-shaped or non-collapse-shaped.** `𝒦₄` has **no overlap with the shape of what actually survived** in the theory. That is the strongest available evidence that it is not theory-derived.

---

## 2. Scope and non-goals

**Did:** origin audit of `𝒦₄` · full reconstruction of `𝒦₉` · extraction of theory-level obligations
from the ratified invariant map · basis comparison · `Qualify` robustness · `𝒪_core` root re-audit.

**Did NOT:** compute `𝔐_K` · freeze anything · select a vocabulary · merge the bases · run a broad
definition search. Retrievals were **specific**, to establish existing claims (R1).

---

## 3. Evidence inventory

| artifact | what it supplied | standing |
|---|---|---|
| `readiness/07-MINIMUM-IMPLEMENTABLE` §Method | `𝒦₄`, in full | **the origin** |
| `reviews/synthesis/analysis/OPERATION-CONTRACT-GAP.md` | `C-1…C-9` with forcing elements + the 11×9 property table | **GN-77's source** |
| `readiness/exec/verify_readiness_claims.py` | what is *actually* mechanically verified | **see §3a** |
| `reviews/synthesis/model/invariant-map.md` | ratified invariants and their classes | theory-level obligations |
| `readiness/03-OPERATION-TRANSFORMATION-READINESS` §7 | GN-84 four statements; Step 277's necessity criterion | operation layer |

### 3a. ⚠️ CORRECTION AGAINST MY OWN PRIOR CLAIM

I wrote that `𝒦₉` was *"independently re-verified by `exec/verify_readiness_claims.py`."*
**That is FALSE.** The script's own docstring lists what it checks:

```
C1  vocabulary disjointness (GN-75)
C2  0 operation signatures in the governed surface (GN-77)
C3  "postcondition" occurs 0 times (GN-77)
C4  exactly 1 "precondition" hit, describing Authorization (GN-77)
```

> `[NEG]` **It verifies the *"0 operations defined"* half of GN-77. It does NOT verify the *"9 capabilities required"* half.** My gap record leaned on that verification when granting `𝒦₉` "better standing". **That leg of the argument is withdrawn.** `𝒦₉`'s standing rests on §5's forcing citations instead — which turn out to be stronger anyway.

---

## 4. Origin audit of `𝒦₄`

**Verified, not repeated.** `07-MINIMUM-IMPLEMENTABLE`, opening **Method** paragraph, complete:

> *"A reference kernel **must be able to** (1) hold a knowledge state, (2) transition it legally,
> (3) reject illegally, (4) replay from history. Take the transitive dependency closure of those
> four capabilities over the construct graph. Everything outside the closure is excluded by a
> dependency fact, not by judgement."*

| question | answer |
|---|---|
| Is `𝒦₄` derived? | **No** — no derivation appears |
| Declared? | **Not as a governance act** |
| Stipulated as a methodological starting point? | **YES** — it is literally the Method paragraph |
| Earlier Theory v1.2 statement it follows from? | **None found in the document or its citations** |
| Any artifact independently justifying the four as necessary? | **None found** |
| …as sufficient? | **None found** |
| Does *"must be able to"* mean semantic necessity or implementation scope? | **Implementation scope** — the sentence defines what *"a reference kernel"* is taken to be, for the purpose of computing a closure |

$$\boxed{Status(\mathcal K_4) = \textbf{STIPULATED}}$$

**The document is internally honest.** Its guarantee is scoped to *"the closure **of those four**"* —
it never claims the four themselves are derived.

---

## 5. `𝒦₉` reconstruction — fully recoverable

`OPERATION-CONTRACT-GAP.md` states the governing distinction **before** the table:

> **"The ratified theory REQUIRES this capability" ≠ "The canonical theory HAS DEFINED this operation."**
> *"The first is a **reading of ratified invariants**. The second would be an act of authorship.
> **Every row below is of the first kind.**"*

| # | capability | forced by (ratified) | Theory support | standing |
|---|---|---|---|---|
| **C-1** | move an item one rung up the ladder | ladder + **I-12** (covering relation; skipping excluded) | ratified invariant | **Derived** (reading) |
| **C-2** | attach `Committed` to an Accepted item **by an authority act** | **A6**, **I-4** | ratified | **Derived** — *and canon fixes its authority requirement* |
| **C-3** | make a Determination (`Supported → Accepted` under AcceptancePolicy) | v0.1 §1 concept row | ratified | **Derived** |
| **C-4** | change an in-force policy version through a governed decision | **I-11**, §3 stratification (R-1) | ratified | **Derived** · GC-1 open |
| **C-5** | compose evidence so duplicates do not amplify and corroboration does | **I-5, I-6** — *the two **TESTED** invariants* | ratified **and tested** | **Derived, strongest row** |
| **C-6** | compute the gap between state and requirement | `Zero(K,EC)`, `EC = η(G, IdealState)` (R-2) | ratified signature | **Derived** |
| **C-7** | **turn an observation into evidence** | Source/Semantic observation split; Evidence concept row | ratified | **Derived** — *"the qualification predicate is undefined in canon"* |
| **C-8** | evaluate a decision contract for admissibility | DC 6-tuple | ratified | **Derived** — *no ratified conjunction over exactly those six exists* |
| **C-9** | act, and observe the result | the action loop; the decision interlock | ratified | **Derived** · OQ-4 open |

$$\text{99 cells } (9 \times 11): \quad \textbf{2 fully fixed} \cdot \textbf{9 partial} \cdot \textbf{88 empty · Ratification status: NONE for all nine}$$

### What GN-77 **is**

`[EXP]` **A reading of ratified invariants** — a *contract-gap statement*, not a mathematical
necessity proof and not a governance declaration. `[NEG]` **"Canonically REQUIRED" must NOT be
upgraded to "mathematically necessary."** The document forbids exactly that upgrade in its own
opening.

$$\boxed{Status(\mathcal K_9) = \textbf{DERIVED (reading of ratified invariants) · sufficiency NEVER CLAIMED · minimality NEVER CLAIMED}}$$

---

## 6. Theory-level obligations

From `invariant-map.md`, classified by the map itself:

| obligation | exact evidence | class | kernel relevance |
|---|---|---|---|
| **Knower owns the frame** | R3 → Q18 → R5 (025h authorization semantics) | **INVARIANT (evidenced)** — *"the only semantic property with an unbroken chain across three regimes"* | **ownership** |
| **proposal ≠ decision** | Q17; 025g/025h | **INVARIANT (evidenced)** | **non-collapse** |
| **extraction ≠ determination** | `20260825-234405` | **invariant-CANDIDATE, single-source** | **non-collapse** |
| **duplicate-invariance of evidence** | EXP-01 property matrix | **INVARIANT, TESTED** | non-collapse |
| dependency-before-aggregation | EXP-01 | invariant-candidate, computationally tested | — |
| Zero's four-way non-satisfaction typology | 025d | invariant-candidate | non-collapse |
| `Zero(K,G,EC)` signature | 025d | **definition**, not invariant | — |
| **8-primitive kernel membership** | 049/050 | ⚠️ **IMPLEMENTATION CHOICE** — *"reduction outcome, **not a proven minimum**"* | see §18a |

### The meta-pattern — the load-bearing finding

> *"everything that survived regime transitions was either **ownership-shaped** or
> **non-collapse-shaped**. **Nothing that was merely a definition survived unchanged.**"*

---

## 7. Candidate theory-derived basis `𝒦_T`

$$\boxed{\mathcal K_T \textbf{ CANNOT BE CONSTRUCTED from the evidence retrieved. Its SHAPE can.}}$$

The invariant map yields **obligations**, not a **capability decomposition**. Converting one into
the other is precisely the act R5 forbids doing by stipulation. **What can be stated:**

$$\mathcal K_T \text{ would be OWNERSHIP-shaped and NON-COLLAPSE-shaped, not machine-shaped.}$$

**Recorded as an evidence limitation (R3), not filled by invention.** Cardinality: **unknown**.

---

## 8. Capability-by-capability analysis of `𝒦₄`

### 8.1 `HoldState`
No `C-` row corresponds. **No ratified element forces it as a *capability*** — it is the existence of
`K_t`, an ontological fact. `[EXP]` **Classification: consequence of the ontology, not an independent
semantic capability.** Nothing retrieved shows behaviour required beyond the state object existing.

### 8.2 `TransitionLegally`
Partially corresponds to **C-1** (ladder move, forced by I-12) and **C-3** (determination). But
`𝒦₄` states it as *one general* capability where the canon forces **specific, separately-cited
transitions**. `[EXP]` **Classification: a COARSENING of at least two distinct canon-forced
capabilities.** Whether it is also distinct from *"a transition function exists"* is **not
determinable** from what was retrieved — `δ`'s signature is not ratified (0 postconditions in canon).

### 8.3 `RejectIllegally`
**No `C-` row.** The nearest ratified element is **I-12** (covering relation; *skipping excluded*),
which is stated as a **constraint on legal transitions**, not as a capability to reject. And the
operations lane classifies `Reject` as **CONTRADICTORY** — *a ratified-required state reachable only
by an operation that breaches two ratified rules.*

`[EXP]` **Classification: on the retrieved evidence it reads as a SAFETY PROPERTY of the transition
system** — `Invalid(o,K,Γ) ⇒ δ(K,o,Γ) = ⊥` — **not a capability.** ⚠️ **But this is not settled**: the
`Reject ↔ I-12 ↔ Article 8` contradiction is unresolved, and until it is, whether rejection is an
operation, an outcome, or a property **cannot be determined**. **Recorded as undetermined.**

### 8.4 `Replay`
> ### ⭐ **In GN-77, "Replay semantics" is one of the ELEVEN PROPERTIES applied to each capability — it is NOT a capability.**

`[EXP]` **The two bases disagree about `Replay`'s TYPE**: `𝒦₄` treats as a *capability* what `𝒦₉`
treats as a *per-operation property*. **This is a genuine structural disagreement, not a naming
difference.**

Whether `History + Transitions + InitialState + Inputs + RuleVersions + Context ⇒ Replay` **cannot be
tested here** — the corpus records that deterministic replay requires **operation versioning**
(`G-18`, absent) and that **refusals are unrecordable** (`G-17`), so at least two required conditions
are known **not** to hold. `[EXP]` **Replay is not currently derivable — because its preconditions
are absent, which is a different fact from its being primitive.**

⚠️ **Replayability ≠ Reproducibility ≠ Auditability** — kept distinct; the corpus does not
consistently distinguish them, and this analysis does not resolve that.

---

## 9. `𝒦₄` vs `𝒦₉` correspondence

| `𝒦₄` | GN-77 rows | same obligation? | relationship |
|---|---|---|---|
| `HoldState` | **none** | no | **Orthogonal** — ontological, not capability |
| `TransitionLegally` | C-1, C-3 (partial) | no | **Coarser abstraction** over ≥2 canon-forced capabilities |
| `RejectIllegally` | **none** (I-12 constrains) | no | **Incomparable** — property vs capability; undetermined |
| `Replay` | **a PROPERTY row, not a capability** | **no** | **Contradictory on TYPE** |
| — | **C-2, C-4, C-5, C-6, C-7, C-8, C-9** | — | **7 of 9 have NO counterpart in `𝒦₄`** |

$$\boxed{\text{Relationship}(\mathcal K_4, \mathcal K_9) = \textbf{INCOMPARABLE — different levels, with one TYPE contradiction (Replay)}}$$

**They are not rival answers to one question.** `𝒦₉` reads *"what does the ratified canon oblige the
system to do?"*; `𝒦₄` asserts *"what must a reference kernel structurally do?"* **Neither answers the
other's question.** `[NEG]` **My earlier framing of them as rivals with unequal standing is withdrawn.**

## 10–11. `𝒦₄` vs `𝒦_T` · `𝒦₉` vs `𝒦_T`

**Not computable.** `𝒦_T` was not constructible (§7). What can be said: `𝒦₄` is **shape-disjoint**
from the surviving-invariant pattern; `𝒦₉` is **shape-aligned** — C-2 and C-4 are ownership-shaped
(authority acts, governed decisions), C-5 and C-7 are non-collapse-shaped (duplicates must not
amplify; observation must not collapse into evidence).

---

## 12–14. Necessity · Sufficiency · Primitive-vs-derived

| basis | necessity | sufficiency | primitive/derived |
|---|---|---|---|
| `𝒦₄` | **untested** — no artifact tests removal of any of the four | **untested; never claimed** | `HoldState` derived (ontology) · `Replay` type-disputed · `RejectIllegally` undetermined · `TransitionLegally` composite |
| `𝒦₉` | **each row cites a forcing ratified element** — that is necessity *relative to the ratified canon* | **NEVER CLAIMED**, and the 88 empty cells make it unclaimable | all nine are **readings**, none defined; primitiveness not addressed |
| `𝒦_T` | — | — | — |

`[NEG]` **Neither basis has a sufficiency result. Neither has an executed necessity test.** Step 277's
necessity criterion is stated over `R_mandatory` = `ℐ` and *"has never been run by anyone."*

---

## 15. Basis equivalence

$$\mathcal K_1 \simeq_{\mathrm{sem}} \mathcal K_2 \iff \text{each can represent the obligations the other represents, under explicit mappings}$$

`[NEG]` **This relation cannot be established from the corpus.** No mapping between capability
vocabularies exists anywhere retrieved; §9 shows 7 of 9 rows have no `𝒦₄` counterpart at all.

$$\boxed{\text{Classification: INCOMPARABLE (with one contradictory TYPE assignment)}}$$

---

## 16. Recursion test

`Cap(𝒪; 𝒦₁) = Cap(𝒪; 𝒦₂)` **cannot be evaluated** — `Cap` is undefined for either basis, and 88 of
99 contract cells are empty.

> `[EXP]` **What the evidence DOES show: capability decomposition is representation-relative.** The
> same underlying system is decomposed into 4 machine-shaped items by one lane and 9 domain-shaped
> items by another, **with `Replay` appearing as a capability in one and a property in the other.**
> **Capability minimality is therefore not unique at the vocabulary level** — the same phenomenon the
> operations lane already recorded one floor down (*"minimality is representation-relative"*).

**The problem did move up one floor, exactly as predicted.**

---

## 17. `Qualify` closure robustness

| basis | `Qualify` inside? | evidence | status |
|---|---|---|---|
| `𝒦₄` | **NO** | `minimum_implementable.py` closure: *"Assessment/Qualification/Measurement — reachable only downstream of Evidence/Σ"* | computed, **model-relative** |
| `𝒦₉` | **YES — it IS `C-7`**, *"turn an observation into evidence"*, forced by the Source/Semantic observation split | GN-77 §A | **canonically required** |
| `𝒦_T` | **unknown** | `𝒦_T` not constructible | — |

> ### `[EXP]` **Both are true, and they are not in conflict.** `Qualify` is **outside the `𝒦₄` dependency closure** *and* **a canonically required capability under `𝒦₉`**. Different questions.
>
> `[NEG]` **"`Qualify` is globally outside the kernel" is NOT established and is prohibited.**
> The `𝒦₉` closure **has not been computed** — **robustness untested.**

---

## 18. `𝒪_core` root claim re-audit

| | |
|---|---|
| **Graph fact** | **ESTABLISHED** — `𝒪_core` has no incoming edge in the admitted 42-edge graph |
| **Epistemic claim** (`𝒪_core` cannot be derived from Theory) | **NOT ESTABLISHED** — and now **actively contradicted**: Step 277's criterion derives primitiveness from `R_mandatory`, so `𝒪_core` **is** derivable *given* `ℐ` |

> ### **The graph establishes only the former.** And §2 of the triage showed `ℐ → 𝒪_core` is an edge the graph does not contain.

### 18a. ⚠️ A nuance against my carrier finding

`invariant-map.md` classifies **8-primitive kernel membership** as an **"implementation choice at
current standing — reduction outcome, not a proven minimum."** That does **not** contradict `C-022`'s
ratification (ratified ≠ proven minimal), but it does mean **"`K_t` is RATIFIED" must not be read as
"`K_t` is the proven minimal carrier."** Recorded.

---

## 19. `Π ∈ ≡?` — the distinction

$$\operatorname{Derive}(\Pi \in \equiv \mid \mathcal O_{core}) \qquad\text{vs}\qquad \operatorname{Canonicalize}(\Pi, \equiv)$$

`[EXP]` **These are different questions**, and both corpus statements can hold. **Not resolved here,
and not chosen to make the graph acyclic.** The conflict is preserved.

---

## 20. Updated status register

| item | status |
|---|---|
| `𝒦₄` | 🔴 **STIPULATED — candidate basis only** |
| `𝒦₉` | 🟢 **DERIVED as a reading of ratified invariants** · sufficiency & minimality **never claimed** · ratification status **NONE** for all nine |
| `𝒦_T` | ⚪ **NOT CONSTRUCTIBLE** from retrieved evidence; **shape** known |
| `𝒦₄` vs `𝒦₉` | **INCOMPARABLE** — different levels; **one TYPE contradiction (`Replay`)** |
| capability minimality | **representation-relative — NOT unique at the vocabulary level** |
| `Qualify` | outside `𝒦₄`'s closure · **IS `C-7` under `𝒦₉`** · global claim **prohibited** |
| `𝒪_core` "cannot be derived" | **NOT ESTABLISHED** — contradicted by Step 277 given `ℐ` |
| `𝔐_K` / `D-1A` | **still blocked** |

---

## 21. Explicit unresolved questions

1. Does a theory-derived capability basis `𝒦_T` exist at all? **Unknown.**
2. Is `Replay` a capability or a property? **Contradictory across lanes.**
3. Is `RejectIllegally` an operation, outcome, or safety property? **Undetermined** — blocked by the `Reject ↔ I-12 ↔ Article 8` contradiction.
4. Is `𝒦₉` **sufficient**? **Never claimed by anyone.**
5. Would the `𝒦₉` closure place `Qualify` inside the kernel? **Uncomputed.**
6. Is `Admissible` undecidable? **Still not independently verified by me.**

---

## 22. Recommendation for the next act

`[REC]` **The smallest epistemically legitimate next act is to compute the `𝒦₉` closure** — the same
`minimum_implementable.py` procedure, run against `C-1…C-9` instead of the stipulated four.

**Why it is smallest:** the program exists, the nine are now fully reconstructed with citations, and
it requires **no new definition, no stipulation and no governance act.** It would settle §17's
robustness question and test whether the nine-survivor partition holds.

⚠️ **It is a separate act and is NOT performed here.**

---

# Finding

`𝒦₄` is **stipulated**; `𝒦₉` is **derived as a reading of ratified invariants**; the two are
**incomparable, at different levels**, with one **type contradiction** over `Replay`. A theory-derived
basis `𝒦_T` **cannot be constructed** from the retrieved evidence, though its **shape** —
ownership- and non-collapse-shaped — is recoverable, and **`𝒦₄` matches none of it**.

# What was falsified

- **My claim that `verify_readiness_claims.py` verified the nine.** It verifies C1–C4; **none is the nine.** That leg is withdrawn.
- **My framing of `𝒦₄` and `𝒦₉` as rivals with unequal standing.** They are **incomparable**, not rival.
- **"`𝒪_core` cannot be derived"** — never established, and now contradicted by Step 277 given `ℐ`.
- **"`Qualify` is outside the kernel"** — holds only under `𝒦₄`; `Qualify` **is `C-7`** under `𝒦₉`.
- **"`K_t` is ratified" read as "proven minimal carrier"** — the invariant map calls 8-primitive membership an **implementation choice**.

# What survived

`𝒦₄`'s stipulated status · GN-84's four statements · Step 277's criterion and that it has never been
run · the `ℐ → 𝒪_core` dependency · `Reject ↔ I-12 ↔ Article 8` as the one unavoidable governance act ·
**NOT READY**.

# Capability basis verdict

| | verdict |
|---|---|
| **`𝒦₄`** | **D — Candidate basis only** |
| **`𝒦₉`** | **B-adjacent — theory-derived (as a reading), but sufficiency untested and minimality never claimed.** Not A: sufficiency unshown. Not D: it *is* derived. |
| **`𝒦_T`** | **E — insufficient evidence** |
| **Global** | **D + E** — the basis in actual use for the closure is a candidate; the theory-derived alternative cannot yet be constructed |

# Dependency impact

> **The nine-survivor partition becomes CONDITIONAL.** It was computed over `𝒦₄`'s closure. It is not
> invalidated — it is **model-relative**, and **must be recomputed under `𝒦₉`** before any item is
> called globally blocking or globally parallel.

# Next legitimate act

**Compute the `𝒦₉` closure with the existing program.** No new definition, no stipulation, no
governance act. **Not performed here.**

**Theory v1.2 FROZEN · kernel NOT SELECTED · `𝒪_core` NOT FROZEN · `𝒦₄` NOT VALIDATED · no code.**
