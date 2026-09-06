# A9 — Counterexample / Falsification Register (consolidated)

**Status:** CURRENT as of Wave 3. Sources: executed witnesses (evidence level C), verifier constructions (class B), corpus-internal counterexamples (SC). Every entry names its target claim.

## Executed (witness-backed)

| CE | Target claim | Counterexample | Source |
|---|---|---|---|
| CE-01 | Raw-operator duplicate invariance | 100× duplicate of s=0.7: raw SAT/BAYES → ~1.0; pipeline → 0.7 | exp01_recheck.py |
| CE-02 | Raw-operator dependence safety | API→LLM1/LLM2 chain: SAT 0.8→0.997 treated as independent | exp01_recheck.py |
| CE-03 | Scalar conflict retention | {0.9 for, 0.9 against} ≡ {0.1 for, 0.1 against} under any net scalar | exp01_recheck.py |
| CE-04 | Evidence-volume boundary crossing | 10⁶ evidence items, no authority act → not Committed | ladder_dc_reference.py |
| CE-05 | Skip transition | Candidate→Accepted rejected | ladder_dc_reference.py |
| CE-06 | Single-state expressibility of `Accepted ∧ Contest:Active` | layered model probe — no single state carries both | ladder_dc_reference.py |

## Verifier-constructed (class B, this programme)

| CE | Target claim | Counterexample | Finding |
|---|---|---|---|
| CE-07 | Any strength-only aggregator satisfying duplicate-invariance ∧ corroboration-increase | M₁=M₂ as multisets, required outputs differ | T-K6a |
| CE-08 | WM under signed monotonicity | mean(0.9, 0.1)=0.5 < 0.9 with a supporting item | T-K6c |
| CE-09 | Unqualified scalar impossibility | digit-interleaving injection [0,1]²→[0,1] (kills the *unqualified* claim; continuity restores it) | TV-F-005 |
| CE-10 | step-048 invariant 9 at Unknown | SafetyGate=Unknown ∧ Execute(d): satisfies all 20 invariants | TV-F-006 |
| CE-11 | Invariant system characterizes the system | quiet model M₀ satisfies everything (liveness-free) | TV-F-007 |
| CE-12 | I-11 excludes illegitimate genesis | "P always in force, never changed" satisfies I-11 vacuously | TV-F-007.4 |
| CE-13 | Replay under live oracles | one live human/LLM call → two replays diverge | TV-F-013 |
| CE-14 | D-S under dependence | m⊕m for m({A})=0.6: Bel 0.6→0.84 | TV-F-014A |
| CE-15 | D-S conflict handling | Zadeh: 0.99/0.01 vs 0.01↔0.99 → certainty in the mutually near-excluded C | TV-F-014A |

## Corpus-internal (SC — the corpus attacking itself; preserved as evidence)

Non-monotonic posterior 0.977→0.728 under contradictory E₃ (025c-2 — the *anticipated instance* of step-004's own disclaimer, per TV-F-001) · retraction non-commutativity `E₂=Retract(E₁)` (025k §41) · readiness falls while |K| grows (025a-1 §18) · Boolean-collapse counterexample behind Sat≠Boolean (25D.5) · 5+1≠6 scalar-Zero example (25D.17) · Cartesian-explosion 16,800-state example (203 §1) · Ω(W₁)=Ω(W₂) identifiability failures (031 §62) · the twenty step-031 counterexamples (§49–68; conceptual, several now superseded by executed/proven versions above).

## Failed counterexample searches (recorded per mandate — absence of counterexample ≠ proof)

No counterexample found against: the rim theorems under their stated assumptions (P-01…P-13) · I-5/I-6 at pipeline level (given N1) · the escalation law's soundness · Sārathi's codomain discipline. Each remains exactly as strong as its assumption set — no upgrade claimed.
