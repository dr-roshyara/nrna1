# KnowledgeOS Theory Extraction — Project Charter

**2026-09-07 · `[DEF]` charter · placement DERIVED, not chosen:**
`php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → **`docs/knowledgeos`**

> **Purpose.** Extract and organize everything that may constitute KnowledgeOS theory from the
> brainstorming corpus, **without prematurely selecting among competing definitions or directions.**

**Status:** defined, **not started.** Theory v1.2 FROZEN · kernel NOT SELECTED · `OQ-1` **PAUSED**.

---

## 1. The four layers — and what may not cross between them

| layer | question it answers | owns | may **not** do |
|---|---|---|---|
| **ARCHAEOLOGY** | *What did we actually write and discover?* | `three_model_convergence/` | decide what belongs in the theory |
| **EXTRACTION** | *What theoretical knowledge is present?* | **this project** | adjudicate · select · resolve · merge |
| **ADJUDICATION** | *What do we accept as canonical?* | Governance | invent theory that extraction did not find |
| **IMPLEMENTATION** | *How do we build what was accepted?* | engineering | implement anything not adjudicated |

$$\boxed{\textbf{Source archaeology} \neq \textbf{theory extraction} \neq \textbf{theory adjudication} \neq \textbf{implementation}}$$

**The boundary is directional.** Each layer consumes the one above as **evidence** and never as
**authority**.

### The four hard rules (Governance, 2026-09-07)

| rule | meaning |
|---|---|
| **3MC is evidence, not authority** | its registries **cannot define** KnowledgeOS theory |
| **Extraction is not adjudication** | **multiple definitions remain alive** |
| **Implementation is not architectural truth** | **existing code cannot silently ratify theory** |
| **Canonicalization comes last** | **no premature "one true `K`"** |

$$\boxed{\textbf{The corpus is evidence; the theory is an extracted and eventually adjudicated result.}}$$

## 2. 🔴 First consequence: my extension proposal was mis-addressed

`registry-extension-proposal/` proposed adding a **`K` concept entry with ten enumerated definitions**
to `three_model_convergence/01_source-analysis/dimension-registry.md`. **Under the corrected
architecture that is wrong**, and the error is instructive:

| proposal | it is really | belongs to |
|---|---|---|
| `EXT-01` `K` — 10 definitions, relationships classified | **extraction** — *what theory is present* | **this project** |
| `EXT-04` three Gītā candidates | **extraction** | **this project** |
| `EXT-03` `Implementation:` field | **extraction** — theory↔code mapping | **this project** |
| `EXT-02` `Π` second contested axis | **mixed** — *"the glyph occurs with 6 meanings"* is archaeology; *"these are 6 concepts or 1"* is extraction | **split** |
| `EXT-05` `Latest appearance` semantics | **archaeology schema** — it measures the corpus | 3MC, if they want it |

$$\boxed{\textbf{This DISSOLVES the question "may this lane write to } \texttt{dimension-registry.md}\textbf{?" — the answer is that it need not.}}$$

`[INF]` **I had made `three_model_convergence` the owner of KnowledgeOS theory because it was the
only concept-level registry I found.** Being the only one is not the same as being the right one.

## 3. `three_model_convergence` — its boundary, as clarified

**It is an independent, read-only archaeological project.** It answers:

what concepts occur · where they first appeared · how they evolved · which model contains them ·
what definitions exist **in the sources** · what relationships hold between documents · what
contradictions and correspondences occur · what evidence connects documents · where the three models
converge and diverge.

| it owns | it does **not** own |
|---|---|
| the reading manifest, progress and classification registers | which definition of `K` KnowledgeOS has |
| the 1 224 per-file extraction records | what belongs in the theory |
| `dimension-registry.md` as a **corpus-occurrence** record | the canonical concept registry |
| the three model reconstructions (`02_`, `03_`, `04_`) | adjudication of any kind |
| `12_canonical-theory/STATUS.md`'s guard | writing into `12_canonical-theory/` |

**Read-only rule, unchanged:** the brainstorming corpus is **evidence** and is never edited by either
project. **Its own protocol already says so and it stays authoritative for its own lane.**

## 4. This project — scope, inputs, outputs

### Inputs (all consumed as **evidence**, never as authority)

| input | supplies |
|---|---|
| `brainstorming/` — **~3 136 files** | the primary source; **read-only** |
| `three_model_convergence/` | 1 224 per-file records · 27 concept entries · 96 file landmarks · manifests |
| `verification/gap-discovery/` — **this lane's `V1`–`V4` and 9 packages** | implementation evidence · glyph archaeology · experimental evidence · conflict measurement |
| `research/` — **168 `.py` across 3 estates** | **implementation facts** |
| `governance/` | acts already taken (`EPISTEMIC-STATUS-VOCABULARY`, `AST-019`, `KOS-OPERATING-MODEL-001`) |

### Output — the Theory Candidate Set, in 19 categories

```
Concepts · Definitions · Principles · Invariants · Hypotheses · Conjectures ·
Formal results · Evidence · Experiments · Constraints · Requirements ·
Alternatives · Conflicts · Gaps · Open questions · Decisions ·
Rejected ideas · Historical ideas · Implementation mappings
```

⚠️ **The 19 are a starting hypothesis, not a fixed schema.** Some may be empty; some may need
splitting; a category the corpus demands and this list lacks is itself a finding. **Emptiness is
recorded, never filled.**

### Record schema — reusing the existing vocabularies, inventing none

**Per the corpus rule and ES-005.4, this project defines no new status vocabulary.** It reuses:

| borrowed from | vocabulary |
|---|---|
| `governance/EPISTEMIC-STATUS-VOCABULARY.md` **(ADOPTED 2026-09-04)** | `[DEF] [THM] [COR] [PRP] [EXP] [CONJ] [NEG] [OPEN] [REC] [DEFECT]` · **an untagged mathematical statement is a defect** |
| 3MC protocol §3 | `[SR] [DR] [DF] [HP] [CG] [PR] [TH] [EX] [AN] [UN] [OP] [RF] [CT]` |
| 3MC **MD-017** | relationship taxonomy: `new_concept · new_representation · new_decomposition · refinement · contradiction · unresolved_equivalence` — **`unresolved_equivalence` is the DEFAULT** |
| 3MC **MD-018** | status chain `candidate → supported → corroborated → formally_defined → operationally_defined → canonical` |
| 3MC consolidation audit | `Grounding:` — `architecturally-grounded · philosophical-correspondence-only · reframing · mixed · contested-ungrounded` |
| **this project adds only** | **`Implementation:`** — `none · type-exists · operation-runs · result-produced-on-it`, with estate · class · file |

⚠️ **`Implementation` has four levels because *"implemented"* is three different claims** — a type
existing, an operation running, and a result having been produced on it are not the same, and
`EXT-03` recorded that as an open question.

**A theory element record:**

```
ID                KOS-T-nnnn                    ← stable, this project's namespace
Concept
Category                                        ← one of the 19, with reason
Definitions       D-01 … D-0n                   ← ENUMERATED, never merged
Relationships     per pair, MD-017              ← unresolved_equivalence unless evidenced
Source            first appearance · files
Latest            mention | refinement | implementation | governed-decision   ← all four, uncollapsed
Evidence          corpus · experimental · none
Implementation    none | type-exists | operation-runs | result-produced-on-it
Dependencies      what this element presupposes
Conflicts
Gaps
Open questions
Status            13-value · MD-018 chain · Grounding
Confidence
Implementation consequence
```

## 5. Method

```
1  inventory       what elements exist, from 3MC records + targeted corpus search
2  classify        one of the 19 categories, with a written reason
3  enumerate       every definition, D-01…D-0n — no merging
4  relate          MD-017 per pair; unresolved_equivalence is the default
5  evidence        corpus · experimental · implementation, kept separate
6  gaps            what is demanded and absent — after a complete-corpus search
7  hand off        to Adjudication, deciding nothing
```

**Binding on every step:** [`CORPUS-SEARCH-RULE.md`](../brainstorming/verification/gap-discovery/CORPUS-SEARCH-RULE.md)
— the whole `brainstorming/` tree is the search universe · **multiplicity must be discovered before
absence is claimed** · a zero measures a **scope and a pattern**, never a theory.

## 6. Prohibitions

| ⛔ this project may not | because |
|---|---|
| choose between definitions | that is Adjudication |
| resolve a contradiction | Extraction records it |
| select a carrier — **`OQ-1` is out of scope** | a governance decision, and it must be evaluated against the **whole** theory |
| invent missing theory | a gap is a finding, not a task |
| promote a hypothesis to a principle | MD-018: no entry skips a stage |
| turn a proposal into a requirement | *"we should represent knowledge as `(𝒳,𝒜)`"* is a **proposal**; *"KnowledgeOS represents knowledge as `(𝒳,𝒜)`"* is a **decision**; *"experiments used `(𝒳,𝒜)`"* is an **implementation fact** — **three different records** |
| collapse historical and canonical | `Historical ideas` is its own category |
| merge candidates on resemblance | MD-017's default is `unresolved_equivalence` |
| normalize terminology | glyph normalization is `H1`, a registry act |
| write to `three_model_convergence/` or the corpus | both are read-only to this project |
| write to `12_canonical-theory/` | its guard file forbids it |

## 7. What this re-sequences

```
WAS                                   NOW
1  brainstorming                      1  brainstorming
2  OQ-1 carrier decision   ◀── here   2  three-model convergence   (read-only archaeology)
3  Theory v1.3                        3  KnowledgeOS theory extraction   ◀── here
4  implementation                     4  classification
                                      5  definitions · alternatives · relationships
                                      6  gap & conflict analysis
                                      7  theory candidate set
                                      8  governance decisions   ← OQ-1 is ONE of these
                                      9  canonical theory
                                     10  formal specification
                                     11  implementation
