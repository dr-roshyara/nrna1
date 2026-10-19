# Consolidation review S0036–S0065: scope (fixed before the reviewers start)

*2026-10-19. Rulebook §12 (consolidation every 25 sources read in order); METHOD-VERSIONS entry 47 (directly after Trial T3). The Principal's new arrangement:*
- two independent reviewers on different models;
- a planted-gap test whose answer key the Principal seals before commissioning.

*The sections below are **frozen at commit `adbe16c5c`**. Nothing in scope is edited until both reports are in.*

## Sections in scope: written or changed since the last consolidation (base `00ac1a128`)

| § | File | sha256 (first 12) | Windows |
|---|---|---|---|
| 4.2 | `chapter_04/04.02-improvement-cycle.md` | 013d90ffbb3d | C7 |
| 4.3 | `chapter_04/04.03-recommendation-lifecycle-observed.md` | 178501457f00 | C8 |
| 5.4 | `chapter_05/05.04-gap-operator.md` (C7, C8 and the S2377 parts) | 6cd1d2502d78 | before C7, C7 |
| 5.5 | `chapter_05/05.05-who-executed-who-authorized.md` | 6e106b3153c0 | C8, C10, T3 |
| 5.6 | `chapter_05/05.06-what-an-act-covers.md` | cb69fca600eb | C10, T3, C10 test |
| 6.4 | `chapter_06/06.04-ownership-thresholds-breached-invariants.md` | 756fcc6687d1 | C7 |
| 6.5 | `chapter_06/06.05-recording-authority-without-holding-it.md` | 85144e36b6f3 | T3 |
| 8.2 | `chapter_08/08.02-rediscovery-and-reachability.md` | 2a82aaaa6872 | C7 |
| 8.3 | `chapter_08/08.03-does-it-govern.md` | e839b1c1326e | C8 |
| 8.4 | `chapter_08/08.04-claims-of-novelty-and-of-absence.md` | d2f83c3b9d7e | C9 |
| 8.5 | `chapter_08/08.05-verifying-a-reconstruction.md` | 425efaf64e06 | C10, C10 test |
| 8.6 | `chapter_08/08.06-independence-of-a-check.md` | d54130bed9e8 | T3 |
| 8.7 | `chapter_08/08.07-correction-chains.md` | a942ca880af3 | T3 |
| 9.1–9.5 | `chapter_09/09.01-…` to `09.05-…` | 15deac7fd1cb · 4b7cec4ef3fd · d834a7429e8d · 4ef4ac04c038 · d46bf4982584 | before C7 – C10 |

**Also in scope:**
- `BOOK-OUTLINE.md`;
- `THEORY-MAP.md`, fork rows F13a–F22 and the window blocks C7–T3;
- `CONCEPT-REGISTER.tsv` rows CON-0099–CON-0134.

**Ch. 0–2:** split into section files at `ccfd8646d` (join-checked byte for byte) with no content change. Ch. 1 has one added line from C8 (`d5f333a4a`). Not in scope, except as homes for the dependency check.

## Concepts (36: CON-0099 … CON-0134)

- **VALIDATED by one transition review only (12)**, which need a second, independent verdict here: 0099, 0102, 0104, 0105, 0107, 0108, 0111, 0120, 0121, 0126, 0129, 0131.
- **PROVISIONAL (12)**: 0100, 0101, 0103, 0115, 0117, 0123, 0125, 0127, 0128, 0130, 0132, 0134. A verdict is asked for each.
- **INVALID (12)**: 0106, 0109, 0110, 0112, 0113, 0114, 0116, 0118, 0119, 0122, 0124, 0133. Their valid cores are checked as stated in the book, not re-litigated.
- **Points written in after the C10 planted-gap test** (§8.5 C2 scope bullets, C7 scope, C9; §5.6 C7) are unreviewed and in scope.

## Sources (all already read)

- **In index order:** S0036 … S0065 (30).
- **Added under §12a, topic-full:** S0091, S0113 (C9), S0129, S2834 (C8), S0164, S0913, S0067, S0071 (T3).
- **Read in full for the gap theory:** S2377.

Paths are in the access record and `02-FILES.jsonl`.

## What the consolidation adds (REVIEWER-INSTRUCTIONS, consolidation mode)

- **The architecture view:** does the derivation run from the primitives through the laws to the results? Are the primitives complete and minimal? Are any parts isolated, circular or unsupported?
- **The sequence check** of the sections in scope: concepts used before they are defined, or never defined.
- **Kernel or application · minimal · stable** for each concept.
- **For Trial T3:** defects in §8.6, §8.7, §6.5, §5.5 C6 and §5.6 C6 that review C11/T3 missed. *Measure:* count them after both reports.

## Open questions for the Principal, carried

- Were the "Principal DDD Architect" reviews in S0064 human or AI (T3 finding 10)?
- Fork F22, plane or context: whose call?
