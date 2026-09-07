# Proposal / Question to `three_model_convergence` — a possible **`misattribution`** relationship

**From:** KnowledgeOS Theory Extraction · **To:** the `three_model_convergence` lane (MD-017's owner)
**2026-09-07 · `[OPEN]` — a question, not an amendment**

> ## ⛔ Nothing in `three_model_convergence` is modified by this document
> **`dimension-registry.md`, MD-017, MD-018, `machine-record-schema.md`, `protocol.md`,
> `revisions.md`, `classification-register.tsv` and every other 3MC artifact are untouched.**
> `misattribution` currently exists **only** in Theory Extraction's own local vocabulary
> (`04-SCHEMA-v2` §4), declared as local. **This document asks a question and proposes nothing to any
> governed artifact.**

---

## 1. Exact evidence locations

| | artifact | locus |
|---|---|---|
| **`D-01`** | `brainstorming/mathematical_ideas_that_can_be_implemented/`<br>`20260902-182003_consolidated-architectural-audit-and-ratification-adjustment.md` | **§3.1, line 694** (definition) · also **§118, §120, §127, §597, §614** |
| **`D-03`** | same directory,<br>`20260902-182009_review-as-senior-mathematician.md` | **§8, lines 429–456** |
| **the destination slot** | `phase_measure_theory/…/research/REFINED-STEP-290.md` · `step_261` | `𝔎 = (K, =_str, ≡_sem, **≈_obs**, SameId, ≡_H, ≡_P)` — `261.20` / `261.25` |
| **MD-017, as adopted** | `three_model_convergence/14_decision-log/model-boundary-decisions.md` | **line 838** · schema addendum `01_source-analysis/machine-record-schema.md` **lines 138–160** |
| **extraction record** | `docs/knowledgeos/theory-extraction/elements/KOS-T-0003-eq-sem.md` | `D-01`, `D-03`, and the protest |

## 2. The two definitions involved, verbatim

### `D-01` — the definition (CLOSURE-4 §3.1)

> *"Two knowledge states `K_1` and `K_2` are **Semantically Equivalent** relative to query set `Q`,
> context frame `Γ`, and operation sequence `𝒪` if and only if all observable evaluation and
> determination profiles match:"*

$$K_1 \equiv_{\text{sem}}^{Q,\Gamma,\mathcal O} K_2 \iff \forall q \in Q,\ \forall o \in \mathcal O,\quad \mathrm{Det}(\mathrm{EVal}(K_1,q,\Gamma),q,\Gamma) = \mathrm{Det}(\mathrm{EVal}(K_2,q,\Gamma),q,\Gamma)$$

### `D-03` — the statement about `D-01` (review §8)

> *"The document defines `K_1 ≡_sem^{Q,Γ,O} K_2` iff their determinations match for all `q ∈ Q` and
> operations `o ∈ O`. **This is useful, but I would not call it general semantic equivalence.**
> It is closer to `K_1 ≈_{Q,Γ,O} K_2` = **contextual observational equivalence**. Why? Because two
> representations can produce the same answers for the selected queries while differing in other
> observations. … **This is a naming and scope correction**, but an important one."*

⭐ **The source characterises the relationship itself: *"a naming and scope correction."*** It does not
dispute the biconditional; it disputes **which named relation the biconditional defines.**

## 3. Why **`refinement`** is insufficient

**MD-017's own test** (decision log, line 838, value 4):

> *"**Refinement** — an existing concept receiving **additional semantics** (tightened, softened,
> narrowed, or extended, **without changing its referent**)."*

| the test | applied to `D-01 → D-03` |
|---|---|
| *additional semantics?* | 🔴 **none.** The biconditional is **unchanged** — `D-03` restates it and adds no condition, no parameter, no constraint |
| *tightened / softened / narrowed / extended?* | 🔴 **the formula is not touched.** What narrows is the **claim about** the formula |
| *without changing its referent?* | 🔴 **the referent is exactly what changes** — the formula is said to denote **`≈_obs`**, not `≡_sem` |

$$\boxed{\begin{array}{c}\textbf{Refinement changes the CONCEPT and keeps the definition attached to it.}\\ \textbf{Here the CONCEPT is untouched and the DEFINITION is re-attached.}\\ \textbf{The extension of } D\text{-}01 \textbf{ is bit-identical before and after.}\end{array}}$$

⚠️ **`refinement` is the nearest available value, and recording it would assert that `≡_sem` now means
something narrower — which is the opposite of what `D-03` says.** `D-03` leaves `≡_sem`'s meaning
entirely open and **moves the formula elsewhere.**

## 4. Why **`unresolved_equivalence`** is insufficient

**MD-017's own test** (value 6):

> *"**Unresolved equivalence** — two formulations *might* represent the same underlying thing, but
> equivalence has not been established. **This is the default** …"*

**Two independent failures:**

