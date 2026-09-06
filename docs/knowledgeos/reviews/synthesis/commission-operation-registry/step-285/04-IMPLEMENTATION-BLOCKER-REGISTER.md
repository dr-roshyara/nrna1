# 04 · IMPLEMENTATION BLOCKER REGISTER
> ## ⛔ NOTHING RATIFIED. Subsumes Step-284 deliverable 05 (one register, not two).
> **Three materially different states are kept apart throughout:**
> **(α) cannot implement — semantics undefined** · **(β) can implement, cannot claim canonicality** ·
> **(γ) can implement and claim, cannot empirically certify.**

| ID | Construct | Broken dependency | Type | Evidence | Lane | Prerequisite decision | Blocks Part V? | Blocks implementation? | State |
|---|---|---|---|---|---|---|---|---|---|
| **B-01** | Constitution status | the ground under Arts. 3/4/6/7/8/9/11 | **G** | three records disagree; OQ-10 unperformed | **governance** | which record states the status | **yes — indirectly all of it** | yes (via B-05/B-06) | **α (prior)** |
| **B-02** | operations `𝒪` | the universe was never bounded | **A + G** | 0 governed operations; 48 enumerations; **empty intersection**; no act | architecture → governance | **P-11a/b/c** | **yes — V.5 RED** | **yes** | **α** |
| **B-03** | transformations `δ` | downstream of B-02; no pre/post | **F + A** | `postcondition` = 0 governed-wide; executed `K₁ is K₀ == True` | architecture | after P-11, then per-operation contracts | **yes — V.6 RED** | **yes** | **α** |
| **B-04** | identity | `e.state` mutability undisposed | **F + G** | TG-06 OPEN BLOCKING; **two opposite repairs on record** | theory → architecture | which repair, by what act | yes (V.2 AMBER) | **yes** | **α** |
| **B-05** | equality | identity, then closure of `𝒯` | **F + A** | *"`K₁ = K₂` has no truth value in the theory as it stands"* | theory | after B-04 and P-11 | yes (V.2/V.3) | **yes** — postconditions undecidable | **α** |
| **B-06** | `Reject` / REJECTED guard | ratified state, unspecified guard | **F + G** | §256.11 self-declared non-specification; no act | architecture → governance | **P-1** | yes | **yes** | **α** |
| **B-07** | resolution / return rung | Art. 8 silent on destination | **F + G** | Art. 8.3 constrains the record, not the rung | governance | **P-2** (and R-2) | yes | yes | **α** |
| **B-08** | qualification | no body | **D + F** | TG-14 OPEN BLOCKING; *"CORPUS ESTABLISHES"* **contradicted** | theory | define or rule policy-parametric | yes (V.7 AMBER) | **yes** — pipeline stop 1 | **α** |
| **B-09** | closed invariant register | completeness of I-1…I-12 unasserted | **F** | no register exists | architecture | close it or state it open | yes (V.4 rider) | **yes** — obligations have no codomain | **α** |
| **B-10** | typed rejection/failure semantics | no vocabulary | **F** | canon defines no rejection type | architecture | define the vocabulary | yes | **yes** | **α** |
| **B-11** | policy in/out of `K` | AUTHORIZED text vs executed result | **A** | v0.2 §3 *"AT REST inside `K_t`"* vs *"`P ∉ 𝒜`"* | architecture | which placement stands | yes (V.8) | yes | **α** |
| **B-12** | GC-1 policy loop | two closures stand | **G** | ES-005.4; neither withdrawn | **governance** | which termination stands | yes (V.8 rider) | no | **β** |
| **B-13** | Σ | 0 governed occurrences; 3-vs-4 refutation | **A + G + D** | AF-F-38; ≥12 forms | theory → governance | adopt, mark, or leave open | yes (blocked from prose) | no | **β** |
| **B-14** | `Q_t` | 0 governed occurrences; **threefold collision** | **A + G + F** | AF-F-37; `Ask(p)` measured **zero** in the live repo | theory → governance | GD-08 + collision ruling | yes | no | **β** |
| **B-15** | lineage | canon silent; code is a **Type-3 analogy** | **A + E** | *"must not be reported as implementation"*; **4 tests, not 47** — and 47 still cited | architecture · engineering | a canonical definition to conform code to | partial (V.9) | no — **already built** | **β** |
| **B-16** | authority evaluator | `Authorize()` runtime absent | **F + I** | *"an enum, not an evaluator"*; `humanActRef` free text | architecture · engineering | authority→gate binding; typed act | yes (V.8) | **yes** for authorization | **α** |
| **B-17** | measurement | measurability prior to scale typing | **D + I** | MT-5; executor declared out of scope | theory · engineering | is a probability space required | partial (V.9) | yes for measurement | **α** |
| **B-18** | replay | downstream of B-04/B-05; flag defect | **F + E** | IR-F-2 **FAIL**; *"the platform does not model replay"* | theory · engineering | after identity/equality | yes | yes | **α** |
| **B-19** | persistence | canonically silent | **F + A** | 0 governed content | architecture | define or scope out | partial | yes | **α** |
| **B-20** | empirical witnesses | the EKP does not implement the constructs | **E** | 15/24 tests · 16/25 constructs unobserved (**separate denominators**) | **engineering** | witness scope decision | no — reportable as fact | no | **γ** |
| **B-21** | EG-05 suites | specified, unexercised | **E** | OQ-5 | engineering | execute them | no | no | **γ** |

**Counts:** α (semantics undefined) **14** · β (implementable, not claimable) **4** · γ (claimable,
not certifiable) **3**. **Blocks Part V: 16. Blocks implementation: 14.**
