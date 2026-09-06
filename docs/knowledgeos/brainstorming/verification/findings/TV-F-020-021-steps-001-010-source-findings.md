# TV-F-020 / TV-F-021 — Source findings from independent reconstruction of Steps 001–010

**VERIFY SESSION · 2026-08-29 · Phase 2B first deep-verification batch (mandate «200-Step End-to-End Reconstruction» §25.5–9). Sources read directly; nothing taken from prior registers without re-check.**

---

## TV-F-020 — The dependency relation ≺ has inconsistent orientation at its birth site

- **Claim under examination:** step-002 §5 defines `e₁ ≺ e₂` as "e₂ was produced using e₁" (parent ≺ child). Step-002 §6 Case B (raw API response e₁ → extracted JSON field e₂) then writes **`e₂ ≺ e₁`** — child ≺ parent — with the author's own caveat *"depending on how we orient the provenance relation."*
- **Source:** `phase_measure_theory/20260827-140919_step-002-…same.md` §5 (lines ~211–216) vs §6 Case B (lines ~271–278).
- **Level:** L1 Math (well-definedness of a load-bearing relation).
- **Test performed:** direct comparison of definition site and first use site.
- **Result:** **UNDER-SPECIFIED.** The orientation of ≺ is explicitly left open by the author and never fixed in Steps 001–010. Downstream, step-004 E-K4 (`e_i ≺ e_j ⇒ e_j not independent of e_i`) and step-003 §11 dependency sensitivity consume ≺ assuming the §5 orientation; K0's P5 frame (~, ≺) adopted the §5 orientation as a **PROPOSED RECONSTRUCTION** — this finding records that the source itself does not settle it.
- **Severity:** MINOR (both orientations give isomorphic theory; but every formal statement using ≺ is orientation-relative, and the corpus never says which).
- **Non-consequence:** this does NOT invalidate any rim theorem — T-K proofs quantify over the graph structure, not the arrow direction convention.
- **Disposition required:** one-line orientation convention in the final vocabulary (governance/notation act; no mathematical content).
- **Register sync:** AC C-062.

## TV-F-021 — Two overlapping, unreconciled transition-operation alphabets in Steps 007/009

- **Claim under examination:** step-007 §11 defines the Knowledge-State transition operations `𝒪_K = {Introduce, Corroborate, Qualify, Contradict, Supersede, Resolve, Retract, Invalidate}` (8 ops, δ: K×𝒪_K ⇀ K). Step-009 §22 — two files later, same day — defines the belief-revision operations `ℛ_K = {Contextualize, Corroborate, Downgrade, Retract, Supersede, Accept, Reject, Defer, Resolve}` (9 ops, Revise: K×ℛ_K ⇀ K).
- **Overlap:** {Corroborate, Retract, Supersede, Resolve} shared · only-𝒪_K: {Introduce, Qualify, Contradict, Invalidate} · only-ℛ_K: {Contextualize, Downgrade, Accept, Reject, Defer}.
- **Source:** `20260827-142709_step-007-…` §11; `20260827-152002_step-009-…` §22. Neither file cites or maps to the other's alphabet; step-009 does not say ℛ_K extends, replaces, or coexists with 𝒪_K.
- **Level:** L1 Math (transition-algebra canon).
- **Result:** **INCONSISTENT-BY-SILENCE (two partial operation alphabets over the same carrier K, no union act, no supersession act).** The split then continues: step-051 §4 introduces a third alphabet (event set Σ, 9 events), and Q-series/025-series carry further variants — this finding pins the **origin** of the transition-alphabet divergence to 007→009, before any later fork.
- **Severity:** MAJOR for canonicalization (Δ-unification cannot proceed without a governance choice), MINOR for the rim (no proven result depends on a specific alphabet).
- **Non-consequence:** T-K9 (replay induction) holds for any finite operation alphabet with deterministic δ given (ρ,ω); the finding blocks only the *canonical* transition algebra, not replay.
- **Disposition required:** anti-reconciliation procedure over {𝒪_K, ℛ_K, Σ(051), later variants}: classify pairwise (equivalent/compatible/incompatible/orthogonal); a merged alphabet would be a PROPOSED RECONSTRUCTION.
- **Register sync:** AC C-063.

---

## Minor source observations (recorded, not findings)

1. **Step-003 §3 type slip, repaired in-corpus:** `Conflict(P) = True iff S⁺>0 ∧ S⁻>0` type-checks only under a numeric projection of S± that the same document (§13–14) declares non-fundamental. Step-004 §7–8 repairs this by restating conflict set-theoretically (`E⁺≠∅ ∧ E⁻≠∅`). Classified **RESOLVED in-corpus** (a genuine refinement satisfying the §15/§5 resolution test); recorded because it is the cleanest example of the corpus fixing its own type error within one hour.
2. **Step-004 triple numbering of one axiom set:** the same material carries (a) sixteen unnamed section-properties, (b) the §19 kernel/policy split A₁–A₈, (c) the §20 constitution E-K1–E-K10 — three ID systems in one file. Already partially reflected in A3X; the definition-verification register must key on E-K IDs (the only set reused downstream).
3. **Step-007 notation:** ℋ_t is a component of K_t (§2) while §3 asserts K_t ≠ H_t and §15 treats H_t as external fold input — consistent (containment vs identity) but the ℋ_t/H_t duplication seeded the later History-in-or-out-of-the-tuple oscillation (25K excludes it; 31.14 makes it a projection; 51.2 re-includes it — B5 C-1).
4. **Text corruption:** step-007 §22 contains a malformed boxed LaTeX expression (`$$\boxed{P\land\neg P$$` unclosed); step-010 §15 has `Similarity\neqIdentity` (missing spacing). Rendering defects only.
5. **TV-F-001 corroboration strengthened:** step-003 §12 is a *third*, even earlier in-corpus statement of the monotonicity scope disambiguation (support-aggregation monotonic; complete assessment not), alongside step-004 §6 and closure-04a §8. The G12/G1 gap classification in TV-F-001 stands; the corpus stated the scope from the very beginning.
