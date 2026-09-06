# MINIMALITY RESULT — the necessity test, executed

> ## ⛔ STATUS BLOCK — NOTHING IN THIS FILE IS RATIFIED
>
> **Authority:** HPA ruling **GN-79** / commission **GN-80** (2026-08-31), ARCHITECTURE/THEORY
> lane. **Tasks 3–6 of the commission.**
>
> **No registry below is canon.** **No candidate is adopted**, including `𝒪_core` and
> `𝒯_candidate`. **Where several minimal registries exist they are enumerated and none is
> selected** — selection by preference would void the deliverable (GN-81 AC-4). The operation
> registry remains **NOT ESTABLISHED**.
>
> `DERIVED` · `FORMALLY SHOWN` · `EMPIRICALLY TESTED` · `PROPOSED` ·
> `NORMATIVE DECISION REQUIRED` · `RATIFIED` (used only of pre-existing constraints).
>
> **Scripts and raw output:** `exec/rm.py` (reference model) · `exec/mintest.py` (this test) ·
> `exec/OUT-mintest.txt` (raw output, quoted throughout) · `exec/consistency.py` /
> `exec/OUT-consistency.txt` · `exec/circularity.py` / `exec/OUT-circularity.txt` ·
> `exec/inventory.py` / `exec/OUT-inventory.txt`.

---

## 0 · What was executed, and why it is the criterion the record asks for

### 0.1 The criterion

The record states it twice and executes it neither time:

- **step 277 §277.30** — *"`o` is primitive `⟺ ∃r ∈ R_mandatory : r ∉ Closure(𝒯₋ₒ)`. This is the
  correct basis for the next step."* Step 277 then lists nine falsification tests **T1…T9 as future
  obligations** and says *"Step 278 must perform the actual counterfactual experiment"*.
- **step 272A §272A.16** — the same shape over `D_mandatory`, deferred to step 273.

`handoff/06-HANDOFF-VERDICT.md`, on the record: *"277 states the criterion — … — and never runs
it"*; *"**`𝒪_core` minimality — the criterion exists; the test has never been run by anyone.**"*

**`R_mandatory` is never enumerated in step 277.** `D_mandatory` is enumerated in 272A §272A.17 —
and `exec/circularity.py` shows the enumeration is a **bijection** onto 272A's own operation list,
so the criterion over it returns *"all 19 necessary"* by construction and can discriminate nothing
(`OPERATION-REGISTRY-DERIVATION.md` §2.1). **The index set therefore had to be derived, and it was
derived from the ratified surface**: `R_A`, fifteen capabilities, `OPERATION-REGISTRY-DERIVATION.md`
§2.2.

### 0.2 How `Closure` was made computable

`Closure(𝒯)` is instantiated as **bounded reachability under finite composition**: for a subset
`S` of operations, a mandatory capability `r = (seed, goal)` is *in* `Closure(S)` iff some finite
sequence of operations drawn only from `S`, of length ≤ the capability's depth bound, carries the
seed state to a state satisfying `goal`.

This is what makes the test executable **without** resolving the state-equality question that
blocks the contracts: reachability needs only a *decidable* state comparison for deduplication, not
a *canonical* one. `DERIVED`.

### 0.3 The procedure, in the order it ran

| step | what it does |
|---|---|
| **0** | **Mechanical effect-equivalence.** Two candidate names are equivalent iff they return the same `(state, outcome)` on every (probe state × argument) pair. Names that collapse are reported; the test runs over one representative per class. Operations that are genuine **reads** (they succeed and leave `K` unchanged) are excluded, because they can appear in no witness of a reachability goal. |
| **1** | **Witness search.** For each capability, *all minimal support sets* of size ≤ 4 — two phases: a support-tracking BFS with subset-dominance pruning narrows the pool, then an **exact** enumeration in increasing size over that pool, with two sound prunes (a witness must contain an operation that *writes* a goal field; supersets of a found witness are skipped). |
| **1b** | **Constructed witnesses** for any capability the size-4 enumeration could not reach, shown by an explicit program with its trace. |
| **2** | **Per-operation necessity**, in the record's own form: `o` is **necessary** iff some capability has **every** witness containing `o`. Run **three times** — over all fifteen capabilities, over the thirteen `RATIFIED-FORCED` ones, and over the twelve that are both ratified-forced **and** ratified-formulated. |
| **3** | **All minimal sufficient registries, enumerated exactly.** `S` is sufficient iff every capability has some witness `⊆ S`; the minimal such `S` are computed by iterated expansion over the witness families and reduction to the antichain. |
| **4** | **Subset removal** — every **pair** and every **triple** over the non-necessary operations, to catch interaction effects that single removal misses. |

