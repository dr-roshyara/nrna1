# canonical-ubiquitous-language-glossary

**Scope(s):** THEORY-LEVEL · **Row count:** 7 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** Artifact J
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0040, scope THEORY-LEVEL: The 20260830_1918 mandate's ~30-term canonical glossary, written last, assigning every term a DDD role, owning bounded context, mathematical object, forbidden synonyms and FROZEN/PROPOSED/OPEN/REFUTED status; confirms ten mandated term-pair separations are satisfied and tracks which terms changed status this phase.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1648 §"Terms are FROZEN only where the definition is derived or corpus-established and nothing in this phase contests it. Written after the mathematics stabilised, per §13."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1648 §"Knowledge State ... Assertion ... Proposition ... Entity ... Dimension ... Value ... Observation ... Evidence ... Context ... Governance Status ... Authority ... Governance ... Validation ... Determination ... Decision ... Transformation ... Event ... Provenance ... Lineage ... History ... Identity"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1648 §"Assurance | REMOVED from the formal vocabulary — split four ways ... JustificationStrength · Traceability · Risk | NEW — the split's products ... Entity · Dimension · Value | NEW as first-class terms — previously hidden inside an opaque P ... Conflict | SEPARATED from Contradiction ... ℛ_rel (relate"]

## Lifecycle
last_seen: S1660. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1648 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1648, S1660 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1648 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S1648 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows
- [S1648] types=['PRINCIPLE'] scope=METHODOLOGICAL — "Governing rule for the glossary itself: a term is marked FROZEN only if its definition is derived or corpus-established AND uncontested by anything found in this verification phase; the glossary is deliberately written last, after the mathematics stabilized, rather than upfront." (anchor: "Terms are FROZEN only where the definition is derived or corpus-established and nothing in this phase contests it. Written after the mathematics stabilised, per §13.")
- [S1648] types=['FORMALIZATION', 'DEFINITION'] scope=THEORY-LEVEL — "The full canonical glossary assigns, for the first time in one place, a DDD building-block role and an owning bounded context to every term: e.g. Knowledge State is an Aggregate in bounded context Knowledge; Assertion and Entity/Dimension/Value are Value Objects/Entities in Knowledge; Observation/Evidence are Value Objects in bounded context Evidence; Governance Status/Authority/Policy/Governance are Policy/attribute/Domain-Service in bounded context Governance; Determination is an Aggregate in bounded context Adjudication; Decision and Event are Domain Events; Validation, Assessment, Transformation, Governance are Domain Services; Provenance/Lineage/Identity/Equality/Contradiction/Conflict/Supersession are attributes or derived relations in Knowledge; each entry also carries a canonical meaning, its mathematical object, synonyms, and an explicit forbidden-synonym list (e.g. Knowledge State forbidden-synonym'd against both Knowledge and History)." (anchor: "Knowledge State ... Assertion ... Proposition ... Entity ... Dimension ... Value ... Observation ... Evidence ... Context ... Governance Status ... Authority ... Governance ... Validation ... Determination ... Decision ... Transformation ... Event ... Provenance ... Lineage ... History ... Identity")
- [S1648] types=['DEFINITION'] scope=OBJECT — "Two governance lifecycle terms not previously formalized in this batch are defined and FROZEN: Deprecation (a lifecycle marker meaning 'no longer recommended', distinct from Refuted and Superseded, per step 218.22) and Invalidation (a governance act meaning 'declared no longer admissible', distinct from Refutation and Withdrawal)." (anchor: "Deprecation | no longer recommended | lifecycle marker | attribute | Governance | "retired" | ≠ Refuted · ≠ Superseded | 218.22 · FROZEN ... Invalidation | declared no longer admissible | governance act | operation | Governance | "revocation" | ≠ Refutation · ≠ Withdrawal | derived · FROZEN")
- [S1648] types=['OPEN-QUESTION', 'RESTATEMENT'] scope=OBJECT — "Uncertainty and Missingness are both explicitly recorded as NOT DEFINED in the canonical glossary: Uncertainty remains open per gap G-3 and must not be confused with Epistemic Status, and Missingness is noted to have gone dead after corpus step 184." (anchor: "Uncertainty | — | NOT DEFINED | — | — | "confidence" | ≠ Epistemic Status | OPEN — G-3 ... Missingness | — | NOT DEFINED | — | — | — | — | dead after step 184 · OPEN")
- [S1648] types=['VALIDATION'] scope=THEORY-LEVEL — "All ten mandated term-separations are confirmed satisfied in the canonical vocabulary (Knowledge/KnowledgeState, Evidence/Observation, Claim/Proposition, Validation/Assessment, Provenance/Lineage, Identity/Equality, Policy/Authority, Decision/Action, State/History, EpistemicStatus/GovernanceStatus), with the last one specifically noted to be enforced by a running linter rather than merely stated." (anchor: "The ten mandated enforcements — all satisfied: Knowledge ≠ KnowledgeState ✓ · Evidence ≠ Observation ✓ · Claim ≠ Proposition ✓ · Validation ≠ Assessment ✓ · Provenance ≠ Lineage ✓ · Identity ≠ Equality ✓ · Policy ≠ Authority ✓ · Decision ≠ Action ✓ · State ≠ History ✓ · Epistemic Status ≠ Governance")
- [S1648] types=['GOVERNANCE', 'RESTATEMENT'] scope=THEORY-LEVEL — "Terms whose status changed in this verification phase: Assurance REMOVED (split four ways); JustificationStrength/Traceability/Risk added as NEW terms (the split's products); Entity/Dimension/Value promoted to first-class NEW terms (previously hidden inside opaque P); Conflict SEPARATED from Contradiction as a distinct concept; R_rel (related_to) added as a NEW symmetric relation family learned from the implementation; and Epistemic Status DEMOTED from FROZEN back to PROPOSED because its three-state model was refuted." (anchor: "Assurance | REMOVED from the formal vocabulary — split four ways ... JustificationStrength · Traceability · Risk | NEW — the split's products ... Entity · Dimension · Value | NEW as first-class terms — previously hidden inside an opaque P ... Conflict | SEPARATED from Contradiction ... ℛ_rel (relate")
- [S1660] types=['LIMITATION'] scope=THEORY-LEVEL — "Final semantic-closure verdict: NO — three terms remain genuinely double-bound across the corpus (Provenance denoting three distinct objects, Authority conflating competence with trust-rank, and E/V denoting Entity/Events and Value/Vertices respectively); explicitly recorded as unreconciled rather than resolved." (anchor: "NO — three terms still carry two meanings: Provenance (three objects), Authority (competence vs trust-rank), E/V (Entity/Events, Value/Vertices). Recorded, not reconciled.")

## Notes for P3
None beyond what is recorded above.
