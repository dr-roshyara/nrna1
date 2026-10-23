# Settlement of the consolidation S0101–S0130 (due at S0125; Rulebook v1.3 §12, entries 57–58)

*2026-10-23. By the Professor. Two reviewers on different models (A = Opus, B = Sonnet), independently; then one integration reviewer (Opus) with the R3 lists. Rotated chapters: Ch. 0 and Ch. 1. No planted-gap test (next at S0150, prepared by the Rule Reviewer).*

## Reports, saved verbatim, with their sha256 at hand-back

| Report | File | sha256 at receipt |
|---|---|---|
| A (Opus) | `REPORT-A.md` | `788393e20469072cab22d564b39bfaa01c98b578159f289f18712daf486294bd` |
| B (Sonnet) | `REPORT-B.md` | `8facaaae524123bc82cfbb4b068ec03825b273e03a5ba0f71699b662683b5199` |
| Integration (Opus) | `INTEGRATION-REPORT.md` | `1054fd22567a06e325f87ac551bf4c6f0d94744b28971da9ceac71a200ca6168` |

## 0. Deliverable status

**Met. No critical finding.**
- **Findings:** A 20 (6 high); B 19 (2 high) plus its marks proposal; integration 17 (2 high).
- **Rotated chapters:** 30 defects in the union, all repaired or recorded (§2).
- **Concepts:**
  - CON-0202 is INVALID as stated, restated as CON-0238;
  - CON-0208 is restated and VALIDATED (its INVALID ground withdrawn);
  - CON-0213 goes back to PROVISIONAL;
  - five VALIDATED concepts confirmed with narrowed scope (CON-0209, 0219, 0220, 0226, 0232);
  - CON-0237 restated.
- **Inherited quote failures:** 14 → 3 (the remaining three are in Ch. 3, rotation S0150).

## 1. Entry 58: measures

| Measure | Value |
|---|---|
| **Harness time** (subagent time, from the harness) | A 957 s · B 1,342 s · integration 616 s · **total 2,915 s (48.6 min)** |
| Wall clock, review phase | 16:58:45 → 17:32:57 UTC (34 min; A and B in parallel) |
| **Defects found in the rotated chapters** | A: 18 (Ch. 0: 7, Ch. 1: 11; 2 high) · B: 19 (Ch. 0: 6, Ch. 1: 13; 1 high) · overlap 7 · **union 30 (Ch. 0: 12, Ch. 1: 18)**; 2 high (A-05 C1 stale, A-06/R1-1 §1.14.1 a stale second home of the evaluator) |
| Inherited quote failures in Ch. 1 | 6. B found 4 to be a checker artifact and 2 real; all 6 resolved (the checker fixed, S0018 L106 repaired, S0028 anchors aligned) |
| Overlap estimate (integration, a rough indication only) | Lincoln–Petersen 18 × 19 / 7 ≈ 49, so perhaps ≈ 19 defects undetected in Ch. 0–1; the reviewers are not independent, so this is not a measurement |

*For the Principal's evaluation of entry 57 at S0150:* one consolidation with two rotated chapters cost about 49 minutes of reviewer time and found 30 defects in chapters last reviewed in full at S0100. Most were stale cross-references to decisions taken since.

## 2. Findings and their disposition

**Found by both reviewers** (integration §4):
- A-02 = B-01 (CON-0213);
- A-04 ≈ B-19 (R4c "only two");
- A-05 = B R1-2 (C1 decided);
- A-06 = B R1-1 (§1.14.1 history);
- A-08 = B-16 (stale map and outline);
- A-18 ≈ B-05 (K4-o, G4);
- A-13 ≈ B-06/B-18 (loss detectability);
- A-11/A-14 ≈ B-11 (validated scopes);
- A-07 ≈ B-05 (superseded strata).

**Found by one only:** checked by the integration reviewer, and held unless marked "not checked".

