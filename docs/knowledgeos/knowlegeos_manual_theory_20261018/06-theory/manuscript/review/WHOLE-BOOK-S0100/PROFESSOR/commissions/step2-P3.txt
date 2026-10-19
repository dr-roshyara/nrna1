## Step 2: review of your part (P3, Ch. 4 and Ch. 5)

Your inventory is saved. Now open your brief `R/P3/BRIEF.md` in full and the book copy `R/BOOK-UNDER-REVIEW/`. All isolation rules of your commission still bind. **Process independence:** you are an AI session independent of the author as a process, not as a person; say so in one line.

**Scope (Rulebook v1.3 §12c, consolidation mode):** all sections of your part, not only recent changes: definitions, derivations and assumptions; forks; dependencies on other chapters (follow them into the copy where your part cites them); and the **architecture view** (do the primitives lead through the laws to the results; are the primitives complete and minimal; are there isolated, circular or unsupported parts?). The brief's "Changed theory" list is text changed at the last two settlements: review it like the rest, and say for each item whether it holds. Measure against `BOOK-UNDER-REVIEW/06-theory/RESEARCH-PURPOSE.md` (kernel or application, minimal, stable, answers the central question).

**1. Completeness.** Compare your inventory with the part, its map rows and its register rows. Mark each point COVERED / MISSING / DISTORTED / CONTRADICTED / OPEN / JUSTIFIABLY EXCLUDED; rank gaps by theoretical importance. **State that the blind comparison covers only your five sampled sources**; the rest of the part is reviewed non-blind.

**2. Validity.** Attack every concept, definition, derived result and settled fork of the part: definition (two meanings merged?), logic (premises explicit; necessary vs sufficient; converse; circularity; quantifiers), counterexample within scope, mathematics and statistics (n, selection, independence of sources), evidence (does the source support it in meaning; interpretation stated as fact?), consistency and order (conflict with an established result; use before definition), DDD (boundaries and invariants from evidence, or imposed?). Also:
- **trivial cases:** the empty mechanism set, a mechanism that refuses everything, the unchanged state; a model they satisfy lacks must-do requirements;
- **invariant owners:** who guarantees each invariant, by which operation;
- **ubiquitous language:** report every homonym (one term, several meanings), within the part and against chapters it cites;
- **independence and minimality** of primitives and axioms: for each, try to build a model where it alone fails; report independent / derivable / undecided;
- **proof checking:** each claimed Ded step by step; a sketch is reported as a sketch;
- **source lineage:** sources with a shared origin count as one line of support.

**Quote checks.** Check quotes and paraphrases ("in meaning") against the sources; you may open the whole allowed source behind any anchor your part cites. For each finding, say whether it is **source-verified** (you checked the source) or **checked for internal consistency only**.

**3. Refutation adequacy** (entry 55): the reviewer judges each falsification attempt of §2 step 2 as **adequate or inadequate, with the reason**, and gives the strongest counterexample or rival explanation it finds itself. A filled-in line is not proof of serious testing. The Professor puts this paragraph into every review commission until `REVIEWER-INSTRUCTIONS.md` carries it.

Where the book records no falsification attempt for a concept or result, say so.

**4. Extra task: the foundational derivations.** Pick the most foundational derivations of your part (those the most other results rest on; the brief's dependency tables help). For each give: (a) the strongest counterexample within its stated scope; (b) its boundary, where it should stop holding; (c) a materially different rival explanation of the same evidence; (d) its falsifier. Then judge whether the book's own treatment is adequate, and why.

**Verdicts:** VALIDATED (justified within its stated scope) / INVALID (a demonstrated defect defeats it as stated) / PROVISIONAL (plausible, not yet justified), each with a one-line reason and scope. Distinguish disproved, unsupported, unresolved and not yet built. Verdicts only for concepts whose status you actually tested; list the rest as not tested.

**Severity:** **critical** (could invalidate a foundational concept, derivation or chapter conclusion; flag it first) / **high** (a material defect in a result or its support, not foundational) / **normal** (a local issue). Give a one-line reason for each.

**Report** (tables, not prose; up to about 2,000 words before an appendix; lower-priority points in the appendix):
1. Assessment in 3 lines: is the part reliable within its scope; the largest risk.
2. Findings table, ranked: severity · type (theory defect / theory omission / source defect) · claim or location (§, claim number, CON-ID) · source line · what is wrong or missing · consequence · correction · source-verified or internal only.
3. Changed theory: each item of the brief's list, holds or not, with reason.
4. Refutation adequacy and the extra task (4).
5. Concept verdicts; architecture view (primitives → laws → results; independence and minimality).
6. Coverage: ranked gaps; new topics or forks; source errors listed separately.
7. Next steps for the theory in dependency order, each with its completion condition; a question for the Principal only where the theory cannot proceed without one.
8. Limits: what you did not read, check or decide.
9. **The list of every file you opened and every search you ran** (REVIEWER-NOTE item 5), and whether you reached any file in its item 3.

Deliver the report once, through the hand-back. Never accept a conclusion because it is in the book; never treat a search miss as absence; never turn uncertainty into certainty.
