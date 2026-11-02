# Window C89 (S0353–S0355): settlement

*2026-11-02. Section: §8.82 (v2). Review: `REVIEW-REPORT.md` (sha256 in `SHA256-REVIEW.txt`), W-01–W-13. 0 critical, 7 high. Every finding accepted after checking it against the sources and the book (§8.75 C1 L40–45; §8.77 C6; §8.79.5; §8.81.5; S0354 L13–14; S0355 L26, L81).*

## Theory first
- **A change over time, not a new pattern** (C1, W-02): §8.75 C1's open `HumanDecisionPort` is filled by AST-019 with a CLI argument; START `recordedBy: human` carries the words the invoking session supplies. R3 on §8.75 C1.
- **Production on a stopped item** (C2, W-07): the amendment slice was built with no lane, no label and no entry while the item was STOPPED: commission-authorized production as a second channel of authority beside lanes. The Ded's unprovable step withdrawn (W-03).
- **The producer admitted as specified** (C4, W-04): under the only stated grounds of ineligibility, AST-019's producer passes as adoption reviewer.
- **No rule drawn** (C3, W-01); the case-table gap (W-08); the producer's order of verification (C5, W-06); "fresh" in two senses (W-10).

## Dispositions

| W | Sev | Disposition | Where |
|---|---|---|---|
| W-01 | high | accepted | PL3; C3 |
| W-02 | high | accepted (checked: §8.75 C1 L45; §8.77 C6); R3 on §8.75 C1 | title; PL1; C1; homes |
| W-03 | high | accepted: step 3 withdrawn | PL2; C2 |
| W-04 | high | accepted (checked: S0354 L13–14) | title; PL4; C4 |
| W-05 | high | accepted | PL4; C4; index |
| W-06 | high | accepted (checked: S0353 L124–125; S0354 L25–26) | PL5; C5 |
| W-07 | high | accepted (checked: S0355 L26, L81; S0353 L129) | title; PL2; C2 |
| W-08 | normal | accepted | PL3; C3 |
| W-09 | normal | accepted | C1 |
| W-10 | normal | accepted: two senses glossed | head; C4 |
| W-11 | normal | accepted | .3 |
| W-12 | normal | accepted | C1 |
| W-13 | normal | accepted | lineage |
| Appendix (homonym "authorized", "exhausted", next actor, subagent, commits, source defects) | — | accepted, as coverage lines | .3 |

## R3
- §8.75 C1 ("the open interface"): AST-019 fills the port with `--human-act`. No other inbound citations (W: §8.82 C1–C5 have none; §8.81 C2's five citing lines unchanged).

## Checks at settlement
- Quote check on v2 against S0352–S0355: every source quotation matched; the misses are quotations of the book and of the draft (§8.82.5).
- R2: tier A–C 0 in scope (STEP8); W's no-corpus run, 20 tier-D words, ordinary English.

## Procedure
- The brief was committed first (`6d196bcbb`), then reading and drafting; the draft committed (`d38c54e9d`) before W was dispatched; no item in the draft from W's inventory. W ran no git command; nothing edited.

## Measures
- 13 findings · 0 critical · 7 high · material omissions 3 (W-04, W-07, W-11).
