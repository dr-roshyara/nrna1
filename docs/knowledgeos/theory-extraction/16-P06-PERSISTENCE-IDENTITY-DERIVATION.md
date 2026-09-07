# `P-06` — Persistence Identity Derivation

**2026-09-07 · decision-support only · baseline: [`15-P06-SEMANTIC-ONTOLOGY.md`](./15-P06-SEMANTIC-ONTOLOGY.md).**

> ⛔ **Schema v2 unmodified · v3 not begun · 0 stress records modified · proposal register unmodified ·
> `F-4`/`F-5` unrepaired · 3MC / `dimension-registry.md` / MD-017 / MD-018 untouched · candidate
> identity not adjudicated · `Q3` and `Q4` not closed · no excluded-lane evidence used · code existence
> not used as theoretical identity · no architecture selected · no database/table/key requirement
> asserted as a semantic fact · migration convenience NOT used as an epistemic criterion.**

`[EMP]` evidenced · `[DERIVED]` logically required · `[ARCH]` architectural choice · `[OPEN]` unresolved.

---

# 1. The semantic baseline, carried unchanged

The ten items from `15` §2 are carried **without addition or removal**:
glyph · occurrence · definition · candidate · establishment mechanism/act · definition→candidate
association · implementation artifact · realization-vs-model-of · definition source · assertion/status.

`[EMP]` **All ten are semantically non-collapsible** — each has a recorded loss-on-collapse in `15` §2.
⛔ **None is dropped here because it can be serialized as a field; none is added because storage would
find it convenient.**

# 2. The identity-language boundary — mandatory, and the crux of this pass

| level | question it answers |
|---|---|
| **A · semantic distinguishability** | can these two be collapsed without information loss? |
| **B · independent referenceability** | can this be *referred to* apart from that? |
| **C · persistence identity** | does this need an **independently stable identifier** in storage? |
| **D · physical representation** | table · row · document · nested object · field · node |

$$\boxed{\begin{array}{c}A \nRightarrow B \nRightarrow C \nRightarrow D.\\ \textbf{A semantic entity does NOT entail a persistence key.}\end{array}}$$

## `[DERIVED]` The test that separates **B** from **C**

Referenceability can be **structural** — by natural key or path (`Zero.D-03`, `kosmodel.py:Qualify`).
It becomes **identity** only when structural reference fails:

$$\boxed{\begin{array}{c}\textbf{Persistence identity is FORCED when a thing must be referenced}\\ \textbf{(i) from a context that cannot reach it by path, or}\\ \textbf{(ii) across a change to its own containing structure.}\end{array}}$$

⚠️ **This is the whole discipline of this pass.** Without it, every semantic entity trivially becomes a
key, and the earlier reduction would be undone by notation.

# 3. The ten items re-evaluated

| # | item | semantically non-collapsible? | must be independently **referenceable**? | must have independent **persistence identity**? | could be a **value**? | could be a **qualified field**? |
|---|---|:--:|:--:|:--:|:--:|:--:|
| 1 | **Glyph** | ✅ `[EMP]` | ✅ | 🔴 **NO** `[DERIVED]` — **its string IS its natural key**; a "renamed" glyph is a different glyph | ✅ | ✅ |
| 2 | **Occurrence** | ✅ `[EMP]` | ✅ | ⚠️ **NOT identity — but STABILITY is forced.** See §3.1 | ⚠️ | ✅ |
| 3 | **Definition** | ✅ `[EMP]` | ✅ | ⚠️ **CONDITIONAL** — see §3.2 | 🔴 | ✅ |
| 4 | **Candidate** | ✅ `[EMP]` | ✅ | ⭐ **YES — FORCED** `[DERIVED]`, see §3.3 | 🔴 | 🔴 |
| 5 | **Establishment mechanism/act** | ✅ `[EMP]` | ⚠️ | 🔴 **NO** `[DERIVED]` — 2–3 values on the candidate; §9 | ✅ | ✅ |
| 6 | **Definition→candidate association** | ✅ `[EMP]` | ✅ | ⭐ **YES — FORCED** `[DERIVED]`, see §8 | 🔴 | 🔴 |
| 7 | **Implementation artifact** | ✅ `[EMP]` | ✅ | 🔴 **NO** `[ARCH]` — `(path, symbol)` is a natural key | ⚠️ | ✅ |
| 8 | **Realization vs model-of** | ✅ `[EMP]` *(1 instance)* | 🔴 | 🔴 **NO** `[DERIVED]` — a **value on the artifact→definition link** | ✅ | ✅ |
| 9 | **Definition source** | ✅ `[EMP]` **decisive** | ⚠️ | 🔴 **NO** `[DERIVED]` — a typed value: `textual@<file>` / `code@<file:symbol>` | ✅ | ✅ |
| 10 | **Assertion / disposition / status** | ✅ `[EMP]` | ⚠️ | 🔴 **NO** `[DERIVED]` — a **structure that must carry its scope**, not an identity | ⚠️ | ✅ |

