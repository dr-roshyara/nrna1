# Proposed Extension Package — `dimension-registry.md`

**2026-09-07 · PROPOSAL ONLY.** Five proposed extensions, each in the mandated eight-field shape.

> ## ⛔ Nothing here has been applied.
> **`dimension-registry.md` is NOT modified.** It is another lane's governed artifact under the
> `three_model_convergence` two-stage protocol (`revisions.md`, `classification-register.tsv`).
> **Whether this lane may write there is a governance question and is not answered here.**
>
> **No definition is merged · no candidate adjudicated · no carrier chosen · `OQ-1` untouched.**

> ## ⚠️ RE-ADDRESSED 2026-09-07 — this package was aimed at the wrong layer
> Governance clarified that `three_model_convergence` is **read-only archaeology, not the owner of
> KnowledgeOS theory**. **`EXT-01` (`K`), `EXT-03` (`Implementation:`) and `EXT-04` (the three Gītā
> candidates) are EXTRACTION content and belong to
> [`docs/knowledgeos/theory-extraction/`](../../../../theory-extraction/00-CHARTER.md), not to
> `dimension-registry.md`.** `EXT-02` splits — glyph *occurrence* is archaeology, *are-these-one-concept*
> is extraction. `EXT-05` is an archaeology schema question for 3MC.
>
> **This dissolves the package's own closing question.** *"May this lane write to
> `dimension-registry.md`?"* — **it need not.** The proposals stand as content; only their destination
> changes. Nothing here is withdrawn.

---

## 0. 🔴 A correction to my own assessment of yesterday

I reported *"123 concept entries."* **Measured by section boundary:**

| section | lines | entries | kind |
|---|---|---:|---|
| **concept entries** | 1 – 3 389 | **27** | true concept-indexed records |
| **file landmarks** | 3 390 – 14 213 | **96** | `0483 — LANDMARK: …` — **file-indexed** |

$$\boxed{\textbf{The registry is 27 CONCEPT entries plus 96 FILE-LANDMARK entries in one document.}}$$

