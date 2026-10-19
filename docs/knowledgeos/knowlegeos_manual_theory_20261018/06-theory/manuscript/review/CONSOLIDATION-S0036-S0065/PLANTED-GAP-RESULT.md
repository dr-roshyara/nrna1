# Planted-gap test, consolidation review S0036–S0065: result

*Scored 2026-10-19 by the Rule Reviewer session against the sealed key (sha256 `c1744852…5c7077`, unchanged at scoring). Counts only; seven chosen plants are not a random sample.*

## Score

| Plant | Reviewer A (Opus) | Reviewer B (Sonnet) |
|---|---|---|
| D1 §8.6 C1: "full model separation" (source: NIL) | found (CF-2) | found by reading (C-2) |
| D2 §9.2 C2: reusability "by inspection" (source: "until used once") | found (CF-1) | found by reading, as critical (C-1) |
| O1 §8.6 C6: automation never manufactures authority | found (high) | found, via the chapter copy |
| O2 §8.6 C3: transcript search is self-contaminating | found | found only by diff (non-blind) |
| O3 §6.4 C6: a name does not make a mechanism work | found | found only by diff (non-blind) |
| O4 §6.4 C1: the criteria are not independent | found (high) | found only by diff (non-blind) |
| O5 §9.2 C2: the litmus tests product-independence, not placement | **missed** | **missed**, even with the diff |

## Validity: the test was partly compromised

- **The originals were reachable.** The chapter files of the three sections stayed in the book. Reviewer B compared copies and chapter files by `diff`, and says so. Reviewer A did not diff, but `book_search.py` searched the chapter files, so its hits for removed text came from the originals. A notes that the copies "omit three load-bearing points that the frozen chapter files still carry".
- **So the omission scores are not blind.** Only the two distortions were found by reading against the sources, by both reviewers: **4 of 4 blind detections of distortions.**
- **O5 was missed by both**, even with a diff: a whole claim removed and the following claim renumbered was not reported.
- **The Professor's own plant** ("lane", "BC-7" undefined): neither report names them as undefined terms. Reviewer B flags BC-7's invariants as used unqualified (C-4), which is related but not the same.

## Lessons for the next test

1. The reviewers must not reach the originals: the commission says the copies are the versions under review, tells them not to read the chapter files of those sections or compare files, and the book search excludes them (or searches the copies).
2. A removed whole claim with renumbering is hard to detect, even for a reviewer comparing files: keep such plants, they test what matters.
3. Across both tests so far: distortions found by reading 6 of 6 (C10: 2 of 2; here 4 of 4); omissions found blind 4 of 5 in C10, not measurable here.
