# 01 — Method and Provenance

**2026-09-07.** Research question as commissioned: *the Gītā philosophical interpretation — find the
candidates for KnowledgeOS and for the KnowledgeOS kernel, and write the findings systematically.*

---

## 1. Provenance first — this is not first contact

Under `../CORPUS-SEARCH-RULE.md` the whole corpus was searched before any candidate was proposed.
**The Gītā strand is one of the most heavily worked in the estate.**

| Location | Gītā-bearing files |
|---|---:|
| `three_model_convergence/01_source-analysis/` | **439** |
| `phase_measure_theory/` (incl. `knowledgeos_kernel/`) | **335** |
| `mathematical_ideas_that_can_be_implemented/` | 64 |
| `brainstorming/` (root) · `kernel/` | 73 |
| `reviews/` · `reviews/synthesis/analysis/` | 41 |
| `architecture/` · `verification/` | 44 |
| **`external_research/` — HPA chapter analyses** | **20 of 18 chapters** *(2 duplicates)* |

**Dedicated candidate artifacts already exist**, in the kernel research lane:
`18` (candidates) · `19` (tiering completed, all 18 chapters) · `20` (kernel candidates) ·
`21` (dimension/purification) · `22` · `23` (Ch 10–15 reconstruction) · `24` (Ch 16 + `Qualify`) ·
`25` (track closed, `Guṇa` fork) · `28` (Ch 1 failure modes) · `30` (the eight hold) ·
`35` (missingness carrier) · plus `R2-TRANSLATION-REGISTER` (GK-01…GK-12),
`07-GK-REGISTRY-RECONCILIATION` (20 terms, 6 ID collisions), `D285-8-GITA-RESEARCH-APPENDIX`,
`REFINED-STEP-286` (933 lines), and `KnowledgeOS_Vedanta_Pramana_Lens.md` at the top level.

$$\boxed{\textbf{The candidacy question has already been asked and answered. This document consolidates and INDEPENDENTLY VERIFIES; it does not re-derive.}}$$

`[CORPUS]` **And the estate has formally closed it.** `Theory 13 §6` (2026-09-04), under the heading
*"A closed question, recorded so it is not reopened"*:

> *"The Gītā interpretive strand ran five cycles — contradiction, elimination, reduction, audit,
> bridge. **In none did it produce a kernel primitive**, and in each it produced a question already
> answerable by experiment."*

**Reporting a fresh discovery here would therefore be a defect, not a result.** What follows is the
register, the verification, and the residue that is genuinely still live.

## 2. The promotion chain — the estate's own, not mine

$$\text{correspondence} \nRightarrow \text{type} \nRightarrow \text{primitive} \nRightarrow \text{canonical architecture}$$

*(`REFINED-STEP-286`.)* And the three-way discipline from `35`:

$$\boxed{\text{Gītā interpretation} \neq \text{mathematical proof} \neq \text{DDD domain fact}}$$

## 3. The tier scheme

| Tier | Meaning | Test |
|---|---|---|
| **T0** | REFUTED / `RX` | contradicted, or type-check fails |
| **T1** | correspondence only | a real resemblance; **no KnowledgeOS carrier** |
| **T2** | typed candidate | has a carrier, **not promoted** |
| **T3** | **KnowledgeOS candidate** | a layer · service · operation · policy — **explicitly NOT kernel** |
| **T4** | **KERNEL candidate** | a primitive, a canonical relation, or a kernel component |
| **T4-SHAPE** | *(added by the lane)* | a **well-formed kernel-level form whose slots are empty** |

**The kernel bar, concretely.** The ratified primitives are

$$\{Entity,\ State,\ Event,\ Observation,\ Proposition,\ Relation,\ Policy,\ Action\}$$

`C-022`/step-049, *"50 attack classes, no counterexample."* **A T4 claim must add to that set, add a
canonical relation, or constrain `K` itself.**

## 4. My independent verification of the load-bearing negative claim

The register's headline is *"zero new primitives across 18 chapters."* **I did not take it on
citation.** Two checks over `docs/knowledgeos/`:

**(a) Is the ratified set stated as the eight, and only the eight?**

```
"{Entity, State, Event, Observation, Proposition, Relation, Policy, Action}"
   → 24 files, byte-identical modulo whitespace
```

**(b) Does any Sanskrit term appear INSIDE a stated primitive set?**

```
grep '\{[^}]*(Ātma|Buddhi|Guṇa|Kṣetra|Jñāna)[^}]*\}'
   → 0 hits that are primitive sets
```

Every hit is a **mapping** brace (`{A = Ātma}`), a **doctrine** reference, or — the one interesting
case — *"an epistemic state machine whose state is expressed through **eight primitives** and whose
transformations are **governed by Buddhi**."*

$$\boxed{\textbf{VERIFIED. } Buddhi \textbf{ appears as an OPERATOR OVER the eight, never as a ninth. The "zero new primitives" claim holds under independent search.}}$$

⭐ **And that single sentence is the whole Gītā result in miniature:** the tradition supplies things
that act *on* the state, and the kernel needs things the state is *made of*.

## 5. What this package is not

- **not** a promotion of any candidate · **not** a governance act · **not** an addition to `𝒪_core`
- **not** a reopening of a question the estate has closed — the closure is recorded in `04` §5
- **not** a merge of the two Gītā lanes: their conflict is *reported* (`04` §3), never resolved here
