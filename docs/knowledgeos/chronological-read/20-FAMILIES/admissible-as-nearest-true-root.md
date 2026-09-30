# admissible-as-nearest-true-root

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Admissible (undecidable) · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope OBJECT: "Relocation of the kernel-blocking chain's nearest true root from O_core to the (unverified-by-this-document) undecidability of Admissible."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2789 §"The nearest thing to a true root is Admissible, not O_core -- it blocks I, which blocks the necessity test, which blocks O_core. I did not verify Admissible's undecidability myself ... it is the single most load-bearing unverified claim in this triage."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2789. Candidate lifecycle: ACTIVE. Evidence: `retracted_by` and `superseded_by` are both empty, `contested_by_own_contradiction_type` is false. This is a heuristic based on how recently (by source_id) this label was last used — its only occurrence (S2789, batch B0067) — not a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S2789 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
S2789 (sections 4-6 of a five-blocker triage review) supplies this label's rationale directly: it relocates the "nearest true root" of a kernel-blocking dependency chain from O_core to Admissible — reasoning that Admissible's (claimed, but unverified-by-this-document) undecidability blocks invariants I, which blocks the necessity test, which in turn blocks O_core, making Admissible logically prior to O_core in the blocking chain [S2789]. The document is explicit and self-limiting about the strength of this claim: it states plainly "I did not verify Admissible's undecidability myself," and identifies this as "the single most load-bearing unverified claim in this triage" [S2789]. Alongside this relocation, the same document records a broader status-change table: the claim "O_core is the root" is weakened (not retracted); a separate claim that K_min needs a new apparatus is withdrawn; "K_min is stipulated" stands; "D-1A as next step" is not established; "Reject is independent and actionable" is strengthened; and the document's overall verdict (NOT READY) is unchanged despite this internal restructuring [S2789]. The document also explicitly flags two scope limitations: its reading was blocker-driven, not exhaustive over the full corpus (stated at 3,225 files / 1,643,190 lines), and two load-bearing claims — Admissible's undecidability and a separate "GN-77" nine-capability enumeration — were not independently verified by this document [S2789].

rationale_truncated_count = 0 (all rationale-bearing rows for this label are shown above).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE (family.assumption_register is empty for this label).

## All rows (source_id order)

- [S2789] types=[ANALYSIS, LIMITATION] scope=OBJECT — "Relocates the kernel-blocking chain's 'nearest true root' from O_core to Admissible (claimed-but-unverified-by-this-document undecidable), which blocks invariants I, which blocks the necessity test, which blocks O_core; records a broader status-change table (O_core-is-root weakened; K_min-needs-new-apparatus withdrawn; K_min-is-stipulated standing; D-1A-as-next-step not established; Reject-is-independent-and-actionable strengthened; overall NOT READY verdict unchanged); flags the triage's reading as blocker-driven not exhaustive, and two claims (Admissible's undecidability, GN-77's nine-capability enumeration) as independently unverified by this document." (anchor: "The nearest thing to a true root is Admissible, not O_core ...")

## Notes for P3
- This label is a self-aware, explicitly-hedged claim: the source document itself states it did not independently verify the very undecidability claim ("Admissible is undecidable") that this whole re-rooting argument depends on, and calls it "the single most load-bearing unverified claim in this triage" [S2789]. Any downstream use of "Admissible is the nearest true root" should carry this caveat forward rather than treat it as settled.
- The document's overall verdict (NOT READY) is explicitly reported as unchanged by this re-rooting — i.e., relocating the root from O_core to Admissible did not change the practical bottom-line status, only the internal diagnosis of where the blocking chain actually originates.
- This label references several other object names (O_core, I / invariants, K_min, D-1A, GN-77, "the necessity test") that are not resolved within this label's own single-row family — these look like they belong to other working_labels in the broader corpus (kernel-minimality / blocker-triage related); no group_id currently connects this label to any of them, so cross-referencing is left to P3.
