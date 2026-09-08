# MD-044 §1 — Source Chain and Dependency Table (Phase A)

## Corrected chronology (not independent documents — one continuous editorial session)

| # | File (seq) | Content (per this reconstruction's own governed per-file record + direct reading) |
|---|---|---|
| 1 | 014317 (M0235) | Initial proof draft: defines 𝔈=(𝒳,𝒬,𝒞,𝓗,𝒮,ℐ,𝒜), `Obs`, `≡_sem`, `⪯`; Lemma 1 (Capability Irreducibility, proof by contradiction using a witness scenario); Theorem 1 (Minimal Capability Set); the 13-capability candidate universe; `DetectGap` shown derivable ("across all eight tested variants"); Zero/Representation/Select boundary arguments; explicitly labels the result "conditionally proved," NOT "the unique final Kernel... universally proven." Contains at least two subsequent senior-review response passes in the same file, converging on the same conclusion via a slightly different formalization (`Tr_K(h)` trace-based `≡_sem`, `Irred(c\|C)` universal-quantifier irreducibility). |
| 2 | 014642 (M0236) | "Direct continuation of M0235." Restates Lemma 1/Theorem 1 with an explicit **Definition 1 (Admissible Epistemic Environment)**, Factivity/Selection/Zero boundary formalization, and — critically — a **first executable Python verification testbed** (`EpistemicKernel`, `MinimalityVerificationSuite`) that was actually run: a toy 3-capability universe (`Interpret`, `Qualify`, `Hypothesize`), `test_irreducibility_witness("Qualify", ...)` returning a concrete boolean result, plus a lossless reference-relative-encoding test with a computed compression ratio. **Also contains a self-caught GOVERNANCE-FABRICATION event**: an early draft register block declares `(RATIFIED)` under authority "KnowledgeOS Core Epistemic Framework Committee" — a fictitious body — flagged by the same session's own senior-review pass ("Unless that committee and ratification act actually exist in your governance system, this must not appear as a factual ratification... exactly the kind of provenance error KnowledgeOS is supposed to prevent") and corrected to `UNRATIFIED / PROPOSAL FOR FOUNDATIONAL FREEZE`. |
| 3 | 014807 (M0237) | ~83% verbatim repeat of M0236's own corrections (per this reconstruction's own per-file record); new content: `MinKer(𝔠_KOS)` formalized as a **set** of non-dominated minimal elements (not a single minimum — `⪯_sem` is only a partial order), `⪯_sem` grounded in trace-simulation `⪯_cap`, a three-condition stopping rule, a 10-row status ledger. **This draft's own "Uniqueness" bullet claims "Exactly one semantic equivalence class `[K*]_≡sem` exists in `MinKer(𝔠_KOS)`"** — stated as if established. |
| 4 | 015837 (M0238) | Four corrections to M0237, the most consequential being a **category-error catch**: an equivalence class is not itself an implementation; introduces `MinKer_/≡sem = {[K]_≡sem \| K∈MinKer(𝔠_KOS)}` as the **set of distinct minimal semantic classes** — directly superseding M0237's own "exactly one class" claim, without asserting a specific cardinality. Also flags `⪯_cap`'s own "losslessly simulated" as still-informal, requiring a `Trace(K,h)` formalization (`operational semantics still OPEN`); renames the "Unfalsifiable Stopping Rule" to a falsifiable one. |
| 5 | 020004 (M0239) | The session's own final, official 20-item TODO ledger, 4-gate structure. States directly and authoritatively: **"Minimal Kernel Existence: NOT YET PROVED"**, **"Minimal Kernel Uniqueness: NOT YET PROVED"** (explicitly non-failure framing for a plural result), "Named Operator Set: NOT YET PROVED", "Governance Ratification: OPEN". |

**This is a single continuous same-session self-review-and-correction dialogue (~17 minutes,
01:43–02:00), not five independent sources or five confirmations of the same claim.** Per the
Statistical-discipline requirement (Phase H, applied here at the sourcing level): the internal
"uniqueness" claim in file 3 is corrected by file 4 and settled by file 5's own official ledger —
this is the corpus's own self-correction, not a contradiction this study needs to resolve, and not
independent replication of anything.

## Dependency table

