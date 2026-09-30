# 1au: reconciliation. The schema-r2 decision package (no adoption)

**Review verdict: PASS_WITH_LIMITATIONS.** The drafted package (A/PACKAGE.md) is **not** handed over as-is. The human decision brief below incorporates the reviewer's corrections.

## Corrections adopted from the review
1. **Necessity is narrow.** Only **kind's** loss of support is attributable solely to the norm/act distinction: both k-pairs are identical on every v1 field except k and outcome, so no analysis-time filter can separate them. **State's** support is circular by construction, and can be removed by an **anti-circularity rule** without r2. **History** fails on anchoring already.
2. **The dual-nature problem is unhandled.** Every NORM record is the *content* of a ruling act. **Both surviving authority pairs sit on this boundary.** A single `observation_type` field suffices for an act-only count **only if** it is defined relative to the record's own operation. The NORM dataset needs a **two-level representation**: the record's own act vs the norm content it states.
3. **A minimal alternative exists** (v1-internal, no new field):
   - (i) the epistemic basis of `outcome` is mandatory;
   - (ii) a record enters legality witnesses only if an attempt at or performance of `o` is attested by an anchored source;
   - (iii) anti-circularity: `outcome` may not be coded from the same text as the witnessed variable.
4. **Loaded options** are corrected, and a **missing option** is added: **defer + a blind pilot**. That means a blind coder applies the act/norm distinction to the 36 events, testing the falsification criteria F1/F3 before adoption.
5. **Fidelity fixes:** some of worker A's inferences were labelled SOURCE FACT; tier assignment was inconsistent (F1–F3 in REVIEW.json).

## Sealed expectation
The two-level representation ✓. The narrow-necessity result and the minimal alternative were not anticipated.
