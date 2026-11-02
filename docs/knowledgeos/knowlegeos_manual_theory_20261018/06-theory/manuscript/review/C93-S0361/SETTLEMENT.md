# Window C93 (S0361): settlement

*2026-11-02. Section: §8.86 (v2). Review: `REVIEW-REPORT.md` (sha256 in `SHA256-REVIEW.txt`), W-01–W-10. 0 critical, 4 high. Every finding accepted after checking it against the book (§10.2 L5; §9.13 L37, L48, I7, F31; §9.15 G4, K4-k, K6) and the source (S0361 L344, L347, L1164–1165, L1353–1358).*

## Theory first
- **T10-05 read correctly** (C1, W-01): standing admitted, well-foundedness since 2026-10-31; the draft cited T10-05 for its opposite. A book defect recorded (W-10): the turn-2 "none admitted" lines read as current alone.
- **F31 open** (C3, W-03): (B)'s questions are the book's open question; θ is fixed to Θ_f in D; the draft's A1 extension withdrawn.
- **One serialised step** (C4, W-04): where the book checks before an append, check and append are one kernel step (§9.13 L48; G4; K4-k; K6); otherwise it evaluates over the record.
- **The append guard, not CE*** (C2, W-05); (C) moves toward the book's evaluation over recorded acts.
- **Evidence admission, not confidence** (C5, W-02); U kept inside the kernel; the trivial case.

## Dispositions

| W | Sev | Disposition | Where |
|---|---|---|---|
| W-01 | high | accepted (checked: §10.2 L5) | PL1; C1; index |
| W-02 | high | accepted (checked: S0361 L344, L347, L1164–1165) | title; PL5; C5; .4 |
| W-03 | high | accepted (checked: §9.13 I7, F31) | title; PL3; C3 |
| W-04 | high | accepted (checked: §9.13 L48; §9.15 G4, K6) | title; PL4; C4 |
| W-05 | normal | accepted (checked: §9.13 L37) | title; PL2; C2 |
| W-06 | normal | accepted | lineage |
| W-07, W-08 | normal | accepted: "pure" and "admit/admissible" glossed | head |
| W-09 | normal | accepted | C5 |
| W-10 | normal | recorded for the next consolidation (map line C93; not changed here) | .3 |
| Coverage ((A)'s verdicts keep U; (C)'s single admission path; the trivial case; crossing confers no authority; SPLIT; self-versioning; who evaluates rules) | — | accepted, tagged "(W, step 1)" | C2; C5; .3 |

## R3
- None: §8.86 C1–C5 have no inbound citations. The book defect of W-10 is recorded, not corrected, in this window.

## Checks at settlement
- Quote check on v2 against S0361: every source quotation matched; the misses are quotations of the book and of the draft.
- R2: tier A "effected" (STEP8); "pure" and "admit" glossed (W-07, W-08).

## Procedure
- Brief committed first, then reading and drafting, the draft committed (`2417b87c0`) before W was dispatched; no item in the draft from W's inventory. W's first combined copy command was denied and redone simply; one scratch script name outside the `c93-w-*.txt` pattern, disclosed. No git command; nothing edited.

## Measures
- 10 findings · 0 critical · 4 high · material omissions 1 (W-04).
