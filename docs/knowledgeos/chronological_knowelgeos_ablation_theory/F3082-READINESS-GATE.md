# F3082 READINESS GATE (v1.2)

**Question:** is F3082 eligible to start D1 (complete content extraction) under F-Series v1.2?
**Answer at writing (2026-09-25):** **NOT YET.** Four conditions are open (5–8). The evidence basis is ready and the
mechanism is ready and tested. Still missing: the independent re-audit, HDR-1, the human approval, and the human GO.

| # | Condition | Status | Evidence |
|---|---|---|---|
| 1 | F3082 reading evidence intact | **MET** | run `FR-F3082-001`: 2/2 pages, page hashes re-verified, digests valid, content sha256 = manifest (`1e6ff511…48cf7`); `READ-LOG.jsonl` differs from HEAD by one appended *refused* line only (F-LOG-0008 incident note); `PAGE-DIGESTS.jsonl`, `F-READ-INTEGRITY.jsonl`, manifest unchanged |
| 2 | F3082 state | **MET** | `READ-COMPLETE` (5 events, legal); the state ledger is hash-anchored (`F-STATE-ANCHOR.json`, 4,556 lines) and the chain VERIFIED |
| 3 | parked draft out of reach | **MET** | moved to `quarantine/F3082-draft-v1.0/` (with its generator `p1.py`); no pipeline path reads it; the C15 deny-list names it |
| 4 | v1.2 blocking findings closed in code | **MET (to be independently verified)** | F-01: stage freezes plus re-verification (`Freeze.*`). F-02: C15 plus attestations plus quarantine (`Extraction.test_F02_*`). 84/84 tests |
| 5 | independent re-audit of v1.2 | **OPEN** | a fresh read-only auditor, separate scratch; verdict pending |
| 6 | HDR-1 — human accepts the isolation residual (C15 §6) | **OPEN** | protocol v1.2 §F-11 |
| 7 | HDR-4 — human approval lines for protocol, runbook, contracts index | **OPEN** | until written, the reader and every gate refuse (verified on the real lane: `PROTOCOL-NOT-APPROVED`) |
| 8 | HDR-5 — human GO for F3082 D1 | **OPEN** | after 5–7 |
| — | HDR-2, HDR-3 | open, **not blocking D1** | they block hypothesis registration and checkpoint tests only |

**F3082 facts for D1 planning (counts only; no content):** 448 units: 22 markup, 83 HEADING, 193 LINE, 90 LIST-ITEM,
23 TABLE-ROW, 5 QUOTE, 24 MATH, 8 CODE.
- **Math:** 83 math-bearing units (v1.1 detected 24); markers are LaTeX commands in 73 units and inline `$` in 59.
- **Definitions:** 53 definition-cue units (v1.1: 31). Both page-digest quotes that v1.1 missed now fire.
- **Structure:** the file is two concatenated AI responses. Its independent L1 audit is due (first text F-ID).

**When all conditions are met, the sequence is:**
1. A fresh isolated EXTRACTOR runs D1 and stops at CONTENT-EXTRACTED.
2. Commit.
3. A fresh AUDITOR runs the independent L1 audit and the comparison is computed.
4. The human accepts or rejects L1.
5. Only then does L2 begin, under a separate GO.
