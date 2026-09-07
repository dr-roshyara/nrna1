# A/B/C/D Evaluated Against the `P-06` Semantic Constraints

**2026-09-07 · decision-support only.**

> ⛔ **No alternative selected · `P-06` not decided · Schema v3 not begun · schema unmodified · no
> record modified · `F-4`/`F-5` not repaired · 3MC / `dimension-registry.md` / MD-017 / MD-018
> untouched · no definition or candidate adjudicated · no carrier · no kernel · no canonical theory.**
>
> **`Q3` (base-vs-subscripted) and `Q4` (obligation level) are carried as OPEN/DEFERRED throughout,
> as instructed. `ℐ`/`𝓘` and `≡_sem`/`≡_H` are NOT assumed to be the same phenomenon.**

**The constraints, as given, labelled for reference:**

| | constraint |
|---|---|
| **C1** | a record must not equate **glyph identity** with **candidate identity**; `Glyph → Occurrence → Definition → Candidate` are distinct levels |
| **C2** | glyph identity representable **independently** of candidate identity; a glyph may refer to several definitions/candidates |
| **C3** | occurrence distinguishable from definition/candidate identity *(record-vs-field OPEN)* |
| **C4** | definitions **independently representable**; one glyph/reading can contain several definitions |
| **C5** | candidate identity **distinct** from definition identity; permits *many-defs → one candidate*, *one glyph → many candidates*, and **no identity inference from glyph equality alone** |
| **C6** | implementation **separate** from theoretical definition; one definition may have several realizations; **code existence ⇏ theory definition** |

---

## 0. ⭐ A finding about `C1` itself, before the alternatives

`C1` lists the levels in this order:

$$\text{Glyph} \rightarrow \text{Occurrence} \rightarrow \text{Definition} \rightarrow \text{Candidate}$$

**Candidate comes LAST.** That admits two readings, and they are not equivalent:

| reading | meaning | consequence |
|---|---|---|
| **R-list** | four distinct levels, **listed in no particular dependency order** | all four alternatives remain open |
| ⭐ **R-derivation** | **a candidate is what definitions CONVERGE ON** — it is *derived from* definitions, not a container of them | 🔴 **narrows the field sharply** — see below |

**If `R-derivation` is intended:**

- **Alternative A** keys records by candidate — so it **requires candidate identity as an INPUT**.
  ⚠️ But candidate identity is precisely what is unsettled for `K`'s four ambiguous readings and
  `Π`'s R5/R6. **A would demand the analysis's output as its own precondition.**
- **Alternative B** nests candidate *inside* glyph — which **inverts the derivation**: the candidate
  would be a child of the glyph rather than a convergence of definitions.
- **Alternative D** matches `R-derivation` **exactly**: glyph is the key, definitions hang off it, and
  each definition declares `belongs_to_candidate:` — the candidate **emerges from** the definitions.
- **Alternative C** is compatible with either direction, since candidate records are created **only
  when** a candidate is confirmed.

$$\boxed{\begin{array}{c}\textbf{Which reading of } C1 \textbf{ is intended is itself a decision input,}\\ \textbf{and it discriminates more sharply than any other single answer.}\end{array}}$$

⚠️ **I do not assume either reading.** It is carried into §11 as the **first** question.

---

## 1. Alternative **A** — candidate-keyed

| # | assessment |
|---|---|
| **1 preserves** | ⭐ **candidate identity as a first-class object** — the only alternative where a candidate can carry its own `kind`, `status`, `Latest`, dependencies and relations natively. `many-defs → one candidate` is native |
| **2 risks collapsing** | 🔴 **glyph-level information.** `Σ`'s **143 symbolic occurrences** have no home unless glyph becomes a cross-cutting index; `≡`'s base-glyph family and `ℐ`/`𝓘` likewise. 🔴 **And ambiguous readings have no record at all** unless a holding-record type is added |
| **3 satisfies** | `C1` ✅ · `C2` ⚠️ *(glyph demoted to a field — "independently representable" only weakly)* · `C3` ✅ *(field)* · `C4` ✅ · `C5` ✅ **strongest** · `C6` ✅ |
| **4 needs added** | a **glyph index**; a **holding-record type** for ambiguous identity; an occurrence field; a definition-**source** field for `F-4` |
| **5 migration forces identity?** | 🔴🔴 **YES — and this is decisive against the critical criterion.** `K`'s 4 ambiguous and `Π`'s R5/R6 must be **assigned to a candidate to be recorded at all** |
| **6 represents the 8 without adjudicating?** | 🔴 **NO** — not `K`, not `Π` |
| **7 the four cases** | `Zero` multi-def ✅ · `Π` multi-candidate ✅ *(its strength)* · `Qualify` multi-realization ✅ · `Σ` impl-only ⚠️ *(needs the source field)* |
| **8 new assumption** | 🔴 ⭐ **candidate identity is DETERMINABLE AT RECORD-CREATION TIME.** **False for 6 readings across two records** |
| **9 proposals** | `P-01` **dissolves** · `P-15` **dissolves** · `P-14` **dissolves** · `P-02` survives · rest independent |
| **10 records** | **~16–17, plus holding records → ~20+** |

