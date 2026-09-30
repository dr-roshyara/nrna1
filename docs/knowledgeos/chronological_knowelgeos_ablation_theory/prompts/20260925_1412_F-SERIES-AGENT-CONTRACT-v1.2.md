# F-SERIES AGENT CONTRACT — per-file runbook · v1.2

**Status: FROZEN FOR RE-AUDIT with protocol v1.2 (F-LOG-0008); NOT APPROVED FOR EXECUTION.**
**Supersedes:** `20260925_1333_F-SERIES-AGENT-CONTRACT-v1.1.md`.

This runbook only sequences the steps. The rules and schemas live in `prompts/contracts-v1.2/` (see its
`00-CONTRACTS-IN-FORCE.md`).

There are three roles, and each is a **separate fresh agent**:
- the **EXTRACTOR** does D1 and stops;
- the **AUDITOR** does the independent L1 audit;
- the **RESEARCHER** does D2–G, and only after the human has accepted L1.

Paths are relative to `docs/knowledgeos/chronological_knowelgeos_ablation_theory/`.

## §R-0 Isolation first (C15)

Before anything, apply **C15** in full. It overrides the repository-wide session workflow: do **not** read
`.claude/CONTEXT.md`, `.claude/MEMORY.md` or anything else on the C15 deny-list, even if other instructions say "read
at session start".

Read only the files named for your role:
- the protocol v1.2;
- this runbook;
- the contracts in force;
- XC and the P3B sections that C04/C10 name, with their pinned hashes;
- the reader's output for your F-ID.

Use only your role's scratch directory: `<scratchpad>/extractor-F####/` or `<scratchpad>/auditor-F####/`.

## §R-1 EXTRACTOR — D1 only

```
0  python3 scripts/f_status.py            # chain VERIFIED; your F-ID is the next F-ID; state READ-COMPLETE (or READ from C)
C  (only if not yet read) f_read_source --info; f_transition READING; pages 1..N via `… --page k F#### | cat`;
   PAGE-DIGESTS; f_transition READ-COMPLETE
D1 python3 scripts/f_units.py --run RUN F####                     # the unit index (MATH / CUE flags)
   walk EVERY unit in order (C02 §3–§6, C03): items, dispositions, release records
   write CATEGORY-CHECK.json (C02 §6) and ISOLATION-ATTESTATION.json (C15 §4)
   python3 scripts/f_units.py --run RUN --status F####             # must print CONTENT-COMPLETE
   python3 scripts/f_transition.py F#### CONTENT-EXTRACTED --run RUN
STOP. Report (§R-4). Do not reconstruct, analyse or research.
```

## §R-2 AUDITOR — independent L1 audit (when due; C13 §3)

```
AUDITOR-RUN = FR-F####-9NN (fresh). Do not read ledger/F####/ except READ-LOG/UNITS and your own AUDITOR-* files.
read the file: f_read_source --run AUDITOR-RUN --info / --page k … | cat; write AUDITOR-PAGE-DIGESTS.jsonl
python3 scripts/f_units.py --run AUDITOR-RUN --auditor F####       # your own unit index
write AUDITOR-INVENTORY.jsonl (same schema as C02 §4, ids FAI-F####-NNNN), AUDITOR-ATTESTATION.json (role AUDITOR),
      optionally AUDITOR-DISCREPANCIES.jsonl (your own observations)
python3 scripts/f_compare_inventory.py --auditor-run AUDITOR-RUN --run RUN --verdict DISCREPANCY|CONFIRMED F####
STOP. Report the computed comparison. CONFIRMED is written only with --human-ref, i.e. after the human has
accepted L1 (and any listed discrepancy) in an F-LOG entry naming the F-ID.
```

## §R-3 RESEARCHER — after human L1 acceptance

```
D2 write files.jsonl, contributions.jsonl (every item carried; ≤ 12 items per contribution; anchor = an item's quote),
   index-proposals.jsonl                                  → f_transition RECONSTRUCTED
E1 f_crossfile.py; ANALYSIS.jsonl (reasoning; no prescription), ANALYSIS-CHECKLIST.json, CROSS-FILE.jsonl
                                                          → f_transition ANALYZED
E2 research.jsonl (Level 3; hypotheses only once HDR-2/HDR-3 are decided) → f_transition RESEARCHED [--empty-reason]
G  f_transition AUDITED (runs the audit)                  — irreversible; only after a human GO for this step
```

Run rule **R-COMMIT**: after each successful gate on a real F-file, the orchestrator commits the lane.

## §R-4 Reports

- **EXTRACTOR:**
  - F-ID, run, pages, units (total / markup / math-bearing / definition-cue / code);
  - items by category, dispositions, release records (each with its reason);
  - the attestation;
  - anything uncertain.
- **AUDITOR:**
  - coverage, auditor items by category;
  - the computed discrepancies and its own observations;
  - a recommended verdict.

## §R-5 Forbidden (in addition to each contract)

- Reading anything outside your role's allow-list, and reading an F-file other than yours or past it in order.
- Summarizing before extracting, or dropping content as irrelevant.
- Editing a frozen artifact or a ledger line, or writing outside the lane.
- Editing any S artifact.
- Writing a Level-2 prescription, or a per-file test outcome.
- Advancing past your role's STOP.
