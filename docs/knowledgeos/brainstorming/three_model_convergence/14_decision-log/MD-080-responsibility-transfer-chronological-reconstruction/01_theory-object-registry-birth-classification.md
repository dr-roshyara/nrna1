# MD-080 §01 — Theory Object Registry: Birth Classification

Classification vocabulary: **TERM BIRTH** (the bare word/symbol appears, no formal content) /
**CONCEPT BIRTH** (a genuine idea is introduced, informally) / **FORMAL OBJECT BIRTH** (a type,
tuple, or equation is given) / **RECONSTRUCTION ONLY** (this reconstruction's own derived finding, not
corpus-native) / **UNKNOWN** (not determined by evidence available to this phase).

Every row below is either (a) directly verified by this reconstruction's own primary-source reading
(this phase or MD-067–079), marked `VERIFIED`, or (b) drawn from the concurrent session's own
`01-BIRTH-CENSUS-DET-R-FAMILY.md` and not independently re-checked this phase, marked `UNVERIFIED
(concurrent census)` — per this reconstruction's own standing discipline, an unverified secondhand
claim is reported as such, never silently upgraded to fact. One concurrent-census claim is corrected
this phase (see `EvalReq`, already recorded in MD-078); one further is corrected below (`Zero`).

| Object | Birth classification | Source | Status |
|---|---|---|---|
| `Sat` | CONCEPT BIRTH (2026-08-27, `step-023` §10: "the degree/status to which K satisfies r_i," immediately used as a stipulated fiat value, no computation rule) → FORMAL OBJECT BIRTH (2026-09-01/02, T5 `[00-51]`: `Sat(K_t,r)∈{0,1}`) | `phase_measure_theory/step-023`; math lane `[00-51]` | VERIFIED |
| `EC_t`/`EC` | CONCEPT BIRTH + FORMAL OBJECT BIRTH, simultaneous (2026-08-27, `step-023` §14: "I recommend introducing EpistemicContract as a first-class domain concept," 7-field tuple given immediately) | `phase_measure_theory/step-023` | VERIFIED |
| `Req` | FORMAL OBJECT BIRTH (2026-09-01/02, T5 `[00-47]`, as part of the `EC_t→Req(EC_t)→r→Sat→Δ_t→Zero` co-birth) | math lane `[00-47]` | VERIFIED. A claimed earlier **TERM BIRTH** (2026-08-16, `reviews/…knowledge-placement-requirement-registration.md`) is reported by the concurrent census — almost certainly ordinary-English "requirement" in a governance/registration process, not the formal `Req(EC)` function; **not independently verified this phase**, flagged `UNVERIFIED (concurrent census), likely homonym` |
| `r` | FORMAL OBJECT BIRTH, two independent lineages: T5's own `[00-51]` (2026-09-01/02, 7-tuple `(id,type,scope,content,standard,priority,validity)`); T21 Part II Def 2.18 (2026-09-06, abstract `r∈Req`) | math lane; T21 Part II | VERIFIED |
| `standard` | TERM BIRTH only (2026-09-01/02, one field-name inside `[00-51]`'s own 7-tuple) — **never reaches CONCEPT or FORMAL OBJECT BIRTH anywhere in the corpus**; relocated to `EC.Rules` at T21 (2026-09-06), itself never given internal structure in any of the 21 parts | math lane `[00-51]`; T21 Part I/II | VERIFIED (GAP-001, MD-068, reaffirmed exhaustively MD-078) |
| `Acceptance`/`AcceptanceCondition` | bare `Acceptance`: **TERM BIRTH** claimed 2026-08-16 (`reviews/…KOS-ATTR-ARCH-001-review-decision-summary.md`) — `UNVERIFIED (concurrent census)`, plausibly ordinary-English governance usage. `AcceptanceCondition` (the compound, tracked term): **no birth of any kind found** — zero occurrences anywhere in the corpus, confirmed exhaustively (MD-078 §01, 5 independent censuses) | — | VERIFIED (the negative) |
| `Eval` | FORMAL OBJECT BIRTH (2026-09-06, T21 Part VI §6.15, `Eval:K×E×P×EC×Γ→𝒱`) — a differently-shaped `Eval(M,D)→E_model` also formally born the same day (Part 17 §17.24), `UNRELATED_HOMONYM` | T21 Part VI, Part 17 | VERIFIED. A claimed earlier **TERM BIRTH** (2026-08-26, `phase_measure_theory/question-4-evidence-and-epistemic-state-transition.md`) is `UNVERIFIED (concurrent census)` this phase |
| `Eval_c` | FORMAL OBJECT BIRTH, `kos12/evalc.py`, self-dated ~2026-09-02 per its own docstring | `research/knowledgeos-sim/kos12/` | VERIFIED (MD-078) |
| `Sat_c` | FORMAL OBJECT BIRTH, `kos12/satc_spec.py`, self-dated ~2026-09-02 | `research/knowledgeos-sim/kos12/` | VERIFIED (MD-078) |
| `Δ_t`/`Δ` | FORMAL OBJECT BIRTH (2026-09-01/02, T5 `[00-47]`/`[DEF-21]`: `Δ_t={r∈Req(EC_t):¬Sat(K_t,r)}`) | math lane `[00-47]` | VERIFIED |
| `Zero` | FORMAL OBJECT BIRTH (2026-09-01/02, T5 `[00-47]`/`[DEF-22]`: `Zero(K_t,EC_t)⟺Δ_t=∅`) | math lane `[00-47]` | VERIFIED. **Correction to the concurrent census**: its claimed 2026-08-24 birth (`kernel/…godel-escher-bach-extraction….md`) is directly verified this phase to be a **different, single-argument `Zero(K)` "Zero lens" construct** ("That gives us a very concrete definition for Zero: `Zero(K)=…`"), already tracked by this reconstruction's own prior work (MD-069) as one of four permanently distinct branches (`ZeroLens`, kept separate from the canonical `Zero(K,EC)`). Recorded forward; the concurrent census's own file not edited |
| `Det_r` | FORMAL OBJECT BIRTH (2026-09-06 00:39:47, T21 Part VI §6.18, signature only, body disclosed as "may be defined") — **single occurrence anywhere in the corpus**, confirmed twice independently (MD-078) | T21 Part VI | VERIFIED |
| `EvalReq` | FORMAL OBJECT BIRTH, partial (argument list only, no codomain — codomain is `RECONSTRUCTION ONLY`, forced by composition) — same single occurrence | T21 Part VI §6.17 | VERIFIED. **Correction, already recorded in MD-078**: the concurrent census's claimed 2026-08-27 birth (`step-025e`) is a substring false positive on `EvalRequirement`, a homonym |
| `Determination` | FORMAL OBJECT BIRTH (2026-09-06, T21 Part III Def. 3.5, proven via Thm. 3.1) | T21 Part III | VERIFIED. A claimed earlier **TERM BIRTH** (2026-08-19, `reviews/…KOS-AIP04-DISCOVERY-001-…correction2.md`, a governance lane) is `UNVERIFIED (concurrent census)` — **the concurrent census's own Finding 3 explicitly flags, and this phase does not resolve**, whether the governance-lane `Determination` and the mathematical `Determination` are the same object; recorded as `UNRESOLVED`, not silently assumed either way |
| `Decision` | FORMAL OBJECT BIRTH, two forms: T21 Part III Def./Thm. 3.2 (separation proof, 2026-09-06); T21 Part 16 §16.4 (`D=⟨Q,A,K,EC,U,C,Π,Γ,t,Auth,Status⟩`, richer, same day) | T21 Part III, Part 16 | VERIFIED. A claimed earlier **TERM BIRTH** (2026-08-26, `phase_measure_theory/most-important-discovery-of-the-round.md`) is `UNVERIFIED (concurrent census)` this phase |
| `Γ` | FORMAL OBJECT BIRTH (2026-09-06, T21 Part II Def. 2.15, 7-tuple `⟨D,P,T,U,C,V,A⟩`) — at least three further, mutually inconsistent formal definitions within the same rewrite (MD-078 §01) | T21 Part II | VERIFIED. A claimed earlier **TERM BIRTH** (2026-08-24, `kernel/…typed-mathematical-epistemic-model.md`) is `UNVERIFIED (concurrent census)` this phase — given the `Zero` correction immediately above, this specific claim is flagged for skepticism, not confirmed or refuted |

## Note on the `UNVERIFIED (concurrent census)` rows

Six of sixteen objects carry a claimed pre-`step-023` (i.e., before 2026-08-27) `TERM BIRTH` sourced
only to the concurrent session's own census, not independently checked by this phase. **One of the two
spot-checked this phase was found to be a confirmed homonym (`Zero`)** — this is disclosed explicitly
as a reason for caution, not as grounds to reject the remaining unverified claims outright. None of
these earlier `TERM BIRTH` claims, even if genuine, changes any `FORMAL OBJECT BIRTH` classification
above — a bare term appearing before a concept is formalized is expected and unremarkable; it would
only matter if it turned out to carry formal content, which none of these six claims to.