## 2. Alternative **B** — glyph-keyed, candidates nested

| # | assessment |
|---|---|
| **1 preserves** | ⭐ **glyph level natively** — occurrences, symbolic counts, base-glyph siblings all have a home. Candidate exists as a nested entity, so it can carry properties |
| **2 risks collapsing** | 🔴 ⭐ **a candidate that spans GLYPHS.** If `ℐ` and `ℛ_req` are one candidate under two names, **B cannot represent it** — the candidate lives inside one glyph record. **This is B's characteristic loss** |
| **3 satisfies** | `C1` ✅ · `C2` ✅ **strongest** · `C3` ✅ · `C4` ✅ · `C5` ⚠️ **partially — *one glyph → many candidates* ✅, *one candidate → many glyphs* ✗** · `C6` ✅ |
| **4 needs added** | a **cross-glyph candidate link**; a definition-source field |
| **5 migration forces identity?** | ✅ **No** — an ambiguous reading stays nested and unresolved |
| **6 represents the 8 without adjudicating?** | ✅ **Yes** |
| **7 the four cases** | `Zero` ✅ · `Π` ✅ · `Qualify` ✅ · `Σ` ⚠️ |
| **8 new assumption** | 🔴 ⭐ **a candidate belongs to exactly ONE glyph.** ⚠️ Not yet falsified, **but `ℐ`~`ℛ_req` is the prospective counterexample and `Q4` keeps it open** |
| **9 proposals** | `P-01` **survives** *(definitions under one glyph still differ in kind)* · `P-15` **survives** · `P-14` dissolves · `P-02` survives |
| **10 records** | **8** |

## 3. Alternative **C** — two record types, cross-linked

| # | assessment |
|---|---|
| **1 preserves** | ⭐⭐ **everything the evidence contains** — glyph and candidate both first-class, dispositions as the link; *one candidate → many glyphs* and *one glyph → many candidates* both native |
| **2 risks collapsing** | ✅ **nothing structurally.** 🔴 **The risk is DRIFT** — two record sets that can disagree, and no mechanism yet proposed to keep them consistent |
| **3 satisfies** | `C1` ✅ · `C2` ✅ · `C3` ✅ *(and it is the only one where "occurrence = record" is natural)* · `C4` ✅ · `C5` ✅ **fully, both directions** · `C6` ✅ |
| **4 needs added** | a **link/disposition entity**; a **consistency rule** between the two sets |
| **5 migration forces identity?** | ✅ **No — under one rule:** *candidate records are created only for CONFIRMED candidates.* ⭐ **Ambiguity then lives at glyph level, and confirmation promotes it.** Without that rule, C forces identity exactly as A does |
| **6 represents the 8 without adjudicating?** | ✅ **Yes, and most completely** |
| **7 the four cases** | ✅ all four, including `Σ`'s impl-only reading as a **glyph-level** occurrence with no candidate record |
| **8 new assumption** | ⚠️ **that two record sets can be kept consistent.** A cost, not a semantic commitment |
| **9 proposals** | `P-01` ⭐ **TRANSFORMS** *(dissolves for candidate records; **survives for glyph records**, which still hold multi-kind readings)* · `P-15` dissolves for candidates, survives for glyphs · `P-14` dissolves · `P-02` survives |
| **10 records** | **~24** — 8 glyph + ~16 candidate, plus links |

## 4. Alternative **D** — glyph dossier, candidate as a field

