# Interpretation-sensitivity map: blind slice (LOOP-1BQ)

**Status:** analysis output (AI-generated, Claude family; not reviewed). It does not modify the bundle, the comparators or the sealed results.

**Question.** Where can a careful literal reader of the frozen spec (BUNDLE/) choose differently? For each such choice, which BLIND quantities (U_P2) change, and does the U frame change? A change in U contradicts the sealed V1b values, and so R5/V1b.

## 0. Method and validation
- **Base program:** `CLAUDE-SIDE/slice_enum.py` (Opus), copied unmodified into scratch. It reproduces the sealed `SLICE-RESULTS.json`, sha256 `cc6e635f872ef15b889b8112663a26afc755447893e12aa3a3bd54a6e18dcb40` (Python 3.13.2, 98 s).
- **Variants.** Each variant changes only the code that implements one clause. The exact diff is in each file's header (`variants/<id>/<id>.py`). They were generated mechanically, with an assert that each replaced site occurs exactly once (`tools/mkvariants.py`).
- **Change detection.** Every leaf of a variant's `SLICE-RESULTS.json` is compared with the base (`tools/build_json.py`).
  - The tier (BLIND/EXPOSED) and the distinct-quantity id come from the frozen comparator `compare_slice.py`, run as (base, variant) into scratch.
  - The U frame is also checked against the sealed V1b values with the frozen `calibrate_slice.py`, which covers 181 units.
- **Correction (errors are data).** The first V6 build changed `bconf_b`, which the Opus code shares between R-b and R-c. That build therefore also changed R-c, which is not the named clause: R-c's B-conf has its own explicit P0.1 restatement. It was rebuilt with a separate R-b variable, and only the rebuilt V6 is reported. The discarded run moved R-c sizes, which shows that the base's R-c result rests on P0.1's explicit "w1 present and conforms".

## 1. Ambiguity table

| # | Spec quote | Baseline (both Claude programs) | Defensible alternatives (variant) | Rejected readings (reason) |
|---|---|---|---|---|
| AMB-1 | K7 (R5 §7.2): "every change point of σ′ at which no act of h enters into force (under f′) with the new value is f(a2)"; Prop 4.1(iii): "…is the entry into force of an added act". SLICE-SPEC §3 names this clause as ambiguous in U_P2. | Applies to **every** change point of σ′, so in T={0,1} to t=1 whenever σ′(0)≠σ′(1). "The new value" is σ′(1). It is covered by a1 (iss, f′=1, v=σ′(1)), or else a2 must be added with f(a2)=1. | **V1a:** "the new value" is the value **newly set by h′**, so the clause binds only a change point at a time where σ′ differs from σ (t(w1)=1). **V1b:** the clause binds only change points of σ′ that are **not already change points of σ with the same new value**. | (i) "act of h enters into force" evaluated under h's f, not f′. Contradicted by K7's explicit "(under f′)" and Prop 4.1's "(in h′)". (ii) A converse reading: every f(a2) must be an uncovered change point. "every change point … is f(a2)" does not state the converse. (iii) The clause also requires v(a2) = the new value. This is already forced by F1, so it is not a distinct reading. |
| AMB-2 | P0.2: "a2 optionally added as an unreferenced iss act with prep 1 and con none"; Def 1.7b (+act): "any norm, any α(a), any d(a), any f(a)". "The new value" in AMB-1. | v(a2) ∈ V, d(a2) ∈ T and f(a2) ∈ T∪{∞} are all free, subject to F1. | **V2:** the added act carries **the new value**, v(a2) = σ′(t(w1)), the value that makes w1 conform. | (i) a2 is mandatory. K7 says "differs **at most** … and by a2 present"; P0.2 says "optionally". (ii) a2 referenced, or con ρ1. Contradicted by "unreferenced", "con none". (iii) a2 restricted to d ≤ f, or f ≠ ∞. Def 1.8 imposes no order, and analytically there is no effect on any value: d enters only Adopted, which H-B does not use, and an a2 with f=∞ can never help h′ into H-B^𝐑, because scope histories already have a1 present (D-rec). |
| AMB-3 | K7 / P0.2: "σ changes only at t(w1)" / "differs at most in σ (only at t(w1))"; Prop 4.1(iii): "differs from h only in σ (and only at **violation points** of h)". | σ′ = σ except at t(w1). The other time point is kept even if it is also a violation point. | **V3:** the Prop 4.1(iii) wording. σ′ may also differ at **any other violation point** of h (σ(t) ∉ P), where it ranges over V. At t(w1) it must lie in P. | (none further) |
| AMB-4 | P0.2: "(σ changes only at t(w1); f free; …)" | Read as "σ′ differs from σ only at t(w1)", as in K7. | **V5:** additionally read as a constraint on σ′'s own change points, Chg(σ′) ⊆ {t(w1)}. With t(w1)=0, σ′ must be constant. | (none further) |
| AMB-5 | V1.3 R-b: "B-conf^b: some w ∈ W conforms" (no P0.1 restatement). | W=∅ gives false; in U this means w1 present and conforming. | — | "vacuously true when W=∅" is **not literal**, because ∃ over ∅ is false. Run as a **control (V6)**: B-rec contains B-recW, so w1 is always present in R_B^b and H-B^b, and the choice is structurally inert. |
| AMB-6 | §7.1 "For adp: obj = the other slot, which must be a present iss act"; Def 1.4. | An adp slot exists only if the other slot is a present iss act. adp acts count in D-rec, R-i and R-h (they are in E). con may be ρ1 on adp (F2). | none defensible | Counting only E^iss in D-rec, or forbidding con on adp. Both contradict "a ∈ E" and "con(s) for each present act s". Dangling adp is excluded by the typing in Def 1.4. |
| AMB-7 | K4: "≈_{σ,f}, i.e. equal on every enumerated coordinate except σ and the values of f"; Def 1.7. | Everything other than σ(0), σ(1) and the f-values is kept. | none defensible | Letting f's domain vary. By Def 1.7, the domain is fixed when no item set is in X. |
| AMB-8 | Def 1.10 Chg(h) = {(t,τ): 1 ≤ t ≤ N, …}; B-chg "some w has t(w) < t" | With T={0,1}, the only change point is t=1, and the work disjunct is "w1 at 0". | none defensible | "t(w) ≤ t" is not the text. |

