# proposition-type-EDV-vs-SROG-contest

**Scope(s):** OBJECT · **Row count:** 3 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `AttributeProposition`, `P=(E,D,V)`, `P=(S,ρ,O,Γ)` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0762** [`proposition-assertion-knowledge-hierarchy` · `proposition-type-EDV-vs-SROG-contest` · `s1605-question-7-assertion-layer-rediscovery` · `unknown-value-bottom-element-gap`] — labels share the notation 'P=(E,D,V)'

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0042, scope OBJECT): Q14 (20260826-182409) supplies the properly-typed P=(E,D,V) and then, in its own §3 titled 'the biggest mathematical problem', refutes it as too restrictive to represent relational propositions ('Assertion A contradicts Assertion B'), recommending P=(S,ρ,O,Γ) with AttributeProposition⊂Proposition as 'a very important correction'. The correction is never adopted (AttributeProposition occurs in exactly one file); Step 262.15 reopens the question and records it as an open sub-test with two undecided models (C-02).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1730] §"=> Of 10 propositions the corpus itself requires, 2 are cleanly expressible.
     THREE are inexpressible"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1737. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type=True.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S1733 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| Examples | PRESENT | S1737 |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | PRESENT | S1730 |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Divergence 1: Q14 (20260826-182409) supplies P=(E,D,V) and then, in its own §3 titled 'The biggest mathematical problem: Proposition = Entity + Dimension + Value', calls it too restrictive and recommends P=(S,ρ,O,Γ) as 'a very important correction' — never adopted (AttributeProposition occurs in exactly one file); Step 262 reopens it and records it as an open sub-test [S1733].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1730] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "EXP-19: tested against 10 propositions the corpus itself states it needs to express, P=(Entity,Dimension,Value) cleanly expresses only 2; 3 are inexpressible (negation — no negative values in V_D; conditional — 'IF v>=3.60 THEN patch-current', the founding conditional-determination problem; universal quantification — 'every committee has >=3 members'); 2 are lossy (a validity interval collapses to one timestamp t; a unit is dropped); 1 ('A1 contradicts A2') is not a proposition at all in this type system and must be expressed as an R-edge instead, giving the theory two uncoordinated mechanisms for propositional content; 1 ('Nexus is ready to migrate') is expressible but empty, holding the conclusion with no way to express the rule that produced it; 1 ('Nexus.version is UNKNOWN') is ambiguous between a value-space category error and true absence." (anchor: "=> Of 10 propositions the corpus itself requires, 2 are cleanly expressible.      THREE are inexpressible")
- [S1733] types=[CONTRADICTION, ARGUMENT] scope=THEORY-LEVEL — "Divergence 1: Q14 (20260826-182409) supplies P=(E,D,V) and then, in its own §3 titled 'The biggest mathematical problem: Proposition = Entity + Dimension + Value', calls it too restrictive and recommends P=(S,ρ,O,Γ) as 'a very important correction' — never adopted (AttributeProposition occurs in exactly one file); Step 262 reopens it and records it as an open sub-test." (anchor: "### 2.1 `P = (E,D,V)` — **the corpus refutes it in the same file that supplies it**")
- [S1737] types=[COUNTEREXAMPLE] scope=THEORY-LEVEL — "The sharpest of nine mandated counterexamples: E_q is indexed by the claim q it is evidence for (Step 230.15), but the theory's amended 9-field evidence record drops this index, making evidence free-floating; this loses both the ability to say what an item is evidence for, and the domain the independence relation (needed for corroboration) would need to live on." (anchor: "| 6 | Can evidence exist without an assertion? | 🟡 **type-yes, semantically-no** |")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
