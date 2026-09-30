# v1-1-sim-circ5-semantic-equivalence-circularity

**Scope(s):** OBJECT · **Row count:** 8 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** CIRC-5, sem(K)=(status,A,attributed,value) · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G1919: [`theory-v1-0-def-register-findings-2026-09-10` · `v1-1-sim-circ5-semantic-equivalence-circularity`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0058 · scope OBJECT: The v1.1 circularity audit's one genuine finding: semantic equivalence (P7) is defined via a behavioural projection chosen by the experimenter, and behaviour is compared under that same projection -- circular and unresolved, and declared blocking for any kernel-minimality claim (it is why the prior kernel-reduction lane found 13 operators under one algebra and 8 under another). Candidate independent grounding: fix the theory's epistemic preservation vector P=(Meaning,Evidence,Warrant,Uncertainty,Alternatives,History,Identity,Context,Inquiry,Authorization) by contract and define equivalence as agreement on its declared-essential dimensions -- not tested.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2392 §"equiv_sem is well defined | STILL REQUIRING MATHEMATICAL WORK | CIRC-5 -- currently circular"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2925. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type=True

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2393 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2924 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S2393 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2394, S2924 |
| open_questions | PRESENT | S2392, S2393 |

## Rationale
CIRC-5 finds P7's semantic-equivalence test circular: the projection defining 'behaviour' was chosen by the experimenter with no independent criterion, explicitly linked to why the prior kernel-reduction lane got two different minimal-kernel sizes under two algebras [S2393].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2392] types=[OPEN-QUESTION] scope=OBJECT — "Records semantic equivalence's well-definedness as STILL REQUIRING MATHEMATICAL WORK, citing CIRC-5's circularity." (anchor: "equiv_sem is well defined | STILL REQUIRING MATHEMATICAL WORK | CIRC-5 -- currently circular")
- [S2393] types=[COUNTEREXAMPLE, ARGUMENT] scope=OBJECT — "CIRC-5 finds P7's semantic-equivalence test circular: the projection defining 'behaviour' was chosen by the experimenter with no independent criterion, explicitly linked to why the prior kernel-reduction lane got two different minimal-kernel sizes under two algebras." (anchor: "P7 compares two representations' behaviour under a semantic projection sem(K) = (status, A, attributed, value) -- chosen by the experimenter. ... it is why that lane's minimal kernel was 13 under one algebra and 8 under another.")
- [S2393] types=[EXTENSION, OPEN-QUESTION] scope=OBJECT — "Proposes fixing the theory's declared epistemic preservation vector by contract as a candidate independent grounding for semantic equivalence, explicitly not tested in this pass." (anchor: "The theory's own epistemic preservation vector P = (Meaning, Evidence, Warrant, Uncertainty, Alternatives, History, Identity, Context, Inquiry, Authorization) (Section 66) is a candidate: fix P first, by contract ... Not tested here.")
- [S2394] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Registers TG-4 (HIGH, blocks minimality): the CIRC-5 circular-projection problem for semantic equivalence." (anchor: "TG-4 | equiv_sem is defined via behaviour, behaviour via a chosen projection | G1 mathematical | CIRC-5 | HIGH -- blocks minimality")
- [S2395] types=[CONSTRAINT] scope=OBJECT — "States plainly that no minimality claim about the kernel is admissible until CIRC-5's semantic-equivalence circularity is independently resolved." (anchor: "Until equiv_sem is grounded independently (candidate: the preservation vector P, fixed by contract), no minimality claim is admissible.")
- [S2924] types=[DEFINITION, EXTENSION] scope=OBJECT — "Finds the reply document proposes an observational equiv_sem definition addressing OPEN-3: R1 equiv_sem R2 iff for all (Q,C,EC), Obs_{EC,Q,C}(R1)=Obs_{EC,Q,C}(R2), with eight explicitly enumerated observable dimensions (Identity, Meaning, EvidenceRelations, Assessment, Gap, Determination, History, ValidTransitions), self-described as removing the circularity of 'they are equivalent because they mean the same thing.' Connects this directly to the prior batch's CIRC-5 finding (equiv_sem defined via an arbitrarily-chosen behavioral projection) -- argues an ENUMERATED observable set is precisely what would stop the projection choice from being arbitrary, which is exactly what CIRC-5 and a separate J-proof-obligations document (filing 'equiv_sem is well defined' as still requiring mathematical work) both need. Notes as UNDECIDABLE whether the separately-dated v1.1-simulation package (no internal timestamp, datable only to after 00:46:31) could have known about this definition." (anchor: "OPEN-3 — a definition that removes the circularity by name ... R_1≡_sem R_2 ⟺ ∀(Q,C,EC): Obs_{EC,Q,C}(R_1)=Obs_{EC,Q,C}(R_2) ... eight enumerated observables: Identity, Meaning, EvidenceRelations, Assessment, Gap, Determination, History, ValidTransitions. ... This is exactly what CIRC-5 needs. ... An enumerated observable set is precisely what stops 'a chosen projection' from being arbitrary.")
- [S2924] types=[CONTRADICTION, EXPERIMENTAL-RESULT] scope=OBJECT — "Identifies a genuine three-way structural conflict among rival equiv_sem definitions in the corpus: (A) the newly-found observational definition with 8 enumerated observables, never cited by any other lane; (B) CLOSURE-4's executable, Q/Gamma/O-indexed tester (K1 equiv_sem^{Q,Gamma,O} K2 iff determinations match for all q,o), previously reviewed elsewhere as 'the semantic equivalence claim is too strong'; (C) Step 261's 7-tuple K-fraktur=(K,=_str,equiv_sem,approx_obs,SameId,equiv_H,equiv_P), carried through Steps 288-291, in which equiv_sem and approx_obs are DELIBERATELY DISTINCT tuple positions. Argues the conflict is structural, not stylistic: definition A defines equiv_sem observationally, while structure C exists specifically to KEEP equiv_sem and approx_obs apart -- if semantic equivalence really is observational equivalence, C's two positions collapse into one, exactly the collapse C was constructed to prevent." (anchor: "3. ⭐⭐⭐ But ≡_sem now has THREE rival definitions, and they conflict ... A ... B CLOSURE-4 ... reviewed as 'the semantic equivalence claim is too strong' ... C 𝔎 = (K, =_str, ≡_sem, ≈_obs, SameId, ≡_H, ≡_P) — a 7-tuple ... in which ≡_sem and ≈_obs are distinct tuple positions ... The conflict is structural, not stylistic. A defines ≡_sem observationally. C exists to keep ≡_sem and ≈_obs apart. If semantic equivalence IS observational equivalence, the two positions of C's 7-tuple collapse.")
- [S2925] types=[CORRECTION] scope=OBJECT — "Reclassifies four specific items using the new category: CIRC-5/TG-4's circular equiv_sem ('blocks minimality') from 'apparent gap' to OPEN BY COMMISSION (the prompt's SS26 commissioned the circularity audit while its line 1449 forbade repair); TG-1's three unchosen repairs R1/R2/R3 from 'apparent indecision' to OPEN BY COMMISSION ('do not choose', and the lane says so); the 10 notation collisions from 'apparent debt' to OPEN BY COMMISSION (commissioned at line 1405, constrained at line 1422); and J's four 'STILL REQUIRING MATHEMATICAL WORK' rows from 'apparent incompleteness' to OPEN BY COMMISSION (the proof-obligation register was itself commissioned to say exactly this). Explicitly clarifies this reclassification does NOT dissolve the underlying issues -- equiv_sem still genuinely has three rival, unreconciled definitions (per the prior G-40 finding, S2924), TG-1 still genuinely holds -- what changes is only the EVIDENCE CLASS of the silence: it is now understood as a declared experimental boundary, not an omission, joining the same category as rho_A, Det_r, Omega-B, succeq, and OPEN-1..6." (anchor: "Reclassified on this evidence: CIRC-5/TG-4 ... TG-1 three repairs unchosen ... the 10 notation collisions ... J's four STILL REQUIRING MATHEMATICAL WORK rows ... This does not dissolve the underlying items. ≡_sem still has three rival definitions (G-40); TG-1 still holds. What changes is the evidence class of the silence: it is a declared boundary of an experiment, not an omission")

## Notes for P3
(none beyond what is captured above)
