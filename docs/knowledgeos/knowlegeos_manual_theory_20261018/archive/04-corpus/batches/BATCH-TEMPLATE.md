# BATCH-nn — <tier, theme>

| Field | Value |
|---|---|
| Opened / closed (UTC) | |
| Authorization reference | <G-LOG entry that permits this reading> |
| Queue rows | <S-ids> |

## 1. Counts
| Queued | DONE | DEFERRED | DUPLICATE-OF | VARIANT-OF |
|---|---|---|---|---|

DEFERRED files and their reasons:

## 2. Checks (all must PASS before closing)
| Check | Result | Evidence |
|---|---|---|
| Hash recheck of every file in the batch | | |
| `lines_read = lines_total`, `bytes_read = bytes_total` for every DONE file | | |
| `quote_anchor_check.py` over the batch's extraction records | | command + output counts |
| Ledger reconciliation: every extraction appears in the ledgers; every new ledger row cites an extraction | | |
| READ-LOG and READ-AND-EXTRACTED rows for every DONE file | | |
| No secondary source cited outside LEADS | | |

## 2a. Independent review checkpoint (R4, after V-2)
Requires the Principal's written approval for corpus material to reach a reviewer (G-LOG-01xx §5).

| Item | Value |
|---|---|
| Sample | files drawn at random from this batch (seed recorded); size and selection rule stated |
| Reviewer | fresh session; re-extracts the sample **blind**, from the sources only; then audits coverage, quotes and theory-map changes |
| Adjudication | fresh session, blinded X/Y, same classes as V-2 |
| Access audit | reviewer and adjudicator tool calls, paths and counts only |
| Results | omissions (foundational), misreadings, label errors, spurious, missed-by-both; the R1–R3 rule checks |

## 3. What changed in the reconstruction
- New concepts:
- Changed definitions (chronology events):
- New dependencies:
- New contradictions, by classification:
- New mathematical statements, by status:
- Claims whose status changed:

## 3a. Provisional synthesis after this batch
**R — what the sources establish** (each sentence cites claim or anchor ids; [EV] only):

**T — evaluation and new deductions** (marked [I], [H] or [NEW]; validity check named):

**Supported now / still uncertain / missing evidence:**

**Model revisions forced by this batch** (what earlier provisional statement the evidence changed):

## 4. Coverage after this batch
| Stratum (folder × month) | Read (n) | In frame (N) |
|---|---|---|

Counts only; no extrapolation.

## 5. Open questions and next batch
