# contradiction-registry-17-entries-classified

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** 11-CONTRADICTION-REGISTRY · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0042, scope OBJECT: "An independent contradiction registry classifies 17 entries under the rule that 'the newer definition is better' is never itself a valid resolution: 6 true contradictions remain open (of C-01..C-08, two resolved), 4 are resolved by independent evidence (self-inconsistency, executed counterexample, or independent expressive failure -- never recency alone), 3 are terminology drifts, 2 are correctly-identified abstraction-level distinctions rather than contradictions, and 3 entries (SELF-1..SELF-3) are explicit self-corrections of the immediately preceding adversarial verification pass's own findings."

No single_candidate_flags recorded for this label.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1741 §"## Self-corrections — findings of the *previous* pass that this pass corrects"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1741. Candidate lifecycle: DORMANT.
Evidence: no retracted_by, no superseded_by, not contested_by_own_contradiction_type — all empty. DORMANT is a heuristic based on recency of source_id, not a confirmed retirement. (It is worth noting the object's own subject matter is itself about detecting and classifying contradictions — but the `contested_by_own_contradiction_type` flag being `false` here refers only to the mechanical check for *this label*, not to whether the registry's own content records contradictions, which it explicitly does.)

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1741 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE. `rationale_truncated_count` is 0.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- `[S1741]` types=[CORRECTION, RETRACTION] scope=METHODOLOGICAL, completeness=N/A — "Three explicit self-corrections of the immediately preceding adversarial pass: SELF-1, 'Σ has no access to ℛ' is incomplete — the corpus's own ℛ carried a Σ field, and the real defect is that reducing it to a triple deleted it (and the deleted field was itself ill-typed); SELF-2, 'Sufficient has no signature anywhere' is wrong — Q was typed at 42.40 and removed in ratification, not absent; SELF-3, 'the smallest missing concept is (W,Ω)' is overstated as a single answer — D_t and the ℛ 8-tuple are independent gaps, and the ℛ restoration is smaller and closes more." (anchor: "## Self-corrections — findings of the *previous* pass that this pass corrects")
- `[S1741]` types=[PRINCIPLE] scope=METHODOLOGICAL, completeness=N/A — "Methodological principle demonstrated across all 17 registry entries: every resolved contradiction was resolved on independent grounds (self-inconsistency for C-10, an executed counterexample for C-11/C-13, independent expressive failure for C-12) and never merely because a later file existed; C-02 and C-04 remain open contradictions by the corpus's own admission." (anchor: "> **Every \"resolution by recency\" was avoidable.**")

## Notes for P3
Only 2 of the registry's own 17 classified entries appear as rows here (both self-correction/methodology-level statements from the same source S1741, `docs/knowledgeos/brainstorming/verification/independent/11-CONTRADICTION-REGISTRY.md`); the individual C-01..C-08 and SELF-1..SELF-3 contradiction entries themselves are referenced only in summary form inside these two rows' statements, not captured as their own rows in this family. P3 should be aware this label's row_count (2) substantially understates the size of the underlying document (a 17-entry registry) — this file's title and node_metadata note describe the full registry, but the two captured rows are its methodological framing statements, not the entry-by-entry content. No group_ids connect this label to anything else.
