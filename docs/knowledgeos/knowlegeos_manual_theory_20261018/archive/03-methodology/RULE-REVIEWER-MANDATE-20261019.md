# Rule RV-1: the independent reviewer's mandate

| Field | Value |
|---|---|
| Status | **APPROVED**, recorded 2026-10-19 on the Principal's instruction. The Principal's message gave the role definition and said it "should be recorded as a rule amendment before the Professor treats it as formally in force". Recorded here, it is in force |
| Register | `METHOD-VERSIONS.md`, entry 26 |
| Applies | from review C5 (S0026–S0030) onward, and to every whole-book review. Reviews C1–C4 stay as they were run |
| Changes | the content of the independent review's second phase (AR-10, AR-11). SQ-1 becomes one of the three areas below |
| Keeps unchanged | the AR-11 standing approval and its access discipline (a recorded scope per review; read-only; sealed and hold-out files never opened) · the blind first phase, frozen before the reviewer sees the Professor's work · CV-1 statuses and the §8a second attempt · the Principal's reserved decisions |

## 1. Purpose

The independent reviewer is a **specialist quality controller for the theory**. It is not a second administrator of the research records.

## 2. The three areas of the mandate

| Area | Questions the reviewer answers |
|---|---|
| **1. Conceptual correctness** | Are the concepts clearly defined? Do they make sense? Are they used consistently across the book? Can the reviewer find a counterexample that disproves a concept marked VALIDATED? For a concept marked INVALID, can it be validated (CV-1 §8a)? |
| **2. Mathematical derivation** | Are the mathematical and logical steps valid? Do the conclusions follow from the assumptions? Are the equations, proofs and intermediate steps correct? Are assumptions or undefined terms missing? |
| **3. Sequential flow of the theory** | Is the theory developed in the right order? Is each concept introduced before it is used? Does each step follow from the steps before it? Are there gaps, circular reasoning or contradictions across chapters? (This is rule SQ-1; gaps keep their `SEQ-nnnn` IDs.) |

## 3. Source support: only where it matters to the theory

- The reviewer reads the relevant original sources. The blind first phase gives it its own reading of them.
- It judges **whether the theory is supported by the sources when that affects a concept's validity or the reasoning**. Examples:
  - a book sentence stronger than its source;
  - a concept validated on evidence the source does not give;
  - a contradiction between sources that the theory ignores.
- Routine evidence administration remains the **Professor's** responsibility, and the reviewer does not check it:
  - quote formatting;
  - line-total questions;
  - file fingerprints and hash tables;
  - read logs and session logs;
  - change history;
  - record layout;
  - completeness of record sections that do not affect the theory.

## 4. Who owns what

| Role | Owns |
|---|---|
| **The Professor** | source reading · evidence records · quotations and the quote check · change history and fingerprints · routine research bookkeeping · writing the theory |
| **The independent reviewer** | independent evaluation of concepts, mathematical reasoning and the sequence of the theory (§2), and source support where it affects them (§3) |
| **The Principal** | decisions reserved to the Principal, including unresolved governance questions |

## 5. Where the mandate applies

- **Each five-source batch review:** the reviewer evaluates the **new and changed theory** of the batch, and what it depends on.
- **Each whole-book review:** the reviewer evaluates **whether the entire theory remains coherent**: concepts used with one meaning throughout, the whole sequence from the start, and early claims against later evidence.

## 6. Report format

The report has these parts:
1. A verdict on the theory.
2. Findings, each with:
   - its area (conceptual, mathematical, sequential);
   - its severity (FOUNDATIONAL · ORDINARY · NOTE);
   - its location;
   - what is wrong;
   - the evidence;
   - the correction it recommends.
3. A CV-1 table: a spot-check of the VALIDATED concepts and the §8a result for the INVALID ones.
4. An SQ-1 gap table.
5. What the reviewer checked and found correct.

Findings about routine bookkeeping are out of scope. If the reviewer notices one anyway, it lists it in one line under "Out of mandate", without assessing it.

## 7. Open point (not decided by this rule)

**When whole-book reviews run.** The role definition calls them "periodic" without fixing when they run. The Professor's proposal, awaiting the Principal: every 25 sources, or when a chapter is declared complete, whichever comes first, plus one review of Chapters 0–2 soon, since those chapters predate most of the rules.

## Addendum (2026-10-19T08:16:47Z): how a review is asked (Principal)

**Principal (verbatim):** "when the independent reviewer should work, you tell to him review . you tell what you have developed this theory ,and you ask if that makes sense or if that concept/idea is valid ?"

**So every review starts from a brief written by the Professor:**
1. **What was developed:** the new or changed sections; for each, the claim, the concepts (with their CV-1 status) and the derivations.
2. **The questions:** for each item, does it make sense? Is the concept valid? Does the step follow? Is anything used before it is defined?
3. **Where to check:** the source lines each item rests on, so the reviewer can check source support where it matters (§3).

The reviewer answers the questions within the three areas of §2.

**Open, for the Principal:** whether the blind first phase stays. It was designed to check the Professor's reading of the sources, which §3 now leaves with the Professor.

## Addendum (2026-10-19T08:24:52Z): keeping the reviewer independent (Principal)

**Principal (verbatim):** the concern, "that means the reviewer is not independent but your assistant to give conformation on what you have done"; the approval, "yes record item 1, leave 2 and 3 open".

**Why:** a reviewer briefed with the Professor's theory and asked "does this make sense?" starts from the Professor's framing and tends to confirm it. The safeguards below keep the brief (previous addendum) without that effect. They also close the open point of the previous addendum: **the blind first phase stays.**

**Every review runs in this order:**
1. **Blind first.** The reviewer reads the batch's sources and writes its own understanding of the theory in them. That extraction is frozen (with its fingerprint) before the reviewer sees any of the Professor's work.
2. **Then the brief.** The Professor's brief gives what was developed: sections, claims, concepts with their CV-1 status, derivations, and the source lines they rest on.
3. **Refute, not confirm.** The reviewer is asked to find where the theory is wrong, unsupported, out of order or circular, comparing it with its own frozen reading. It may raise problems the brief does not ask about. "No finding" counts only if the reviewer states what it tried to break and how.
4. **Findings stand unless disproved.** The Professor may set a finding aside only with evidence or a derivation. Disagreement alone is not enough. A disputed finding stays on record with both views, and the reasoning decides (SQ-1 §4; one-page method: evidence over majority).

**The limit that remains:** the reviewer is another instance of the same AI system, started by the Professor. Its independence is **process independence** (separate role, separate context, blind start), not **personal independence**. In the book's terms (Ch. 1, RC-30), every review report must say so. Only a human reviewer, the Principal or someone the Principal names, gives personal independence.

**Still open (not decided here):** when whole-book reviews run (§7) · whether the workspace is committed at checkpoints, and by whom.