### 0.4 Declared modelling commitments, and which way each biases the answer

Every commitment is in `exec/rm.py`'s header. The ones that could change the conclusion:

| commitment | direction of its effect on the conclusion |
|---|---|
| **M1** structural state equality (canon defines none) | affects deduplication only; a coarser equality would merge states and **reduce** the reachable space — could only lose witnesses, i.e. **understate multiplicity** |
| **M2** five typed rejection kinds (canon defines none) | affects *why* an operation refuses, not *whether*; no effect on reachability |
| **M3** both fine and coarse operations in the pool where the corpus names both | **increases** multiplicity — and this is deliberate: suppressing one granularity would be selection by preference |
| **M4** evidence grade is a **set of independent source classes**, never a number | satisfies I-5/I-6 structurally **without** selecting an aggregation operator, so **OQ-3 stays open by ruling** |
| **M5** Σ is not in the state core | avoids importing an unratified structure; the operations that need Σ are reported, not relied on |
| **M6** guards derived from ratified constraints only | a *weaker* guard would let more operations realise a capability → **would increase multiplicity**. So the guards bias **against** multiplicity. |
| six operations constructed from the ratified surface because **the corpus names no operation for them** (`Commit`, `EnactPolicyVersion`, `ComputeZero`, `MarkConflict`, plus `Observe`/`RecognizeDimension` for the dimension case) | **increases** the number of realizers. **Without them several ratified capabilities have no realizer at all** — which is itself a principal finding, reported in §5.3 |
| depth bounds, the size-4 witness cap, and per-search node budgets | a missed witness can only **understate multiplicity** and **overstate necessity** — never the reverse |

> **The net direction matters for reading the verdict.** Every bound in the method biases toward
> *fewer* registries and *more* necessary operations — that is, **toward verdict A**. A result that
> nevertheless finds multiplicity is therefore reported against the bias of its own method.
> `DERIVED`.

---

---

## 1 · STEP 0 — mechanical effect-equivalence of the candidate pool `EMPIRICALLY TESTED`

Verbatim from `exec/OUT-mintest.txt`:

```
candidate pool size (names)          : 55
distinct effect classes              : 39
classes containing >1 name           : 7
  COLLAPSED: Compare == Equal == Evaluate == Explain == ExplainRevision ==
             Identity == LineageQuery == ProvenanceQuery == Query == SupersessionHistory
  COLLAPSED: Add == Assert == Create
  COLLAPSED: Accept == Determine
  COLLAPSED: ChangePolicy == EnactPolicyVersion
  COLLAPSED: LinkEvidence == Support
  COLLAPSED: Promote == Transform
  COLLAPSED: Remove == Retract
NO-OP-ON-K names (never change state on any probe): 3
  Compare, Trace, noop
search pool (state-changing reps)    : 36
```

**Two results follow, both mechanical.**

**(a) Twelve of the candidate names are pure reads.** The ten-name class headed by `Compare`, plus
`Trace` and `noop`, succeed and leave `K` unchanged on every probe. A pure read can appear in **no
witness of a state-reachability requirement**, so twelve candidate names are eliminated from the
minimality question *before* any removal test — not because they are unimportant, but because they
are not operations on the carrier.

This **independently re-derives step 259 §259.8's rule** — *"only state-transforming operations
enter the primary congruence test… Thus we must not manufacture a false universal algebra"* — by
execution rather than by argument. `EMPIRICALLY TESTED`.

**(b) Seven groups of distinct corpus names are one operation.** Under the ratified guards,
`Add ≡ Assert ≡ Create`, `Accept ≡ Determine`, `ChangePolicy ≡ EnactPolicyVersion`,
`LinkEvidence ≡ Support`, `Promote ≡ Transform`, `Remove ≡ Retract`. The 57-name live universe
therefore carries at most **39 distinguishable effects**. `EMPIRICALLY TESTED`.

