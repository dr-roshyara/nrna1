# Window C29: measures, challenge records, step 8, checks

*2026-10-25. Sources: S0151, S0152, S0153, S0154, S0158, read in index order among allowed sources. S0155–S0157 are not on the allowed list (S0155 exposed: `ACCESS-INCIDENT.md`). New: §8.22 (C1–C11). No new concept. No kernel section changed.*

## Measures (per window; v1.4 §10)

| Measure | Value |
|---|---|
| Sources, lines | S0151 823 · S0152 200 · S0153 940 · S0154 596 · S0158 763 = 3,322 lines |
| Depth | all five full (the Professor's and W2's blind judgement agree) |
| Professor | S0151–S0154 read on 2026-10-24, about 19:23–19:33Z. Reading then stopped at the incident. S0158 read and §8.22 drafted on 2026-10-25, about 01:12–01:30Z. The Professor's harness time is not available to the Professor |
| Reviewer W2 step 1 | 89 s (harness) |

## Challenge records (v1.4 §2 step 2)

- **C4 (Ded):**
  - counterexample tried: a back-test whose known findings include a missing-act defect;
  - boundary: the back-test then also tests reach for that class;
  - rival: none;
  - falsifier: a back-test that, in its own setup, exposes defects unknown to the checker's author.
- **C7 (Ded):**
  - counterexample tried: a specification derived from the incumbent's behaviour, which makes it the incumbent by another name;
  - boundary: the specification must be declared independently of the incumbent's output;
  - rival: differential testing (agreement with the incumbent) as sufficient when the incumbent is trusted;
  - falsifier: a shared defect that a specification check also passes.
- **C1 (Ded, checked by diff):** no counterexample. The bodies are identical.

## Step 8

- **Dependencies:**
  - §8.22 C2 rests on §9.17 R2 (CON-0261, PROVISIONAL: unverified);
  - C3 rests on §1.14.5-1 and §8.20 C12–C13;
  - C4 rests on CON-0253 (VALIDATED);
  - C7 rests on CON-0257 and CON-0265 (PROVISIONAL: unverified);
  - C9 rests on §9.17 R1 and §8.17;
  - C10 on §9.13; C11 on §1.16.1 and §9.16 R2.
- **R1:** no new register rows.
- **R2 (tier D in §8.22):** all ordinary words: adviser, integrate, integrating, idempotent, consumer, byte-identical, identical, discipline, deterministic, specification, built, define, adopt, quoted, engine. "Adviser" is spelled "advisor" in the corpus.
- **Quote check:** §8.22 12 found, 0 not found.
