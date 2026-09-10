---
artifact: CONCEPT RECONSTRUCTION — EvalReq / Sat / 𝒮_sat, birth → present
date: 2026-09-10
status: **The "missing codomain" is NOT missing. It is defined three times. They do not agree, and the latest one cites none of the others.**
method: birth point → chronological forward read → complementary-definition search → identity tests A–E
scope: firewall excluded throughout. Nothing canonicalized.
---

# `EvalReq` / `Sat` / `𝒮_sat` — from the birth point

> **§12 applied.** My own census said *"`EvalReq` … four arguments, **NO CODOMAIN**."* Taken at
> face value that is a gap. Reconstructed from the birth point, **it is not.**

---

## A. Birth Point

| kind of birth | when | source | what it gives |
|---|---|---|---|
| **earliest lexical** (`EvalReq` literally) | 2026-09-06 00:39:47 | `theory-part-06` §6.17 | the abbreviation only |
| ⭐ **earliest conceptual + first explicit definition + first formal type** | **2026-08-27 18:33:20** | **`step-025e` §25E.27** | `EvalRequirement(K,r,C) → Status` — **boxed, WITH a codomain** |
| **sibling born with it** | same | same | `EvalContract(K,EC,C) → ContractStatus`, `ContractStatus = {Ready, Blocked, Invalid, Indeterminate}` — **enumerated** |
| **first operational use** | — | ⛔ **none found** outside the firewall |
| **first explicit relation to another theory object** | 2026-09-06 00:39:47 | `theory-part-06` §6.18 | `Sat(K,r,Γ) = Det_r(EvalReq(K,r,EC,Γ), EC)` |

$$\boxed{\textbf{The lexical birth is 10 days AFTER the conceptual birth, and the conceptual birth HAS the codomain the lexical one lacks.}}$$

**Independent corroboration** — `verification/spec/STEP-VERIFY-025a-025g` L240 (2026-08-29 20:07),
reading `025e` without reference to this reconstruction:

> *"`EvalRequirement(K,r,C)→Status`; `EvalContract→{Ready,Blocked,Invalid,Indeterminate}` **is CLEAR
> and the file's most solid formal object**"*

---

## B. Chronological evolution, oldest → newest

| # | when | source | what happened |
|---|---|---|---|
| **t₀** | 08-27 18:31 | `025d` §25D.11–12 | `Satisfied = ContractSpecific` (boxed); `Satisfied(K,r,EC)`; codomain `𝒮` = **9** statuses, drifting to 11, **never re-declared** |
| **t₁** | 08-27 18:33 | ⭐ `025e` §25E.27 | **`EvalRequirement(K,r,C) → Status`** + `EvalContract → {Ready,Blocked,Invalid,Indeterminate}` |
| **t₂** | 08-29 15:02 | `A3X-algebra-invariant-register` | EC's own forms recorded drifting: pair / 4-tuple / 9-tuple, *"provisional"*; ⚠️ **"No 8-tuple"** — contradicting `STEP-VERIFY-025a-025g` L238, which reads an 8-tuple at the `25F` header |
| **t₃** | 08-29 20:07 | `STEP-VERIFY-025a-025g` | judges the evaluation pair **CLEAR and the most solid formal object**; also records `Satisfied` as **three types under one name** |
| **t₄** | 09-02 09:39 | ⭐⭐ `…three-valued-sat-predicate` | **`Sat` becomes class-indexed**: `Sat(K_t,r) = Sat_c(K_t,r) if r ∈ ℛ_c`. Common interface **`Sat_c : 𝒦 × ℛ_c × Γ → V_Sat`**, **`V_Sat = {⊤,⊥,U}`**, and a **complete 3-case body** `Sat_c(K_t,r;Γ_t) = ⊤ if K_t,Γ_t ⊨ P_c(r); ⊥ if ⊨ ¬P_c(r); U otherwise` |
| **t₅** | 09-06 **00:23:01** | ⭐⭐⭐ `theory-part-01` | **A DECLARED RE-FOUNDING** — see §E |
| **t₆** | 09-06 **00:30:23** | ⭐⭐ `theory-part-03` §3.13–14 | *"the satisfaction semantics must be **contract-specific**"* — and **`𝒮_sat = {S,U,P,C}`**, `Sat : 𝕂 × Req → 𝒮_sat`, plus a contract projection **`π_EC : 𝒮_sat → {0,1}`** |
| **t₇** | 09-06 00:36:45 | `theory-part-05` | uses `𝕊_sat` (L273, L316) |
| **t₈** | 09-06 **00:39:47** | `theory-part-06` §6.15/6.17/6.18 | `Eval : K×E×P×EC×Γ → 𝒱`, *"`𝒱` is an evaluation space"* · `EvalReq(K,r,EC,Γ)` **untyped** · `Det_r : 𝒱 × EC → 𝕊_sat` · `Sat(K,r,Γ) = Det_r(EvalReq(…), EC)` |

