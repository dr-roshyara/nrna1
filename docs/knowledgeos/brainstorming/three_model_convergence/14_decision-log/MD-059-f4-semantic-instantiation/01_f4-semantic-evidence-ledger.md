# MD-059 §01 — F4 Semantic Evidence Ledger

Format: `primitive → exact source → source status → semantic meaning → formal type → dependencies →
unresolved ambiguity`. All entries traced to Model B's own Phase-2 register (`03_model-b_mathematical/
02_concept-register.md` §A/§G/§K/§N, `03_contradictions-and-open-questions.md` UE-1/UE-2/UE-3/OQ-3),
which is itself already-admissible, already-provenanced F4/Model-B material.

| Primitive | Source | Status | Meaning | Type | Dependencies | Ambiguity |
|---|---|---|---|---|---|---|
| **`K_t`** (knowledge state) | §A, M0005–M0132 | `[HP]→[DF]`, DEVELOPING | "the knowledge state at time t" | opaque object (no single agreed internal structure) | — | **§G: 9+ mutually unresolved tuple-structure formulations** (7-component/6-argument-function/6-component/33-definition/5-component/6-component/11-component-with-20-invariants, plus ≥2 more in M0283–M0338) — `unresolved_equivalence`, no corpus act shows any two are the same object |
| **`Δ_t`** (knowledge gap) | §A, frozen M0132 | `[DF]`, ESTABLISHED (frozen by governance act) | requirement-deficit set | `Δ_t = {r ∈ R_t : Sat(K_t,r)=0}` — a set comprehension over `R_t`, using `K_t` only as an argument to `Sat`, never decomposing it | `R_t`, `Sat` | `R_t`'s own closure is never shown anywhere in this evidence base |
| **`Sat(K_t,r)`** (satisfaction predicate) | §A, implicit in the frozen `Δ_t` definition | `[DF]`, ESTABLISHED (as used in the frozen definition) | whether `K_t` satisfies requirement `r` | `Sat: K_t × R_t → {0,1}` | `R_t` | codomain/decision-procedure for `Sat` itself not separately specified in this register beyond its use inside `Δ_t` |
| **`R_t`** (requirement set) | §A (used, not itself defined as a registry object) | **NOT SPECIFIED as a closed registry** | "the" requirements active at time t | a set, type otherwise unstated | — | never shown closed, mandatory-membership rule never stated — structurally the same shape of gap as `𝒪_K`/R10 (MD-058), independently found here |
| **`Orgasm_t: K_t → K_{t+1}`** (Epistemic Closure Event) | §K, M0071–M0080, `[EX]` confirmed executed (KR-ORGASM/KR-CLOSURE-2026-09-02) | DEVELOPING | a genuine state-transition function, distinguished from mere state | `K_t → K_{t+1}` | `K_t` (as an opaque argument) | **P-6/OQ-3, UNRESOLVED**: whether `Orgasm_t`'s own irreversibility is a kernel property or belongs to History/Audit only — no execution record for the decisive test M0080 itself names |
| **Composition rules** ("union"/"strict"/"last-wins" excluded; "majority"/"intraframe-only" survive) | §N, M0129, `[EX]` for exclusions, `[OP]` for survivors | DEVELOPING | candidate merge/transition operators over `K_t` states, parameterized by a frame `φ ⊇ {time,context}` | `K_t × K_t → K_t` (candidate signature, per rule) | — | **UE-3, unresolved which survivor to use, and whether the two survivors are themselves equivalent or genuinely distinct** — a fifth "tested-not-eliminated" status, not forced to established/rejected |
| **`Y_t`/`L`/`Y_Transform`** (Yoni/Linga structural hypothesis) | §L, M0085–M0095 | `[CG]`, DEVELOPING | a proposed "initiating pole"/"transformation pole" structural split | `L:(K_t,Q_t)→P_t`, `Y_Transform(P_t,...)→O_t` | `K_t`, `Q_t` (undefined here) | **OQ-2/P-14: the decisive experiment (`KR-LINGA-YONI-2026-09-02`) has no confirmed-executed record** in this evidence base, unlike the structurally parallel KR-ORGASM/KR-COMP-SEP protocols |

**What is explicitly NOT in this ledger**: any specific internal decomposition of `K_t` (e.g. the
11-component `(A_t,R_t,E_t,Σ_t,H_t,Z_t,L_t,T_t,G_t,C_t,M_t)` variant, UE-2) — using any one of the
9+ variants as *the* structure of `K_t` would itself be the forbidden arbitrary decomposition choice
(R2, MD-058); none is adopted here.

## Primary-source additions (from `01a`'s targeted extraction; supersedes the register-level rows
above wherever the two differ — the register was a secondary synthesis, these are primary)

| Primitive | Source | Status | Meaning | Type | Dependencies | Ambiguity |
|---|---|---|---|---|---|---|
| **`[DEF-11]` Semantic Knowledge State** | M0043, primary, `[DF]` | ESTABLISHED (as a deliberately abstract type) | `K_t ∈ 𝕂`; the tuple type is explicitly declined ("the principal unresolved mathematical question") | opaque, with a representation map `r(K_t)=x_t` for any given representation `r` | — | none — the abstraction is deliberate, not a gap |
| **`[DEF-15]` Semantic equivalence** | M0043, primary, `[DF]` | ESTABLISHED (as a definition; not adopted as canonical elsewhere) | `r₁≡_sem r₂ ⟺ B_{r₁}=B_{r₂}` for two representations of one state, over "the admissible test domain" | `Rep × Rep → Bool`, parameterized by an unspecified `B` | `B` (observable behaviour, itself undefined further in this file) | **a genuine, primary, pre-existing corpus precedent for MD-058's own independently-derived `Obs_{Q,𝒪}`-equality shape — not previously cross-referenced in MD-057/058** |
| **`[DEF-19]`–`[DEF-21]` `EC_t`/`Req(EC_t)`/`Sat`/`Δ_t`** | M0043, primary, `[DF]` | ESTABLISHED (as definitions) | `EC_t=EC(S_t,G_t,Q_t,C_t)` (an epistemic contract, explicitly purpose-relative — *"stronger than pretending there is one universally correct ideal knowledge state"*); `Δ_t=Gap(K_t,EC_t)={r∈Req(EC_t):¬Sat(K_t,r)}` | `Sat: 𝕂 × Req(EC_t) → Bool` | `EC_t`'s own four parameters | `Req(EC_t)`'s own openness is **by design** (purpose-relativity), not an oversight — a correction to this ledger's own earlier "R_t never shown closed" framing (§ above), which treated deliberate parameterization as an accidental gap |
| **`Σ_t=(A,S,R,V,C)`** (Epistemic State component) | M0125, primary | `[DF]`, concrete | five enumerated sub-fields: Acquisition/Support/Resolution/Validity/Conflict, each with a stated value domain | a genuinely typed record | — | this is **one of eleven named `K_t` top-level components**; the other ten are named, not separately typed, in the files opened this phase |
| **`K_{t+1}=δ(K_t,e_t)`** | M0125, primary | `[DF]`, stated | event-indexed transition (matches MD-057's own catalogued form "A" for `δ`) | `𝕂 × Event → 𝕂` | — | not shown reconciled with `Orgasm_t` (register §K) or the composition-rule family (register §N) — a further, undocumented plurality this ledger discloses rather than silently resolves |
| **`K_t=Replay(K_0,H_t)`** | M0125, primary | `[DF]`, stated | history-reconstruction function | `𝕂 × History → 𝕂` | `H_t` (history, undefined further here) | not cross-checked against `δ` for consistency (i.e. whether `Replay(K_0,H_t)` and iterated `δ` agree) — OPEN, not addressed by any file opened this phase |
| **`Sat(K_t,r)`'s own computability** | M0132, primary, direct quote | **NOT SPECIFIED, source-admitted** | *"the semantics of each component are still open. Therefore, `Sat(K_t,r)` cannot be fully defined yet"* | — | all ten untyped `K_t` components | **this is the sharpened smallest-missing-primitive finding of this phase** — the corpus's own text, not an inference by this reconstruction |

## Correction to this ledger's own earlier framing (disclosed, not silently repaired)

The initial pass of this ledger (rows above the "Primary-source additions" heading) characterized
`R_t` as an open registry needing closure, treating this as structurally identical to `𝒪_K`/R10
(MD-058). The primary source (M0043) shows `Req(EC_t)`'s parameterization by `EC_t` is a **deliberate
design choice** (purpose-relativity), not an unclosed registry per se — the actual blocker, per
M0132's own direct statement, is `Sat`'s own *computability*, which depends on `K_t`'s component
semantics, not on `R_t`'s own closure. Both framings point to genuine, real obstructions; the
primary-sourced one is more precise and supersedes the earlier one throughout this MD-059.
