---
artifact: G-25 · THE 2026-09-06 EVENT — adjudicated against my own claims
date: 2026-09-10
status: **NOT a re-founding. A DECLARED SELECTIVE-INHERITANCE REWRITE, keyed to governance status. Three of my own claims withdrawn.**
supersedes: the "re-founding #6, the first witnessed one" classification in 07-THEORYSTATE-CHRONICLE and 06-GAP-REGISTER
---

# G-25 — was 2026-09-06 a continuation or a re-founding?

> **Answer: neither, as I framed it.** It declares **continuity** ( *"a strong starting point"* ),
> **exercises inheritance twice**, and re-derives everything else — because its inheritance rule is
> keyed to **explicit governance acts**, and almost nothing in the 025-series ever received one.

---

## A. Birth-point table — five kinds, separated

| object | lexical birth | conceptual birth | first explicit definition | first mathematical type | first operational use |
|---|---|---|---|---|---|
| **`Sat`** | 2026-08-27 16:25 `step_023` | same | `025d` §25D.12 `Satisfied(K,r,EC)` | `025d` (codomain `𝒮`) | ⛔ none |
| **`Sat_c`** | 2026-09-02 09:39 | same | same doc, boxed | `𝒦 × ℛ_c × Γ → V_Sat` | ⭐ **`research/knowledgeos-sim/` — see §B** |
| **`EvalReq`** | **2026-09-06 00:39** `theory-part-06` | ⭐ **2026-08-27 18:33 `025e` §25E.27** | `025e` (as `EvalRequirement`) | `EvalRequirement(K,r,C) → Status` | ⛔ none |
| **`Eval`** | 2026-08-26 17:29 `question-4` | — *(not re-read this pass)* | `theory-part-06` §6.15 | `K×E×P×EC×Γ → 𝒱` | ⛔ none |
| **`Eval_c`** | 2026-09-02 10:13 | — | — | — | ⛔ none |
| **`Det_r`** | 2026-09-06 00:39 | same | `theory-part-06` §6.18 | `𝒱 × EC → 𝕊_sat` | ⛔ **none** |
| **`𝒮`** | 2026-08-27 18:31 `025d` §25D.4 | same | enumerated, **9** | 9→10→11, **never re-declared** | ⛔ none |
| **`V_Sat`** | 2026-09-02 09:39 | same | boxed, `{⊤,⊥,U}` | same | ⭐ **via `Sat_c`** |
| **`𝒮_sat`** | 2026-09-06 00:30 `theory-part-03` §3.14 | same | boxed, `{S,U,P,C}` | `Sat : 𝕂×Req → 𝒮_sat` | ⛔ none |
| **`𝕊_sat`** | 2026-09-06 00:36 `theory-part-05` | — | ⛔ **never** (notation only) | — | ⛔ none |
| **`𝒱`** | 2026-09-06 00:39 `theory-part-06` §6.15 | same | *"an evaluation space"* + a 7-component conceptual value | `Eval`'s codomain | ⛔ none |
| **`Status`** | 2026-08-27 18:33 `025e` | — | ⛔⛔ **NEVER DEFINED** — see §E | — | ⛔ none |

**Operational-use census** (`.py`/`.txt`/`.json`, firewall excluded):
`Det_r` **0** · `EvalReq` **0** · `V_Sat` **0** · `S_{sat}` **0** · ⭐ **`Sat_c` 17**.

---

## B. ⭐⭐⭐ The finding that reverses my own: `Sat_c` was IMPLEMENTED AND RUN

`research/knowledgeos-sim/` contains a full campaign:

| kind | files |
|---|---|
| runner | `run_satc.py` |
| modules | `kos12/satc_spec.py` · `kos12/phaseC.py` · `kos12/repairs.py` · `kos12/zerolens.py` |
| results | `satc_phaseA_spec.json` · `satc_phaseB_adversarial.json` · `satc_phaseC_zero.json` · `expG_core.json` · `eval_results.json` · `cases.json` · `contr/C4_pair_separation.json` |

