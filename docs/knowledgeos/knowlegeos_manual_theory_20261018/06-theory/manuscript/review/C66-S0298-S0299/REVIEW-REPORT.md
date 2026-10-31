C66 STEP 2-3: REVIEW REPORT

Window C66 · reviewer W · transition review of §8.59 (C1–C5), together with the C65 settlement `2f9c57114` and the latest commit touching §8.59, `0e2b0c6e0`. I re-read my saved inventory first; `STEP1-INVENTORY.md` has sha256 `36022bf6…f3ed`, which matches `SHA256-STEP1.txt`.

**Process independence:** independence is of process, not of person. I saw §8.59 only after my inventory was saved. One of my own step-1 judgements was wrong, and the draft took it over (W-02).

## Summary

1. **The C65 settlement is validated as applied.** Rows W-01 to W-09 match the restated §8.58, the R3 note at §8.50 C2, the annotation on the C64 W-03 row, index row 8.58 and the change log. I checked §8.58 PL1, PL3, PL4 and PL5 by hand. Two minor points:
   - the settlement was committed with placeholders, which were filled one commit later in `0e2b0c6e0`;
   - §8.58 C1 cites §8.61 C3, which is an unsettled draft, for the mtime caveat. I suggested that citation myself at C65.
2. **There are no critical findings and five high ones:**
   - C1 misreads its homes: the book's tombstone changes an entry in place, and the claim also mixes entry identity with item identity (W-01).
   - C5's "next actor, in tension" is resolved by the line's own records, S0253 and S0296. My step-1 judgement there was overdrawn (W-02).
   - C3's "open point" was judged inside the line by S0253 (W-03).
   - C4 maps the line's check before the switch onto the book's check before retirement, a mapping §8.46.5 already rejected, and it misses CON-0243 (W-04).
   - §8.60 L58 cites §8.59 C2 for a point that is in my inventory but not in §8.59 (W-05).
3. **These parts hold:** the 11 + 1 + 2 ≠ 13 deduction, which I checked in S0253 itself (L204); the three objects on one carrier; the count of three in Premise 2 being the repair's reading, which I checked in S0300 L806–815; and scope by finding, not by file, which I checked in S0296 L20 and L24.

## Findings

Labels: V = I verified it in the text; Int = my interpretation.

