*Window reviewer W (Opus), steps 2-3 review report, returned as hand-back text, verbatim (saved by the Professor at 2026-10-23T16:50:58Z). sha256 of the hand-back text at receipt: `d8c9405ac2f3e7a94aff22cd6aab143ba6e0efce5cf49d72b0dc0da5ac5f0868` (UTF-8, the text below this line exactly).*

C24 STEP 2-3: REVIEW REPORT

*Re-read the saved inventory first. The file's header gives db39e97d…; I recomputed that sha256 on the text below the header line (stripped) and it matches (`c24w_hash.py`). Read: §8.17, CON-0231–0235 and their neighbours, the Ch. 8 index row, the step-8 file, C23 SETTLEMENT §2, and the homes §8.15 C5/C6/§8.15.3, §8.16 C6, §9.15 K1/L5/L6, §9.16 R2, §9.8.6. Not read: no Ch. 0–2 basis file. For W-07's premise only, I checked S0117 L158–177.*

## 1. Summary (10 lines)
1. §8.17 is faithful in most of what it quotes. But three of its five new concepts fail as stated: CON-0234's loss detector, CON-0235's "not by wording", and CON-0231's supersession clause.
2. The largest risk is C5/CON-0234. The dense-sequence detector fails in exactly the concurrent "take one side" case it is offered for. A small-scope check found 40 of 40 such cases undetected, and tail truncation is also undetected.
3. C2's "both cannot hold" is not shown. One consistent reading makes the estate coherent.
4. One lineage gap is missed: S0130's two selections appear in no registered act. S0129 shows the fields blank.
5. The book now contradicts itself: §9.8.6 item 7 still says R-CONFLICT is "NOT SELECTED", while S0130 adopts it.
6. The C23 rule (the authority's words first, every gloss labelled with its author) is met in C7. It is partly breached in C1, C3 and C9.
7. The adversarial pass tested the book more than the sources. It is inadequate on C1, C2, C5, C6, C7 and C9.
8. Depth: full on S0126 is defensible under the Professor's bar. Its theory yield is small and partly misread (W-06).
9. Two of my own step-1 notes are retracted: the "precedence of texts" tension (see W-06), and "humanActRef = an invariant with an owner" (see W-12).
10. There are no critical findings. Under R5 and entry 58, W-01 and W-02 are high: their only real dependants are open questions.

## 2. Findings

| W | Sev. | Type | Finding | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|---|
| W-01 | high | defect | CON-0234's claim "a dense sequence makes a dropped entry detectable" is false in the very scenario it is for. Two writers each append seq n+1; taking one side leaves 1..n+1 dense, with no gap. A truncated tail is not detected either. A predecessor chain (C5's "only if") fails the same two ways. | S0128 L48, L95–96, L161. `c24w_checks.py`: 40/40 take-one-side losses undetected; truncated chain still consistent | Restate: density detects only interior deletion, with seq unique across writers. A lost suffix or a dropped concurrent branch is detectable only against an external commitment (K1). Keep the merge limb. | C5, CON-0234 and §8.17.3 Q3 state the limit |
| W-02 | high | defect (missing premise, R5) | C2 "both cannot hold as stated" assumes that E10's history may include operation *before* establishment. If history is counted from establishment, Option A is a fixpoint that can never be satisfied, while L3, which comes after L2, can be reached. So the estate is consistent. There is a second ground the source gives for rejecting A: A would merge existence into authority. | S0127 L33, L132; check: A is never established under that reading, and established at t=K otherwise | Present it as a fork with two readings. Add the existence≠authority ground. | C2 names both readings; §8.17.3 Q1 rewritten |
| W-03 | high | omission (lineage) | S0130 records D1 = B′ and D2 = ADOPT "supplied by the human PO/ARB". The only registered second act left both blank. S0130 cites only "both acts". Under CON-0207, either a third, unregistered act exists, or the registrar supplied the selection. C9 presents the decision as "registered verbatim". | S0129 L157–158, L209–215; S0130 L5, L86 | Record the gap: the decision's basis is not on the record. This is a fifth instance of §8.16 C6's "basis outside the record". Drop "verbatim". | C9 and the Lineage note carry it |
| W-04 | high | defect | CON-0235 "force is not readable from wording". The same source reads force from form: a selection "delivered as an instruction … registered as decided", and blank fields as not selected. C7 cites those lines without seeing the conflict. | S0129 L157–166 | Restate: unambiguous form fixes force. Conditional or ambiguous wording is fixed only by the author, and the weaker reading is registered until then. | CON-0235 restated |
| W-05 | high | consistency | §9.8.6 item 7 still records R-CONFLICT as "NOT SELECTED". §9.8 is not listed among §8.17's homes, and item 6 already holds "History ≠ Reconstruction Guess". The step-8 file says no kernel section changed, but this one needs an update. | §9.8.6 items 6–7; S0130 L16, L28–29 | Update §9.8.6 item 7 (R3 check); cite §9.8 in C8/C9 | §9.8.6 and §8.17 agree |
| W-06 | high | defect + C23 rule | CON-0231's "a decided text can supersede its object" is misread. The decision chose the artifact's own §5.1 table, "which [is] correct", over the artifact's §1 summary sentence. That is CON-0232's rule inside one artifact, not a decision correcting its object. Also, L11 and L46 are the registrar's text, given as "the authority's words". | S0126 L46; S0122 L26 | Drop the clause. Join the case to C3 (the body governs its own summary). Label both lines as the registrar's. | CON-0231 and C1/C3 revised |
| W-07 | high | defect (earlier concept) | The CAP-01 counterexample in CON-0224 needs a premise: that CAP-01's recurrences are "independent governed acts", which is E6's definition. S0117 credits CAP-01's exclusion to E7, the counterfactual test; S0127 credits it to E6, which is a source defect. CON-0224's conclusion stands on S0117's own E6 and E7 rows. | S0117 L161–177; S0127 L30 | State the premise, or rest the claim on E6 "necessary" plus E7 "discriminates". Record S0127 L30 as a source defect. | CON-0224 and C2 bullet 3 |
| W-08 | normal | defect | C4: "a lost amendment … restores the wider" standing generalises from its example. A lost widening narrows standing. "Lost first" adds an order of loss that the source does not claim: the source names the layer that cannot be reconstructed, not an order. "Measured, not argued" repeats the interested producer's own label; the figures are reported, not shown. | S0128 L51, L121 | Write "reverts to what the remaining entries give, in either direction". Write "unreconstructable", not "lost first". Label the figures "reported". | C4 and CON-0233 |
| W-09 | normal | defect | C6: L6 is not "unmet". With almost no act times, L6 is vacuous. What is missing is an input (neither true nor false), so CE* cannot order the acts. | S0128 L49 | Write "the input is absent", not "false" | C6 |
| W-10 | normal | defect | C9: the decision reserves location and technology, so "versioned, tracked history" goes beyond it. Git history in the same repository can be rewritten, so it is not an external anchor without a commitment held elsewhere. | S0130 L82–84 | Make it conditional | C9 |
| W-11 | normal | language | The label R-CONFLICT covers two propositions: the proposed one (with the seq-gap clause) and the adopted one (no gap clause; "reconstruction capability"). "CON-0234 is now adopted" identifies them by label, against CON-0219. D2 means two things inside §8.17 (C-10 existence and R-CONFLICT). | S0128 L161; S0130 L28–29 | Qualify both | C9, register |
| W-12 | normal | defect | C8: the engine enforces only that a reference is present. That the reference is not self-reference is a norm, so "a mechanical form of L5" overstates. "CON-0234 and CON-0214 (b) are one practice": the erratum precedent is (a). | S0129 L147–149, L188 | "a mechanical necessary condition"; map to (a) and (b) | C8 |
| W-13 | normal | omission | The evidence strengthens with each source. S0128 L83: no loss asserted. S0129 L187: "C's failure mode, dated". S0130 L44, L54: "observed failure", lineage "cannot be reconstructed" — while S0128 L51 gives 100%/83% reconstructable. What was shown is that the record was untracked, not lost. B's rejection reason also changed (S0128 L104–108 vs S0130 L53). | as cited | Record it; keep C4's balance bullet | C4 or C9 |
| W-14 | normal | defect | C1 says correctness was "checked only by the window reviews". That is a universal claim drawn from a search. | S0126 | Delete or scope | C1 |