## 3.1 Occurrence — **stability** is forced, identity is not

`[EMP]` **An occurrence's natural key is `(file, site)` — and in this corpus that key is demonstrably
unstable.** This session renamed **16 + 39 + 1 = 56 corpus files**. Any occurrence reference held as a
path **broke**, silently.

$$\boxed{\textbf{Occurrence references require STABILITY. Identity is one way to obtain it; a stable-path mechanism is another.}}$$

⚠️ `[ARCH]` **Which mechanism is a design choice.** ⛔ **Not recorded as a forced identity** — that
would confuse a *requirement* with one of its *solutions*.

## 3.2 Definition — forced **only under one addressing choice**

`[EMP]` `K`'s `D-03`/`D-05` were re-dispositioned to a **different candidate (`𝒦`)**. Under
candidate-addressing, their reference **moves** (`K.D-03` → `𝒦.D-01`); under glyph-addressing it does
**not** (`K.D-03` is unchanged, only its association changes).

$$\boxed{\begin{array}{c}\textbf{Definition persistence identity is FORCED iff definitions are addressed under CANDIDATES.}\\ \textbf{Under glyph-addressing, a path suffices.}\end{array}}$$

`[DERIVED]` **Conditional, therefore not counted as forced.** ⚠️ **It is the one place where an
addressing choice creates a persistence obligation** — evidence that **`D` can feed back on `C`**, which
is exactly what §2's ladder exists to expose.

## 3.3 Candidate — **forced**, and grounded without closing `Q3`

Three independent grounds:

| | ground | `[?]` |
|---|---|:--:|
| **(a)** | ⭐ **some candidates have NO natural key.** `Π`'s R2/R3 candidates are **described, not named** — *"the inquiry frame `(Q,C,O)`"*, *"the preservation contract"*. A composite key `Π.R2` names **the reading that revealed the candidate**, not the candidate | `[EMP]` |
| **(b)** | ⭐⭐ **`ℐ` carries properties while its glyph-membership is OPEN.** `Q3` leaves undecided whether `ℐ` and `𝓘` index one candidate or two. **A candidate keyed by glyph could not survive `Q3` being answered "one"** — its status, grounding, dependencies and relation would have to be moved | `[DERIVED]`, **while `Q3` is open** |
| **(c)** | **`𝒦` was established by naming AND distinguished by derivation** (`15` §1). A key derived from either mechanism alone misrepresents the other | `[EMP]` |

⚠️ **(b) is conditional on `Q3` remaining open — and it is open.** ⛔ **Nothing here closes `Q3`;** the
obligation exists *because* it is unclosed, and would need re-derivation if it were closed.

# 4. The four cases re-stressed at the persistence level

## Case 1 — `ℐ`

> **What identities/references must persistence provide to represent this without inventing a definition?**