| Symbol / concept | Source-defined? | Exact definition (as given) | Formal role | Missing dependency |
|---|---|---|---|---|
| `K` (kernel implementation) | Partially | `K=(Carrier,Transitions,Capabilities,Invariants,Observables)` (M0235); `K=(X_K,δ_K,Cap_K,Inv_K,Obs_K)` (M0235, later pass) | Candidate object | Concrete instantiation for any real candidate (F1/F3/F4/F5/F6) never given |
| `𝒞` / candidate capability universe | Yes, named | `{Observe, Interpret, Represent, Relate, Discriminate, Qualify, Hypothesize, DetectGap, Challenge, Validate, Revise, Determine, Select}` (13 items) | Domain of capabilities | Explicitly "a candidate capability universe, not yet a claim that all are primitives" — never finalized |
| `𝔎_adm` (admissible implementation space) | **NO** | Referenced throughout, never enumerated or given a membership criterion | Domain over which `Min` ranges | **NECESSARY BUT UNSPECIFIED** |
| `𝔠_KOS` / `𝔉` (the fixed epistemic contract) | **NO** | Referenced as "the fixed KnowledgeOS epistemic contract"; never itself enumerated as a concrete requirement set in these 5 files | The satisfaction target | **NECESSARY BUT UNSPECIFIED** (adjacent apparatus elsewhere in the corpus — `Adeq(K,Q,C,EC)⟺∀r∈Req(Q,C,EC),Sat(K,r)` — itself calls `Sat` "one of the deepest unresolved primitives," per M0239 item 17) |
| `⊨` (K satisfies contract) | **NO** | Used (`K⊨𝔠_KOS`) but never given operational semantics | Satisfaction predicate | Directly chained to the two unspecified items above |
| `Obs` (observation function) | Partially | Given as a named vector: `(StateTransition, GapDetection, Determination, RevisionOutcome, InvariantStatus, Attribution[, Transition, History])` — components are *named*, not each individually defined | Basis for `≡_sem`/`⪯_cap` | Component-level semantics not given; **the toy Python testbed does concretely instantiate a version of this** (a state digest hash + invariant check) for a 3-capability toy universe only |
| `⪯_cap` (capability simulation ordering) | Partially | "`K_1⪯_cap K_2` iff every observable trace of `K_1`... can be losslessly simulated by `K_2`..." | Base ordering for `≡_sem`/`≺_sem` | M0238 itself flags "losslessly simulated" as the one remaining informal term, requiring a `Trace(K,h)` formalization — **explicitly OPEN per the source's own words** |
| `≡_sem`, `≺_sem` (semantic equivalence/strict order) | Yes, formally, but derivative | `K_1≡_sem K_2 ⟺ K_1⪯_cap K_2 ∧ K_2⪯_cap K_1`; `K_1≺_sem K_2 ⟺ K_1⪯_cap K_2 ∧ ¬(K_2⪯_cap K_1)` | Ordering used by `MinKer` | Sound only if `⪯_cap` is itself well-defined — inherits the same "OPEN" status |
| `Irred(c\|C)` (universal irreducibility) | Yes, formally | `∀K'[Capabilities(K')⊆C\{c} ⟹ K'̸≡_sem K_C]` | Irreducibility criterion | A genuine universal-quantifier statement is GIVEN, and a witness-construction PROOF METHOD is given (Lemma 1) — but no witness has actually been constructed and verified for the real 13-capability universe or for any of F1/F3/F4/F5/F6; only a 3-capability TOY case was executed |
| `Min` / `MinKer(𝔠_KOS)` | Yes, formally | `MinKer(𝔠_KOS) = Min_⪯sem{K∈𝔎_adm \| K⊨𝔠_KOS}` — explicitly the SET of non-dominated minimal elements, not a single minimum | The object this whole study is about | Formula is well-typed given its own inputs, but every one of those inputs (`𝔎_adm`, `⊨`, `⪯_sem`) is itself unspecified — **the formula is a sound shape referencing unspecified content** |
| `MinKer_/≡sem` | Yes, formally (M0238's own correction) | `{[K]_≡sem \| K∈MinKer(𝔠_KOS)}` — the set of distinct minimal semantic classes | Correctly-typed object for the uniqueness question | Cardinality (`\|MinKer_/≡sem\|`) explicitly `NOT YET PROVED` per M0239's own ledger |

## What this table establishes

MinKer is **not** a case of "everything is undefined" — it has a real, internally consistent formal
skeleton (definitions, a stated theorem, a witness-based proof method, even a toy executable
instantiation). But its **three load-bearing inputs** (`𝔎_adm`, `𝔠_KOS`/`⊨`, and `⪯_cap`'s own
`Trace`-based semantics) are each explicitly `NECESSARY BUT UNSPECIFIED`, and this is stated by the
source material's own final self-assessment (M0239), not inferred by this study.
