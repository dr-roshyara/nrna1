# Window C13 (S0071–S0075) + the §1.14 form rewrite: lineage, homes, step 8, scan, before review

*2026-10-19. Five sources in index order.*
- *S0071 and S0072 were already read topic-full in theory turn 1; at index order they were checked against §6.5 and §9.6, with no new claim.*
- *No source was added under §12a.*
- *New section: Ch. 8 §8.8.*
- ***Ch. 1 §1.14 rewritten in form only*** (the Principal's instruction).
- *Map: F25 added. Index and register rows added. Nothing else.*

## Lineage
S0071–S0075: one work item (KOS-ARCH-BASELINE-003), one day, citing each other. **One line.** Within it:
- S0073's verifier is process 34210a39 (S0049, S0055, S0059);
- S0074's corrector is S0073's author;
- S0075's verifier shares a model with verification #1.

## Existing homes (step 3; top 5 hits read)
| Hit | Relation | Used in |
|---|---|---|
| §6.5 C4 (CON-0134) | extends (why derived values cannot become true) | C1 |
| §3.2 derived attributes; §4.3 C5a | same family | C1 |
| §9.4 C1 total derivation (CON-0114) | different | — |
| Ch. 2 TODO T2-05 enforcement categories (S0078 OQ-12) | same subject | C1 |
| §8.5 C9 counts with method; CON-0120 | same | C2 |
| §8.7 C5 wrong pen | conflict | C3 (F25) |
| §8.7 C3 stopping rule | same | C6 |
| §8.6 C1, C3 | extends | C4 |
| §9.6.5 RL | same | C6 |
| §5.6 C1a, C6; I-7 | same family | C5 |

## Generalisation rule; no rate
- C1: the law holds under a stated premise (a fixed fold).
- C2: counts only ("four instances in one estate").
- C3: a fork (F25), not a law.
- C4, C5: one source each.

## Dependencies (step 8)
| Claim | Depends on |
|---|---|
| C1 | CON-0134 (PROVISIONAL), CON-0102 (VALIDATED) |
| C2 | §8.5 C9 (provisional), CON-0120 (VALIDATED) |
| C3 | §8.7 C5 (VALIDATED as an instance), §1.14.3 |
| C5 | I-7; §5.6 |

## Strength scan
- "never" appears only in quotes, and in the fold-premise Ded of C1, which is scoped by its premise.
- "only" ×3, all scoped.

**Quotes:** §8.8 18/18; §1.14 13/13.

## §1.14 form rewrite: what changed (to be checked by the reviewer)
- Claim heads numbered per subsection (47 claims). Explanatory blocks are left unnumbered (in plain words, business terms, notation, time axes, open parameters, definitions, the answer).
- Added: a status and lineage header, §1.14.0 in plain language, a claims index, §1.14.6 open questions, §1.14.7 what does not hold, and a references table.
- **Integrity check (script):** with the inserted claim labels removed, §1.14.1–§1.14.5 are byte-identical to the previous version (commit before this window).
- **The added blocks only point to text already in the section. No claim was added.**