> **This does not settle the naming disputes; it sharpens them.** `Promote ≡ Transform` is a
> statement about this model's guards, and `250 §250.23` records that *"Transformation"* carries
> **three** distinct senses. What the collapse shows is that **whichever sense is chosen, one of
> the two names is redundant in the registry** — and `250 §250.4` warns explicitly
> `Create ≠ necessarily Add`, so the collapse of `Create` into `Add` is a **modelling consequence,
> not a corpus finding**. Recorded as `NORMATIVE DECISION REQUIRED` in each case.

---

## 2 · STEP 1 — the witnesses `EMPIRICALLY TESTED`

All minimal support sets of size ≤ 4, enumerated exactly. One row per mandatory capability.

| cap | forced by (ratified) | minimal witnesses | realizers |
|---|---|---|---|
| **A1** advance `Candidate → Supported` | ladder + I-12 | **2**: `{Promote}` · `{Authorize, MarkConflict, Resolve}` | 4 |
| **A2** Determination `Supported → Accepted` | ladder + I-12 + v0.2 *Determination* | **2**: `{Accept}` · `{Promote}` | 2 |
| **A3** attach `Committed` by authority | A6 / I-4 | **1**: `{Authorize, Commit}` | 2 |
| **A4** governed in-force policy version change | I-11 + v0.2 §3 loop | **1**: `{Approve, Authorize, ChangePolicy}` | 3 |
| **A5** compose evidence (dedup · corroborate · dependency-first) | **I-5 + I-6** (the only TESTED pair) | **1**: `{Assess, LinkEvidence}` | 2 |
| **A6c** typed gap `Zero(K,EC)` | Zero + I-9 | **1**: `{ComputeZero}` | 1 |
| **A7** observation → evidence | SourceObs ≠ SemanticObs | **1**: `{Observe, Qualify}` | 2 |
| **A8** reach `REJECTED`, item preserved | FA-1 D-FA-1 / Art. 7 | **1**: `{Reject}` | 1 |
| **A9** reach `CONFLICTED` | FA-1 D-FA-1 / Art. 8 | **5**: `{Authorize, MarkConflict}` · `{Add, DetectContradiction, Relate}` · `{DetectContradiction, Infer, Relate}` · `{DetectContradiction, LinkEvidence, Refute}` · `{DetectContradiction, Relate, Split}` | 9 |
| **A10** governed exit from `CONFLICTED` | FA-1 D-FA-1 / Art. 8 | **1**: `{Authorize, Resolve}` | 2 |
| **A11** `UNKNOWN ≠ ABSENT` | Art. 9 / I-9 | **2**: `{Observe}` · `{RecognizeDimension}` | 2 |
| **A12** authority-free proposal | I-2 + v0.2 §3 flow node | **1**: `{ComputeZero, Propose}` | 2 |
| **A13** admissible decision under DC | 042 DC 6-tuple + I-3 | **0 of size ≤ 4** — see §3.2 | — |
| **A14** act → observe *(PROPOSED)* | action loop; OQ-4 open | **1**: `{Act}` | 1 |
| **A15** replay *(PROPOSED)* | `State ≠ History`, not in the ratified surface | **1**: `{Replay}` | 1 |

**Every mandatory capability is reachable.** No ratified-forced capability turned out inexpressible
in the candidate universe. `EMPIRICALLY TESTED`.

### 2.1 The unique-writer cross-check `FORMALLY SHOWN`

Necessity found by a bounded search could be an artefact of the bound. So it was cross-checked by
an argument that does not use the search at all: *if exactly one operation in the pool writes a
field the goal requires, its necessity is independent of the search's completeness.* Executed:

```
fields with exactly ONE writer in the search pool: 10
  approvals <- Approve     auth_acts <- Authorize    decisions <- Decide
  item.committed <- Commit item.version <- Revise    proposals <- Propose
  qualified <- Qualify     replays <- Replay         validations <- Validate
  zero <- ComputeZero

fields with MORE THAN ONE writer (necessity DOES depend on the search):
  dims     <- Observe, RecognizeDimension
  ev       <- AdmitEvidence, Qualify, Refute
  item.ev  <- LinkEvidence, Merge, Refute, Split
  ... (item.status, rels, obs, item.grade likewise)
```

