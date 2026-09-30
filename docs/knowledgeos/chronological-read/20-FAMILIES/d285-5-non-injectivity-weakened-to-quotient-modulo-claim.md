# d285-5-non-injectivity-weakened-to-quotient-modulo-claim

**Scope(s):** OBJECT · **Row count:** 5 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** non-injectivity MODULO a chosen quotient, not of δ itself · **Aliases:** D285-5 result corrected/weakened
**Candidate group membership (NOT an identity claim):**
- **G0454**: [`d285-5-non-injectivity-weakened-to-quotient-modulo-claim` · `operation-non-injectivity-hypothesis`] — explicit agent-stated uncertainty: 'd285-5-non-injectivity-weakened-to-quotient-modulo-claim' POSSIBLY relates to 'operation-non-injectivity-hypothesis' (batch B0050). Note: Major self-correction: re-running the D285-5/T-E witness (S2063, S2071/S2072) against all four corpus-named equality relations (not just structural/semantic/observational) reveals a FOURTH relation, provenance-sensitive (=_lambda), under which the two operations o1,o2 (differing only in provenance Pi) produce states that are NOT equal -- meaning delta IS injective on this exact witness under that corpus relation. The correct statement of what D285-5 established is therefore much weaker than originally claimed: not 'delta is non-injective' but 'delta composed with a semantic quotient that discards Pi is non-injective' -- and whether Pi should be discarded from semantic equality is itself an explicitly OPEN corpus decision (Decision 3, Step 254), meaning the original D285-5 (S2063) silently resolved an open governance/derivation question rather than deriving a settled result.

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0050, scope OBJECT): Major self-correction: re-running the D285-5/T-E witness (S2063, S2071/S2072) against all four corpus-named equality relations (not just structural/semantic/observational) reveals a FOURTH relation, provenance-sensitive (=_lambda), under which the two operations o1,o2 (differing only in provenance Pi) produce states that are NOT equal -- meaning delta IS injective on this exact witness under that corpus relation. The correct statement of what D285-5 established is therefore much weaker than originally claimed: not 'delta is non-injective' but 'delta composed with a semantic quotient that discards Pi is non-injective' -- and whether Pi should be discarded from semantic equality is itself an explicitly OPEN corpus decision (Decision 3, Step 254), meaning the original D285-5 (S2063) silently resolved an open governance/derivation question rather than deriving a settled result.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2080 §"E5 — WHAT DOES D285-5 ACTUALLY ESTABLISH? ... That is: non-injectivity MODULO a chosen semantic quotient. It is NOT a property of delta. Under the corpus's relation D (provenance-sensitive), the same delta IS injective on this witness."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2082. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by/superseded_by empty, contested flag false). The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the ledger, not a confirmed retirement and not a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
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
| experiments | PRESENT | S2080, S2081 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2080] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Confirms, via captured executed output, the corrected/weakened statement of the D285-5 non-injectivity result (delta is non-injective only modulo a chosen semantic quotient that discards provenance, and is injective under the corpus's provenance-sensitive relation on the same witness) -- the definitive textual confirmation that the script referenced in S2081 was actually run and produced this exact result." (anchor: "E5 — WHAT DOES D285-5 ACTUALLY ESTABLISH? ... That is: non-injectivity MODULO a chosen semantic quotient. It is NOT a property of delta. Under the corpus's relation D (provenance-sensitive), the same delta IS injective on this witness.")
- [S2081] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Re-runs the D285-5 action/result witness against all four corpus-named equality relations rather than the three previously tested, finding it vacuous under structural equality (as before), conditionally substantive under semantic equality only if a specific, currently-undecided discard-of-provenance choice is made, undefined-as-stated under observational equality pending a fixed observation set, and newly found VACUOUS under provenance-sensitive equality -- meaning two of the four relations make the property vacuous, one is conditional on an open decision, and one is undefined without further specification." (anchor: "K1={a0,a_scan[id]} via o1; K2={a0,a_vendor[id]} via o2 ... A structural = : Pi is inside id -> different ids -> different sets: VACUOUS. B semantic == : equal IF Pi is discarded -- but that CHOICE is Decision 3, OPEN: SUBSTANTIVE *but see E2*. C observational ~ : equal under cardinality-only observation: SUBSTANTIVE relative to declared set. D provenance =_lam : explicitly compares Pi -> unequal by construction: VACUOUS.")
- [S2081] types=[CORRECTION] scope=OBJECT — "States the precise, corrected characterization of what the D285-5 witness actually establishes: a much narrower claim than 'delta is non-injective' -- specifically, non-injectivity is a property of delta composed with a particular, not-corpus-mandated semantic quotient that discards provenance, and the very same delta is injective on the very same witness once provenance-sensitive equality (also a corpus relation) is used instead." (anchor: "NOT: 'delta is non-injective.' NOT: 'delta is non-injective in o.' ONLY, and precisely: There EXIST o1≠o2 and a state K0 such that delta(K0,o1)==_semantic delta(K0,o2) under a semantic projection that DISCARDS Pi. That is: non-injectivity MODULO a chosen semantic quotient. It is NOT a property of delta. It is a property of delta COMPOSED WITH that quotient. Under the corpus's relation D (provenance-sensitive), the same delta IS injective on this witness.")
- [S2082] types=[CORRECTION] scope=OBJECT — "Explicitly acknowledges that the original D285-5/T-E test only checked three equality relations when the corpus actually names four, and that the missing fourth relation directly reverses the conclusion on the exact same test witness -- a self-diagnosed completeness failure in the original test design." (anchor: "A fourth relation exists that my work never used. ≅_λ (provenance-sensitive) is exactly the relation the provenance/authority architecture needs — and under it, δ is injective on my own witness. I tested against three relations and the corpus has four.")
- [S2082] types=[CORRECTION] scope=OBJECT — "Identifies the precise mechanism of the original overreach: the corpus has an explicitly named, still-open governance decision (Decision 3, Step 254) about whether provenance/authority is part of semantic equality, and the earlier D285-5 test silently picked one answer to that open question (discard provenance) without flagging that it was making a choice at all -- meaning the result quietly substituted an assumption for an unresolved decision." (anchor: "Whether Π belongs in semantic equality is an OPEN corpus decision. CORPUS — Step 254 Decision 3: 'Is governance/authority part of semantic equality? Should K1=K2 require the same Authority/Policy/Governance, or only the same epistemic content?' — explicitly undecided. So D285-5's =_semantic — which discards Π — silently resolved an open corpus decision. The relation is named in the corpus; the projection I used is not, and the choice it embodies is Decision 3, which the corpus leaves open. B's suspicion was correct and understated.")

## Notes for P3
(Own observation) This label participates in 1 candidate group(s) (G0454); P3 should assess whether any represent the same underlying object as this label, per the reasons recorded in _LABEL-NORMALIZATION.md.
