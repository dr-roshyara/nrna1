# evidence-independence-correlation-invariant

**Scope(s):** THEORY-LEVEL · **Row count:** 5 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Corr(E_i,E_j), I_64 · **Aliases:** EvidenceVolume != EvidenceStrength
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 199's invariant that correlated/derivative evidence (e.g. multiple AI agents reporting from the same underlying source) must not be counted as independent confirmation; grounds a typed Evidence Graph (derivedFrom/supports/contradicts/corroborates/supersedes) and the quantitative-estimate provenance invariant I_65.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1417 §"EvidenceVolume \neq EvidenceStrength. ... one highly reliable independent observation may be more valuable than 100 copies of the same unreliable observation."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1417 §"E_2=f(E_1). Then: E_2 is derived evidence, not independent evidence. ... G_E=(V_E,E_E) where edges represent: derivedFrom, supports, contradicts, corroborates, supersedes."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1429. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type: true.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1417 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1417 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1417, S1429 |
| examples | PRESENT | S1417 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1417] types=[DISTINCTION, EXAMPLE] scope=OBJECT — "Distinguishes the amount of evidence n(E) from its Quality(E); worked example -- if ten AI agents independently 'report' the same conclusion but all derived it from the same source (E1=E2=...=E10), the ten reports are not ten independent pieces of evidence, and naively computing P(H|E1..E10) as if independent is a classic statistical trap." (anchor: "EvidenceVolume \neq EvidenceStrength. ... one highly reliable independent observation may be more valuable than 100 copies of the same unreliable observation.")
- [S1417] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_64: correlated or derivative evidence must not be counted as independent confirmation without justification -- an important statistical governance invariant; requires preserving EvidenceProvenance and ideally EvidenceDependency (Corr(E_i,E_j)) to avoid manufacturing artificial certainty." (anchor: "I_{64}: Correlated or derivative evidence must not be counted as independent confirmation without justification.")
- [S1417] types=[FORMALIZATION, EXTENSION] scope=OBJECT — "Extends lineage into a mathematically load-bearing role for evidence interpretation: E2=f(E1) marks derived (non-independent) evidence, formalized via a typed Evidence Graph G_E with edges derivedFrom, supports, contradicts, corroborates, supersedes -- an evidence topology." (anchor: "E_2=f(E_1). Then: E_2 is derived evidence, not independent evidence. ... G_E=(V_E,E_E) where edges represent: derivedFrom, supports, contradicts, corroborates, supersedes.")
- [S1429] types=[CONTRADICTION] scope=OBJECT — "HA-S01/K7: the only actual multi-evidence combination formula stated anywhere (Dempster's rule) omits its own required independence-of-sources precondition and the well-known Zadeh high-conflict pathology, despite the same corpus elsewhere treating source dependence as its central epistemic hazard; the synthesis document itself labels the rule 'controversial'." (anchor: "The only explicit multi-evidence combination formula in the whole layer is Dempster's rule ... presented without its independence-of-sources precondition and without the Zadeh high-conflict pathology, while the same corpus makes source-dependence its central hazard ... The synthesis itself labels th")
- [S1429] types=[RESTATEMENT, LIMITATION] scope=OBJECT — "Confirms the strongly and repeatedly stated prohibition against conflating evidence count with independent evidence count (echoed at S1417's invariant I_64), but notes the actual computational treatment of dependent evidence exists only for variance/covariance terms -- the general 'weight evidence accordingly under correlation' rule has an entirely unspecified scheme." (anchor: "EvidenceCount ≠ IndependentEvidenceCount (027 §27.25); ten agents echoing one source ≠ ten pieces of evidence (199 §27.11); invariant I64 ... Dependent-evidence computation exists only for variances ... For evidence weighting under correlation, 'must be weighted accordingly' — scheme unspecified.")

## Notes for P3
(Own observation) Lifecycle candidate is CONTESTED — P3 should review the contradiction evidence before treating this object as settled in either direction.
