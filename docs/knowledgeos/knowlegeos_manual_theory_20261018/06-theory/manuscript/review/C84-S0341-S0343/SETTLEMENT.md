# Window C84 (S0341–S0343): settlement

*2026-11-02. Section: §8.77 (v2). Review: `REVIEW-REPORT.md` (sha256 in `SHA256-REVIEW.txt`), W-01–W-17. 0 critical, 7 high. Every finding accepted.*

## Theory first

- **The live AMBIGUOUS is safe, not right** (C2, W-02): by S0341's report, `a8ce5a39` is assigned to S5 and only mentioned in S6; §8.67 C3's "the verdict is right" is restated by R3; the re-verification's "must STAY AMBIGUOUS" made the artifact its pass condition.
- **Attribution is read from free text by both patterns** (C2, W-03, Ded); only the REGISTER field carries the assignment; the remedy belongs there (§9.15 identity link).
- **The safety question re-homed** (C2, W-04): it turns on where `registered_process_label` comes from; leaning safe, not shown.
- **S0342 maps the end of work to a STOP transition recorded by Governance**, as a source (C3, W-08); R3 on §8.72 C4. "Closing" a homonym beside STOP and STOPPED.
- **C6 added** (W-09): S0343 makes the human START executed by the capability the operating norm; "never fabricated" has no check inside the record.
- **C1 corrected** (W-01): S0341 is already used at §9.15 K1; CON-0233 once more, sharing S0190's origin.
- **C4 corrected** (W-06, W-07): the "refusal bars" claim withdrawn (no stated ground); the recording capacity is a declared recorder in the book (§9.15 R4).

## Dispositions

| W | Sev | Disposition | Where |
|---|---|---|---|
| W-01 | high | accepted (checked: §9.15.8 L236; §8.17 L65) | head; C1; lineage |
| W-02 | high | accepted; R3 on §8.67 C3, §8.72.3, §8.73.3 | C2; title; PL2 |
| W-03 | high | accepted | C2; title; PL2 |
| W-04 | normal | accepted | C2 |
| W-05 | normal | accepted; R3 on §8.74 C3 | C2 |
| W-06 | high | accepted: claim withdrawn and restated | C4; index |
| W-07 | high | accepted | C4; PL4; index |
| W-08 | high | accepted; R3 on §8.72 C4 | C3; title; PL3 |
| W-09 | high | accepted: C6 added | C6; title; PL6 |
| W-10 | normal | accepted | §8.77.3 |
| W-11 | normal | accepted | C4 |
| W-12 | normal | accepted | C3 |
| W-13 | normal | accepted | head |
| W-14 | normal | accepted | C5 |
| W-15 | normal | accepted: §8.67.3 pointers corrected book-wide (L119 monotone; L118 V-4/V-7); the consolidation's insertion at §8.67.3 had shifted them (the Professor's defect) | §8.68, §8.71, §8.72, §8.74, §8.76, §8.77 |
| W-16 | normal | accepted | head |
| W-17 | normal | accepted | §8.77.3 |

## R3

- §8.67 C3 (safe, not right); §8.72.3 (the match set); §8.72 C4 (S0342's STOP); §8.73.3 (the oracle); §8.74 C3 (a further mechanism, V-4 credited). Pointer corrections as W-15.

## Measures

- 17 findings · 0 critical · 7 high · material omissions 4 (W-01, W-03, W-08, W-09). W disclosed two errors in its own step-1 inventory (the bars count; the overreach behind W-06).