So the necessity of **`Approve`, `Authorize`, `Commit`, `Propose`, `Qualify`, `ComputeZero`** — and,
for the capabilities that include them, `Decide`, `Validate`, `Replay` — rests on a
**unique-writer argument, not on the search**. `Reject`'s and `Resolve`'s necessity likewise rests
on being the only operation that can *reach the required status value* (`Reject` is the only
operation that sets `Rejected`; `Resolve` is the only one that moves an item out of `Conflicted`
into the ladder). `FORMALLY SHOWN`.

**One necessity claim is genuinely cap-bounded, and is reported as such.** `Observe`'s necessity for
**A7** depends on the search: `obs` has a second writer, `Act`, so a route
`… → Decide → Act → Qualify` would also produce a qualified observation. That route needs ~12
operations and exceeds A7's depth bound, so **`Observe`'s necessity holds within the bound and is
not established absolutely.** No other necessity claim has this weakness.

---

## 3 · Two structural discoveries the test made `EMPIRICALLY TESTED`

### 3.1 The ratified state model permits an **I-12 bypass** through `CONFLICTED`

The second witness for **A1** is not an artefact — it is a genuine path:

```
A1 (Candidate -> Supported)
  minimal witnesses: 2
    {Promote}
    {Authorize, MarkConflict, Resolve}
```

Read as a program: `Authorize(i)` → `MarkConflict(i)` (`Candidate → Conflicted`, a governed
suspension, which **FA-1 permits from any pre-boundary status**) → `Resolve(i)`
(`Conflicted → Supported`, a governed resolution). The item arrives at `Supported`
**without ever traversing the covering relation `Candidate ⋖ Supported`.**

**The cause is a gap in the ratified text, not in the model.** FA-1 §2 ratifies that
`REJECTED`/`CONFLICTED` are *"reachable from any pre-boundary status by a governed act"* and that
CONFLICTED holds *"until governed resolution"* — and it **never states which rung a governed
resolution returns to.** I-12 governs *"the admission axis only"*; a round trip off the axis and
back is unaddressed.

> **`NORMATIVE DECISION REQUIRED` — named, not made.** *Which rung does a governed resolution
> return an item to?* The candidate answers — the rung held before suspension · `Candidate` ·
> a rung the resolving authority names · resolution is terminal like `REJECTED` — are **not
> interchangeable**, and only the first closes the bypass. **This is a finding against the
> ratified layered state model, discovered by execution.** It is reported, not repaired: repairing
> it would amend a ratified artifact.

The bypass is load-bearing for the result. Step 4 confirms it mechanically:

```
PURE INTERACTION pairs (neither member individually necessary):
  {MarkConflict, Promote}  ->  loses A1
```

Neither `MarkConflict` nor `Promote` is necessary alone — because each covers A1 by a different
route — but removing **both** costs A1. That is precisely the interaction that single-operation
removal cannot see, and it is why the commission's instruction to test subsets mattered.

### 3.2 `A13` has no witness of size ≤ 4; its minimal support is ≥ 5

The exhaustive size-4 enumeration returned **nothing** for A13. Reachability was then shown by an
explicit program:

```
A13: program of 7 steps, support size 7
     LinkEvidence(i1:e1) -> ok      Assess(i1) -> ok       Validate(-) -> ok
     Authorize(i1) -> ok            ComputeZero(-) -> ok   Propose(next) -> ok
     Decide(-) -> ok
     GOAL REACHED: True
     support: {Assess, Authorize, ComputeZero, Decide, LinkEvidence, Propose, Validate}
     -> the executed enumeration showed NO witness of size <= 4, so any minimal
        witness for A13 has size >= 5.
```

**A13 is the only capability whose realization requires more than three operations**, and the reason
is structural: the DC 6-tuple needs six independently-produced slots, and five of the six have a
**unique writer** in the pool. So A13's cost is not a modelling accident — it is what a six-slot
decision contract costs. But note **A13's formulation is PROPOSED, not ratified** (no ratified
conjunction over exactly those six slots exists), which is why the whole analysis is also run with
A13 removed. `EMPIRICALLY TESTED`.

---

## 4 · STEP 2–4 — necessity, the registries, and the interactions `EMPIRICALLY TESTED`

