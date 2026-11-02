# Window C92 (S0360): settlement

*2026-11-02. Section: §8.85 (v2). Review: `REVIEW-REPORT.md` (sha256 in `SHA256-REVIEW.txt`), W-01–W-14. 0 critical, 4 high. Every finding accepted after checking it against the book (§9.15 L64, L66, L85; §9.11 row 11a; §9.18 L273; §3.2 CON-0007) and the sources (S0357 L528; S0358 L94–101, L224–229; S0359 L118–178).*

## Theory first
- **Authorization decided before the record is a rival** (C2, W-01): the book records the act and evaluates entitlement in the kernel (G2; C1 = constitute); only G4 refuses at append, for single-use grants. R3 on §8.84 C4, whose wording of the guard was too wide.
- **U kept in the kernel** (C4, W-04): S0360 moves the undecided value out of the domain.
- **Preserving is not deciding** (C5, W-02): the draft's title and index row withdrawn; kernel history agrees only on linking (W-03: H3, row 11a).
- **The kernel already holds a grade and a gate** (C3, W-06).
- **The reviewed document plausibly downstream of S0358–S0359** (C1, W-05).

## Dispositions

| W | Sev | Disposition | Where |
|---|---|---|---|
| W-01 | high | accepted (checked: §9.15 L64, L66, L85); R3 on §8.84 C4 | title; PL2; C2; .4 |
| W-02 | high | accepted | title; PL5; C5; .4; index |
| W-03 | high | accepted (checked: §9.11 row 11a) | C5 |
| W-04 | high | accepted | title; PL4; C4 |
| W-05 | normal | accepted (checked: S0358 L94–101; S0359 L118–178) | lineage; PL1; C1; .4 |
| W-06 | normal | accepted (checked: §9.18 L273) | PL3; C3 |
| W-07 | normal | accepted: terms qualified | head |
| W-08 | normal | accepted | C1 |
| W-09 | normal | accepted | .3 |
| W-10 | normal | accepted | .3 |
| W-11 | normal | accepted | .3 |
| W-12 | normal | accepted | C2 |
| W-13 | normal | accepted: STEP8's R2 line completed below | this file |
| W-14 | normal | accepted (source defect) | lineage |

## R3
- §8.84 C4 ("the guard accepts an append"): restated by an R3 note. §8.85 C1–C5 have no inbound citations.

## Checks at settlement
- Quote check on v2 against S0357–S0360: every source quotation matched after three case fixes; the misses are quotations of the book and of the draft.
- R2 (W-13, the complete list in scope): event-store, algorithm (S0360's sense); admissible (§8.83 C1, §8.84.3; S0360's "admissible transition"); pipeline (S0358's sense); weight (per-evidence sense); adviser (THEORY-MAP L7); mistake, numeric, paraphrase, loose, warn, correctly, closing, appear, plain, keep: ordinary English; intent, command, aggregate qualified in the section's head.

## Procedure
- Brief committed first, then reading and drafting, the draft committed (`b863fb35d`) before W was dispatched; no item in the draft from W's inventory. W ran no git command; nothing edited.

## Measures
- 14 findings · 0 critical · 4 high · material omissions 3 (W-06, W-07, W-10).
