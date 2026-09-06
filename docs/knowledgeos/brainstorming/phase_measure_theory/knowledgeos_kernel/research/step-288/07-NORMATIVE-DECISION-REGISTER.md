# 07 — Normative Decision Register (mandate §§6, 20)

> **§20 is binding: this step makes NO governance decision.** No branch is chosen, no `X`, no `λ`, no
> `Deduplicate` criterion, no bindings, no identity policy, no `K`-order, no kernel state.
> Every row below records **formally consistent options** and their consequences. **Nothing is recommended.**

## §6 DECISION 3 — is `Π` part of semantic equality? BOTH branches, no recommendation

**Corpus-native, not my framing:** `261.8` states both readings; `254` poses the question.

| Consequence | **Branch A · `Π ∈ ≡`** | **Branch B · `Π ∉ ≡`** |
|---|---|---|
| **effect on `≡`** | provenance-discriminating; finest semantic relation | provenance-blind; coarser |
| **effect on `≅_λ`/`≅_P`** | 🔴 **collapses into `≡`** — becomes redundant | remains a distinct relation |
| **effect on `D285-5`** | 🔴 **property VACUOUS** (antecedent unsatisfiable) | **SUBSTANTIVE** |
| **effect on `D285-6`** | 🔴 **projection illegitimate as stated** | legitimate |
| **effect on deduplication** | only provenance-identical items dedupe → **near-zero dedupe** | semantic dedupe possible, **provenance lost** unless `≅_P` retained |
| **effect on canonicalization** | canonical form must carry `Π` → **no canonical form across sources** | canonical form possible; `38.85` still binds |
| **effect on provenance** | maximal preservation | needs a **separate** mechanism (`≅_P`) |
| **effect on replay** | ✅ replay-exact (`261.19`: Replay **requires** provenance) | 🔴 **replay may not reproduce** |
| **effect on contradiction handling** | same proposition from two sources = **two** items, not a conflict | conflicts detected on content |
| **effect on operations** | `Supersede`/`Revise` need identity anyway (`261.19`) — unchanged | `Merge` can aggregate support (`25J.20`) |
| **effect on `δ`** | postconditions provenance-sensitive; harder to satisfy | weaker postconditions |
| **effect on governance** | audit-maximal | audit needs `≅_P` alongside |
| **invariant `I-C`** | 🔴 **VACUOUS** | **SUBSTANTIVE** |

### What is genuinely normative vs what follows mathematically

| Component | Class | Note |
|---|---|---|
| *Does any mandatory operation observe `Π`?* | **TECHNICAL** | decidable by the `258-A` congruence experiment: `∃T: F(H_1)=F(H_2) ∧ F(T(H_1))≠F(T(H_2))` — **`258.31`: *"we do NOT ask philosophically"*.** ⚠️ **Blocked on `𝒪`, not on taste.** `258.15` already runs it: **`TraceOrigin` distinguishes; pure audit metadata does not** |
| *Which operations are mandatory?* | **NORMATIVE** | = `𝒪`/`𝒯` ratification |
| *Which of `258.31`'s three repairs* (enrich `K` · supply as context · remove the operation) | **NORMATIVE** | *"only the first two preserve the operation"* |
| everything in the table above, **once** the above are fixed | **MATHEMATICAL** | follows |

> $\boxed{\text{Neither branch is "the architecture". Both are formally consistent. No recommendation.}}$

## The register

