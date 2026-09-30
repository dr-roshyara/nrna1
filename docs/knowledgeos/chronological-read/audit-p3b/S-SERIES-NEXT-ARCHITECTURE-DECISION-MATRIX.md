# S-Series Next Architecture Decision Matrix (decision support; NOT a recommendation; NOT ranked)

**Scope:** S-Series only.

**Evidence base:**
- OB0004 R2/R2.2/R2.3 (G-LOG-0047…0051);
- the decomposition pilot (G-LOG-0053);
- the post-pilot brief (G-LOG-0054);
- the research-purpose gate (G-LOG-0055);
- Gate C (G-LOG-0057: n = 2 labels);
- the next-pilot design v2.

**Tags:** OBSERVED / INFERRED / HYPOTHESIS / UNRESOLVED.

| Dimension | A. Reconstruction-first only (frozen v1.7 as written) | B. Research-first only | C. Two-layer (floor + discovery, in parallel) | D. Staged hybrid (discovery first as triage, then floor per selected or all labels) |
|---|---|---|---|---|
| **Epistemic guarantees** | full frozen set: chronology, never-thinned absences, layer-A anchoring, anti-hindsight (OBSERVED design) | claim-scoped evidence; hindsight and negative-evidence weaker (gate §9, HYPOTHESIS); no reconstruction | the floor's guarantees plus discovery-layer controls (freeze, page proof) (HYPOTHESIS) | the floor's guarantees for floored labels; discovery-only labels have B's weaker guarantees until floored (HYPOTHESIS) |
| **Discovery capability** | register per label, deep checklist on 567/1,975 labels; Gate C: the pipeline-produced registers **omitted 29 findings** from read files (OBSERVED, pilot-specific) | Gate C: 51 candidates, 29 A-omissions surfaced, M1 0.462 on A's findings (OBSERVED, n = 2) | the union of both; unmeasured (UNRESOLVED) | as B at triage; as A after flooring |
| **Reconstruction capability** | complete by design (OBSERVED on small labels; heavy labels need decomposition: G-LOG-0053) | none by design (OBSERVED) | complete (via the floor) | complete for floored labels only |
| **Extraction completeness** | not guaranteed by reading (Gate C observation; UNRESOLVED magnitude) | not guaranteed; fewer files read | not guaranteed; measurable via the segment inventory + independent extraction (HYPOTHESIS, v2) | as C |
| **Cost** | high and front-loaded: all 396 batches read whole; 82 labels exceed one context (OBSERVED) | low: Gate C about 40% of the bytes on 2 labels (OBSERVED) | highest total: floor plus discovery (INFERRED) | variable; depends on the flooring policy (INFERRED) |
| **Scalability** | bounded by label capacity; decomposition fidelity has 4 losses (OBSERVED); OB0018 open | scales with questions (INFERRED) | as A for the floor; OB0018 open | as A for floored labels |
| **Hindsight risk** | lowest (chronological layer A first) | highest without layer-A anchoring (HYPOTHESIS) | low for floored content; discovery records anchor to layer A | low after flooring; triage stage B-like |
| **Verification burden** | verifier + audit per batch (OBSERVED) | per-claim verification; lenient self-grading observed in Gate C | both (INFERRED) | both, staged |
| **Unresolved risks** | extraction completeness; heavy-label execution (OB0018) | reconstruction absent; candidate-generation and trigger losses (Gate C) | combined cost; whether discovery must wait for the floor | selection bias in which labels get floored; v3.5 Phase-3 obligation if not all labels are floored |
| **Evidence supporting** | frozen design; OB0004 R2.3 page-proven reading on 4 small labels; pilot Property A | Gate C (n = 2): efficiency, A-omissions, 0 hindsight failures | complementary failure modes seen in Gate C (INFERRED) | none specific |
| **Evidence missing** | extraction completeness; OB0018 | recall on reconstruction-type findings; a larger sample | the v2 pilot; the V1 instrument validation; OB0018 | any experiment; a policy for which labels to floor |

**Governance constraints common to all options (OBSERVED):**
- **B and D (if not all labels are floored)** depart from v1.7's terminal predicate (§23) and v3.5's Phase-3 roll-up. That requires a §26 revision and a v3.5-level act (§26.4).
- **A and C** keep the frozen obligations.

**State:** decision support only. No option is adopted; nothing executed; production frozen; H-19 SEALED; S5c PROHIBITED.
