# Window C24: per-source measures, adversarial pass, step 8, checks

*2026-10-23. Sources: S0126–S0130, in order, each started at opening and closed after its theory was written into §8.17. New: §8.17 (C1–C9); register CON-0231–CON-0235. No kernel section changed: the sources bear on §9.15 K1, L5 and L6 as evidence, recorded in §8.17, and nothing in them changes the theory.*

## Per source (clock calibrated at C23)

| Source | Lines | Start → stop (UTC) | Time | Depth | Findings | In §8.17 |
|---|---|---|---|---|---|---|
| S0126 | 83 | 16:35:03 → 16:35:28 | 25 s | full | 2 | C1 |
| S0127 | 157 | 16:35:28 → 16:36:01 | 33 s | full | 4 | C2, C3 |
| S0128 | 193 | 16:36:07 → 16:36:59 | 52 s | full | 4 | C4, C5, C6 |
| S0129 | 217 | 16:36:59 → 16:37:30 | 31 s | full | 3 | C7, C8 |
| S0130 | 86 | 16:37:30 → 16:37:46 | 16 s | full | 2 | C9 |

**Skim.** None. After C23, where a skim lost theory, the bar for skim is higher: a source is skimmed only if every distinction in it is already in the book. S0126 came closest; its point, that the adoption rests on a bounded review, is new.

## Adversarial pass (before review)

| Claim | Attack | Result |
|---|---|---|
| C1 (CON-0231) | does the adoption claim correctness after all? | No; the rationale cites only the bounded verdicts. *Caveat:* the adoption's conditions are reported by the registrar, not quoted from the act; stated in C1 |
| C2 (tension) | does the estate's wording, "a history that cannot exist **at establishment time**", already carry the snapshot premise? | Partly: it names the instant. But "permanently" still does not follow, since L3, which contains E10, is adopted as attainable. The tension stands |
| C3 (CON-0232) | is "never silently" too strong? | It bars replacing the object, not summarising it; holds |
| C4 (CON-0233) | does losing an amendment always widen standing? | **No:** a widening amendment's loss narrows it. The claim is that loss changes standing silently, in either direction; C4 says "such as a narrowing" (to be checked by the reviewer) |
| C5 (CON-0234) | does the book's merge really never drop? | Union by content identity keeps every entry, and conflicting copies are refused (§9.16 R2). Holds for the merge; D still lacks a loss detector, as stated |
| C6 | is L6 "unmet" too strong? | L6 is an assumption about recorded act times; the estate records almost none (2 of 210). "Unmet in the estate as it stands" is within that evidence |
| C7 (CON-0235) | could force be inferred from the author's authority? | No: the authority's own text was a recommendation (S0129 L87) |
| C8 | does humanActRef attest the human act? | No: it requires a reference, not an attestation; stated |
| C9 | is the git anchor an external anchor? | Partly: it detects later removal relative to commits, not loss before commit; stated |

## Book search; step 8

| Query | Hits | Relation |
|---|---|---|
| adopt, verified, bounded review | CON-0229, CON-0231 | C1 |
| level model, history conjunct | CON-0224 | C2 |
| summary, restatement, governing object | CON-0219 | C3 |
| durable, gitignore, external anchor, loss | §9.15 K1 (S0341), CON-0212 | C4, C5, C9 |
| act time, chronology | §9.15 L6; §9.13 order | C6 |
| force, recommendation, decision | §8.15 C6 / §8.15.3 | C7 |
| root, human act, manufactured authority | §9.15 L5; R-34 | C8 |

**Dependencies:** CON-0224 (provisional); §9.15 K1, L5, L6 (settled; unchanged); §9.16 R2 (settled).

## Checks

- Quote check: §8.17 0 not found. Whole book: 14, the baseline.
- R1: 211 rows with a home, 1 inherited flag. Register file checked after each write (236 lines).
