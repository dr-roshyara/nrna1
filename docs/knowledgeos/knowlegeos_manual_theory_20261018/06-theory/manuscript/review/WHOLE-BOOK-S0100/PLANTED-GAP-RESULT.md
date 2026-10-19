# Planted-gap test, whole-book review S0100: result

*Scored 2026-10-19 by the Rule Reviewer against the sealed key (`PLANTED-GAP-ANSWER-KEY.md`, released with this result; sha256 `cd58fe22…3a454c`, unchanged since sealing). Scored: the ten part reports, the integration report, the Professor's step-8 file (committed before the first commission) and the stage-1 settlement `4c81960ba`. Counts only; eight chosen items are not a random sample, and the result says what the process caught **for these items under these conditions**, not how reliable it is.*

**Validity of the test:** the copy matched its sealed manifest at scoring (119 of 119); the working book unchanged since `f7be43070`; isolation audit: 19 passes, 2 × B2 (P1-B, P5-B), neither touching a plant's detection; the P5-A remark ruled no breach. **All detections below are blind.**

## 1. Detection

| Item | Class (key severity) | Where | Found by | Severity given |
|---|---|---|---|---|
| P1 | foundational derivation, hidden premise removed (**critical**) | §9.8 Lemma D, proof step 4, result 1 | **P4-B** F7 (the reductions rest on a function that does not exist; "mark them conditional on CE defined"); **integration** row 58 and finding 2 (the chain is cut at CE). **Not** P4-A. Both P4 falsification tables rate Lemma D "adequate" | high |
| P2 | unsupported generalisation, Int → Ded (high) | §9.8.4 | **P4-A** F9, **P4-B** F14 | normal |
| P3 | undefined code (normal) | §9.8.3 "RX-3" | **nobody** | — |
| P4 | ordinary word as undefined term (normal) | §9.8.1, §9.8.2 "watermark" | **nobody** | — |
| P5 | whole result removed, renumbered (high) | §9.8.6 result 4 (CON-0149) | **nobody**. The settlement even records "§9.8 M5 and **result 6** hold" for the TT4 check: after the renumbering, result 6 is another result | — |
| P6a | distortion of a source rule (high) | §5.7 C3 | **P3-A** F1, **P3-B** F1 | high |
| P6b | downstream claim not reassessed, cross-chapter (high) | §9.8 result 5 still cites "§5.7 C3's rule (recognized only when recorded)" | **nobody**, including the integration review (its I-18 lists §5.7 C3 on CON-0145, not §9.8's citation) | — |
| P7 | settlement-introduced defect (high) | §8.11 C1 | **P5-A** F1, **P5-B** F1, and **the Professor** (PS8-1, before any review) | high |

**Found: 4 of 8** (P1, P2, P6a, P7). **The critical item was found:** the integrity gate passes, by one of two part reviewers and the integration reviewer.

**Per reviewer** (items in its part): P4-A 1 of 5 · P4-B 2 of 5 · P3-A 1 of 1 · P3-B 1 of 1 · P5-A 1 of 1 · P5-B 1 of 1 · integration 1 of 2 (P1 yes, P6b no) · the Professor's step-8 check 1 of 1 in its scope (P7; P5 was also in its step-9 list, as "§9.8 result 6", and not noticed).

## 2. Treatment in the stage-1 settlement (finding is not resolving)

| Item | Settlement row | Treatment | Verdict |
|---|---|---|---|
| P1 | P4-7 | "Mark the reductions 'conditional on CE defined' **in the register**"; resolved when "conditions carried in the register". Lemma D's statement, proof step 4 and result 1 are not corrected in the text; the downstream list names §9.6–§9.9 and CON-0148, CON-0160 | **partial.** The condition is right; its place is wrong: a lemma stated without its premise stays false as written. Severity high, not critical |
| P2 | N4-01 | made conditional: if past queries are in Req and the deployment has revocation or supersession, H1′ is needed | **correct in substance** (the universal is gone); adds conditions beyond the original Int; label not restored to Int. Severity normal, not high |
| P6a | P3-1 | align C3, §5.7.1, CON-0145 and the index with S0080 L47, "recorded, never inferred" | **correct** |
| P6b | — | P3-1's affected list (§5.7 C3, §5.7.1, CON-0145, index) omits §9.8, which cites C3 | **not treated:** the entry-55 claim-level downstream check was not run for an accepted correction |
| P7 | P5-1 | delete the sentence; "both checked self-declarations were true; the erratum's is unchecked" in all four places | **correct**, and sharper than the base text |

**Treatment: 3 correct, 1 partial, of 4 found** (plus P6b untreated).

## 3. Why the misses happened, and what would have caught them

| Miss | Cause | Would a check have caught it? (run on the copy, 2026-10-19) |
|---|---|---|
| P3 RX-3, P4 watermark | **The same failure as "lane" / "BC-7" in the last test, now twice.** No reviewer checks for undefined terms; reading for meaning passes over them | partly. A script listing code-like identifiers that occur once in book + map + register flags RX-3, **among 169 candidates**; single-section repeated words not in the register flag "watermark", **among 213**. Both lists are too noisy as they stand; they need narrowing (for example: not defined in a definitions block, not in the corpus allowed list) |
| P5 result removed | **The same failure as O5 in the last test, now twice:** a whole removed claim with renumbering is not noticed by reading, and the step-9 check cited the result by number | **yes, precisely.** Checking each register row's home section for its CON-ID flags **4 rows, CON-0149 among them** |
| P6b downstream citation | the integration review worked from the part reports, which saw only §5.7; the settlement's downstream list for P3-1 was built from the section, not from inbound citations | the dependency graph cannot (one cluster of 139 nodes). An **inbound-citation check** ("which sections cite §5.7 C3?") by search is cheap and would have listed §9.8 |
| P1 under-rated; "adequate" in both P4 falsification tables | adequacy judged from the lemma's own description ("definitional"), without checking its premises against §9.8.2, which says CE is undefined | proof checking per step, applied to every "adequate" judgement |

## 4. Across the three tests (counts, not rates)

| Class | C10 | Consolidation S0036–S0065 | Whole book S0100 |
|---|---|---|---|
| distortions against a source | 2 of 2 | 4 of 4 (blind) | 2 of 2 (P6a, P7) |
| removed whole claim with renumbering | — | 0 of 1 (O5) | 0 of 1 (P5) |
| undefined terms | — | 0 of 1 (lane / BC-7) | 0 of 2 (P3, P4) |
| unsupported generalisation | — | — | 1 of 1 (P2) |
| hidden premise in a derivation | — | — | 1 of 1 (P1), by one of two part reviewers |
| downstream / cross-chapter | — | — | 0 of 1 (P6b) |

**Bounded conclusion.** For these items, the review reliably catches what contradicts a source or the text around it. It does not catch what is **absent**: undefined terms, a removed claim, or a citation elsewhere that a change leaves stale. Those three have now been missed in every test that contained them. Reading does not find them; **cheap mechanical checks would** (§3).

## 5. Recommendations (for the Principal's decision)

1. **Add three mechanical checks to every brief** (v1.3 §2 step 8, or with v2 §8.2): (a) register home vs the section's CON-IDs; (b) inbound citations of every changed or corrected claim; (c) an undefined-term candidate list, narrowed before it is used. Check (a) and (b) are ready to write; (c) needs one round of narrowing, tested on RX-3, "watermark", "lane", "BC-7".
2. **The settlement runs the claim-level downstream check (entry 55) for every accepted correction**, not only for the changed theory before review.
3. **Proof checking applies to every "adequate" judgement** in the refutation tables (reviewer instructions, step 3).
4. **Severity:** a missing premise in a foundational lemma is critical by v1.3's definition; both reviewers and the settlement rated it high.

## 6. What stage 2 must now do for the plants

The plants exist only in the copy. In stage 2 the Professor applies the settlement to the working book **only where the working book has the same defect**. For P1, P2, P3, P4, P5, P6a, P6b and P7 the working book has the base text, so nothing is to be "repaired" there. **But the settlement's real findings on the same sections stay**, and the four lessons above apply to the stage-2 work itself.
