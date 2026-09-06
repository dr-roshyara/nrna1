# 10 — Conflict Records · `DECISION REQUIRED`

**Five conflict records in the mandated shape (`CORPUS-SEARCH-RULE.md` §5).** Each states the
alternatives, their sources, the exact difference, whether the corpus resolves it, and the
consequence for implementation.

$$\boxed{\textbf{None is resolved here. Resolution by preference is forbidden and none was applied.}}$$

Ordered by the estate's own priority, not mine.

---

## `CR-1` — `Contr`: what is contradiction predicated of, and does it explode?

| | |
|---|---|
| **Concept** | `Contr` — the contradiction predicate. **Estate priority 1** (TODO Group B) |
| **Alternative A — signature** | `Contr(p)` · `Contr(p,K)` · `Contr(K_t,p)` · **`Contr(K_t,σ)`** (a **scope**) · `Contr(p,C,t)` · `Contr(p,K,t)` · **`Contr(P,¬P)`** (a **pair**) |
| **Sources for A** | `…155900_prompt-minimum-machinery-to-represent-contradiction` · `…093546_satisfaction-by-requirement-class` · `…155903_transcript-now-perform-the-contr-experiment` · `…160601_priest-non-classical-logic-tools-for-contr-zero-identity` |
| **Alternative B — consequence** | `Contr(p) ⇒ GlobalInvalidity` **vs** `Contr(p) ⇒ LocalConflict(p)` |
| **Sources for B** | `…160601_priest-…` — **both forms in the same file** |
| **Further alternative** | the **semantic** definition `Contr(p) ⟺ S⁺(p)=1 ∧ S⁻(p)=1`, i.e. `Σ=(1,1)` — agreed across sources and independently derived in this lane |
| **Exact difference** | **A decides the *subject*:** a proposition · a proposition **pair** · a state-proposition pair · a **scope**. **B decides *blast radius*:** does one contradiction invalidate the state (classical) or stay local (paraconsistent)? **The two choices are independent** — any signature works with either consequence |
| **Equivalent / conflicting?** | **A: genuinely competing** — `Contr(K_t,σ)` and `Contr(p)` are not notational variants; a scope predicate cannot be recovered from a proposition predicate without a quantifier the corpus has not fixed. **B: contradictory** — they cannot both hold |
| **Precedence evidence** | **None selects.** `Contr` is `[OPEN]` in `The Frozen Model` §4.4, the HPA-approved TODO register (priority 1) and step-292. **Constraining evidence exists:** `KR-CONTR-EVAL` — *no flat domain of any cardinality is adequate*; `KR-CONTR-FDE` — FDE preserves all five `DirectContradiction\|X` pairs and collapses two **boundary** distinctions; the five non-collapse constraints `Contr ≢ {Absent, InsufficientEvidence, NotAssessed, Unknown, Unobservable}` |
| **Undecided** | the signature; the explosion policy |
| **Implementation consequence** | The signature fixes the **evaluator's input type** and therefore `Sat_consistency` — one of the five classes with no evaluator. The consequence fixes whether a contradiction anywhere **halts determination everywhere.** Both are load-bearing for `Det` and for `Zero` |
| **DECISION REQUIRED** | ① which signature ② `GlobalInvalidity` or `LocalConflict` |

---

## `CR-2` — `⪰`: one ordering, or several kept apart?

