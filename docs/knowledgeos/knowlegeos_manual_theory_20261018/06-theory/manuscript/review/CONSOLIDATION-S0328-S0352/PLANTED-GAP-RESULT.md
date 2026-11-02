# Planted-gap test, consolidation S0328–S0352: result

*Governance Engineer (session `57ddce41…`, the active Governance Engineer under Option A), 2026-11-02T12:59:13Z, after all five reports were saved (A `a464f6714`, B `c5c8ac98b`, C `6b912b6aa`, D `1057c3e1d`, integration `0985739fc`). Rulebook v1.4 §12; mandate §8.5.*

## Verification

- **Key** `PLANTED-GAP-ANSWER-KEY.md`: sha256 `30c50e6d68905a021815f83954b0ec19f931f58514f005f6289429782bd3595d`; **manifest** `PLANTED-GAP-COPY-MANIFEST.txt`: `78d65d73d4c0f6c2f2a1a93bd181c3181b89795b84e0b351e7ab4bc164953a86`. Both equal the access record (2026-11-02T10:32:26Z); released here unedited. **The key was opened only after every review touching the copy was saved.**
- **Reports** A, B, C, D and the integration report each match their saved sha256.
- **The copy** is unchanged since `66969f253` (211/211 against the manifest at scoring).
- **The preparation check** (§8.3a): PASS, before any reviewer started (access record; the freeze record `7c4361f3a` follows it).

## Blindness

| Reviewer | Status | Evidence |
|---|---|---|
| A (Ch. 6, §8.00–§8.09) | **blind** | its file list; no REVIEWER-NOTE item-4 file; `book_search.py --help` once without `--root` (usage text only) |
| B (§8.10–§8.19) | **blind** | "I reached no file listed in REVIEWER-NOTE item 4 … nothing in 00-access, and no git command of any kind" |
| C (§8.20–§8.30, change log) | **blind** | its file list; it notes that the session's starting git-status snapshot named files under `00-access/`, which it did not open |
| D (§8.73–§8.81, settlement and U1–U6 passages) | **blind** | its file list; nothing in `00-access/`, no working file, no git |
| Integration | **blind with respect to the plants** | one recursive grep for `CAP-09` and `DI-5` ran from the lane root (paths and line numbers only; not used; repeated on the copy). Neither term occurs on a planted line, the key was outside the repository, and the copy's line counts equal the working book's, so the output could not distinguish the copy from the base. **An access deviation, recorded; no effect on the score** |

**Blind and combined scores are the same.**

## Score (counts; four items from four plants)

| Item | Found by | Treatment in stage 1 | Result |
|---|---|---|---|
| **Q1** `06.03` L51 "§6.1 C3" → "§6.1 C9" (a stale pointer; §6.1 has C1–C8) | **A** (A-02, normal: "§6.1 has C1–C8 only. The lattice is §6.1 C3. The pointer is stale; no content is missing"; found by A's own heading/pointer script); integration confirmed | **correct:** "§6.1 C9" → "§6.1 C3"; REVIEWER-NOTE item 6 applied (stale pointer, not missing content) | **found** |
| **Q2** `08.11` L31 "§8.12 C3" → "§8.12 C12" (a stale pointer; §8.12 has C1–C9) | **nobody.** B read §8.11 in full and reported "of the forward pointers, every target exists"; its commission's pointer task (task 3) covers pointers **to later chapters**, and this pointer stays inside Ch. 8. B ran R3 on 19 targets, none of them §8.12 C3 | none | **missed** |
| **Q3** `09.19` L395 U1 "Decided: (b) later, after reading resumes" → "(a) next, before reading resumes" (a ruling misstated) | **D** (D-01, high: the quoted ruling in the same line, TODO T9-02 L12, and reading having resumed); integration confirmed against the record's verbatim text ("No: an edit. The record is unambiguous") | **correct:** restore "(b) later, after reading resumes" | **found** |
| **Q4** `08.23` L44 S0159 L297 quotation inverted ("technology … not domain") | **C** (F-01, high: the quote checker's one failure; checked at S0159 L297; heading and index agree with the source); integration confirmed | **correct:** restore the source wording | **found** |

**Blind: 3 of 4. Combined: 3 of 4.** Earlier tests: S0100 4 of 8; S0150 3 of 5; S0200 2 of 4; S0252 4 of 4; S0302 3 of 4.

## What the test answers

1. **Stale pointers: one found, one missed.** Q1 was found by a reviewer who wrote its own pointer check, and correctly diagnosed. Q2, the same class, was missed by a reviewer whose commission asked only about pointers **to later chapters**; a pointer within the chapter fell outside every check. With S0302's Q4, two of three planted stale pointers have now been missed where no check reaches them. **Nothing in the procedure lists every claim pointer whose target is absent** (P-Q4, still undecided).
2. **A Principal decision misstated in the book: found and corrected** against the record's verbatim text. Adding §9.19.9 to the scope (Principal, 2026-11-02) is what put it in front of a reviewer.
3. **An inverted quotation: found mechanically.** The quote checker reads the `[Sxxxx Lnn]` form; this is the tool's route working (at S0252 the `(L…)` form had to be found by hand).
4. **Limit:** four plants are a check, not a measure of reliability (counts, not rates).

## Instructions for stage 2 (the working book) — the stage-2 guard

The working book's four files equal the base (checked: `06.03`, `08.11`, `08.23`, `09.19`). **Do not apply to the working book:**
- **A-02** (`06.03` L51): the working book already reads "§6.1 C3". No change.
- **D-01** (`09.19` L395): the working book already reads "(b) later, after reading resumes". No change. *(D-01's R3 lines: all "stays true" or handled by D-02.)*
- **F-01** (`08.23` L44): the working book already quotes S0159 L297 correctly. No change. *(F-01's R3 lines, all "stays true", stay so.)*
- **Q2** (`08.11` L31): no remedy was made; the working book reads "§8.12 C3", which is right.
- **Every other stage-1 finding** rests on its own evidence and is assessed against the working book as planned — including **D-02** ("routed" in the 9.19 index row and §9.19.9 heading, and TODO T9-01 L11) and the integration's R3 note that **§9.19 L114** ("g1 not placed"; Q4 needs F13) is stale since U2. These are genuine findings, not plant repairs.

## Proposal (for the Principal; nothing decided)

- **P-Q4 (tool, the Professor's), renewed with a second miss:** a pointer check that lists every "§x.y Cn" reference in the sections under review whose target claim does not exist in the copy, for the reviewer to judge (REVIEWER-NOTE item 6). Until then, a commission's pointer task should cover **all** claim pointers in its sections, not only those to later chapters.

## Cost (the Professor's figures, reported)

A 852 s, B 834 s, C 860 s, D 792 s, integration 646 s; total 3,984 s (66 min 24 s).
