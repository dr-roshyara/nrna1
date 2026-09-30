# status-sigma-five-axis-overload

**Scope(s):** OBJECT · **Row count:** 8 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "Status", "Σ" · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0042, scope OBJECT: "Status/Σ is shown by decision-signature analysis to overload >=5 orthogonal facts (asked, evidence, authority, supersession, validity) plus a sixth process fact (Contested) in one word; a lossless single enum would need 96 (or >=16 for Σ×Γ alone) values. Went uncaught for 270 corpus steps because Ubiquitous-Language audits looked for inconsistent usage rather than dimensional overload (UL-1/G-06/G-29, EXP-21..23)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1718 §"UL-1 (`DERIVED`, CRITICAL). `Status` is the worst case: five orthogonal facts"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1748. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S1748), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1718 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1720, S1725, S1725, S1732, S1738, S1738, S1748 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
UL-1: Status overloads five orthogonal facts (asked, evidence, authority, supersession, validity) plus a process fact in one word; a single enum would need 96 values (per finding 06/SG-2); this is a modelling error a Ubiquitous Language audit exists to catch, and it went uncaught for 270 steps because prior UL audits looked for inconsistent usage rather than dimensional overload. [S1718]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1718] types=[ARGUMENT, LIMITATION] scope=THEORY-LEVEL — "UL-1: Status overloads five orthogonal facts (asked, evidence, authority, supersession, validity) plus a process fact in one word; a single enum would need 96 values (per finding 06/SG-2); this is a modelling error a Ubiquitous Language audit exists to catch, and it went uncaught for 270 steps because prior UL audits looked for inconsistent usage rather than dimensional overload." (anchor: "UL-1 (`DERIVED`, CRITICAL). `Status` is the worst case: five orthogonal facts")
- [S1720] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "G-06 (CRITICAL): Σ/Status is ≥5 orthogonal axes forced into one word; a single enum needs 96 values; EpistemicStatus≠GovernanceStatus is correct but separates only 2 of ≥5 axes." (anchor: "| **G-06** | **Σ is ≥5 orthogonal axes in one word.**")
- [S1725] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "EXP-22: by decision-signature analysis over 10 situations, the underlying facts a status word must carry are asked, authority, evidence, supersession, validity — five orthogonal facts whose worst-case cross-product is 96 states; a one-dimensional Σ is therefore provably inadequate." (anchor: "=> A one-dimensional Sigma is provably inadequate: it would need 96 values.
     The distinctions are NOT one axis.  They are at least FIVE orthogonal facts.")
- [S1725] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "EXP-23: systematically dropping each of the five candidate axes (asked, evidence, authority, supersession, validity) one at a time produces a harmful collision (two situations requiring different decisions become identical) in every case, demonstrating necessity without pre-choosing any vocabulary; existing corpus words map onto four of the axes (Unknown/Supported/Refuted/Conflicted → evidence; Accepted/Rejected → authority; Superseded → supersession; Invalidated/Stale → validity) while Contested reads a sixth, unmodeled, open-process fact." (anchor: "=> Every axis whose removal produces a HARMFUL collision is IRREDUCIBLE")
- [S1732] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "EXP-12: the running system has exactly two independently-varying schema-enforced status axes (lifecycle: draft/approved/baseline/frozen; source-trust/authority: authoritative/derived/generated/provisional) with 7 observed combinations confirming empirical independence — but neither axis is the theory's Σ (epistemic support/Supported-Refuted-Conflicted-Unknown); no field in the running system records whether a claim is supported by evidence at all." (anchor: "BUT -- and this is the finding -- NEITHER axis is the theory's Sigma.")
- [S1738] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Σ⊥Γ orthogonality is SUSTAINED on ten constructed, meaningful, distinct (Σ,Γ) cases (e.g. Refuted+Committed: authority committed a claim the evidence refutes; Supported+Contested: the PF-6 residue), independently corroborated by corpus Step 271.20's own statement of the requirement with the same worked case; a lossless single vocabulary would need >=16 values." (anchor: "A lossless single vocabulary would need **≥16 values**. **The separation is necessary.**")
- [S1738] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Executed: identical evidence yields Supported under policy(min_support=2) and Unknown under policy(min_support=3) — Σ(a) alone is ill-typed, only Σ(a,policy) is well-typed; this also refutes the corpus's own relation model, whose r carries a bare Σ field with no policy parameter, which is exactly what creates the Policy→K→ℛ→Σ→Policy cycle." (anchor: "> **Therefore `Σ(a)` is ill-typed. Only `Σ(a, policy)` is well-typed.**")
- [S1748] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Raw execution confirms all ten constructed (Σ,Γ) quadrant cases are meaningful and distinct, requiring a lossless single vocabulary of >=16 values, and that identical evidence yields Conflicted regardless of min_support in this particular constructed example — Σ is derived and policy-relative." (anchor: "=> every one of the ten is a MEANINGFUL, DISTINCT state.
     A single status vocabulary must therefore have >= 16 values")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
- family.files_touching lists ['S1741'] in addition to the source_ids that appear in family.rows — no row from ['S1741'] appears in this label's row list. Noted as a data-completeness oddity for P3, consistent with a pattern seen in other labels processed in this batch.
- Rows for this label were captured under more than one scope tag (['OBJECT', 'THEORY-LEVEL']) — this may reflect genuine cross-scope relevance (e.g. an OBJECT used at THEORY-LEVEL) rather than a labeling error, but P3 may want to confirm.
