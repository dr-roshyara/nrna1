# `KR-ZOOM-01` — CONCLUSIONS

**Read `AUDIT.md` first.** Seven metrics were found degenerate; the conclusions below are already
narrowed accordingly.

---

## 1. What the experiment established

`[EXP]` **In the tested synthetic regime:**

1. **An observed value became a knowledge state with further structure** in ~70 % of cases,
   across four typed traversal regimes, on two independent splits. *(Criterion warning: `AUDIT-1`.)*
2. **Traversal order never produced the same final state — 0 of 518 admissible pairs — while
   producing the same contract observable in ~30 % of them.** This replicated across splits.
3. **Zoom changed the contract observable in ~70 % of cases, LOST determination in ~7 %, and
   gained it in 0 %.** Refinement can make the answer *worse*.
4. **The zoomed state supported a further reasoning cycle in ~55 % of cases**, with the contract
   determinate in ~94 % of those.
5. **All structural divergence in the counterfactual was confined to the anchor-deletion trap.**
   Without decision D2 this would have been reported as an ~11 % finding.
6. **Varying $Q$ across resolution changes the observable in 72 % of cases** — quantifying
   exactly what the fixed-$Q$ discipline (D1) protects against.

## 2. What the experiment did **not** establish

- **Not** that Zoom differs from decomposition. Relation kinds are generator-assigned (§8 of
  RESULTS). **The claim is not made.**
- **Not** that silent dimensions affect next-resolution structure — the operator *cannot* show it
  (`AUDIT-2`).
- **Not** H3 in any form (`AUDIT-5`).
- **Not** anything about non-synthetic carriers. **No carrier is declared.**
- **Not** that atomicity transitions occur outside a generator that can produce them (`AUDIT-7`).

## 3. Hypothesis status

| | status |
|---|---|
| H1 recursive refinement | **SUPPORTED IN TESTED REGIME** ⚠️ non-discriminating criterion |
| H2 resolution-relative atomicity | **SUPPORTED IN TESTED REGIME** ⚠️ representable, not discovered |
| H3 Zero instability across resolution | **INCONCLUSIVE** — untestable in this design |
| H4 resolution-dependent determination | **SUPPORTED IN TESTED REGIME** |
| H5 recursive re-basing | **SUPPORTED IN TESTED REGIME** |
| H6 Q-reconstruction | **NOT SUPPORTED** ⚠️ not independent of H4 |
| §13 counterfactual (structural) | **AUDIT INVALIDATED** |
| §13 counterfactual (observable) | **SUPPORTED IN TESTED REGIME** — ~4 % |
| §12 order dependence | **SUPPORTED IN TESTED REGIME** — the strongest result |

## 4. Audit status

**14/14 gates PASS. 7 degenerate metrics. One defect found and repaired in the audit itself**
(gate P conflated *untracked* with *modified*). Deterministic replay byte-identical.

## 5. Observed counterexamples

- **Determination loss:** 56 (train) / 58 (test) cases where zooming made $Q$ **less** answerable.
  A counterexample to any reading of "refinement always improves knowledge".
- **Different state, same observable:** 76 / 78 pairs. A counterexample to inferring state
  identity from observable identity.
- **Same relations, different observable:** 9 / 12 pairs. The converse counterexample.
- **`CONTROL C`:** 67 / 53 roots where outward Zoom succeeded while inward terminated — a
  counterexample to atomicity being a property of the value.

## 6. Implications for Knowledge Algebra

`[EXP]` **Order dependence at the state level does not imply order dependence at the observable
level.** ~30 % of order-swapped pairs agreed on the observable while **none** agreed on the state.

> `[REC]` **A commutativity test conducted on the observable alone will conclude that the
> operators commute when they do not.** This is the second independent setting in which
> non-commutativity has appeared (after `KR-ZERO-ALGEBRA` `H2`, 11/1 162). **`ES-006.1` requires a
> second independent adopter before promotion is discussable — this may be one. It is recorded,
> not proposed.**

