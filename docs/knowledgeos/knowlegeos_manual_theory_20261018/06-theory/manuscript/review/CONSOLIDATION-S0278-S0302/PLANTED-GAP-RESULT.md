# Planted-gap test, consolidation S0278–S0302: result

*Governance Engineer (session `57ddce41…`, the active Governance Engineer under Option A), 2026-11-01, after stage 1 (`2b9441cc9`). Rulebook v1.4 §12; mandate §8.5.*

## Verification

- **Key** `PLANTED-GAP-ANSWER-KEY.md`: sha256 `c0427904385209c8a5add10d674d0a1aa96f27ef5721e2f8b1b34ad89b91a0cc`; **manifest** `PLANTED-GAP-COPY-MANIFEST.txt`: `6371c74180fd96915bbd4fe6f0619e0208232f47da9a842117976d1254986c23`. Both equal the access record (2026-10-31T17:18:57Z); released here unedited. **The key was opened only after every review touching the copy was saved** (A, B, C and the integration report, run 2).
- **Reports** A, B, C and the integration report each match their saved sha256.
- **The copy** still matches the manifest (188/188) at scoring.
- **The preparation check** (§8.3a): PASS, **before** any reviewer started (access record 2026-10-31T17:27:24Z).
- **Run 1** (interrupted by the usage limit 2026-10-31T17:39Z; its report A set aside in `00-access/consolidations/S0278-S0302/run1/`) is **not scored**; only run 2, fresh and independent, is scored (FREEZE.md).

## Blindness

| Reviewer | Status | Evidence |
|---|---|---|
| A (Sonnet; Ch. 2) | **blind** | its file list; the Professor's FREEZE note: no file of REVIEWER-NOTE item 4 |
| B (Opus; Ch. 3) | **blind** | "I reached no file in REVIEWER-NOTE item 4 … nothing in 00-access, no git history" |
| C (Opus; §8.52–§8.61, passages) | **blind** | its file list (the reviewers' folder, REVIEWER-INSTRUCTIONS, the copy, allowed sources) |
| Integration (Opus) | **blind** | "Item-4 files: none reached … nothing in 00-access; no git history; no file comparison" |

**Blind and combined scores are the same.**

## Score (counts; four items from four plants)

| Item | Found by | Treatment in stage 1 | Result |
|---|---|---|---|
| **Q1** `02.10` L20 "(Hyp, testable)" → "(Src, measured)" (an overstated label) | **A** (A-F8, normal: "A prediction from PK-P4 is not Src or measured"; A cites L107, the integration corrects it to L20) | **correct in substance:** relabelled "Hyp (derived from PK-P4)" | **found** |
| **Q2** `03.01` L17 "`[CON-0003 · invalid]`" → "validated" (status against the register) | **B** (B-01, high, from R1 and four other places); **A** noted R1's flag as out of scope; merged by the integration | **correct:** tag → `invalid` | **found** |
| **Q3** `08.56` L40 "The sources do not say which." → "The sources settle it: reading (b), the parser decides." (readings collapsed) | **C** (C-01, high: contradicts L24, L86, the index, the map, the change log and §8.58 L57); integration confirmed, "no content is missing" | **correct, with an addition:** "*The sources do not say which; L28 ('stores the normalized meaning') fits (b).*" The base sentence is restored; the clause on L28 is new | **found** |
| **Q4** `03.05` L46 "(compare §3.10 C7, idempotence)" → "§3.10 C11" (a pointer to a claim that does not exist) | **nobody.** B and the integration both reported a **different** defect **on the same line** (B-08: "regenerates" vs "no longer regenerates", modal vs actual); neither checked the pointer. B ran R3 on "§3.10 C4" but not on the pointers of the lines it read | none | **missed** |

**Blind: 3 of 4. Combined: 3 of 4.** Earlier tests: S0100 4 of 8; S0150 3 of 5; S0200 2 of 4; S0252 4 of 4.

## What the test answers

1. **Q1 — an overstated label: found.** The label check of task 1 works on a claim's head against its body.
2. **Q2 — status against the register: found.** R1 surfaced it to two reviewers; one judged it, one noted it as out of scope (correct scope discipline).
3. **Q3 — readings collapsed into one: found and correctly diagnosed** as a stated reading, not missing content (REVIEWER-NOTE item 6 used).
4. **Q4 — a stale pointer: missed,** although a reviewer read and corrected the same line for another reason. **No check verifies that a cited claim exists:** R3 lists who cites a claim the reviewer names; nothing lists the pointers in a section whose target is absent. At S0252 the reverse class (content missing, pointer right) was found because the pointer was cited from several places; a single wrong pointer has no such echo.
5. **Limit:** four plants are a check, not a measure of reliability (counts, not rates).

## Instructions for stage 2 (the working book) — the stage-2 guard

The working book's four files equal the base (checked: `02.10`, `03.01`, `03.05`, `08.56`). **Do not apply to the working book:**
- **A-F8** (`02.10` L20): the working book already reads "(Hyp, testable)". No change is needed; "derived from PK-P4" arose only from the plant.
- **B-01** (`03.01` L17): the working book already tags CON-0003 `invalid`. No change. *(B-01's R3 lines, all "no change needed", stay so.)*
- **C-01** (`08.56` L40): the working book already reads "The sources do not say which." **Do not add the clause on L28**: it came from repairing the plant; if the Professor holds it as a finding about S0293 L28, it needs its own evidence and a separate entry.
- **Q4** (`03.05` L46): no remedy was made; the working book reads "§3.10 C7", which is right. **B-08** (modal vs actual on the same line) is a genuine finding on its own evidence and is assessed against the working book as planned.
- **Every other stage-1 remedy** (Ch. 2: 17; Ch. 3: 17; Ch. 8, map, outline; CON-0001 VALIDATED → PROVISIONAL) rests on its own evidence and is assessed against the working book as planned.

## Proposal (for the Principal; nothing decided)

- **P-Q4 (tool, the Professor's):** a **pointer check** that lists every "§x.y Cn" reference in the sections under review whose target claim does not exist in the copy, for the reviewer to judge (stale pointer or missing content, REVIEWER-NOTE item 6). Q4 shows that reading a line does not catch a wrong claim number in it.

## Cost (the Professor's figures, reported)

Run 2: A 857.2 s, B 849.4 s, C 1,022.5 s, integration 1,766.7 s; total 4,495.8 s (74 min 56 s). Run 1 (interrupted, discarded) not counted. Confirmed defects in the rotated chapters: Ch. 2 17, Ch. 3 17.
