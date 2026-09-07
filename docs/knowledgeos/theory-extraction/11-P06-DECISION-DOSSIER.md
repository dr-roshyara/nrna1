# `P-06` Decision Dossier — the Unit of Extraction

**2026-09-07 · decision-preparation only · evidence base: [`10-EXTRACTION-UNIT-ANALYSIS.md`](./10-EXTRACTION-UNIT-ANALYSIS.md) and the eight records.**

> ⛔ **No decision is taken here.** `P-06` not chosen · `Q2`/`Q3` not chosen · `Q4` not resolved ·
> Schema v3 not designed · no record modified · v2 unmodified · no conceptual proposal applied ·
> 3MC / `dimension-registry.md` / MD-017 / MD-018 untouched · no definitions adjudicated · no
> candidates merged · no carrier · no kernel · no canonical theory.
>
> **Purpose: make the decision VISIBLE before an implementation makes it implicitly.**

---

## 1. The `P-06` question, stated precisely

> ### **What does one extraction record identify?**

v2 answers this **twice, incompatibly**: a record is **keyed by a glyph or name** (`"Σ"`, `"Π"`,
`"Zero"`) and **asserts a candidate** (it carries one `kind`, one `status`, one set of four `Latest`
fields, as though one thing were being described).

$$\boxed{\begin{array}{c}P\text{-}06 \textbf{ is not "which fields do we need."}\\ \textbf{It is: WHICH THING HAS IDENTITY in the extraction model.}\end{array}}$$

⚠️ **And it is not a theory question.** It asks what the *extraction record* identifies, **not** what
KnowledgeOS's concepts are.

## 2. Evidence — the strongest item from each of the eight

