# Conceptual Proposal Register

**2026-09-07.** Every conceptual amendment discovered in the two stress passes, recorded as a
**proposal only**.

> ## ⛔ NONE of these is applied
> **Only `B-6` was applied** (mechanical, charter-authorized) — see `04-SCHEMA` §3b and
> `09-B6-VALIDATION.md`. **Everything below is `[PROP]` or `[OPEN]`.**
>
> **Nothing adjudicated · nothing canonicalized · no carrier · no kernel · `OQ-1` untouched ·
> 3MC, `dimension-registry.md`, MD-017, MD-018 and the brainstorming corpus unmodified.**

**Status key:** `[PROP]` a plausible change with motivating evidence · `[OPEN]` an unresolved design
question with no proposed answer.
**Boundary key:** **LOCAL** to Theory Extraction · **CROSSES** the 3MC boundary.

---

| ID | proposal | status | boundary |
|---|---|:--:|:--:|
| `P-01` | `kind` attaches to the **definition**, not the record | `[PROP]` | LOCAL |
| `P-02` | epistemic **status** attaches to the definition | `[PROP]` | LOCAL |
| `P-03` | disposition **5 · `malformed`** | `[PROP]` | LOCAL |
| `P-04` | `selection: superseded` | `[PROP]` | LOCAL |
| `P-05` | level **`modelled-as-placeholder`** | `[PROP]` | LOCAL |
| `P-06` | **record-level vs reading-level identity** — a record may address a *glyph* | `[OPEN]` | LOCAL |
| `P-07` | kind **`value-set`** | `[PROP]` | LOCAL |
| `P-08` | kind **`register` / `interface`** | `[PROP]` | LOCAL |
| `P-09` | kind **`structure`** | `[PROP]` | LOCAL |
| `P-10` | relationship **`co-obligation`** | `[PROP]` | LOCAL |
| `P-11` | relationship **`supersession`** | `[PROP]` | **CROSSES** |
| `P-12` | relationship **`misattribution`** | `[PROP]` | **CROSSES** — escalated `06` |
| `P-13` | empty-field value **`not-searched`** | `[PROP]` | LOCAL |
| `P-14` | **comparison-arm implementations** — one candidate → rival definitions → separate implementations | `[OPEN]` | LOCAL |
| `P-15` | the four `Latest` fields may describe **different candidates** | `[OPEN]` | LOCAL |
| `P-16` | formalize the **implementation-scope exclusion** in the schema text | `[PROP]` | LOCAL |
| `P-17` | **titled-but-unplaced decisions** — how to record them | `[OPEN]` | LOCAL |
| `P-18` | **`revisit_required`** trigger + structured queue | `[PROP]` | LOCAL |
| `P-19` | **structural-diversity** pilot criterion | `[PROP]` | LOCAL |
| `P-20` | **anti-anchoring** safeguards `S-1`…`S-4` | `[PROP]` | LOCAL |
| `P-21` | **extraction manifest** + declared scope | `[PROP]` | LOCAL |
| `P-22` | **coverage measurement** + stopping criterion | `[PROP]` | LOCAL |
| `P-23` | **provenance ≠ methodological ≠ scheduling** dependency | `[PROP]` | **governance-facing** |
| `P-24` | **v3 backward-compatibility gate** on the eight records | `[PROP]` | LOCAL |

---

## `P-01` — `kind` attaches to the definition

**Originating:** stress-2 §B-1. **Evidence:** `KOS-T-0008` `Π` — its seven readings span `object`
(provenance), `structure` (`(Q,C,O)`), `operation` (the observable map) and `value-set`? (Policy);
`KOS-T-0009` `Zero` — five `relation` definitions plus one `constant` (`0_i ∈ 𝒟_i`).
**Change:** move `kind` from the record to each `D-nn`; the record carries a derived summary only.
**Why conceptual:** it re-addresses a field, changing what the schema asserts identity *of*.
**Status `[PROP]` · LOCAL.**

## `P-02` — status attaches to the definition