The analysis was run over **three** requirement sets, to separate what the ratified surface forces
from what my formulation adds:

| set | capabilities | rationale |
|---|---|---|
| `R_A` | all **15** | includes the two `PROPOSED` capabilities (A14 action-loop far side, A15 replay) |
| `R_A_ratified` | **13** | ratified-forced only; A14, A15 dropped |
| `R_A_strict` | **12** | ratified-forced **and** ratified-formulated; A13 also dropped, because its six-slot conjunction is mine |

### 4.1 Necessary operations

| set | necessary | the operations |
|---|---|---|
| `R_A` | **16** | `Act, Approve, Assess, Authorize, ChangePolicy, Commit, ComputeZero, Decide, LinkEvidence, Observe, Propose, Qualify, Reject, Replay, Resolve, Validate` |
| `R_A_ratified` | **14** | the same, minus `Act`, `Replay` |
| `R_A_strict` | **12** | the same, minus `Decide`, `Validate` |

Not necessary in any variant (removal costs no capability): `Accept, Add, AdmitEvidence,
AssertPolicy, Derive, DetectContradiction, Infer, MarkConflict, Merge, Promote,
RecognizeDimension, Refine, Refute, Reintroduce, Relate, Remove, Revise, Split, Supersede,
Withdraw` — **20 of the 36 tested representatives**.

> **`Merge`, `Split`, `Revise`, `Supersede`, `Withdraw`, `Reintroduce`, `Remove`/`Retract` and
> `Derive` are NOT NECESSARY for any capability the ratified surface forces.** Between them these
> eight names carry **56 of the 17 sources' mentions**, `Merge` and `Supersede` being the two
> best-corroborated names in the entire corpus (10 sources each). **Corroboration across the
> research record is not necessity against the ratified surface.** `EMPIRICALLY TESTED`.
>
> This is *not* a recommendation to drop them. It is the statement that **the ratified surface
> quantifies over none of them**, so their necessity cannot be tested against it
> (`OPERATION-REGISTRY-DERIVATION.md` §2.4). A registry that includes them is including them on
> some ground other than ratified entailment, and that ground must be named.

### 4.2 All minimal sufficient registries — **SIX, in every variant**

```
R_A (all 15)        : 6 minimal registries, sizes [18, 19, 20], CORE = 16, BAND = 9
R_A_ratified (13)   : 6 minimal registries, sizes [16, 17, 18], CORE = 14, BAND = 9
R_A_strict (12)     : 6 minimal registries, sizes [14, 15, 16], CORE = 12, BAND = 9
CROSS-CHECK  intersection == necessary-set ?  True   (all three variants)
```

**The strict variant in full — enumerated, none selected:**

```
CORE = {Approve, Assess, Authorize, ChangePolicy, Commit, ComputeZero,
        LinkEvidence, Observe, Propose, Qualify, Reject, Resolve}          (12)

R1 (|R|=14) = CORE + {Accept, MarkConflict}
R2 (|R|=14) = CORE + {MarkConflict, Promote}
R3 (|R|=15) = CORE + {DetectContradiction, Promote, Refute}
R4 (|R|=16) = CORE + {Add, DetectContradiction, Promote, Relate}
R5 (|R|=16) = CORE + {DetectContradiction, Infer, Promote, Relate}
R6 (|R|=16) = CORE + {DetectContradiction, Promote, Relate, Split}
```

The other two variants have the **identical structure** — the same six extension sets over a
larger core (`R_A_ratified` adds `Decide, Validate`; `R_A` adds `Act, Replay` on top). So:

> **The undetermined band is INVARIANT at exactly nine operations across all three requirement
> sets:** `Accept · Add · DetectContradiction · Infer · MarkConflict · Promote · Refute · Relate ·
> Split`. Changing which capabilities count as mandatory changes the **core**; it does not change
> **what is undetermined**. `EMPIRICALLY TESTED`.

**What generates the six.** Exactly three independent choices, each traceable to an unresolved
question:

