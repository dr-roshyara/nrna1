# Chapter 16. Validation strategy: external review, source validation, reference model

*Status: DRAFT, checked in the final cross-chapter review (same model family); corrections applied.*

The theory is validated along **three independent axes**. Success on one does not substitute for another.

## 16.1 Axis 1: external scientific review (D6)

- **Reviewer:** preferably a human mathematician or theoretical computer scientist who did not take part in the research. Another AI model family may supplement, but not replace, the human review.
- **Package:** manuscript v1, Ch. 1–14, plus the synthetic-check records. Stage 1: claims only. Stage 2: proofs and evidence.
- **Questions for the reviewer:**
  - Are the main theorems correct as stated? Thm 3.8, 4.3, 4.5, 7.3, 8.6, 9.8, 10.4, 11.3, 11.12, 12.5(a),(c).
  - Is ⊑ the right information order? Is there prior literature, e.g. on provenance, information flow, or minimal realization of input/output maps?
  - Is the U-1 witness (Ch. 4) correct?
  - Verify the LIT items cited in Ch. 0–13: Moore/Hopcroft minimization, Pfleeger, Sénizergues, Eilenberg (pseudovarieties), Schützenberger/Carlyle–Paz, H(g(X)) ≤ H(X), arithmetic-coding bounds, and the cryptographic commitment properties.
- **Acceptance:** written per-claim verdicts. Until they are received, no chapter's status exceeds what its header says, and none is externally reviewed.

## 16.2 Axis 2: source validation against KnowledgeOS (blocked; D1, D2, gate)

- **Prerequisites:**
  - G-LOG-0147 entered;
  - the D1 review passed;
  - the signed human test;
  - the Principal's gate act;
  - a separate act for the count-only check.
- **Scope:** five sources (three named by ID; two blocked until named).

**What to check (each maps a §14.2 assumption to evidence):**

| Assumption | Question for the sources | If false |
|---|---|---|
| IC | do KnowledgeOS decisions read data not recorded as events (clock, external services)? | record them, or treat those decisions as external |
| DET | are decisions functions of the event history? | Ch. 6 nondeterminism treatment |
| OD | are illegal operations defined and observable? | canonicity fails (CE 3.9) |
| A1 | is the decision set fixed per version? are past decisions revised? | Ch. 11 bitemporal model |
| A2 | are logs append-only with delimited entries? | Prop. 2.13 does not apply |
| OF | is the output log faithful? | verification needs attested outputs |
| Trust and fault models | which components are attested, and by whom? what failure behaviour is documented? | verification claims cannot be instantiated |
| Determinism of records | are any audit mechanisms randomized (salts, signatures with nonces)? | outside ⊑; Ch. 14 open problem 1 |
| Assumption 5.2 | are contract checks functions of the recorded history? | requirements become external decisions (Ch. 5) |
| 5 core operations (SRC) | do ASSERT, LINK, REVISE, RETRACT and ISOLATE generate every needed transition? | "execute transformations" stays unmapped (Ch. 3) |
| Gap/Zero (SRC) | is Δ set-valued? is satisfaction a function of state? | re-prove Ch. 5 for graded requirements |

**Output:** for each source, the coverage (full or partial), the claim it supports, quotations verified, and discrepancies recorded without silent reconciliation.

## 16.3 Axis 3: reference model and computational validation (synthetic; permitted now)

**A small reference implementation** of the core objects:
- event logs;
- decisions;
- the canonical-state fold (Moore refinement for finite specifications);
- outcome logs;
- bitemporal logs;
- verifiability checks (confusable-pair test);
- the (S-joint) checker.

**Property-based tests** (randomized, seeded) for the invariants of Ch. 15 §15.4. **Every CE of the manuscript becomes a regression test.**

**Machine learning, used honestly:**
- *Counterexample search:* ML-guided generation of fault models, decision tables and policies that maximize "near-violations" of conjectures (e.g. storage ratio bounds; Thm 4.7 under finite state). Every candidate found is checked exactly.
- *Conjecture generation:* symbolic regression or decision-tree learning on enumerated small cases, e.g. to guess the general per-node storage ratio. Learned patterns are **hypotheses only**.
- **Not used as evidence:** ML confidence scores, or the mere agreement between same-family models.
- **What counts as evidence:** the **outputs of independently written code** (FIN). Across the manuscript, "independent" means independently written code by same-family reviewers, not independent scientific review.

**Discipline:** protocols frozen and hashed before code; code identity recorded before runs; deviations disclosed; independent implementations for key checks.

## 16.4 Readiness for implementation

| Component | Ready for a reference prototype? | Ready for production? |
|---|---|---|
| kernel fold, decision specifications with OD | yes | after source validation (IC, DET, OD), external review, and the STAKE choice of specification language (Thm 3.12) |
| outcome and bitemporal logs | yes | after the A1/A2 source checks and the stakeholder retention choices |
| verifiability checker | yes | after fault and trust models are documented and approved (STAKE) |
| privacy check on the minimal record | yes | after the privacy policy is defined (STAKE) |
| storage optimization | measurement only | — |

**No irreversible implementation on unvalidated assumptions.**

## 16.5 Order of work

1. Re-review the material added after the last review rounds (CE 4.5e, the Prop. 8.10(b) witness, Prop. 11.6, Prop. 12.6, and Ch. 14–16).
2. The external review package (Axis 1).
3. The reference model and property tests (Axis 3), in parallel.
4. Source validation (Axis 2) when the gate passes.
5. Revise the theory, then architecture decisions with stakeholders.
