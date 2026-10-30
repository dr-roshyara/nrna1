# Planted-gap test, consolidation S0226–S0252: result

*Governance Engineer (session `57ddce41…`), 2026-10-30, after stage 1 (`018569cac`). Rulebook v1.4 §12; mandate §8.5.*

## Verification

- **Key** `PLANTED-GAP-ANSWER-KEY.md`: sha256 `7cd6e745198f615b04b44214a495c7bbc379b494ffa7d2e2dc201f31a4c4c374`; **manifest** `PLANTED-GAP-COPY-MANIFEST.txt`: `b3b6396b65a1953c76897a8408f5f30074749fa4f5a40edbb9f56faabab47c1c`. Both equal the access record (entry 2026-10-30T15:44:39Z); released here unedited. *(The key's preamble says "four files differ"; it is three, two plants being in 10.02. Corrected in the access record, entry of 16:05:52Z.)*
- **Reports** A, B and the integration report each match their saved sha256 (`sha256sum -c`).
- **The copy** still matches the manifest (172/172) at scoring.
- **The preparation check** (§8.3a): PASS, but run **after** the reviewers had started (research record addendum 2026-10-30T16:02:43Z). It found no deviation, so the late check changes nothing in the score.

## Blindness

| Reviewer | Status | Evidence |
|---|---|---|
| A (Opus; Ch. 10) | **blind** | its file list: the copy, REVIEWER-INSTRUCTIONS, the briefs; "no file in REVIEWER-NOTE item 4 was reached"; the Professor's access note |
| B (Sonnet; §8.39–§8.45 and passages) | **blind** | its file list: the copy, REVIEWER-INSTRUCTIONS, allowed corpus sources, `git log` times of two corpus files (not manuscript history); the Professor's access note |
| Integration (Opus) | **blind** | its file list: the copy, REVIEWER-INSTRUCTIONS, the briefs and reports A, B; "never opened … 00-access/ … any git history" |

No reviewer opened the files outside the reviewers' folder that name the test (the Governance Engineer's state file, the research record, the access record). **Blind and combined scores are the same.**

## Score (counts; four items from four plants)

| Item | A | B | Integration | Treatment in stage 1 | Result |
|---|---|---|---|---|---|
| **Q1** "sluice" in 10.02 L100 (ordinary undefined word) | **yes** (A-13; R2 table "sluice: defect") | — (not in B's scope) | confirmed (book_search: one occurrence) | **correct:** "replaced or defined" | **found** |
| **Q2** §8.43 C6 removed, with its homes-table row | — | **yes** (B-02, high): **"the content is missing, not a stale pointer"**, sourced from S0243–S0245 | confirmed, and added the fourth pointer §8.44 L24 (N-01); "write C6 recommended" | **correct:** write C6 (Src; Int) from S0243/S0241/S0244/S0245; the four pointers stay true | **found and correctly diagnosed** |
| **Q3** refuted(k, t) reverted to "whatever support of k is recorded" | **yes** (A-01, high): both refuted and contested; against g1 and the change log | — | confirmed (merged with A-12) | **correct:** "…and no support of k is live" restored | **found** |
| **Q4** S0248 quote inverted to "Kernel, not mechanism" | — | **yes** (B-01, high), against S0248 L411/L1194, by hand | confirmed; also the contradiction with L68 | **correct:** quote "Mechanism, not kernel" / "Not kernel" | **found** |

**Blind: 4 of 4. Combined: 4 of 4.** Earlier tests: S0100 4 of 8; S0150 3 of 5; S0200 2 of 4.

## What the test answers

1. **Q1, an ordinary undefined word: found for the first time** (missed in all four earlier tests). The in-scope R2 list (decision D2) put "sluice" in front of the reviewer, who judged it. *Limit:* one plant, one reviewer.
2. **Q2, a removed claim: found and diagnosed as missing content**, not as an index error (decision D3). At S0200 the same class was misdiagnosed and its only trace would have been deleted.
3. **Q3, a decided rule reverted in a rotated chapter: found.** The reviewer read the definition against its neighbour, the decided rule and the change log.
4. **Q4, an inverted quote: found by hand only.** `corpus_quote_check.py` reads no "(L…)" citations, so it checked nothing in this scope (B-12; integration confirmed). Hand checking worked here; the tool gives no assurance for this citation style.

## Instructions for stage 2 (the working book) — the stage-2 guard

These stage-1 remedies repair plants. **Do not apply them to the working book:**
- **A-13, the "sluice" half only.** The working book has no "sluice". The "part 2" half rests on its own evidence and is assessed against the working book.
- **B-02 + N-01: do not write a new §8.43 C6.** The working book's §8.43 has C6 and its homes-table row; the four pointers are right. *(The stage-1 remedy describes the content the working book already holds; compare, do not duplicate.)*
- **A-01, the restoration of "and no support of k is live".** The working book's §10.2.3 L195 already reads so. **A-12's other parts** ("live" as §9.18.5 L193; the pointer to g1's case list; premise (iv) marked decided; §9.18 L24 "contested") rest on their own evidence and are assessed against the working book.
- **B-01: no change.** The working book's §8.44 C3 quotes "Mechanism, not kernel".
- **R3 lines of these four:** dispositions that assume the planted text (e.g. "10.99 L18: T once the text is fixed") need no action in the working book.
- **Every other stage-1 remedy** rests on its own evidence and is assessed against the working book as planned.

## Proposals (for the Principal; nothing decided)

- **P1 (tool):** extend `corpus_quote_check.py` to "(L…)" and "Sxxxx Lnn" citations, or state its coverage in REVIEWER-NOTE (B-12, confirmed by the integration review). The Professor owns the tool.
- **P2 (commissions, for the next test):** forbid `03-methodology/` files other than `REVIEWER-INSTRUCTIONS.md` and the decision records, which name the test. No reviewer opened them this time; the preparation check noted the gap.
- **P3 (process):** the Professor starts reviewers only after the Principal confirms the §8.3a PASS, not on the Governance Engineer's report alone.

## Cost (the Professor's figures, reported)

Review 2,332.5 s (38 min 53 s) for A, B and the integration review, against 3,770 s for S0201–S0225. Confirmed defects in the rotated chapter: 14 in Ch. 10.
