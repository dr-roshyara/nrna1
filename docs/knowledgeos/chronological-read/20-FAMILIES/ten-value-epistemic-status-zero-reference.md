# ten-value-epistemic-status-zero-reference

**Scope(s):** `OBJECT` · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Satisfied,PartiallySatisfied,Unknown,Insufficient,Conflicted,Stale,Invalid,Prohibited,NotApplicable,Missing` · **Aliases:** `25D.4`, `25D.7`
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0042, scope OBJECT): 025d §25D.4 (extended by §25D.7) defines a ten-value epistemic-status set, and zero_reference.py implements and executes all ten, passing 8/8 falsification tests (reproduced this pass, exit 0), with in-code discrimination of 'Missing' (expected governed artifact absent) from 'Unknown' (no evidence either way). The claimed/ratified theory's Σ=(dir∈{Refuting,Neutral,Supporting}, str∈5-ordinal) cannot express 6 of the 10 (Missing, Stale, Prohibited, NotApplicable, Invalid, Insufficient).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1733] §"### 2.4 Epistemic status — the corpus has **ten** executable values; the claimed theory has a pair"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S1738`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

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
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1733, S1738 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S1733]` types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Divergence 4: 025d defines and zero_reference.py implements and executes a ten-value epistemic status set (passing 8/8 falsification tests), while the claimed theory reduces status to Σ=(dir,str) which cannot express Missing, Stale, Prohibited, NotApplicable, Invalid or Insufficient — with the caveat that Sat(K_t,r_i) grades a requirement against a state while Σ grades an assertion, so they are formally different functions even though the distinctions the ten-set draws are exactly the ones the claimed theory declares inexpressible." (anchor: "### 2.4 Epistemic status — the corpus has **ten** executable values; the claimed theory has a pair")
- `[S1738]` types=[EXPERIMENTAL-RESULT, CORRECTION] scope=THEORY-LEVEL — "Σ's claimed value set (dir∈{Refuting,Neutral,Supporting}, str∈5-ordinal) is REFUTED against the corpus's own 025d-defined, zero_reference.py-executed ten-value set (8/8 passing): six of the ten values (Missing, Stale, Prohibited, NotApplicable, Invalid, Insufficient) are inexpressible in (dir,str) — precisely stated: Sat(K_t,r_i) grades a requirement against a state while Σ grades an assertion, so the fix is not simply 'make Σ the ten-set', but the claim that 'the theory cannot express Missing' is false since executable, tested, passing code in this corpus already does." (anchor: "**`Σ = (dir, str)` is the right value set** | 🔴 **REFUTED**")

## Notes for P3
- No additional observations beyond what is captured above; nothing about this label's own rows struck this reviewer as unusual relative to its evidentiary base.
