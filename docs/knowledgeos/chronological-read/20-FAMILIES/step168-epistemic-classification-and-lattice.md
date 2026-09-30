# step168-epistemic-classification-and-lattice

**Scope(s):** THEORY-LEVEL · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Declared/Observed/Tested/Verified/Supported/Inferred/Hypothesized/Inconclusive/Not-verifiable, Status(P)=<Basis,Method,Strength,Uncertainty>, lattice not a ladder: ProbabilisticallySupported not comparable to DeterministicallyVerified · **Aliases:** nine-state epistemic classification, the verification lattice
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 168's nine-state epistemic classification for a claim (Declared/Observed/Tested/Verified/Supported/Inferred/Hypothesized/Inconclusive/Not verifiable), explicitly a lattice rather than a simple ladder because ProbabilisticallySupported and DeterministicallyVerified are not mutually comparable (e.g. P(H|E)=0.95 is strong support but not equivalent to Verified(H)=true); formalizes claim status as a tuple Status(P)=<Basis,Method,Strength,Uncertainty> (e.g. <Observed,AutomatedTest,Deterministic,0> vs an AI conclusion <Inferred,LLM,Probabilistic,High>); founding rule: 'evidence of a statement is not automatically evidence of its truth.'

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1361 §"Declared Observed Tested Verified Supported Inferred Hypothesized Inconclusive Not verifiable. ... Evidence of a statement is not automatically evidence of its truth. ... At first glance we might write: Declared < Observed < Tested < Verified. But that is incomplete ... ProbabilisticallySupported and DeterministicallyVerified are not necessarily comparable. ... Status(P) = <Basis, Method, Strength, Uncertainty>."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1361 §"Declared Observed Tested Verified Supported Inferred Hypothesized Inconclusive Not verifiable. ... Evidence of a statement is not automatically evidence of its truth. ... At first glance we might write: Declared < Observed < Tested < Verified. But that is incomplete ... ProbabilisticallySupported and DeterministicallyVerified are not necessarily comparable. ... Status(P) = <Basis, Method, Strength, Uncertainty>."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1361. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1361 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1361 |
| dependencies | PRESENT | S1361 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1361 |
| warnings | PRESENT | S1361 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S1361]** types=[FORMALIZATION, DEFINITION] scope=THEORY-LEVEL — "Defines a nine-state epistemic classification for a claim (Declared/Observed/Tested/Verified/Supported/Inferred/Hypothesized/Inconclusive/Not-verifiable) as safer than a binary true/false; argues this forms a lattice rather than a simple linear ladder because ProbabilisticallySupported and DeterministicallyVerified are incomparable (e.g. P(H|E)=0.95 is not equivalent to Verified(H)=true); formalizes per-proposition status as Status(P)=<Basis,Method,Strength,Uncertainty>. Founding rule: evidence of a statement is not automatically evidence of its truth." (anchor: "Declared Observed Tested Verified Supported Inferred Hypothesized Inconclusive Not verifiable. ... Evidence of a statement is not automatically evidence of its truth. ... At first glance we might write: Declared < Observed < Tested < Verified. But that is incomplete ... ProbabilisticallySupported and DeterministicallyVerified are not necessarily comparable. ... Status(P) = <Basis, Method, Strength, Uncertainty>.")
- **[S1361]** types=[WARNING, EXAMPLE] scope=THEORY-LEVEL — "Extends the correlated-evidence warning ('concordance is not independence') with the 'three-agent problem' example: if agents A, B, C all read the same document and produce conclusion X, that is 3 outputs -> 1 source, not three independent confirmations, so n=5 agreeing does not imply evidence strength scales 5x. States provenance is computational, not merely metadata: if E2=Transform(E1), E2 is not an independent source merely for being a separate artifact. Recommends separate graphs for evidence (G_E: derivedFrom/copiedFrom/independentOf/corroborates/contradicts), knowledge (G_K: supports/supersedes), and claims (G_C: supportedBy/contradictedBy/derivedFrom/refinedBy) rather than one generic graph." (anchor: "Concordance is not independence. ... n=5 does not imply: EvidenceStrength=5×. ... Agent A ─┐ Agent B ─┼──► same document ───► conclusion X. Agent C ─┘ ... 3 outputs → 1 source. ... E2=Transform(E1), then E2 does not constitute an independent source merely because it is a separate artifact.")

## Notes for P3
- Own observation: only 2 row(s) touch this label — a thin evidentiary base; treat any generalization from it with caution.
- Own observation: ungrouped in P2a — no co-occurrence or notation signal tied it to another label; may be a genuinely isolated object, or simply under-linked by the mechanical pass.
- Own observation: no rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) row was found for this label — its purpose/motivation, if any, is carried only in DEFINITION/FORMALIZATION-typed rows.
