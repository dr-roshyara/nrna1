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

## HANDOVER -03 (prepared 2026-10-01): Gate 1 proper, non-Claude coder (R2)
- **Status: PREPARED. AWAITING HUMAN HANDOVER.** Bundle -02 and prompt -02 are unchanged; both hashes were re-verified on 2026-10-01.
- **Operator guide:** `HANDOVER-03-INSTRUCTIONS.md` (= `~/F-APRIME-GATE1-HANDOVER-INSTRUCTIONS-03.md`), sha256 `d68525c4e4dabda3a56e725fba8638418d57148eff8094df4705ebb49e6de286`. It was reviewed by `kos-theory-reviewer` (PASS_WITH_LIMITATIONS); all 8 corrections are applied.
- **Session requirements:** a fresh session; memory, personalization and **web access** off; one attempt; nothing said beyond the frozen prompt.
- **The operator's current ChatGPT review conversation is excluded**, because it has seen Claude's conclusions.
- **Variant B** (the operator unpacks; 5 files; a 3-line prompt) is a **recorded deviation**: the coder does not verify the hash.
- **Return:** `~/F-APRIME-GATE1-RETURN-03/` (CODING.json, NOTES.md, TRANSCRIPT.txt, PROVENANCE.txt).
- **Return-defect rule:** a non-JSON or mis-structured return is **logged as a defect and never repaired** by Claude. The comparator runs only on an as-returned, parseable file; otherwise the defect is reported.
- **Known limitation:** possible training-data exposure if the repository was ever public (PROVENANCE `repo_public`).

## HUMAN DECISION (2026-10-01): handover -03 uses a Claude session, not a non-Claude coder
- **Human act:** "Its not necessary to have another coder. Claude independent session can handle it."
- **Executed as:** a fresh headless session with **Claude Sonnet 5.5** (RETURN-02 used Opus 5.5).
  - It runs in `~/gate1-r03-claude/` **outside the repository**.
  - Tools: Read and Write only. It sees the 5 bundle files only.
  - Guide: `HANDOVER-03-CLAUDE-INSTRUCTIONS.md`.
  - Return folder: `~/F-APRIME-GATE1-RETURN-03-CLAUDE/`.
- **Consequence (binding on every later claim):** this is within-family replication with a different model.
  - **The cross-family requirement of Gate 1 is waived by the human, not satisfied.**
  - Errors shared by all Claude models are not detectable.
  - R3 items tagged R2_DEPENDENT stay qualified: "cross-family reproduction untested".
- The non-Claude guide (`HANDOVER-03-INSTRUCTIONS.md`) is kept, unused, for possible later use.

## RESULT (2026-10-01): return -03-CLAUDE received, sealed, compared, reviewed
- Sonnet 5.5 vs 1ax/RETURN-02: act.state κ 0.50 · norm.deontic κ 0.62 · membership 0.667. Reviewer verdict: **NOT-REPRODUCED-SCHEMA-UNDERDETERMINED** (21/24 cells schema-admissible both ways; 3 coder deviations).
- Record: `RETURN-03-CLAUDE-REVIEW.md` · log F-LOG-0202. Gate 1 status, schema revision and downstream handling are **open for human decision**.