**(a) It understates the evidence.** `unresolved_equivalence` means *we do not know the relationship.*
Here the source **states it positively**: `D-01` is **correct**, and it is **filed under the wrong
name**, with the correct name given (`≈_{Q,Γ,𝒪}`) and an existing destination slot (`≈_obs` in
`261.25`'s seven). **Recording "we do not know" discards an endorsement and an attribution claim that
the source makes explicitly.**

**(b) It is a level mismatch — and this is the structural reason.**

| | MD-017's six values | `D-01 → D-03` |
|---|---|---|
| relate | **two candidates** to each other | a **statement** to a **definition** |
| level | **object-level** | ⭐ **meta-level** — `D-03` is *about* `D-01` |
| symmetry | **symmetric** *(A and B might be the same)* | ⭐ **asymmetric** *(`D-03` is about `D-01`; not conversely)* |

$$\boxed{\begin{array}{c}\textbf{All six MD-017 values are SYMMETRIC, OBJECT-LEVEL relations between candidates.}\\ D\text{-}03 \textbf{ is an ASYMMETRIC, META-LEVEL statement about a definition.}\\ \textbf{The vocabulary is not deficient — it is addressed to a different level.}\end{array}}$$

`[INF]` **That reading matters for the answer**: if MD-017 is *deliberately* object-level, the right
answer may be **"no — meta-level statements belong somewhere else in 3MC"**, not an extension. **We do
not know which, and it is 3MC's call.**

## 5. The proposed semantic distinction

`[PROP]` **offered for 3MC to accept, reject, or reshape:**

```
misattribution(A, B)
    B asserts that A is CORRECT as a definition,
    and that A is attributed to the WRONG named concept.

  preserves   A's extension — unchanged, bit-identical
  changes     WHICH concept A defines
  direction   asymmetric — B is about A
  requires    a named destination for A  (here: ≈_obs, existing in 261.25)
  is NOT      "A is wrong"          (≠ contradiction)
  is NOT      "A means more/less"   (≠ refinement)
  is NOT      "A and B may be one"  (≠ unresolved_equivalence)
  is NOT      "A restated"          (≠ new_representation)
```

### Against the six, side by side

| MD-017 value | why it is not this |
|---|---|
| `new_concept` | `D-03` introduces no new entity — `≈_obs` **already exists** in `261.25` |
| `new_representation` | asserts **same referent, different shape**. Here the shape is identical and **the referent is disputed** |
| `new_decomposition` | nothing is split |
| `refinement` | §3 — no additional semantics; the referent is what changes |
| `contradiction` | 🔴 **they do not conflict — `D-03` endorses `D-01`** |
| `unresolved_equivalence` | §4 — understates the evidence, and is object-level |

### The question put to 3MC

> **Should MD-017 (or the 3MC relationship vocabulary generally) be extended to represent
> misattribution — a definition endorsed as correct while its attribution is disputed?**
>
> **Or is MD-017 deliberately object-level, such that meta-level statements about definitions belong
> to a different 3MC mechanism (`revisions.md`, a per-file record field, or none)?**

**Both answers are acceptable to Theory Extraction.** If the answer is *no extension*, Extraction will
keep `misattribution` strictly local and cite MD-017 unchanged.

## 6. Theory Extraction is **not** changing 3MC

- **no 3MC artifact is modified by this document** — verified: `dimension-registry.md`, MD-017,
  MD-018, `machine-record-schema.md`, `protocol.md`, `revisions.md`,
  `classification-register.tsv`, all `per-file/` records, untouched;
- `misattribution` is declared **local to Theory Extraction** in `04-SCHEMA-v2` §4 and §"What v2 does
  not change";
- in `KOS-T-0003` the pair is recorded as **`unresolved_equivalence` with an explicit written
  protest**, exactly as the current handling requires — **the local value is used in the commentary,
  never substituted for the MD-017 value**;
- **3MC remains the owner of MD-017 and the sole authority on its vocabulary.**

## 7. No theory conclusion follows from this finding

**Explicitly, none of the following is established, implied, or advanced by this document:**

| ⛔ not established |
|---|
| that CLOSURE-4's definition **is** `≈_obs` |
| that `≡_sem` means anything in particular — **it remains `[OPEN]`, TODO Group D** |
| that `CR-4` is closed, or that its options are narrowed |
| that `D-01` or `D-03` is correct, preferred, or adjudicated |
| that CLOSURE-4 is ratified, or that its rejection by two reviews is settled |
| that `261.25`'s seven relations are exhaustive, or that `≈_obs` is vacant |
| that any relationship between `D-01` and `D-03` is **proven** — the record still says `unresolved_equivalence` |

$$\boxed{\begin{array}{c}\textbf{This is a VOCABULARY question about how to RECORD a relationship.}\\ \textbf{It is not a claim about semantic equivalence, and it decides nothing about } \equiv_{sem}.\end{array}}$$

**And it carries no consequence for `OQ-1`, the carrier question, kernel selection, `𝒪_core`, or
Theory v1.3.**

## 8. What Extraction will do with either answer

| 3MC answers | Extraction does |
|---|---|
| **extend MD-017** | adopt the 3MC value, **retire the local one**, and re-record `KOS-T-0003`'s pair under it |
| **no — meta-level belongs elsewhere** | keep `misattribution` **strictly local**, cite MD-017 unchanged, and record 3MC's reasoning as the disposition |
| **reshape it** | adopt whatever 3MC defines; **the local value is provisional in either direction** |
| **defer** | leave `unresolved_equivalence` + protest standing; **the record is already correct under the current vocabulary** |

⚠️ **No answer blocks extraction.** `KOS-T-0003` is complete and valid today; the protest is the
record of the limitation, and this proposal is the escalation of it.