Only AMB-1 to AMB-4 admit defensible alternative literal readings that could move a computed value. All of them concern the (iii)-L witness. AMB-5 is computed as a control.

## 2. Per-variant results

"U-consistent" means that `calibrate_slice.py` finds 0 mismatches against the sealed V1b U values.

| Variant | Clause | Changed BLIND units (U_P2): baseline → variant | U frame changes (vs sealed V1b) | Verdict |
|---|---|---|---|---|
| **V1a** last clause binds modified times only | AMB-1 | `R-h/(iii)-L/n_counterexamples` 324 → 132. `R-h/(iii)-L/least_counterexample` σ=(r,r), a1 iss d0 v=p prep0 f∞, Cap ∅, w1 t=0, Ref {a1,w1} → σ=(r,r), a1 iss d0 v=r prep0 f0, Cap {adp}, w1 t=1, Ref {a1,w1}. | **2:** U R-h (iii)-L n_cex 228 → 132, and its least cex | **contradicts V1b** |
| **V1b** last clause binds new change points only | AMB-1 | none (results byte-identical to `cc6e635f…`) | 0 | U-consistent, **no effect** |
| **V2** a2 carries the new value | AMB-2 | `R-g/(iii)-L/n_cex` 348 → 540; `R-g+/(iii)-L/n_cex` 348 → 540. In addition, non-BLIND U_P2 units change: (iii)-L becomes false for R0, R-b, R-c, R-d, R-e, R-f, R-i and R-i′ (n_cex 192, 192, 384, 144, 192, 384, 384, 384), each with a new least counterexample. | **26:** (iii)-L becomes false in U for R0, R-b, R-c, R-d, R-e, R-f, R-i and R-i′; R-g and R-g+ n_cex 252 → 348 | **contradicts V1b** |
| **V3** σ′ may change at every violation point | AMB-3 | `R-h/(iii)-L/n_cex` 324 → 0. `R-h/(iii)-L/least_counterexample` → null. (Non-BLIND: R-h (iii)-L holds false → true.) | **3:** U R-h (iii)-L holds false → true, n_cex 228 → 0, least cex → null | **contradicts V1b** |
| **V5** Chg(σ′) ⊆ {t(w1)} | AMB-4 | `R-g/(iii)-L/n_cex` 348 → 696; `R-g+/(iii)-L/n_cex` 348 → 696; `R-h/(iii)-L/n_cex` 324 → 456. In addition, non-BLIND U_P2 units change: (iii)-L becomes false for R0, R-b, R-c, R-d, R-e, R-f, R-i and R-i′. | **27:** (iii)-L becomes false in U for 8 readings; R-g and R-g+ 252 → 480; R-h 228 → 360 | **contradicts V1b** |
| **V6** (control, rejected reading) R-b vacuous on W=∅ | AMB-5 | none (byte-identical to `cc6e635f…`) | 0 | inert, as predicted |

Details:
- Full per-unit values are in `SENSITIVITY.json`, under `variants.<id>.changed_units` (BLIND), `changed_U_P2_nonblind_units` and `u_frame_changes`.
- No variant changes any size, scope size, (i)-S or (ii)-S quantity, or any (i)-O or (ii)-O value. AMB-1 to AMB-4 act only on the (iii)-L witness search, and the (iii)-L scope does not depend on it.
- No variant crashed. Run times were 120–133 s each, with all variants run in parallel.

## 3. Conclusion

There are 67 distinct BLIND quantities. Of these, **63 are interpretation-robust under every defensible reading found**: all U_P2 class sizes, all scope sizes, n_frame_histories, and every (i)-S and (ii)-S counterexample count and least counterexample. None of the identified ambiguities touches them, and the shared choices behind them (adp typing, ≈_{σ,f}, the single change point, R-b) admit no alternative literal reading that changes a value; the R-b control confirms this. The **interpretation-sensitive** BLIND quantities are the four (iii)-L witness quantities in U_P2:
- `ncex:(iii)-L@R-g`;
- `ncex:(iii)-L@R-g+`;
- `ncex:(iii)-L@R-h`;
- `lcex:(iii)-L@R-h`.

