# epistemic-status-partial-order

**Scope(s):** THEORY-LEVEL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Unknown/Observed/Supported/Established/Conflicted/Refuted/Superseded lattice` · **Aliases:** `knowledge lattice`, `non-monotonic epistemic status`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 192's proposal that epistemic status is a non-monotonic partial order (an illustrative knowledge lattice) rather than a linear scale toward truth, since states like Refuted or Superseded cannot be ranked against Supported on one axis.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1395] §"EpistemicStatus is better modeled as a partially ordered structure than as a linear scale."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1395] §"EpistemicStatus is better modeled as a partially ordered structure than as a linear scale."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1395. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S1395 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S1395 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S1395 |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1395 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Proposes a conceptual knowledge lattice (Unknown/Observed/Supported/Established with lateral branches Conflicted/Refuted/Superseded) rather than a linear scale Unknown<Observed<Supported<True, because states like Refuted vs Supported, or Superseded vs False, cannot be ranked on one axis; the diagram is explicitly illustrative, not the final mathematical lattice [S1395].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1395] types=[FORMALIZATION, ARGUMENT] scope=THEORY-LEVEL — "Proposes a conceptual knowledge lattice (Unknown/Observed/Supported/Established with lateral branches Conflicted/Refuted/Superseded) rather than a linear scale Unknown<Observed<Supported<True, because states like Refuted vs Supported, or Superseded vs False, cannot be ranked on one axis; the diagram is explicitly illustrative, not the final mathematical lattice." (anchor: "EpistemicStatus is better modeled as a partially ordered structure than as a linear scale.")
- [S1395] types=[INVARIANT] scope=THEORY-LEVEL — "Rejects the naive monotone assumption Unknown->Supported->Established (never backwards): new evidence can legitimately move Supported->Conflicted or Supported->Refuted, so knowledge evolution is non-monotonic." (anchor: "Knowledge evolution is non-monotonic.")
- [S1395] types=[CONSTRAINT, DISTINCTION] scope=THEORY-LEVEL — "Rejects building a 'TruthLattice' directly (objective truth may be unavailable to the system); instead the system can only construct an EpistemicStatusStructure plus a separately-tracked RealityState, related only observationally." (anchor: "We cannot simply create: TruthLattice ... What we can construct is: EpistemicStatusStructure. And separately: RealityState.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
