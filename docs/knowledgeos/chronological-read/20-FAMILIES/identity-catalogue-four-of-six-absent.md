# identity-catalogue-four-of-six-absent

**Scope(s):** THEORY-LEVEL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `assertion/evidence/knowledge-state/transformation/policy/version identity` · **Aliases:** `IE-1`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0041, scope THEORY-LEVEL): Of the mandate's six required identity criteria, only assertion identity (partially, via id) and version identity (IMPLEMENTED only in the EKP, not in the theory) exist; evidence identity, knowledge-state identity, transformation identity, and policy identity are all absent from the theory, and Step 266's own stated dependency (deterministic replay requires operation versioning) is therefore unsatisfied since no transformation identity exists.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1710] §"Four of six required identities do not exist. Step 266 §266.14 states the dependency -- deterministic replay requires operation versioning -- and no transformation identity is ever defined, so replay CANNOT BE GUARANTEED DETERMINISTIC across any change to the operation set. This directly undercuts TG-4: the reference kernel replays deterministically only because its four operations are frozen in one file."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1714. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows) — this DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S1710, S1710 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S1710, S1714 |
| Dependencies | PRESENT | S1710, S1714 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1714 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Finding IE-1: catalogues all six mandate-required identities and finds evidence identity, knowledge-state identity, transformation identity, and policy identity absent from the theory (only assertion identity is partially established via the id field, and version identity exists only in the EKP implementation, not the theory); since Step 266 §266.14 itself states deterministic replay requires operation versioning, and no transformation/operation identity exists, replay cannot be guaranteed deterministic across any change to the operation set, directly undercutting the executable kernel's TG-4 achievement (09-TRANSFORMATION-GAP.md), which replays deterministically only because its four operations happen to be frozen in one file. [S1710] Finding IE-5: attempting the mandate's five required identity counterexamples finds three succeed (same-content-different-provenance distinguished by explain; same-state-different-history indistinguishable in K but distinguished by a history predicate; same-assertions-different-relations distinguished by withdraw), while two (same-content-different-policy; same-id-different-version) are UNTESTABLE because Policy identity and version identity simply do not exist in the theory, and this untestability is itself the finding -- the absence is a gap in the theory, not a limitation of the test methodology. [S1710]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1710] types=[ARGUMENT, CORRECTION] scope=THEORY-LEVEL — "Finding IE-1: catalogues all six mandate-required identities and finds evidence identity, knowledge-state identity, transformation identity, and policy identity absent from the theory (only assertion identity is partially established via the id field, and version identity exists only in the EKP implementation, not the theory); since Step 266 §266.14 itself states deterministic replay requires operation versioning, and no transformation/operation identity exists, replay cannot be guaranteed deterministic across any change to the operation set, directly undercutting the executable kernel's TG-4 achievement (09-TRANSFORMATION-GAP.md), which replays deterministically only because its four operations happen to be frozen in one file." (anchor: "Four of six required identities do not exist. Step 266 §266.14 states the dependency -- deterministic replay requires operation versioning -- and no transformation identity is ever defined, so replay CANNOT BE GUARANTEED DETERMINISTIC across any change to the operation set. This directly undercuts TG-4: the reference kernel replays deterministically only because its four operations are frozen in one file.")
- [S1710] types=[ARGUMENT, LIMITATION] scope=OBJECT — "Finding IE-5: attempting the mandate's five required identity counterexamples finds three succeed (same-content-different-provenance distinguished by explain; same-state-different-history indistinguishable in K but distinguished by a history predicate; same-assertions-different-relations distinguished by withdraw), while two (same-content-different-policy; same-id-different-version) are UNTESTABLE because Policy identity and version identity simply do not exist in the theory, and this untestability is itself the finding -- the absence is a gap in the theory, not a limitation of the test methodology." (anchor: "Two of the mandate's five required counterexamples CANNOT BE CONSTRUCTED, because the objects they quantify over (Policy identity, version identity) do not exist in the theory. That is itself the finding: the absence is not a gap in the test, it is a gap in the theory.")
- [S1714] types=[PRINCIPLE, LIMITATION] scope=METHODOLOGICAL — "Finding FR-1: states a methodological principle that when a required falsification test cannot even be constructed (here: 'two states have different policies' and 'same id, different version', both untestable because Policy identity and version identity do not exist in the theory), the theory is not thereby confirmed in that region -- it is unfalsifiable there, which is a worse epistemic status than confirmation, since two of the mandate's five required identity counterexamples fall into this category." (anchor: "When a required falsification test cannot be CONSTRUCTED, the theory is not thereby confirmed -- it is UNFALSIFIABLE IN THAT REGION, which is worse. Two of the mandate's five identity counterexamples fall here.")

## Notes for P3
Lifecycle (DORMANT) rests on recency heuristics only — no explicit retraction, supersession, or self-contradiction signal was found in this label's own rows. No internal tension noticed across this label's own rows for this batch.