| choice | options | the unresolved question behind it |
|---|---|---|
| how to move up the ladder | `{Promote}` covers both rungs · `{Accept}` covers only the second, so the first rung must come from the CONFLICTED round trip | **granularity** — one coarse rung-mover or two fine ones? `OPERATION-CONTRACT-GAP` §C-2 records this as undecided |
| how to reach `CONFLICTED` | a governed act (`MarkConflict`) · or detection (`DetectContradiction` + one of four ways to create the contradiction) | **may CONFLICTED be entered without governance?** Art. 8 calls it a *governed* suspension; the corpus supplies only the detection route |
| how to create the contradiction that detection needs | `Relate`+`Add` · `Relate`+`Infer` · `Refute`+`LinkEvidence` · `Relate`+`Split` | **is a contradiction an evidential fact or a relation?** `RELATION-ALGEBRA` makes `contradicts` a **derived, never-stored** predicate; 272A insists `Contradiction ≠ EpistemicStatus`; FA-1 ratifies `CONFLICTED` as a **state** |

### 4.3 Interaction effects — what single removal misses

```
pairs tested: 630   pairs whose joint removal costs a capability: 357
of those, PURE INTERACTION pairs (neither member individually necessary): 3
  {Accept, Promote}                    ->  loses A2
  {DetectContradiction, MarkConflict}  ->  loses A9
  {MarkConflict, Promote}              ->  loses A1
triples over non-necessary ops: 2024   lossy: 65   NEW (not explained by a lossy pair): 1
  {MarkConflict, Refute, Relate}       ->  loses A9
```

Three pure-interaction pairs and one irreducible triple. **Each corresponds exactly to one of the
three choices in §4.2** — which is the mechanical confirmation that the multiplicity is generated
by those three questions and by nothing else. Had the test only removed operations one at a time,
it would have found `Accept`, `Promote`, `MarkConflict`, `DetectContradiction`, `Refute` and
`Relate` all "not necessary" and drawn the false conclusion that all six can go. `EMPIRICALLY
TESTED`.

---

## 5 · Tasks 4, 5 and 6 — the answers

### 5.1 Task 4 — does a minimal registry exist?

**Under the reachability criterion alone: YES, and there are six of them.** `EMPIRICALLY TESTED`.
Every mandatory capability is reachable; the sufficient sets form a well-defined family; its
minimal elements were enumerated exactly, and the enumeration is self-consistent (the intersection
of all minimal registries equals the independently computed necessary set, in all three variants).

**Fit for ratification: NO — and the obstruction is not the multiplicity.** It is §5.3.

### 5.2 Task 5 — the necessary core, with each member's argument

The intersection of all six minimal registries, in the strict variant. Each member's necessity is
stated with the reason it survives:

| operation | loses | argument |
|---|---|---|
| `Approve` | A4 | **unique writer** of `approvals`; I-11 requires a *governed, versioned approval* and nothing else records one |
| `Assess` | A5 | the **only** operation producing a non-empty grade — so the only one that can satisfy I-5's dedup-and-corroborate and I-6's dependency-first requirement jointly |
| `Authorize` | A3, A4, A10 | **unique writer** of `auth_acts`. **The most load-bearing member: three capabilities.** A6/I-4 makes authority non-derivable from evidence, so nothing else can produce it |
| `ChangePolicy` ≡ `EnactPolicyVersion` | A4 | the only operation that can raise the in-force policy **version**; `AssertPolicy` can only introduce a version-1 record not in force |
| `Commit` | A3 | **unique writer** of `item.committed`; A6 fixes that this is crossed by authority and never by evidence |
| `ComputeZero` | A6c, A12 | **unique writer** of `zero`; I-9 requires the typed four-way gap, and `Propose` cannot fire without it |
| `LinkEvidence` ≡ `Support` | A5 | evidence must reach the item before it can be composed; `Merge`, `Split` and `Refute` all clear the grade rather than compose it |
| `Observe` | A7 | the intake side of `SourceObs ≠ SemanticObs`. **Cap-bounded** — see §2.1: `Act` is a second writer of `obs` on a ~12-step route beyond A7's depth bound |
| `Propose` | A12 | **unique writer** of `proposals`; I-2 requires an authority-free selector to exist |
| `Qualify` | A7 | **unique writer** of `qualified`; the `Observation ≠ Evidence` transition |
| `Reject` | A8 | the **only** operation that reaches `Rejected`. **And its specification violates two ratified constraints** — §5.3 |
| `Resolve` | A10 | the **only** operation that moves an item out of `Conflicted`; Art. 8's *"until governed resolution"* |

