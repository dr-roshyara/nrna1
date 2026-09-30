# knowledge-dimension-vs-sentence-distinction

**Scope(s):** OBJECT · **Row count:** 4 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Knowledge Dimension = an independently distinguishable proposition about an observation`, `Sentence -> one or more K_t` · **Aliases:** `dimension is not a sentence`
**Candidate group membership (NOT an identity claim):**
- **G1759**: [`atomic-knowledge-unit-kt-observation-hypothesis` · `knowledge-dimension-vs-sentence-distinction`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0056, scope OBJECT): Correction/refinement recurring through S2309: a sentence is a linguistic representation/encoding that may express one, several, or overlap with other sentences over the same knowledge dimension (e.g. 'runs RHEL 9.8' and 'the operating system is RHEL 9.8' are two sentences but potentially one dimension); the mathematical primitive should be an independently distinguishable/independently evaluable proposition (the smallest independently evaluable knowledge claim), not literally a sentence, and not necessarily even a whole proposition (a single sentence such as 'has 8 vCPUs and 31GB RAM' may itself decompose into two atomic claims).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2309] §"A sentence is a linguistic representation that may contain one or more K_t units. ... the number of sentences gives us a possible dimensional view, but not necessarily the true number of dimensions."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2356. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows) — this ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S2309, S2310 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S2309 |
| Examples | PRESENT | S2309 |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | PRESENT | S2356 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2309] types=[DISTINCTION, EXAMPLE] scope=OBJECT — "Using a repeated Nexus-server worked example (RHEL 9.8, 8 vCPU, port 8081, etc. as ten atomic statements), argues a sentence is not necessarily one K_t unit -- e.g. 'has 8 vCPUs and 31 GB RAM' contains two atomic units -- and that an observation's true dimensionality is not bounded by however many sentences currently describe it (K_t(O) subset-of all potentially observable aspects of O; 'Knowledge at t != the complete observation'); new sentences discovered later add new dimensions (K_t^11), and a corrected statement changes an existing dimension's value rather than necessarily creating a new one." (anchor: "A sentence is a linguistic representation that may contain one or more K_t units. ... the number of sentences gives us a possible dimensional view, but not necessarily the true number of dimensions.")
- [S2309] types=[DEFINITION, CORRECTION] scope=OBJECT — "Explicit correction of the sentence=dimension identification: two different sentences ('runs RHEL 9.8' vs 'operating system is Red Hat Enterprise Linux 9.8') may express one underlying dimension; defines Knowledge Dimension as an independently distinguishable proposition about an observation, with a sentence merely an encoding/representation of one, and calls for a principled independence test between candidate dimensions, connecting this to the corpus's existing Representation != Identity discipline." (anchor: "Knowledge Dimension = an independently distinguishable proposition about an observation, not simply Knowledge Dimension = sentence.")
- [S2310] types=[DEFINITION, CONSTRAINT] scope=OBJECT — "Restates the minimal-knowledge-unit hypothesis k_t(O) (smallest independently meaningful unit of knowledge about an observation at t) with the qualification that sentence != dimension automatically -- semantic independence of a statement must be established before it counts as a candidate dimension; and states K_t(O) is necessarily partial, since K_t(O) subset-of D(O), the full (possibly unbounded) dimension set of the observation." (anchor: "A sentence is not automatically a knowledge dimension merely because it is syntactically a sentence. ... independent knowledge-bearing statement -> candidate dimension")
- [S2356] types=[CORRECTION, OPEN-QUESTION] scope=OBJECT — "Corrects/sharpens the atomic-knowledge-unit hypothesis by pointing out that reviewed prior work jumps directly from an observation O to a set of atoms k_1..k_n without deriving them, despite itself distinguishing dimension != statement != knowledge atom != value; the transformation producing the atoms from an observation is never actually supplied." (anchor: "The document currently jumps: $$O\rightarrow k_1,\ldots,k_n\rightarrow K_t$$ But **where do \(k_1,\ldots,k_n\) come from?** ... dimension \(\neq\) statement \(\neq\) knowledge atom \(\neq\) value ... but does not provide the transformation between them.")

## Notes for P3
Carries 1 candidate group membership (G1759); P3 should decide whether it reflects the same underlying object as the other label(s) in that group, or merely a surface-signal coincidence. Lifecycle (ACTIVE) rests on recency heuristics only — no explicit retraction, supersession, or self-contradiction signal was found in this label's own rows.
