# Gate 1 proper on the A′ observation layer (human act: Decision 1, 2026-09-30)

| | |
|---|---|
| Status | **PREPARED. AWAITING HUMAN HANDOVER to a non-Claude coder** (a human, or another model family). Claude cannot commission a non-Claude coder itself (no access; Codex excluded by standing rule), so this is a declared stop condition of the decision record |
| Bundle | `~/F-APRIME-GATE1-BUNDLE-01.tar` sha256 `40104fcac7551eeab409bfdaca48afab110e9fb6b7c206bbf4c38f4fd09b54d0` · commissioning prompt `~/F-APRIME-GATE1-COMMISSIONING-PROMPT.txt` |
| Contents | the frozen A′ records, de-cued (opaque ids + section + verbatim span; the lineage is withheld), SCHEMA-r2 §1–4, SOURCES, TASK (identical to the 1ax task + one independence sentence). **Excluded:** every Claude coding, review, description, hypothesis, F-LOG and expected finding |
| On return | hash-seal first. Then score against the 1ax coding with a frozen comparator (to be written and committed **before** the return is opened). Review; reconciliation. **Claude's coding is NOT ground truth**: disagreements are reported with their epistemic basis, and A′ is not modified because of a disagreement (the human decision rule) |

## DEFECT (disclosed): bundle -01 is VOID, and superseded by -02
- **Defect:** bundle -01 (`40104fca…`) was built **without `SCHEMA-r2.md` and `TASK.md`**. Wrong source paths (`LOOP-1AX/A/` instead of `LOOP-1AX/`); the `cp` / `sed` errors were not checked. Detected **before any handover**.
- **-01 status: VOID. Do not hand it over.** Its files are kept on disk for provenance.
- **Replacement:** `~/F-APRIME-GATE1-BUNDLE-02.tar` sha256 `6bad9adc4b7acb682398490fe745af5725fa51c61a7e8bf03d7087d60e673e27`, with prompt `~/F-APRIME-GATE1-COMMISSIONING-PROMPT-02.txt` sha256 `86c920da8f0a47b933d40d89ca07d06844a89aee81c0988f25d3ec1eee54473c`. Contents: README, TASK (the 1ax task + one independence sentence), SCHEMA-r2 (byte-identical to the frozen one), RECORDS, SOURCES.
- **Status: PREPARED (-02). AWAITING HUMAN HANDOVER to a non-Claude coder.**

## PROTOCOL DEVIATION (human decision, 2026-09-30): coder is a Claude subagent, not a non-Claude coder
- **Human act:** "we dont need gpt or any other coder. you are indpenedent reviewer and make a review."
- **Executed as:** a fresh Claude general-purpose subagent (Opus 5.5), given only the 5 bundle-02 files (hashes re-verified unchanged after coding), with instructions to read nothing else. It has no session history; not verified to be free of inherited CLAUDE.md system context.
- **Consequence (binding on every later claim):** this is a **within-family reproducibility check**, NOT the cross-family independence test Gate 1 was designed for. Correlated Claude errors are not detected by it. It must not be reported as Gate 1 proper satisfied.
- **Return sealed before reading:** `RETURN-02.sha256` (files at `~/F-APRIME-GATE1-RETURN-02/`).
