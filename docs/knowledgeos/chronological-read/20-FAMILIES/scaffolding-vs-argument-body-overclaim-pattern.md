# scaffolding-vs-argument-body-overclaim-pattern

**Scope(s):** METHODOLOGICAL · **Row count:** 6 ·
**Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `overclaims live in headings/lead-ins/summaries/register rows, never in argument bodies`; `sweep on claim TYPE, not specific STRING`
**Aliases:** "scaffolding overclaim pattern"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0051, scope METHODOLOGICAL: "A generalizable methodological finding from auditing the Step-287 equality research: every presentational overclaim found across two audit passes lived in scaffolding (a section heading, lead-in sentence, summary line, or register row) rather than in the body of an argument, and a targeted correction sweep keyed on a specific offending string (e.g. 'K_{t+1}≻K_t') systematically misses structurally-identical overclaims using different wording (e.g. 'the axes ARE the observations', 'partial order by construction') -- future correction sweeps should target the general claim-type pattern ('X is Y' asserted flatly in scaffolding) rather than specific strings."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2127 §"> # VERDICT: **REQUIRES-CORRECTION** > **Two BLOCKING findings — and both are the SAME DEFECT CLASS I fixed once already:** > **a heading or lead-in sentence asserting what its own body refutes.** The nine audits caught the §4 heading and the `08` F5 heading. **They missed the §3 heading and the `08` F5 body.**"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S2133. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type: true.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S2127 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S2133 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2127, S2129 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S2133 |
| experiments | PRESENT | S2127 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