## 3. Concept verdicts

| Concept | Verdict | Reason · scope |
|---|---|---|
| CON-0231 | PROVISIONAL | "Adopted ≠ verified" holds by definition. The supersession clause is defeated (W-06) |
| CON-0232 | VALIDATED | The authority's own words (S0127 L119). Scope: which text governs a decision. One lineage |
| CON-0233 | PROVISIONAL | One estate, an interested producer, figures not reproduced. Wording (W-08) |
| CON-0234 | INVALID as stated | The detector limb fails in its own scenario (W-01). The no-side and merge limbs hold |
| CON-0235 | INVALID as stated | A counterexample in the same source (W-04) |
| C2 tension | not demonstrated | A consistent model exists (W-02) |

## 4. Brief questions

**The C23 rule:**

| Claim | Result |
|---|---|
| C1 | Partly breached: the L11 and L46 registrar text is unlabelled (W-06) |
| C2 | Met ("as registered") |
| C3 | The bullet-1 framing at L39 is the registrar's and unlabelled; minor |
| C7 | Met: the author's own words at L87 come first |
| C9 | "Verbatim" is unsupported, and the act is missing (W-03) |

**C2.** The tension is not real as stated (W-02).

**C4.** It is stated correctly only for a narrowing amendment, which is what the register row says. The book text overgeneralises (W-08).

