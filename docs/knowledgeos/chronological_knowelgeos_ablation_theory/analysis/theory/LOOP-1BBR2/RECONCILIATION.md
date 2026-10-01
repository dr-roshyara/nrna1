# 1bb-r2: reconciliation. **FAIL on content → STOP for the human** (pre-registered: no r3)

| | |
|---|---|
| Frozen | `76411541f` · run 1: a timeout (no content; `3949f352d`) · run 2: SPEC-r2 produced, rate-limited before the changelog (`4c69fa9ab`) · review run 3 |
| Verdict | **FAIL** |

## Progress against r1
- D5, D8, D9 and N2 are **FIXED**.
- **G-SYM**, the symmetry rule that only an independent norm-state observable (NIF-A) can fire a falsifier, is **correct and symmetric** as written.
- Both scripts are **substantially executable**: a non-bare guard, local refs, no fetch, handled exits, deterministic output, hashed results, M-TIME respected in the script, and absence = NOT-EVIDENCED-IN-CAPTURE.

## Blocking issues (reviewer)
- **B-1:** CHANGELOG-r2 is missing; it was interrupted by the rate limit.
- **B-2:** a **precedence clause** keeps contradictory r1 text in force (NIF-B, the graded fallback, the recording-time fallback) with no tie-break. As a result:
  - **SV-1:** an H-D falsifier (P5) can still fire on behaviour that H-B explains;
  - **SV-2:** H-B falsifiers (P2 non-C1, P5-HB, P8-HB) can fire on the maker's records.

  **Symmetry is still violated through residual r1 text, not through the new rule.**
- **B-3:** DP-P4 is not exhaustive (no catch-all).
- **B-4:** both scripts hash a relative `BASH_SOURCE` after `cd`. This is a script bug, trivially fixable.
- **B-5:** the scoping script counts a candidate channel's own writers as executors, which **forces** a negative "NO_AS_NIF_A" for non-doc candidates: a constructed negative.
- **Other new defects:** ND-6..ND-11 (grading asymmetries SV-3/SV-5/SV-6; no M-TIME ordering rule for a git time vs NIF-A state times; script column and parsing residuals). There are also undisclosed changes to two FIXED items (FR-D10 outcome semantics; FR-D7 vocabulary).

## Diagnosis (INFERENCE; main analyst)
- **The repair loop is not converging.** The spec grew from about 116 KB (r1) to about 124 KB (r2). Each round fixes most defects and introduces new ones. The dominant mechanism is **layering**: r2 patches r1 under a precedence clause instead of replacing it, so contradictions persist.
- **A structural observation:** the two authorized searches are **descriptive**. "Does WP-4C-1 work exist, and when is its earliest trace?" and "does an independent norm-status channel exist?" **do not need the falsifier machinery** in order to be run correctly. They need only a small, read-only, reviewed capture script. The falsifier and symmetry rules matter only at the **later discrimination step**, where captured evidence is scored against H-D and H-B.
- **Coupling the descriptive searches to the full discrimination spec** is why they are blocked.