**Originating:** §B-2. **Evidence:** `Zero` — *`Zero`-as-a-state* and *`Zero`-as-a-missingness-
representation* are **`[RF]`** while four readings stay live, **in one record**.
**Change:** per-`D-nn` epistemic status; record status becomes a derived summary.
**Why conceptual:** a record could then be simultaneously `[RF]` and live, which changes the meaning
of the record's own status field. **Status `[PROP]` · LOCAL.**

## `P-03` — disposition 5 `malformed`

**Originating:** §B-3. **Evidence:** `Π` **R7** — `Π = (X, 𝒯, ℰ, ~_Q, Q, Π)`, **containing `Π` as its
own last component**. Fits none of the four dispositions: not a definition of `Π` (it presupposes
`Π`), not a different candidate (it names `Π`), not merely ambiguous, not a symbolic occurrence (it
is presented **as** a definition).
**Change:** add `5 · malformed / ill-formed as presented`.
**Why conceptual:** it admits a class of source material the model currently cannot hold, and
**risks becoming a bin for anything hard.** ⛔ **I do not know whether R7 is a transcription defect or
a genuine recursion; the value must not decide that.** **Status `[PROP]` · LOCAL.**

## `P-04` — `selection: superseded`

**Originating:** §B-4 / §F-1. **Evidence:** `KOS-T-0007` `Σ` `D-05` — the implemented `Σ` returns
`(direction, strength)`, structurally **`Σ₀`**, which the corpus records as **superseded**.
`stipulated` is true and insufficient; **no `selection` value fits.**
**Change:** add `superseded` to `selection`.
**Why conceptual:** it makes the schema assert a **corpus-historical** fact (*that definition was
replaced*) inside an implementation field — a new kind of claim for that field.
⚠️ **And it presupposes `P-11` `supersession` being available as a relationship.** **`[PROP]` · LOCAL.**

## `P-05` — level `modelled-as-placeholder`

**Originating:** §B-5 / §F-3. **Evidence:** `KOS-T-0011` `ℐ` — its only code appearance is
`"InvariantReg": (["K","Sigma","Policy"], "NOT ENUMERATED", "D")`, **a graph node whose own label says
the register is not enumerated** — and it is in the **excluded** lane.
**Change:** a level **below `type-exists`** for a construct present in a model only as a *named absence*.
**Why conceptual:** it adds a level to a vocabulary whose four values were validated, and the new
level's admissibility interacts with the scope rule (`P-16`). **`[PROP]` · LOCAL.**

## `P-06` — record-level vs reading-level identity `[OPEN]`

**Originating:** §B-7 / §H. **Evidence:** `Π` — **4 of 7 readings are disposition 2**, so the record
describes **a glyph, not a candidate**; `kind` was unassignable at record level.
**The open question:** what is the correct **unit of extraction**? Candidate? Glyph? Occurrence?
⛔ **No answer proposed.** A hierarchy *glyph → occurrence → candidate → definition* is **one**
possibility and is **not** asserted here. **`[OPEN]` · LOCAL.**

## `P-07` · `P-08` · `P-09` — three candidate kinds

| ID | kind | evidence | note |
|---|---|---|---|
| `P-07` | **`value-set`** | `Σ` — all six definitions are sets or tuples **of value domains** | ⭐ **predicted in v2's watch list and now evidenced** |
| `P-08` | **`register` / `interface`** | `ℐ` — a named collection **whose members are the contract**; members are **predicates**, so not `value-set` | ⭐ **not predicted** |
| `P-09` | **`structure`** | `Π` R2/R3 — `(Q,C,O)`; a preservation contract | weakest of the three; **observed, not clearly demanded** |

**Why conceptual:** each extends the kind vocabulary, and `P-07`/`P-08` interact with `P-01`
(if `kind` moves to the definition, the vocabulary is exercised differently).
⚠️ **`constant` is NOT proposed here — it is already in v2** and was independently exercised a second
time by `Zero` `D-06`. **All three `[PROP]` · LOCAL.**

## `P-10` — relationship `co-obligation`

**Originating:** §D. **Evidence:** `ℐ` `D-01` (invariants — **predicates** preserved across
transitions) ~ `D-03` `ℛ_req` (**equivalence relations** that must not collapse). **Differently
typed**, yet addressed to the same obligation; **54 in-scope files vs 5**.
**Change:** *different formalisations of the same OBLIGATION* — **not** equivalence of referents.
**Why conceptual:** it introduces a relationship whose criterion is **purpose**, not reference — a new
kind of relation for the vocabulary. **`[PROP]` · LOCAL.**

