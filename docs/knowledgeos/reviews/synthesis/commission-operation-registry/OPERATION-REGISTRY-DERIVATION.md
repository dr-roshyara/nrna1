# OPERATION REGISTRY DERIVATION — candidate universe, mandatory capabilities, consistency

> ## ⛔ STATUS BLOCK — NOTHING IN THIS FILE IS RATIFIED
>
> **Authority:** HPA ruling **GN-79** / commission **GN-80** (2026-08-31), executed by the
> **ARCHITECTURE / THEORY** lane. This is a **derivation**, not a ratification. The deliverable
> returns for independent review (C-8) and then a **separate HPA ratification act**.
>
> **No operation named in this file is canon.** **No candidate is adopted**, including `𝒪_core`
> and `𝒯_candidate`. **Nothing is selected by preference.** The operation registry remains
> **NOT ESTABLISHED**; Operations and Transformations remain
> `IMPLEMENTATION BLOCKER — CANONICAL SEMANTICS NOT ESTABLISHED`.
>
> **Epistemic states used, kept rigorously separate on every claim:**
> `DERIVED` · `FORMALLY SHOWN` · `EMPIRICALLY TESTED` · `PROPOSED` ·
> `NORMATIVE DECISION REQUIRED` · `RATIFIED`.
> **`RATIFIED` appears in this file only to name constraints that were ratified BEFORE this
> commission. None of the below is RATIFIED.**
>
> **Companion files:** `MINIMALITY-RESULT.md` (tasks 3–6) · `OPERATION-CONTRACTS.md` (task 7) ·
> `exec/` (every script and its raw output).

---

## 0 · Method

**What was read.** The ratified/authorized surface first, as constraint:
`model/canonical-architecture-v0.2.md` (AUTHORIZED, GN-19), `model/canonical-architecture.md`
(v0.1, superseded, read for I-1…I-10 and the DC 6-tuple which v0.2 carries forward),
`final-architecture/FA-1…FA-9` (RATIFIED, GN-31). Then the research lane as **candidate
material, read and not trusted**: steps 232, 249, 250, 256, 257, 259, 272A, 272B, 277;
`verification/KNOWLEDGE-STATE-ALGEBRA.md`, `TRANSFORMATION-CANONICAL-MODEL.md`,
`RELATION-ALGEBRA.md`; `canonical-construction/02-OPERATION-UNIVERSE.md` and its
`exec/oderive.py`; `handoff/02-O-CORE-AUTHORITATIVE-DETERMINATION.md` and
`03`/`04`; and the governed synthesis findings `analysis/OPERATION-CONTRACT-GAP.md`,
`TRANSFORMATION-CONTRACT-GAP.md`, `DRAFT-HPA-RULING-operation-registry.md`,
`COMMISSION-operation-registry-derivation.md`.

**What was executed.** Five scripts, all preserved with raw output:

| script | what it executes | raw output |
|---|---|---|
| `exec/inventory.py` | Task 1 — unions the corpus enumerations, computes the membership matrix and every pairwise disagreement | `exec/OUT-inventory.txt` |
| `exec/rm.py` | the reference model: state, 55 candidate operations, typed rejection, guards derived from the ratified constraints | — (library) |
| `exec/mintest.py` | Task 3 — effect-equivalence, witness search, per-operation necessity, exhaustive minimal-registry enumeration, subset removal | `exec/OUT-mintest.txt` |
| `exec/consistency.py` | Task 8 — every candidate operation against every ratified constraint, exhaustively over probes × arguments | `exec/OUT-consistency.txt` |
| `exec/circularity.py` | whether the corpus's own requirement set can discriminate at all | `exec/OUT-circularity.txt` |

**Method rule observed throughout.** The strongest statement made never exceeds the strength of
the available evidence. Where a field could not be filled without a normative choice, the choice
is **named and left unmade**.

**One methodological finding before any result — the prior "derivation" was not a derivation.**
`canonical-construction/exec/oderive.py` is the executable behind the widely-cited
*"14-forced / 18-upper"* result. Reading it: it contains a hand-authored dictionary `FORCES`
mapping each non-collapse law to the operation the author judged it to entail, and a dictionary
`LAWS` of measured repetition counts used as *"evidence weight"*. There is **no closure
computation, no removal test, and no state model** in the file. The script prints the transitive
image of its own input table.

Therefore the *"14 forced operations"* is `PROPOSED` (a hand-built forcing table), **not**
`DERIVED`, and the repetition counts are corpus frequency, which is not entailment. This does not
make the 14-element result wrong; it makes it **untested**, exactly as
`02-O-CORE-AUTHORITATIVE-DETERMINATION.md` says of the whole area. `FORMALLY SHOWN`.

---

## 1 · TASK 1 — the complete candidate operation universe

Executed by `exec/inventory.py`; raw output `exec/OUT-inventory.txt`. Seventeen live sources plus
ten pre-canonical ones were inventoried by direct reading; the script only unions, diffs and
counts.

### 1.1 Headline counts `EMPIRICALLY TESTED` (executed enumeration)

```
sources inventoried                        : 17
DISTINCT SEMANTIC-CORE OPERATION NAMES     : 57
names EXCLUDED by at least one source      : 7  (Command, Delete, Deserialize,
                                                 Event, Load, Save, Serialize)
historical union (10 pre-canonical sets)   : 69
historical names NOT in the live universe  : 48
GRAND UNION (live + historical)      |O|   : 105
```

