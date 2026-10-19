# Rulebook v1: KnowledgeOS theory research (one page)

| Field | Value |
|---|---|
| Status | **APPROVED** by the Principal, 2026-10-19T08:31:40Z: "yes approve the rulebook, continue with S0028" |
| Effect if approved | these ten rules are the **operating rules** of the research. The earlier rule documents (M-04 v1.0, the working practice, AR-1…AR-12, CV-1, ML-1/2, SQ-1, RK-1, RK-2, RV-1 and their addenda) stay as **history**, and as the full text behind each rule here. Where they differ, this rulebook governs |
| Applies | from the source after approval; no retroactive change |

## The purpose

Build the theory of KnowledgeOS from the corpus. **The work is the theory**; records and checks serve it and are kept as small as possible.

## The ten rules

1. **Access.** Open only files on the allowed list. Never open sealed, hold-out or quarantine files, or `.env` files. Each review's access scope is recorded.
2. **Reading.** Read each source **in full**, in index order. **At most two sources per turn**, then report.
3. **Theory first.** Before each source, read the **theory map** (`06-theory/THEORY-MAP.md`). For each source, ask:
   - does it **confirm, refute, refine or extend** something in the map?
   - does it **settle** an open fork or question?
   - what **follows** from it that it does not say?
   - what is the **strongest counterexample**?
   - can it be stated in **mathematical, statistical or logical** form?

   Update the map after each source.
4. **Writing.** Change the book **only when the theory changes** (a definition, claim, derivation or open question). Repeats and plain support stay in the source record.
   - No sentence may be stronger than its source.
   - Every claim is labelled: **Src** (the source says it) · **Ded** (derived here) · **Int** (interpretation) · **Hyp** (hypothesis).
5. **Concepts.** Register only the theory's **building blocks**: definitions, premises, results. A concept is VALIDATED only after a **real** attempt to break it, recorded in one line. Otherwise it is INVALID, with what would settle it.
6. **Book form.** One folder per chapter, one file per section. Each section has this order:

   ```
   §n.m.1 In plain language
   §n.m.2 Definitions
   §n.m.3 Claims and derivations   (each labelled; derivations step by step)
   §n.m.4 Open questions
   References                      (table: source · lines · record)
   ```

   Inline citations are short, e.g. [S0026 L49]. Revision numbers and correction notes go to the change ledger, **not** into the theory text.
7. **Parallel lines (forks).** When the theory can go two ways:
   - **Name both** (A and B) and find the exact premise where they part.
   - **Classify the fork:**
     - **factual** (the sources disagree about what is the case): decided by evidence;
     - **definitional** (one word, two meanings): keep both meanings under separate concept IDs;
     - **normative** (what *should* be): decided by the Principal or the ARB, never by the Professor.
   - **For a factual fork, test in order:**
     - logic: a line that contradicts itself or the established theory falls;
     - a deciding observation: a case where A and B predict differently;
     - explanatory reach without extra assumptions.
   - **If nothing decides, keep both** in parallel. Results that depend on the fork are written "if A, then …". A fork closes only by evidence or by decision, never because a later source repeats one side.
   - Open forks are listed in the theory map.
8. **Verification by script.** One script run marks a source read: allowed-list check, fingerprint, quote check, concept-tag check, read-log row. The Professor does no verification by hand. Each source record holds only the anchored quotes the book relies on, plus one line per idea saying what happened to it.
9. **Review.**
   - **Every five sources**, an independent review of the **theory**, in this order: blind reading of the sources (frozen) → the Professor's brief → asked to **refute**, not confirm → findings stand unless disproved with evidence.
   - It covers three areas: conceptual correctness · mathematical derivation · sequential flow.
   - Every review report states that its independence is **process** independence, not personal.
   - After each review, one **theory-only turn**: consolidate the affected sections, derive results, connect chapters, settle forks. No reading in that turn.
   - **Whole-book reviews:** timing **open** (the Principal's decision).
10. **Authority and history.**
    - The Principal decides the rules and all reserved or normative questions.
    - Changes apply forward only.
    - History is kept in one change ledger. Whether the workspace is committed at checkpoints is **open** (the Principal's decision).

## How each turn ends

A short report of what the **theory** gained:
- new or changed definitions;
- derived results;
- counterexamples;
- forks opened or settled;
- open questions.

It does not list files written.

## v1.1 (2026-10-19T08:47:47Z): the core loop, as the Principal wrote it — APPROVED

**Principal (verbatim):** "use ai intelligence-> read corpus file->validated the theory concept->check if already given->if yes->update->if not write new concept in new section/subsection->make reference at end of the file->mark as read->if you cant valid the concept with your intelligence, if you are intelligence say this concept might not valid->write in todos -> you should not care if the wording before and after match. you focus if the theory makes sense and valid . ->you ask reviwer after five files that you wrote these things, do you agree. thats it."

**The loop, per source:**
1. Read the source, with the theory map in mind.
2. For each concept or idea: **is it valid?** Judge it by reasoning: does it make sense, does it survive a counterexample?
3. **Is it already in the book?** If yes, update that section. If no, write it in a new section or subsection.
4. Put the references at the end of the file.
5. Mark the source as read (script: allowed list, fingerprint, read log).
6. If a concept **might not be valid**, say so in the text and write it in the chapter's TODO file.

**After every five sources:** a brief to the reviewer, "I wrote these things; do you agree?", with the reviewer free to disagree and asked to give reasons.

**Dropped from v1:**
- the per-source record (E-files) and the quote check;
- per-concept justification text in the register (the register keeps ID · term · status · where, as an index);
- change-ledger rows per source;
- attention to whether wording matches the source word for word.

**Kept:**
- the allowed-list check;
- the theory map;
- references at the end of each section;
- at most two sources per turn;
- the reviewer's blind first reading (RV-1 safeguard, approved earlier; the Principal may drop it);
- forks handled by rule 7;
- the Principal decides normative questions.

> **Superseded (2026-10-19T08:51:41Z):** the operating rules are now `RULEBOOK-v1.1.md`, which consolidates this file and its §v1.1. This file is kept as history.