---

## C. Definition evolution

| Time | Source | Definition | Type | Role | Relation to prior | Status |
|---|---|---|---|---|---|---|
| 08-27 18:33 | `025e` §25E.27 | `EvalRequirement(K,r,C) → Status` | function, 3-ary | evaluate one requirement | **BIRTH** | `COMPLETE` (signature) |
| 08-27 18:33 | `025e` §25E.27 | `EvalContract(K,EC,C) → ContractStatus`, 4 values | function, 3-ary | evaluate the contract | **BIRTH**, sibling | `COMPLETE` |
| 09-06 00:39 | `theory-part-06` §6.17 | `EvalReq(K,r,EC,Γ)` | ⛔ **no codomain** | same role | ⚠️ **arity 3→4; `C` → `EC,Γ`; no citation** | `PARTIAL` |
| 09-06 00:39 | `theory-part-06` §6.15 | `Eval : K×E×P×EC×Γ → 𝒱` | function, 5-ary | general evaluation | ⭐ supplies `𝒱` | `COMPLETE` |
| 08-27 18:31 | `025d` | `Satisfied(K,r,EC) → 𝒮` (9→11, never re-declared) | predicate/element/function — **3 types one name** | satisfaction | **BIRTH** | `PARTIAL` |
| 09-02 09:39 | `Sat_c` doc | `Sat_c : 𝒦 × ℛ_c × Γ → V_Sat = {⊤,⊥,U}` **+ full body** | function, 3-ary | satisfaction **by requirement class** | ⚠️ no citation of `025d` | ⭐ `COMPLETE` |
| 09-06 00:30 | `theory-part-03` §3.14 | `Sat : 𝕂 × Req → 𝒮_sat = {S,U,P,C}` + `π_EC : 𝒮_sat → {0,1}` | function, 2-ary | satisfaction, contract-projected | ⚠️ no citation of `025d` or `Sat_c` | ⭐ `COMPLETE` |
| 09-06 00:39 | `theory-part-06` §6.18 | `Sat(K,r,Γ) = Det_r(EvalReq(K,r,EC,Γ), EC)` | composition, 3-ary | satisfaction **by contract** | ⚠️ `Γ` on the left, `EC`+`Γ` on the right | `PARTIAL` (body deferred) |

---

## D. Completeness reconstruction — §7 succeeds **three times**

| what my census called missing | where it is actually supplied | distance |
|---|---|---|
| **`EvalReq`'s codomain** | ⭐ **`𝒱`**, defined **§6.15 of the same document**, three sections earlier — *"where `𝒱` is an evaluation space"*, with a 7-component conceptual value `⟨Support, CounterSupport, Uncertainty, Conflict, Dependencies, Assumptions, Justification⟩`. Forced by composition: `Det_r`'s domain **is** `𝒱` | **3 sections** |
| **`𝕊_sat` undefined** | ⭐⭐ **`𝒮_sat = {S,U,P,C}`**, `theory-part-03` §3.14 | **9 min 24 s earlier** |
| **`Sat`'s body deferred to `Det_r`** | ⭐⭐ a **complete 3-case model-theoretic body** in `Sat_c` — `K_t,Γ_t ⊨ P_c(r)` | **4 days earlier** |
| **`Det_r`'s body** | ⛔ **nowhere readable.** Withheld by design as *"contract-specific"*; 97 % of its occurrences are firewalled | — |

$$\boxed{\textbf{Three of four "missing" components are present. My census statement was TRUE of one document and FALSE of the corpus.}}$$

---

## E. Identity / homonym analysis — Tests A–E

### ⭐⭐⭐ Test A — is the later "complete" definition a different object? **YES — a declared re-founding.**

`theory-part-01`, **2026-09-06 00:23:01**, opening lines, verbatim:

