# executed-test-222-repairs

**Scope(s):** CROSS-OBJECT · **Row count:** 8 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** `EXECUTED-TEST-222-repairs`
**Candidate group membership (NOT an identity claim):**
- **G0351**: [`executed-test-222-repairs` · `step-verify-221-222-audit`] — explicit agent-stated uncertainty: 'executed-test-222-repairs' POSSIBLY relates to 'step-verify-221-222-audit' (batch B0038). Note: Rare executed-computational-evidence document (2026-08-30, evidence class B: two deterministic Python programs run to completion): tests the Step 222 SemanticIntegrity definition (SI(T,K,C)) against the 8 concrete scenarios and the conditional-invariant repair against all 5 minimal conflicting subsets mandated by the 20260830_935_prompts.md directive, finding SI vacuously satisfiable, non-composable (refuted by explicit counterexample), and a ternary (context-relative) relation, and correcting the verifier's own prior STEP-VERIFY-221-222.md claim that conditional invariants dissolve 4/5 MCS down to a more precise 2-dissolved/1-different-mechanism/1-relocated/1-survives breakdown.
- **G1569**: [`executed-test-222-repairs` · `step222-falsification-pass-candidate-architecture`] — labels co-occur in the same contribution's labels[] 8 separate times across the corpus

## Sources (how this label entered the ledger)
- **PROPOSAL** batch `B0038`, scope `CROSS-OBJECT`: Rare executed-computational-evidence document (2026-08-30, evidence class B: two deterministic Python programs run to completion): tests the Step 222 SemanticIntegrity definition (SI(T,K,C)) against the 8 concrete scenarios and the conditional-invariant repair against all 5 minimal conflicting subsets mandated by the 20260830_935_prompts.md directive, finding SI vacuously satisfiable, non-composable (refuted by explicit counterexample), and a ternary (context-relative) relation, and correcting the verifier's own prior STEP-VERIFY-221-222.md claim that conditional invariants dissolve 4/5 MCS down to a more precise 2-dissolved/1-different-mechanism/1-relocated/1-survives breakdown.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1560 §"def SI(K, C, T, critical, declared_loss): ... 8 executed cases"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S1560 §"def SI(K, C, T, critical, declared_loss): ... 8 executed cases"]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1560. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1560, S1560, S1560, S1560, S1560, S1560, S1560, S1560 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1560] types=['EXPERIMENT', 'EXPERIMENTAL-RESULT'] scope=CROSS-OBJECT — "Executed deterministic Python implementation of Step 222's SI(T,K,C) definition, run against all 8 scenarios mandated by the verification directive (complete preservation, partial loss undeclared, partial loss declared, AI-summary case matching SS222.14, empty critical set, contradictory declaration, ambiguous context A/B); cases 1-4 behave correctly, matching the corpus's own stated expectations." (anchor: "def SI(K, C, T, critical, declared_loss): ... 8 executed cases")
- [S1560] types=['EXPERIMENTAL-RESULT', 'CORRECTION'] scope=CROSS-OBJECT — "Defect 1 (VACUOUS SATISFACTION, case 5): SI has no non-triviality condition on the critical-semantics set -- if CriticalSemantics(K,C) is empty, any transformation (including one that destroys everything) scores SI=1, since the declarer controls what counts as critical; identified as the same defect class as Step 100's vacuous closure theorem (TV-F-045, a universally-quantified condition over a possibly-empty set)." (anchor: "CriticalSemantics(K,C) = \emptyset \Rightarrow SI = 1 for ANY transformation, including one that destroys everything.")
- [S1560] types=['EXPERIMENTAL-RESULT'] scope=CROSS-OBJECT — "Defect 2 (INCOHERENT DECLARATIONS UNDETECTED, case 6): the definition never checks DeclaredLoss against what was actually lost, so a declarer may freely over-declare loss and still pass, weakening the audit value of the declaration mechanism itself." (anchor: "Declaring loss of an attribute the transform actually preserves returns SI = 1 with no complaint. ... A declarer may over-declare freely")
- [S1560] types=['EXPERIMENTAL-RESULT', 'CORRECTION'] scope=CROSS-OBJECT — "Defect 3 (SI IS A TERNARY RELATION, case 7): identical K and T under two different contexts flip SI from 1 to 0 -- correct by design (SI is context-relative) but with an architectural consequence the corpus does not observe: unqualified claims of the form 'T preserves semantic integrity' (found at SS218.31's Semantic Preservation Principle, SS219.25, and SS222.36's verdict, none naming C) are not well-formed under SI's own definition." (anchor: "Identical K, identical T, two contexts => SI flips from 1 to 0. ... one may never say 'T preserves semantic integrity'. Only 'T preserves SI relative to C' is meaningful.")
- [S1560] types=['EXPERIMENTAL-RESULT', 'CORRECTION'] scope=CROSS-OBJECT — "Defect 4 (COMPOSITION FAILS -- the decisive result): a constructed counterexample with T1 (drops Confidence, declares it) and T2 (drops Source, declares it) each individually satisfy SI=1, but the composite T2 o T1 scores SI=0 because the corpus defines no composition rule for DeclaredLoss (it would need to union under composition); this matters because the corpus's own stated motivating case -- the AI pipeline Input->Interpretation->Summary->Recommendation (SS218.32, SS219.22) -- is exactly such a multi-stage composition, so SI as defined cannot evaluate the pipeline it was written to govern. The failure mode is identified as an instance of the corpus's own oldest theorem LocalCorrectness not-implies GlobalCorrectness (boxed at step-048 SS48.1, step-058 SS58.3, and steps 091/208/211 -- five prior boxings, none cited by Step 222's SI." (anchor: "SI(T1)=1 (declared Confidence) ... SI(T2)=1 (declared Source) ... SI(T2 \circ T1)=0 L={Confidence,Source} -> undeclared. ... Each stage individually satisfies semantic integrity. The composite does not.")
- [S1560] types=['EXPERIMENTAL-RESULT'] scope=CROSS-OBJECT — "Part 1 verdict table for SI: Well-typed YES (genuine improvement on SS215.10's ill-typed proportional-to), Decidable YES given finite CriticalSemantics/Recoverable sets, Falsifiable YES (cases 2 and 4 correctly return 0), Useful PARTIALLY (correct on design cases but vacuously satisfiable when critical=empty), Stable under composition NO (refuted by executed counterexample) -- overall: SI is a real improvement and genuine repair of TV-F-083, but incomplete in a way that defeats its own motivating use case." (anchor: "Well-typed YES ... Decidable YES ... Falsifiable YES ... Useful PARTIALLY ... Stable under composition NO -- REFUTED BY EXECUTED COUNTEREXAMPLE.")
- [S1560] types=['EXPERIMENTAL-RESULT'] scope=CROSS-OBJECT — "Part 3 summary of what this executed test establishes: (1) SI is well-typed/decidable/falsifiable -- TV-F-083 genuinely repaired; (2) SI is vacuously satisfiable when CriticalSemantics=empty; (3) SI does not compose, refuted by explicit counterexample, the failure being the corpus's own uncited LocalCorrectness-not-implies-GlobalCorrectness theorem; (4) SI is a ternary relation, so unqualified 'T preserves semantic integrity' statements are not well-formed; (5) conditional invariants dissolve two MCS, not four, MCS-2 survives, MCS-4 is relocated not resolved. Explicitly NOT established: that SI is useful at scale, that Recoverable(T(K)) is computable for real transformations (the finite-set case tested here is the favorable case), or that any real corpus artifact has ever actually declared a CriticalSemantics set." (anchor: "ESTABLISHED: 1. SI is well-typed, decidable and falsifiable ... NOT ESTABLISHED: that SI is useful at scale; that Recoverable(T(K)) is computable for real transformations ... that any declaration of CriticalSemantics exists anywhere in the corpus for any real artifact.")
- [S1560] types=['EXTENSION', 'EXPERIMENTAL-RESULT'] scope=CROSS-OBJECT — "Part 4 (verifier-recommended, not applied, evidence class E): five possible repairs -- (1) a composition rule DeclaredLoss(T2 o T1) superset-or-equal union of DeclaredLoss(T1),DeclaredLoss(T2), executed-tested to confirm it restores SI=1 for the counterexample, rated the highest-leverage fix since without it SI cannot evaluate the AI pipeline it exists to govern; (2) a non-triviality condition barring CriticalSemantics=empty or requiring the empty declaration to be justified; (3) requiring DeclaredLoss subseteq L so over-declaration is detected; (4) always writing SI(T,K,C) with C explicit, retiring unqualified 'preserves semantic integrity' claims; (5) declaring the projection pi for MCS-1, specifying the partial order for MCS-4, naming the entropy for MCS-5, and declaring an enumerable Q for MCS-2 -- none of these repairs is established by the corpus itself." (anchor: "Add a composition rule: DeclaredLoss(T2 \circ T1) \supseteq DeclaredLoss(T1) \cup DeclaredLoss(T2). Executed test confirms this restores SI = 1 for the counterexample.")

## Notes for P3
(none beyond what is noted above)
