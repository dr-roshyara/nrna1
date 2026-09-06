# TV-F-005 … TV-F-008 — Wave-1 P1/P2 findings (satisfiability, scalar qualifier, gate defect, registry collisions)

Date 2026-08-29. Methods: independent proof/model construction by the verifier (class B/C) on inputs re-verified against sources by exact quotation.

---

## TV-F-005 — Scalar-impossibility claim requires a continuity/order qualifier — **corpus claim MODIFIED, refined theorem PROVEN**

**Level:** L1 Mathematical · **Severity:** MINOR (precision) · **Source claims:** EXP-01 verdict / GN-46 §14 ("no scalar in [0,1] can retain (S⁺,S⁻)"); I-9's normative content.
**Result (A4 T-K7):** (i) **PROVEN:** no *continuous* injective map `[0,1]² → ℝ` exists (invariance-of-domain / compactness argument), and no *order-respecting* scalar survives the executed `{0.9,0.9}` vs `{0.1,0.1}` counterexample; (ii) **REFUTED as literally stated:** set-theoretic injections `[0,1]²→[0,1]` exist (digit interleaving), so the unqualified sentence is false on a technicality. **Consequence:** the design doctrine (structured status, no universal confidence scalar) stands on the *qualified* theorem. **Non-consequence:** scalar *projections* (GapCount, Coverage) remain legitimate as labeled projections. **Disposition:** final theory carries the qualified statement; register note to MV-F/EXP-01 framing (advisory).

---

## TV-F-006 — `SafetyGate=False ⇒ ¬Execute` is silently permissive at `Unknown` — **CONFIRMED defect (expressiveness, not inconsistency)**

**Level:** L1 Mathematical · **Severity:** MAJOR (safety-relevant formulation defect) · **Source:** step-048 §48.20 vs §48.26 (`Unknown ≠ False ≠ True`), and the executed witness `ladder_dc_reference_output.txt` ("safety-critical unknown → Block: PASS").
**Test:** three-valued semantics analysis. With `SafetyGate ∈ {True, False, Unknown}` (mandated by invariant 15), invariant 9's antecedent fires only on `False`; at `Unknown` it imposes **no prohibition** — exactly where the corpus's own witnessed Unknown→Block rule (042 §42.12) forbids execution.
**Result:** invariants 9 and 15 are jointly satisfiable (no inconsistency) but invariant 9 **fails to express its evidently intended semantics** under the value space invariant 15 mandates. As written, a state with `SafetyGate=Unknown` and `Execute(d)` satisfies the entire step-048 set. **UNDER-SPECIFIED / formulation defect.**
**Counterexample:** M with one decision d, `SafetyGate(d)=Unknown`, `Execute(d)=True`, all other invariants vacuously satisfied — satisfies `⋀I₁…I₂₀`.
**Non-consequence:** does not impugn the witnessed Block behavior (which implements 042, not 048). **Disposition:** PROPOSED REFINEMENT (label only): `SafetyGate(d) ≠ True ⇒ ¬Execute(d)`; adopting it is a governance decision.

---

## TV-F-007 — Invariant joint satisfiability (P1) — **𝒱 ≠ ∅ PROVEN-UNDER-INTERPRETATION; the system is a SCHEMA, and it is liveness-free**

**Level:** L1 Mathematical · **Severity:** — (foundational result, mixed positive/negative) · **Target:** consolidated core `I_core` = ratified I-1…I-12 + step-048 1–20 + step-120 K1–K7 (verbatim statements re-verified against sources).

