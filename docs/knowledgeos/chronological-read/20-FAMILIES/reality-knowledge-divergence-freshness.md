# reality-knowledge-divergence-freshness

**Scope(s):** THEORY-LEVEL · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Delta_t=Reality_t-Knowledge_t, Freshness(p,t) · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 192's concept that Reality and Knowledge can legitimately diverge (Delta_t!=0 as a normal condition) and that Freshness (time since last observation) is a distinct epistemic-quality dimension, not a truth indicator; extends to a six-dimensional K(p) status vector and a warning against over-modeling."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1395 §"Delta_t = Reality_t - Knowledge_t. ... Delta_t \neq 0 can legitimately occur."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1395 §"K(p)=(EpistemicStatus,TemporalValidity,ProvenanceQuality,EvidenceStrength,AuthorityStatus,Freshness)."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1395. Candidate lifecycle: DORMANT. Evidence: `retracted_by` and `superseded_by` are both empty, `contested_by_own_contradiction_type` is false. This is a heuristic based on how recently (by source_id) this label was last used — all four rows trace to a single source document (S1395), with no later row in this label's own family continuing or applying it — not a confirmed retirement.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1395 (×3) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1395 |
| examples | PRESENT | S1395 |
| warnings | PRESENT | S1395 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (family.rationale_evidence is empty for this label; rationale_truncated_count = 0, so no further rationale-bearing rows exist beyond what's captured). Note: the DEFINITION rows themselves carry rationale-like content (e.g. framing Δ_t≠0 as "a normal, expected condition, not a defect," and the immediate warning against over-modeling), but neither was mechanically classified into the rationale_evidence bucket, so neither is asserted as rationale here — see "All rows" below for their content as definitions/warnings instead.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE (family.assumption_register is empty for this label).

## All rows (source_id order)

- [S1395] types=[DEFINITION, EXAMPLE] scope=THEORY-LEVEL — "Defines Δ_t=Reality_t-Knowledge_t, which can legitimately be nonzero (reality changed; observation hasn't happened; evidence unprocessed; knowledge stale; governance lags reality) — framed as a normal, expected condition, not a defect." (anchor: "Delta_t = Reality_t - Knowledge_t. ...")
- [S1395] types=[DEFINITION, DISTINCTION] scope=OBJECT — "Defines Freshness(p,t) as elapsed time since last observation, explicitly not the same as Truth (an old proposition can remain true, a very recent one can be false) — Freshness is an epistemic quality dimension, not a truth indicator." (anchor: "Freshness(p,t)=t-t_lastObservation(p). ... Freshness≠Truth.")
- [S1395] types=[EXTENSION, FORMALIZATION] scope=OBJECT — "Proposes a six-dimensional knowledge status vector K(p)=(EpistemicStatus, TemporalValidity, ProvenanceQuality, EvidenceStrength, AuthorityStatus, Freshness), more expressive than a single 'status=VERIFIED' field." (anchor: "K(p)=(EpistemicStatus,TemporalValidity,ProvenanceQuality,EvidenceStrength,AuthorityStatus,Freshness).")
- [S1395] types=[CONSTRAINT, WARNING] scope=METHODOLOGICAL — "Warns against over-modeling: do not add 50 fields; only introduce a new dimension when it protects a real invariant or answers a real domain question — echoed later elsewhere in the corpus as 'model integrity' guidance." (anchor: "Only introduce a dimension when it protects a real invariant or answers a real domain question.")

## Notes for P3
- This is a single-source label (all 4 rows from S1395, one document, batch B0034) — thin in provenance but internally coherent: a definition, a distinction, a formal extension, and an immediate self-applied methodological caution against over-extending that same formalization.
- The label's own fourth row explicitly anticipates and warns against exactly the kind of dimension-proliferation this file's own template guards against ("only introduce a new dimension when it protects a real invariant or answers a real domain question") — the node_metadata source note calls this "the same discipline echoed later as 'model integrity' guidance elsewhere in the corpus," suggesting a possible link to a later-labeled "model integrity" object; no group_ids currently connect them, so this is flagged for P3's attention rather than asserted here.