`kos12/zerolens.py` L47, verbatim: *"The coarse projection `π : 𝓑 → {T,F,U}` — **what `Sat_c` did**."*

$$\boxed{\textbf{The class-indexed branch was specified, implemented, adversarially tested and run in three phases. The contract-indexed branch has ZERO executable presence.}}$$

⛔ **My `G-24` claim — *"`V_Sat` … zero downstream consumers"* — is TOO STRONG and is corrected.**
Precisely: **`V_Sat` the symbol** occurs in one document; **`Sat_c` the function**, whose codomain
it is, has an implementation and a test campaign. *(And `zerolens.py`'s past tense — *"what `Sat_c`
did"* — suggests `Sat_c` was itself later superseded **inside the simulation**; not chased here.)*

---

## C. Definition/version registry — every material formulation preserved

Recorded in `05-DEFINITION-EVOLUTION-REGISTRY.tsv` (`Sat` ×2 new rows, `EvalReq` ×2, `EvalContract`,
`Eval`, `𝒮_sat`). **No version deleted, none selected.**

---

## D. Result-space identity matrix — and the `U` question settles it

| result space | birth | elements | `U` means | consumers |
|---|---|---|---|---|
| **`𝒮`** | `025d` 25D.4 | **9 → 10 → 11**, never re-declared | `Unknown`, one of nine | 0 |
| **`V_Sat`** | `Sat_c` doc | **3** — `{⊤,⊥,U}` | ⭐ *"satisfaction **cannot currently be determined**"* (L29); body: `U` **otherwise**, i.e. neither `⊨ P_c(r)` nor `⊨ ¬P_c(r)` | ⭐ **`Sat_c` → 17 executable** |
| **`𝒮_sat`** | `theory-part-03` §3.14 | **4** — `{S,U,P,C}` + `π_EC : 𝒮_sat → {0,1}` | ⭐ *"**unsatisfied/unknown**"* — **conflates two things** | 3 docs (parts 03/05/06) |
| **`𝕊_sat`** | `theory-part-05/06` | — | — | 3 docs |

### Pairwise

| pair | classification | basis |
|---|---|---|
| `𝒮_sat` × `𝕊_sat` | **SAME OBJECT — CONTINUATION** | same series, same night, 9 min apart, `part-05` uses it between them; ⚠️ notation `\mathcal S` → `\mathbb S` recorded, not treated as semantic |
| ⭐⭐ **`V_Sat` × `𝒮_sat`** | ⛔ **DISTINCT OBJECT — and INCOMPARABLE** | **`V_Sat` separates `⊥` (provably not satisfied) from `U` (undetermined). `𝒮_sat`'s single `U` = "unsatisfied/unknown" MERGES exactly those two.** Conversely `𝒮_sat` has `P` (partial) and `C` (conflicted), which `V_Sat` lacks. **Neither refines the other; no projection or order is stated anywhere** |
| `𝒮` × `V_Sat` | **IDENTITY UNWITNESSED** | 11 vs 3; `Unknown` present in both but never related |
| `𝒮` × `𝒮_sat` | **IDENTITY UNWITNESSED** | 11 vs 4; no mapping |
| `𝒮` × `𝕊_sat` | **IDENTITY UNWITNESSED** | via the above |
| `V_Sat` × `𝕊_sat` | ⛔ **DISTINCT** | inherits `V_Sat` × `𝒮_sat` |

⭐ **The `U` test was the decisive instrument, exactly as §5 anticipated.** Same glyph, materially
different semantics, and the difference is not a refinement in either direction.

---

## E. `EvalRequirement` vs `EvalReq` — and the mandatory Proven / Type-constrained / Inferred split

### ⛔⛔ First, a correction to my own claim of two hours ago

I wrote: *"the conceptual birth **HAS the codomain** the lexical one lacks."*
**Checked at source: `Status` appears exactly ONCE in `025e` — at L1008, as a bare arrow target.
It is NEVER DEFINED.** `025e` mentions `𝒮`, `025d`, "status set" and the enumeration **zero times**.

⭐ **And its sibling in the same section IS complete:** `EvalContract(K,EC,C) → ContractStatus` with
`ContractStatus = {Ready, Blocked, Invalid, Indeterminate}`, enumerated.

$$\boxed{\textbf{One section, two arrows: one codomain enumerated, the other only NAMED.}}$$

### The three-way classification §4 requires

| claim | classification | why |
|---|---|---|
| `EvalRequirement`'s codomain is **named** `Status` | ⭐ **PROVEN** | boxed at `025e` L1008 |
| **what `Status` is** | ⛔ **NOT ESTABLISHED** — not even inferred | never defined in `025e`; no cross-reference to `025d`'s `𝒮` |
| `EvalReq`'s codomain is `𝒱` | ⭐ **TYPE-CONSTRAINED** | `Det_r : 𝒱 × EC → 𝕊_sat` applied to `EvalReq(…)` forces it. **The corpus never states it** |
| **`Status = 𝒱`** | ⛔ **INFERRED — and I withdraw it** | nowhere established. Different documents, different lanes, 10 days apart, zero citation |
| `EvalRequirement` **is** `EvalReq` | ⛔ **IDENTITY UNWITNESSED** | same role, abbreviation of the same word — but **arity 3→4**, `C` split into `EC,Γ`, codomain dropped, and `theory-part-06` cites `EvalRequirement` **zero times** |

⇒ **The honest statement:** *a codomain is **named** at birth and **constrained** by composition ten
days later; it is **never defined**, and the two arrows are **not established to be the same
function**.*

⛔ **My earlier "gap CLOSED" for `EvalReq`'s codomain is DOWNGRADED to `QUALIFIED`.**

---

## F. ⭐⭐⭐ The 2026-09-06 verdict — **NOT a re-founding**

### The measurement

**23 documents, 00:23:01 → 07:51:53, ≈60,000 lines.** Backward citations
(`step-NNN` | `025x` | `QNN` | *"corpus explicitly"* | *"earlier document"*):

| part | 01 | 02–21a |
|---|---|---|
| citations | **4** | ⭐ **0, in all 22** |

`025` appears **zero times in all 23 documents**.

### But the four citations in `part-01` refute my reading

| line | verbatim | what it does |
|---|---|---|
| 3 | *"I will not treat an attractive formulation as a theorem merely because it appeared in an earlier document."* | ⭐ states an **epistemic policy about warrant** |
| 5 | *"The existing corpus gives us **a strong starting point**, but it also contains historical claims that were later corrected. For example, the corpus **explicitly rejected** the earlier 'unique minimal kernel' claim…"* | ⭐⭐ **DECLARES CONTINUITY** — and cites a corpus *rejection* as binding |
| 1558 | *"The corpus **explicitly adopted** history-preserving delta rather than naïve monotonicity."* | ⭐⭐ **EXERCISES INHERITANCE** — uses a corpus result as authority |
| 2011 | *"The corpus **explicitly withdrew** the previous unique-minimality claim for exactly this reason."* | ⭐⭐ **inherits a negative result** |

### §7's mandated distinction, resolved

$$\text{policy to re-derive} \quad \ne \quad \text{semantic decision to replace}$$

**It is the first, not the second.** The rewrite nowhere says the earlier `Sat`, `EvalRequirement`
or `𝒮` is *wrong*, or that it *replaces* them. Its rule is:

> **Inherit what the corpus has explicitly ADOPTED or explicitly WITHDRAWN. Re-derive everything
> that merely appeared.**

⭐ **The 0-of-22 citation count is the measured EFFECT of that policy, not evidence of a declared
restart** — because almost nothing in the 025-series ever received an explicit governance act.
*(`G-00` established independently that no governance act touches most of this material.)*

### ⛔ Withdrawal

| my claim | verdict |
|---|---|
| *"Re-founding #6 — and the first witnessed one"* | ⛔ **WITHDRAWN.** Re-founding #1 (`031`) **drops** the 025 apparatus; this document calls the corpus *"a strong starting point"* and inherits from it twice |
| correct classification | ⭐ **`DECLARED SELECTIVE-INHERITANCE REWRITE`** — continuity declared, inheritance keyed to **governance status**, re-derivation everywhere else |

**This is a better finding than the one it replaces:** the corpus is not being discarded. It is
being filtered through a rule that almost nothing passes.

---

## G. Gap impact — §9 dependency test, one claim withdrawn

| claimed dependency | verdict | evidence |
|---|---|---|
| `G-25` → **`Det_r`** | ⭐ **DIRECT** | `Det_r` is born in `theory-part-06` §6.18 |
| `G-25` → **`G-01`** | ⭐ **DIRECT** | 2 of `Sat`'s 4 codomains are born inside the rewrite — `𝒮_sat` (part-03), `𝕊_sat` (parts 05/06) |
| `G-25` → **`G-05`** | **DIRECT, but thin** | ⭐ `theory-part-02` **L1437**: `R_{req}(Q,Γ) ⊆ Dist(R(K))` — **one line, one document.** Notable: this is the `ℛ_req(Q,Γ)` *form* that the verification lane never writes |
| `G-25` → **`C-1`** | ⛔ **UNWITNESSED — WITHDRAWN** | the rewrite's `Loss_` is **`Loss_T`** (parts 08/12/18), a **transformation** loss with a **set** adequacy condition `Loss_T ∩ Dist_EC(K) = ∅`. **Different subscript, different index, different object** from `Loss_{ℛ_req}`. ⭐ And it is the **set** rendering — `C-1`'s non-scalar branch |

**Revised `G-01`:** unchanged as re-scoped — four codomains, **and now two of the six pairs are
proven `DISTINCT`** rather than merely unmapped.
**Revised `G-24`:** corrected — `V_Sat` the *symbol* has one document; `Sat_c` the *function* has an
implementation. The gap is not "no consumers"; it is *"the implemented branch was not carried
forward."*

---

## H. TheoryState impact — which historical states change

⛔ **None.** Every correction here is to **my reconstruction**, not to any `TheoryState(t)`:

| | ① state at `t` | ④ my reconstruction, corrected |
|---|---|---|
| `t` = 08-27 18:33 | `EvalRequirement(K,r,C) → Status`, codomain **named, undefined** | ⛔ was *"has the codomain"* → **names one** |
| `t` = 09-02 09:39 | `Sat_c` complete **and implemented** | ⛔ was *"zero consumers"* → **17 executable** |
| `t` = 09-06 00:23 | a rewrite declaring continuity + a warrant policy | ⛔ was *"re-founding #6"* → **selective inheritance** |

**No later evidence was back-propagated.** `Sat_c`'s implementation is used only to describe
`t`=09-02 forward, never to claim `025e` knew of it.

---

## I. Remaining unresolved, after this reconstruction

| | question | why it survives |
|---|---|---|
| **1** | ⭐ **What is `Status`?** Named at `025e`, never defined, never linked to `025d`'s `𝒮` two minutes and one file earlier | it is the only codomain in the family with **no elements at all** |
| **2** | ⭐⭐ **Why was the implemented branch not carried forward?** `Sat_c` has a spec, a runner, three phases and result files; `Det_r` has none, and the rewrite does not mention it | this is the **cost** of the selective-inheritance policy, measured |
| **3** | **`V_Sat` × `𝒮_sat` incomparability** — one separates `⊥` from `U`, the other merges them; one has `P`,`C`, the other lacks them | no reconciliation is possible without a decision, and no decision exists |
| **4** | **`EvalRequirement` ≟ `EvalReq`** — `IDENTITY UNWITNESSED` | arity 3→4, `C` → `EC,Γ`, zero citation |
| **5** | **`Det_r`'s body** | `FIREWALL-LIMITED` — 97 % of occurrences firewalled; **not** `MISSING` |

⛔ **Nothing canonicalized. No definition selected. No result space merged. Firewall honoured —
`three_model_convergence/` was not opened.**