| ID | Question | Formally consistent options | Technical consequences | Governance consequences | Status | Authority |
|---|---|---|---|---|---|---|
| **N-1** | 🔴 **Are `≡` and `≈` one relation or two?** | (a) `≈` collapses into `≡`; (b) `≡` gets a new, non-observational definition; (c) rename to remove the glyph clash | (a) register drops to 5, violates *"not interchangeable"*; (b) `≡` currently **has no definition** | the corpus **contradicts itself** — `258.8`/`261.21` vs `261.1`/`246` | 🔴 **OPEN — CRITICAL, newly found** | ARB |
| **N-2** | `Π ∈ ≡`? (Decision 3) | A / B, per §6 | full table §6 | audit strength vs dedupe capability | 🔴 OPEN — **technical half decidable once `𝒪` closes** | ARB + PO |
| **N-3** | Which `𝒪_K`? | any closed observation set | determines `≈` **and** `≡_K` under `258.8` | `261.23` condition 1 | 🔴 OPEN | ARB |
| **N-4** | Which `𝒪`/`𝒯`? | ⚠️ **not enumerable here** | gates congruence, minimality, `258.20/21`, all bindings | `261.23` condition 2; **the single upstream gate** | 🔴 **OPEN — blocks everything** | ARB |
| **N-5** | Is `≈` indexed by axis-subset, policy, or `𝒪`? | `X⊆{A,S,R,V,C}` (**exactly 32**, EXECUTED) / policy `C` (`25I.35`) / `𝒪_K` (`261.5`) | 32 is `Σ`-level; `𝒪_K` is `K`-level | which question `≈` answers | 🔴 OPEN | ARB |
| **N-6** | Which `λ` (provenance relevance)? | a **principle** exists; **no predicate** | `≅_P` undefined until fixed | `261.23` condition 3 | 🔴 OPEN | ARB |
| **N-7** | Is `≡` required transitive? | yes / no / **within a context** | without it **no quotient exists** | `195.16` `I_48` supplies the shape for `≅_I` | 🔴 OPEN | ARB |
| **N-8** | Define **"identity context"** | scope choice | unbinds `I_48`'s parameter | makes the one narrowing usable | 🔴 OPEN | ARB |
| **N-9** | Ratify `I_48`–`I_51`? | ratify / reject / amend | `I_48` is the only equality-axis narrowing | research → architecture promotion | 🔴 OPEN | ARB |
| **N-10** | Decision codomain: `Relation × Status` or a flattening? | 4 corpus codomains + the product | flattening loses the `012 §36` separation | *"relation ≠ epistemic certainty"* | 🔴 OPEN | ARB |
| **N-11** | Per-operation equality bindings | 7 analysed + **6 unanalysed** | `261.19`: no single relation suffices | cannot start before `𝒪` closes | 🔴 OPEN — **BLOCKED by N-4** | ARB |
| **N-12** | `Deduplicate`'s criterion | **4** (`261.17`) | near-zero dedupe under A | `261.19`: NORMATIVE/domain-dependent | 🔴 OPEN | PO + domain |
| **N-13** | Which canonicalization? | ⚠️ **`38.85`: must FOLLOW evidence** | `=` and `≡_exact` are **Level-5 only relative to it** | `38.53` normalization is epistemic | 🔴 **OPEN — newly raised** | ARB |
| **N-14** | `id = H(P,e,c,t,Π)` with mutable `e.state` | repair / re-scope / accept the dangling | **EXECUTED**: violates `StructuralValid` | **a later construction contradicts `25I.30`** | 🔴 OPEN — **governance, not engineering** | ARB |
| **N-15** | Ratify `EntityID`/`KAID`/`RecordID`? | ratify / reject | 5 of 11 identity kinds absent | `261.23` condition 6 | 🔴 OPEN | ARB |
| **N-16** | Is `Continuity` a kernel relation? | yes / no | in **no** register; `195.24` identity ⊥ lineage | new carrier if yes | 🔴 **OPEN — newly raised** | ARB |
| **N-17** | Is `G_I` a kernel component or part of `ℛ`? | separate graph / inside `ℛ` | `38.87`: *"must not be collapsed"*; `ℛ` is 3-field | kernel shape | 🔴 **OPEN — newly raised** | ARB |
| **N-18** | `Merge` commutative? associative? | impose / do not | `25J.46/47`: *"not yet imposed"* / *"a design goal"* | distributed ingestion depends on associativity | 🔴 OPEN | ARB |
| **N-19** | False-merge/false-split loss asymmetry and operating point | domain-specific (`38.20`) | no loss function, no threshold, no calibration | `38.22` abstention; `38.80` no silent promotion | 🔴 OPEN — ⚠️ **also blocked by `G-22`: no `(Ω,𝓕,P)`** | PO + domain |
| **N-20** | The `287` identifier collision | equality-`287` / invariants-`287` / renumber | two artifacts share a number | registry hygiene | 🔴 OPEN — **carried, user's call** | registry |

**20 open normative decisions. N-4 gates N-3, N-5, N-11 and the technical half of N-2.**

## STATUS
**ESTABLISHED** the decision set is enumerable · **BOUNDED** 20 decisions, options stated for each ·
**NORMATIVE** all 20 · **TECHNICALLY OPEN** none of these is technical, **except** the `258-A`
component of N-2 · **BLOCKED** N-3/N-5/N-11 behind N-4 · **DEFERRED** every one to governance