| | |
|---|---|
| **Concept** | `⪰` — progress / epistemic ordering. **Estate priority 3** (TODO Group C) |
| **A — state progress** | `K_{t+1} ⪰_EC K_t` — orders **states**, indexed by an epistemic context |
| **B — evidence strength** | `ES_{K_t}(p) ⪰ s_min` — orders **scalars** against a threshold |
| **C — refinement / subsumption** | `⪰` as refinement (`25I.21`, `25J.8`); `C ⊑_S D` — *"subsumption gives the formal shape of the missing `⪰` lane, **not its content**"* |
| **Exact difference** | **A and B do not have the same type.** A is a preorder on a state space; B is a threshold test on a scalar per proposition; C is a structural relation between concept descriptions. **A single `⪰` would have to be all three** |
| **Equivalent / conflicting?** | **Complementary, at different levels** — and the corpus says so: *"two independent paths exist and **must stay independent**"* |
| **Precedence evidence** | **None.** `[OPEN]`, and *"the previous experiment correctly identified `⪰` as missing; **it did not establish its semantics**"* |
| **Cost of leaving it open, measured** | **83 % of progress claims (1 491 / 1 793) specify no ordering**; `291` measured **0 of 5 axes ordered**; `Sat_status` is one of the five classes with no evaluator |
| **DECISION REQUIRED** | is `⪰` **one relation** or a **family of typed orderings** kept separate? If a family, is one of them the kernel's, and which? |

---

## `CR-3` — `δ`: reactive or commanded, total or partial?

| | |
|---|---|
| **Concept** | `δ` — the state transition. TODO Group E |
| **A — event-indexed** | `K_{t+1} = δ(K_t, e_t)`; composition `δ(δ(K_t,e_1),e_2)` |
| **B — operation-indexed** | `δ(K, o) = K'` |
| **C — partial, with rejection** | **`δ(K, o) = Reject(r)`** — `…step_285_canonical-state-reconciliation-gita-informed-lens.md:1070`, `…step_285_gita-informed-reading-canonical-state-research.md:867` |
| **Exact difference** | **A makes the kernel reactive** (the world supplies events); **B makes it commanded** (an actor supplies operations). **C makes `δ` a function into `K ⊎ Rejection` rather than into `K`** |
| **Equivalent / conflicting?** | A and B are **genuinely competing** — they place authority differently, and the estate's `Command ≠ Transformation` result (`I-C`, `§256.21–22`) lives on the B side. C is **orthogonal** and composes with either |
| **Precedence evidence** | **None.** `[OPEN]` in three registers. **Open sub-question in the corpus:** `δ(K,o_1)=δ(K,o_2) ⇒ o_1=o_2?` — marked **TBD** |
| **What survives from my package** | **the commit-case finding** — `Γ` is derived and not a component of `K`, so `δ` has nowhere to write; executed, `K₁ is K₀`. **That is a defect under A, B and C alike, and it is not resolved by choosing among them** |
| **Implementation consequence** | every invariant is stated as `P(K) ⇒ P(δ(K,o))`. A/B fixes what `o` ranges over; C decides whether the schema needs a rejection case |
| **DECISION REQUIRED** | ① event or operation ② total, or partial with a `Reject` codomain |

---

## `CR-4` — `≡_sem`: a sound definition under a contested name

| | |
|---|---|
| **Concept** | `≡_sem` — semantic equivalence. TODO Group D |
| **A** | `K_1 ≡_sem^{Q,Γ,𝒪} K_2` iff **determinations match** for all `q ∈ Q`, `o ∈ 𝒪`. **Executable** — `"""CLOSURE-4: Observational Semantic Equivalence Tester."""` |
| **Sources for A** | `…182003_consolidated-architectural-audit…` §3, CLOSURE-4 |
| **B** | `𝔎 = (K, =_str, **≡_sem**, ≈_obs, SameId, ≡_H, ≡_P)` — one of **seven** relations, with `≡` and `≈` explicitly **non-interchangeable** (`246`, `261.20`, `261.25`) |
| **The corpus's own reconciliation** | *"I would **not call it general semantic equivalence**… closer to `K_1 ≈_{Q,Γ,𝒪} K_2` = **contextual observational equivalence** — two representations can produce the same answers for the selected queries while differing in other observations"* |
| **Exact difference** | **A is B's `≈_obs` slot, not its `≡_sem` slot.** A is parameterized by a question set; B's `≡_sem` is not |
| **Equivalent / conflicting?** | **Not conflicting — mis-slotted.** A is a correct definition of a *different relation in the same structure* |
| **Precedence evidence** | **Partial and asymmetric.** A was declared `[CLOSED]`/`RATIFIED`; **two independent reviews rejected that** (*"not closed"*, *"too strong"*, *"not supported by the evidence"*), and every later register says `[OPEN]`. **The rejection has precedence in time and is unanswered** |
| **Implementation consequence** | `≡_sem` gates **Reduction** (TODO I). Adopting A under the right name unblocks the reduction lane at `≈_obs` strength — **weaker than `≡_sem`, and possibly sufficient** |
| **DECISION REQUIRED** | adopt A as **`≈_{Q,Γ,𝒪}`** (filling `≈_obs`) and leave `≡_sem` open — or keep both open? |

