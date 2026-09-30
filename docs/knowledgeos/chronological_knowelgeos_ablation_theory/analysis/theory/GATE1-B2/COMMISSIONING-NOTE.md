# 1ag run B2: Gate 1 bundle coded by a **fresh Claude subagent** (frozen before the run)

| | |
|---|---|
| **Classification** | **SECONDARY, same-family (Claude). This is NOT Gate 1 proper** (which requires a non-Claude coder). It is not independent confirmation |
| Human act | "for 1ag, Gate 1 proper: give ~/F-GATE1-PROPER-BUNDLE-01.tar, start a new fresh subagent" (2026-09-29) |
| Bundle | `~/F-GATE1-PROPER-BUNDLE-01.tar` sha256 `08cf04b2784d00047b9941a7b43a9f51186ad3f87f6b59c3ce4e7f4d1ff0278a`, verified; the commissioning prompt sha256 `262af6ab686c71afac8a509f440e9da6ce0161fa3262298b075cae83f1182f9f`, verified. Unpacked into a non-git scratchpad; per-file hashes in `UNPACKED-MANIFEST.sha256` |
| Procedure | Two fresh headless Claude sessions, one per folder (GATE1-BUNDLE-01, RESERVE-BUNDLE-01). Each runs **inside its folder only**, with Read/Write tools only, and the instruction "Read README.md in the current directory and follow it exactly". Tool paths are audited afterwards |
| **Disclosed deviation** | Commissioning-prompt steps 1–2 (verify the hash, unpack) were done by the main analyst, because the subagents have no shell. Step 3's "independently" is realized as separate sessions per folder |
| Scoring (frozen instruments, unchanged) | `GATE1/gate1_agreement.py` and `RESERVE/reserve_agreement.py`, run unchanged in fresh directories. **Three comparisons:** (1) main-sealed vs B2; (2) B1 (the earlier blind Claude coding) vs B2, i.e. intra-family replication, where B1 is wrapped as `{"rows": B1}` (a relocation-only adapter, disclosed); (3) main vs B1 (already recorded; reproduced for reference) |
| Interpretation rule | B2 is a **second same-family replication**. B1~B2 agreement measures intra-family coding stability. Main~B2 tests whether the earlier main-coder-bias finding (F-LOG-0135/0136) reproduces. **Nothing here counts as INDEPENDENT** |
| Next | a review subagent audits the codings and the scoring; then reconciliation |
