# MD-090 §02 — TheoryState Updates and New Object Entries

Per the master mission's identity discipline (§6): no identity is inferred from shared symbol, name,
purpose, or chronological proximity. Where evidence is insufficient either way, the state is recorded
as `IDENTITY UNRESOLVED — INSUFFICIENT EVIDENCE`, not defaulted to `UNRELATED_HOMONYM`.

## Corrections to already-tracked `TheoryState`s (recorded forward; MD-067–089's own text not edited)

### `Sat(K,r)`
- **Birth corrected**: Part 01 §21, not Part IV/V as MD-067/076 recorded. `Sat` is Boolean at birth,
  with an explicit self-disclosed note that "later versions may require richer semantics."
- **New evidence on scarcity**: outside the recurring `Δ_X={r∈Req(X):¬Sat(K,r)}` template (now confirmed
  instantiated across nine domains, §01), `Sat` as a free-standing concept appears exactly once in
  Parts 07–09 combined (one theorem's proof only) and zero times elsewhere. This sharpens, not
  contradicts, MD-085's `E-A` verdict — `Sat` is not merely under-evidenced for bridging purposes, it
  is structurally marginal to the theory's own remaining eighteen parts once past its birth.

### `EC` (Epistemic Contract)
- **Birth corrected**: Part 01 §19, not Part V.
- **New evidence**: `EC` is instantiated with at least four different field-count/name variants across
  the corpus even before counting the pre-T21 competing formulations already tracked in MD-085's
  Closure Matrix (Part 01's 6-field original; Part 02's abbreviated-name 6-field refinement; Part 19's
  reuse as a bare, unelaborated parameter; Part 18's own `AC`, an architecture-specific sibling
  contract type, never identified with `EC` itself).

### `Δ_t` / `Zero`
- **Birth corrected**: Part 01 §22/§23 (Definitions), not Part IV/V.
- **New evidence, genuinely new finding**: `Δ`'s own signature is unstable *within* Part 17 itself —
  §17.3 introduces a bare, contract-implicit `Δ_t`; §17.32 (same part, ~900 lines later) introduces a
  differently-shaped 2-argument `Δ(K_t,EC_t)` with no `Req`/`Sat` machinery shown — a within-document
  drift, not only a cross-document one. Recorded as a new, sharper instance of the pattern MD-078's own
  T21-inconsistency finding (`EKS-54`) already tracks.

### `Det_r` / `EvalReq`
- **Formal status corrected and weakened**: `Det_r` is introduced in Part VI §6.18 in prose — "A
  contract-specific determination function **may be defined**" — never given a numbered `Definition`
  heading, unlike `Det`'s own `Definition 6.2` two sections earlier. `EvalReq` likewise has no numbered
  Definition. Both occur **exactly twice each**, confined entirely to §6.17–6.18, never reused anywhere
  in the remaining 17 parts or either worked example. This is a stronger, more precisely evidenced
  basis for MD-076's own Terminal Classification C ("formally specified but semantically open") — the
  object is not merely uncomputed, its own textual status is tentative even at birth.
- **Worked-example confirmation, extends MD-070**: both flagship worked examples (21a, 21a-rev2)
  stipulate `Sat(K,r_i)=Satisfied` directly, with zero invocation of `Det_r`/`EvalReq`/`Eval` anywhere —
  the fiat-stipulation pattern MD-070 found in an earlier example is now confirmed, by full-text
  extraction, to be exactly how the theory's own *final, most polished* demonstration handles it too.

### `Determination ⇏ Decision`
- **Massively reinforced, not new in kind**: Part 13 alone dedicates a named section (§13.26, "Decision
  Is Not Determination") and Theorem 13.65 ("Determination Non-Implication Theorem") to this; Part 16
  restates it as a boxed constitutional principle and two further named theorems (Decision-Theoretic
  Separation Theorem, Risk-Decision Separation Theorem). This is now independently corroborated by at
  least six distinct sections across Parts 13, 16, and 18's own architecture layer — the single most
  heavily reinforced negative claim found anywhere in this 23-file corpus.

## New `TheoryState` entries opened this phase

### `Γ_R` — Reasoning Context (Part 21)
- `Γ_R=⟨Context,Time,Contract,Model,Rules,Assumptions,Authority,Resources⟩`, Definition in Part 21
  §21.something (batch H). A domain-specific refinement of the generic `Γ` parameter, scoped to the
  reasoning/proof-object apparatus specifically.
- **Identity relative to the generic `Γ`** used throughout the rest of the corpus: `IDENTITY
  UNRESOLVED — INSUFFICIENT EVIDENCE`. `Γ_R` is presented as a specialization, not tested against
  generic `Γ`'s own instantiation across other parts.

### `Δ_Cog` / `Zero_Cog` — Reasoning Knowledge Gap (Part 21)
- A further domain instantiation of the `Δ_X`/`Zero_X` template, this time for the reasoning-engine
  layer: `Δ_Cog(K,RCog)={r∈Req(RCog):¬Sat(K,r)}`, `Zero_Cog(K,RCog)⟺Δ_Cog(K,RCog)=∅`.
- Confirms, does not extend, the already-established template-reuse pattern (§01). Not a new construct
  in kind, recorded for completeness of the domain inventory.

### The four-relation identity family — `=`, `≡_sem`, `≈_{Q,Γ}`, `≅_prov` (Part 08)
- Formally named and axiomatized as a set for the first time (structural equality, semantic
  equivalence, inquiry-relative observational equivalence, provenance-sensitive equivalence), with
  stated implication ordering (`≅_prov⇒≡_sem`, not conversely).
- **Cross-check against this reconstruction's own tracked `≡_sem`/`⪯_cap` research line** (MD-043–058,
  the MinKer/kernel-minimality thread): not tested this phase — flagged as a candidate cross-lane
  comparison for a future phase, not investigated here (out of MD-090's own bounded scope).

### `Standing` (Part 13, third confirmed instance)
- "Determination provides epistemic standing; policy governs decision" (§13.26) — an informal prose
  phrase, not a formal object with its own defined structure.
- **Identity relative to MD-080's tested `Standing(p)` evaluator and MD-089's FactFinding-pipeline
  `Standing` waypoint**: `UNRELATED_HOMONYM`. Positive evidence for distinctness: this usage carries no
  field structure, no evaluator semantics, and sits inside an entirely different architecture (the
  Determination/Decision/Policy separation, not an evidence-assessment pipeline) — the bar for
  `UNRELATED_HOMONYM` (positive evidence of distinct meaning/context/responsibility) is met, not merely
  absence of a found relationship.

### `Decision`/`Act`/`ADR` object family (Parts 16, 18) vs. the post-T22 `ActionRationale`/`AR_t` chain
(MD-089's own Thread 3, `F_t≠AR_t≠W_t≠Decision_t≠Authorization_t≠Action_t`)
- T21's own Part 16 defines `D=⟨Q,A,K,EC,U,C,Π,Γ,t,Auth,Status⟩` and `Act=⟨Decision,Actor,Authority,
  Target,Intent,Parameters,Time,Context,Provenance,Status⟩`; Part 18 adds `ADR=⟨Question,Alternatives,
  Evidence,Assumptions,Decision,Rationale,Constraints,Consequences,Authority,Time,Version,Provenance⟩`.
  The post-T22 segment (MD-089, one day later in the corpus's own chronology) independently builds its
  own `ActionRationale`/`AR_t`/`Warrant` apparatus with no citation of T21's `Decision`/`Act`/`ADR`
  objects found in either extraction pass.
- **Identity**: `IDENTITY UNRESOLVED — INSUFFICIENT EVIDENCE`. Not tested this phase (would require a
  dedicated cross-reference check between the two segments' own decision/action apparatus, out of this
  phase's own bounded scope) — recorded as a candidate for the next chronological frontier, not
  resolved here. Per the mission's own §6, absence of a found relationship is not itself evidence of
  `UNRELATED_HOMONYM`.

## Objects considered and explicitly not opened as new `TheoryState`s

The pervasive `Δ_X`/`Zero_X` template's other domain instances (`Δ_P`/`Zero_P` persistence, `Δ_R`/
`Zero_R` retrieval, `Δ_T`/`Zero_T` temporal, `Δ_C`/`Zero_C` causal, `Δ_M`/`Δ_F` model/forecast, `Δ_R`/
`Δ_D` risk/decision, `Δ_Arch`/`Zero_Arch` architecture) are recorded in the impact map (§01) as
confirmed instances of one already-identified pattern, not opened as individually new objects — opening
nine near-identical `TheoryState` entries for one template would misrepresent nine instantiations as
nine discoveries.