### Construction 1 — quiet model M₀ (vacuous satisfaction)
Sorts: one Knower N; world set W={w₁,w₂} with Ω(w₁)=Ω(w₂) (secures I-7's `X_t ≠ Observed(X_t)` structurally); one policy P₀ (version 1) *in force since t=0, never changed*, with a recorded external adoption act; empty evidence store, no assertions, claims, decisions, executions, AI proposals, transitions.
Interpretation of undefined predicates: any (they occur only in conditionals whose antecedents are false in M₀); type distinctions (proposal/decision/authorization/…, Unknown/False/True) realized as distinct values.
**Check:** every step-048 invariant and K1–K7 obligation is a universal conditional over events/objects that M₀ lacks → vacuously true; I-1 holds by assigning ownership to N; I-2/I-3/I-4/I-9 hold as type-level distinctness; I-5/I-6 vacuous; I-8 vacuous; I-10/I-11 vacuous (no changes); I-12 vacuous (no transitions). **M₀ ⊨ I_core.** ∎

### Construction 2 — nontrivial model M₁
Extend M₀: evidence e₁ with full 5-field provenance (immutable store); assertion A₁ (Candidate) supported by e₁; ladder path Candidate→Supported (evidence event) →Accepted (Determination under P₀v1, recorded) →Committed (authority act, recorded) — consecutive steps only (I-12), boundary crossed by `Act_auth` only (A6/I-4); decision d at time t with: all DC components True, `Admissible(d)` interpreted as their conjunction (T-K5), `Authorized(d)` via P₀v1 + recorded authority, `PolicyVersion(d)=P₀v1` (inv 19), `SafetyGate(d)=True` (inv 9), knowledge basis = state at t (inv 3, no hindsight), model version recorded (inv 18); execution X(d) with post-observation O' (K7 capability realized); no revisions, AI proposals routed Evaluation→Authorization (inv 16) — one included and blocked to exercise it. Each of the 39 core obligations checks by direct inspection. **M₁ ⊨ I_core.** ∎

### Results
1. **𝒱 ≠ ∅ : PROVEN-UNDER-INTERPRETATION.** Both vacuously (M₀) and nontrivially (M₁) — *relative to verifier-chosen interpretations of the ≥12 undefined predicates* (`Admissible, Authorized, Applicable, authoritative, material, governed, SafetyGate, CriticalClaim, Uncertain/UncertaintyPreserved, Reconstructable, ValidTransition, the covering order`). 
2. **The invariant system is a SCHEMA, not a theory.** Its satisfiability question is **not well-posed as stated** in the corpus: with uninterpreted predicates it has models trivially; with *intended* semantics it is **UNVERIFIED** because the intended semantics are undefined. This sharpens (and partially discharges) LB-3: vacuous unsatisfiability is *excluded*; substantive satisfiability is *relative*.
3. **Structural theorem (new):** the consolidated core contains **no liveness obligation** (every member is safety-shaped; K7 demands a *capability*, not an occurrence; 048's liveness item is an example outside the 20). Hence the **empty/quiet system satisfies the whole invariant set** — the invariants alone cannot characterize KnowledgeOS, only envelope it. Any claim that the invariant set "defines" the system is thereby REFUTED (none found asserted, but §120.61's intersection formula invites the reading).
4. **Bootstrap analysis (sharpens MV-F-8 / ⭕-1):** I-11 guards *changes* only. For any policy P, the state "P has always been in force, never changed" satisfies I-11 vacuously — **the invariant system cannot see illegitimate genesis**. PAIR-1's suspected S₀ inconsistency dissolves (vacuous satisfaction at S₀ is available), but the *legitimacy* gap is thereby proven to lie **outside** the invariant system: genesis needs an axiom/boundary condition (per 1514 §P6 options), not another invariant of the same shape. **PROVEN (vacuity of the change-guard at genesis).**
5. **Non-consequences:** none of this establishes the *intended* system's consistency (undefined predicates), the adequacy of the invariant set (completeness question 048 §48.34 remains OPEN), or anything about the registries' mutual mapping (TV-F-008).

**Disposition:** P1 closes with the four results above; the final theory's Part I must present the invariant system as a *safety-envelope schema over 12 uninterpreted predicates* with satisfiability relative to interpretation — and must carry the genesis-blindness theorem.

---

## TV-F-008 — Invariant-registry identifier chaos: CONFIRMED and worse than recorded — **CONFIRMED + EXTENDED**

**Level:** L1 (integrity of the formal record) · **Severity:** MAJOR (for any consolidation step) · **Re-check of:** C-042/X.21.
**Results (all by verbatim quotation):**
1. `I_1`/`I-01` has **five** distinct denotations (recorded: three): Historical immutability (025a-5) · Evidence integrity (048) · Observation integrity (162) · `Observation ≠ Interpretation` (Step 161 **and** chapter-183 — two further registries not previously mapped).
2. `Unknown ≠ False` carries **six** different IDs (recorded: ≥3): 048 #15 · 162 I-14 · 025a-5 I6 · 161 I_6 · 183 I_7 · **168/169 L_4** (a further registry prefix, `L_n`, previously unmapped) — with materially different content strength (three-way vs two-way vs Null-distinction).
3. **Swapped-ID phenomenon (new):** between Steps 161 and 183, `I_6`/`I_7` exchange meanings (`Unknown≠False` ↔ other invariants) — same prefix, same numeric range, different sequences.
4. **Tooling hazard confirmed:** registries in Steps 159–205 live in extension-less raw-title files (`# step 162 We continue with…`, `chatper 1-183_…` [sic]); timestamped `step-NNN` files stop at 158; several byte-identical duplicate pairs exist. Any glob on `*step-1??-*.md` silently misses the late registries — a plausible cause of the corpus's own failure to map them.
5. K5's MUST/SHOULD dual boxes (C-033) re-confirmed verbatim, with the demotion *stated as deliberate* while the MUST box stands — plus newly quoted near-duplicate pairs (K6 scope narrowing between its own two boxes; K4's predicate change; 048 #1 "should" inside a hard ⋀-conjunction).
**Consequence:** the consolidated invariant catalogue (final theory Part I) **cannot cite corpus IDs as identifiers**; it must mint fresh IDs with a full provenance column. **Disposition:** consolidation rule adopted for Part I (verifier-owned artifact); no corpus change proposed.