## `P-11` — relationship `supersession` · **CROSSES**

**Originating:** §D / §F-1. **Evidence:** `Σ₀ → Σ`, with the implemented `Σ` realizing the
**superseded** member. **MD-017's six values have nothing for *A was replaced by B*.**
**Why conceptual, and why it crosses:** MD-017 is **3MC's**. `[PROP]` is recorded **local** to
Extraction; ⚠️ **whether MD-017 should carry it is 3MC's question, and no proposal has been sent** —
unlike `P-12`, which has. **`[PROP]` · CROSSES.**

## `P-12` — relationship `misattribution` · **CROSSES, escalated**

**Originating:** pass 1 Defect D. **Evidence:** `≡_sem` `D-03` states `D-01` is **correct and filed
under the wrong name**. Not `refinement`, not `contradiction` (one **endorses** the other), and
`unresolved_equivalence` **understates** it.
**Status:** ⭐ **escalated to 3MC in `06-PROPOSAL-TO-3MC-MISATTRIBUTION.md`** — asynchronous;
**no response awaited or depended upon**. Local value in use in commentary only; the record still
carries `unresolved_equivalence` **with a protest**. **`[PROP]` · CROSSES.**

## `P-13` — empty-field value `not-searched`

**Originating:** B-6's boundary. **Evidence:** `K` `D-08`/`D-09` measurement cells show `—`; the
in-scope file counts were **not measured** for those two, which is neither `n/a` nor `never`.
**Change:** a third value for *applicable · not yet searched*.
**Why conceptual, and why NOT applied under B-6:** B-6 authorizes the `n/a`/`never` distinction only.
⭐ **Adding a third value exceeds that authorization**, so it is registered rather than applied.
**`[PROP]` · LOCAL.**

## `P-14` — comparison-arm implementations `[OPEN]`

**Originating:** §F-2. **Evidence:** `Zero` — `zero_strict`, `zero_weak`, `zero_kleene`,
`zero_reasoned` are **four functions realizing four RIVAL definitions, deliberately, side by side.**
**The distinction that must not be lost:**

```
one candidate → definition A → implementation A          (rival arms)
              → definition B → implementation B
      vs
one definition → implementation 1                        (alternative realizations)
               → implementation 2
```

⛔ **No representation proposed.** v2 can already attach an implementation to a `D-nn`, so it may
**already** suffice — **that is exactly what is unresolved.** **`[OPEN]` · LOCAL.**

## `P-15` — the four `Latest` fields may describe different candidates `[OPEN]`

**Originating:** §E-1. **Evidence:** `Π` — R1's *latest mention* and R4's *latest implementation* are
facts about **different candidates**, so the record's four temporal fields are **not about one object**.
⛔ **No answer proposed** — it depends on `P-06`. **`[OPEN]` · LOCAL.**

## `P-16` — formalize the implementation-scope exclusion

**Originating:** §F-4 / §G-1. **Evidence:** `ℐ` — without extending the scope exclusion to
implementation, the record would have read **`type-exists`** on the strength of the extractor's own
placeholder. ⚠️ **The exclusion was APPLIED IN PRACTICE during stress pass 2 and is not yet in the
schema text.**

$$\boxed{\begin{array}{c}\textbf{Extraction-generated artifacts cannot serve as corpus evidence for}\\ \textbf{existence · definition · implementation · status · relationship · ABSENCE.}\\ \textbf{A placeholder reading "NOT ENUMERATED" is not evidence the concept is absent.}\end{array}}$$

**Why conceptual rather than mechanical:** it extends a rule to **new field families** and adds the
**absence** clause, which is a new assertion about what cannot be inferred. ⚠️ **Recorded as the gap
between practice and rule text** — the same pattern as Defect E in pass 1. **`[PROP]` · LOCAL.**

## `P-17` — titled-but-unplaced decisions `[OPEN]`