## 7. Implications for Zero / Śūnya

- **Projection ≠ Zero held throughout** — but in the safe direction only (`AUDIT-6`).
- **H3 remains open and now has a stated blocker:** testing Zero instability across resolution
  requires **dimension identity that survives Zoom**. That is a design requirement, not a tuning
  parameter.
- `[EXP]` Zero rates rose from 0.777 at $r=0$ to 0.847 at $r=1$ — **finer states had proportionally
  *more* eliminable material**, not less. Not adjudicated; recorded.

## 8. Implications for Representation Reduction

`[EXP]` Zoom **lost** determination in ~7 % of cases and gained it in **none**. Read alongside
`KR-REP-REDUCTION`'s DPI corollary — adequacy cannot be regained along a deterministic sequential
chain — this is **consistent**, and worth stating precisely: **Zoom here is not a sequential
chain** (each $K_{r+1}$ is generated from a node, not derived from $K_r$ by a function of $K_r$
alone), so the DPI does **not** apply and the observed monotone direction is **not** forced.

## 9. Implications for State Transition / Re-basing

`[EXP]` Re-basing succeeded in ~55 % of cases with the contract determinate in ~94 % of those —
direct support for `KR-STATE-01`'s premise that $K_{r+1}$ can serve as a substrate rather than a
terminus. **`KR-STATE-01` remains unauthorized; this does not authorize it.**

## 10. Candidate next experiments

1. **`KR-ZOOM-02` — a set-anchored zoom operator**, so the counterfactual becomes testable
   (`AUDIT-2`). **Highest value: it repairs the one thing this experiment could not measure.**
2. **Dimension identity across resolution**, to make H3 testable at all (`AUDIT-5`).
3. **A discriminating non-triviality criterion** — generate 1-dimension children so H1 can fail.
4. **`KR-ALGEBRA-IA-01`** — the Information Algebra crosswalk. **Deliberately NOT combined with
   this experiment**, per the spec's own instruction; run only now that order dependence is measured.

## 11. Governance recommendation

> ### **EXPERIMENTAL EXTENSION + NEW HYPOTHESIS. NOT kernel modification.**

- **No change** to Theory v1.2, the kernel, any frozen artifact, or any prior experiment's results.
- **Experimental extension:** `KR-ZOOM-02` with a set-anchored operator (§10.1).
- **New hypothesis (`[CONJ]`):** *state-level order dependence does not imply observable-level
  order dependence.* **Refutable by** a regime in which final-state disagreement and
  observable disagreement coincide. Two independent settings now show them coming apart.
- **No new formalization candidate is proposed.** The order-dependence result is a *measurement*,
  not an algebraic property, and `AUDIT-1`/`AUDIT-7` mean this experiment is not a basis for one.
- **Explicitly NOT recommended:** any claim that Zoom is a kernel primitive, an operator, a
  topology, or a law. Per spec §22, and per the evidence.

---

## 12. AMENDMENT 2026-09-04 — operator misidentification

See `RESULTS.md` §9. The implemented operator was **restriction-zoom (descent)**, not
**inquiry-zoom**. `[NEG]` **The principle it violated is `Inquiry focus ≠ Knowledge boundary`.**

**Every conclusion above is re-scoped to descent operators.** The order-dependence result
survives intact under that scope. The ~7 % determination loss survives but measures **the cost of
making the focus the boundary** — which makes it the first quantitative support for the owner's
principle rather than a result about epistemic zoom.

**Governance recommendation is unchanged in kind** (experimental extension, not kernel
modification) but its content is replaced: `KR-ZOOM-02` is respecified as an **inquiry-zoom**
experiment, not a set-anchored descent. **The set-anchored proposal in §10.1 was necessary but
not sufficient — it widened the anchor while still making it the boundary.**