`[INF]` **The concept-level inventory covers 27 concepts, not 123.** After entry 27 (*"Summary — current
state as of file 0099"*) the artifact **changed indexing axis** from concept to file. That is a
structural observation about the registry, not a criticism — but it means **the concept coverage gap is
four times larger than I stated.**

⚠️ **And `Latest appearance` being absent is explained by it:** a file-indexed entry has no "latest",
because the file *is* the index.

---

## `EXT-01` — `K` : a **NEW** concept entry

| field | content |
|---|---|
| **existing entry** | 🔴 **NONE.** `K` has **no concept entry**. It appears only inside file-landmark headings (0503, 0508, 0518, 0528, 0548, 0589). **The theory's central object is the largest hole in its own concept registry** |
| **proposed addition** | one concept entry, `K`, with **definitions enumerated and NOT adjudicated** |
| **source evidence** | `V3` (`glyph-register/`) + this pass. **Ten definitions:** `K=(𝒜,ℛ)` · `𝒦=(K,H)` · `𝒦=(K,C,T,E,A)` · `𝕂=(Σ,M,B,D,O,δ)` · `𝒦=(E,S,T,O,P,R,Π,A)` · `𝕂={(G,σ,θ,λ,π)}` · `𝕂={admissible knowledge states}` · ratified `K_t` over 8 primitives · `K_t=(𝒜_t,ℰ_t,ℛ_t,𝒞_t,ℋ_t,Γ_t)` · ⭐ **`K(t)=(E,C,I,A,P,R,U,B,M,S)`** — a **ten-component** state at file **0518**, **found in this pass and not in `V3`'s nine** |
| **classification** | `[UN]` undefined / `[CT]` contradictory in the protocol's 13-value vocabulary — **the entry records a contested concept, not a chosen one** |
| **relationship** (MD-017) | **`unresolved_equivalence` for all ten pairs** — the protocol's default. **The one exception with evidence:** `K=(𝒜,ℛ)` and ratified `K_t` are related by `(𝒜,ℛ) =_semantic π_K(K_t)` — **`refinement`/projection, lossy, definable-not-computable**. Every other pair: **no evidence either way** |
| **implementation evidence** | ✅ `K=(𝒜,ℛ)` and ratified-primitive types are **implemented** — `verification/**/exec/` (54 `.py`, 5 723 LOC): `class K`, `Assertion(id,P,e,c,t,σ)`, `Proposition(entity,dimension,value)`, `Observation`, `Evidence`. 🔴 **The other eight have no code** |
| **unresolved questions** | which script (`𝒦` / `𝕂` / `K`) each definition belongs to — **undeclared and load-bearing** · whether `𝕂=(Σ,M,B,D,O,δ)` (2 files, **0 results**) is a rejection candidate · whether the ten-component `K(t)` at 0518 supersedes or parallels the others |
| **governance approval** | ⛔ **REQUIRED** — a new entry, and the concept is `OQ-1`'s object. **Adding the entry must not be read as progress toward the carrier decision** |

### The proposed definition block, in the shape specified

```
K   —  status: contested   ·   status_chain: candidate   ·   maturity: UNDEFINED

D-01  K   = (𝒜, ℛ)                              181 files · 71 with a result · IMPLEMENTED
D-02  K_t = {Entity,State,Event,Observation,
             Proposition,Relation,Policy,Action}  48 files · 17 with a result · IMPLEMENTED
D-03  𝒦  = (K, H)                                25 files · 12 · no code
D-04  𝒦  = (E,S,T,O,P,R,Π,A)                     16 files ·  9 · no code
D-05  𝒦  = (K,C,T,E,A)                           12 files ·  6 · no code
D-06  K_t = (𝒜_t,ℰ_t,ℛ_t,𝒞_t,ℋ_t,Γ_t)             7 files ·  5 · no code
D-07  𝕂  = {(G,σ,θ,λ,π)}                          5 files ·  4 · no code
D-08  K(t)= (E,C,I,A,P,R,U,B,M,S)                 file 0518    · no code
D-09  𝕂  = {admissible knowledge states}          schema, members undefined
D-10  𝕂  = (Σ,M,B,D,O,δ)                          2 files ·  0 results · no code

relationships:   D-01 ~ D-02   refinement/projection  (=_semantic π_K, lossy)   [evidence]
                 all other pairs                       unresolved_equivalence    [default]
```

⚠️ **The counts are citation measurements, not rankings of correctness.** A definition with few
citations may still be the right one.

---

## `EXT-02` — `Π` : extension of an **EXISTING** entry

| field | content |
|---|---|
| **existing entry** | ✅ **entry 10, `Provenance (contested — distinct from Evidence, or not?)`** · first appearance 0090 · maturity `semantic_definition` only · status **contested** · grounding **mixed** · recorded contradiction: *"whether Provenance is separate from Evidence Integrity is not settled anywhere in 0087-0092"* |
| **proposed addition** | ⭐ **a SECOND contested axis, orthogonal to the one recorded.** The registry contests *Provenance vs Evidence*. `V3` measures a different problem: **the glyph `Π` carries six meanings** |
| **source evidence** | `V3`: provenance (`id=H(P,e,c,t,Π)`, `Lineage`, **Decision 3: `Π ∈ ≡?`**) · **`Π=(Q,C,O)` the inquiry frame** · `Π = preservation contract` · `Π : 𝓡→O` the observable (Theory 01) · `Π_t` probabilistic representation · Policy. ⚠️ **plus `Π=(X,𝒯,ℰ,~_Q,Q,Π)` — a tuple containing `Π` as its own last component** |
| **classification** | `[CT]` contradictory — **but note this is a NOTATION collision, not necessarily a concept collision.** Whether the six are one concept or six is exactly what must not be assumed |
| **relationship** (MD-017) | **`unresolved_equivalence`** across all six. `[INF]` My reading is that they are **six different concepts sharing one glyph**, not six readings of one — **but that is an inference and it is not evidence** |
| **implementation evidence** | ✅ `Provenance` **is implemented** — `class Provenance(source, method, at)` in `verification/**/exec/kos_kernel.py`; `Item.source` in Carrier A; `provenance_state` in Carrier B (41 occurrences, the most frequent field in that estate) |
| **unresolved questions** | is the registry's *Provenance-vs-Evidence* question the same question as the glyph collision, or independent? · **is the self-containing tuple a transcription error or a genuine recursion?** — I do not know, and guessing is not my act |
| **governance approval** | ⛔ **REQUIRED** — one reading (`Π ∈ ≡?`, Decision 3) **carries the programme's central normative question**, so the annotation must not appear to resolve it |

---

## `EXT-03` — the `Implementation:` field, across entries

| field | content |
|---|---|
| **existing entry** | the field exists as prose in **65 places** but **only 18 reference code** across the whole registry |
| **proposed addition** | one structured `Implementation:` line per concept entry — `none · partial · <estate> · <class> · <file>` |
| **source evidence** | **three implemented estates, 168 `.py`** — **A** `verification/zero-algebra/` 59 (`Case`, `Rec(v,s,t,rank)`, `Rep(cls,items)`, `Item(…7 fields)`) · **B** `research/knowledgeos-sim/` 55 (`EVal`, `Arg`, `State`, `Boundary`) · **C** `verification/**/exec/` 54 / 5 723 LOC (`K`, `Assertion`, `Proposition`, `Observation`, `Evidence`, `Provenance`, `Op`) |
| **classification** | `[EX]` experimental — **an implementation fact, never a definition and never a decision** |
| **relationship** | **orthogonal to `Grounding:`.** `Grounding` answers *where the evidence came from*; `Implementation` answers *whether code exists*. ⚠️ **A concept can be `architecturally-grounded` with no code, and implemented while `philosophical-correspondence-only`. The two must not be merged** |
| **implementation evidence** | the field *is* the evidence |
| **unresolved questions** | does *implemented* mean **a type exists**, **an operation runs**, or **a result was produced on it**? — **three different claims**, and `EXT-05`'s problem in miniature |
| **governance approval** | ⚠️ **probably not** for adding an evidential field to existing entries — but **yes** for the schema change itself, since `machine-record-schema.md` is governed |

---

## `EXT-04` — `Yajña` · `Śraddhā` · `Kṣamā` : three **NEW** candidate concepts

| field | content |
|---|---|
| **existing entry** | 🔴 **NONE.** Measured over the concept section (lines 1–3 389): **0 occurrences each.** Also 0 in `02_model-a_gita/02_concept-register.md` |
| **proposed addition** | three entries, entering as **`candidate concepts discovered during independent corpus archaeology`** — the status the commission specified |
| **source evidence** | `gita-candidates/` T3 rows `N5`, `N6`, `N7`, from artifact `18`'s tiering of ~60 correspondences across 18 chapters. `Yajña → evidence aggregation` · `Śraddhā/Śaṅkā → trust · confidence · the abstention rule` · `Kṣamā → error tolerance` |
| **classification** | `[AN]` analogical — **all three are correspondence-only and UNEXAMINED against the corpus.** None has ever been tested |
| **relationship** (MD-017) | **`unresolved_equivalence`** with existing entries — `Yajña` plausibly touches entry 2 `Evidence Integrity`, `Śraddhā` entry 15 `Justification Type/Strength`, `Kṣamā` nothing obvious. ⚠️ **Plausibility is not evidence and no merge is proposed** |
| **implementation evidence** | 🔴 **none for any of the three** |
| **unresolved questions** | ⭐ **the interesting one: their absence is itself a test.** Two lanes independently identified the same three as unexamined — mine by tiering, the registry by not having them. **Either the extraction machinery is not corpus-complete, or these three genuinely have no corpus presence worth an entry.** ⚠️ **Absence from a 52 %-complete pass is not evidence of absence from the corpus** |
| **governance approval** | ⛔ **REQUIRED** — three new entries. And per the commission: **absence from the registry does not mean they belong in canonical theory**; they must run the same protocol as everything else |

---

## `EXT-05` — `Latest appearance` : **field semantics must be defined before population**

**Flagged, not proposed.** The commission is explicit that this field needs a meaning first, and the
four candidate readings are **not the same measurement**:

| reading | what it would measure | what it would say about `K` |
|---|---|---|
| **latest textual mention** | the newest file naming it | ~2026-09-07 — nearly meaningless, everything is mentioned |
| **latest refinement** | the newest file *changing* it | `K(t)=(E,C,I,A,P,R,U,B,M,S)` at 0518 — **or** `(𝒜,ℛ)=_semantic π_K(K_t)` at Step 285 |
| **latest implementation** | the newest code touching it | Carrier C's `kos_kernel.py` |
| **latest governed decision** | the newest ratified act | 🔴 **none — `K` has never been decided** |

$$\boxed{\textbf{For } K \textbf{ the four readings give four different dates, and one of them is EMPTY.}}$$

`[INF]` **That divergence is itself the argument for the field** — a concept whose latest *mention* is
today and whose latest *decision* is never is in a specific, nameable state. **But which reading the
field carries is a schema decision, and it is not mine.**

⚠️ `[REC]` **Carry all four as separate sub-fields rather than choosing one.** Offered as a
recommendation; **the four are not collapsed here.**

---

## Summary — what is being asked of Governance

| | proposal | kind | approval |
|---|---|---|:--:|
| **`EXT-01`** | `K` as a new concept entry, **10 definitions enumerated, none adjudicated** | new entry | ⛔ **required** |
| **`EXT-02`** | `Π` — a second contested axis on existing entry 10 | extension | ⛔ **required** |
| **`EXT-03`** | structured `Implementation:` from three estates / 168 `.py` | new field | ⚠️ schema change |
| **`EXT-04`** | 3 Gītā candidates, `[AN]`, unexamined | new entries | ⛔ **required** |
| **`EXT-05`** | `Latest appearance` — **4 readings, not collapsed** | schema question | ⛔ **decision first** |

**And the prior question, which none of the five may presume:**

$$\boxed{\textbf{May this lane write to } \texttt{dimension-registry.md} \textbf{ at all?}}$$

## What this package does not do

- **modifies nothing** — `dimension-registry.md` is untouched
- **merges no definitions** — all ten `K` readings and all six `Π` readings stay separate, at
  `unresolved_equivalence`, the protocol's own default
- **adjudicates no candidate** — every proposed status is `candidate` or `contested`
- **chooses no carrier** — `EXT-01`'s implementation evidence records *which definitions have code*;
  it is **not** an argument for ratifying any of them, and `OQ-1` is untouched
- **does not treat absence as evidence** — `EXT-04` states explicitly that absence from a 52 %-complete
  pass is not absence from the corpus