**Originating:** §E-3. **Evidence:** `brainstorming/verification/DECISION-SIGMA-EPISTEMIC-STATUS.md`
is titled **"DECISION"** and sits in **`brainstorming/`, not `governance/`**. `Σ`'s
`governed decision` correctly reads **`never`**, and the file is recorded as a **naming** observation.
**The open question:** should the schema carry *"a decision-titled artifact exists outside the
register"* as a field? ⛔ **No answer proposed** — and ⚠️ **it must not become a way to credit a
governed decision that does not exist.** **`[OPEN]` · LOCAL.**

---

## Interaction map — why these must not be applied piecemeal

```
P-06 (unit of extraction)  ──gates──►  P-01 kind@definition
                           ──gates──►  P-02 status@definition
                           ──gates──►  P-15 Latest ownership
P-11 supersession          ──gates──►  P-04 selection: superseded
P-16 scope exclusion       ──gates──►  P-05 modelled-as-placeholder
P-01 + P-02                ──affect──►  P-07 · P-08 · P-09 (kind vocabulary)
```

⚠️ **`P-06` is upstream of four others and is `[OPEN]`.** `[REC]` **Applying any of `P-01`, `P-02`,
`P-04`, `P-05` or `P-15` before `P-06` is answered risks a second migration.** Recorded as a
recommendation; **no sequencing decision is taken here.**

## Tally

| | count |
|---|---:|
| **`[APPLIED — MECHANICAL]`** | **1** — `B-6` only |
| **`[PROP — CONCEPTUAL]`** | **13** |
| **`[OPEN — CONCEPTUAL]`** | **4** — `P-06`, `P-14`, `P-15`, `P-17` |
| crossing the 3MC boundary | **2** — `P-11`, `P-12` *(one escalated, one not sent)* |

---

# Phase R additions — `P-18` … `P-24`

**Registered 2026-09-07 from [`12-PHASE-R-PROPOSAL.md`](./12-PHASE-R-PROPOSAL.md). None applied.**
⛔ **No duplicates created** — `P-20` and `P-22` are cross-referenced against `P-16` and `P-13` below.

## `P-18` — `revisit_required` trigger and structured queue

