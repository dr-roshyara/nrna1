# F-SERIES AGENT CONTRACT — per-file runbook · v1.1

**Status: FROZEN FOR REVIEW with protocol v1.1 (F-LOG-0004). Supersedes `20260925_1239_F-SERIES-AGENT-CONTRACT-v1.0.md`.**
This runbook is the per-file procedure. The rules and schemas it applies live in `prompts/contracts-v1.1/C01…C14`
(each rule lives in exactly one contract; this runbook only sequences them). You process **one F-ID** and stop.
Paths are relative to `docs/knowledgeos/chronological_knowelgeos_ablation_theory/` unless they start with `docs/`.

## §R-0 Before you start

1. Read in full: protocol v1.1, this runbook, contracts C01–C14, and the inherited S documents that C02/C04/C10 name
   (XC, and P3B v1.7 §1B, §1C, §1D, §11.1, §13.2, §13.4, §13.6, §13.10, §13.10a, §14.4). Verify their pinned sha256.
2. `python3 scripts/f_status.py`. Your F-ID is the "next F-ID". Its state tells you where to resume.
3. Choose `RUN = FR-F####-NNN` (a fresh NNN for a fresh attempt; resume with the same RUN).

## §R-1 Procedure — each gate must succeed before the next step

```
C READ (C01)
  python3 scripts/f_read_source.py --run RUN --info F_ID
  python3 scripts/f_transition.py F_ID READING --run RUN
  FOR k in 1..N:  python3 scripts/f_read_source.py --run RUN --page k F_ID | cat
                  append the page digest (C01 §3)
  python3 scripts/f_transition.py F_ID READ-COMPLETE --run RUN          # or READ-PARTIAL/READ-FAILED + --reason, STOP

D1 COMPLETE CONTENT EXTRACTION (C02, C03)
  python3 scripts/f_units.py --run RUN F_ID                            # writes UNITS.jsonl, prints the unit index
  walk EVERY unit in order; for each: create inventory item(s) (C02 §4, C03) or a disposition (C02 §5)
  write CONTENT-INVENTORY.jsonl, UNIT-DISPOSITIONS.jsonl, CATEGORY-CHECK.json
  python3 scripts/f_units.py --run RUN --status F_ID                   # must print CONTENT-COMPLETE
  python3 scripts/f_transition.py F_ID CONTENT-EXTRACTED --run RUN

D2 STRUCTURAL RECONSTRUCTION (XC via C04 §2)
  write files.jsonl, contributions.jsonl (every contribution cites inventory_refs; every item carried), index-proposals.jsonl
  python3 scripts/f_transition.py F_ID RECONSTRUCTED --run RUN

E1 ANALYSIS, LEVEL 2 (C04–C09)
  python3 scripts/f_crossfile.py F_ID                                  # candidates vs earlier AUDITED F-IDs
  write ANALYSIS.jsonl, ANALYSIS-CHECKLIST.json, CROSS-FILE.jsonl (every candidate dispositioned)
  python3 scripts/f_transition.py F_ID ANALYZED --run RUN

E2 HYPOTHESES, LEVEL 3 (C10)
  write research.jsonl (pre-registered; no outcome) — or pass --empty-reason
  python3 scripts/f_transition.py F_ID RESEARCHED --run RUN [--empty-reason "…"]

G AUDIT (C13)
  python3 scripts/f_audit.py F_ID --run RUN
  IF it requires the independent audit: STOP; the orchestrator dispatches a fresh auditor (C13 §3)
  python3 scripts/f_transition.py F_ID AUDITED --run RUN              # or AUDIT-FAILED

REPORT (§R-3). Do not open the next F-ID.
```

## §R-2 Order of work inside D1 (anti-summarization rule)

1. Extraction runs **before** any summary. `files.summary` and the analysis are written after `CONTENT-EXTRACTED`.
2. Walk the units in order. For each unit ask the 26 category questions of C02 §3. Record every hit. A unit may yield
   several items; an item may cover several consecutive units.
3. Do not decide importance. A one-line aside, a rhetorical definition or an odd notation is recorded like a theorem.
4. Only then fill `CATEGORY-CHECK.json` with, for each category, the count and **how** the whole file was checked
   for it.

## §R-3 Report (final message, ≤ 40 lines)

F-ID · path · content sha256 · pages read/expected · units (covered / dispositioned / markup) · inventory items by
category · definitions captured · contributions by type · lens checklist · cross-file candidates and dispositions ·
Level-3 records and pre-registered hypotheses · state reached · anything the human should look at · the next F-ID.

## §R-4 Forbidden (in addition to each contract's list)

Reading any F-file other than F_ID (except already-audited ledger records) · reading past F_ID in list order ·
summarizing before extraction · dropping content as irrelevant · writing outside the lane folder · editing any S-Series
artifact · editing a ledger line or an event · testing a hypothesis per file · adopting or canonicalizing theory.
