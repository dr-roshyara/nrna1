# Working instructions in force under Rulebook v1.4

| Field | Value |
|---|---|
| Status | **Index, maintained by the Governance Engineer** (the Principal, 2026-11-03: "approve" — "The Governance Engineer keeps a one-page index of the working instructions in force under Rulebook v1.4 (each with its date and decision-record entry); the Professor and every brief cite it. Folding them into a Rulebook v1.5 is decided after the theory-turn chain.") |
| Authority | **None of its own.** Each instruction's authority is its entry in `PRINCIPAL-DECISION-RECORD-RULES-20261018.md` (cited by addendum time). If this index and the record differ, the record wins and this index is corrected |
| Rulebook | v1.4 in force; **no v1.5** |
| Updated | 2026-11-11 |

## Reading and windows

| # | Instruction (short form) | In force since | Record |
|---|---|---|---|
| W1 | **Standing instruction:** the Professor reads without a per-window go-ahead, stopping at each consolidation gate and on stop conditions | 2026-11-02 | 2026-11-02T01:21:05Z |
| W2 | **Window size:** at most 5 sources in index order (v1.4 §3), from one conversation (siblings first), ≈ 4,000 lines max; every source read full | 2026-11-03 (C101, C106) | 2026-11-03T01:32:13Z; 2026-11-03T02:51:10Z |
| W3 | **Window size after the trial (approved 2026-11-11; sent; applies when reading resumes at S0403):** up to ten sources per window within the 4,000-line cap for coherent series, review split into two commissions above five; other windows at most five; re-checked at the S0403–S0427 consolidation. *Supersedes W2's limit of five for coherent series.* Earlier trial: S0383–S0402 windows up to 10 sources S0383–S0402 windows up to 10 sources within the 4,000-line cap, review split into two commissions; trial measures reported at the S0378–S0402 consolidation. *Split as C107 S0383–S0391, C108 S0392, C109 S0393–S0396, C110 S0397, C111 S0398–S0402 (applied); the S0403–S0427 clause (up to ten within the cap) **sent** (reported by the Professor, 2026-11-03)* | 2026-11-03; 2026-11-11 | 2026-11-03T04:10:18Z; 04:11:03Z; 2026-11-11T15:45:26Z |
| W4 | **Section numbers:** Ch. 8 continues §8.100 onward (08.100-…); 08.99 stays the change log; tools parse 2–3 digits, sort numerically; test `tmp/tools/test_section_numbers.py` | 2026-11-03 | 2026-11-03T03:58:14Z |
| W5 | **Changed sources:** source closing checks the allowed list's disk status; S0386 used as on disk; **S2444 needs a ruling before it is opened** (access record) | 2026-11-03 | 2026-11-03T05:40:25Z |
| W6 | **Time records:** Reading · Drafting · Waiting · Settlement · Rework, non-overlapping | 2026-11-01 | 2026-11-01T17:32:50Z |
| W7 | **Implementation ideas** may be noted in `07-implementation-ideas/IDEAS-LOG.md` (outside the book; never cited; not reviewed) | 2026-11-03 | 2026-11-03T02:57:42Z; 03:38:47Z |
| W8 | **Role classification:** approved 2026-11-11 as in `ROLE-CLASSIFICATION-PROPOSAL.md` (sent 2026-11-11; applies when reading resumes at S0403): items tagged by role from the next window; supporting devices and estate records one line with their home; themes and summaries mark roles; register role column filled on next review; reviewers check tags (a wrong tag is a miss); effect reported at S0403–S0427 | 2026-11-03 (sent, reported by the Professor) | 2026-11-03T06:53:59Z |

## Review and access

| # | Instruction | In force since | Record |
|---|---|---|---|
| A1 | **No git history**, diffs, blame, historical versions or commit listings, for the Professor and every reviewer | 2026-11-02 | 2026-11-02T02:56:29Z |
| A2 | **A file a reviewer may not open, it may not search** | 2026-11-01 | 2026-11-01T17:32:50Z (item 3) |
| A3 | **Copy lists:** every commission lists the files that may be copied to scratch; no directory copies or globs | 2026-11-02 (C98) | 2026-11-02T17:38:57Z |
| A4 | **Allowed list path:** every commission names `CORPUS-ALLOWED.tsv`'s full path | 2026-11-03 | the Principal's ruling in `review/CONSOLIDATION-S0353-S0377/ACCESS-DEVIATION.md` |
| A5 | **`02-FILES.jsonl`: do not open** (reviewers); tools may read it | 2026-11-03 | 2026-11-03T04:11:03Z |
| A6 | **Minor access deviations** (disclosed; lane book files or allowed corpus already read; not relied on; rerun or not needed): recorded, no stop. Sealed, hold-out, quarantine, `00-access/` or git history: stop (d) | 2026-11-03 | 2026-11-03T02:42:46Z; 02:52:37Z |
| A7 | **Outside-authors rule:** no private person's or outside author's name reproduced (eponymous terms and citations allowed) | 2026-10-31 | 2026-10-31T17:24:15Z |
| A8 | **Theory turns:** three reviewers (two `kos-lane-reviewer` on different models, one `kos-theory-reviewer`), blind-first | per commission (Req/A, T9-02, the R2→R1 chain) | 2026-11-02T15:46:33Z |

## Consolidations

| # | Instruction | In force since | Record |
|---|---|---|---|
| K1 | **Planted-gap preparation off the critical path:** the GE prepares before the gate, builds and seals at the gate, runs the independent check without a separate go-ahead; the Principal hears the result before reviewers start | S0378–S0402 | 2026-11-03T02:42:46Z (S2) |
| K2 | **Per-window report** of findings and misses, and restatement counts | S0353–S0377 on | 2026-11-03T01:32:13Z; 02:51:10Z |
| K3 | **Chapter summaries** in `06-theory/manuscript/SUMMARIES/NN.SUMMARY.md` (metadata, never cited); block summaries for Ch. 8 and Ch. 9; checked only for chapters in scope | from S0378–S0402 | 2026-11-03T01:59:49Z |
| K4 | **Themes list** `SUMMARIES/THEMES.md` (metadata; drafts cite sections) | S0353–S0377 on | 2026-11-03T02:51:10Z |

## Chapter 10 and theory turns

| # | Instruction | In force since | Record |
|---|---|---|---|
| T1 | **Ch. 10:** consequences 1–6 and plan steps 1–2 applied, reviewed at S0378–S0402; T10-15 prepared, not run; **stage 1 not prepared** | 2026-11-03 | 2026-11-03T03:54:51Z; 2026-10-31T15:16:17Z |
| T2 | **Chain after the S0378–S0402 consolidation:** turn 1 (A and output forms, T9-22), then turn 2 (derivation chain); reading pauses; commissions `COMMISSIONS-CHAIN-R2-R1-THEORY-TURNS-20261103.md` (prepared, not issued). **Turn 1 issued 2026-11-11 (sent), three Claude reviewers, none on an outside service; turn 2 prepared, not issued** | after S0402 | 2026-11-03T04:02:46Z |

## Change log of this index

| Date | Change |
|---|---|
| 2026-11-03 | First version |
| 2026-11-03 | W3 clause and W8 marked sent (the Professor's gate report: "You sent both today"); the index citation in force from C111's briefs |
| 2026-11-11 | W3: window size after the trial approved (record 2026-11-11T15:45:26Z) |
| 2026-11-11 | W8: role classification approved |
| 2026-11-11 | T2: turn 1 approved for dispatch |
| 2026-11-11 | W3, W8, T2 marked sent (the Principal: "sent") |