**Refutation adequacy:**

| Claim | Judgement | Reason |
|---|---|---|
| C1 | inadequate | Missed the rival basis (the authority's own "no architectural impact" judgement, S0126 L41) and the misread |
| C2 | inadequate | Missed the history-origin reading |
| C3 | adequate | — |
| C4 | adequate as an attack | The correction is not carried into the text |
| C5 | inadequate | Tested the book's merge, not the source's detector |
| C6 | inadequate | Treats absent as false |
| C7 | inadequate | The same-source counterexample was missed |
| C8 | adequate | — |
| C9 | inadequate | Rewritable git; the lineage gap |

**R4.** There are no Ded steps in §8.17, so R4 is not applicable.

**Six questions:**

| # | Question | Answer |
|---|---|---|
| 1 | Order | W-05 |
| 2 | Mathematics | W-01, W-02 |
| 3 | Statistics | n = 1 estate. "Overwhelmingly" is unquantified. The 83% is one snapshot taken by an interested party (W-08) |
| 4 | Computation | small-scope checks, below |
| 5 | Clarity | W-11 |
| 6 | Coverage | below |

**Depth:**

| Source | Professor | W | Comment |
|---|---|---|---|
| S0126 | full | skim | Full is defensible under "every distinction already in the book". Of the two new points, one restates CON-0229 + S0125, and the other is misread (W-06) |
| S0127–S0130 | full | full | — |

[I] The bar "skim only if every distinction is already in the book" leaves almost no registration surface skimmable. That is not too low, but it makes the §3 safeguard idle. The missed points cluster in the fastest-read sources (S0130 at 16 s; S0129 L164). That is a signal, not evidence.

**C23 settlement spot-check:** W-1 (the CON-0230 row), W-3 (§8.16 C4, CON-0222) and W-9 (§8.16 C6) are met.

## 5. Coverage (from my inventory)

| Status | Points |
|---|---|
| COVERED | adoption ≠ existence; L2 flip; levels; summary ≠ object; existence≠authority≠maturity; verifier open; classification finding; inversion; amendment layer; projection, not source; import time; position vs decision; humanActRef; additive practice; D2 binds D1; target ≠ state |
| COVERED earlier | conditional REQUIRED (§8.16 L81); trigger, observer and authority (CON-0216); four evidence dimensions (CON-0217) |
| DISTORTED | the S0126 supersession (W-06); the seq detector (W-01); "lost first" (W-08) |
| MISSING, ranked | (1) the S0130 lineage gap (W-03); (2) the risk→failure escalation (W-13); (3) E6≠E10 as a condition for the levels to be distinct (S0127 L88, L143); (4) graded criteria, identified vs exercised (L25–27); (5) evidence that is REPRODUCIBLE only while it exists (S0128 L135); (6) corroboration by a different query: maturity=adopted, exit 0, vs the escalated maturity=research, exit 2 (S0129 L177 vs S0128 L10–11), an instance of CON-0229; (7) relocation, not duplication: one representation (S0130 L67); (8) refusal as evidence, credited by the refuser (S0129 L117–119); (9) "independent" used in three senses |
| JUSTIFIABLY EXCLUDED | field model (§8.16); placement procedure; engine default; option proportionality |

**Source defects (separate):**
- S0127 L30 credits CAP-01's exclusion to E6; S0117 credits E7.
- S0128 L48/L96: the detector fails at the tail and for concurrent appends.
- S0130 L68 (30 grants) vs S0128 L51 (99 grants) is unexplained.
- S0128 L43 (`.gitignore:32`) vs S0129/S0130 (lines 25 and 32).
- S0130 L54 vs S0128 L51.
- S0129 L162 "DECIDED" vs S0130 L33 "OQ-2 remains open".

## 6. Next steps
1. Correct W-01 and W-02, then run R3 on CON-0234 and CON-0224. Done when the inbound rows are reassessed.
2. Correct W-03, W-04 and W-06. Done when CON-0231 and CON-0235 are restated.
3. Correct W-05 (§9.8.6).
4. Then the normal findings W-07 to W-14.

No question for the Principal.

## 7. Limits
- The checks (`c24w_checks.py`, `c24w_hash.py` in the scratchpad) are small finite models, not proofs.
- I did not read §9.13 in full, or the THEORY-MAP beyond two rows.
- I did not reproduce S0128's measurements.

**Independence:** process independence, not personal independence.
