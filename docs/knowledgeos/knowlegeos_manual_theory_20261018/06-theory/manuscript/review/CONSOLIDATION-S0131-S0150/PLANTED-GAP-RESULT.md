# Planted-gap test, consolidation S0131–S0150: result

*Scored 2026-10-24 by the Rule Reviewer against the sealed key (`PLANTED-GAP-ANSWER-KEY.md`, released with this result; sha256 `1b47153f…cb6e5d146`, unchanged since sealing). Scored: reports A, B, the integration report and the stage-1 settlement `00030fa97`. Counts only; five chosen items are not a random sample; the result says what was caught for these items under these conditions.*

**Validity:** the copy matched its manifest at scoring (138 of 138); the working book unchanged since the freeze (`8981f6d8e`); no reviewer reached a forbidden file (the Professor's audit). **Caveat:** the Rule Reviewer's commit subject `4ab5b3b82` said "planted-gap test sealed", visible in the reviewers' recent-commit context; the reports do not mention a test, but heightened care cannot be excluded. The same caveat applies, unnoticed at the time, to the S0100 test (`c20b1f8f3`).

## Detection

| Item | Class | Found by | How |
|---|---|---|---|
| Q1 | undefined code "AC-7" (§3.8) | **A (A-20), B (B-28)** | R2, judged real; **first time an undefined code was found** |
| Q2 | ordinary word as undefined term "seal-line" (§8.18 C20) | **nobody** | R2 does not list it; reading passed over it |
| Q3 | whole claim removed, renumbered (§8.21 C14, the ledger) | **A (A-01), B (B-02)** | through the traces: the section title and index row still promise the claim, and the C28 settlement's W-10/W-15 pointers no longer fit. **First time a removed claim was found** |
| Q4a | distortion of §8.19 C19 against S0138 L116 | **B only (B-09, normal)** | as a contradiction between the head and the body, not against the source |
| Q4b | stale citation of §8.19 C19 in §8.20 C3 | **nobody** | the integration reviewer ran R3 on "§8.19 C19", but reported no mismatch: once B-09 framed the fix as "heading only", the citation agreed with the remaining body |

**Found: 3 of 5** (Q1, Q3, Q4a).

## Treatment in the stage-1 settlement

| Item | Row | Treatment | Verdict |
|---|---|---|---|
| Q1 | A-20 = B-25, B-28 | "define or cite AC-7" | **correct in substance** (no AC-7 exists; "define" is one of the key's two remedies; "cite" is not possible) |
| Q3 | A-01 = B-02 | "restore the claim on S0150 L109–118, L248–273 … **or** retitle the section and the index row … *Stage 2 decides which, by checking the working book*" | **partial.** The right remedy is offered, but not chosen; and choosing by checking the working book departs from the two-stage rule (stage 1 decides on the copy and its sources) |
| Q4a | B-09 | "fix the heading" | **partial.** The head is fixed; the first bullet's distortion ("including a write in the interval") is not, although S0138 L116 says that write is undetected |

## Across the four tests (counts)

| Class | C10 | S0036–S0065 | S0100 | S0150 |
|---|---|---|---|---|
| distortion against a source | 2 of 2 | 4 of 4 | 2 of 2 | 1 of 1 (found as an internal contradiction) |
| removed whole claim | — | 0 of 1 | 0 of 1 | **1 of 1** (via traces) |
| undefined code | — | 0 of 1 (BC-7) | 0 of 1 (RX-3) | **1 of 1** (by R2) |
| undefined ordinary word | — | 0 of 1 (lane) | 0 of 1 (watermark) | **0 of 1** (seal-line) |
| stale citation elsewhere | — | — | 0 of 1 | **0 of 1** |

**Bounded conclusion.** The mechanical checks help where they reach: R2 found the undefined code. The coverage basis and settlement pointers made a removed claim visible. Two classes are still missed in every test: **an ordinary word used as an undefined term** (R2 does not list it, and reading accepts it as English), and **a stale citation elsewhere** (R3 lists the citing lines, but the reviewer must compare each with the corrected claim, and did not). Treatment is weaker than detection: two of three found items were treated only in part.

## Recommendations (for the S0150 decision)

1. **R3 output must be dispositioned line by line** (unaffected / corrected / reassess), not just run; the integration reviewer states the disposition per citing line.
2. **Corrections are made against the source, not only for internal consistency**: a heading that contradicts its body is fixed by checking which of the two the source supports.
3. **Stage 1 chooses its correction on the copy and the sources**; "decide by checking the working book" is not allowed before the score.
4. **R2 for ordinary words**: list words that appear in a claim head or definition and in no definition elsewhere in the book (tier C as drafted misses them); test it on "lane", "watermark", "seal-line".
5. **Commit subjects of test preparation are neutral** (the Rule Reviewer's own lesson).