| # | assessment |
|---|---|
| **1 preserves** | ⭐ **every distinction, as fields.** Glyph key stable; ⭐ **matches `C1`'s `R-derivation` reading exactly** — candidate emerges from definitions |
| **2 risks collapsing** | 🔴 ⭐ **candidate identity has no independent existence.** A candidate cannot carry its own status, dependencies or **relations to other candidates** — `𝒦` can be **named** but not **described**. **This is D's characteristic loss** |
| **3 satisfies** | `C1` ⚠️ *(distinguishes the levels; does not grant candidate an identity)* · `C2` ✅ · `C3` ✅ · `C4` ✅ · `C5` ⚠️ *(both mappings expressible as field values; **"candidate identity distinct from definition identity" only weakly — a field is not an identity**)* · `C6` ✅ |
| **4 needs added** | `belongs_to_candidate:` per definition; **per-definition `kind`, `status`, `Latest`** — i.e. **`P-01` + `P-02` + `P-15` applied together**; a definition-source field |
| **5 migration forces identity?** | ✅ **No** — an ambiguous reading carries a null or `ambiguous` field value |
| **6 represents the 8 without adjudicating?** | ✅ **Yes** |
| **7 the four cases** | `Zero` ✅ · `Π` ✅ *(as field values)* · `Qualify` ✅ · `Σ` ⚠️ |
| **8 new assumption** | 🔴 ⭐ **candidate identity does not need to be an ENTITY.** ⚠️ **Testable, and currently unfalsified:** nothing in the eight records describes `𝒦` or `0_i` beyond their definitions. **But `P-10` `co-obligation` would be a relation BETWEEN candidates**, which under D would hold between *field values* |
| **9 proposals** | `P-01` **required** · `P-02` **required** · `P-15` **required** · `P-14` dissolves |
| **10 records** | **8** |

---

## 5. Constraint-satisfaction matrix

| | `C1` | `C2` | `C3` | `C4` | `C5` | `C6` |
|---|:--:|:--:|:--:|:--:|:--:|:--:|
| **A** candidate-keyed | ✅ | ⚠️ | ✅ | ✅ | ✅ | ✅ |
| **B** glyph-keyed nested | ✅ | ✅ | ✅ | ✅ | ⚠️ **one direction only** | ✅ |
| **C** dual records | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **D** glyph dossier | ⚠️ | ✅ | ✅ | ✅ | ⚠️ **field, not identity** | ✅ |

⚠️ **`C6` is satisfied by all four and by none of them alone** — every alternative still needs a
**definition-source field** to prevent `F-4` (`Σ D-05`, code recorded as a definition). **That
requirement is independent of `P-06`.**

## 6. Against the critical criterion

> **Preserve maximum information without forcing an identity choice the evidence does not settle.**

| | forces an unsettled identity choice? | information lost |
|---|:--:|---|
| **A** | 🔴 **YES** — `K`'s 4 ambiguous, `Π`'s R5/R6 must be assigned a candidate to exist | glyph level, unless indexed |
| **B** | ✅ no | 🔴 **one candidate → many glyphs** |
| **C** | ✅ no *(given the confirmed-candidates-only rule)* | ✅ **none structurally**; cost is **drift risk** |
| **D** | ✅ no | 🔴 **candidate cannot be described or related** |

$$\boxed{\begin{array}{c}\textbf{On the stated criterion, } A \textbf{ is the only alternative that FORCES an unsettled choice.}\\ B, C, D \textbf{ each satisfy it and differ in WHAT THEY CANNOT SAY.}\end{array}}$$

⛔ **This is not a selection.** It reports how the four stand against the criterion **as stated**, and
the criterion is one input among the answers still to be given.

## 7. Proposal impact — `P-01` … `P-24`

| ID | A | B | C | D |
|---|---|---|---|---|
| `P-01` kind@definition | **dissolves** | **survives** | ⭐ **transforms** *(glyph records still need it)* | **required** |
| `P-02` status@definition | survives | survives | survives | **required** |
| `P-15` `Latest` ownership | **dissolves** | **survives** | **transforms** | **required** |
| `P-14` comparison arms | dissolves | dissolves | dissolves | dissolves |
| `P-05` placeholder | transforms | transforms | transforms | transforms |
| `P-03` `P-07`–`P-13` `P-16`–`P-24` | **independent** | independent | independent | independent |
| `P-04` | dep. `P-11` | dep. `P-11` | dep. `P-11` | dep. `P-11` |
| `P-10` co-obligation | dep. `Q4` | dep. `Q4` | dep. `Q4` | ⚠️ dep. `Q4` **and on D's field-not-entity assumption** |
| `P-06` | **is the decision** | — | — | — |

**Unchanged from the dossier:** the choice discriminates **only `P-01` and `P-15`** — now with the
refinement that under **C** they **transform** rather than dissolve, because glyph records still carry
multi-kind readings. ⭐ **`P-10` acquires a new dependency under D only.**

## 8. Migration implications *(described; none performed)*