These four move under readings V1a, V2, V3 and V5 of the K7 last clause, the added-act value and the place where σ′ may change. However, **every reading that moves a BLIND value also moves sealed U values**, so it contradicts the sealed V1b (and so R5/V1b) results. The only alternative that stays consistent with U, V1b ("change points already in σ need no new coverage"), changes nothing. So, given the sealed U calibration, the blind slice is robust to every identified ambiguity. **Limitations:**
- The sensitivity of these four quantities is real only for an implementer willing to diverge from V1b's U values.
- The calibration anchors the shared interpretation to V1b, which is itself a Claude-family product. This map therefore cannot separate "V1b and both slices share a misreading" from "the reading is correct".
- The ambiguity list is one Claude reader's enumeration. It is not exhaustive by construction.

---

## Review reconciliation (REVIEW-SENSITIVITY: PASS_WITH_LIMITATIONS; 2026-10-08, before any non-Claude return)
The review re-ran V3 and got a byte-identical result. It also ran a probe variant of its own, V1c (the last clause binds only change points that cross the P boundary): **inert, `cc6e635f…`**. All variant diffs are faithful and single-site. Every required change is applied here; the sections above are kept unchanged.

1. **AMB-3 is downgraded to WEAK / non-literal.** Def 1.10 defines violation for work events ("w violates … iff σ(t(w)) ∉ P"). So "violation points of h" (Prop 4.1(iii)) most naturally means {t(w1)}, which is the same as K7 and P0.2. V3's "any t with σ(t) ∉ P" is not in the text. V3 stays in the map as a control.
2. **Added "no alternative" rows:**

   | Point | Spec | Why there is no alternative |
   |---|---|---|
   | (ii)-S h_v "σ_v constant, w1 present and violating" | K6 / P0.2 | With P = {p, q}, the only violating value is r, so σ_v = (r, r) is the unique solution |
   | (ii)-S h_c "w1 conforming" | K6 / P0.2 | Either p or q is allowed; both programs accept both |
   | (ii)-S n_counterexamples | SLICE-SPEC §3 | Explicit: "either h_v or h_c is missing" |
   | Three-value order | SLICE-SPEC §1 | Explicit: p < q < r |

   **Interaction, recorded:** under R-h, B-rec^h requires every present act slot to be in Ref, while K7's added a2 is "unreferenced". So under R-h an added a2 can never lie in H-B. Every sensitive R-h result therefore comes from the σ′ clauses (AMB-1 and AMB-3), not from AMB-2.
3. **Readings invisible in U (named; their extent is not bounded).** Some readings agree with the baseline whenever |V| = 2 and |P| = 1, and differ only when |P| ≥ 2 or |V| ≥ 3. **The U calibration cannot detect them, and they are exactly what U_P2 brings into play.** One such reading was tested (V1c) and was inert. The class is not enumerated. **Pairwise combinations of variants were NOT run**, e.g. V1b+V2 or V1b+V5; a combination could be U-consistent even where each part is not.
4. **The conclusion, restated:** the 63 robust quantities are robust under every identified single-clause reading. **The 4 sensitive quantities are robust conditional on the V1b U values and on single-clause readings that U can detect.** V1b, the base program and this map are all Claude-family. This is consistency with V1b, not correctness.
5. **Decision procedure for a non-Claude disagreement** (applied mechanically after the return is sealed, before any interpretation):
   - **D0. U-frame check first.** If the return's U values disagree with sealed V1b, take that U disagreement through the comparator and PREREG classes before anything else.
   - **D1. A disagreement on a robust quantity (one of the 63) while U agrees → class A** (implementation), **unless the implementer's NOTES.md records a reading outside the AMB list.** In that case → **undetermined (new ambiguity)**; the reading is added to the map and goes to the human.
   - **D2. A disagreement on a sensitive quantity (one of the 4) that exactly matches one variant's fingerprint on all changed units in U_P2, with the implementer's NOTES naming that reading → class D (interpretation, AMB-k).** If the fingerprint matches but the NOTES are silent → class D, flagged AMBIGUOUS.
   - **D3. A disagreement on a sensitive quantity with no exact variant match → UNDETERMINED.** It is never class A, because the AMB list is not exhaustive and combinations were not run. Then an implementation audit follows, plus a reviewer; the result goes to the human.
   - **D4. Agreement on all 67 → cross-family reproduction under the implementer's interpretation.** If its NOTES record a different reading on an AMB point while the values still agree, that point is shown to be inert for this slice.

   Mapping to the frozen PREREG classes: A → (a) or (b); D, and any UNDETERMINED result → (c) until resolved; a mathematical discrepancy → (d). Every non-(a) result goes to the human (PREREG-SLICE §4).
