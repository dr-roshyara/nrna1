# temporal-precision-invariant

**Scope(s):** THEORY-LEVEL · **Row count:** 6 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** I_41, I_42, I_43, I_44 · **Aliases:** representation precision must not exceed epistemic precision
**Candidate group membership (NOT an identity claim):**
- G0966: labels `semantic-precision-invariant` and `temporal-precision-invariant` — working_label token overlap Jaccard=0.50 (shared tokens 'invariant', 'precision'); a mechanical lexical-similarity signal, not a confirmed relationship.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0034 · scope THEORY-LEVEL — "Step 193's four temporal invariants: I_41 (a temporal claim must preserve its time coordinate's semantic meaning), I_42 (correction creates a new transition, never erases the original), I_43 (no causal inference from timestamp order alone), I_44 (the system must distinguish exact, bounded, and uncertain temporal knowledge); includes the false-precision warning about storing a point timestamp for an interval-known time."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1396 §"I_41: A temporal claim must preserve the semantic meaning of its time coordinate."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1396. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded. Since no retraction/supersession/contradiction evidence is present, this lifecycle label is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1396 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1396 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1396 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1396] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_41: a temporal claim must preserve the semantic meaning of its time coordinate -- e.g. an observation timestamp must never be silently reinterpreted as the time the observed fact became true." (anchor: "I_41: A temporal claim must preserve the semantic meaning of its time coordinate.")
- [S1396] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_42: historical correction must create a new transition rather than erase the original -- essential for auditability." (anchor: "I_42: Historical correction must create a new transition, not erase the original transition.")
- [S1396] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_43: causal reconstruction must not rely solely on timestamp ordering; causal relationships must be explicitly represented or justified where they matter." (anchor: "I_43: Causal reconstruction must not be inferred solely from timestamp ordering.")
- [S1396] types=[EXTENSION] scope=OBJECT — "Extends the distributional model to time itself: an observation time can be uncertain (T_v ~ F_T, or only known to lie in an interval [t1,t2]), letting temporal uncertainty become part of the epistemic model, e.g. P(T_v<08:30|E)." (anchor: "P(T_v<08:30\mid E). ... in forensic or distributed-system reconstruction it can become very practical.")
- [S1396] types=[PRINCIPLE, WARNING] scope=THEORY-LEVEL — "If only T_v in [08:00,08:15] is known, storing a single point value like T_v=08:07 merely because the database requires one timestamp is false precision; representation precision must never exceed epistemic precision." (anchor: "Representation precision must not exceed epistemic precision.")
- [S1396] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_44: the system must distinguish exact, bounded, and uncertain temporal knowledge, expected to later influence the temporal data model." (anchor: "I_44: The system must distinguish exact, bounded, and uncertain temporal knowledge.")

## Notes for P3
(Own observation.) Single-source label (all 6 rows from S1396); no rationale_evidence rows were mechanically captured for it even though the "false precision" row (I_44's companion) carries an implicit justification (a database forcing a single timestamp when only an interval is epistemically known) — P3 may want to treat that row as informal rationale even though the mechanical extractor did not classify it as ARGUMENT/EXPLANATION/ANALYSIS/ALTERNATIVE. G0966's link to `semantic-precision-invariant` is a bare token-overlap signal (shared words "invariant"/"precision") with no stated-uncertainty note behind it in this label's own data, so it is weaker evidence than the explicit-uncertainty groups seen on other labels in this batch.