> *"write the theory **from beginning to end as one coherent mathematical work**, in parts, and only
> afterward use the resulting theory to drive implementation."*
> *"**I will not treat an attractive formulation as a theorem merely because it appeared in an
> earlier document.**"*

**23 `theory-part-*` documents, 2026-09-06, 00:23:01 → 07:51:53 — a complete 21-part theory rewrite
in one night.**

**Citation census across `theory-part-03/05/06`** for `025d` · `025e` · `ContractSpecific` ·
`EvalRequirement` · `EvalContract`:

$$\boxed{\textbf{0 · 0 · 0 — zero hits in all three documents.}}$$

⇒ **`RE-FOUNDING`, and — unlike re-foundings #3 and #5 — it is `WITNESSED`: the document states its
reason for not inheriting.** This is **re-founding #6** in the reconstruction.

### Test B — could the incomplete definition be completed elsewhere? **YES, three times.** §D.

### Test C — are these one evolving lineage? ⛔ **NO, on citation evidence.**

`theory-part-06` cites `Sat_c`, `V_Sat`, class-indexing and requirement classes **zero times**,
though `Sat_c` was written **four days earlier in the same lane** and solves the same problem.

### Test D — historical vs present incompleteness

| | |
|---|---|
| **`TheoryState(t₁)` = 08-27 18:33** | `EvalRequirement` was **complete** then. It did not become incomplete; **a later document re-derived it without the codomain** |
| **present reconstruction** | the corpus supplies `𝒱`, `𝒮_sat` and a `Sat` body — **from three mutually non-citing sources** |

⭐ **The gap was never historical. It is an artifact of reading one document.**

### Test E — importing a verification conclusion backward? **Checked and refused.**

`STEP-VERIFY-025a-025g` (08-29) judges the `025e` pair *"CLEAR and the most solid formal object."*
That is used **only** as corroboration of `TheoryState(t₁)`, and is **not** propagated forward to
claim `theory-part-06` inherited anything. It demonstrably did not.

### Classification table

| pair | verdict | basis |
|---|---|---|
| `EvalRequirement(K,r,C)` × `EvalReq(K,r,EC,Γ)` | ⭐ **RE-FORMULATION — identity unwitnessed** | same role, same lane-family, abbreviation of the same word; but arity 3→4, `C` split to `EC,Γ`, codomain dropped, **zero citation** |
| `Sat_c`'s `V_Sat={⊤,⊥,U}` × `theory-part-03`'s `𝒮_sat={S,U,P,C}` | ⛔ **DISTINCT OBJECT** | **3 values vs 4**; `U` means *unknown* in one and *unsatisfied/unknown* in the other; no mapping stated anywhere |
| `theory-part-03`'s `𝒮_sat` × `theory-part-06`'s `𝕊_sat` | **SAME OBJECT — STRONG CONTINUITY** | same series, same night, 9 min apart, `theory-part-05` uses it between them. ⚠️ notation shifts `\mathcal S` → `\mathbb S` — recorded, not treated as semantic |
| `025d`'s `𝒮` (9→11) × `𝒮_sat` (4) × `V_Sat` (3) | ⛔ **THREE CODOMAINS, no reconciliation** | no document maps any pair |
| `Sat_c`'s class-indexing × `Det_r`'s contract-indexing | ⭐⭐ **DISTINCT SOLUTIONS TO ONE PROBLEM** | both answer *"satisfaction means different things for different requirements"* — one indexes by **requirement class**, the other by **contract**. Neither cites the other |
| `025d`'s `Γ` (sufficiency rules) × `Sat_c`'s `Γ_t` (left of `⊨`) × `theory-part-06`'s `Γ` | **UNDECIDABLE** | `Γ_t` in `K_t,Γ_t ⊨ P_c(r)` is a **premise set** — closest to the sufficiency-rules sense, **not** the context sense. See `G-02` |

---

## F. `TheoryState(t)` at each load-bearing transition

