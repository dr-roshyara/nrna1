# pks-current-architecture-baseline-stage2

**Scope(s):** OBJECT · **Row count:** 12 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** PKS Current Architecture Baseline Stage 2 · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G0889: [`eks-current-architecture-baseline` · `pks-current-architecture-baseline-stage2`] — working_label token overlap Jaccard=0.50 (shared tokens: ['architecture', 'baseline', 'current'])

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0005 · scope OBJECT: The 25-section, evidence-tiered PKS current-architecture reconstruction produced under EP-01 Stage 2/P2, with U-01..U-14 unknowns and X-01..X-12 contradictions, corrected by a P2-F1 HPA erratum.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0194 §"the PKS current-state classification is not performed here — that is the P2 session's output ... no implementation search under app/, resources/, tests/, routes/, or database/ found for PKS."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0196 §"CBC-1 Knowledge Assessment ACCEPTED bounded context ... CBC-3 Normative Governance CANDIDATE SEAM (evidence-decided, not a context) ... Three concepts are recorded as contested and deliberately unassigned: Risk, Question, Exception record."]
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0196. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type=True

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0194, S0196 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0196 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0195, S0196 |
| open_questions | PRESENT | S0196 |

## Rationale
Anticipates from a preliminary repository search that PKS's A–G classification will be dominated by G/UNKNOWN because no implementation is found under the usual code roots, so the corpus must be expanded beyond the plan's minimum list with every admission/exclusion recorded as a finding [S0194]. Observes that PKS's most-enforced architectural properties are stated as prohibitions (knowledge never holds authority; projections never authoritative; revision never in-place; path to projection never bidirectional) rather than positive rules [S0196]. Documents the observed identifier-minting workflow: a commission/package is prepared, an identifier may be reserved in text, an authority decides, a record is written, a row is appended to the register (the minting act), then committed — an entirely manual human process outside the CAP-001 collision checker [S0196].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0194] types=[ANALYSIS, LIMITATION] scope=OBJECT — "Anticipates from a preliminary repository search that PKS's A–G classification will be dominated by G/UNKNOWN because no implementation is found under the usual code roots, so the corpus must be expanded beyond the plan's minimum list with every admission/exclusion recorded as a finding." (anchor: "the PKS current-state classification is not performed here — that is the P2 session's output ... no implementation search under app/, resources/, tests/, routes/, or database/ found for PKS.")
- [S0195] types=[CORRECTION, EXPERIMENTAL-RESULT] scope=OBJECT — "Finds and corrects a mis-measurement (P2-F1) in the PKS baseline: the .claude/settings.json permission block counts were read as '1 allow/1 ask/1 deny' (counting structural blocks) when the entries actually number 19/22/8, matching the governed record and eliminating a claimed contradiction." (anchor: "Measured (this review): permissions block = deny 19 · ask 22 · allow 8 — exactly matching the Runtime Adapter record's stated counts ... X-07 should be reclassified (contradiction not established) and U-12 resolved.")
- [S0196] types=[RESTATEMENT] scope=OBJECT — "States the single most important structural finding of the PKS baseline, self-quoted from the PKS's own governing records ('the PKS is software — No: it is knowledge specifications')." (anchor: "The PKS has no server, no database, no daemon. It is knowledge specifications (YAML + Markdown) plus a set of command-line validation programs. Its 'runtime' is a human-and-AI governance process.")
- [S0196] types=[CONTRADICTION, LIMITATION] scope=OBJECT — "States the sharpest gap between documented and implemented PKS: the frozen/promoted logical architecture (AC-1/AC-2) exists only as strategic design; the actual implementation is a smaller, different capability layer (CAP-001/003/004/006) realizing only part of the Phase III Capability Catalog." (anchor: "The PROMOTED logical architecture (two components AC-1 Knowledge Assessment, AC-2 Knowledge Projection) is NOT realized as runtime components ... whole-system conformance assessment ... is 'specified, not realized' — 'nothing performs it, no role owns it'.")
- [S0196] types=[OPEN-QUESTION] scope=CROSS-OBJECT — "Leaves the PKS/EKS identity relationship explicitly UNRESOLVED from PKS evidence alone, refusing to import EKS semantics to close the ambiguity per the study's amendment 4." (anchor: "whether EKS and PKS are the same system, different systems, or overlapping systems is UNRESOLVED by PKS evidence (§24 U-02).")
- [S0196] types=[LIMITATION, EXPERIMENTAL-RESULT] scope=OBJECT — "Catalogs several concrete measured PKS defects: an identifier-citation collision from an earlier minting act, a designed governance contract with no repository artifact, and an unresolved live git merge-conflict block found inside the Governance Runtime Adapter record." (anchor: "a curable citation-damage (G-10: R-70/R-71 ambiguous after R-65/R-66) ... the Implementation Boundary Contract IBC-1 does not exist as a repository artifact (G-7) ... at least one governed artifact currently contains an unresolved git merge-conflict block.")
- [S0196] types=[LIMITATION, RESTATEMENT] scope=OBJECT — "Records the PKS's own honest self-assessment that its Operational Evidence stands at ZERO-INDEPENDENT: internal review soundness has been demonstrated, external/independent operational validity has not." (anchor: "sixteen reviews, five adoptions, and a frozen baseline establish that the methodology is INTERNALLY SOUND. They establish NOTHING about whether it WORKS.")
- [S0196] types=[CONTRADICTION] scope=OBJECT — "Registers X-11: a tension between the PKS's claimed terminology freeze since M6 and its continued minting of new downstream identifiers/capability names, left unreconciled by any source." (anchor: "Terminology FROZEN since M6 (M8) yet the corpus continues to mint new terms and identifiers ... the freeze applies to the strategic model's vocabulary, not to downstream artifact labels; not reconciled by any source.")
- [S0196] types=[CONCEPT, DISTINCTION] scope=OBJECT — "Records the PKS's own governed context map: two ACCEPTED bounded contexts, one CANDIDATE SEAM explicitly not promoted to a context, one ACCEPTED adjacent external domain, and three concepts the architecture deliberately allocates nowhere." (anchor: "CBC-1 Knowledge Assessment ACCEPTED bounded context ... CBC-3 Normative Governance CANDIDATE SEAM (evidence-decided, not a context) ... Three concepts are recorded as contested and deliberately unassigned: Risk, Question, Exception record.")
- [S0196] types=[RESTATEMENT] scope=OBJECT — "Answers the document's own 'could a new Principal Architect run and extend this from the document alone' test with a mixed verdict: yes for governance and the implemented capability layer, no for standing up a PKS 'system' since no deployment unit exists." (anchor: "Answer to the joining-architect test — Yes for the governance, Yes for the capability layer, No for the runtime.")
- [S0196] types=[ANALYSIS] scope=OBJECT — "Observes that PKS's most-enforced architectural properties are stated as prohibitions (knowledge never holds authority; projections never authoritative; revision never in-place; path to projection never bidirectional) rather than positive rules." (anchor: "The strongest invariant set in the architecture is negative (AD-1 §5): AP-1, AP-2, AP-3, AP-9 are prohibitive — 'the strongest evidence was always a rule stating what may not happen.'")
- [S0196] types=[EXPLANATION] scope=OBJECT — "Documents the observed identifier-minting workflow: a commission/package is prepared, an identifier may be reserved in text, an authority decides, a record is written, a row is appended to the register (the minting act), then committed — an entirely manual human process outside the CAP-001 collision checker." (anchor: "identifier allocation is a manual human activity, performed by editing markdown ... no identifier service/hook/gate exists beyond the CAP-001 validator itself.")

## Notes for P3
(none beyond what is captured above)
