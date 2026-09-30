# state-sufficiency-candidate-definition-285

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Sufficient(K,𝒪,ℐ)` · **Aliases:** `candidate state-sufficiency definition`
**Candidate group membership (NOT an identity claim):**
- **G0441**: [`state-sufficiency-candidate-definition-285` · `sufficient-state-control-theory-lens`] — explicit agent-stated uncertainty: 'state-sufficiency-candidate-definition-285' POSSIBLY relates to 'sufficient-state-control-theory-lens' (batch B0050). Note: Candidate (explicitly not-yet-solved) definition of Sufficient(K,𝒪,ℐ): K is sufficient iff it contains enough information to (1) evaluate every mandatory invariant, (2) determine operation legality, (3) execute every canonical transformation, (4) distinguish required state identities, (5) support required equality decisions, (6) support deterministic replay where required. Paired with an information-theoretic reducibility test: an operation-legality decision L(o,K) must not silently depend on extra information X unless X is declared an explicit external dependency.

## Sources (how this label entered the ledger)
- **PROPOSAL** batch `B0050`, scope `OBJECT`: Candidate (explicitly not-yet-solved) definition of Sufficient(K,𝒪,ℐ): K is sufficient iff it contains enough information to (1) evaluate every mandatory invariant, (2) determine operation legality, (3) execute every canonical transformation, (4) distinguish required state identities, (5) support required equality decisions, (6) support deterministic replay where required. Paired with an information-theoretic reducibility test: an operation-legality decision L(o,K) must not silently depend on extra information X unless X is declared an explicit external dependency. (relation_to_existing: POSSIBLY:sufficient-state-control-theory-lens)

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2055 §"Sufficient(K,𝒪,ℐ) iff the state contains enough information to: 1. evaluate every mandatory invariant; 2. determine operation legality; 3. execute every canonical transformation; 4. distinguish required state identities; 5. support required equality decisions; 6. support deterministic replay, where replay is required. ... Step 285 should not pretend that it is already solved."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2055. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S2055 |
| informal_meaning | PRESENT | S2055 |
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
Proposes an information-theoretic reducibility test distinguishing state from external context: a legality decision that silently depends on information beyond K is either evidence that K is incomplete, or that the extra information X must be explicitly declared an external dependency rather than folded into K — a discipline against 'arbitrarily putting every useful object inside K'. [S2055]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2055] types=['DEFINITION'] scope=OBJECT — "Gives a six-part candidate necessary-condition definition of state sufficiency Sufficient(K,𝒪,ℐ), explicitly flagged as a candidate ('the exact formal predicate must be derived later') rather than a solved definition." (anchor: "Sufficient(K,𝒪,ℐ) iff the state contains enough information to: 1. evaluate every mandatory invariant; 2. determine operation legality; 3. execute every canonical transformation; 4. distinguish required state identities; 5. support required equality decisions; 6. support deterministic replay, where replay is required. ... Step 285 should not pretend that it is already solved.")
- [S2055] types=['ARGUMENT'] scope=OBJECT — "Proposes an information-theoretic reducibility test distinguishing state from external context: a legality decision that silently depends on information beyond K is either evidence that K is incomplete, or that the extra information X must be explicitly declared an external dependency rather than folded into K — a discipline against 'arbitrarily putting every useful object inside K'." (anchor: "if an operation legality decision is L(o,K), then there must exist no required information X such that L(o,K,X) cannot be reduced to L(o,K) unless X is explicitly defined as an external dependency.")

## Notes for P3
- This label participates in 1 candidate group(s) (G0441) — per R5/R12 this is not an identity claim; P3 should review whether any group member denotes the same underlying object as this label.
- Thin evidence base (n=2 row(s)) — treat conclusions here as provisional.
