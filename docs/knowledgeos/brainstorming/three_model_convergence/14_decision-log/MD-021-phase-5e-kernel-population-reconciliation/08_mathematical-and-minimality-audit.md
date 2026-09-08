# Phase 5E — Mathematical and Minimality Audit

## Mathematical-structure audit (EVIDENCED / PARTIALLY EVIDENCED / NOT EVIDENCED)

| Candidate | Carrier/state space | Elements | Operations | Constraints/invariants | Domain/codomain | Equivalence relation | Minimality criterion |
|---|---|---|---|---|---|---|---|
| K-1 (seq 1006) | EVIDENCED (8-primitive state) | EVIDENCED (named list) | NOT EVIDENCED (this table itself; operators are documented elsewhere in the corpus, not in seq 1006) | PARTIALLY EVIDENCED ("50 attack classes, no counterexample" implies a tested invariant set, not itself enumerated here) | NOT EVIDENCED | NOT EVIDENCED | NOT EVIDENCED |
| K-2 (`(𝒜,ℛ)`) | EVIDENCED (2-tuple named) | PARTIALLY EVIDENCED (symbols named, not expanded in the digest) | NOT EVIDENCED | NOT EVIDENCED | NOT EVIDENCED | NOT EVIDENCED | NOT EVIDENCED |
| K-4 (10-tuple) | EVIDENCED (cardinality) | NOT EVIDENCED (fields not named in seq 1006's own table row) | NOT EVIDENCED | NOT EVIDENCED | NOT EVIDENCED | NOT EVIDENCED | NOT EVIDENCED |
| NEW-OBJ-5E-09 (Knowledge Atom `KA=(p,d,v,c,e,t,pi)`) | EVIDENCED | EVIDENCED (7 named fields) | NOT EVIDENCED | NOT EVIDENCED | NOT EVIDENCED | NOT EVIDENCED | NOT EVIDENCED |
| NEW-OBJ-5E-10 (Operator Contract) | n/a (operator schema, not a state space) | EVIDENCED (7 named fields: Name/Input/Pre/Transform/Post/Inv/Evidence) | EVIDENCED (this IS an operation schema) | PARTIALLY EVIDENCED (`Pre`/`Post`/`Inv` fields imply constraints, not themselves enumerated) | NOT EVIDENCED | NOT EVIDENCED | NOT EVIDENCED |
| Knowledge-Space foundation family (topological/measure-theoretic) | EVIDENCED per member (`(X,τ)`, `(Ω,ℱ,ℙ)`) | PARTIALLY EVIDENCED | NOT EVIDENCED (this audit's scope) | NOT EVIDENCED | NOT EVIDENCED | NOT EVIDENCED | NOT EVIDENCED |

**No missing structure is inferred.** Every `NOT EVIDENCED` cell reflects the actual inspection depth
of this phase (digest-plus-targeted-spot-check), not an assumption that the structure does not exist
elsewhere in the source document.

## Minimality audit (four-way: Lexical / Informal / Semi-formal / Formal)

| Candidate | Lexical | Informal | Semi-formal | Formal | Verdict |
|---|---|---|---|---|---|
| K-1 (the ratified 8-primitive state) | word "minimal" not confirmed in seq 1006's own table row | the table's own framing ("RATIFIED... COMPUTATIONALLY TESTED") is a *ratification* claim, not a *minimality* claim | NOT EVIDENCED | NOT EVIDENCED | **(none confirmed this phase)** — ratified ≠ proven minimal; these are different claims, not conflated here |
| K-4 (10-tuple, "superseded by ~120 steps") | NOT EVIDENCED | the "superseded" framing implies the corpus itself judged it non-minimal/non-adopted, not that it was formally tested for minimality | NOT EVIDENCED | NOT EVIDENCED | **(none)** |
| Knowledge-Space foundation family | NOT EVIDENCED at the "minimal" word level | each replacement is framed as *more adequate*, not explicitly *more minimal* | NOT EVIDENCED | NOT EVIDENCED | **(none)** — this family's own criterion is mathematical adequacy/safety (per seq 2315's own framing, "more general and safer"), a different property from minimality |

**Extension of Phase 5D's own finding**: no new formally tested minimality claim (level Formal) was
found among the 409 P2/P3 documents. Phase 5D's own strongest candidate (seq 0856, "Minimal
Architectural Kernel") remains the strongest minimality-adjacent finding in the entire reconstruction
to date; nothing in P2/P3 supersedes or strengthens it.

## Statement, not overclaimed

**Absence of a proof is not converted into "the candidate is non-minimal."** Every verdict above is
stated as "(none confirmed this phase)," never as "confirmed non-minimal."
