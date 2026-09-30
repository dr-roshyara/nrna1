# step168-deterministic-verification-caveats

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Authorized_record(a,action,t) vs world truth`, `Formal correctness != Model correctness`, `V(x)=1 if P(x) else 0`, `V=<Predicate,Inputs,Assumptions,Method,Result>` · **Aliases:** `system-record truth vs world truth`, `verification must expose assumptions`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 168's caveats on deterministic verification: a deterministic verifier V(x) in {0,1} evaluated over supplied inputs proves P(x) only relative to those inputs and the formalization used, not that the formalization correctly represents reality (Formal correctness != Model correctness); worked example -- a database-backed Authorized(a,action,t) check that returns true only establishes Authorized_record(a,action,t)=true, which may diverge from world truth if the database is stale (system-record truth vs world truth). Every verification should therefore expose its assumptions via V=<Predicate,Inputs,Assumptions,Method,Result> so a result does not appear stronger than it is.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1361] §"That proves: P(x) relative to the supplied inputs and formalization. It does not automatically prove: the formalization correctly represents reality. Therefore: Formal correctness ≠ Model correctness. ... Authorized_record(a,action,t)=true. The distinction between system-record truth and world truth matters. ... V = <Predicate, Inputs, Assumptions, Method, Result>."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1361. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1361 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1361 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1361 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1361] types=[WARNING, DISTINCTION] scope=THEORY-LEVEL — "A deterministic verifier returning V(x)=1 proves P(x) only relative to the supplied inputs and formalization, not that the formalization correctly represents reality (Formal correctness != Model correctness); worked example -- an Authorized(a,action,t) check backed by a possibly-stale database only establishes Authorized_record(a,action,t)=true (system-record truth), which may diverge from world truth. Every verification should expose V=<Predicate,Inputs,Assumptions,Method,Result> so results do not appear stronger than warranted." (anchor: "That proves: P(x) relative to the supplied inputs and formalization. It does not automatically prove: the formalization correctly represents reality. Therefore: Formal correctness ≠ Model correctness. ... Authorized_record(a,action,t)=true. The distinction between system-record truth and world truth matters. ... V = <Predicate, Inputs, Assumptions, Method, Result>.")

## Notes for P3
Very thin evidentiary base (1-2 rows) — classification here is provisional and should be revisited if more contributions surface. Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.
