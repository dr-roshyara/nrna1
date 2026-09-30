# k4-origin-audit-stipulated

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Status(K_4) = STIPULATED · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G0635: shares an explicit agent-stated uncertainty with `kmin-stipulated-not-derived` (batch B0067) — the normalization note records this as possibly the same underlying finding (K4's four capabilities are a Method-paragraph stipulation, not derived) as `kmin-stipulated-not-derived` (S2790), expressed under a different basis name (K_min = K_4). Relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- PROPOSAL, batch B0067, scope OBJECT: "Formal origin audit confirming K4's four capabilities are a Method-paragraph stipulation, not derived; the same underlying finding as kmin-stipulated-not-derived (S2790) under a different basis name (K_min = K_4)." (relation_to_existing: POSSIBLY:kmin-stipulated-not-derived)

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2797 §"A reference kernel must be able to (1) hold a knowledge state ... Status(K_4) = STIPULATED."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2797. Candidate lifecycle: ACTIVE. Evidence: no retraction, no superseding row, no self-contradiction flag; ACTIVE here is a heuristic based on recency of the single captured sighting (S2797), not a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2797 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
This label documents a falsification-style origin audit: it traces K4's four defining capabilities back to an uncited "must be able to" statement in a Method paragraph, finds no derivation from Theory v1.2 and no independent necessity/sufficiency justification anywhere in the corpus for those four capabilities themselves, and reads the "must be able to" language as scoping an implementation rather than asserting a semantic necessity — concluding Status(K4) = STIPULATED. The row also credits the audited source document as "internally honest," since that document itself never claimed the four base capabilities (only their transitive dependency closure) were derived [S2797].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S2797] types=[ANALYSIS] scope=OBJECT — "Verifies K4's four capabilities originate from an uncited 'must be able to' Method-paragraph stipulation with no derivation from Theory v1.2, no independent necessity or sufficiency justification found anywhere, and 'must be able to' read as defining implementation scope rather than asserting semantic necessity — concluding Status(K4)=STIPULATED, while noting the source document is internally honest since it never claims the four themselves (only their closure) are derived." (anchor: "A reference kernel must be able to (1) hold a knowledge state ... Take the transitive dependency closure of those four capabilities ... Is K_4 derived? No. Declared? Not as a governance act. Stipulated as a methodological starting point? YES. ... Status(K_4) = STIPULATED.")

## Notes for P3
Single-row label (S2797), from `docs/knowledgeos/reviews/2026-09-06-KOS-CAPABILITY-BASIS-LEVEL-A-FALSIFICATION.md`. The label's own PROPOSAL source note already flags a candidate identity with `kmin-stipulated-not-derived` (K_min = K_4) — this is the clearest signal in this entire sub-batch that two labels may denote the same finding under different names, and per the task rules I have not merged them; P3 should treat G0635 as a priority reconciliation candidate. No purpose/rationale gap here — the row is itself a rationale-bearing finding (an ANALYSIS row), but there is no captured informal meaning, formal definition, or type signature for "K_4" within this label's own rows.