| W-nn | Finding | Sev. | Evidence | Correction | Resolved when |
|---|---|---|---|---|---|
| W-01 | **C1's book reading is misstated.** (1) "An entry does not change after it is appended" is not the book's rule in that absolute form. "A tombstone changes an earlier entry in place", cited by a recorded removal act (§9.13.2 L59–62; §9.8 F30). Neither §8.39 C3 (a) nor §8.35 C1 contains the sentence. (2) The tombstone **drops** content; it does not keep the old text "as an entry". So "agreement in purpose" through the tombstone is inverted. (3) "One document under one path over two versions" is a question of **item** identity over versions, which is open (§8.39 C3 (b); T9-14), not of entry identity. That is a known pattern. (4) PL1 states "two entries" as certain. | high | V: §9.13 L59–62; §9.8 L115; §8.39 L72–73; §8.35 L42–47 | Restate. In the book, content changes only by a tombstone, under a recorded act it cites, and the tombstone drops content; a revision is a new entry. The repaired summary is neither: it is an in-place change that keeps the old text, with labels naming the act, and the act's record kept elsewhere (S0299 under S0296). Give two readings: (a) two entries; (b) one item over versions, which is open. Fix PL1 and the index row. | C1, PL1, the index row; R3 at §8.60 L50 and L17 |
| W-02 | **C5's tension is not there.** S0298's routing (L383–393) concerns the DV correction itself. The fresh independent review it requires is S0253, whose subject is that correction at `2f0301c2` (S0253 L8). S0253 routes the RV repairs Architecture → Governance bounded → PO/ARB (L262–275). S0296 registers the same sequence (L33, which is the record's sentence; §8.57 C4 gives two readings of it). S0299 L169 is almost word for word S0296 L41, a lineage the draft does not disclose. Mapping slip: the C5 challenge's rival ("applied to the correction, not its repair") is not reading (a) ("wording and measurement only"), although the answer equates them. §8.61 C5's proposed R3 inherits the slip. What remains open is narrower: whether RV-1's added row needs a fresh technical review. S0296 L41 and S0299 L169 leave that route optional. | high | V: S0253 L8, L262–275; S0296 L33, L41; S0299 L169, L176–181 | Withdraw "in tension". State that S0298's required step was taken by S0253, and cite S0253's and S0296's routing. Keep the residual question about RV-1's row as one reading. Fix the challenge's mapping. Correct §8.61 C5's R3 so it names the rival, not reading (a). | C5, the index row, the challenge; §8.61 L64 |
| W-03 | **"A row, not a rule" is not "the line's own open point".** S0298 L271 defers the question to the independent reviewer. That reviewer, S0253, judges "the receipt placement (outcome A) is CORRECT" (L3) and calls the RV-1 row "inside the current envelope" (L190). Inside the line, reading (a) is the reviewer's judgement. It is in-lineage and advisory, not an act. Whether a new row extends the rule can remain the **book's** question. | high | V: S0253 L3, L190; S0298 L271 | Cite S0253 L3 and L190. Restate the point as "judged (a) by the line's reviewer, not accepted; open for the book". Fix PL3 and the index row. | C3, PL3, the index row, the challenge |
| W-04 | **C4 maps onto the wrong stage and misses a rule.** (1) 3(iii-b) is a check **before the switch**. §8.46 C2 as settled (W-01; §8.46.5) records this as a check the book lacks; it does not play the part of the book's gate before retirement (CON-0265). C4 uses CON-0265 as its analogy, which is the same stage mismatch. (2) CON-0265 does not contain the words "fixed before the check it constrains". (3) CON-0243 does contain a rule: "The anchor must be taken again at the operation it protects." On the premise that a new attempt is a new operation, that answers C4's question for the book's own gate. So "the book has no rule on it" does not hold as stated, and the TODO belongs under §8.46 C2's existing open topic (a check before designation). (4) My inventory gives a reason as well as a rule: on a FAIL, the failure record lands in the source after closure (S0298 L268; S0300 L795). The aborted run's object is therefore stale by construction. | high | V: §8.46 L54, L116; CON-0243, CON-0265 rows; S0300 L795 | Restate C4: give no CON-0265 analogy; cite CON-0243; file the TODO under §8.46 C2's topic; add the stale-by-construction reason. §8.20 C6 is listed among the homes but not used: use it or drop it. | C4, homes, STEP8 dependencies |
| W-05 | **A pointer with no content.** §8.60 L58 cites "W's observation … (S0298 L353; §8.59 C2)", but §8.59 C2 does not carry that observation. Under the instructions, this is content missing from §8.59, not a stale pointer. The observation is my inventory item on L353. Tests T3 and T11 treat a whole line as superseded if it carries a superseded label. The RV-5 and RV-6 repairs put current and superseded wording on one line (L153, L330), and S0300 L1043 has the same shape. That answers C5's open question: the re-specification is a loosening, and the source shows where. | high | V: §8.60 L58; S0298 L153, L330, L353; STEP1-INVENTORY L85 | Add it to C2, or to C5's tests point, with the L1043 link to §8.60 C3. | C2/C5; §8.60 L58 resolves |
| W-06 | **The §10 site was named but not repaired.** S0253 gives RV-5's sites as "summary §3 · §10" (L204), and S0299 repeats this (L155), yet repairs §3 and Traceability only (L112–113). The draft's "narrower than L153, not false" leaves out that a site named in the finding was left unrepaired, a miss the bounded check in §8.61 C5 also does not catch. | normal | V: S0253 L204; S0299 L112–113, L155; S0298 L317 | Add to C2. | C2 |
| W-07 | **C2's sources.** (1) S0253 is allowed and already read (§8.46). Cite S0253 L204 directly, not "as S0299 quotes it". The quotation matches, and "11" occurs twice there, so it is not a slip of one figure. (2) S0298 L319, cited for "the review is NOT modified", concerns the AMD3–AMD6 reviews. S0253 did not yet exist then; only S0299 L157 covers it. (3) T18 tested five properties (L351). "Itself clean" names more than those five, and its ✅ is now shown in a text it never ran on (the 405-line version). | normal | V: S0253 L204, L280; S0298 L319, L351 | Correct the citations; scope the T18 point. | C2 |
| W-08 | **C3 details.** (1) "Carries the other three" implies four objects; it should be "the three". (2) A1 does not say "an entry's standing covers what it records" (§1.6 L14); claim-scope is §8.46 C2's (L52). (3) S0300's Premise 2 names one instance, "a reconciliation is NOT a silent act" (L810). So "names none" holds for S0298 only. | normal | V: §1.6 L14; S0300 L806–815 | Fix the wording, the home and the citation. | C3 |
| W-09 | **Records.** STEP8 L3 still says the C62–C65 settlements are "pending"; the same miss was found at C65 (W-08). "No earlier use in the book" holds, but the later citations at §8.60 L55, L58 and §8.61 L7, L64 are not disclosed. S0299 L186 credits "Repair … only, produce evidence, stop" to the act, but in S0296 it stands outside the verbatim block (L26; §8.57 C4 as settled). This is the pattern of taking a record's own sentence for the quoted act. | normal | V | Update STEP8; disclose the later citations; add the attribution point to C5. | records, C5 |
| W-10 | **The recorded challenges are partly inadequate.** C1's answer rests on the tombstone (W-01). C3's answer ignores S0253 (W-03). C5's answer mismaps its rival (W-02). There is no challenge for C4; its rival is that CON-0243 already answers it. | normal | V: §8.59 L83–90 | Restate. | §8.59.4 |

## Claim verdicts

| Claim | Verdict | Reason (scope) |
|---|---|---|
| C1 | PROVISIONAL | "Two versions under one path; the header names the first producer" is VALIDATED (S0253 L8; S0298 L14; S0299 L47). "Two entries in the book's terms" is INVALID as stated (W-01). |
| C2 | PROVISIONAL | The re-measure and the 11 + 1 + 2 ≠ 13 deduction are VALIDATED on S0253 itself. The T18 and L317 points are incomplete (W-05 to W-07). |
| C3 | PROVISIONAL | Three objects on one carrier and the receipt's claim scope are VALIDATED. Premise 2's count is VALIDATED as the repair's reading (W-08 refines it). "The line's own open point" is INVALID as stated (W-03). |
| C4 | PROVISIONAL | The rule that the object belongs to one run is VALIDATED from S0299 L146. The CON-0265 analogy and "the book has no rule" are INVALID as stated (W-04). |
| C5 | PROVISIONAL | No closure by the producer, scope by finding, and non-vacuity are VALIDATED. "Next actor in tension" is INVALID as stated (W-02). |
| C65 settlement | VALIDATED as applied | — |

## Coverage (my inventory against §8.59)

- **COVERED:** S0298 items 2, 3, 5, 7, 8, 9, 11, 13 and 14 (through §8.20, §8.27, §8.46 and §8.60, as §8.59.3 states); item 12; S0299 items 1, 3, 6, 7, 8, 9 and 10.
- **JUSTIFIABLY EXCLUDED:** item 1, Premise 2 (a sketch, at §8.60 C5); item 4 (slot discipline); S0299 items 2, 4 and 5 (RV-2 at §8.60 C3).
- **MISSING:**
  - the L353 line rule (W-05);
  - the failure record after closure (W-04);
  - item 6, the DV-1/DV-2 coupling: "the right moment against an incomplete object" has no home. It is a coverage-and-timing pair that bears on CON-0243.
  - item 15, the list of DDD principles: no use of its own (low).
- **DISTORTED:** the L317 judgement (W-06); S0299 item 11 and my own routing judgement (W-02).
- **New topic:** none beyond §8.46 C2's open topic, a check before designation. C4's TODO belongs there.
- **Checks:**
  - **R1:** 241 homes, 1 flag (CON-0133, pre-existing).
  - **R2 (`--scope 08.59`):** tier A 0. I judged all 8 tier-D candidates. "enumeration" is the source's term and is glossed in C3 ("what the copy must equal"); keep that gloss wherever it is used. "governance-state-durability" is the line's name. "passed", "comparison", "next", "reviewer", "agree" and "rival" are ordinary words or the book's own.
  - **R3:** the claim-level citers are §8.60 L17, L18 and L50 (C1); §8.60 L58 and §8.61 L20 and L63 (C2); §8.61 L64 (C5).

## Next steps (in dependency order)

1. W-04: restate C4, then the TODO topic.
2. W-01: restate C1, then R3 at §8.60 C2.
3. W-02: restate C5, then correct §8.61 C5's R3.
4. W-03, then W-05 (which resolves the §8.60 L58 pointer), then W-06 to W-10.

## Limits

- I read S0253 only at L1–12, L190–212, L255–279 and its grep hits. I read S0300 only at L790–830.
- I did not open S0302 or the `.claude/scripts/` folder. Whether the folder changed between the review and the repair is still undecided.
- I did not use git on the corpus files to separate the 403-line and 405-line versions.
- I read §8.46 at L1–60 and L104–120, and the other homes through grep hits only.
- `git show 2f9c57114` also displayed one changed row of the C64 settlement, which is part of that commit; I did not open the C64 folder.

## Files opened (W = docs/knowledgeos/knowlegeos_manual_theory_20261018)

- **C66 review folder:** BRIEF-W-STEP2.md, STEP8-AND-CHECKS.md, STEP1-INVENTORY.md (checked by sha256 and re-read), SHA256-STEP1.txt.
- **C65 review folder:** SETTLEMENT.md, REVIEW-REPORT.md.
- **Chapter 8:** 08.59 (whole), 08.60 (whole), 08.61 (whole); 08.46 (L1–60, L104–120); grep hits in 08.19, 08.20, 08.27, 08.35, 08.36, 08.39, 08.42, 08.57.
- **Chapters 9 and 1:** grep hits in 09.08, 09.13 and 09.17; 01.06 at L12–20.
- **Register and map:** CONCEPT-REGISTER.tsv (the CON-0243 and CON-0265 rows, plus a grep); THEORY-MAP.md (grep).
- **Corpus**, each path checked on CORPUS-ALLOWED.tsv first (rows 59, 62 and 1590):
  - S0296: whole;
  - S0253 `docs/knowledgeos/reviews/KOS-AIP-GOV-STATE-DURABILITY-DV-CORRECTION-INDEPENDENT-REVIEW.md`: the ranges listed under Limits, plus a grep;
  - S0300: L790–830;
  - S0298 and S0299: from step 1.
- **Scratch files**, in `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/`:
  - c66-w-c65.txt
  - c66-w-r2.txt

## Commands run

- `ls` of the C65 and C66 folders, the manuscript, chapter_08, chapter_09 and tmp/tools; `sha256sum` of the inventory; `cat SHA256-STEP1.txt`; `wc -l` on §8.59, STEP8, S0253, S0296 and S0300.
- Git:
  - `git log --oneline -5 -- 08.59*`;
  - `git show --stat 2f9c57114` and `git show 2f9c57114 -- 06-theory/` (without REVIEW-REPORT; saved to c66-w-c65.txt);
  - `git show 0e2b0c6e0 --stat` and its chapter_08 diff.
- `grep -nP` on CORPUS-ALLOWED.tsv for S0253, S0296 and S0300.
- `sed`/`grep` on S0253, S0296 and S0300 (the ranges above).
- Searches limited to the chapter folders, the register and the map: `grep` for S0298, S0299 and "correction summary"; for append-only, "in place" and "does not change"; for "taken again" and "re-attempt"; and claim headers in §8.20, §8.27, §8.36, §8.39, §8.42, §8.57.
- `awk` on CONCEPT-REGISTER.tsv for CON-0243 and CON-0265.
- `python3 -I tmp/tools/register_home_check.py --root W`.
- `python3 -I tmp/tools/undefined_terms.py --root W` (whole book, once by mistake), then the same with `--scope 08.59` (saved to c66-w-r2.txt).
- `python3 -I tmp/tools/inbound_citations.py --root W` for "§8.59 C1" … "§8.59 C5" and "§8.59".

Nothing was edited, and nothing was sent outside.