| | records | what must change in the eight | forced identity decisions |
|---|---:|---|:--:|
| **A** | **~20+** | split `K`→`K`+`𝒦`, `δ`→+constant, `Zero`→+`0_i`, `Π`→**≥5**, `Σ`→+`Σ_Λ`, `≡_sem`→+5 siblings; **plus holding records** | 🔴 **6 readings** |
| **B** | **8** | each gains a nested candidate layer; `kind`/`status`/`Latest` move down two levels | none |
| **C** | **~24** | 8 glyph records retained **+** ~16 candidate records **+** links | none *(under the rule)* |
| **D** | **8** | keys unchanged; `kind`/`status`/`grounding`/`Latest` move to definitions; each gains `belongs_to_candidate:` | none |

⚠️ **Independent of the choice, under every alternative:** `Zero` `D-03` **must split into four**
(`F-5`) and `Σ` `D-05` **must be re-marked as code-sourced** (`F-4`). **Not repaired here.**

## 9. New conceptual assumptions, side by side

| | the assumption each introduces | falsified today? |
|---|---|:--:|
| **A** | candidate identity is **determinable at record-creation time** | 🔴 **YES — 6 readings** |
| **B** | a candidate belongs to **exactly one glyph** | ⚠️ **not yet — `ℐ`~`ℛ_req` is the prospective counterexample, and `Q4` keeps it open** |
| **C** | two record sets can be kept **consistent** | ⚠️ **untested — a cost, not a semantic claim** |
| **D** | candidate identity **need not be an entity** | ⚠️ **not yet — nothing describes `𝒦` or `0_i` beyond their definitions; but `P-10` would need candidates to be relatable** |

⭐ **Only A's assumption is already falsified by the existing records.** B's and D's are **prospective**,
and both hinge on questions the instructions keep **open** (`Q4`, and whether candidates need
properties of their own).

## 10. On a possible `P-25`

⛔ **No new proposal is registered.** The two live issues this evaluation surfaced —

1. **which reading of `C1`'s ordering is intended** (`R-list` vs `R-derivation`), and
2. **whether a candidate must be able to carry its own properties and relations**

— are **components of `P-06` itself**, not separate amendments. **Registering them would duplicate the
decision.** Both appear in §11 instead.

⚠️ **And "Representation must not silently adjudicate ontology"** — offered earlier — remains
**unregistered**, since it is a governance principle overlapping `P-23`'s territory and adding it is
itself a decision.

---

# **A/B/C/D EVALUATED — `P-06` DECISION STILL OPEN**

## The smallest set of questions that selects among the alternatives

**Four. Each is answerable from the evidence already in hand, and together they determine the choice.**

| # | question | what it settles |
|---|---|---|
| **1** | ⭐ **Is `C1`'s ordering `Glyph → Occurrence → Definition → Candidate` DEPENDENCY-BEARING** *(a candidate is derived from definitions)* **or merely a list of distinct levels?** | if dependency-bearing: **A is precluded** *(it needs candidate identity as input)* and **B is strained** *(it inverts the derivation)*; **D matches it exactly**; **C remains compatible** |
| **2** | ⭐ **Must a CANDIDATE be able to carry its own properties and relations** — status, dependencies, and relations to other candidates such as `P-10` `co-obligation`? | **yes ⇒ D is precluded** *(a field cannot be related)*; **no ⇒ D is admissible** and is the minimum-change model |
| **3** | ⭐ **Must ONE candidate be representable under MULTIPLE glyphs?** | **yes ⇒ B is precluded** *(its candidates are glyph-local)*; ⚠️ **and note this is entangled with `Q4`, which is DEFERRED — `ℐ`~`ℛ_req` is the only instance** |
| **4** | **Is the maintenance cost of two cross-linked record sets acceptable — and is the rule *"candidate records only for CONFIRMED candidates"* adopted?** | **no ⇒ C is precluded**; ⚠️ **and without that rule, C forces unsettled identity exactly as A does** |

### How the four questions interact

```
Q1 dependency-bearing?  ──yes──►  A precluded · B strained · D matches · C compatible
                        ──no───►  all four remain open
Q2 candidate needs properties?  ──yes──►  D precluded
Q3 candidate across glyphs?     ──yes──►  B precluded      ⚠️ entangled with deferred Q4
Q4 two-set cost acceptable?     ──no───►  C precluded
```

⚠️ **Answering all four "yes" would preclude A, B and D and leave C. Answering `Q2` and `Q3` "no"
would leave B and D. Neither outcome is recommended here** — the mapping is stated so the decision is
visible rather than emergent.

⛔ **No alternative is selected. `P-06` remains open. Schema v3 not begun.**