**Originating:** Phase R §2. **Evidence:** v2's append-never-edit rule preserves history (`K`'s v1→v2
revalidation deleted nothing) **but produces no queue** — a revisit trigger survives only as prose in
whichever record noticed it. Six trigger types are evidenced from this session: later evidence
(`T-1`), schema defect (`T-2` — `F-4`, `F-5`), stale measurement (`T-3` — `INV-9`, 0.7 %→52 %),
ambiguous disposition (`T-4` — `K`'s 4, `Π`'s R5/R6), scope change (`T-5`), external-lane finding
(`T-6` — 3MC's `05_cross-model/` mid-session).
**Change:** a trigger entity carrying `trigger_type · record+field · original_reading (immutable) ·
new_evidence (citing the ORIGINAL corpus) · why_it_may_change · status`; second-pass results become
**new records** that `supersede` and never rewrite.
**Why conceptual:** it adds an entity type and an immutability guarantee.
⚠️ **Blocked by `P-06`:** *what the trigger points at* — record, candidate, definition or occurrence —
**is undecided.** **`[PROP]` · LOCAL.**

## `P-19` — structural-diversity pilot criterion

**Originating:** §4. **Evidence:** the `K` pilot exercised one structural case and taught nothing
about operations, relations, overloaded glyphs or cross-domain names; **four further concepts each
broke something different.** **Topic sampling would have re-run the `K` case.**
**Change:** pilot selection spans **16 evidenced structural cases**, not subjects — including the six
that only appeared under stress: one-definition-many-implementations (`Qualify`), implementation
without textual definition (`Σ D-05`), rival implementations (`Zero`), superseded implementation
(`Σ`), malformed definition (`Π` R7), model-of vs realization (`ℐ`).
**Why conceptual:** it constrains *what is extracted next*, a methodological commitment.
**`[PROP]` · LOCAL.**

## `P-20` — anti-anchoring safeguards · ⚠️ **distinct from `P-16`**

**Originating:** §3. **Evidence:** I carried *"neither lane cites the other"* for **six days** and
repeated *0.7 % coverage* from a five-day-old artifact — **both by re-citing instead of re-measuring.**
**Change:** `S-1` re-measure never re-cite · `S-2` blind re-extraction of a sample · `S-3` record
reversals explicitly and **count them** *(a pass with zero reversals is evidence of anchoring)* ·
`S-4` open the first-pass record only **after** the second reading is written.

$$oxed{P	ext{-}16 	extbf{ governs CITATION. } P	ext{-}20 	extbf{ governs METHOD. Neither implies the other.}}$$

⚠️ **`S-2` and `S-4` may not both be affordable — recorded as alternatives, not a set.**
**Why conceptual:** it constrains procedure and adds a counted field. **`[PROP]` · LOCAL.**

## `P-21` — extraction manifest and declared scope

**Originating:** §5. **Evidence:** 3MC has `reading-manifest.tsv` (2 376), `progress.tsv` (3 449) and
a resume script; **Theory Extraction has eight records and no denominator.**
**Change:** `M-1` manifest · `M-2` declared in/out-of-scope **including the extracting-lane
exclusion** (adopted at `KOS-T-0001`, extended to implementation in stress pass 2).
**Why conceptual:** it defines what the project is *about*, which no field currently states.
**`[PROP]` · LOCAL.**

## `P-22` — coverage measurement and stopping criterion · ⚠️ **cross-references `P-13`**

**Originating:** §5. **Evidence:** *"second pass"* presupposes a defined first pass; there is
currently no completion criterion.
**Change:** `M-3` re-measurable coverage *(never carried)* · `M-4` a stopping criterion — ⚠️ **which
may not be "all files"**: *"no new candidate kind in N files"* is a different and possibly better
criterion · `M-5` not-searched treatment.

$$oxed{P	ext{-}13 	extbf{ is a FIELD VALUE on a record; } M	ext{-}5 	extbf{ is a CORPUS COVERAGE category. Related, not duplicate.}}$$

**Why conceptual:** a stopping criterion is a claim about sufficiency. **`[PROP]` · LOCAL.**

## `P-23` — provenance ≠ methodological ≠ scheduling dependency · **governance-facing**

**Originating:** §0, §6. **Evidence:** 3MC's reading pass is **52 %** and wrote **concurrently** with
this session. **A diagram arrow meaning *provenance* but read as *scheduling* would block Phase R on
another lane's throughput.**
**Change:** every programme dependency is typed as **provenance** *(X's output is evidence for Y)*,
**methodological** *(Y's method must be settled before X is done correctly)* or **scheduling**
*(X must finish before Y starts)*.
**Applied to the case at hand:** 3MC → Phase R is **provenance**; `P-06` → broad extraction is
**methodological**; ⛔ **no scheduling dependency on 3MC completion is established.**
**Why conceptual, and why it crosses:** it proposes a **governance vocabulary**, not an extraction
field. ⚠️ **It touches how the programme's own diagrams are read** — beyond this project's authority.
**`[PROP]` · governance-facing.**

## `P-24` — v3 backward-compatibility gate

**Originating:** §7. **Evidence:** `Π` (7 readings, ≥5 candidates, 4 kinds, a malformed reading, four
`Latest` values belonging to different candidates) and `Zero` (6 definitions of which `D-03` is really
four, two `[RF]` beside four live, a `constant` under the same name, four rival implementations).
**Change:** v3 must be **demonstrated** capable of representing all eight existing records — `Π` and
`Zero` first — **before** broad extraction begins.
**Why conceptual:** it is a gate on another artifact's acceptance, not a field.
⛔ **If v3 cannot represent `Π` and `Zero` as already recorded, it is not ready for 3 000 documents.**
**`[PROP]` · LOCAL.**


## Revised tally

| | count |
|---|---:|
| **`[APPLIED — MECHANICAL]`** | **1** — `B-6` only |
| **`[PROP — CONCEPTUAL]`** | **20** *(13 + 7 Phase R)* |
| **`[OPEN — CONCEPTUAL]`** | **4** — `P-06`, `P-14`, `P-15`, `P-17` |
| crossing / facing outside this project | **3** — `P-11`, `P-12` *(3MC)*, `P-23` *(governance)* |
| **total registered** | **24** · ⛔ **applied: 1, and it is mechanical** |
