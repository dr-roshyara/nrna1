# Window C63: settlement

*2026-10-31. Reviewer W (Opus, `kos-lane-reviewer`). Report: `REVIEW-REPORT.md`, saved verbatim with `tmp/tools/save_handback.py` (sha256 in `SHA256-REVIEW.txt`: `22591d40…d5f501`). Draft `5086b9e6a` (re-read at `4afaf25aa`). Changed theory reviewed: the C62 settlement (`ffda36c93`), validated as applied; the re-read at `4afaf25aa` applied C62's lessons locally but did not check older settled homes (§8.37, §8.42, §8.53, §8.41).*

| W-nn | Sev. | Professor's check | Disposition | Where applied |
|---|---|---|---|---|
| W-01 | high | **V (Professor):** §8.53 C1 (b) L38 | Accepted. Two readings of what equal normal forms match (representations, against Art 1.3 per §8.53 C1 (b); meaning, fixed by the parser's rule); W's trivial-case check recorded | §8.56 C1, PL1, §8.56.5 |
| W-02 | high | **V (Professor):** S0241 L66–67, L75; S0293 L28 | Accepted. Two readings of "views" (originals kept; normal form replaces them, against Art 5.1 and 6.3) | §8.56 C1, PL1, §8.56.5 |
| W-03 | high | **V (Professor):** §9.10 L93 | Accepted. Identity resolution and the open sameness question as homes; reading (c), the gate decides | §8.56 C1, PL1, homes |
| W-04 | high | **V (Professor):** §8.37 C1 L37–44 | Accepted. §8.37 C1's three readings govern; S0294 L699 a restatement of S0223 L69–71; Art 6 cited | §8.56 C4, PL4, lineage, §8.56.5 |
| W-05 | high | **V (Professor):** S0232 L517 | Accepted. Head 43 is S0232's final definition; the proposed R3 for §8.52 C2 withdrawn; the new occurrence is S0294 L34 | §8.56 C3, PL3, §8.56.5; R3 §8.57 C2 |
| W-06 | high | **V (Professor):** §8.42 L83 | Accepted. Per axis; §9.18's limit carried | §8.56 C5, PL5, §8.56.5 |
| W-07 | normal | **V (Professor):** S0294 L1117, L1209, L1229 | Accepted. Sequence corrected; "shown only in prose" (§8.43 C4) | §8.56 C2, PL2, challenge |
| W-08 | normal | **V (Professor):** S0294 L1069, L1087 | Accepted. Range corrected; "a subset" | lineage, C2 |
| W-09 | normal | R | Accepted. Content axis added; S0294's other senses of "kernel" recorded | §8.56 C3, PL3 |
| W-10 | normal | R | Accepted. Five non-identities in the Constitution; the tombstone qualification | §8.56 C5 |
| W-11 | normal | R | Accepted. Challenge answers restated | §8.56.4 |
| W-12 | normal | R | Accepted. STEP8 updated (dependencies, R2, R3) | STEP8 |
| Coverage (MISSING items; source defects) | — | R | Accepted. S0293's storage principle, refusal, status pattern and absence by search; the translation-collapse tension; the UM series defects, with an R3 note on §8.46 C1 (d) | §8.56.3; §8.46 C1 (d) |

**Claim verdicts (reviewer):** C1–C5 PROVISIONAL; "views" INVALID as stated (restated); C3's R3 note INVALID as stated (withdrawn); C4's two-reading split INVALID as stated (restated to three); C5's "Unknown ≠ Absent ≠ False" INVALID as stated (restated per axis); the C62 settlement VALIDATED as applied.

**Lessons:** a settled multi-reading result reverted again (§8.37 C1's three readings, §8.42 L83's axes); a source's earlier appearance in the book not traced to its origin (head 43 = S0232 L517; Vyapti-001 = S0223); a settled counter-result not reconciled (§8.53 C1 (b)); a projection's direction not checked (views); a re-read limited to the last settlement's lessons.

**R3:** §8.57 C2 (L48), C3 (L53); §8.58 C3 (L51); §8.46 C1 (d). Index row 8.56 restated.

**Checks:** R1 241 homes, 1 flag (CON-0133), unchanged. Tier A in scope 0.

## Measures

| Measure | Value |
|---|---|
| Reviewer W, step 1 | 138.0 s (harness), 11 tool uses |
| Reviewer W, steps 2–3 | 490.2 s (harness, cumulative as reported), 37 tool uses |
| Findings | 12 (6 high, 6 normal; 0 critical); all accepted |
| **Professor's effort (the Principal's instruction 2, 2026-10-31; wall-clock between tool calls, logged at phase boundaries)** | reading: not measured (before the instruction) · drafting: not measured (before the instruction) · settlement: 16 s (brief, 15:52:17–15:52:33Z) + 48 s (records and settlement, 16:03:34–16:04:22Z; corrected from the effort log after commit `1170579e9`) · rework: 2 min 50 s (16:00:44–16:03:34Z: verification of the high findings, §8.56 restated, R3) |
| Method note | phase boundaries are logged at tool calls; time spent before the first call of a phase counts to the previous phase; reviewer time is not included |