| concept | strongest evidence bearing on record identity |
|---|---|
| **`K`** | ⭐ **`𝒦 = (K,H)` and `𝒦 = (K,C,T,E,A)` CONTAIN `K`** — so `𝒦` is a structure that *has* `K`. **Two candidates under one glyph family, on positive evidence**, plus 4 ambiguous |
| **`δ`** | ⭐ **`δ = 0.3099`** (`FR-001`'s resolution constant) is an **`operation` and a `constant` under one glyph** — `kind` is unassignable at record level |
| **`≡_sem`** | ⭐ **the six siblings live at the BASE glyph `≡` while the record sits at the SUBSCRIPT** — `=_str`, `≈_obs`, `SameId`, `≡_H`, `≡_P`. The record's key is at the wrong altitude |
| **`Qualify`** | ⭐ **two implementations, ONE definition** — both declare `Observation × Policy ⇀ Evidence` verbatim. **Multiple implementations ⇏ multiple definitions** |
| **`Σ`** | ⭐⭐ **`D-05` exists ONLY in code** (`(direction,strength)`, structurally the superseded `Σ₀`) — **an implementation fact recorded as a definition** (`F-4`). Plus **143 files of summation `Σ`**, which need a glyph-level home |
| **`Π`** | ⭐⭐⭐ **7 readings, 4 of them disposition 2 → ≥5 candidates, 4 different kinds.** The record demonstrably describes **a glyph, not a candidate**. And its four `Latest` values are **facts about different things** |
| **`Zero`** | ⭐⭐ **`D-03` is FOUR definitions recorded as one** — the code comment reads `# ------- the four readings` and ships `zero_strict/weak/kleene/reasoned`. Plus `0_i ∈ 𝒟_i`, an algebraic **`constant`** under the same name |
| **`ℐ`** | ⭐⭐ **its only artifact is a MODEL OF the concept, not a realization** — `"InvariantReg": (…, "NOT ENUMERATED", …)` — and it sits in the **excluded** lane. Plus **two scripts** `ℐ`/`𝓘` |

**Aggregate:** multiple definitions **8/8** · multiple candidates **6/8 confirmed** ·
`kind` illegitimate at record level **3/8** · implementation at a different level **3/8**.

$$\boxed{\textbf{Both directions of collapse are evidenced: implementation}\to\textbf{definition } (F\text{-}4) \textbf{ and definitions}\to\textbf{one } (F\text{-}5).}$$

## 3. Current model — what v2 record identity assumes

```
record  ═══  keyed by a glyph/name string
   │
   ├── kind          ─┐
   ├── status         ├─ asserted ONCE, as if the record were ONE candidate
   ├── grounding      │
   ├── Latest ×4     ─┘
   │
   ├── Definitions  D-01 … D-0n          ← the ONLY sub-level
   │      └── Implementation (level × provenance × selection)
   │
   └── homonyms · ambiguous_identity · symbolic_occurrences   ← COUNTS and notes, not entities
```

**Four assumptions, stated so they can be tested:**

| | assumption | status |
|---|---|:--:|
| **A1** | a glyph/name identifies exactly one candidate | 🔴 **false — 6/8** |
| **A2** | all definitions under one key are definitions *of the same thing* | 🔴 **false — `K`, `Π`, `Zero`, `δ`** |
| **A3** | a definition is atomic | 🔴 **false — `Zero` `D-03` is four** |
| **A4** | every definition has a textual source; code realizes definitions | 🔴 **false — `Σ` `D-05` is code-only** |

## 4. Forced distinctions vs possible design

### `[EV]` **Forced by the evidence** — any model must represent these

| | distinction | forced by |
|---|---|---|
| 1 | **glyph ≠ candidate** | `𝒦`/`K` · `δ`/`δ=0.3099` · `Zero`/`0_i` · `Π`'s five |
| 2 | **candidate ≠ definition** | 8/8 |
| 3 | **definition ≠ implementation** | `Qualify` — 2 implementations, 1 definition |
| 4 | **definitions can be nested/plural** | `Zero` `D-03` = four |
| 5 | **a definition's source can be code rather than text** | `Σ` `D-05` |
| 6 | **realization ≠ model-of** (`L5′`) | `ℐ` |
| 7 | **occurrences carry dispositions** | all four disposition values are per-occurrence |

### `[PROP]`/`[OPEN]` **Not forced — design space**

| | question | why not forced |
|---|---|---|
| **Q2** | is an **occurrence** a record or a field? | dispositions must exist; **nothing shows they need identity** |
| **Q3** | is **base-vs-subscripted glyph** part of glyph identity, a relation, or something else? | `≡`'s siblings and `ℐ`/`𝓘` show the *problem*; **not the representation** |
| **Q4** | is there an **obligation** level above candidate? | **one instance** |
| — | ⭐ **whether a record must be re-keyed at all** | the seven forced distinctions could be carried **as fields** under the existing key — **see Alternative D** |

$$\boxed{\textbf{Nothing in the evidence forces RE-KEYING. It forces the DISTINCTIONS.}}$$

## 5. Decision alternatives — four, each derived from evidence

⚠️ **No alternative is recommended. No alternative is invented** — each is the model implied by taking
one subset of the evidence as the identity-bearing one.

### **Alternative A — candidate-keyed**

| | |
|---|---|
| a record identifies | **one candidate** |
| fields | glyph(s), occurrences, dispositions, definitions, implementations |
| glyph → definition → candidate | glyph is an **index/field**; definitions hang off the candidate |
| implementation attaches to | a **definition** |
| multiple definitions | `D-nn` under the candidate |
| **multiple candidates under one glyph** | ⭐ **separate records**, cross-referenced by the shared glyph |
| occurrences | **fields** on definitions *(Q2 answered as "field")* |
| comparison arms | grouped implementations under sibling definitions — **no special structure** |
| **derived from** | `𝒦 ≠ K` containment · `δ`/`δ=0.3099` · `Zero`/`0_i` · `Π`'s five |

### **Alternative B — glyph-keyed, candidates nested**

| | |
|---|---|
| a record identifies | **one glyph or name** — declared as such, **no candidate asserted** |
| fields | candidates (nested), definitions under candidates, occurrences, implementations |
| glyph → definition → candidate | **glyph is the key; candidate is a nested entity** |
| implementation attaches to | a definition, inside a candidate |
| multiple definitions | nested two levels down |
| **multiple candidates under one glyph** | ⭐ **nested inside one record** |
| occurrences | fields at glyph level *(where `Σ`'s 143 naturally live)* |
| comparison arms | grouped implementations |
| **derived from** | `Σ`'s 143 symbolic occurrences needing a glyph home · `≡`'s base-glyph siblings · `ℐ`/`𝓘` |

### **Alternative C — two record types, cross-linked**

| | |
|---|---|
| a record identifies | **either a glyph OR a candidate** — two kinds of record |
| fields | glyph records hold occurrences and dispositions; candidate records hold definitions and implementations |
| glyph → definition → candidate | **dispositions are the LINK** between the two record types |
| implementation attaches to | a definition, in a candidate record |
| multiple definitions | in the candidate record |
| **multiple candidates under one glyph** | ⭐ **one glyph record → N candidate records** |
| occurrences | ⭐ **first-class, in the glyph record** *(Q2 answered as "record")* |
| comparison arms | grouped implementations |
| **derived from** | **both** evidence sets at once — glyph-level (`Σ`, `≡`, `ℐ`) and candidate-level (`𝒦`, `δ`, `Zero`, `Π`) |

### **Alternative D — keep the key, redeclare the record, push fields down**

| | |
|---|---|
| a record identifies | **a glyph dossier** — the key is unchanged; **the record simply stops asserting a candidate** |
| fields | `kind`, `status`, `grounding`, `Latest` **move to the definition**; candidate identity becomes a **field on each definition** |
| glyph → definition → candidate | flat: definitions carry `belongs_to_candidate:` |
| implementation attaches to | a definition |
| multiple definitions | as today |
| **multiple candidates under one glyph** | ⭐ **a field value repeated across definitions** — no new entity |
| occurrences | fields *(Q2 = "field")* |
| comparison arms | grouped implementations |
| **derived from** | ⭐ the observation that **the seven forced distinctions are all expressible as fields**; only `A1`–`A4` must be abandoned, not the key |

⚠️ **D is not "do nothing."** It abandons all four v2 assumptions and is exactly `P-01` + `P-02` +
`P-15` applied together. **It is the minimum-migration model and it is genuinely supported.**

## 6. `Q2` — occurrence as record vs field *(analysed, not decided)*

| | `occurrence = field` | `occurrence = record` |
|---|---|---|
| **for** | today's counts already work: `Σ` 143, `Π` 34, `𝓘` 33 · **no case yet required a per-site citation** | ⭐ **dispositions are per-occurrence**, and a count **cannot carry a citation per site** · disposition 3 (ambiguous) is a claim *about a site* |
| **against** | 🔴 **a count is not auditable** — *"143 symbolic occurrences"* cannot be re-checked site by site without re-running the grep | 🔴 **cost**: `Σ` alone would generate **143 records**, and `Π` 34 |
| **evidence bearing** | no record needed a site-level citation to reach its conclusions | ⚠️ **but `Σ`'s `D-03`/`D-05` containment question is unresolved *precisely because* the sites were not individually examined** |

$$\boxed{Q2 \textbf{ is a trade of auditability against volume, and the evidence supports both sides.}}$$

## 7. `Q3` — base vs subscripted glyph *(analysed, not decided)*

**Three readings, each with evidence:**

| reading | what it says | evidence | cost |
|---|---|---|---|
| **part of glyph identity** | `≡_sem` and `≡_H` are **different glyphs** | they are typographically distinct and used distinctly | 🔴 loses that they are **siblings in one declared structure** (`𝔎`'s 7-tuple) |
| **a separate relation** | one base glyph, N subscripted **candidates**, related by a `sibling`/`family` relation | ⭐ `261.25` **declares the family explicitly** as a 7-tuple | 🔴 requires a relation type the vocabulary lacks |
| **a script/spelling variance** | `ℐ` vs `𝓘` is **the same glyph in two scripts** | the corpus declares **no rule**; both appear in the same lanes | 🔴 ⚠️ **would be an ASSUMPTION** — and `𝒦`/`𝕂`/`K` shows script difference can accompany **genuine candidate difference** |

⚠️ **`ℐ`/`𝓘` and `≡_sem`/`≡_H` may not be the same phenomenon.** One is *script variance of a name*;
the other is *subscripted members of a declared family*. **Treating them alike would be a design
choice, not a finding.**

## 8. `Q4` — obligation level, **remains `[OPEN]`**

**Sole instance:** `ℐ` `D-01` (invariants — **predicates** preserved across transitions) ~ `D-03`
`ℛ_req` (**equivalence relations** that must not collapse). Differently typed; addressed to one
obligation; **54 in-scope files vs 5.**

**Why the evidence is insufficient — three reasons, each checkable:**

1. **`n = 1`.** No second instance appeared across eight concepts.
2. **An alternative explanation is not excluded.** They may be **two candidates in a
   `co-obligation` relation** (`P-10`) rather than two members of an **obligation entity**. ⚠️ **A
   relation and a level are different answers, and nothing distinguishes them here.**
3. **The one instance is contaminated at the point that matters.** The claim that they are
   *equivalent* (a contrapositive identity) was made in the **excluded gap-discovery lane** and is
   **not admissible as corpus evidence** (`P-16`). **So the strongest link between them cannot be used.**

$$\boxed{\begin{array}{c}\textbf{Deciding an ontology level from one instance whose key link is inadmissible}\\ \textbf{would be exactly the manufacturing this machinery exists to prevent.}\end{array}}$$

## 9. Proposal impact — `P-01`…`P-17` × the four alternatives

| ID | A candidate-keyed | B glyph-keyed nested | C dual records | D fields-down |
|---|---|---|---|---|
| `P-01` kind@definition | ⭐ **dissolves** | **survives** | **dissolves** *(candidate records)* | ⭐ **is the mechanism — required** |
| `P-02` status@definition | **survives** | **survives** | **survives** | **survives** *(required)* |
| `P-03` malformed | independent | independent | independent | independent |
| `P-04` selection:superseded | dep. `P-11` | dep. `P-11` | dep. `P-11` | dep. `P-11` |
| `P-05` placeholder | **transforms** → `L5′` field | transforms | transforms | transforms |
| `P-06` | **is the decision** | — | — | — |
| `P-07`/`08`/`09` kinds | independent *(vocabulary)* | independent | independent | independent |
| `P-10` co-obligation | dep. `Q4` | dep. `Q4` | dep. `Q4` | dep. `Q4` |
| `P-11` supersession | independent | independent | independent | independent |
| `P-12` misattribution | independent *(3MC)* | independent | independent | independent |
| `P-13` not-searched | independent | independent | independent | independent |
| `P-14` comparison arms | **dissolves** *(if `D-03` splits)* | dissolves | dissolves | dissolves |
| `P-15` Latest ownership | ⭐ **dissolves** | **survives** | **dissolves** | ⭐ **required** |
| `P-16` scope exclusion | independent | independent | independent | independent |
| `P-17` titled-unplaced | independent | independent | independent | independent |

### ⭐ The discriminating finding

$$\boxed{\begin{array}{c}\textbf{The choice among A/B/C/D changes the fate of only TWO proposals: } P\text{-}01 \textbf{ and } P\text{-}15.\\ \textbf{Fifteen of seventeen are independent of it, or depend on something else.}\end{array}}$$

`[INF]` **`P-06` is lower-stakes for the proposal register than it appeared — and higher-stakes for
migration.** §10.

## 10. Migration consequences *(described; no migration performed)*

| | records after | what changes in the eight | cost |
|---|---:|---|:--:|
| **A** | **~16–17** | `K`→`K`+`𝒦` · `δ`→`δ`+constant · `Zero`→`Zero`+`0_i` · `Π`→**≥5** · `Σ`→`Σ`+`Σ_Λ` · `≡_sem`→+5 siblings · `ℐ`(+`ℛ_req`?) · `Qualify` unchanged. **Every ambiguous disposition must be resolved or given a holding record** | 🔴 **high** |
| **B** | **8** | each record gains a **candidate layer**; `kind`/`status`/`Latest` move down two levels | 🟡 medium |
| **C** | **~24** | 8 glyph records **+** ~16 candidate records **+** the disposition links between them | 🔴 **highest** |
| **D** | **8** | keys unchanged; `kind`/`status`/`grounding`/`Latest` move to definitions; each definition gains `belongs_to_candidate:` | ⭐ **lowest** |

⚠️ **Under A and C, the 4 ambiguous `K` dispositions and `Π`'s R5/R6 would each need a decision or a
holding record — the migration would force identity choices the evidence does not settle.** Under B
and D they remain fields and stay unresolved. **That is a consequence, not an argument.**

⚠️ **`Zero` `D-03` must split into four under every alternative** (`F-5`), and `Σ` `D-05` must be
re-marked as code-sourced under every alternative (`F-4`). **Those two are independent of `P-06`.**

## 11. Second-pass corpus consequences

**Once `P-06` is decided, the Corpus Revisit would look for what the first pass structurally could not
represent:**

| | what to search for | which defect it answers |
|---|---|---|
| 1 | **definitions collapsed into one** — a source saying *"the N readings"* | `F-5` (`Zero`) |
| 2 | **candidates hidden under one glyph** — containment (`X = (…Y…)`) as a detector | `F-7`, `A1` |
| 3 | **implementation-only readings** — code with no textual definition | `F-4` (`Σ`) |
| 4 | **glyph forms wrongly merged** — script and subscript variants | `Q3` |
| 5 | **models-of vs realizations** — placeholders, stubs, `"NOT ENUMERATED"`-style labels | `F-6` (`ℐ`) |
| 6 | **abandoned and superseded definitions** — and whether they are still implemented | `P-11`, `Σ`/`Σ₀` |
| 7 | **relationships visible only after candidate identity separates** | `𝒦`~`K`, `ℐ`~`ℛ_req` |
| 8 | **occurrence-level dispositions** that the counts hid | `Q2` |

⚠️ **Two constraints the second pass must carry, both evidenced in this session:**
**(i) re-measure, never re-cite** — I carried a false claim six days and a stale figure from a
five-day-old artifact; **(ii) `P-16`** — extraction artifacts are not corpus evidence for existence,
definition, implementation, status, relationship **or absence.**

---

## Non-conclusions

⛔ **`P-06` not decided · no alternative recommended · `Q2` and `Q3` not decided · `Q4` unresolved ·
Schema v3 not begun · no record modified · v2 unmodified · no proposal applied · no migration
performed · no definitions adjudicated · no candidates merged · no carrier · no kernel · `𝒪_core` not
frozen · no `OQ-1` · no canonical KnowledgeOS theory · 3MC, `dimension-registry.md`, MD-017, MD-018
and the brainstorming corpus unmodified.**

⚠️ **The four alternatives are not exhaustive** — they are the models the *present* evidence implies.
A fifth may be demanded by evidence not yet extracted.

# **`P-06` DECISION READY — NO DECISION MADE**

## The exact questions a human decision-maker must answer

| # | question | note |
|---|---|---|
| **1** | **What does one record identify — a candidate (A), a glyph (B), both as separate types (C), or a glyph dossier with candidate as a field (D)?** | this is `P-06` |
| **2** | **`Q2`** — is an **occurrence** a record or a field? | trades **auditability against volume**; evidence supports both |
| **3** | **`Q3`** — is base-vs-subscripted glyph part of glyph identity, a sibling relation, or script variance? | ⚠️ **and are `ℐ`/`𝓘` and `≡_sem`/`≡_H` the same phenomenon?** They may not be |
| **4** | **`Q4`** — defer the obligation level, or accept one instance? | `[OPEN]`; **its key link is inadmissible under `P-16`** |
| **5** | Under A or C, **how are the 4 ambiguous `K` dispositions and `Π`'s R5/R6 handled** — forced decision, or holding records? | the migration would otherwise force identity choices the evidence does not settle |
| **6** | Should **`P-01` and `P-15`** be applied, or allowed to dissolve? | **the only two proposals the choice discriminates** |
| **7** | Do **`F-4`** (`Σ` `D-05` code-sourced) and **`F-5`** (`Zero` `D-03` = four) get repaired **now**, given they are **independent of `P-06`**? | they need repair under **every** alternative |

**Stopping here. No decision, no v3.**