Identifies a generalizable meta-pattern: every one of the eight overclaim findings across both audit passes sits in scaffolding (a heading, lead-in sentence, summary line, or register row), never in the body of an argument -- the nine-prompt audit corrected the reasoning thoroughly but under-corrected the scaffolding, because its propagation sweep was keyed on a specific string ('K_{t+1}≻K_t') rather than on the general claim-type pattern ('X is Y' asserted flatly in a heading or summary). Recommends future correction sweeps target claim TYPE rather than specific strings. Nothing in the artifact's substantive reasoning is wrong -- all findings are presentational overclaims, exactly what a scaffolding-focused audit is designed to catch. [S2127]

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S2127] types=[CONTRADICTION, VALIDATION] scope=OBJECT — "Despite the prior nine-prompt audit's thorough repair, finds two BLOCKING residual defects of the exact same class it had already fixed elsewhere: a heading or lead-in sentence asserting what its own body immediately refutes. Specifically, Step 287's own §3 heading ('the axes ARE the observations') is explicitly refuted seven lines later in its own body, and the '08-FINDINGS' source document's F5 body paragraph ('it is a partial order by construction and NOT total') survives untouched even though the F5 heading above it was already corrected with an appended SUPERSEDED block -- a reader reaching the body paragraph first receives the refuted claim before the correction." (anchor: "> # VERDICT: **REQUIRES-CORRECTION** > **Two BLOCKING findings — and both are the SAME DEFECT CLASS I fixed once already:** > **a heading or lead-in sentence asserting what its own body refutes.** The nine audits caught the §4 heading and the `08` F5 heading. **They missed the §3 heading and the `08` F5 body.**")
- [S2127] types=[EXPERIMENTAL-RESULT] scope=CROSS-OBJECT — "Systematically checks all 18 forbidden inferences identified across this batch's Step 287/288 equality research (Sigma-order->K-order; candidate-construction-as-established; 32-projections-as-selected; math-equivalence-as-architectural; product-order-before-components; any-axis-automatically-ordered; value-level-implies-state-level; under-specified-hiding-two-deficits; Decision3+X-solves-equality; D285-5-proving-general-non-injectivity; grantId-implies-authority-identity; provenance-implies-state-identity; relevance-principle-as-predicate; Step261-bypass; Step288-already-solved; philosophy-as-evidence; available/constructed-as-established; not-established-as-impossible), finding 12 clean and 6 still carrying presentational findings." (anchor: "| 3 | 32 projections ⟹ a relation is selected | ⚠️ **M-4, M-5** | | 5 | product partial order exists before components | ⚠️ **B-2, M-1, M-2** | | 6 | any Σ coordinate is automatically ordered | ⚠️ **M-3** | ... **12 clean · 6 carrying findings.**")
- [S2127] types=[ANALYSIS, PRINCIPLE] scope=METHODOLOGICAL — "Identifies a generalizable meta-pattern: every one of the eight overclaim findings across both audit passes sits in scaffolding (a heading, lead-in sentence, summary line, or register row), never in the body of an argument -- the nine-prompt audit corrected the reasoning thoroughly but under-corrected the scaffolding, because its propagation sweep was keyed on a specific string ('K_{t+1}≻K_t') rather than on the general claim-type pattern ('X is Y' asserted flatly in a heading or summary). Recommends future correction sweeps target claim TYPE rather than specific strings. Nothing in the artifact's substantive reasoning is wrong -- all findings are presentational overclaims, exactly what a scaffolding-focused audit is designed to catch." (anchor: "**Every one of the 8 findings sits in a HEADING, a LEAD-IN SENTENCE, a SUMMARY LINE, or a REGISTER ROW. Not one is in the body of an argument.** The nine audits corrected the reasoning thoroughly and **under-corrected the scaffolding** — precisely because a sweep keyed on `K_{t+1} ≻ K_t` finds `K`-order propagation and **not** `≈` or *"partial order"* propagation. **The F5 sweep was too narrow, by construction.** **Recommendation for future passes:** sweep on the **claim type** (*"X is Y"* asserted flatly, in a heading or summary) rather than on the **specific string**.")
- [S2129] types=[CONTRADICTION] scope=OBJECT — "Finds a second new material defect (M-7) in the invariants artifact: it states a count of testable-today invariants three separate times, disagreeing with itself -- one location says I-V is 'the only one testable today', a later location says I-V AND I-O are the only candidates testable today, and I-O's own row already independently marks it testable -- the same defect class as the earlier BLOCKING findings (a local statement contradicting the document's own conclusion), now appearing in a brand-new artifact on its first pass." (anchor: "Line 102 (§6, `I-V` row): *"✅ **the only one testable today**"* Line 156 (`ESTABLISHED` block): *"**`I-V` and `I-O` are the only candidates testable today**"* And line 104 (§6, `I-O` row) itself reads *"✅ **testable** — because it is NOT a transition invariant."* ... **the artifact contradicts itself on a count it states three times: one vs two.**")
- [S2129] types=[EXTENSION, PRINCIPLE] scope=METHODOLOGICAL — "Extends the scaffolding-overclaim pattern to a fourth confirmed instance (this re-run's own M-7 count contradiction), alongside the §4 heading/body contradiction, the 08-F5 body/SUPERSEDED-block contradiction, and the 00-INDEX 6th/11th headline contradiction -- all four are local statements contradicting the document's own conclusion, and all four sat in a heading, summary row, or count, never in an argument. Proposes a standing methodological check: after any substantive edit, re-verify every count, heading, and summary row against the document's body." (anchor: "| 4 | **§6 *"the only one"* vs `ESTABLISHED` *"`I-V` and `I-O`"*** | **this re-run** | > **All four are a local statement contradicting the document's own conclusion, and all four sat in a heading, a summary row, or a count — never in an argument.** ... A standing check is warranted: **after any substantive edit, re-verify every count, heading and summary row against the body.**")
- [S2133] types=[WARNING, CORRECTION] scope=METHODOLOGICAL — "Step 288 was written without consulting the 025i-025z corpus seam (dated 2026-08-28, two days before Steps 246 and 260), which already contains a knowledge identity algebra, semantic equivalence/refinement/merge treatment, knowledge state algebra, and entity resolution work. The author diagnoses this as the third instance of a recurring personal error: 'searching for a phrase instead of a concept' (had searched for 'behavioural equivalence' and found Step 260, but not for 'refinement', 'merge', 'entity resolution', or 'same-as')." (anchor: "**`REFINED-STEP-288.md` was built on an incomplete evidence base. Six of its claims are corrected here; one is REFUTED.**") — lineage claim: SOURCE-CLAIMED-CORRECTION of REFINED-STEP-288.md

## Notes for P3

- Lifecycle is mechanically flagged CONTESTED, with `contested_by_own_contradiction_type: true` — a CONTRADICTION-typed row is present (S2127, S2129); P3 should verify the specific tension directly rather than take the flag as settled.
