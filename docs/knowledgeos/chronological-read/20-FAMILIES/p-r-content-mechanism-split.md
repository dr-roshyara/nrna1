# p-r-content-mechanism-split

**Scope(s):** OBJECT · **Row count:** 3 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `P (entity-dimension-value)`, `R subset A x A x RelType` · **Aliases:** `CS-3`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

**Single-candidate flags (UNKNOWN-CANDIDATE-GROUP-style; NOT an identity claim):**
- [S1704] (batch B0041): a specific documented duplicate-title finding (025c vs 025n) that may already be captured under a general corpus-inventory duplication finding INV-2 in an earlier batch, not confirmed indexed


## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0041, scope OBJECT): The theory carries propositional content via two unreconciled mechanisms: P for entity-dimension-value facts and R for inter-assertion facts (e.g. contradicts), with R-edges being bare triples carrying no id, evidence, provenance, or status -- yet R_der is shown elsewhere (exp_congruence.py EXP-2) to be exactly the load-bearing component that makes the state sufficient.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1701] §"the theory has two carriers of content -- P for entity-dimension-value facts, R for inter-assertion facts -- and never states how they relate. ... R edges carry no id, no e, no sigma, no Pi. ... the theory's load-bearing component is precisely the one with no provenance, no evidence and no status."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1704. Candidate lifecycle: CONTESTED. Evidence: contested_by_own_contradiction_type: true.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S1701, S1703, S1704 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | PRESENT | S1701, S1703, S1704 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Finding CS-3: 'a1 contradicts a2' is propositional content that is not a Proposition in this type system but an R-edge instead, leaving the theory with two unreconciled content carriers (P for entity-dimension-value facts, R for inter-assertion facts); since R subset A x A x RelType gives edges no id/evidence/status/provenance, and exp_congruence.py's EXP-2 independently showed R_der is the load-bearing component making the state sufficient, the theory's most important component is exactly the one that cannot itself be asserted, evidenced, statused, or contested [S1701]. Finding SG-9: classifying all eleven corpus status words by kind (epistemic/governance/lifecycle/procedural/relational/derived) shows Conflicted, Contested, and Superseded are relational facts about pairs of assertions rather than properties of a single assertion; under the terminal model these become R-edges, which (per 05-ASSERTION-SEMANTICS.md CS-3) carry no status, provenance, or time of their own, so the theory cannot express when a supersession was recorded or who authorized it [S1703]. Finding EG-3: in the terminal Assertion type, e is merely a set of evidence identifiers, so nothing in K=(A,R) holds an actual evidence object, meaning evidence identity (Step 025i / 20260827-140919's own open question), provenance (Pi is the assertion's, not the evidence's), validity, and independence are all unanswerable from K alone; the corpus's five-step evidence algebra (025c, 025c-1, 025c-2, 025c-3, 025n) therefore has no surviving inputs in the terminal model to operate on [S1704].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1701] types=[ARGUMENT, CONTRADICTION] scope=OBJECT — "Finding CS-3: 'a1 contradicts a2' is propositional content that is not a Proposition in this type system but an R-edge instead, leaving the theory with two unreconciled content carriers (P for entity-dimension-value facts, R for inter-assertion facts); since R subset A x A x RelType gives edges no id/evidence/status/provenance, and exp_congruence.py's EXP-2 independently showed R_der is the load-bearing component making the state sufficient, the theory's most important component is exactly the one that cannot itself be asserted, evidenced, statused, or contested." (anchor: "the theory has two carriers of content -- P for entity-dimension-value facts, R for inter-assertion facts -- and never states how they relate. ... R edges carry no id, no e, no sigma, no Pi. ... the theory's load-bearing component is precisely the one with no provenance, no evidence and no status.")
- [S1703] types=[ARGUMENT, LIMITATION] scope=OBJECT — "Finding SG-9: classifying all eleven corpus status words by kind (epistemic/governance/lifecycle/procedural/relational/derived) shows Conflicted, Contested, and Superseded are relational facts about pairs of assertions rather than properties of a single assertion; under the terminal model these become R-edges, which (per 05-ASSERTION-SEMANTICS.md CS-3) carry no status, provenance, or time of their own, so the theory cannot express when a supersession was recorded or who authorized it." (anchor: "Three of these are RELATIONAL, not properties of an assertion at all: Conflicted, Contested and Superseded are all facts about PAIRS. Under the terminal model that makes them R-edges -- and R-edges carry no status of their own (05-ASSERTION-SEMANTICS.md CS-3), so the theory cannot say WHEN a supersession was recorded or WHO authorized it.")
- [S1704] types=[ARGUMENT, LIMITATION] scope=OBJECT — "Finding EG-3: in the terminal Assertion type, e is merely a set of evidence identifiers, so nothing in K=(A,R) holds an actual evidence object, meaning evidence identity (Step 025i / 20260827-140919's own open question), provenance (Pi is the assertion's, not the evidence's), validity, and independence are all unanswerable from K alone; the corpus's five-step evidence algebra (025c, 025c-1, 025c-2, 025c-3, 025n) therefore has no surviving inputs in the terminal model to operate on." (anchor: "The terminal model demotes evidence to opaque identifiers. In A = (id, P, e, c, t, Pi), e is a set of evidence identifiers. Nothing in K = (A, R) holds an evidence OBJECT. ... The corpus built an evidence algebra across FIVE separate steps ... and the terminal state retains none of its inputs. The algebra has nothing to operate on.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
