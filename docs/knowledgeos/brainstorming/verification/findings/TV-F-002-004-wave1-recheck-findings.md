# TV-F-002 · TV-F-003 · TV-F-004 — Wave-1 source re-check findings

Method for all three: independent re-check by exact quotation with full-context reading; no repair performed. Date 2026-08-29.

---

## TV-F-002 — Zero findings both inside and outside the Knowledge State (C-023) — **CONFIRMED**

**Level:** L1 Mathematical · **Severity:** MAJOR
**Claim under examination:** Q15-revised (the corpus's most-developed "frozen" transition model) simultaneously (a) states T5 *"Zero evaluates Knowledge; Zero does not constitute Knowledge"* (§1.1 line 2313; repeated as core invariant §9.1 line 2833), (b) writes every transition rule's output as the 6-tuple `(𝒜_t, ℛ_t, ℰ_t, ℋ_t, 𝒵_t, ℒ_t)` with `𝒵_t` in the 5th slot — and §6.4 *mutates* it (`𝒵_t'` "has the conflict marked as resolved"), and (c) defines `S_t=(K_t, Z_t, L_t, …)` with `Z_t` glossed "Zero findings (gaps, conflicts)" *beside* `K_t` (§8.3).
**Test:** component-gloss check — could `𝒵_t` denote something other than Zero findings? **No**: the revised section never re-glosses the tuple; the only glosses for the Z symbol anywhere in the file are Zero findings, and §6.4's own gloss puts conflicts inside `𝒵_t`, matching §8.3's wording exactly.
**Result:** **CONTRADICTED (internal)** — Zero findings are simultaneously constitutive of K (transition rules) and excluded from K (T5) and external to K (S_t). **Aggravating evidence:** the same file's pre-revision review (lines 1229–1265) diagnoses precisely this and prescribes `Zero(K_t,I_t)→Z_t` *"rather than"* `→K_{t+1}` — the revised §6 does not apply its own file's fix.
**Consequences:** any Level-1 treatment of the "frozen" transition model must first choose one horn; ⭕-3 (Zero-in-K self-reference) stands. Dependency: the Q20-revised model (which excludes Z from S_t by invariant) is *incompatible* with Q15-revised on this point — supersession between the two is NOT established (C-002 stands).
**Required disposition:** research decision on where Zero findings live (state vs derived analysis vs persisted-as-knowledge-by-explicit-act — the pre-revision text's own third option). Possible repair (PROPOSED REFINEMENT only): adopt the file's own review prescription; consequence: the 6-tuple loses two slots and every §6 rule must be rewritten — a *theory change*, not a correction.

---

## TV-F-003 — Composition law: overloaded notation, not opposite directions (C-024) — **MODIFIED**

**Level:** L1 Mathematical · **Severity:** MINOR
**Claim under examination:** Step 204 §204.8 states composability both as `Post_{τ₁} ⇒ Pre_{τ₂}` and as `Composable(τ₁,τ₂) ⟺ Post(τ₁) ⊇ Pre(τ₂)`.
**Test:** reading analysis against the file's own definitions (§204.1 types Pre/Post as Boolean predicates; §204.3 as contract slots; neither ever set-valued) and its worked example §204.11 (`Post_{τ₁}=VerifiedEvidence`, `Pre_{τ₂}=VerifiedEvidence ∧ RequiredContext` ⇒ composition FAILS).
**Result:** under states-as-sets, the two lines are converse (P⇒Q ≙ states(P)⊆states(Q)) — a flat contradiction; but §204.11's outcome is reproduced by the *clause-set* reading (`⊇` over guaranteed-condition clauses) and by the implication, and inverted by the state-set reading. **The intended reading is clause-containment; the state-set reading is ruled out by the file's own example.** Residual defect: **UNDER-SPECIFIED / overloaded notation** — `⊇` applied to objects never defined as sets, disambiguated only by the informal gloss "for the relevant conditions".
**Required disposition:** register reclassification (done); a formal typing of Pre/Post (predicate vs clause-set with the containment direction fixed) is a PROPOSED REFINEMENT. The composition law itself, read as clause-containment, joins the P2 proof package as a candidate CONDITIONALLY PROVEN (it is then just precondition-strengthening logic).

---

## TV-F-004 — Zero replay determinism vs human oracle (C-040) — **MODIFIED**

**Level:** L1 Mathematical / L2 Computational · **Severity:** MINOR (gap), was recorded as contradiction
**Claim under examination:** 025d §25D.27 claims Zero reproducibility "whenever the same inputs and model versions are used" while §25D.12 permits `Satisfied(K,r,EC) = HumanAuthorization(K,r)`.
**Test:** qualifier-coverage analysis + whole-file search for recording/persistence of authorization outcomes.
**Result:** the determinism qualifiers demonstrably do **not** cover human acts (declared inputs are `K_t`, `EC_G` only; "model versions" never extended to human oracles; no explicit statement that authorization outcomes are persisted). However, three passages imply **lookup semantics**: the signature `HumanAuthorization(K,r)` takes K as argument; `GovernanceApprovalObtained` is elsewhere evaluated as a K-resident status (`Missing` when "not been issued", §25D.7); Zero results are immutable with preserved evidence (§25D.9, §25D.36 steps 5–6); and §25D.21 separates execution authorization from Zero evaluation. **Reclassified: UNDER-SPECIFIED** — the human-oracle case is not brought under the §25D.27 qualifier set; the recording of authorization outcomes is implied but never stated.
**Required disposition:** the replay-boundary item (V3 P11) sharpens to: *state which evaluator classes are replay-safe (recorded-artifact lookups) vs replay-unsafe (live oracles), and require the former for deterministic assurance* — PROPOSED REFINEMENT only. LB-4's status is unchanged (conditional).

---

**Register updates applied by these findings:** AC C-020 → G12+G1 gap (TV-F-001) · C-023 → CONFIRMED contradiction (TV-F-002) · C-024 → notation defect (TV-F-003) · C-040 → underspecification (TV-F-004). Wave-1 remaining: P1 satisfiability probe (in flight) + P2 rim proofs (verifier work, next).
