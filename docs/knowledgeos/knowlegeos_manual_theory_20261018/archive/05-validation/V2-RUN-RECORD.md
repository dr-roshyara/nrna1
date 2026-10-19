# V-2 run record

| Field | Value |
|---|---|
| Plan | M-03 §5 V-2 · execution material `V2-REVIEWER-INSTRUCTION.md` |
| Decisions | **D-1:** MRP-1.1 adopted **as a candidate** · **D-5:** reviewer = sub-agent. Both by the Principal, in session, 2026-10-18; recorded here, not in the governance log (V-2 uses background files only) |
| D-6 | **Adjudicator = a third fresh session** (Principal, in session, 2026-10-18). Implementation: one fresh sub-agent per source file, identical instructions, sees neither extraction before it is frozen; neither Professor nor the reviewer adjudicates |
| Corpus content | none |

## Steps

| # | Step | State | Evidence |
|---|---|---|---|
| 1 | Source set fixed; no line-break characters that break the anchor tool | done | `V2-REVIEWER-INSTRUCTION.md` §1; check 2026-10-18 |
| 2 | Output format amended to one record per source, **before** the reviewer started | done | `V2-REVIEWER-INSTRUCTION.md` §3 note |
| 3 | Professor extraction, blind (B-08 not consulted): 7 records, **538 items**; anchor check all PASS/PASS-WS; every line accounted for | done | `v2-professor/` |
| 4 | Professor extraction frozen | done 2026-10-18T13:49:27Z | `v2-professor/FREEZE.sha256` (manifest `90d7263e…918c`), READ-LOG |
| 5 | Reviewer extraction (fresh-context sub-agent, same model family): 7 records, **854 items** | done | `v2-reviewer/` |
| 6 | Reviewer extraction frozen | done 2026-10-18 (UTC, logged) | `v2-reviewer/FREEZE.sha256` (manifest `359cb168…3f4d`), READ-LOG |
| 7 | Reviewer access audit from its transcript: 7 Read (exactly the 7 sources), 7 Write + 20 Edit (only its own 7 output files), no shell, search or web | **PASS** | tool-call list parsed from the transcript (paths and counts only) |
| 8a | Anchor check, reviewer: **854 / 854 PASS** | **PASS** | `quote_anchor_check.py` |
| 8b | Line coverage, reviewer: 6 of 7 complete; **E-04 leaves line 40 (a blank line) unaccounted** | technical defect, minor | coverage check. Technical, not semantic: no content is on that line |
| 9a | Neutral pairing worksheets: items grouped only by overlapping line ranges; readers **blinded** as X/Y with a random assignment per file; the key is held outside the workspace (sha256 `6d126990…1d18`) | done | `v2-adjudication/WORKSHEET-01…07.md` |
| 9b | Adjudication: 7 fresh sessions (one per source), each reads the source first, then the worksheet; works at proposition level; classes MATCH · OMISSION · MISREADING · LABEL-ERROR · SCOPE · BOTH-DEFENSIBLE · SPURIOUS · NEITHER | **done**; access audit PASS for reads (file 06 wrote via a shell heredoc, procedural only) | `v2-adjudication/ADJ-01…07.md`, `FREEZE.sha256` |
| 9c | Un-blinding and disagreement register | done | `v2-adjudication/LABEL-KEY-revealed.tsv`, `V2-DISAGREEMENTS.tsv` (366 non-MATCH rows) |
| 10 | Capture–recapture | **not estimable** (population built from the union; same-model dependence; 5 NEITHER found > estimate) | `V2-REPORT.md` §3 |
| 11 | Result | **FAILURE (narrow):** 2 Professor SPURIOUS items = cross-document inferences unmarked, which map to the predeclared LABEL-ERROR | `V2-REPORT.md` |

## Known limitations of this run

- I read RQ-002 before (B-08). V-2 therefore tests **fidelity, not discovery**.
- The reviewer is the same model family as Professor, so shared blind spots are likely (M-03 RF-1).
- The anchor-tool defects TF-1/TF-2 are unfixed; step 1 shows TF-1 cannot affect these seven files.