```
t₁ 08-27 18:33   Conceptual  requirement evaluation is a typed function
                 Mathematical  EvalRequirement(K,r,C) → Status          COMPLETE
                 Operational   never executed                          UNUSED
                 Validation    "CLEAR, the most solid formal object"   CORROBORATED (08-29)
                 Governance    no act                                  UNRESOLVED

t₄ 09-02 09:39   Conceptual  satisfaction is CLASS-relative
                 Mathematical  Sat_c : 𝒦×ℛ_c×Γ → V_Sat={⊤,⊥,U}, body given   COMPLETE
                 Operational   ⛔ V_Sat occurs in ONE file corpus-wide — ZERO downstream consumers
                 Validation    marked [PROP]; "candidate current value domain"
                 Governance    no act

t₅ 09-06 00:23   ⭐ DECLARED RE-FOUNDING — "I will not treat an attractive formulation as a
                    theorem merely because it appeared in an earlier document"

t₆ 09-06 00:30   Conceptual  satisfaction is CONTRACT-relative  (= 025d's t₀ conclusion, re-derived, uncited)
                 Mathematical  Sat : 𝕂×Req → 𝒮_sat={S,U,P,C}; π_EC : 𝒮_sat → {0,1}   COMPLETE
                 Operational   unused
                 Validation    none
                 Governance    no act

t₈ 09-06 00:39   Conceptual  satisfaction DECOMPOSES into evaluation + determination
                 Mathematical  Eval → 𝒱 COMPLETE · EvalReq PARTIAL (codomain recoverable)
                               · Det_r signature COMPLETE, BODY WITHHELD BY DESIGN
                 Operational   unused
                 Validation    none
                 Governance    no act
```

**All five histories are independent, and every one of these states is
`governance = UNRESOLVED`.** Not one of these formulations has been adopted by any act.

---

## G. Present reconstruction

**`EvalReq` — DEFINED IN MULTIPLE STAGES.** Signature and codomain both exist: `EvalRequirement(K,r,C) → Status`
at birth, and `𝒱` by composition at `theory-part-06`. **Not missing.**

**`Sat` — COMPETING DEFINITIONS.** Three complete formulations, three codomains (3, 4, 9→11 values),
two indexing strategies (class vs contract), **zero cross-citations**.

**`Det_r` — NOT YET SEARCHED.** Signature complete, body withheld by design, 97 % firewalled.

⛔ **None of these is "missing".** The census sentence *"`EvalReq` … NO CODOMAIN"* was **true of one
document and false of the corpus** — the exact error §6 and §12 warn against, committed by me two
hours ago and corrected here.

---

## H. Gap impact

| gap | action | why |
|---|---|---|
| *"`EvalReq` has no codomain"* | ⭐ **CLOSED** | supplied at birth (`Status`) and by composition (`𝒱`). Never a corpus gap |
| *"`𝕊_sat` is undefined"* | ⭐ **CLOSED** | `𝒮_sat = {S,U,P,C}`, `theory-part-03` §3.14, 9 min earlier |
| **`G-01`** (`Sat`'s codomain 9/10 → 3) | ⭐ **RE-SCOPED, and the count is now known** | not a narrowing. **Four codomains**: `𝒮` 9→10→11 (`025d`) · `V_Sat` 3 (`Sat_c`) · `𝒮_sat` 4 (`theory-part-03`) · `𝕊_sat` (`theory-part-06`, = `𝒮_sat`). **No document maps any pair** |
| **NEW `G-24`** | **OPEN** | ⛔ `V_Sat` is **defined, boxed, complete — and occurs in exactly ONE file corpus-wide.** A codomain with **zero downstream consumers** |
| **NEW `G-25`** | **OPEN** | ⭐⭐ **Re-founding #6**, 2026-09-06 00:23 — a 23-document, 21-part rewrite that **declares** it will not inherit, and cites the 025-series **zero times** while re-deriving its conclusions |
| **`C-1` / `G-09`** | **unchanged** | untouched by this reconstruction |

---

## I. Next step, by dependency impact

⭐ **`G-25` — the 2026-09-06 re-founding.** It is upstream of everything in the 09-06 lane: 23
documents, a complete theory rewrite, and **every object this family cares about is born inside it**
(`Det_r`, `𝒮_sat`, the `Eval`/`EvalReq`/`Det_r` decomposition). Until its inheritance relation to
the 025-series is established, **every 09-06 formulation is of unknown ancestry** — and `G-01`,
`G-05`, `C-1` and the `Det_r` question all have one endpoint inside it.

`G-12` remains the larger chronological debt but is **not** upstream of this family.

⛔ **Firewall honoured. Nothing canonicalized. No definition selected. All versions preserved.**
