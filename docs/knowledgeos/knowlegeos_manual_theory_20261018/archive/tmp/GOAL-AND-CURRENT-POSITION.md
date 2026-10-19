# Goal and current position: read this first, every time

> **Superseded as the working picture (2026-10-19, Rulebook v1/v1.1):** read `06-theory/THEORY-MAP.md` first. The method is `03-methodology/RULEBOOK-v1.1.md`. This file is kept as history.


## The goal (Principal, 2026-10-18)

**Reconstruct and extract the KnowledgeOS theory from the original corpus of about 2,700 files, read in chronological order, with complete source traceability.** The deliverable is the **theory book**.

- S-series documents give only the **rules**; they are not the research subject.
- No readiness assessments, no redesigning the method, no S-series analysis.

## How (approved method M-04 v1.0 + working practice)

**Per source, in index order (`02-FILES.jsonl`):**
1. Check the path is on the allowed list. Sealed and hold-out files are never opened.
2. Read the whole source.
3. Write `tmp/corpus-reading/records/E-<S-id>.md` with business-language meaning and quotes with line anchors.
4. Run `quote_anchor_check.py`; every quote must PASS.
5. Compare each idea with the book; label it, examine it by reasoning, write it into the book text (`tmp/theory/R01-BOOK-INTEGRATION-<range>.md`), dispose of every idea, and look back.
6. Log the read in `tmp/corpus-reading/READ-LOG-CHRONO-20261018.tsv`.
7. Then, and only then, open the next source.

**Every 5 sources:** review the book as one work; decide continue / correct / pause.

**Unclear concept:** research it in authorized corpus documents (logged as a contextual lookup) and credible external sources. Keep source / context / external / interpretation apart. Unresolved → `TODO — UNDEFINED` or `TODO — MEANING UNRESOLVED`.

**Ask the Principal** only for a genuine blocker: one question, with evidence and a recommendation.

**Never:** edit frozen documents, governance records, the authority store or Git; pass corpus content to sub-agents; call a hypothesis a finding.

## Rules added by the Principal on 2026-10-18 (reread 2026-10-19; `03-methodology/`)

- **D-C1:** corpus access APPROVED, with the scope recorded (allowed list, exclusions, logging; residual risk accepted).
- **D-R5:** write into Ch. 0–2 and all later chapters; **the Professor decides the chapter structure**. Recommended: a chapter plan file.
- **ML-1:** one folder per chapter; **every NEW section in its own file** with a **References list at the end** (path + S-ID required; lines + E-record recommended); CH-00/01/02 stay single files (exception).
- **ML-2 / 2a / 2b:** reviews go in `chapter_NN/review/`; all-chapter reviews go in `06-theory/manuscript/review/` (C2 → `review/C2-S0011-S0015/`).
- **CV-1:** every concept gets a `CON-NNNN` ID + status **VALIDATED or INVALID** (reason in `basis`) in `06-theory/CONCEPT-REGISTER.tsv` (an L-9 exception). Shown in the book as `[CON-NNNN · validated]`. INVALID → the chapter `TODO.md`; continue. Several concepts for one thing: separate IDs, `same_subject_as`, qualified names. The reviewer makes a second attempt on INVALID items. Back-fill of Ch. 0–2: open.
- **Still frozen (L-9, pending D-R6):** the idea register, the official read log, concept-evolution entries. The read log stays in `tmp/`.

## Principal direction (2026-10-18 ≈22:29Z)

"follow the rules in future . you must not correct everythign for the past work." Rules apply **forward**; no back-fill or retroactive repair of past work.

## Current position

| Item | State |
|---|---|
| Last source with a verified record **and** written into the book | **S0018** (2026-10-18 ≈22:40Z UTC): Ch. 0–1 RC-36…RC-41; Ch. 3 §3.1–3.4; 23 concepts CON-0001…0023 |
| Next source | **S0028** (S0026, S0027 done under RK-2: Ch. 3 §3.8, Ch. 4 §4.1; CON-0064…0071). Review C5 after **S0030** |
| Independent review C-1 (S0006–S0010) | DONE 2026-10-19: SOUND WITH MINOR ISSUES; 3 foundational + 28 ordinary findings, all foundational accepted and corrected (`06-theory/manuscript/BASELINE-REVISIONS.md`; `tmp/review/C1-S0006-S0010/`) |
| Access | **open in this lane:** the lane's own rules govern (Principal 21:44Z); approval recorded in `00-access/ACCESS-RECORD.md`; sealed and hold-out files excluded |
| Write locations | **book:** `06-theory/manuscript/` (Principal: "Write the theory manuscript in …/06-theory/manuscript"), revisions recorded in `BASELINE-REVISIONS.md` (RC-n) · **decisions:** `03-methodology/` (METHOD-VERSIONS) · **access:** `00-access/ACCESS-RECORD.md` · **per-source records and read log:** `tmp/corpus-reading/` |
| Read before the restart, not recorded (do not count) | S0105–S0116 |
| PROPOSED rules awaiting the Principal | C9, C10, E8, E9, the `tmp/theory/` location |

## Rule RK-1 (APPROVED 2026-10-19, applies from S0026)

One home per fact; hashes once per five-file batch; INVALID concepts in the register (chapter TODOs only for non-concept problems and SEQ gaps); chapter change logs closed; **at most two sources per turn, then report**; strength check and a real counterexample attempt before VALIDATED; shorter records. Text: `03-methodology/RULE-RECORD-KEEPING-20261019.md`.

## Rule RV-1 (APPROVED 2026-10-19, from review C5)

The independent reviewer checks the **theory**: conceptual correctness · mathematical derivation · sequential flow (SQ-1); source support only where it affects concepts or reasoning. Bookkeeping (quotes, hashes, logs, records) is the Professor's. Applies to batch and whole-book reviews. Open: when whole-book reviews run. Text: `03-methodology/RULE-REVIEWER-MANDATE-20261019.md`.

## RV-1 independence (APPROVED 2026-10-19)

Review order: blind extraction frozen first → Professor's brief → reviewer asked to refute, not confirm → findings stand unless disproved with evidence. Every report states: process, not personal, independence. **Open:** whole-book review timing; commits at checkpoints.