| requirement | what it needs |
|---|---|
| candidate exists by naming | a **candidate identity** independent of any definition — §3.3(b) |
| zero definitions established | four **definition** references, each with per-definition status; **no fifth created** |
| carries status · grounding · dependencies · a relation | those attach to the **candidate identity**, not to any definition |
| `Q3` open | ⭐ **the candidate identity must NOT be derived from the glyph** — otherwise answering `Q3` re-keys it |

$$\boxed{\mathcal I \textbf{ forces exactly ONE persistence identity: the CANDIDATE. Nothing else.}}$$

## Case 2 — `Qualify`

> **What must be independently addressable to prevent implementation identity becoming definition identity?**

| requirement | what it needs |
|---|---|
| 3 definitions | 3 **definition** references — path suffices *(glyph-addressed)* |
| **1 definition realized by 2 artifacts** | ⭐ the **artifact→definition link must be many-to-one**, and the artifact's key is `(path, symbol)`. **No new identity** |
| `G1` / no derived body | a **provenance value** (`stipulated`) on the link — **not an identity** |

$$\boxed{\begin{array}{c}\textbf{Implementation/theory separation is preserved by the LINK'S CARDINALITY AND ITS PROVENANCE VALUE.}\\ \textbf{It does not require an artifact identity.}\end{array}}$$

⭐ **This is the clearest case where a semantic distinction needs no persistence identity at all.**

## Case 3 — `Π`

> **What must be independently addressable so that an unresolved association is not converted into a false candidate assignment?**

| requirement | what it needs |
|---|---|
| one glyph, multiple candidates | **glyph as a value**; **candidate identities** — §3.3(a) |
| candidate-level `Decision 3` on R1 | attaches to **R1's candidate identity** |
| **R5/R6 unresolved** | ⭐⭐ **an association with a definition endpoint and NO candidate endpoint.** §8 |

## Case 4 — `Zero`

> **What identity structure prevents definition, candidate and implementation collapsing into one object?**

| requirement | what it needs |
|---|---|
| 6 definitions, 2 `[RF]` + 4 live | **per-definition status** — a field on each definition |
| `D-03` currently conflated | ⚠️ **`F-5`'s repair — splitting into four sibling definitions.** ⛔ **Not performed.** Needs only **paths**, not identities |
| `0_i` a distinct candidate | a **second candidate identity** under the same name |
| **4 rival definitions implemented side by side** | ⭐ **four artifact→definition links, each one-to-one** — the rivalry is carried by *which definition each realizes*, **not by any grouping object** |

$$\boxed{\textbf{Comparison arms need NO identity — they are 4 links, not a 5th object. } (P\text{-}14 \textbf{ confirmed dissolved at the persistence level.})}$$

# 5. Derived identity obligations

**Named from the derivation, not from the prompt's illustrative list.**

| | obligation | `[?]` | ground |
|---|---|:--:|---|
| **OB-1** | **definitions must be independently distinguishable and stably referenceable** | `[EMP]` | 8/8; `Zero` 6, `K` 4 |
| **OB-2** | ⭐ **candidates must have persistence identity not derived from glyph or from any definition** | `[DERIVED]` | §3.3 (a)(b)(c) — `Π` R2/R3 unnamed; `ℐ` under open `Q3` |
| **OB-3** | **occurrences must be distinguishable from definitions, and their references must be STABLE** | `[EMP]` + `[DERIVED]` | `Σ`'s 143; **56 files renamed this session** |
| **OB-4** | ⭐ **associations must be identifiable independently of BOTH endpoints** | `[DERIVED]` | §8 |
| **OB-5** | **an association must be representable with its candidate endpoint absent** | `[EMP]` | `K`'s 4 ambiguous; `Π` R5/R6 |
| **OB-6** | **candidate properties must not require an established definition** | `[EMP]` | `ℐ` |
| **OB-7** | **artifact→definition links must be many-to-one and carry provenance** | `[EMP]` | `Qualify` 2:1; `Σ`'s stipulated body |
| **OB-8** | **definition source must be attached at definition level** | `[EMP]` | `F-4` |
| **OB-9** | **establishment mechanism and its warrant must be attached at candidate level** | `[DERIVED]` | `I-11`; `C-022` vs a bare naming |
| **OB-10** | **every status/assertion must carry its epistemic scope** | `[EMP]` | `never` carries its scope |

⚠️ **Only `OB-2` and `OB-4` are obligations of *identity*. The other eight are obligations of
*distinguishability, stability, attachment or cardinality*.**

# 6. Cardinality — derived separately from identity

| relation | cardinality | `[?]` |
|---|---|:--:|
| glyph → occurrence | **1 : N** | `[EMP]` |
| glyph → definition | **1 : N** | `[EMP]` |
| glyph → candidate | **1 : N** | `[EMP]` — 6/8 |
| candidate → definition | **1 : N** | `[EMP]` — `K` 4, `Zero` 5 |
| **definition → candidate** | ⭐ **`0..1` evidenced; `0..N` `[OPEN]`** | ⚠️ **"exactly one" is NOT established** — `Π` R5/R6 may be ambiguous *between* named candidates, i.e. several competing hypotheses for one definition |
| definition → unresolved candidate | **`0`** must be permitted | `[EMP]` |
| occurrence → definition | ⭐ **`0..1`** | `[EMP]` — 143 occurrences with **zero** |
| candidate → implementation artifact | **1 : N** | `[EMP]` |
| definition → implementation artifact | **1 : N** | `[EMP]` — `Qualify` 2 |
| association → definition | **exactly 1** | `[DERIVED]` |
| association → candidate | **`0..1`** | `[EMP]`; **`0..N` `[OPEN]`** per the row above |

⛔ **`Q3` and `Q4` not resolved; `ℐ`~`ℛ_req` not used.** ⚠️ **`candidate → glyph` cardinality is
deliberately absent** — it is exactly `Q3`/`Q4`, and asserting it would close them.

# 7. Lifecycle identity

**The question: must identity survive a change in status · disposition · interpretation · source ·
implementation · candidate association?**

| item | must survive | `[?]` |
|---|---|:--:|
| **candidate** | ⭐ **status, grounding, dependencies, AND glyph-membership** *(`ℐ` under open `Q3`)* | `[DERIVED]` |
| **definition** | **status** *(`Zero`'s `[RF]`)*, **source** *(`Σ` `D-05` re-marking)*, ⭐ **and its candidate association** *(`K` `D-03`/`D-05` re-dispositioned)* | `[EMP]` |
| **association** | ⭐⭐ **its own status, and the ARRIVAL of a candidate endpoint** — an association must persist while going from *unresolved* to *resolved* | `[DERIVED]` |
| **implementation artifact** | ⚠️ **nothing evidenced** — no artifact changed which definition it realizes | `[OPEN]` |
| **establishment act** | ⭐ **must survive a change in the candidate's later interpretation** — `𝒦`'s naming stands even though its distinctness was later derived | `[DERIVED]` |

$$\boxed{\begin{array}{c}\textbf{The lifecycle test confirms exactly the two identities of §5:}\\ \textbf{CANDIDATE (survives glyph-membership change) and ASSOCIATION (survives endpoint arrival).}\end{array}}$$

# 8. Association identity — `I-10` resolved as **(1)**, and why

> **Does *"the association carries its own status"* imply *"the association is an independently
> identifiable object"*, or merely *"independently representable as an assertion structure"*?**

⭐ **The evidence forces (1), and the argument does not rest on "has status".**

| step | reasoning |
|---|---|
| 1 | The natural key of an association is **(definition, candidate)** |
| 2 | 🔴 **For the case `I-10` exists for, the candidate endpoint is ABSENT** — `K`'s 4 ambiguous, `Π`'s R5/R6. **The composite key does not exist** |
| 3 | Fall back to **(definition)** alone? 🔴 **Fails** — `definition → candidate` is **`0..N`** `[OPEN]` (§6), so one definition may carry **several competing** association hypotheses |
| 4 | Fall back to a **positional list** under the definition (`D-04.assoc[0]`)? 🔴 **Fails** — adding or retiring a hypothesis **reorders**, so the reference is unstable |
| 5 | And it **must** be referenceable from outside: `I-10` makes its status changeable, and a revisit trigger *(`P-18`)* must point at **what may be wrong** — which here is the association, not the definition and not the candidate |

$$\boxed{\begin{array}{c}\textbf{ASSOCIATION IDENTITY IS FORCED — } I\text{-}10 \textbf{ resolves to option (1).}\\ \textbf{Not because it has a status, but because NO stable structural reference to it exists.}\end{array}}$$

⚠️ **The prompt's caution is honoured:** *"has status"* alone would **not** have been sufficient — item
10 (assertion/status) has status and is **not** identity-forced. **The difference is the absent endpoint.**

# 9. Establishment identity — four notions separated

| notion | example | needs identity? |
|---|---|:--:|
| **mechanism** | `naming` · `stipulation` · `measurement` | 🔴 **no — a value** |
| **act** | `G-67` *(`ℐ`)* · **`C-022`** *(ratified 8)* · **`FR-001`** *(`δ=0.3099`)* | 🔴 **no — each is an EXISTING citable artifact with its own identity elsewhere** |
| **warrant** | *"50 attack classes, no counterexample"* vs **a bare naming** | 🔴 **no — a value carried beside the act reference** |
| **source/provenance** | ⚠️ **distinct** — provenance is of a **definition** (`OB-8`); establishment is of a **candidate** (`OB-9`) | 🔴 no |

**What must survive a change in later interpretation:** ⭐ **the act reference and the warrant.**
`𝒦`'s naming stands even though its *distinctness* was later derived by containment — so the
establishment record must not be rewritten when the interpretation changes.

⛔ **The establishment act is NOT promoted to an entity.** The evidence shows it must be **referenced
and warranted**, not that it must be **created** — every act cited is already an artifact.

# 10. Persistence patterns — realizations, not ontologies

| pattern | satisfies natively | needs machinery | risks losing | assumption | forces identity prematurely? |
|---|---|---|---|---|:--:|
| **typed-row** | `OB-1`…`OB-10` — rows for candidate and association; values elsewhere | occurrence-stability | — | rows can be typed heterogeneously | 🔴 no |
| **document / nested** | `OB-1` `OB-3` `OB-6` `OB-8` `OB-10` | ⚠️ **`OB-2` and `OB-4`** — nesting gives paths, not identities | 🔴 **unresolved associations** *(no parent to nest under)* | a natural containment hierarchy exists | ⚠️ **yes — nesting a candidate under a glyph pre-empts `Q3`** |
| **candidate-centric** | `OB-2` `OB-6` `OB-9` | `OB-3` `OB-4`; **a home for unassigned definitions** | 🔴 **`OB-5`** | candidate identity available at write time | 🔴 **yes** |
| **glyph-centric** | `OB-1` `OB-3` `OB-5` | `OB-2` `OB-4` `OB-9` | 🔴 **candidates spanning glyphs** — `[OPEN]` | a candidate belongs to one glyph | ⚠️ **yes — glyph identity** |
| **association-centric** | ⭐ `OB-4` `OB-5` `OB-1` `OB-2` | `OB-3` `OB-9`; a glyph index | ⚠️ readability | associations are the primary fact | 🔴 no |
| **graph-like** | all ten | occurrence-stability; discipline against over-linking | ⚠️ **provenance and scope diffuse across edges** | edges may carry properties | 🔴 no |
| **hybrid** | ⚠️ depends entirely on the split | — | — | — | ⚠️ depends |

⛔ **None selected.** ⚠️ **These are storage realizations; none is an ontology, and none may redefine §1.**

# 11. A/B/C/D as persistence projections

| | all obligations? | needs | assumptions | can represent unresolved? | forces candidate id? | forces glyph id? | preserves association status? | preserves impl/theory separation? |
|---|:--:|---|---|:--:|:--:|:--:|:--:|:--:|
| **A** candidate-keyed | 🔴 **no** | a holding class; association objects | candidate id at write time — **falsified** | 🔴 **no** | ✅ **forced** | 🔴 | 🔴 **no home for `OB-5`** | ✅ |
| **B** glyph-keyed nested | 🔴 **no** | association objects; a cross-glyph link | one candidate ⊂ one glyph — `[OPEN]` | ✅ | 🔴 | ✅ **forced** | 🔴 **positional only** | ✅ |
| **C** dual records | 🔴 **no** | ⭐ **association objects**; a synchronization rule | two classes stay consistent — `[ARCH]` | ✅ | ✅ | ✅ | 🔴 **not a class in C** | ✅ |
| **D** glyph dossier | 🔴 **no** | candidate promotion; association objects | candidate ≠ entity — **falsified** | ✅ | 🔴 | ✅ | 🔴 **field only** | ✅ |

## ⭐ The option space is mis-framed — stated as instructed

$$\boxed{\begin{array}{c}\textbf{All four are organized around GLYPH-vs-CANDIDATE.}\\ \textbf{The derivation forces CANDIDATE and ASSOCIATION identity.}\\ \textbf{ASSOCIATION appears in NONE of the four.}\end{array}}$$

`[DERIVED]` **A/B/C/D is therefore no longer the right option space** — not because a fifth
architecture is better, but because **the axis they vary is not the axis the obligations constrain.**
Every one of the four requires association objects to be **added**, and once added, the glyph-vs-candidate
distinction between them **stops carrying the weight it was chosen to carry.**

⛔ **No fifth architecture is invented.** §10 lists **realizations already known**, and
`association-centric` is one of them **only because it appeared in the derivation, not to fill a matrix.**

# 12. Decision boundary

## Settled `[EMP]`
Ten items non-collapsible · `OB-1` `OB-3` `OB-5` `OB-6` `OB-7` `OB-8` `OB-10` · nine cardinalities ·
**definition → candidate is `0..1`, not exactly-1** · occurrence → definition is `0..1` ·
**56 renames make path-based occurrence references demonstrably unstable** · `Π` R2/R3 candidates have
**no natural key** · `𝒦` carries **both** establishment mechanisms.

## Derived `[DERIVED]`
⭐ **`OB-2` candidate persistence identity is FORCED** *(no natural key; open `Q3`; dual establishment)*
· ⭐ **`OB-4` association persistence identity is FORCED** *(absent endpoint · `0..N` · positional
instability · external reference)* · `OB-9` establishment must be **referenced and warranted, not
created** · **glyph, artifact, realization-vs-model-of, source and status are NOT identity-forced** ·
**definition identity is forced only under candidate-addressing** · **`I-10` resolves to option (1)** ·
**A/B/C/D is the wrong option space.**

## Architectural choices `[ARCH]`
The **occurrence-stability mechanism** (identity vs stable paths) · whether definitions are addressed
under glyph or candidate · glyph as value or entity · artifact keying · the physical pattern from §10 ·
**any synchronization rule a multi-class pattern needs.**

## Still open `[OPEN]`
**`Q3`** *(does `ℐ`/`𝓘` index one candidate or two — and `OB-2`'s ground (b) depends on it staying
open)* · **`Q4`** *(candidate↔candidate relations)* · **`candidate → glyph` cardinality** *(= `Q3`/`Q4`)*
· **`definition → candidate` upper bound: `1` or `N`** · **implementation-artifact lifecycle** *(nothing
evidenced)* · **establishment-act promotion** *(not required; not refused)* · **whether
`association-centric` is a pattern or a re-framing of the ontology.**

---

**P-06 PERSISTENCE IDENTITY DERIVED — 10 SEMANTIC ITEMS REQUIRE NON-COLLAPSIBLE REPRESENTATION — 2 PERSISTENCE IDENTITIES FORCED — NO REPRESENTATION ARCHITECTURE SELECTED — SCHEMA v3 NOT BEGUN.**
