# 1av-b: de-cued pilot. Reconciliation

| | |
|---|---|
| Frozen | `5e1dde247` · codings sealed `43c4eab4e` · scores + review task `13539bdf0` |
| Scores (recomputed: match) | P3~P4 type **1.000** · source_act 0.833 · vs the (cued) audit 0.889 · 3 labels changed vs the cued P1 |

## Reconciled findings
1. **Individuation precedes classification.**
   - **11/36 events** (3 collision groups: REGISTER × 2, OPEN-WORK × 2, R-36 × 7) are **individuated only by the coder's description**, not by any source-anchored field.
   - Latent span collisions: **E01**, **E25**.
   - **E22's anchor excerpt (L480–490) does not contain the described line (L493)**: my evidence-scope defect, disclosed.
2. **Changed labels:**
   - E07 = **COLLISION** (the cued GENERIC_PRACTICE is more faithful);
   - E22 = **CUE-DRIVEN** (the cue was a locator; the cued UNKNOWN is more faithful);
   - E25 = **CUE-DRIVEN** (a latent span collision: the de-cued coders coded the original filing of R-90, not the re-use of the retired number).
3. **Kind's witnesses dissolve further (D11, D12).** The two "strict" k-pairs differ in **zero recorded fields**. Each pair is **one sentence stating a distinction**, with one member being the negative side of a norm ("A recording note cannot open a work package"; "the register holds constitutional decisions"). **Not two attested occurrences.** k: NOT DEMONSTRATED (confirmed, with a structural reason).
4. **Evidence (D13):** e's remaining R-36 cluster pair is **individuated only by description** (the matrix items are not anchored spans).
5. **Authority flag (D3, open):** E01, a member of authority's first witness pair (the Chief's declined ADOPT), shifted its quote under de-cueing. **Authority's two pairs must be re-verified under span-level individuation** before "SUPPORTED" is relied on.
6. **Agreement still degenerate (D9):** 13 identical quotes, identical coined verbs, identical hard-case choices. On collision groups agreement is guaranteed, so there are at most 28 distinct records. **Cue sensitivity belongs to the input, not the coder.** The cued audit cannot adjudicate cue effects (D10).

## Schema implication (reviewer; FORMAL CONSEQUENCE of "individuation precedes classification")

**A verbatim individuating span is required under both A and B.** It is separate from `type_quote`, and together with operation and target it must be unique across records. **Records that resolve to the same span are merged** (marked dual when act + norm). R-36 item events need their item-level source, or must be merged to cluster level.

## Model impact

- **The empirical core is now provisional even for authority**, pending re-individuation.
- The measurement problem is **two layers deep**:
  - **individuation** (what counts as one event);
  - **classification** (norm vs act).

  Both precede any variable-support claim.