---

## `CR-5` — `Qualify`: three signatures before any body

| | |
|---|---|
| **Concept** | `Qualify` — `G1`, called *the* single irreducible gap by two lanes |
| **A** | `Qualify : Observation × Policy ⇀ Evidence` — partial, named, typed |
| **B** | `Qualify(x) → {Qualified, Unqualified, Undetermined, **Terminus**}` — with `C10: Qualify ≠ prove everything — justification may legitimately **TERMINATE**` |
| **C** | `Evidence × Proposition × Context × Provenance × Discrimination → EpistemicStatus` |
| **D — different framing** | the **Knowledge Qualification Problem**: `Φ : K̂_t → {Knowledge, Not-Knowledge}` (`20260825-233107`) |
| **Exact difference** | **A produces `Evidence`. B produces a four-valued verdict. C produces an `EpistemicStatus` from five inputs. D partitions `K̂_t`.** These are four different arrows with four different codomains — **not one function described four ways** |
| **Equivalent / conflicting?** | **Genuinely competing.** A is a *constructor* (makes evidence); B and D are *classifiers*; C is an *evaluator*. **B's `Terminus` value has no counterpart in A or C** and encodes a substantive claim — that justification may legitimately stop |
| **Precedence evidence** | **None.** All four coexist; three lanes independently report *"named, typed, **no body**"* |
| **What survives from my package** | ✅ **the body-absence** — under all four signatures. 🔴 **the word *undefined*** — four definitions exist at the signature level |
| **Implementation consequence** | `Qualify` is step 3 of 15 in the pipeline and the first stop. **The signature choice decides what the rest of the pipeline consumes** — `Evidence`, a verdict, or a status. **A body cannot be derived until the codomain is chosen** |
| **DECISION REQUIRED** | which signature — and note that **the choice is prior to the derivation**, so *"derive `Qualify`"* is not yet a well-posed task |

---

## What I am NOT asking about

| | why |
|---|---|
| **`𝒪_core` cardinality (5 / 13 / 8 / six registries)** | the corpus explains the divergence itself — *minimality is representation-relative*, `Reachability ≠ Epistemic adequacy` — and puts kernel selection **last**. **Recorded as multiplicity; no decision sought** |
| **Theory v1.3 closure** | **resolved by the corpus** — `09` §1. v1.2 frozen, no v1.3 |
| **`dedup`** | **state A** — searched, not found. A search result, not a gap |
| **`OQ-1` carrier · `OQ-2` `DECISION-02`** | **already on the estate's own register as governance acts**, ahead of everything here. **I am not re-asking them; I am pointing at them** |

## The shape of the ask

```
CR-1  Contr      signature + explosion policy      ── priority 1 in the estate's own register
CR-2  ⪰          one relation or a typed family
CR-3  δ          event vs operation · total vs partial-with-Reject
CR-4  ≡_sem      adopt A as ≈_{Q,Γ,𝒪}, or keep open      ── smallest, cheapest
CR-5  Qualify    which of four signatures            ── prior to any derivation
```

**Options available for each, per the rule:** select A · select B · combine · derive a unifying
definition · **preserve both at different abstraction levels** · run an experiment · defer ·
escalate to Governance.

`[REC]` **`CR-4` is the cheapest and is nearly self-resolving** — the corpus already contains the
correction. **`CR-5` is the most consequential**, because it is upstream of the derivation everyone
is waiting for and nobody has noticed that the derivation is not yet well-posed.