| Finding(s) | Disposition | Where |
|---|---|---|
| I-01, A-05, A-06, B R1-1, R1-2, R1-3 | §1.14.1 marked as history, with a three-column pointer table: decided · fixed only within Θ_f · open or reserved (I-01's distinction, which corrects both reviewers' lists). Case 12 marked superseded. C1 decided, with pointers in §1.13, §1.14, §1.17. ι = (b) labelled a Θ_f restriction | Ch. 1 |
| A-01, I-02, A-09 | CON-0237 restated with "silently"; the §8.17 heading and plain language; F24 no longer cites R-CONFLICT as claim-level evidence (map, outline) | §8.17 C5; register; map; outline |
| A-02, B-01, B-08, I-12 | CON-0208 restated and VALIDATED on the authority's own words; CON-0213 PROVISIONAL, restricted to the registrar's reading | §8.14 C1; §8.15 C8; register |
| A-03, I-03 | CON-0202 INVALID as stated; CON-0238: three senses of reference | §8.13 C1; register |
| A-04, I-15 | "only two" labelled the Principal's framing (F22 not reopened) | §9.16 R4c, R4d |
| A-07, A-18, B-05 | CON-0191 brought to v14; the superseded §9.13 stratum marked; G4's current rule stated; K4-o retained as a pointer to L9 | §9.13; §9.15; register |
| A-08, B-16 | THEORY-MAP §9 (position, counts, register); F22 row (the 10-23 decisions); outline F22 decided, CE\*'s one home, the History row | map; outline |
| A-10, A-11, A-14, A-15, B-11 | the validated meanings trimmed to their cores | register |
| A-12, A-20, I-14 | §8.16 C2: "a recorder's release, basis not shown", evidence for neither rule; the senses of "owner" qualified | §8.16 C2 |
| A-13, B-06, B-18, I-07 | loss detectability stated once, in §9.15 K1 ("iff a surviving entry cites it, or a numbered successor survives"); §8.17 C5 points there | §9.15 K1; §8.17 C5 |
| A-16 | CON-0195's attribution corrected (the PO/ARB adopted Option A; the criteria are the registrar's listing) | register |
| A-17, I-17 | §8.15 C4: reproducibility was used in §1.16.3 and §8.6 | §8.15 C4 |
| A-19, B-13 | S0127 R-6 and S0117 L211 added to §8.17 C2; "permanently" labelled the registrar's | §8.17 C2 |
| B-02 | kernel/application marks for §8.12–§8.17, as proposals, with the boundary tests owed | BOOK-OUTLINE |
| B-03 | CON-0236: two registrar defaults on the same day recorded | register |
| B-04, I-06 | S0164 is S0103 pasted in (verified by the Professor: 96 of 136 lines); §8.6 and §8.12 C1 corrected: one statement, not two | §8.6; §8.12 C1 |
| B-07 | CON-0215 and §8.15 C1: "not to be presumed general" (deontic) | §8.15 C1; register |
| B-09 | §8.15 C9: both directions for M4 only | §8.15 C9 |
| B-10, I-16 | CON-0201: the dependency between its two levels stated | register |
| B-12 | §8.17's title and plain language brought up to the C24 settlement | §8.17 |
| B-14 | E1–E10 and L1–L3 homonyms qualified | §8.17 C2 |
| B-17 | Class C attribution labelled the registrar's; the dated AMS universal in §1.14; §0.8 scoped | §8.15 C1; §1.14; §0.8 |
| I-04 | §1.10 I-0 scoped to assigned identifiers; content identity as the rival | §1.10 |
| I-05, B R1-5 | §1.16.7 and THEORY-MAP scoped to invariants that merging can violate | §1.16.7; map |
| I-08 | "application (as a recorder)" added as a candidate primitive row | BOOK-OUTLINE §1 |
| I-09 | cut membership aligned: the record is in D, a cut is evaluable | §9.13 R1 area, §9.13.6; §9.16 R2 (d) |
| I-10 | R3a's reason scoped to consumptions; whether Φ reads work-act uses goes to T9-07 (f) | §9.16 R3a; T9-07 |
| I-11, B-18 | CON-0206 is a corollary (the contrapositive) of I-7 | §8.13 C5; register |
| I-13 | "C1 (§9.13 Θ_f)" in §8.14 | §8.14 C5 |
| B-19 | the source of truth between K4-k and the owner's record goes to T9-07 (g) | T9-07 |
| SEQ (B §5) | a glossary of C-5 and C-10 in §8.12; the evidence classes glossed in §8.15 C1 | §8.12; §8.15 |
| Rotated-chapter defects, all others (A R0-1..7, R1-1..11; B R0-1..6, R1-1..13) | repaired in batches 2 and 3 | Ch. 0, Ch. 1 |

**Verdicts not adopted:**
- A's promotions of CON-0186, 0201, 0206, 0207, 0212, 0214, 0225 and 0229 to VALIDATED: B kept them PROVISIONAL, and the integration reviewer advised against for 0201, 0206 and 0212. They stay **PROVISIONAL**.
- A's INVALID verdict on CON-0237: it is restated in place instead (the restatement is its valid core).

**Not addressed in this settlement, recorded:**
- the "transfer versus completion" reading of S5's block is covered by §8.16 C1;
- B R0-4's §0.2 "first recorded case … so far" could not be located in the current text;
- A-10's and A-19's claims were not checked by the integration reviewer, but A-19 is applied.

## 3. Checks

- **Quote check:** every changed section has 0 not found. The whole book has 3 failures, all inherited, in Ch. 3. The baseline is regenerated, and its header records why: the checker fix and two repairs.
- **R1:** 214 rows with a home; 1 inherited flag (CON-0133).
- **Register:** 238 concepts (40 VALIDATED, 104 PROVISIONAL, 94 INVALID).
- **Model:** unchanged (v15). Reviewer B re-ran it and the Helly check; both are identical to the saved runs.

## 4. The Professor's choice, reviewed

**"No new blind step."** Neither reviewer objected. Both judged the book against the sources, and A and B checked 264 and 160 quote–anchor pairs respectively.

## 5. Open items

| Item | Role | Next action |
|---|---|---|
| boundary tests owed for the K? concepts (BOOK-OUTLINE, Ch. 8 marks) | Professor | in theory turns, when the Principal schedules one |
| T9-07 (f) Φ and work-act uses; (g) source of truth | Professor | only on a critical finding or a new source (the Principal) |
| a cut-level definition of membership in D, if needed (I-09 follow-up) | Professor | next §9.13 revision |
| the rotation | Professor | S0150: Ch. 2, Ch. 3 (with Ch. 3's 3 inherited quote failures); planted-gap test by the Rule Reviewer |
| reading | Professor | C25 (S0131–S0135) |
