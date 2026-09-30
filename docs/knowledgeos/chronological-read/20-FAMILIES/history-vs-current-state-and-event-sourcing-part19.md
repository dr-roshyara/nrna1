# history-vs-current-state-and-event-sourcing-part19

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "DELETE != RETRACT", "K_t = Replay(E_1..E_t)" · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope OBJECT: "History/current-state distinction, append-only logging limits, event sourcing as a candidate (not mandated) strategy including rule-version-dependent replay divergence, and the Retraction != Deletion != SoftDelete distinctions."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2782 §"Persistence should therefore not simply overwrite status=established with status=retracted ... DELETE != RETRACT ... SoftDelete != Retraction"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2782. Candidate lifecycle: ACTIVE. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S2782), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2782 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S2782 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2782] types=[DISTINCTION, WARNING] scope=OBJECT — "19.17-19.23: Current(K_t) must be distinguished from History(K_t) -- overwriting status fields destroys required reconstruction, so K_t->K_{t+1} must remain reconstructible; append-only history H_{t+1}=H_t∪{e_{t+1}} supports this but AppendOnly≠AutomaticallyCorrect (a log can still hold incorrect/duplicate/misordered/invalid events, so history preservation does not replace semantic validation); event sourcing K_t=Replay(E_1..E_t) is a candidate implementation strategy, not a theorem, and introduces schema-stability/versioning/deterministic-replay/ordering/migration/idempotency requirements; replaying event E_7 under a changed RuleVersion (3 vs 4) can legitimately produce K'_t≠K_t, so reproducible historical replay needs Replay(H_t,V_rules,V_models,V_contracts,V_schemas) not just Replay(H_t); a system must represent both Determined(p,t1) and Retracted(p,t2) without contradiction since each is a per-time status statement; DELETE≠RETRACT (deletion destroys the representation, retraction changes epistemic standing while preserving historical existence), and a common 'deleted_at' SoftDelete implementation is not automatically a Retraction model." (anchor: "Persistence should therefore not simply overwrite status=established with status=retracted ... DELETE != RETRACT ... SoftDelete != Retraction")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
- This label rests on a single captured row — the evidentiary base is thin by construction; P3 should treat any characterization here as provisional pending further corpus evidence, not as a settled account.
