# Protocol A (WP-4C-1 execution capture): reconciliation. **EVIDENCE PACKAGE ONLY; NO INTERPRETATION**

| Step | Result |
|---|---|
| Design r0 | FAIL (B1–B3: path classification) → targeted repair |
| Design r1 | **PASS_WITH_LIMITATIONS** (B1–B3 and N1–N3 fixed; 14/14 path tests correct; no regressions) |
| Pre-execution safety check (main analyst) | `bash -n` OK; git subcommands only for-each-ref / rev-list / log / --version; writes only to OUT; the only `rm` is `$OUT/.tmp_*` |
| Execution | run verbatim; HEAD `cf2f6b913`; **from the root and from `app/`, the outputs are byte-identical**; exit 0; 0 capture errors; sealed `4a142ee53` |
| Output review | **PASS_WITH_LIMITATIONS**: integrity OK; earliest trace confirmed; neutrality OK; coverage OK |

## Evidence package (SOURCE FACT about the captured local history; RECORDING_ORDER only)
- Capture: **LOCAL_REF_SET** (25 refs; 4020 commits). **Completeness vs the host: NOT SHOWN.**
- **51** matching commits: 3 EXECUTION-TRACE · 47 DOC-OR-GOVERNANCE · 1 UNCLASSIFIABLE.
- **Earliest EXECUTION-TRACE:** `e4724a44`, author = committer date `2026-08-04T00:30:25Z`. Subject: "WP-4C-1 batch B1 -- RED for K1..K4": the target's own failing tests. **RECORDING_ORDER only** (M-TIME).
- Further EXECUTION-TRACEs: `939e35dc` (2026-08-04T15:19:21Z; WP-4C-1 implementation) and `cc1a3394` (2026-08-04T18:38:51Z).

## Limitations (recorded)
- **L1:** `cc1a3394` is mechanically an EXECUTION-TRACE but substantively WP-6 coverage work that only mentions WP-4C-1 (C2 has no subject test). No effect on the earliest trace.
- **L2 (self-contamination):** 21/51 matches are **this research programme's own artefacts** (2026-09-30 / 10-01), including the protocol commit. That inflates DOC-OR-GOVERNANCE (47 vs 27 historical). The reviewer recommends a **path-based R-SELF rule** (a declared programme-artefact root, initially `docs/knowledgeos/`, listed in a versioned SELF-ROOTS.txt; such commits classed SELF-ARTEFACT, excluded from the classes and the earliest trace). **Not applied:** it is a protocol change for future captures → **human decision**. It does not change the ET set here.
- **L3:** the earliest-trace commit changes a plan file whose **name** implies 19:00 on 2026-08-04; **every recording of it precedes that** in UTC and +02:00. Recorded only: file names are not timestamp sources (M-TIME).
- **L4:** the result string EXECUTION-TRACE-EVIDENCED-IN-CAPTURE is not defined in the protocol text (wording).

**No conformance, hypothesis or falsifier statement is made here** (Option A). Interpretation belongs to the discrimination-readiness review.
