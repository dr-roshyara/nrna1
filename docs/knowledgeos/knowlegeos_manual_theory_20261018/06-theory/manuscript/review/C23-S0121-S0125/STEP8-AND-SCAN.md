# Window C23: per-source measures, step 8, adversarial pass, checks

*2026-10-23. Sources: S0121–S0125, in order. Each was started at opening and closed after its theory was written into §8.16. New: §8.16 (C1–C6, two skim lines); register CON-0226–CON-0229; CON-0222 extended; §8.15 C2 corrected (additive pointer). No kernel section changed (the Principal: kernel edits only when a source changes the theory; none did).*

## Per source

| Source | Lines | Start → stop (UTC) | Time | Depth | Findings | In §8.16 |
|---|---|---|---|---|---|---|
| S0121 | 136 | 16:21:09 → 16:22:08 | 59 s | full | 2 | C1, C2 |
| S0122 | 228 | 16:22:08 → 16:22:48 | 40 s | full | 4 | C3, C4, C5 |
| S0123 | 49 | 16:22:58 → 16:23:12 | 14 s | **skim** | 0 new | skim line |
| S0124 | 108 | 16:23:13 → 16:23:36 | 23 s | full | 2 | C6 |
| S0125 | 74 | 16:23:36 → 16:23:49 | 13 s | **skim** | 0 new | skim line |

- **The clock was calibrated in this window.** The wall-clock gap across a model run (25.5 s) equals the process's own elapsed time (25.53 s), so the times are real. S0121's time includes recreating two scratchpad helpers, which had been deleted between sessions.
- **Skim, honestly applied (the first skims in 125 sources).**
  - S0123 repeats §8.13 C5 and S0122's rule.
  - S0125 registers S0122's content.

  Each has its one line in §8.16. The reviewer reads one of them blind (§3).

## Adversarial pass, made before review (the change announced after C22)

| Claim | Attack tried | Result |
|---|---|---|
| C1 (CON-0226) | does D itself conflate transfer with completion? | No. A transfer is not a use, and Φ reads no transfer as an ending. The conflation is in a projection; recorded as a marks question, not proposed |
| C2 (correction) | is S0121's withdrawal itself reliable? | It is Governance withdrawing its own claim, with the seq-29 handoff on record. Accepted; §8.15 C2 corrected |
| C3 (CON-0227) | (a) is the source's orthogonality test adequate? (b) is a required reference into BC-1's unbuilt mechanism a referent gap? | (a) No: it shows the parts are not one function, not that they are independent; stated. (b) Marked pending; S0124 did not answer it (bounded review), so it is open |
| C4 (extends CON-0222) | does "evidence toward" just rename the problem the C22 review found? | No. The authority decided the asymmetry and added rule 7, which says what the record is not; the partiality is intended and stated |
| C5 (CON-0228) | does forbidding the field lose needed information? | The source routes the need elsewhere (C-5, correlation); the claim is about where it lives |
| C6 (CON-0229) | is "a review citable for what it checked" just CON-0154 again? | It is CON-0154 applied to reviews; kept as an application, and it is what blocks citing S0124 as support for C3 (a) |

## Book search; step 8

| Query | Hits | Relation |
|---|---|---|
| handoff, released, completed | §9.16 R4a; §8.15 C2 | C1, C2 |
| owner, holder, data/semantic ownership | §9.16 R4a; Ch. 9 roles | C3 (Int: R4a is a split ownership) |
| required reference, referent | CON-0203, CON-0220 | C3 (b) |
| world vs record | CON-0222; §9.15 | C4 |
| bounded review, verification scope | CON-0154 | C6 |

**Dependencies:** §8.15 (settled at C22; C2 corrected here); §9.16 R4a (closed as a sketch; unchanged); CON-0154 (provisional).

## Checks

- Quote check: §8.16 0 not found. Whole book: 14, the baseline.
- R1: 205 rows with a home, 1 inherited flag.
- **Incident:** during this window, a one-line script truncated the register file before reading it. Caught by R1 ("0 rows"). Restored from git HEAD, the four new rows and one change re-applied, the diff checked (5 insertions, 1 deletion).
