# C04 — Structural reconstruction and structural analysis · v1.2 (delta)

**Base:** `contracts-v1.1/C04-structural-analysis.md`, sha256 `edcbaf07a5c112d887f0b296845d91860b09bc9a254fbf22e2847af9d6e86209`.

## Changes
1. **Reconstruction runs only after the L1 evidence layer is accepted.** When the independent L1 audit is due, RECONSTRUCTED requires a verified `INDEPENDENT-AUDIT-L1.json` (C13 §3) with human acceptance.
2. **Anti-catch-all (F-03).** A contribution carries ≤ 12 inventory items, and its `anchor` must contain, or lie inside, the quote of one of its items.
3. **Identifier guard (C15 §5)** on `files.summary`, `contribution_assessment`, `objects_touched`, contribution `statement` and `labels`.
4. **Level-2 records** (the shared ANALYSIS schema, C06 v1.2 §2) require `reasoning`, an ISO-8601 UTC `research_time`, and no prescriptive wording outside quotation marks (C08 v1.2 §1). The checklist reasons are ≥ 10 characters.
5. **Freezes.** RECONSTRUCTED freezes files, contributions, index-proposals and, when present, the independent-audit artifacts. ANALYZED freezes ANALYSIS, ANALYSIS-CHECKLIST and CROSS-FILE.
