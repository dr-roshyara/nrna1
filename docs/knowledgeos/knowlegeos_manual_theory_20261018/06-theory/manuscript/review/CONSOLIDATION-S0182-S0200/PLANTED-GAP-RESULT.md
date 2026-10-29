# Planted-gap test, consolidation S0182–S0200: result

*Governance Engineer, 2026-10-29, after stage 1 and its addendum (commits 8faab7396, 1d10e28da). Rulebook v1.4 §12.*

## Verification

- **Key** `PLANTED-GAP-ANSWER-KEY.md`: sha256 `66f6fe0f0a0f692e0c8bab4f8b912f2de3c98b0d197bf5c5d24fa9495f2f0d9b`, equal to the ACCESS-RECORD entry of 2026-10-28T16:44:46Z. **Manifest** `PLANTED-GAP-COPY-MANIFEST.txt`: `43dbced284635594421731d4a5594599ba86427dad2ae5c8d3c14b08370df99f`, equal. Both released here, unedited. *(The key's header date "2026-10-29" is an error; it was prepared 2026-10-28.)*
- **Reports** A, B, C and the integration report: each matches its saved sha256.
- **The copy:** unedited since preparation (the Professor's git check, stage 1).

## Blindness

| Reviewer | Status | Evidence |
|---|---|---|
| A (Opus) | **aware** that a test exists: read `README-PROFESSOR.md` (disclosed, "I did not use it"); one grep for "allowed" in `00-access/ACCESS-RECORD.md`; no working-book file | ACCESS-NOTE; A's file list |
| B (Sonnet) | **blind** | ACCESS-NOTE |
| Integration (Opus) | **aware** through REPORT-A's file list, which records the disclosure; one line count on `00-access/` | its file list |
| C (Opus, §8.07–§8.26) | **blind**; its scope contains no plant | the Professor's tool-call audit |

**Cause.** `README-PROFESSOR.md`, which names the test, was placed by the Governance Engineer in the folder the reviewers were sent to; the brief did not list the files reviewers could open (the Professor's). Both are fixed for future tests (P3 below; commission C already lists its files).

**How A counts.** Reported both ways, as the decision record requires for exposure: the **blind score (B)** and the **combined score**. They are equal here, so the question does not change the result.

## Score (counts; four items from three plants)

| Item | A (aware) | B (blind) | Integration | Treatment in stage 1 | Result |
|---|---|---|---|---|---|
| **Q1** "keel" in 06.03 C1 heading (ordinary undefined word) | no | no — R2's 687 lines not judged (B says so) | no | none | **missed.** R2 listed it in tier A (used once, absent from the corpus) and tier D |
| **Q2** §8.29 C7 removed, with its table row | no — S0196 AP-1 marked MISSING but not traced to §8.29 | **trace only:** B-12 saw the §8.00 index cite a "C7" that §8.29 lacks, and judged it "a draft claim dropped, index not updated" | confirmed B's reading (I-26) | **wrong:** delete the index clause, which would erase the only trace | **not found; misdiagnosed** |
| **Q3a** §6.7 C1 inverted against S0089 L81 | **yes** (A-01, high) | **yes** (B-01, high) | yes (I-01) | **correct:** restore "not a governed artifact … non-registration, not non-existence" | **found** |
| **Q3b** THEORY-MAP L242 left stale | **yes** (R3 line: "correct; no change") | **yes** (R3 line: "consistent with the correction") | yes ("stays true") | **correct** once Q3a is restored; the present mismatch is implied, not named | **found** |

**Blind: 2 of 4. Combined: 2 of 4.** One further trace was seen and misdiagnosed. Earlier tests: S0100 4 of 8; S0150 3 of 5.

## What the test answers

1. **R2 tier D (Q1): the tool works; the review does not use it.** "keel" was listed among 687 candidate lines; tier A alone has 67 words, all absent from the corpus. Restricted to the sections in scope, tier A has four (keel, rater, workbenche, unforgeable). The class "ordinary word as undefined term" has now been missed in every test.
2. **Coverage against the window inventory (Q2): did not find a removed claim.** The only reviewer who saw a trace explained it as an index error, and the integration review confirmed that reading. A reference to a missing claim was not checked as a possible loss of content.
3. **R3 line by line (Q3b): worked for the first time.** The stale citation elsewhere, missed in every earlier test, was listed and correctly dispositioned by all three reviewers.

## Instructions for stage 2 (the working book)

- **I-26 (Q2) is not applied.** The working book's §8.29 has C7; its index row is right.
- **I-01 (Q3a): no change.** The working book's §6.7 C1 already reads correctly.
- **Q1: nothing to do.** The working book has no "keel".
- **THEORY-MAP L242: no change.**
- Every other stage-1 correction (integration report and addendum C) rests on its own evidence and is assessed against the working book as planned.

## Proposals (for the Principal; nothing decided)

- **P1 (tool):** R2 prints first the candidates in the sections under review (`--scope`), each to be judged by the reviewer; the whole-book list stays as background.
- **P2 (review instruction):** a reference to a claim that does not exist is checked against the window's inventory and sources ("missing content or stale pointer?") before it is treated as an index error.
- **P3 (test preparation):** no file that mentions the test is placed in a folder reviewers are sent to; every commission lists the files it may open.

## Cost (the Professor's figures, reported)

Review 3,915.5 s (65 min 16 s) for A, B, integration and C, against about 22,078 s (≈ 6 h) for S0151–S0181. Defects found in the rotated chapters: 4 in Ch. 6 and 41 in Ch. 8.