Plus, when the corresponding capability is counted: `Decide` and `Validate` (A13, whose formulation
is PROPOSED), `Act` (A14, OQ-4 open), `Replay` (A15, not in the ratified surface).

### 5.3 Task 6 — the non-uniqueness, reported and NOT resolved — **and a stronger obstruction**

**Six minimal registries exist. They are enumerated in §4.2. None is selected.** The three choices
that generate them are named in §4.2 and each is `NORMATIVE DECISION REQUIRED`. Nothing in this
lane's authority can settle any of the three, and preferring one would void the deliverable
(GN-81 AC-4).

**But the decisive result is not the multiplicity — it is that no member of the family is
consistent.** Cross-referencing §4.2 against the ratified-consistency check
(`OPERATION-REGISTRY-DERIVATION.md` §3.2):

| finding | consequence for the family |
|---|---|
| **`Reject` violates I-12 and Art. 8** as specified (`Conflicted → Rejected` with no governed act) | `Reject` is in the **CORE of all six registries**, because it is the only operation that reaches `Rejected`, and A8 is **RATIFIED-FORCED**. **Therefore all six minimal registries contain an operation whose specification violates a ratified invariant.** |
| **`Split` violates I-12** (it creates items directly at `Supported`/`Accepted`/`Conflicted`) | excludes **R6** on consistency grounds, leaving five — a *reduction* in multiplicity, not a resolution |
| **`LinkEvidence` is UNRECONCILED** against the executed algebra's immutability invariant, and 277 §277.14 would re-type it as a **relation** rather than a state operation | `LinkEvidence` is in the **CORE**. If it is re-typed, **A5's witness changes and the core changes** |
| **A13's six-slot conjunction is PROPOSED** | `Decide` and `Validate` enter the core only through it |
| **The resolution return-rung is unspecified** (§3.1) | if resolution returns to the pre-suspension rung, A1's second witness disappears, `Promote` becomes necessary, and **R1 disappears** — the family collapses from six to five with a different core |

> ### **THE OBSTRUCTION, stated once and precisely**
>
> A minimal registry **can** be computed from the ratified surface — six of them were. **None of
> them can be established**, because the single operation that every one of them must contain in
> order to satisfy a **ratified** capability is an operation whose **specification contradicts two
> ratified constraints**, and the repair is a normative choice this lane may not make.
>
> Membership therefore does not merely admit alternatives; it is **not determined** until a set of
> named prior canonical decisions is taken. `FORMALLY SHOWN` from the executed results.

**The prior decisions, enumerated. Each one changes membership; none is made here:**

| # | prior canonical decision | what changes |
|---|---|---|
| **P-1** | **Is `Reject` an epistemic disposition (256.11, no source-status guard) or a governance act (277/276, external, authority-gated)?** | whether the core's only realizer of the ratified A8 is consistent at all |
| **P-2** | **Which rung does a governed resolution of `CONFLICTED` return to?** | whether the I-12 bypass exists; whether `Promote` is necessary; the number of registries |
| **P-3** | **Granularity: one coarse rung-mover (`Promote`/`Transform`) or two fine ones (`Promote` + `Determine`/`Accept`)?** | R1 vs R2, and the identity of the core |
| **P-4** | **Is `LinkEvidence` a state operation, a mutable field, or a relation (277 §277.14)?** | A5's witness, hence the core |
| **P-5** | **May `CONFLICTED` be entered without a governed act?** | whether `MarkConflict` or `DetectContradiction` is the route, hence R1/R2 vs R3–R6 |
| **P-6** | **Is a contradiction an evidential fact or a stored relation?** | which of R3–R6 stands |
| **P-7** | **What is the conjunction over the DC 6-tuple?** | whether `Decide` and `Validate` are members |
| **P-8** | **What is the canonical state equality?** | every postcondition, and every algebraic law the corpus already asserts |
| **P-9** | **Is the invariant register closed?** | whether "preserves the invariants" has a codomain at all |
| **P-10** | **What is the typed rejection vocabulary?** | every failure-semantics field |

**P-8, P-9 and P-10 are the commission's own C-5 prerequisites.** The executed evidence is that
they block the **contracts** (`OPERATION-CONTRACTS.md` §0.1) and did **not** block this test.
**P-1 … P-7 are new, and they are what actually blocks the registry.** `DERIVED`.

