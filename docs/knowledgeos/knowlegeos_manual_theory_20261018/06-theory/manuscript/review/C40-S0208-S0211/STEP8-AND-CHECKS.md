# Window C40: measures, challenge records, step 8, checks

*2026-10-29. Sources: S0208, S0209, S0210, S0211, read in index order; all on the allowed list. New: §8.33 (C1–C6). No new concept. Changed theory since the last window review: the C39 settlement (`257af9be2`).*

## Measures

| Measure | Value |
|---|---|
| Sources, lines (`wc -l` + final line) | S0208 1154 · S0209 344 · S0210 692 · S0211 725 = 2,915 |
| Depth | all four full; S0211's body verified identical to S0210 by `diff`, the differing lines read (the Professor and W both did this) |
| Professor, reading (start/stop records) | S0208 11:59:27Z–12:00:15Z; S0209 12:00:15Z–12:00:32Z; S0210 12:00:33Z–12:00:58Z; S0211 12:00:58Z–12:01:26Z. Bookkeeping times; they understate reading |
| Reviewer W, step 1 | 120.4 s (harness), 9 tool uses; one Bash call (`wc -l`, `diff`) |

## Step 8

- **Dependencies:** C1: §9.13 R2, R2a; §9.18 g0; §8.31 C2; T10-21; CON-0221 · C2: §1.13; §8.17 C8; ARCH-INV-1; §9.13; S0206 L1244 · C3: §8.06; §8.30 C4; §8.31 C4 · C4: §9.18 (c1)–(c5), check 33; §8.32 C2; §10.4 R1; F13 · C5: §9.15 G1, G4, G6; §9.16 R3a · C6: the strategy §3; Ch. 3 labels.
- **R1:** no new register rows; 241 homes, 1 flag (CON-0133, pre-existing).
- **R2 (§8.33):** no tier A in scope.
- **Quote check:** 0 in the tool's format.
- **R3:** no existing claim changed.
