# The Req/A theory turn: commission, step 1 (blind)

*2026-11-01. Three reviewers, independently: **A** (kos-lane-reviewer, Opus), **B** (kos-lane-reviewer, Sonnet), **C** (kos-theory-reviewer). Mode: **topic review** (`03-methodology/REVIEWER-INSTRUCTIONS.md`), for a theory turn that read no new corpus source. W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/` (repository-relative). Use absolute paths under the repository root.*

## Step 1 only: your own answer, before you see the Professor's

There are no new sources, so the blind step is **your own answer to the turn's question**, written from the book sections listed below, before you open anything the Professor wrote for this turn. Hand it back and stop. You will receive step 2 after your step-1 answer has been saved with its sha256.

**The research question (the Principal's commission, verbatim):** "Relative to which requirement set `Req` and which interface assumption `A` is 'a minimal kernel' well posed, and what does the book's evidence establish about it?"

**Task 1 (the Principal's, verbatim):** "state how §9.10 relates to BOOK-OUTLINE.md L179's 'missing' item: which of the six deferring claims (§5.5 C3, §6.5 C1, §9.3, §9.4 C7, §9.5 C1, C5) §9.10 already answers, which in part, and which need new capabilities."

**The two outline lines as they stood before this turn (verbatim; do not open BOOK-OUTLINE.md at this step):**

```
| *Missing:* the derivation from primitives to laws as one chain. **Both consolidation reviewers (S0036–S0065):** the sections in scope add cases to Ch. 1 laws and derive nothing from primitives; minimality of the primitives was never tested | — | — |
| *Missing:* the required capabilities of KnowledgeOS itself (T9-01), **the load-bearing undefined object**: six claims defer to it (§5.5 C3, §6.5 C1, §9.3, §9.4 C7, §9.5 C1, C5); candidates in scope: distribution (§9.5), bootstrap (§9.2), the record and fold (§6.5), Zero-all(EC) reachable or Zero-blocking(EC) reachable (§5.4; which one is open); how to establish a capability (S0164 D5 E1–E10); the state space (F13, T9-02) | — | — |
```

## What to write (about 1–2 pages, tables preferred)

1. **Task 1:** for each of the six claims, and for each other candidate L179 names: your class (answered / in part / needs a new capability), with the book lines that decide it.
2. **The research question:** relative to what is "a minimal kernel" well posed? State the requirement side and the interface side separately (where the system ends; how finely its mechanisms are cut). Which results can the book's evidence support, at what strength, and which cannot be stated yet? Is there a structure condition under which a minimal kernel is unique?
3. **Disproof:** what would refute your answer (insufficiency, redundancy, vacuity, and a granularity or boundary at which a result fails)?
4. **The trivial cases:** the empty mechanism set, refuse-all, the unchanged state: what do they show here?
5. Your own judgments marked [I].

## Files you may open at this step (only these)

- `03-methodology/REVIEWER-INSTRUCTIONS.md`; this brief.
- Book sections: `06-theory/manuscript/chapter_09/` files **09.01 to 09.18 only** (`09.01-*` … `09.18-*`); `chapter_05/05.04-*`, `chapter_05/05.05-*`, `chapter_06/06.05-*`; any other `chapter_0[0-8]/` or `chapter_10/` section a cited line points to; `06-theory/CONCEPT-REGISTER.tsv`; `06-theory/THEORY-MAP.md`; `06-theory/RESEARCH-PURPOSE.md`.
- W's tools in `tmp/tools/` **except any file whose name begins `req_a`**; run tools on a scratch copy only.
- **Not at this step:** `chapter_09/09.19-*`, `chapter_09/09.00-index.md`, `chapter_09/09.99-change-log.md`, `chapter_09/TODO.md`, `06-theory/manuscript/BOOK-OUTLINE.md`, any other file in this review folder.
- **Never:** `00-access/`; any other review folder (restrict every search to the files above; do not search `06-theory/manuscript/` recursively); any other file under `03-methodology/`; `review-architecture/`, `review-implementation/`; S0155, S0156, S0157, S0161, S0170, S0174, S0233, S0249; `docs/knowledge_tranfer/*`; sealed, hold-out or quarantine files; `_batch_input*`; `docs/knowledgeos/chronological-read/*`; `.env*`; any application code. If a file is refused or not allowed, record it and continue; do not inspect it another way.

## Rules (the Principal)

- Keep verified facts, interpretations and recommendations apart; never state more than the evidence supports.
- Edit nothing in the repository. Your scratch directory (outside the repository) is named in your dispatch; use only it, and run scripts with `python3 -I`. No corpus or book text to any outside service.
- **The outside-authors rule:** persons named as people in prose are not reproduced (refer to them by role); eponymous technical terms and bibliographic citations of external works (EXTERNAL-SOURCES.md, EXT-nnn) stay unchanged; paths and filenames are quoted unchanged. Do not reproduce any private person's name.

## Hand-back

Begin with "REQ-A STEP 1 ANSWER <A, B or C>". End with every file you opened and every command and search you ran.
