# Rulebook v1.1 (with v1.2 amendment of §5): KnowledgeOS theory research

| Field | Value |
|---|---|
| Status | **APPROVED** by the Principal, 2026-10-19. v1 was approved at 08:31Z; the core loop of §2 was given by the Principal and approved the same day |
| Replaces | `RULEBOOK-v1.md` as the operating rules. v1 and every earlier rule document (M-04, AR-*, CV-1, ML-*, SQ-1, RK-1, RK-2, RV-1) stay as history |
| Applies | from S0030 onward; no retroactive change |

## 1. Purpose

Build the theory of KnowledgeOS from the corpus. **The work is the theory.** What matters is whether it makes sense and is valid. Whether the book's wording matches the source word for word does not matter.

## 2. The core loop, per source

1. **Read** the whole source, with the theory map (`06-theory/THEORY-MAP.md`) in mind.
2. **Validate** each concept or idea by reasoning. Does it make sense? Does it survive a real counterexample?
3. **Is it already in the book?** If yes, update that section. If no, write it in a new section or subsection.
4. **References** go at the end of the file: source, S-ID, lines.
5. **Mark it read:** one script run (`tmp/tools/close_source.py`) checks the allowed list, takes the fingerprint and writes the read log.
6. **If a concept might not be valid,** say so in the text and add it to that chapter's `TODO.md`.
7. **Update the theory map.**

## 3. Writing

- A section has this order: plain language → definitions → claims and derivations → open questions → references.
- Each claim is labelled as the source's statement (**Src**), a derivation (**Ded**), an interpretation (**Int**) or a hypothesis (**Hyp**).
- No claim is stronger than its evidence.
- No revision numbers or correction notes appear in the theory text.

## 4. Parallel lines (forks)

- Name both lines and find the premise where they part.
- Classify the fork:
  - **factual:** decided by evidence, testing first logic, then a deciding observation, then explanatory reach;
  - **definitional:** keep both meanings, each under its own concept ID;
  - **normative:** decided by the Principal or the ARB.
- If nothing decides, keep both lines open. Open forks are listed in the theory map.

## 5. Review, after every five sources (amended 2026-10-19T08:55:12Z, v1.2)

**The reviewer reviews the transition, not the whole theory.** It does not start from the beginning each time.

1. The reviewer reads the five new sources **blind**, and that reading is frozen. It reads only those five sources, not the whole book.
2. The Professor sends a **transition brief** with two parts:
   - **before:** what the book held on these topics, as the relevant lines of the theory map and the sections the new text builds on;
   - **new:** what was added or changed: sections, concepts, derivations.
3. The reviewer answers five questions about the **new** part only:
   1. **Does it match what came before?** Is it consistent with the earlier theory, and in order (nothing used before it is defined)?
   2. **Is it valid mathematically?**
   3. **Is it valid statistically?**
   4. **Is it valid computationally?**
   5. **Does the concept need to be explained more clearly?**

   It may disagree, and gives reasons. A finding stands unless it is disproved with evidence.
4. Every report states that its independence is process independence, not personal independence.
5. After the review comes one **theory-only turn**: settle the findings and consolidate the sections.

## 6. Limits

- Only allowed files are opened; sealed or hold-out files never.
- At most **two sources per turn**, then a report.
- The Principal decides the rules and all normative questions.
- Changes apply forward only.

## 7. What was dropped from v1

- the per-source record files and the quote check;
- the long justification in each register entry: the concept register is now an index of ID, term, status and location;
- the per-source rows in the change ledger.

## 8. Open (the Principal's decision)

- When whole-book reviews run.
- Whether the workspace is committed at checkpoints.

## 9. Each turn ends with

What the **theory** gained: definitions, derived results, counterexamples, forks opened or settled, and open questions.

> **Superseded (2026-10-19T09:09:28Z):** the operating rules are now `RULEBOOK-v1.3.md`. This file is kept as history.