The **57** is the live candidate universe — the number a registry decision must range over.
The **105** is the whole search history. Per **FA-1 §1 L5** (*"HISTORICAL CORPUS … evidence of
discovery, never authority"*) the pre-canonical sets are counted separately and are not folded
into the live universe: `𝒪_K` (8) · `ℛ_K` (9) · 051's `Σ` event alphabet (9) · 025a-5 generators
(9) · 187's `R` (7) · 205's candidate commands (14) · 032's pipeline (8) · 034's inquiry set (10)
· 025z's Lord actions (8) · the `kos_kernel.py` 7. Their 48 names outside the live universe
(`Introduce`, `Corroborate`, `Invalidate`, `Contextualize`, `Downgrade`, `Defer`, `Contradict`,
`Ask`, `Execute`, `Record`, `Measure`, …) are recorded, not revived.

### 1.2 The per-source enumerations, with their own self-assessments

| key | source | \|𝒪\| | the source's own words about its set |
|---|---|---|---|
| `256.2` | step 256 §256.2, boxed | 9 | *"a **reconstruction target**, not a claim that all nine are already formally defined"*; §256.32 lists *"the nine candidate operations are the final operation set"* under **Not established** |
| `256.x` | step 256 §§256.19–.22 | 2 | `Validate: K × Evidence → ValidationResult` and `Approve: Candidate × Authority → ApprovedCandidate` — signed, and **outside** the transformation family (`Validate ≢ Transform`) |
| `257` | step 257 | 6 | the four discriminating operations, all still 🟡; plus `ExplainRevision` and `SupersessionHistory`, which appear in **no** enumeration |
| `259.7` | step 259 §259.7/.8 | 9 | *"the nine operations previously listed are **not exhaustive**"* |
| `272A` | step 272A §272A.14/.17 | 19 | *"the **candidate semantic operation universe**"*; §272A.27: *"`O_core = O_sem^candidate` has **not yet been fully proven**"* |
| `277` | step 277 §277.2/.3 | 22 | the reconstructed historical inventory |
| `277.T` | step 277 §277.31 | 6 | *"`𝒯_candidate ≠ 𝒯_minimal` because the deletion/composability experiments have not yet been completed"* |
| `249` | step 249 §249.4/.19 | 10 | `Remove = UNDEFINED at signature level`; `Merge/Supersede/Split/Reject/Replay = OPEN` |
| `250` | step 250 §250.3 + .11/.12 | 17 | `Merge/Split/Withdraw/Commit = OPEN`; `Supersede/Accept/Reject/Infer = UNRESOLVED` |
| `232` | step 232 §232.3 + CQRS | 11 | *"these operations do not all have the same mathematical character"*; `Reject` is named once and **never defined again** in 1821 lines |
| `algD` | `KNOWLEDGE-STATE-ALGEBRA.md` §2 | 14 | *"**OPEN** — twelve were **mandated** and audited; completeness is not proven"* |
| `algE` | `TRANSFORMATION-CANONICAL-MODEL.md` §2 | 5 | five operations + `noop`, fully instantiated |
| `oderive` | `02-OPERATION-UNIVERSE.md` + `exec/oderive.py` | 14 | *"which of the 4 band members are mandatory: **NOT derivable → D-1**"* |
| `274` | step 274 §274.6/§274.30 | 10 | introduces **`Contest`** and **`QualifyEvidence`**; *"every cell of the closure matrix is `?`… Do not use 'yes' merely because an operation sounds reasonable"* |
| `276` | step 276 §276.4 | 27 | **six** families `O_S ∪ O_E ∪ O_H ∪ O_G ∪ O_Q ∪ O_X`, and `O_X = {Save, Load, Serialize, Deserialize, Delete}` **as members**; boxed `O_G is not uniformly computationally demonstrated` |
| `281x` | `step-281/exec/test_repair_selection.py` | 9 | what the **executed harness** calls *"the mandatory operation set"*: `assert, relate, retract, merge, replay, validate, assess, contradicts, supersede` — `contradicts` appears under that name nowhere else |
| `hand` | `handoff/02` | 1 | `relate` — *"277's `Supersede` covers one relation type; the general case is unlisted"* |

### 1.3 The membership matrix `EMPIRICALLY TESTED`

`n` = how many of the 14 sources name the operation. `-` = explicitly EXCLUDED by that source.
Full matrix in `exec/OUT-inventory.txt`; the distribution is the finding:

| n (of 17 sources naming it) | count | operations |
|---|---|---|
| 13 | 2 | `Merge`, `Supersede` |
| 12 | 1 | `Validate` |
| 10 | 1 | `Replay` |
| 9 | 1 | `Split` |
| 8 | 3 | `Assert`, `Reject`, `Retract` |
| 7 | 1 | `Assess` |
| 6 | 2 | `Remove`, `Revise` |
| 5 | 3 | `Add`, `Authorize`, `Transform` |
| 4 | 4 | `Qualify`, `Query`, `Relate`, `Withdraw` |
| 3 | 6 | `Approve`, `Compare`, `LinkEvidence`, `Refute`, `Support`, `Trace` |
| 2 | 9 | `ChangePolicy`, `Create`, `Derive`, `Evaluate`, `Explain`, `Infer`, `LineageQuery`, `Promote`, `ProvenanceQuery` |
| **1** | **24** | `Accept`, `Apply`, `Commit`, `Contest`, `DetectContradiction`, `Determine`, `Equal`, `ExplainRevision`, `Identity`, `Observe`, `QualifyEvidence`, `Reassess`, `Refine`, `Reintroduce`, `Resolve`, `SupersessionHistory`, `contradicts`, `noop`, `resolve` — plus the five representation names `Delete`, `Deserialize`, `Load`, `Save`, `Serialize`, which are *excluded* by two sources and *included* by one |

> **Corroboration and necessity point in opposite directions — a result worth stating here.**
> The four best-corroborated names in the whole corpus are `Merge` (13 sources), `Supersede` (13),
> `Validate` (12) and `Replay` (10). The executed necessity test finds **`Merge` and `Supersede`
> necessary for nothing**, `Validate` necessary only through a capability whose formulation is
> PROPOSED, and `Replay` necessary only through a capability that is not in the ratified surface at
> all (`MINIMALITY-RESULT.md` §4.1). **Frequency across the research record is not entailment from
> the ratified surface.** `EMPIRICALLY TESTED`.

**24 of the 57 live names — 42 % of the candidate universe — rest on a single source.** Under acceptance standard
**C-7** — *"a member whose source class cannot be named is a proposal, not a member"* — each has a
nameable source, so each is admissible **as a proposal**; none has corroboration inside the
corpus.

### 1.4 Where the sources disagree `EMPIRICALLY TESTED`

The script compared all 45 pairs among the ten enumerations that *claim* to be an operation
universe. Verbatim from `exec/OUT-inventory.txt`:

```
NO TWO ENUMERATIONS AGREE.

  256.2     |O| =  9        274       |O| = 10
  259.7     |O| =  9        276       |O| = 27
  272A      |O| = 19        281x      |O| =  9
  277       |O| = 22
  277.T     |O| =  6        UNION     |O| = 57  <- the complete candidate universe
  249       |O| = 10        INTERSECTION of all claiming enumerations = 0  (EMPTY)
  250       |O| = 17
  232       |O| = 11
  algD      |O| = 14
  oderive   |O| = 14
```

> **There is not one operation name that all thirteen claiming enumerations contain.** The
> intersection is **empty**. A handful of pairs stand in a subset relation (`256.2 ⊂ 250`,
> `249 ⊂ 250`, `277.T ⊂ 277`, `277 ⊂ 276`); every other pair is **incomparable** — each names
> something the other omits. Highest pairwise agreement is `249 vs 232` at Jaccard 0.75; lowest
> is `277.T vs oderive` at 0.05.
>
> **And the one exclusion the corpus appeared to agree on does not hold either.** Executed:
> ```
>   representation ops ['Deserialize', 'Load', 'Save', 'Serialize']
>     EXCLUDED by : ['272A', '277']
>     INCLUDED by : ['276']
>   -> AGREEMENT HOLDS: False
> ```
> 272A §272A.13 calls their earlier inclusion *"an important correction"* and removes them;
> 277 §277.33 keeps them out; **step 276 §276.4 puts them back in, as the sixth family `O_X`** —
> and none of the three cites the others. `EMPIRICALLY TESTED`.

**The eight structural disagreements**, each read out of the sources:

| # | Disagreement | Evidence |
|---|---|---|
| D1 | `LinkEvidence` exists in 277, does not exist in 272A | 277 §277.14 splits `Support_relation(p,e)` from `LinkEvidence(K,p,e)`; 272A never names `LinkEvidence` and keeps `Support`/`Refute` **as operations** |
| D2 | `Split` is a core candidate in 277, **absent from 272A entirely** — omitted, not rejected | 277 §277.3 "Split … Core candidate: **Yes**" |
| D3 | `Infer` is core in 272A, **absent from 277**, and `UNRESOLVED` in 250 | 272A §272A.4.4 `Infer ∈ O_core`; 250 §250.6 *"`Infer` should not yet be assigned `K→K`"* |
| D4 | `Replay`: **REQUIRED** (272A §272A.24) vs **derived fold** (277 §277.16 `Replay(K₀,H)=fold(T,K₀,H)`) vs **OPEN, equality-dependent** (249 §249.14) | three positions on one name |
| D5 | `Trace`/`Query`/`Compare`/`Identity`/`Equal`: inside `O_sem` and **REQUIRED** (272A) vs `Trace ∉ 𝒯_K`, `Query ∉ 𝒯_K`, merely "Supporting" (277 §§277.17–.18) | neither document performs the available reconciliation |
| D6 | `DetectContradiction`, `Resolve`, `Identity`, `Equal`, `Assess`, are in 272A and **wholly absent from 277's 27-name inventory** — and 272A is the **later** artifact (22:42 vs 22:01) | the two lists were never reconciled |
| D7 | `Authorize`/`Validate` are **inside** `O_sem` (272A §272A.14) and simultaneously **"REQUIRED EXTERNAL"** (272A §272A.24) — an inconsistency *within one document*; 277 §277.33 puts governance unambiguously outside | |
| D9 | **`Reject` appears in four incompatible roles**: an epistemic disposition on an item (256.11), a governance transition classed **External** (277 §277.3, 276 `O_G`), a decision-producing function `Reject: Assessment × Authority × Policy → Decision` with verdict `UNRESOLVED` (250 §250.9), and *"listed but effectively abandoned"* (249 §249.13 on step 232) | four readings, one name |
| D10 | **The representation ops are excluded by 272A and 277 and INCLUDED by 276** — see the box above | executed |
| D8 | **The largest gap.** `Transform`, `Add`, `Revise` are 249/250's **ESTABLISHED** state operations (`Transform: 𝕂×Parameters→𝕂` "CORPUS ESTABLISHES"; `Add: 𝕂×X⇀𝕂` "CORPUS ESTABLISHES"). They **vanish entirely** from 272A and 277, replaced by `Assert`. **No document states that `Assert` supersedes `Add`, or that `Transform` was demoted.** | the two latest kernels contain none of the three operations the two earlier passes marked *established* |

**Four name collisions** — the same token carrying demonstrably different semantics:

| token | sense (1) | sense (2) |
|---|---|---|
| `resolve` | `algD`: add a `resolves` **edge** — a relation constructor | `272A`: **resolve an unresolved epistemic problem** (`O_E`) |
| `Create` | `250 §250.4`: *"`Create ≠ necessarily Add`"* — a domain **lifecycle** transition | `algD`: produce the **empty state**, output `∅` |
| `Reject` | `256.11`: `Status(x): Proposed → Rejected` — an **epistemic** disposition on an item | `277 §277.3`: *"Reject | Governance transition | Governance state | External"* |
| `Transform` | `256.7`/`249`: the generic state transformer `K × Params → K` | `250 §250.23`: **three** distinct senses — epistemic / state / representation, and *"cannot yet be a canonical ubiquitous-language term without qualification"* |

**Exclusions — the corpus reaches no joint closure at all.** `Serialize`, `Deserialize`, `Save`,
`Load` are excluded by 272A (§272A.13/.15) and 277 (§277.33) as representation, not semantics, and
`Delete` as infrastructure/administrative — **and step 276 §276.4 includes all five as the family
`O_X`.** `Command` and `Event` are excluded by 256 (§256.21/.22, `Event ≠ T`) and 232. So even the
exclusion side is contested. `EMPIRICALLY TESTED`.

**Four candidate members have no body at all** — the corpus names them as required and supplies no
definition: `Qualify` (`THEORY-GAP-REGISTER` TG-14, BLOCKING: *"`Qualify` has NO BODY"*) ·
`Authorize` (*"formal only — runtime absent"*) · `dedup` (TG-11: *"UNDEFINED — no such operation
exists"*) · `ChangePolicy` (no signature, no authority guard, no runtime). **A registry listing
these as members is listing oracles.** `DERIVED`.

**One required operation is entirely missing.** Adjudication of `CONFLICTED` — *"ABSENT, no
signature at all"*, re-confirmed three times in the findings register: **there is no corpus
operation that exits the CONFLICTED state.** Yet Art. 8, ratified through FA-1 D-FA-1, holds a
CONFLICTED item suspended *until a governed resolution* — so **A10 is a ratified-forced capability
with no corpus operation to realise it.** This is a gap in the candidate universe, not in the
ratified architecture. `DERIVED`.

**Two forced STRUCTURES that are not operations.** `02-OPERATION-UNIVERSE.md` §3 reports
`⟨D_t⟩` (the recognised-dimension set, forced by `UNKNOWN ≠ ABSENT`) and `⟨serialize⟩` (a
canonical form, forced by `Expression ≠ Meaning`, the corpus's most-repeated law at 71
occurrences). Neither is an operation and neither is treated as one here.

### 1.5 The two-sided gap in the forcing account `EMPIRICALLY TESTED`

```
forced by oderive yet ABSENT from 256.2 u 259.7 : Derive, Determine, Qualify
named in 256.2 u 259.7 yet forced by NO law     : Merge, Reintroduce, Split, Transform
```

`Determine` is the sharp case: `Determination ≠ Decision` is a corpus law repeated 19 times, and
the canonical model **names the transition** (v0.2 carries v0.1's *Determination* concept row
forward unamended) while **no enumeration names an operation for it**. Conversely `Transform` is
the corpus's most-used operation — present in eight of nine kernel phases — and **no non-collapse
law entails that it must exist**. Centrality is not mandatoriness.

### 1.6 Closure claim for Task 1

**The candidate universe is claimed CLOSED with respect to the fourteen sources inventoried, and
NOT claimed closed absolutely.** `DERIVED`. Two of the fourteen sources (257's `ExplainRevision`
and `SupersessionHistory`) were themselves absent from every prior enumeration, which is direct
evidence that a fifteenth reading could add a fifteenth name. Any claim of absolute closure over
a 688-file historical corpus would exceed the evidence.

---

## 2 · TASK 2 — the mandatory-capability set, derived from the ratified surface

**Derivation rule, stated so it can be attacked.** A capability is **mandatory** iff the
**ratified or authorized** surface either (a) asserts a law that quantifies over it, so that
without the capability the law is vacuous, or (b) contains it as a node of the canonical flow.
The capability set is derived **from the ratified invariants and flow, and from no candidate
list** — deliberately, for the reason established next.

### 2.1 Why the requirement set may not be taken from any candidate list `FORMALLY SHOWN`

Executed: `exec/circularity.py`, raw output `exec/OUT-circularity.txt`.

The necessity criterion is stated twice in the corpus, over two different index sets:

- **step 272A §272A.16** — `o ∈ O_core ⟺ ∃d ∈ D_mandatory : Remove(o) ⇒ Loss(d)`
- **step 277 §277.30** — `o is primitive ⟺ ∃r ∈ R_mandatory : r ∉ Closure(𝒯₋ₒ)`

`R_mandatory` is **never enumerated anywhere in step 277**. `D_mandatory` **is** enumerated —
272A §272A.17's 19-row Mandatory Distinction Register. Executed over it:

```
rows in 272A's D_mandatory register        : 19
distinct distinctions                     : 19
distinct required operations              : 19
map distinction -> operation is INJECTIVE : True
operations serving more than one distinction : 0
distinctions with more than one realizer     : 0

      |O_core| = 19  =  |O_sem| — ALL 19 'necessary'
```

> **The map is a bijection.** Every row of the register lists exactly one operation, and every
> operation appears in exactly one row. So for every `o` there is exactly one `d` whose only
> listed realizer is `o`, removing `o` loses `d`, and the criterion returns *all 19 necessary* —
> **by construction**.
>
> This is not a derivation; it is a **restatement**. The register was written by reading an
> operation off each distinction, so the necessity test over it is a **tautology**: it can
> eliminate no member and discover no member its author did not already name.

`FORMALLY SHOWN`. The criterion is informative **only** over a requirement set derived
independently of the operation vocabulary — which is why the set below comes from the ratified
invariants and flow.

### 2.2 The derived mandatory-capability set `R_A` `DERIVED`

Fifteen capabilities. Each row cites the ratified/authorized element that forces it, and carries
its own grade. **None is an operation definition.**

| id | capability the ratified surface requires | forced by | grade |
|---|---|---|---|
| **A1** | move an item from `Candidate` to `Supported` | the ladder + **I-12** — without a traversable first rung, `Supported` is unreachable and the ladder is vacuous | RATIFIED-FORCED |
| **A2** | make a **Determination**: `Supported → Accepted` under an **in-force** AcceptancePolicy | the ladder + **I-12** + v0.2 §1's *Determination* concept row (carried forward unamended from v0.1) | RATIFIED-FORCED |
| **A3** | attach `Committed` to an **Accepted** item **by an authority act** | **A6 / I-4** — *"authority determines commitment, not evidential truth"*; v0.2 R-3 re-typed `Committed` as a decision-boundary status | RATIFIED-FORCED |
| **A4** | move an **in-force** policy from version *n* to *n+1* **through a governed, versioned approval decision** | **I-11** + v0.2 §3's stratification loop, which is a *flow* the authorized model contains, not merely a prohibition | RATIFIED-FORCED |
| **A5** | compose evidence so that **duplicates do not amplify**, **independent corroboration does**, and **dependency resolution comes first** | **I-5 + I-6** — the only **TESTED** invariant pair in the whole model | RATIFIED-FORCED |
| **A6c** | compute the gap between state and requirement, **retaining its type** — `unknown / conflicting / missing / invalid`, never collapsed to Boolean | `Zero(K, EC)` with `EC = η(G, IdealState)` (v0.2 R-2) + **I-9** | RATIFIED-FORCED |
| **A7** | turn a **source observation** into **evidence** | `SourceObservation ≠ SemanticObservation` and the *Evidence* concept row; the distinction is vacuous if the transition is unreachable | RATIFIED-FORCED |
| **A8** | reach `REJECTED`, with the item **still present** | **FA-1 §2 / D-FA-1 (RATIFIED, GN-31)**, Constitution Art. 7 — *"terminal, never deleted"* | RATIFIED-FORCED |
| **A9** | reach `CONFLICTED` | **FA-1 §2 / D-FA-1**, Art. 8 — *"governed suspension"* | RATIFIED-FORCED |
| **A10** | leave `CONFLICTED` **by a governed act** | **FA-1 §2 / D-FA-1**, Art. 8 — *"until governed resolution"* | RATIFIED-FORCED |
| **A11** | recognise a **dimension** while its value is **UNKNOWN**, distinguishably from the dimension being **ABSENT** | Art. 9 + **I-9**, carried into the ratified architecture by FA-1 §2's evidence layer (`UNKNOWN ≠ ABSENT ≠ FALSE`) | RATIFIED-FORCED |
| **A12** | record a **proposal** that carries **no authority** | **I-2** + v0.2 §3's *PROPOSAL (selector, no authority)* flow node | RATIFIED-FORCED |
| **A13** | evaluate a **decision** for admissibility against `DC(d) = (Pre, Inv, Auth, Post, Temporal, Evidence)` | 042's DC 6-tuple + **I-3**; the *capability* is flow-forced | RATIFIED-FORCED **capability** / **PROPOSED formulation** — *no ratified conjunction over exactly those six slots exists* (`OPERATION-CONTRACT-GAP` C-8). The conjunction used in the test is mine. |
| **A14** | act under authorization, and observe the result | v0.2 §3's action loop | **PROPOSED** — the far side of the decision interlock is **OPEN BY RULING (OQ-4)**; only the boundary is settled |
| **A15** | fold the recorded history back to state (replay) | `State ≠ History`, a corpus law at weight 12 | **PROPOSED** — this law is **not in the ratified surface**; and replay is undecidable without a state-equality rule the canon does not supply |

**13 RATIFIED-FORCED · 2 PROPOSED.** The test in `MINIMALITY-RESULT.md` is run over both the
13 and the 15, and the difference is reported.

### 2.3 What this set adds to the governed capability list — a finding `DERIVED`

The governed synthesis artifact `analysis/OPERATION-CONTRACT-GAP.md` §A records **nine**
required capabilities (C-1…C-9) and states, correctly, *"Nine capabilities are canonically
required. Zero operations are canonically defined."* Re-deriving independently from the ratified
surface yields **fifteen**. The correspondence:

| `OPERATION-CONTRACT-GAP` | this derivation |
|---|---|
| C-1 move an item one rung up | **A1** (Candidate→Supported) **and A2** (Supported→Accepted) — the two rungs have **different** ratified gates: only A2 is gated on an in-force AcceptancePolicy. Treating them as one capability hides that gate. |
| C-2 attach `Committed` by an authority act | **A3** |
| C-3 make a Determination | **A2** (merged with C-1's second rung, per the row above) |
| C-4 change an in-force policy version | **A4** |
| C-5 compose evidence | **A5** |
| C-6 compute the gap | **A6c** |
| C-7 observation → evidence | **A7** |
| C-8 evaluate a decision contract | **A13** |
| C-9 act, and observe | **A14** |
| — **not in the governed list** | **A8** reach REJECTED · **A9** reach CONFLICTED · **A10** governed resolution · **A11** UNKNOWN ≠ ABSENT · **A12** authority-free proposal · **A15** replay |

> **Six capabilities the ratified architecture forces are missing from the governed capability
> list.** Four of them (A8, A9, A10, A11) come from **FA-1 §2 / D-FA-1**, which is **RATIFIED**
> (GN-31) — the layered state model that adds REJECTED, CONFLICTED and the
> `UNKNOWN ≠ ABSENT ≠ FALSE` evidence layer beside the untouched ladder. A registry built to the
> nine-capability list alone would be **unable to express rejection, conflict, governed
> resolution, or the difference between an unknown value and an absent dimension** — every one
> of which is ratified.
>
> This is reported as a **finding against the governed capability inventory**, not a correction
> to it: `OPERATION-CONTRACT-GAP.md` is a governed artifact and is not modified here. `DERIVED`.

### 2.4 What the ratified surface does NOT force

Recorded so that nothing is smuggled in: the ratified surface forces **no** capability for
`Merge`, `Split`, `Reintroduce`, `Withdraw`, `Revise`, `Supersede`, `Infer`, `Trace`, `Query`,
`Compare`, `Identity`, `Equal`, `Explain`, `Evaluate`, `LineageQuery` or `ProvenanceQuery`.
Their absence from `R_A` is **not** a claim that they are unnecessary; it is the statement that
**no ratified law or flow node quantifies over them**, so their necessity cannot be tested
against the ratified surface. `DERIVED`.

---

## 3 · TASK 8 — consistency of every candidate operation with the ratified constraints

Executed by `exec/consistency.py`; raw output `exec/OUT-consistency.txt`. Exhaustive:
**55 operations × 21 probe states × 18 arguments = 20 790 applications**, every one checked
against every constraint below, plus three adversarial probes that are deliberately **not** pool
members.

### 3.1 Constraint-by-constraint result

| ratified constraint | executed result |
|---|---|
| **A6 / I-4** — an authority act crosses the Accepted→Committed boundary; **evidence never does** | **HOLDS for all 55 pool members** — and **VIOLATED by one candidate rule the corpus states**. See §3.2a. |
| **I-11** — no in-force policy changes without a governed, versioned approval | **HOLDS** for the in-force policy *record*: operations that changed it off-route: **0**. The probe `RevisePolicyDirect` returns `REJECTED(authority)`. **One ambiguity surfaced** — see §3.3. **Independently registered gap:** `NEXT-FOUNDATIONAL-GAP-AUDIT` G-P1 — *"`Policy` has no author, no owner, no authorising act… An unauthorised policy change silently re-authorises the entire history."* The only corpus operation that changes a policy, `ChangePolicy` (276 `O_G`), has **no signature, no authority guard and no runtime**. |
| **I-5** duplicates must not amplify | **HOLDS, EXECUTED.** `{e1} → grade ['s1']`; adding the duplicate `e1dup` (same source class) → `grade ['s1']` — unchanged. |
| **I-5** independent corroboration must amplify | **HOLDS, EXECUTED.** adding `e3` (independent source class) → `grade ['s1','s3']` — strictly greater. |
| **I-6** dependency resolution precedes aggregation | **HOLDS, EXECUTED.** adding `e4dep`, whose dependency is unresolved → `grade ['s1','s3']` — the unit is **excluded**, not down-weighted. |
| **OQ-3 untouched** | **HOLDS.** No aggregation operator was selected. The grade is a **set of independent source classes**, so I-5/I-6 are satisfied *structurally*. OQ-3 remains open by ruling. |
| **I-9** — Zero's four-way typology must not collapse to Boolean | **HOLDS.** Violations: 0. Every emitted gap value is drawn from `{unknown, conflicting, missing, invalid, satisfied}`. |
| **I-2** — the proposal selector holds no authority | **HOLDS.** Violations: 0. |
| **Art. 7** — REJECTED is terminal-preserved, never a deletion | **HOLDS.** Violations: 0. |
| **I-12** — covering relation; skipping formally excluded | **VIOLATED by 2 operations** — see §3.2. |
| **Art. 8** — CONFLICTED is left only by a governed act | **VIOLATED by 1 operation** — see §3.2. |

### 3.2a · A6 — the one candidate rule that crosses the boundary on evidence `EMPIRICALLY TESTED`

The adversarial state carries **one `Accepted` item, 44 independent supporting evidence units, and
no authority act**. Verbatim from `exec/OUT-consistency.txt`:

```
--- A6 ADVERSARIAL: can evidence volume cross the boundary? ---
  state: 1 Accepted item, 44 independent supporting evidence units, NO authority act
  operations that produced Committed : 0  -> A6 HOLDS: evidence volume never crosses
  PROBE CommitByEvidence          outcomes: ['REJECTED(authority)', 'REJECTED(structural)']
  PROBE RevisePolicyDirect        outcomes: ['REJECTED(authority)', 'REJECTED(structural)']
  PROBE CommitBySufficientSupport outcomes: ['*** CROSSED A6 ***', 'REJECTED(structural)', 'ok']
```

**V-0 · `Commit` as step 025a-2 §36 states it VIOLATES A6.** That rule is:

> `Commit(A)` only if `Relevant ∧ TemporallyValid ∧ SufficientSupport ∧ NoBlockingConflict ∧ ProvenanceAvailable`

**Five conjuncts, no authority conjunct**, and `SufficientSupport` is evidence-derived. Implemented
verbatim as `PROBES["CommitBySufficientSupport"]` in `exec/rm.py`, it **crosses the
Accepted→Committed boundary on evidence alone**. The rule carries its own caveats in the corpus
(*"experimental, not final"*, and `SufficientSupport` is flagged *"circular pending aggregation
algebra"*) — but it is the one operation rule in the candidate material that would, if adopted,
violate the ratified A6. `EMPIRICALLY TESTED`. **Recommendation: excluded, with this reason.**

**A methodological note that matters.** The record's strongest cited witness for A6 —
the *"10⁶ evidence, no authority act → not committed"* run — was **WITHDRAWN as evidence** by
`handoff/06`, because `evidence_volume` was *"declared in `commit()` and never referenced in the
body"*: the test passed on `authority_act is None` and would have passed identically had the law
been false. The test above is not that test: it ranges over **55 independently specified
operations** and it **found a crossing** — which is the behaviour a non-tautological test must be
capable of. It remains **model-relative**: it establishes that no pool member *as specified by its
corpus signature* consults evidence in a Committed guard, and that one corpus-stated rule does.
`EMPIRICALLY TESTED`, scoped.

### 3.2 Candidate operations that VIOLATE a ratified constraint `EMPIRICALLY TESTED`

Verbatim from `exec/OUT-consistency.txt`:

```
Art.8 — 1 distinct violations, 1 operations
  Reject                 CONFLICTED -> Rejected with NO governed act

I-12 — 4 distinct violations, 2 operations
  Reject                 Conflicted -> Rejected
  Split                  creates an item directly at Accepted — entry above the first rung
  Split                  creates an item directly at Conflicted — entry above the first rung
  Split                  creates an item directly at Supported — entry above the first rung
```

**V-1 · `Split` violates I-12.** `Split` as the corpus specifies it
(`Split: K × x → K'`, `Split(x) = {x₁,…,xₙ}`, 256.10; `Split: K ⇀ K₁ × K₂`, 232.13) produces
**new items carrying the parent's status**. When the parent is `Supported`, `Accepted` or
`Conflicted`, the products enter the state **above the first rung**, with no predecessor of their
own. I-12 excludes that by construction: a status is reachable only from its immediate
predecessor, and a freshly created item has no predecessor at all.

This is a **third**, independent problem with `Split`, on top of the two already on the record:
it is **lossy on ℛ** (`KNOWLEDGE-STATE-ALGEBRA.md` §4, executed counterexample; `Split(Merge(K₁,K₂)) ≠ (K₁,K₂)`, 232.13) and it is **forced by no non-collapse law**
(`02-OPERATION-UNIVERSE.md` §4.2). `EMPIRICALLY TESTED`.

*The repair is a normative choice and is not made here.* The three candidate repairs — products
enter at `Candidate`; or products inherit the parent's status and I-12 is amended to admit
splitting; or `Split` is excluded from the registry — are **not interchangeable**, and the second
would require amending a ratified invariant. **`NORMATIVE DECISION REQUIRED` — named, not made.**

**V-2 · `Reject` violates I-12 and Art. 8.** `Reject` as 256.11 specifies it
(`Reject: K × x × Reason → K'`, `Status(x): Proposed → Rejected`) carries **no guard on the
source status**. Applied to a `CONFLICTED` item it moves `Conflicted → Rejected` with **no
governed act** — and Art. 8, ratified through FA-1 D-FA-1, holds a CONFLICTED item suspended
*until a governed resolution*. Rejection is not a governed resolution unless it is routed as one.

*This is exactly the collision `inventory.py` recorded as a name collision:* 256.11 treats
`Reject` as an **epistemic disposition on an item**, while 277 §277.3 classes it as a
**governance transition, external**. Under the 277 reading the guard exists; under the 256
reading it does not. **`NORMATIVE DECISION REQUIRED`: is `Reject` an epistemic act or a
governance act? The two readings differ in whether Art. 8 binds it.** Named, not made.

### 3.3 One MODEL-DETECTED AMBIGUITY — not a violation `DERIVED`

```
I-11/R-1 — 3 operations
  Assess / Reject / Withdraw   mutates POLICY-AS-CONTENT while a policy record of the
                               same id is IN FORCE — legal under R-1 taken literally, yet
                               the canon never binds the in-force record to its content item
```

v0.2 R-1 stratifies `Policy-as-content ∈ K_t` (*"admissible like any claim"*) from
`Policy-in-force (versioned)` (*"the governor of admission"*), and I-11 protects only the second.
Taken literally, any operation may act on the content item. But the ratified text **never states
the binding** between an in-force policy record and the content item it governs by. If they are
bound, mutating the content silently changes what is in force, which is what I-11 exists to
prevent; if they are not bound, the in-force policy's content has no location in `K_t` at all.

**`NORMATIVE DECISION REQUIRED`: does an in-force policy version reference a content item in
`K_t`, and if so is that item frozen while the version is in force?** Named, not made. This is
**adjacent to GC-1 but is not GC-1**: it does not ask which of the two policy-loop terminations
stands. The commission's ratified constraint (I-11 / R-1, the *ratified* termination) is the one
applied throughout, per `ES-005.4`; GC-1 is left where the governance lane holds it, untouched.

### 3.4 Operations whose semantics depend on something UNRATIFIED — surfaced, never relied on

Required by the commission's constraint *"surface, never rely on, any dependence on unratified Σ
or Q_t assumptions."* The reference model uses **only the ratified statuses**; Σ appears nowhere
in the state core, and the evidence grade is a set of source classes rather than a Σ value (M4).
The dependencies that would otherwise have been imported:

| candidate operation | unratified thing it needs | evidence |
|---|---|---|
| `Assess` | **Σ as its codomain.** 272A §272A.21: `Assess: Evidence × Context → Σ`. **Σ is not ratified and the corpus does not agree on it**: 272B derives `Σ_min = 𝒫({Support,Refute}) ≅ {0,1}²` — **four** values; `DECISION-SIGMA-EPISTEMIC-STATUS.md` derives **three** (`Unknown · Supported · Refuted`); 275 derives `Σ = (D,S)`; Q14's `Σ=(A,S,R,V,C)` was **REFUTED**. `handoff/03` records the author's own code returning **four** while the author's own prose said **three**. | the test avoids Σ entirely; A5's goal is a set of source classes |
| `Retract` | **Σ for its very semantics.** 277 §277.10 offers Model A (destructive removal) and Model B (`p: Σ_old → Σ_new`), and prefers **Model B** — which makes `Retract` a Σ-transition, hence unusable until Σ is ratified. | recorded; the model implements Model A and says so |
| `DetectContradiction` | **`Conflict` as `D=(1,1)`** — a 272B construct. 272A §272A.8 separately insists `Contradiction ≠ EpistemicStatus`. | the model reaches the **ratified** `CONFLICTED` state instead |
| `Replay`, `Compare`, `Equal`, `Identity` | **a state identity / equality rule.** Absent from the entire corpus — not even declared open. Every algebraic law in `KNOWLEDGE-STATE-ALGEBRA.md` §4 (commutativity, associativity, idempotence, the join-semilattice) is an **equation between knowledge states** and is therefore **unverifiable as written**. | M1 supplies a model-local structural equality and marks it PROPOSED |
| `Query`, `ExplainRevision`, `Explain`, `Evaluate` | **`Q_t`** — no question/query structure is ratified anywhere, and `Q_t` appears in **none** of steps 249/250/256/257/259/272A/277 | recorded; these are reads and appear in no witness |
| `Decide` | **a conjunction over the six DC slots.** No ratified conjunction over exactly those six exists. | A13's formulation is marked PROPOSED |
| `Act` | **OQ-4**, action/execution semantics, **open by ruling** | A14 graded PROPOSED |
| `Qualify` | **the qualification predicate**, undefined in canon | the model's predicate is declared model-local |
| `LinkEvidence` | **unreconciled** against the executed algebra's immutability invariant (`handoff/02`) | carried into the test *and* reported |
| `Split` | **unreconciled** — lossy on ℛ, needs a declared ℛ policy (`handoff/02`) | carried into the test *and* reported; see V-1 |

### 3.5 The three prerequisites (commission C-5) — status

| prerequisite | status |
|---|---|
| **A closed invariant register** | **DEFERRED, and it is a real blocker.** The canon asserts I-1…I-12 and FA-1's additions; **it nowhere asserts that these are all of them.** `TRANSFORMATION-CONTRACT-GAP.md` §C-3 states the consequence: *"'Preserves I-n' presupposes that the set of I-n is closed."* Independently, step 232 §232.39 proposes a **five**-invariant minimal candidate (Traceability · Semantic Accountability · Epistemic Separation · Governed Transformation · Historical Preservation) with no proof and no derivation of the discarded items — a *different* register again. Consequence: the "invariant preservation" field of every contract has **no codomain**, and is filled in `OPERATION-CONTRACTS.md` only against the enumerated ratified invariants, with the closure gap marked. |
| **Typed rejection semantics** | **DEFERRED; a model-local PROPOSED vocabulary was supplied so the test could run.** The canon defines none. The corpus has exactly one typed rejection — `TRANSFORMATION-CANONICAL-MODEL.md` §1, three kinds: `REJECTED(structure) | REJECTED(policy) | REJECTED(authority)` — and it is **insufficient**: step 232 §232.21 makes evidence an admissibility condition (`E(T) ⊨ ReqEvidence(T,P)`) that those three kinds cannot express, and I-12 legality is a fourth kind again. The model uses **five**: `structural · legality · policy · authority · evidence`. **`NORMATIVE DECISION REQUIRED`.** Further, no corpus document specifies (i) the state after a rejection, (ii) whether a rejection is recorded in history, or (iii) whether a rejected operation is a no-op or an exception. |
| **A state identity and equality rule** | **DEFERRED, and it is the deepest blocker.** Absent from the entire corpus. Step 257 §257.37 states it in the corpus's own voice: *"We still cannot formally decide `K=?` because Identity is not fully specified, Equality is not specified, EpistemicStatus is not frozen… **Therefore the correct next move is not to select a kernel.**"* The model uses structural equality (M1), PROPOSED. |

**On C-5 as a stop condition.** The commission says to stop if these three *"prove to be the
real blocker rather than a dependency."* The executed evidence is that **two of the three are
the real blocker for the CONTRACTS** (task 7) while **neither blocks the MINIMALITY TEST** (task
3), which is a reachability question and needs only a decidable state comparison, which M1
supplies. The test was therefore executed and delivered; the contracts are delivered
**partially**, with each unfillable field named. Both facts are reported rather than one
substituting for the other.

---

## 4 · Provenance table (commission C-7)

Every one of the 55 pool members carries `(source, evidence class)` in `exec/rm.py`'s `PROV`
dictionary. The classes used, and their populations:

Counted mechanically over `PROV` (`python3 -c "import rm; ..."`, 55 of 55 entries present):

| class | meaning | count |
|---|---|---|
| `CORPUS-NAMED` | named as an operation in at least one research artifact | **36** (incl. `Observe`, which is also RC) |
| `CORPUS-NAMED + SIGNED` | additionally carries a formal signature | **5** — `Assess`, `Transform`, `Authorize`, `Approve`, `Validate` |
| `EXECUTED-LANE` | appears only in the executed-algebra artifacts (`algD`/`algE`) | **3** — `Relate`, `Refine`, `noop` |
| `DERIVED-by-forcing-table` | present only via `oderive.py`'s hand-built `FORCES` table — see §0 | **3** — `Qualify`, `Determine`, `Derive` |
| `RC` — ratified-flow/state/object-forced | required by the ratified surface, and **not** corpus-named as an operation | **6** — `AssertPolicy`, `MarkConflict`, `Commit`, `EnactPolicyVersion`, `ComputeZero`, `Decide` (`Observe` is RC *and* corpus-named) |
| `PROPOSED` | forced only as a **structure**, not as an operation | **1** — `RecognizeDimension`, from `⟨D_t⟩` |
| `NON-CORPUS — model instrument only` | exists only to let a capability be tested independently of its intake route | **1** — `AdmitEvidence` |
| `UNRECONCILED` (a flag, not a class) | recorded against the executed algebra | **2** — `Split`, `LinkEvidence` |

**Under C-7, `AdmitEvidence` is not a candidate member at all** — it is a test instrument, and it
appears in no minimal registry reported in `MINIMALITY-RESULT.md`. `RecognizeDimension` is a
**proposal**, since its source class is a forced *structure* and not an operation.

---

## 4a · GN-81 acceptance checks (AC-1 … AC-6) — self-report

The HPA recorded six checks as **binding on acceptance**. Answered here, with the evidence:

| check | answer |
|---|---|
| **AC-1 · Candidate-universe completeness** — *"every operation candidate in the corpus, or only the convenient lists?"* | **17 live sources**, not the convenient three. Includes the two enumerations no prior pass had folded in (**step 274**'s `Contest`/`QualifyEvidence`; **step 276**'s six families with `O_X`), the **executed harness's own 9-op "mandatory operation set"**, both executed-algebra artifacts, and — counted separately per FA-1's L5 rule — **ten pre-canonical sets**. Live universe **57**; grand union **105**. Closure is claimed **only relative to the 17 sources**, and §1.6 gives the reason it cannot be claimed absolutely. |
| **AC-2 · Minimality actually executed** — *"was removal RUN, including combinations and interactions?"* | **Run.** `exec/mintest.py` computes per-capability closure by bounded reachability, then per-operation removal, then **all minimal sufficient registries exactly**, then **every pair and every triple over the non-necessary operations**. Raw output preserved. Where the node budget bound the search, it is stated per capability, and §6 of `MINIMALITY-RESULT.md` states **which way the bound biases the conclusion**. |
| **AC-3 · Hidden assumptions** | §3.4 lists every operation whose semantics need something unratified, and the reference model **uses none of them in the state core**: Σ appears nowhere (the evidence grade is a set of source classes); `Q_t` appears nowhere; identity/equality/replay are model-local and marked `PROPOSED` (M1); the DC conjunction is marked `PROPOSED`; OQ-3 and OQ-4 are untouched. `𝒪_core` and `𝒯_candidate` are **read as candidates and adopted nowhere**. |
| **AC-4 · Alternatives** | Every minimal registry found is **enumerated**, with the count, the intersection, the union and the undetermined band. **No selection is made**, and `MINIMALITY-RESULT.md` §5 states the multiplicity as the result rather than resolving it. |
| **AC-5 · Genuine independence** | This lane executed the derivation only. **No independent review is offered here**; C-8's reviewer must be a different fresh-context party that took no part in constructing the candidate registry. **This deliverable is not self-accepted.** |
| **AC-6 · ⚠ Entailment scrutiny** — *"which ratified object, invariant, governance rule or formally established requirement makes it necessary?"* | Every row of §2.2's capability table cites one, in that column, and **thirteen of fifteen cite a ratified or authorized element**. The two that do not (**A14** action-loop far side, **A15** replay) are graded **PROPOSED** on exactly that ground, are excluded from the primary run, and the sensitivity of the result to including them is reported. Additionally §2.1 shows **why the requirement set could not be taken from the research lane at all**: the corpus's own `D_mandatory` register is a **bijection** onto its own operation list, making the necessity test over it a tautology. §2.4 lists, explicitly, the sixteen corpus-named operations the ratified surface does **not** force — so that nothing enters by "the theory needs it." |

---

## 4b · Where the result is

Tasks 3–6 — the executed necessity test, the six minimal registries, the necessary core, and the
recommended terminal verdict — are in **`MINIMALITY-RESULT.md`**. Task 7 — the eleven-field
contracts — is in **`OPERATION-CONTRACTS.md`**.

In one line: **six minimal registries were computed; every one of them contains an operation whose
specification violates a ratified invariant; membership is not determined until ten named prior
canonical decisions are taken.** Recommended verdict: **D**. Recommended, not ratified.

---

## 5 · What this file does not do

No operation is named as canon · no membership is chosen · no granularity is selected · no
candidate is promoted · `GC-1` is untouched · `OQ-1…OQ-12` are unmoved · `OQ-3` and `OQ-4` are
untouched · Σ, `Q_t`, identity, equality, replay, measurement and the Evidence object are not
defined beyond what the test strictly required, and every such use is marked model-local ·
no ratified artifact was read for anything but constraint, and none was modified · the word
"validated" is not used of anything.
