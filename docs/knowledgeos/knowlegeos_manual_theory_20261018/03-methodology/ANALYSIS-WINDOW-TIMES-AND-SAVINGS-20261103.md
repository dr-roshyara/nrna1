# Analysis: time per window, and where time can be saved under Rulebook v1.4

| Field | Value |
|---|---|
| Status | **The Governance Engineer's analysis, on the Principal's request** of 2026-11-03 ("please compare the time taken at different windows and find out where we can save the time and optimize by intigrating rule in v1.4?"). **It decides nothing** |
| Prepared by | the Governance Engineer (session `57ddce41…`), 2026-11-03 |
| Data | commit times of the lane (`git log`, C69–C104, the consolidations S0278–S0302, S0303–S0327, S0328–S0352, the Req/A and T9-02 turns); source lines from `tmp/corpus-reading/READ-LOG-CHRONO-20261018.tsv`; the Professor's phase lines in `sessions/2026-11-0[12].md`. Script: GE scratchpad `wtimes.py` (not committed) |
| Limits | Commit times measure **elapsed** time between saved steps, not active work; reviewer time is subagent time; a few windows have merged commits (phases shown as 0). Single-day sample (2026-11-01 to 11-03); figures are approximate |

**Labels.** **[V]** from the records · **[I]** interpretation · **[P]** proposal · **[U]** unknown.

## 1. What a window costs

**[V] A window's cycle (settled → next settled) is 12–20 minutes whatever its size**, when nothing stops it:

| Window | Sources | Lines | Cycle (min) |
|---|---|---|---|
| C81, C82, C83, C84 | 3, 3, 4, 3 | 426, 884, 431, 380 | 18, 13, 13, 18 |
| C86, C87 | 4, 2 | 348, 433 | 18, 16 |
| C92, C93, C95, C96 | 1 each | 703, 1,483, 1,081, 1,852 | 12, 15, 18, 17 |
| C99, C101, C102 | 1 each | 1,106, 1,233, 1,225 | 16, 17, 16 |

**[V] Inside a cycle (C97–C103, where every step has its own commit):** brief and drafting 3–9 min · reviewer step 1 about 2 min · **reviewer step 2 7–9 min (about half the cycle)** · settlement 2–3 min.

**[I] So the cost is per window, not per source or per line.** One source per window costs about 16 min a source; three to four sources per window cost about 4–6 min a source. Reading itself is under a minute per source in the read log.

## 2. Where the time goes outside windows

| Event | Elapsed | Of which waiting | Evidence [V] |
|---|---|---|---|
| Consolidation, normal (S0303–S0327) | about 41 min | — | 04:18 → 04:59, 2026-11-02 |
| **Consolidation with planted-gap test (S0328–S0352)** | **about 3 h 17 min** | **about 1 h 51 min** between the copy (11:32) and the freeze (13:23): the GE's planted copy, the independent check, the Principal's go-ahead; reviewers then ran about 26 min | 11:01 → 14:18 |
| Theory turn (Req/A; T9-02) | 41 min; 27 min | T9-02: about 55 min more waiting for the deliverable ruling | turn commits |
| **Stops for a Principal ruling on a reviewer's access deviation** | C78 18 min; C97 about 25 min; C103 about 3 min | the ruling | C78, C97, C103 `ACCESS-DEVIATION` / stop (d) |
| Overnight or idle gaps | C71 6 h; C100 7 h | not work | commit gaps |

**[V] Access-deviation stops recur:** three in 35 windows (C78 git history; C97 non-allowed book files copied; C103 a glob that matched two non-allowed corpus files). All three were settled on the saved report.

## 3. Where time can be saved

| # | Saving | Size [I] | How, under v1.4 |
|---|---|---|---|
| **S1** | **Fewer, larger windows** (sibling grouping; up to 5 sources, §3) | **largest: 2–3× more sources per hour** | **already decided** (Principal, 2026-11-03); C104 is the first 3-source window. Measure at the S0353–S0377 consolidation, as ruled |
| **S2** | **Take the planted-gap preparation off the critical path** | about 1–1.5 h per test (every 50 sources) | the GE designs the plants and drafts the independent-check brief **while the last windows before the gate run**; at the gate only the copy is built, checked and sealed. A **standing authorisation** for the independent check removes one round-trip to the Principal |
| **S3** | **Minor access deviations recorded, not stopped for** | about 10–25 min each, about 1 in 12 windows | a working instruction defining "minor" (§4); stop (d) kept for everything else |
| S4 | Group stops for Principal questions | variable | non-blocking questions (not stop (b)) collected and asked at the consolidation; blocking ones stay immediate. Mostly already practice |
| — | **Not recommended:** shortening the step-2 review, dropping step 1, skimming repeated sources | — | step 2 is half the cycle but is where the 10–19 findings per window come from; §3 forbids skim for repetition; the planted-gap results (about 3 of 4) do not support less review |

**[I] At the new window size, consolidations become the next bottleneck:** five 3-source windows (about 80 min) plus a consolidation (about 40 min, or about 3 h with a planted-gap test) per 25 sources. S2 is therefore the next most valuable step after S1.

**[I] Projection (active time only, rough):** about 2,360 allowed sources remain. At one source per window: about 630 h of windows. At three: about 210 h. At five: about 130 h. Consolidations add about 95 × 40 min to 3 h.

## 4. "Integrating into v1.4": how, without a v1.5

- **[V]** No Rulebook v1.5 exists or is authorised; changes since v1.4 have been made as **the Principal's working instructions under v1.4**, recorded in the decision record (the standing instruction, the git ruling, copy lists, the window grouping, the chapter summaries).
- **[P]** S2 and S3 can be made the same way; no rule text changes. If the working instructions grow further, folding them into one rulebook text is a separate decision for later.

**S3 — a definition of "minor" [P]:** a reviewer's access deviation is minor when **all** hold: (i) it is disclosed by the reviewer; (ii) what was touched is lane book files, or allowed corpus files already read by the Professor, never sealed, hold-out, quarantine, `00-access/` material or git history; (iii) no finding relies on it; (iv) the affected checks were rerun cleanly or are not needed. Then the Professor records it in the window's `ACCESS-DEVIATION.md`, settles, continues, and lists it in the next consolidation report; the GE enters it in the access record. Anything else is stop (d).

## 5. Decisions for the Principal (one at a time)

1. **S2 (planted-gap preparation in parallel):**
   > "From the S0378–S0402 consolidation, the Governance Engineer prepares the planted-gap design and the independent-check brief before the gate, builds and seals the copy at the gate, and runs the independent check without a separate go-ahead; I am told the result before reviewers start."
2. **S3 (minor access deviations):**
   > "A reviewer's access deviation that is disclosed, touches only lane book files or allowed corpus files already read, is not relied on by any finding, and whose checks were rerun or are not needed, is recorded in the window's ACCESS-DEVIATION.md and the window settles without a stop; it is listed at the next consolidation and in the access record. Sealed, hold-out, quarantine, 00-access/ material or git history remains stop (d)."