```

**`OQ-1` is PAUSED and becomes one decision inside step 8.** The
`docs/plans/20260907-1520-…-forward-programme-plan.md` Phases 1–2 are **superseded in sequence** — its
evidence (`V1`–`V4`) is retained and becomes extraction input. `[REC]` **The plan should be amended,
not deleted**, per ES-004.3: status annotations change; findings and history do not.

⚠️ **Not wasted.** `V1` transformed `OQ-1` from *"choose a carrier"* into *"reconcile-or-ratify"*, and
`V2` produced a witness the programme had never had. **Both stand as evidence at their own layer.**

## 8. Where the existing artifacts land

| artifact | layer | role here |
|---|---|---|
| `carrier-options/` (`V1`) · `oq1-decision-brief/` | archaeology + extraction | **implementation mappings** for `K` and the carriers |
| `glyph-register/` (`V3`) | archaeology | **alternatives / name variants**, at glyph level |
| `oq4-witness/` (`V2`) | **experimental evidence** | one `Evidence` record, scope-bound |
| `lane-conflict/` (`V4`) | archaeology | **conflicts** |
| `gita-candidates/` | extraction | 46 rows — 9 duplicate 3MC, **3 genuinely new**, 34 tiered |
| `readiness/` · `theory-compatibility/` · `step-272/` · `second-order/` · `00`–`17` | extraction | gaps · external-theory compatibility · `Sufficient(F,𝒪,ℐ)` · falsifications |
| `registry-extension-proposal/` | ⚠️ **re-addressed** | `EXT-01/03/04` → **this project**; `EXT-02` splits; `EXT-05` → 3MC |

## 9. First increment — defined, not started

> ### ⚠️ `K` is a **PILOT EXTRACTION CASE**, not a theoretical priority
> **Selected because it exercises many extraction mechanisms simultaneously** — multiple definitions,
> multiple glyph forms, partial implementation, unresolved inter-definition relationships, and an
> empty governed-decision field. **It is NOT selected because `K` is more theoretically fundamental
> than other concepts**, and *"largest measured hole"* must **never** become an implicit
> prioritization principle for the theory.
>
> $$\boxed{\textbf{The first example must not become a privileged theory starting point.}}$$

**What makes it a good pilot:** 27 concept entries exist in 3MC and **none is `K`**; ten definitions
are known; two are implemented; the `𝒦/𝕂/K` script distinction is undeclared; and the *latest governed
decision* field is **empty** — so the record exercises enumeration, relationship classification,
four-level implementation, and all four `Latest` sub-fields at once.

**Deliverable:** one `KOS-T-nnnn` record for `K`, ten definitions enumerated, every pair at
`unresolved_equivalence` except the one evidenced projection, all four `Latest` sub-fields populated,
`Implementation` at the four-level granularity. **Adjudicating nothing.**

## 10. What this charter does not do

- **starts no extraction** — it defines the project
- **invents no vocabulary** — five vocabularies borrowed, one field added, with its reason
- **touches no corpus file, no 3MC artifact, and no governance act**
- **does not deprecate `three_model_convergence`** — it clarifies that it is **archaeology, and
  authoritative there**
- **does not decide `OQ-1`** — it removes it from the immediate path
