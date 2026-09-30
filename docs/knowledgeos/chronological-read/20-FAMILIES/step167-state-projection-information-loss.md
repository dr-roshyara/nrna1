# step167-state-projection-information-loss

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** I(S;H) < H(H) generally, S_t = pi(H_t), State compression can destroy epistemic information, pi(H1)=pi(H2) while H1!=H2 => S cannot distinguish histories · **Aliases:** information-theoretic lineage justification
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 167's information-theoretic strengthening of the recurring state-vs-history theme: models current state as a projection of history S_t=pi(H_t) where H_t=(S_0,e_1,S_1,...,e_t,S_t); in general S_t is not equivalent to H_t (current state is a projection of history, not the complete history), and since a projection generally satisfies I(S;H) < H(H) (loses information unless injective), if pi(H1)=pi(H2) while H1!=H2 then current state cannot distinguish those two materially different histories, so state compression can destroy epistemic information (e.g. status=APPROVED alone cannot reveal who approved it, under what authority, using which knowledge/evidence/determination/policy). Concludes this mathematically validates treating lineage as a first-class architectural concern rather than merely an audit preference, without requiring full event sourcing as an implementation consequence -- the conceptual distinction (History->Projection) holds regardless of implementation.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1360 §"S_t = π(H_t). ... I(S;H) < H(H) in the intuitive information-theoretic sense. The projection loses information unless it is injective. If π(H1)=π(H2) while H1≠H2, then current state cannot distinguish the two histories. This is a very important mathematical observation. ... any requirement to distinguish those histories requires additional retained information. ... This validates lineage as a first-class concern ... follows mathematically from information loss under state projection."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1360 §"S_t = π(H_t). ... I(S;H) < H(H) in the intuitive information-theoretic sense. The projection loses information unless it is injective. If π(H1)=π(H2) while H1≠H2, then current state cannot distinguish the two histories. This is a very important mathematical observation. ... any requirement to distinguish those histories requires additional retained information. ... This validates lineage as a first-class concern ... follows mathematically from information loss under state projection."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1360. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by/superseded_by empty, contested flag false). The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the ledger, not a confirmed retirement and not a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1360 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1360 |
| type_signature | PRESENT | S1360 |
| invariants | PRESENT | S1360 |
| dependencies | PRESENT | S1360 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The evidence base attributes the following rationale to this object (as recorded, cited to its source):

- **[FORMALIZATION/ARGUMENT]** [S1360]: Formalizes current state as a projection of history S_t=pi(H_t) where H_t=(S_0,e_1,...,e_t,S_t), and generally S_t not-equivalent-to H_t; gives the information-theoretic argument I(S;H) < H(H) (a non-injective projection loses information), so if pi(H1)=pi(H2) while H1!=H2, current state alone cannot distinguish two materially different histories -- 'state compression can destroy epistemic information' (e.g. status=APPROVED alone cannot reveal who approved, under what authority, or which knowledge/evidence/determination/policy). Concludes this mathematically validates treating lineage as a first-class architectural concern (not merely an audit preference), following necessarily from information loss under state projection.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1360] types=[FORMALIZATION, ARGUMENT] scope=THEORY-LEVEL — "Formalizes current state as a projection of history S_t=pi(H_t) where H_t=(S_0,e_1,...,e_t,S_t), and generally S_t not-equivalent-to H_t; gives the information-theoretic argument I(S;H) < H(H) (a non-injective projection loses information), so if pi(H1)=pi(H2) while H1!=H2, current state alone cannot distinguish two materially different histories -- 'state compression can destroy epistemic information' (e.g. status=APPROVED alone cannot reveal who approved, under what authority, or which knowledge/evidence/determination/policy). Concludes this mathematically validates treating lineage as a first-class architectural concern (not merely an audit preference), following necessarily from information loss under state projection." (anchor: "S_t = π(H_t). ... I(S;H) < H(H) in the intuitive information-theoretic sense. The projection loses information unless it is injective. If π(H1)=π(H2) while H1≠H2, then current state cannot distinguish the two histories. This is a very important mathematical observation. ... any requirement to distinguish those histories requires additional retained information. ... This validates lineage as a first-class concern ... follows mathematically from information loss under state projection.")

## Notes for P3
(Own observation) Nothing unusual noticed while drafting this file beyond what is already recorded above.