---

## 6 · Threats to this result, and which way each cuts

Stated so the independent reviewer can attack them directly (GN-80's nine attack surfaces).

| # | threat | direction, and what would settle it |
|---|---|---|
| T-1 | **The reference model is mine.** A different faithful encoding of the same corpus signatures could yield different witnesses. | **Both directions.** The mitigations: every guard is derived from a ratified constraint (M6); every operation's provenance and evidence class is recorded (`exec/rm.py` `PROV`); the effect-equivalence step is mechanical, so no name was collapsed by judgment. **Settled by:** re-encoding and re-running `exec/mintest.py`. |
| T-2 | **Six operations were constructed because the corpus names no operation for them** (`Commit`, `EnactPolicyVersion`, `ComputeZero`, `MarkConflict`, and — for the dimension case — `RecognizeDimension`; plus `Decide`'s conjunction). | **Increases** realizers, hence multiplicity. **But removing them makes four ratified capabilities unrealizable**, which is a stronger negative, not a weaker one. Each is marked `RC — ratified-forced` in the provenance table, and `RecognizeDimension` is marked a **proposal, not a member** under C-7. |
| T-3 | **The size-4 witness cap and the depth bounds.** | **Understates** multiplicity, **overstates** necessity. The one necessity claim actually exposed by it is `Observe`'s (§2.1), and it is flagged. A13's witness was supplied by construction rather than enumeration. |
| T-4 | **Per-search node budgets were reached for A5 and A13.** | Same direction as T-3. A5's witness is corroborated by the unique-realizer argument (`Assess` is the only producer of a non-empty grade). |
| T-5 | **`R_A` could be the wrong requirement set** — GN-81's AC-6 watch item. | Answered three ways: every row cites a ratified element; the whole analysis is re-run over the 13-capability and 12-capability restrictions; and `exec/circularity.py` shows why the corpus's own set could not be used. **The band is invariant at nine across all three sets**, so the multiplicity is not an artefact of the requirement set. |
| T-6 | **M1's structural equality is model-local.** | Affects deduplication only. A coarser equality merges states, which can only **lose** witnesses. |
| T-7 | **`Split` and `LinkEvidence` were carried into the test despite being recorded UNRECONCILED.** | Deliberate: excluding them would have been a decision. Both are reported — `Split` as an I-12 violator that excludes R6, `LinkEvidence` as a core member whose re-typing would change the core. |
| T-8 | **The `Promote ≡ Transform` and `Add ≡ Assert ≡ Create` collapses.** | They **reduce** the name space, hence reduce apparent multiplicity. Each is flagged `NORMATIVE DECISION REQUIRED` in §1(b). |
| T-9 | **Nothing here is independently reviewed.** | Acknowledged. C-8 requires a different fresh-context party. **This deliverable is not self-accepted, and no verdict here is ratified.** |

---

## 7 · Recommended terminal verdict

The commission requires exactly one of four. All three of the following are literally supported by
the executed evidence, at three different levels, and are set out so the HPA can substitute a
weaker claim if it prefers:

- **B is true as a computed result** — six minimal registries exist, enumerated in §4.2.
- **C is true as a status** — none of the six is fit for ratification, because every one of them
  contains `Reject`, whose specification violates I-12 and Art. 8 (§5.3).
- **D is true as the cause, and it is the most informative claim the evidence supports** — ten
  named prior canonical decisions (P-1 … P-10) each change membership, and seven of them
  (P-1 … P-7) were not previously on the register.

### **RECOMMENDED: D — THE OPERATION UNIVERSE DEPENDS ON AN UNRESOLVED PRIOR CANONICAL DECISION**

**Recommended, not ratified.** The ground is that the test did **not** fail and did **not** merely
run out of evidence: it succeeded, produced a well-defined family of six minimal registries, and in
doing so **identified precisely which prior decisions determine membership** — beginning with the
typing of `Reject`, on which the consistency of every candidate registry depends. That is a
different and more actionable finding than "not yet establishable", and it is what the evidence
shows.

**If the HPA prefers the weaker claim, C is fully supported and B is the computed intermediate
result.** Nothing in this file is `RATIFIED`.
