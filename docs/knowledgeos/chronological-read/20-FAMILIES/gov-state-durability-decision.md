# gov-state-durability-decision

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** B-prime relocation, D1/D2, KOS-AIP-GOV-STATE-DURABILITY-ADR · **Aliases:** GOV-STATE-DURABILITY chain
**Candidate group membership (NOT an identity claim):**
- G0732: links `gov-state-durability-decision` with `gov-state-durability-adr`, `kos-aip-gov-state-durability-program` — labels share the notation 'KOS-AIP-GOV-STATE-DURABILITY-ADR'
- G0936: links `gov-state-durability-decision` with `gov-state-durability-adr` — working_label token overlap Jaccard=0.60 (shared tokens: ['durability', 'gov', 'state'])

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0006, scope OBJECT): The proposed governance-evidence-durability relocation (B' -- move authority records out of the gitignored runtime/ directory) and its ADR/IMPLEMENTATION-DESIGN/MIGRATION-PLAN chain; its D1/D2 decision state is recorded as CONTRADICTORY between the runtime grant and the tracked registration review.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0234] §"Two records disagree on whether the durability decision was made"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0234] §"The GOV-STATE-DURABILITY migration (B' relocation) is NOT EXECUTED, NOT AUTHORIZED TO EXECUTE, MUST NOT BEGIN"

## Lifecycle
last_seen: S0234. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type: True

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S0234 |
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
- [S0234] (ANALYSIS) The runtime directory -- the only gitignored .claude/ subdirectory -- holds the estate's most authoritative records (grants, transitions), while its own name says 'runtime.' There is no runtime state in the runtime directory: governance evidence is misfiled into an ephemeral, ignored location. Load-bearing DDD statement from the PO/ARB: 'An aggregate cannot depend on accidental reconstruction.' Documented consequence (commit de998173, 2026-08-19): the narrative lineage (16 review documents) was committed while all grants and transitions remained outside git.
- [S0234] (ANALYSIS) Where the record lives: P-1 default workflow-state.php:81, P-2 default session-resolve.php:74 (two independent defaults, RA-2), P-3a/b the two --dir overrides. Which mechanism interprets it: P-4 session-resolve.php:90 getenv('KOS_MECHANISM_PATH') -> :103 proc_open -> :156, a write-capable substitution path. The mechanism already supports relocation; only the default contradicts the B' relocation.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S0234]` types=[CONTRADICTION] scope=OBJECT — "The runtime authority record (grant G-KOS-GOV-STATE-DURABILITY-DECISION) quotes a PO/ARB DECISION ACT 2026-08-19 selecting D1=B' (Governance Evidence Relocation) and D2=ADOPT (R-CONFLICT). The tracked registration review (...po-arb-position-registration.md), at its last recorded act, shows D1 NOT SELECTED, D2 NOT SELECTED, DECISION.md NOT PRODUCED. Both sides are recorded; the act sequence that moved D1/D2 from NOT SELECTED to SELECTED is UNKNOWN in the current corpus (U-3). The runtime record is the authoritative workflow state per state=fold, but the registration review is not amended to match." (anchor: "Two records disagree on whether the durability decision was made")
- `[S0234]` types=[ANALYSIS] scope=THEORY-LEVEL — "The runtime directory -- the only gitignored .claude/ subdirectory -- holds the estate's most authoritative records (grants, transitions), while its own name says 'runtime.' There is no runtime state in the runtime directory: governance evidence is misfiled into an ephemeral, ignored location. Load-bearing DDD statement from the PO/ARB: 'An aggregate cannot depend on accidental reconstruction.' Documented consequence (commit de998173, 2026-08-19): the narrative lineage (16 review documents) was committed while all grants and transitions remained outside git." (anchor: "the durability inversion (headline structural finding)")
- `[S0234]` types=[GOVERNANCE/CONSTRAINT] scope=OBJECT — "IMPLEMENTATION-DESIGN is PROPOSED -- DESIGN ONLY; MIGRATION-PLAN is PROPOSED, AMENDED (AMD3/4/5/6); AMD3/4/5/6 unregistered as delivered (OPEN-M6); OPEN-M1..M7 open; Phase 5 remains prohibited." (anchor: "The GOV-STATE-DURABILITY migration (B' relocation) is NOT EXECUTED, NOT AUTHORIZED TO EXECUTE, MUST NOT BEGIN")
- `[S0234]` types=[ANALYSIS] scope=OBJECT — "Where the record lives: P-1 default workflow-state.php:81, P-2 default session-resolve.php:74 (two independent defaults, RA-2), P-3a/b the two --dir overrides. Which mechanism interprets it: P-4 session-resolve.php:90 getenv('KOS_MECHANISM_PATH') -> :103 proc_open -> :156, a write-capable substitution path. The mechanism already supports relocation; only the default contradicts the B' relocation." (anchor: "Path sources are THREE, on TWO axes")

## Notes for P3
- No unusual internal tension observed across this label's 4 captured row(s); evidentiary base is proportionate to row count.
