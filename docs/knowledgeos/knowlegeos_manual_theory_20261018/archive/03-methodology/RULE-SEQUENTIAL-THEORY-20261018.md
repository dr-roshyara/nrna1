# Rule SQ-1: the independent reviewer checks that the theory is built in sequence, without gaps

| Field | Value |
|---|---|
| Status | **APPROVED** by the Principal, 2026-10-18 (in-session, ≈22:25Z) |
| Decision (verbatim) | "Independent reviwer should inspect that if the theory is defined sequencial way and there is should not be gap in between. if there is a gap in the defintion of concept and ideas and theory it should be recoreded too." |
| Register | `METHOD-VERSIONS.md`, entry 24 |
| Relation to other rules | adds a check to the independent review (AR-10, AR-11 standing approval) and uses the concept IDs of CV-1. It removes no obligation |

## 1. What "sequential, without gaps" means

The book builds its theory in order:
1. **Defined before used.** Every concept is defined before (or where) it is first used, in reading order: earlier chapters before later ones, and earlier sections before later ones within a chapter.
2. **Each step rests on what came before.** Every principle, argument or result follows from definitions, premises and results already given, or from sources it cites.
3. **Forward references are marked.** If a passage must use something defined later, it says so explicitly, with a pointer to where it is defined. An unmarked forward reference is a gap.

## 2. What counts as a gap

| Type | Example |
|---|---|
| **Undefined** | a concept is used but never defined anywhere in the book |
| **Used before defined** | a concept is used before its definition, with no marked forward reference |
| **Missing step** | a conclusion does not follow from what came before; a premise, definition or argument between them is missing |
| **Missing link** | two related concepts, ideas or chapters are never connected, although the theory depends on the connection |
| **Circular** | concepts defined in terms of each other, with no independent starting point |
| **Inconsistent order** | a later section silently changes or assumes a different meaning from an earlier definition |

## 3. What the independent reviewer does

- At **each five-file review**, the reviewer reads the chapters (within the AR-11 scope: after its blind extraction is frozen) and checks the passages the batch added or changed, **and** what they depend on, against sections 1 and 2.
- The reviewer reports, for every gap: its type, its location (chapter, section), the concepts or ideas involved (with their `CON-` or idea IDs where they exist), and what would close it.
- "No gaps found" is a valid result only if the reviewer states what it checked.
- The reviewer is read-only. Its report is written in the review folder (ML-2: the chapter's `review/`, or `06-theory/manuscript/review/` for all chapters).

## 4. How gaps are recorded

- **Each gap gets an ID**, `SEQ-NNNN` (e.g. `SEQ-0001`), numbered in order, never reused. (`GAP-n` and `SG-n` are already in use in this project.)
- **Each gap is entered in the `TODO.md` of the chapter where it occurs**, with its ID, type, location, the concepts or ideas involved, and what would close it.
- **A gap in a concept's definition** is also noted in that concept's `follow_up` field in `06-theory/CONCEPT-REGISTER.tsv`. A concept whose definition has a gap does not pass CV-1's definition check, so it is INVALID until the gap is closed.
- **Work continues.** A gap does not stop reading or writing (as in CV-1 §4a). The Professor closes gaps as later sources or derivations allow, records how each was closed (L-4 revision record), and the TODO entry is closed with that reason.
- A gap the reviewer reports and the Professor disputes is not deleted: both views stay recorded, and the reasoning decides (one-page method: evidence over majority).

## 5. Open points (not decided by this rule)

- **Whole-book sequence check.** This rule checks the batch's passages and what they depend on at each five-file review. Whether there is also a periodic check of the whole book from start to end is not decided.
- **Existing chapters.** Whether Chapters 0–2 get a one-off sequence check of their full text now is not decided.
