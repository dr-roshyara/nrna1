# Window C41: settlement

*2026-10-29. Reviewer W (Opus, `kos-lane-reviewer`). Report: `REVIEW-REPORT.md`, saved verbatim (sha256 in `SHA256-REPORT.txt`: `1fe00b4c…08c4e`). Draft `b7dc11ff0`. Changed theory reviewed: the C40 settlement (`3e7e8c598`): every row matches its text.*

| W-nn | Sev. | Professor's check | Disposition | Where applied |
|---|---|---|---|---|
| W-01 | high | **V (Professor):** §9.13.2 L26 (ties unordered, U; the Principal's decision), L62 | Accepted. C1 restated by reading of "failure", (a)–(e); a tie inside one kernel turns T into U, by design; "only on merged or bypass" withdrawn; L62 quoted, not L69 | §8.34 C1, title, plain language, challenge |
| W-02 | high | **V (Professor):** register CON-0134 (INVALID), CON-0141 | Accepted. "A stored derived value has no standing"; "never stored" withdrawn | §8.34 C5, plain language |
| W-03 | high | R (§10.1; T10-01) | Accepted. New C7: S0212's carry-forward sentence is promise (c)'s shape | §8.34 C7, plain language |
| W-04 | high | R | Accepted. INV-KOS-007's content examined: "who" in D (assumed), "method" no field (gap G1) | §8.34.3 |
| W-05 | normal | R (S0213 L385–387, as read at step 1 by both) | Accepted. Tension in the diagram only; "person or system"; §10.4 L24 for "informs" | §8.34 C3 |
| W-06 | normal | R | Accepted. Gate = recognition (Q1 = T), not acceptance at append; S0212's internal tension recorded; "historical" trivial at a cut | §8.34 C4 |
| W-07 | normal | R | Accepted. Three classes: guard, impossible by Φ's inputs, detected | §8.34 C5 |
| W-08 | normal | R (the reviewer checked S0206 L404, L405, L718, L733, L1150 against the source) | Accepted. S0206 lines marked as new reads; S0214's concession; "a mechanism"; homonym then analogy | §8.34 C2 |
| W-09 | normal | R | Accepted. "(canonical)" against "No promotion"; "adopted" against "nothing adopted"; ladder applied after the fact | §8.34 C6 |
| W-10 | normal | R | Accepted. L583 | §8.34 lineage |
| Coverage (MISSING) | normal | R | Accepted (rows added) | §8.34.3 |

**Claim verdicts (reviewer):** C1 PROVISIONAL; C2 VALIDATED in substance; C3 agreement VALIDATED ("does not say so" INVALID); C4 PROVISIONAL; C5 (i) VALIDATED, (ii) INVALID as stated; C6 VALIDATED (Int). C7 added at settlement (from W-03), not separately verdicted. *No register row; levels unchanged.*

**Limit recorded:** W-01's tie case was worked from the text, not run in `ce_model.py`.

## Measures

| Measure | Value |
|---|---|
| Reviewer W, step 1 | 102.9 s (harness) |
| Reviewer W, steps 2–3 | 330.8 s (harness; 433.7 s cumulative) |
| Findings | 10 (4 high, 6 normal; 0 critical); all accepted |
