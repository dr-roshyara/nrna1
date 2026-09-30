# Does role conformance separate accepted from not-accepted items?

**Answer: No.** All 12 pairs of an accepted act and a not-accepted act have witness class **none** for `conformance` and for `item_kind`. The text never states conformance for any not-accepted act, so conformance cannot be a witness in any pair.

Sources used: `SOURCES.md` (R-66, R-67, R-71, R-93) and `TASK.md` only.

## 1. Acts

| id | row | item | item_kind | actor | state_before | conformance | outcome | ground | cluster |
|---|---|---|---|---|---|---|---|---|---|
| A1 | R-66 | Slice 7C (retention guard) | slice | ARB | completed¹ | UNK | ACCEPTED | RULE: "All eight R-65 requirements satisfied" | R-66 ruling |
| A2 | R-67 | WP-3A / `ChallengeRouted` as published language | WP | ARB | "Definition of Done complete" | UNK | ACCEPTED | RULE: "Definition of Done complete" | R-67 ruling |
| A3 | R-71 | WP-7B-R1 (EvidenceAnchorResolver seam) | WP (refinement, per R-66) | ARB | completed¹ | **CONFORMANT**² | ACCEPTED | RULE: "R-70's ACCEPTANCE BOUNDARY IS SATISFIED" | R-71 ruling |
| A4 | R-93 | WP-4C-1 scope D1 + D2 | scope item | ARB CHIEF | "The implementation satisfies its authorized scope" (offered) | UNK | ACCEPTED | RULE: "satisfies its authorized scope" | R-93 ruling |
| N1 | R-67 | frozen-catalog condition | acceptance condition³ | ARB (header; passive) | "contemplated earlier" | UNK | WITHDRAWN | UNK (factual finding) | R-67 ruling |
| N2 | R-67 | WP-3B | WP | UNK | "DEFERRED" | UNK | HELD | UNK: "pending a routing application service" | R-67 consequence |
| N3 | R-93 | WP-4C-1 scope D3 + D4 | scope item³ | ARB CHIEF | "NOT OFFERED" | UNK | NOT-ACCEPTED | UNK: "BECAUSE NOT OFFERED" | R-93 ruling |

¹ INFERENCE from the evidence file names (`…-completion-evidence.md`).
² SOURCE FACT: "engineering PRODUCES THE EVIDENCE SUPPORTING triple qualification; THE QUALIFICATION ITSELF BELONGS TO THE ACCEPTANCE PACKAGE. Engineering does not self-certify it."
³ INFERENCE (see Ambiguities).

## 2. Labelled findings

**SOURCE FACT**
- Only R-71 states the split between who produces the evidence, who certifies it and who accepts it (quoted in note ²).
- R-66, R-67 and R-93 do not say who produced, certified or accepted anything. R-67 says triple qualification was "performed 2026-08-02" but not by whom.
- The reasons the text gives for the not-accepted acts:
  - **N1 (WITHDRAWN):** "`Canonical_Event_Catalog_v1.0.md` has no `visibility` column — `internal` is a value of its SECURITY column — and Round50-05, the authoritative contract, lists … The frozen artifact specifies this implementation rather than contradicting it."
  - **N2 (HELD):** "pending a routing application service"
  - **N3 (NOT-ACCEPTED):** "NOT ACCEPTED BECAUSE NOT OFFERED" … "which remain separately governed (see R-97)"
- None of these reasons mentions role separation or self-certification.

**INFERENCE**
- A1 and A3 were completed before acceptance (from the evidence file names).
- D1/D2 were offered (from the contrast with "NOT OFFERED").
- D3/D4 are the same kind of item as D1/D2 (from the D-numbering).
- The frozen-catalog condition was a condition on WP-3A's acceptance.
- 'WP' means work package. The sources never spell it out; TASK.md's examples suggest it.

**FORMAL CONSEQUENCE**
- **Conformance:** none in all 12 pairs. Every not-accepted act has conformance UNK, and the witness rule requires X to be known and different on both sides. This holds under any recoding of the other fields.
- **item_kind:** none in all 12 pairs. In each pair, either item_kind is equal (A2–N2, A3–N2, A4–N3) or another compared field is known and different (state_before in every pair; actor in the ARB vs ARB CHIEF pairs).
- **A4–N3 is the closest to a minimal pair:** same row, same actor, same item_kind, conformance UNK on both sides. It differs only in state_before (offered vs NOT OFFERED), which matches the text's own reason.
- **Grounds:** coding the N grounds as UNK instead of RULE changes nothing, because UNK is still "not CHOICE".

| pair | conformance | item_kind | decisive field(s) |
|---|---|---|---|
| A1–N1 | none | none | state_before differs |
| A1–N2 | none | none | state_before differs |
| A1–N3 | none | none | actor, state_before differ |
| A2–N1 | none | none | state_before differs |
| A2–N2 | none | none | item_kind equal; state_before differs |
| A2–N3 | none | none | actor, state_before differ |
| A3–N1 | none | none | conformance UNK on N side; state_before differs |
| A3–N2 | none | none | item_kind equal; state_before differs |
| A3–N3 | none | none | actor, state_before differ |
| A4–N1 | none | none | actor, state_before differ |
| A4–N2 | none | none | state_before differs (actor UNK on N side) |
| A4–N3 | none | none | item_kind equal; state_before differs (only difference) |

**UNKNOWN**
- Conformance of A1, A2, A4, N1, N2 and N3.
- Who deferred WP-3B.
- Whether the N grounds count as RULE.

## 3. Ambiguities

1. **Is A3 CONFORMANT?** The separation is stated as kept, but it is adopted as a "RECORDING CORRECTION". That implies an earlier record may have collapsed the roles, and the text does not say whether that record concerned WP-7B-R1. If A3 were UNK, no act would have a known conformance at all.
2. **Is N2 an act at all?** "WP-3B remains DEFERRED" restates an existing hold. If N2 is dropped, 3 pairs disappear and no result changes.
3. **What kind of item is N1?** The text calls it a "condition", not an item of work. Treating the withdrawal of a condition as a not-accepted act is an interpretation.
4. **Is state_before for A1/A3 really "completed"?** If both are coded UNK instead of relying on the file names, A1–N1, A1–N2 and A3–N1 become **POSSIBLE** for item_kind. None becomes STRICT, and conformance stays none.
5. **ARB vs ARB CHIEF.** I treated them as different actors. Treating them as the same changes nothing, because state_before still differs in every R-93 pair.
6. **Grouping.** D1/D2 and D3/D4 are each coded as one act. Splitting them gives the same results.
7. **Closures are not coded as acts.** The WP-7, WP-3A, WP-7B-R1 and WP-4C-1 closures, and the items carried forward without blocking (C-2, GreenfieldCore, ENG-012, R-67 (a)–(c)), are left out.
8. **Ground categories for N1 and N3.** "Premise false" and "not offered" are not discretionary, but no rule is cited, so both are coded UNK.
