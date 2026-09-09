# CHRONOLOGICAL COVERAGE LEDGER

Read status is a **first-class fact**. A structural survey is never a read.

`READ-COMPLETE` · `READ-SUBSTANTIAL` · `READ-PARTIAL` · `READ-STRUCTURAL` · `READ-TARGETED` · `NOT-READ`

---

## Corpus totals (mechanical, from `01-CORPUS-CENSUS.tsv`)

| | count |
|---|---|
| queue entries | 5 998 |
| **firewalled (3MC)** | **3 887** — never openable |
| readable, existing | **2 099** |
| readable, missing on disk | 12 |

## Coverage by state — 2026-09-09, before the G-12 worker cycle returns

| state | count | notes |
|---|---|---|
| `READ-COMPLETE` | 29 | `023`, `025d`, `025e`, `025f`, `025k`, `025l`, `025m`, `276`, `276-final`, `277`, `278`×3, `272b`, `279`×2, `280`×2, `281`×2, `270`, `271`, `273`, `step_251`(targeted-complete), + P-95/96 set |
| `READ-SUBSTANTIAL` | 1 | `025o` (§9–19, §27–32, §34–39 unread) |
| `READ-PARTIAL` | 2 | `025` (lines 653–738), `025n` (§1–15, 26–28, 44–46) |
| `READ-STRUCTURAL` | 20 | `025g`–`025z` less those read |
| `READ-TARGETED` | 3 | `274`, `275`, `step_251` — opened for a specific test only |
| **`NOT-READ`** | **~2 044** | of which **265 are steps 026–268, now under worker extraction** |

$$\text{semantic coverage} \approx \frac{32}{2\,099} \approx \mathbf{1.5\%} \quad\text{complete or substantial}$$

**Stated plainly so it cannot be mistaken:** every finding in this reconstruction rests on ~1.5% of
the readable corpus read semantically, plus a mechanical extraction over 2 083 documents.

## The step spine — what exists

| range | files | status |
|---|---|---|
| `001`–`022` | present | **NOT-READ** — includes `step-016`, Lineage A's only external citation |
| `023`, `025`–`025z` | 35 | Lineage A — 7 complete, 1 substantial, 2 partial, 20 structural |
| **`026`–`268`** | **265** | ⭐ **G-12 — under extraction by six bounded workers** |
| `269`–`282` | ~20 | Lineage B — largely complete |
| `283`–`292` | present | **NOT-READ** — the post-B kernel lane |
| Q-series `question-N` | 31 | **NOT-READ** — candidate common ancestor, 2026-08-26 |

## Other lanes

| lane | files | status |
|---|---|---|
| `verification/` | 480 | **NOT-READ** except `step-280/281/282` execution artifacts (5 read) |
| `mathematical_ideas_…` | 411 | **NOT-READ** by Main; surveyed by Worker C |
| `kernel/` | 181 | **NOT-READ** |
| root + misc | 132 | **NOT-READ** |

## Backfill debt (owed, tracked, not forgotten)

1. `025`, `025a-1…a-5`, `025b`, `025c`, `025c-1…c-3` — 11 docs **chronologically before** `025d`
2. `025i`, `025s` — `KAID` birth documents
3. `025n` remainder · `025o` remainder
4. `025g`–`025z` — 20 documents at `READ-STRUCTURAL` only
5. **Q-series, 31 documents** — candidate common ancestor, entirely unread
6. `001`–`022`, incl. `step-016`
